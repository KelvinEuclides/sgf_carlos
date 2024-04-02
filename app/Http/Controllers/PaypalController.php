<?php

namespace App\Http\Controllers;


use App\Subscription;
use App\UserSubscriber;
use App\UserVoucher;
use App\Utility;
use App\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    private $_api_context;

    public function setApiContext()
    {
        $user = Auth::user();

        $paypal_conf = config('paypal');

        if($user->type == 'company')
        {
            $paypal_conf['settings']['mode'] = env('PAYPAL_MODE');
        }
        else
        {
            $paypal_conf['settings']['mode'] = Utility::getValByName('paypal_mode');
            $paypal_conf['client_id']        = Utility::getValByName('paypal_client_id');
            $paypal_conf['secret_key']       = Utility::getValByName('paypal_secret_key');
        }

        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'], $paypal_conf['secret_key']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    public function subscriptionPayWithPaypal(Request $request)
    {

        $subscriptionID = \Illuminate\Support\Facades\Crypt::decrypt($request->subscription_id);
        $subcription    = Subscription::find($subscriptionID);

        if($subcription)
        {
            try
            {
                $voucher_id = null;
                $price      = $subcription->price;
                if(!empty($request->voucher))
                {
                    $vouchers = Voucher::where('code', strtoupper($request->voucher))->where('is_active', '1')->first();
                    if(!empty($vouchers))
                    {
                        $usedVoucher    = $vouchers->used_voucher();
                        $discount_value = ($subcription->price / 100) * $vouchers->discount;
                        $price          = $subcription->price - $discount_value;
                        if($vouchers->limit == $usedVoucher)
                        {
                            return redirect()->back()->with('error', __('This voucher has expired.'));
                        }
                        $voucher_id = $vouchers->id;
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('This voucher code is invalid or has expired.'));
                    }
                }

                $this->setApiContext();
                $name  = $subcription->name;
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();
                $item_1->setName($name)->setCurrency(env('CURRENCY'))->setQuantity(1)->setPrice($price);
                $item_list = new ItemList();
                $item_list->setItems([$item_1]);
                $amount = new Amount();
                $amount->setCurrency(env('CURRENCY'))->setTotal($price);
                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($item_list)->setDescription($name);
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(
                    route(
                        'subscription.get.payment.status', [
                                                             $subcription->id,
                                                             'voucher_id' => $voucher_id,
                                                         ]
                    )
                )->setCancelUrl(
                    route(
                        'subscription.get.payment.status', [
                                                             $subcription->id,
                                                             'voucher_id' => $voucher_id,
                                                         ]
                    )
                );
                $payment = new Payment();
                $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions([$transaction]);

                try
                {
                    $payment->create($this->_api_context);

                }
                catch(\PayPal\Exception\PayPalConnectionException $ex) //PPConnectionException
                {

                    if(config('app.debug'))
                    {
                        return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($subcription->id))->with('error', __('Connection timeout'));
                    }
                    else
                    {
                        return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($subcription->id))->with('error', __('Some error occur, sorry for inconvenient'));
                    }
                }
                foreach($payment->getLinks() as $link)
                {
                    if($link->getRel() == 'approval_url')
                    {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }
                Session::put('paypal_payment_id', $payment->getId());
                if(isset($redirect_url))
                {
                    return Redirect::away($redirect_url);
                }

                return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($subcription->id))->with('error', __('Unknown error occurred'));
            }
            catch(\Exception $e)
            {
                return redirect()->route('subscription.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return redirect()->route('subscription.index')->with('error', __('Subscription is deleted.'));
        }
    }

    public function subscriptionGetPaymentStatus(Request $request, $subcription_id)
    {
        $user        = Auth::user();
        $subcription = Subscription::find($subcription_id);
        if($subcription)
        {
            $this->setApiContext();
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if(empty($request->PayerID || empty($request->token)))
            {
                return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($subcription->id))->with('error', __('Payment failed'));
            }
            $payment   = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            try
            {
                $result  = $payment->execute($execution, $this->_api_context)->toArray();
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $status  = ucwords(str_replace('_', ' ', $result['state']));
                if($request->has('voucher_id') && $request->voucher_id != '')
                {
                    $vouchers = Voucher::find($request->voucher_id);
                    if(!empty($vouchers))
                    {
                        $userVoucher          = new UserVoucher();
                        $userVoucher->user    = $user->id;
                        $userVoucher->voucher = $vouchers->id;
                        $userVoucher->order   = $orderID;
                        $userVoucher->save();
                        $usedVoucher = $vouchers->voucher();
                        if($vouchers->limit <= $usedVoucher)
                        {
                            $vouchers->is_active = 0;
                            $vouchers->save();
                        }
                    }
                }
                if($result['state'] == 'approved')
                {

                    $order                  = new UserSubscriber();
                    $order->order_id        = $orderID;
                    $order->name            = $user->name;
                    $order->card_number     = '';
                    $order->card_exp_month  = '';
                    $order->card_exp_year   = '';
                    $order->subscription    = $subcription->name;
                    $order->subscription_id = $subcription->id;
                    $order->price           = $result['transactions'][0]['amount']['total'];
                    $order->price_currency  = env('CURRENCY');
                    $order->txn_id          = $payment_id;
                    $order->payment_type    = __('PAYPAL');
                    $order->payment_status  = $result['state'];
                    $order->receipt         = '';
                    $order->user_id         = $user->id;
                    $order->save();
                    $assignSubscription = $user->assignSubscription($subcription->id);
                    if($assignSubscription['is_success'])
                    {
                        return redirect()->route('subscription.index')->with('success', __('Subscription activated Successfully.'));
                    }
                    else
                    {
                        return redirect()->route('subscription.index')->with('error', __($assignSubscription['error']));
                    }
                }
                else
                {
                    return redirect()->route('subscription.index')->with('error', __('Transaction has been ' . __($status)));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('subscription.index')->with('error', __('Transaction has been failed.'));
            }
        }
        else
        {
            return redirect()->route('subscription.index')->with('error', __('Subscription is deleted.'));
        }
    }

}
