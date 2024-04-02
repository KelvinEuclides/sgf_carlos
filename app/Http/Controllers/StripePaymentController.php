<?php

namespace App\Http\Controllers;


use App\Coupon;
use App\Invoice;
use App\InvoicePayment;
use App\Order;
use App\Plan;
use App\Subscription;
use App\Transaction;
use App\UserCoupon;
use App\UserSubscriber;
use App\UserVoucher;
use App\Utility;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
    public $settings;


    public function index()
    {
        $objUser = \Auth::user();
        if($objUser->type == 'super admin')
        {
            $subscribers = UserSubscriber::select(
                [
                    'user_subscribers.*',
                    'users.name as user_name',
                ]
            )->join('users', 'user_subscribers.user_id', '=', 'users.id')->orderBy('user_subscribers.created_at', 'DESC')->get();
        }
        else
        {
            $subscribers = UserSubscriber::select(
                [
                    'user_subscribers.*',
                    'users.name as user_name',
                ]
            )->join('users', 'user_subscribers.user_id', '=', 'users.id')->orderBy('user_subscribers.created_at', 'DESC')->where('users.id', '=', $objUser->id)->get();
        }

        return view('subscriber.index', compact('subscribers'));
    }


    public function stripe($code)
    {

        $subscription_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
        $subscription    = Subscription::find($subscription_id);
        if($subscription)
        {
            return view('subscription/stripe', compact('subscription'));
        }
        else
        {
            return redirect()->back()->with('error', __('Subscripption is deleted.'));
        }
    }


    public function stripePost(Request $request)
    {

        $objUser = \Auth::user();
        $subscriptionID  = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $subscription    = Subscription::find($subscriptionID);

        if($subscription)
        {
            try
            {
                $price = $subscription->price;
                if(!empty($request->voucher))
                {
                    $voucher = Voucher::where('code', strtoupper($request->voucher))->where('is_active', '1')->first();
                    if(!empty($voucher))
                    {
                        $userVoucher     = $voucher->used_voucher();
                        $discount_value = ($subscription->price / 100) * $voucher->discount;
                        $price          = $subscription->price - $discount_value;

                        if($voucher->limit == $userVoucher)
                        {
                            return redirect()->back()->with('error', __('This voucher code has expired.'));
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('This voucher code is invalid or has expired.'));
                    }
                }

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                if($price > 0.0)
                {
                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => env('CURRENCY'),
                            "source" => $request->stripeToken,
                            "description" => " Subscription - " . $subscription->name,
                            "metadata" => ["order_id" => $orderID],
                        ]
                    );
                }
                else
                {
                    $data['amount_refunded'] = 0;
                    $data['failure_code']    = '';
                    $data['paid']            = 1;
                    $data['captured']        = 1;
                    $data['status']          = 'succeeded';


                }


                if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                {

                    UserSubscriber::create(
                        [
                            'order_id' => $orderID,
                            'name' => $request->name,
                            'card_number' => isset($data['payment_method_details']['card']['last4']) ? $data['payment_method_details']['card']['last4'] : '',
                            'card_exp_month' => isset($data['payment_method_details']['card']['exp_month']) ? $data['payment_method_details']['card']['exp_month'] : '',
                            'card_exp_year' => isset($data['payment_method_details']['card']['exp_year']) ? $data['payment_method_details']['card']['exp_year'] : '',
                            'subscription' => $subscription->name,
                            'subscription_id' => $subscription->id,
                            'price' => $price,
                            'price_currency' => env('CURRENCY'),
                            'txn_id' => isset($data['balance_transaction']) ? $data['balance_transaction'] : '',
                            'payment_type' => __('STRIPE'),
                            'payment_status' => isset($data['status']) ? $data['status'] : 'succeeded',
                            'receipt' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                            'user_id' => $objUser->id,
                        ]
                    );

                    if(!empty($request->voucher))
                    {
                        $userVoucher         = new UserVoucher();
                        $userVoucher->user   = $objUser->id;
                        $userVoucher->coupon = $voucher->id;
                        $userVoucher->order  = $orderID;
                        $userVoucher->save();

                        $userVoucher = $voucher->used_voucher();
                        if($voucher->limit <= $userVoucher)
                        {
                            $voucher->is_active = 0;
                            $voucher->save();
                        }

                    }
                    if($data['status'] == 'succeeded')
                    {
                        $assignSubscription = $objUser->assignSubscription($subscription->id);
                        if($assignSubscription['is_success'])
                        {
                            return redirect()->route('subscription.index')->with('success', __('Subscription successfully activated.'));
                        }
                        else
                        {
                            return redirect()->route('subscription.index')->with('error', __($assignSubscription['error']));
                        }
                    }
                    else
                    {
                        return redirect()->route('subscription.index')->with('error', __('Your payment has failed.'));
                    }
                }
                else
                {
                    return redirect()->route('subscription.index')->with('error', __('Transaction has been failed.'));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('subscription.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return redirect()->route('subscription.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function addPayment(Request $request, $id)
    {
        $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');

        $objUser = \Auth::user();
        $invoice = Invoice::find($id);

        if($invoice)
        {
            if($request->amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                try
                {
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $price   = $request->amount;
                    Stripe\Stripe::setApiKey(Utility::getValByName('stripe_secret'));
                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => Utility::getValByName('site_currency'),
                            "source" => $request->stripeToken,
                            "description" => 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                            "metadata" => ["order_id" => $orderID],
                        ]
                    );

                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {
                        $payments = InvoicePayment::create(
                            [

                                'invoice_id' => $invoice->id,
                                'date' => date('Y-m-d'),
                                'amount' => $price,
                                'account_id' => 0,
                                'payment_method' => 0,
                                'order_id' => $orderID,
                                'currency' => $data['currency'],
                                'txn_id' => $data['balance_transaction'],
                                'payment_type' => __('STRIPE'),
                                'receipt' => $data['receipt_url'],
                                'reference' => '',
                                'description' => 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                            ]
                        );

                        if($invoice->getDue() <= 0)
                        {
                            $invoice->status = 4;
                        }
                        elseif(($invoice->getDue() - $request->amount) == 0)
                        {
                            $invoice->status = 4;
                        }
                        else
                        {
                            $invoice->status = 3;
                        }
                        $invoice->save();

                        $invoicePayment              = new Transaction();
                        $invoicePayment->user_id     = $invoice->customer_id;
                        $invoicePayment->user_type   = 'Customer';
                        $invoicePayment->type        = 'STRIPE';
                        $invoicePayment->created_by  = \Auth::user()->id;
                        $invoicePayment->payment_id  = $invoicePayment->id;
                        $invoicePayment->category    = 'Invoice';
                        $invoicePayment->amount      = $price;
                        $invoicePayment->date        = date('Y-m-d');
                        $invoicePayment->created_by  = \Auth::user()->creatorId();
                        $invoicePayment->payment_id  = $payments->id;
                        $invoicePayment->description = 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                        $invoicePayment->account     = 0;
                        Transaction::addTransaction($invoicePayment);

                        Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');

                        Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

                        return redirect()->back()->with('success', __(' Payment successfully added.'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Transaction has been failed.'));
                    }
                }
                catch(\Exception $e)
                {
                    return redirect()->route(
                        'customer.invoice.show', $invoice->id
                    )->with('error', __($e->getMessage()));
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
