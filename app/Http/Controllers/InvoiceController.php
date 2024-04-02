<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Category;
use App\Customer;
use App\Invoice;
use App\InvoiceItem;
use App\InvoicePayment;
use App\Item;
use App\Mail\InvoicePaymentCreate;
use App\Mail\InvoiceSend;
use App\Transaction;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        $customer = User::where('created_by', '=', \Auth::user()->creatorId())->where('type','customer')->get()->pluck('name', 'id');
        $customer->prepend('All Customer', '');
        $status = Invoice::$statues;

        $query    = Invoice::where('created_by', '=', \Auth::user()->creatorId());
        if(!empty($request->customer))
        {
            $query->where('customer_id', '=', $request->customer);
        }
        if(!empty($request->issue_date))
        {
            $date_range = explode(' - ', $request->issue_date);
            $query->whereBetween('issue_date', $date_range);
        }

        if(!empty($request->status))
        {
            $query->where('status', '=', $request->status);
        }
        $invoices = $query->get();

        return view('invoice.index', compact('invoices', 'status','customer'));
    }


    public function create()
    {
        $customers = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $customers->prepend('Select Customer', '');


        $categories = Category::where('type', 'income')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('Select Item', '');
        $invoiceId = $this->invoiceNumber();

        return view('invoice.create', compact('customers', 'categories', 'invoiceId', 'items'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create invoice'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'customer' => 'required',
                                   'issue_date' => 'required',
                                   'due_date' => 'required',
                                   'category' => 'required',
                                   'items' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $status = Invoice::$statues;

            $invoice             = new Invoice();
            $invoice->invoice_id = $this->invoiceNumber();
            $invoice->customer   = $request->customer;
            $invoice->status     = 0;
            $invoice->issue_date = $request->issue_date;
            $invoice->due_date   = $request->due_date;
            $invoice->category   = $request->category;
            $invoice->ref_number = $request->ref_number;
            $invoice->created_by = \Auth::user()->creatorId();
            $invoice->save();
            $items = $request->items;

            for($i = 0; $i < count($items); $i++)
            {
                $invoiceItem              = new InvoiceItem();
                $invoiceItem->invoice_id  = $invoice->id;
                $invoiceItem->item        = $items[$i]['item'];
                $invoiceItem->quantity    = $items[$i]['quantity'];
                $invoiceItem->tax         = $items[$i]['tax'];
                $invoiceItem->discount    = isset($items[$i]['discount']) ? $items[$i]['discount'] : 0;
                $invoiceItem->price       = $items[$i]['price'];
                $invoiceItem->description = $items[$i]['description'];

                $invoiceItem->save();
            }

            return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        $id=Crypt::decrypt($ids);
        $invoice=Invoice::find($id);
        $settings = Utility::settings();

        $status = Invoice::$statues;

        return view('invoice.view', compact('invoice', 'settings', 'status'));
    }


    public function edit($ids)
    {
        $id=Crypt::decrypt($ids);
        $invoice=Invoice::find($id);
        $customers = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $customers->prepend('Select Customer', '');


        $categories = Category::where('type', 'income')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('Select Item', '');

        return view('invoice.edit', compact('customers', 'categories', 'items', 'invoice'));
    }


    public function update(Request $request, Invoice $invoice)
    {
        if(\Auth::user()->can('edit invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'customer' => 'required',
                                       'issue_date' => 'required',
                                       'due_date' => 'required',
                                       'category' => 'required',
                                       'items' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoice.index')->with('error', $messages->first());
                }

                $invoice->customer   = $request->customer;
                $invoice->issue_date = $request->issue_date;
                $invoice->due_date   = $request->due_date;
                $invoice->category   = $request->category;
                $invoice->save();
                $items = $request->items;

                for($i = 0; $i < count($items); $i++)
                {
                    $invoiceItem = InvoiceItem::find($items[$i]['id']);
                    if($invoiceItem == null)
                    {
                        $invoiceItem             = new InvoiceItem();
                        $invoiceItem->invoice_id = $invoice->id;
                    }

                    if(isset($items[$i]['item']))
                    {
                        $invoiceItem->item = $items[$i]['item'];
                    }

                    $invoiceItem->quantity    = $items[$i]['quantity'];
                    $invoiceItem->tax         = $items[$i]['tax_id'];
                    $invoiceItem->discount    = isset($items[$i]['discount']) ? $items[$i]['discount'] : 0;
                    $invoiceItem->price       = $items[$i]['price'];
                    $invoiceItem->description = $items[$i]['description'];
                    $invoiceItem->save();
                }

                return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));

            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Invoice $invoice)
    {
        if(\Auth::user()->can('delete invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $invoice->delete();
                if($invoice->customer_id != 0)
                {
                    Utility::userBalance('customer', $invoice->customer_id, $invoice->getTotal(), 'debit');
                }
                InvoiceItem::where('invoice_id', '=', $invoice->id)->delete();

                return redirect()->route('invoice.index')->with('success', __('Invoice successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function product(Request $request)
    {

        $data['item']        = $item = Item::find($request->item_id);
        $data['unit']        = (!empty($item->units)) ? $item->units->name : '';
        $data['taxRate']     = $taxRate = $item->taxRate($item->tax);
        $data['taxes']       = $item->taxes($item->tax);
        $salePrice           = $item->sale_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }

    public function item(Request $request)
    {

        $items = InvoiceItem::where('invoice_id', $request->invoice_id)->where('item', $request->item_id)->first();

        return json_encode($items);
    }

    public function itemDestroy(Request $request)
    {

        InvoiceItem::where('invoice_id', '=', $request->id)->delete();

        return redirect()->back()->with('success', __('Invoice item successfully deleted.'));
    }

    public function sent($id)
    {
        if(\Auth::user()->can('send invoice'))
        {
            $invoice            = Invoice::where('id', $id)->first();
            $invoice->send_date = date('Y-m-d');
            $invoice->status    = 1;
            $invoice->save();

            $customer         = User::where('id', $invoice->customer)->first();
            $invoice->name    = !empty($customer) ? $customer->name : '';
            $invoice->invoice = \Auth::user()->invoiceNumberFormat($invoice->invoice_id);

            $invoiceId    = \Crypt::encrypt($invoice->id);
            $invoice->url = route('invoice.pdf', $invoiceId);
            try
            {
                Mail::to($customer->email)->send(new InvoiceSend($invoice));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function resent($id)
    {
        if(\Auth::user()->can('send invoice'))
        {
            $invoice = Invoice::where('id', $id)->first();

            $customer         = User::where('id', $invoice->customer)->first();
            $invoice->name    = !empty($customer) ? $customer->name : '';
            $invoice->invoice = \Auth::user()->invoiceNumberFormat($invoice->invoice_id);

            $invoiceId    = \Crypt::encrypt($invoice->id);
            $invoice->url = route('invoice.pdf', $invoiceId);
            try
            {
                Mail::to($customer->email)->send(new InvoiceSend($invoice));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function statusChange(Request $request, $id)
    {
        $status          = $request->status;
        $invoice         = Invoice::find($id);
        $invoice->status = $status;
        $invoice->save();

        return redirect()->back()->with('success', __('Invoice status changed successfully.'));
    }

    public function invoice($invoice_id)
    {

        $settings   = Utility::settings();
        $invoice_id = Crypt::decrypt($invoice_id);
        $invoice    = Invoice::where('id', $invoice_id)->first();

        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $invoice->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $customer      = $invoice->users;
        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];

        foreach($invoice->items as $product)
        {

            $item           = new \stdClass();
            $item->name     = !empty($product->items) ? $product->items->name : '';
            $item->quantity = $product->quantity;
            $item->tax      = $product->tax;
            $item->discount = $product->discount;
            $item->price    = $product->price;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes = \Utility::tax($product->tax);

            $itemTaxes = [];
            foreach($taxes as $tax)
            {
                $taxPrice      = \Utility::taxRate($tax->rate, $item->price, $item->quantity);
                $totalTaxPrice += $taxPrice;

                $itemTax['name']  = $tax->name;
                $itemTax['rate']  = $tax->rate . '%';
                $itemTax['price'] = \App\Utility::priceFormat($settings, $taxPrice);
                $itemTaxes[]      = $itemTax;


                if(array_key_exists($tax->name, $taxesData))
                {
                    $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                }
                else
                {
                    $taxesData[$tax->name] = $taxPrice;
                }

            }

            $item->itemTax = $itemTaxes;
            $items[]       = $item;


        }

        $invoice->items         = $items;
        $invoice->totalTaxPrice = $totalTaxPrice;
        $invoice->totalQuantity = $totalQuantity;
        $invoice->totalRate     = $totalRate;
        $invoice->totalDiscount = $totalDiscount;
        $invoice->taxesData     = $taxesData;

        //Set your logo
        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo');
        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png'));

        if($invoice)
        {
            $color      = '#0e3666';
            $font_color = 'white';

            return view('invoice.pdf', compact('invoice', 'color', 'settings', 'customer', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function payment($invoice_id)
    {
        if(\Auth::user()->can('create payment invoice'))
        {
            $invoice = Invoice::where('id', $invoice_id)->first();

            $customers  = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
            $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'income')->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('invoice.payment', compact('customers', 'categories', 'accounts', 'invoice'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function createPayment(Request $request, $invoice_id)
    {
        if(\Auth::user()->can('create payment invoice'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoicePayment              = new InvoicePayment();
            $invoicePayment->invoice_id  = $invoice_id;
            $invoicePayment->date        = $request->date;
            $invoicePayment->amount      = $request->amount;
            $invoicePayment->account_id  = $request->account_id;
            $invoicePayment->reference   = $request->reference;
            $invoicePayment->description = $request->description;
            $invoicePayment->save();

            $invoice = Invoice::where('id', $invoice_id)->first();
            $due     = $invoice->getDue();
            $total   = $invoice->getTotal();
            if($invoice->status == 0)
            {
                $invoice->send_date = date('Y-m-d');
                $invoice->save();
            }

            if($due <= 0)
            {
                $invoice->status = 4;
                $invoice->save();
            }
            else
            {
                $invoice->status = 3;
                $invoice->save();
            }
            $invoicePayment->user_id    = $invoice->customer;
            $invoicePayment->user_type  = 'Customer';
            $invoicePayment->type       = 'Partial';
            $invoicePayment->created_by = \Auth::user()->id;
            $invoicePayment->payment_id = $invoicePayment->id;
            $invoicePayment->category   = 'Invoice';
            $invoicePayment->account    = $request->account_id;

            Transaction::addTransaction($invoicePayment);

            $customer = User:: where('id', $invoice->customer)->first();

            $payment            = new InvoicePayment();
            $payment->name      = $customer['name'];
            $payment->date      = \Auth::user()->dateFormat($request->date);
            $payment->amount    = \Auth::user()->priceFormat($request->amount);
            $payment->invoice   = 'invoice ' . \Auth::user()->invoiceNumberFormat($invoice->invoice_id);
            $payment->dueAmount = \Auth::user()->priceFormat($invoice->getDue());

            Utility::userBalance('customer', $invoice->customer, $request->amount, 'debit');

            Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

            try
            {
                Mail::to($customer['email'])->send(new InvoicePaymentCreate($payment));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }

    }

    public function paymentDestroy(Request $request, $invoice_id, $payment_id)
    {

        if(\Auth::user()->can('delete payment invoice'))
        {
            $payment = InvoicePayment::find($payment_id);

            InvoicePayment::where('id', '=', $payment_id)->delete();

            $invoice = Invoice::where('id', $invoice_id)->first();
            $due     = $invoice->getDue();
            $total   = $invoice->getTotal();

            if($due > 0 && $total != $due)
            {
                $invoice->status = 3;

            }
            else
            {
                $invoice->status = 2;
            }

            $invoice->save();
            $type = 'Partial';
            $user = 'Customer';
            Transaction::destroyTransaction($payment_id, $type, $user);

            Utility::userBalance('customer', $invoice->customer, $payment->amount, 'credit');

            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'debit');

            return redirect()->back()->with('success', __('Payment successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerInvoice(Request $request)
    {
        $status = Invoice::$statues;

        $query = Invoice::where('customer', '=', \Auth::user()->id)->where('status', '!=', '0')->where('created_by', \Auth::user()->creatorId());

        if(!empty($request->issue_date))
        {
            $date_range = explode(' - ', $request->issue_date);
            $query->whereBetween('issue_date', $date_range);
        }

        if(!empty($request->status))
        {
            $query->where('status', '=', $request->status);
        }
        $invoices = $query->get();

        return view('invoice.customer', compact('invoices', 'status'));
    }

    public function customerInvoiceShow($ids)
    {
        $id=Crypt::decrypt($ids);
        $invoice=Invoice::find($id);
        $settings = Utility::settings();

        $status = Invoice::$statues;

        return view('invoice.customer_view', compact('invoice', 'settings', 'status'));
    }
}
