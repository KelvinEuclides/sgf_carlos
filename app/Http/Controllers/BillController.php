<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Bill;
use App\BillItem;
use App\BillPayment;
use App\Category;
use App\Item;
use App\Mail\BillPaymentCreate;
use App\Mail\BillSend;
use App\Transaction;
use App\User;
use App\Utility;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage bill'))
        {

            $vendor = User::where('type', 'vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vendor->prepend('All Vendor', '');

            $status = Bill::$statues;

            $query = Bill::where('created_by', '=', \Auth::user()->creatorId());
            if(!empty($request->vendor))
            {
                $query->where('vendor', '=', $request->vendor);
            }
            if(!empty($request->bill_date))
            {
                $date_range = explode(' - ', $request->bill_date);
                $query->whereBetween('bill_date', $date_range);
            }

            if(!empty($request->status))
            {
                $query->where('status', '=', $request->status);
            }
            $bills = $query->get();

            return view('bill.index', compact('bills', 'vendor', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function create()
    {
        $categories = Category::where('created_by', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        $bill_number = \Auth::user()->billNumberFormat($this->billNumber());

        $vendors = User::where('type', 'vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $vendors->prepend('Select Vendor', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('--', '');

        return view('bill.create', compact('vendors', 'bill_number', 'items', 'categories'));
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create bill'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'vendor' => 'required',
                                   'bill_date' => 'required',
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
            $bill               = new Bill();
            $bill->bill_id      = $this->billNumber();
            $bill->vendor       = $request->vendor;
            $bill->bill_date    = $request->bill_date;
            $bill->status       = 0;
            $bill->due_date     = $request->due_date;
            $bill->category     = $request->category;
            $bill->order_number = !empty($request->order_number) ? $request->order_number : 0;
            $bill->created_by   = \Auth::user()->creatorId();
            $bill->save();
            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
                $billItem              = new BillItem();
                $billItem->bill_id     = $bill->id;
                $billItem->item        = $products[$i]['item'];
                $billItem->quantity    = $products[$i]['quantity'];
                $billItem->tax         = $products[$i]['tax'];
                $billItem->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $billItem->price       = $products[$i]['price'];
                $billItem->description = $products[$i]['description'];
                $billItem->save();
            }

            return redirect()->route('bill.index', $bill->id)->with('success', __('Bill successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        if(\Auth::user()->can('show bill'))
        {
            $id   = \Crypt::decrypt($ids);
            $bill = Bill::find($id);

            $vendor   = $bill->vendor;
            $items    = $bill->items;
            $settings = Utility::settings();
            $status   = Bill::$statues;

            return view('bill.view', compact('bill', 'vendor', 'items', 'settings', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($ids)
    {
        $id         = \Crypt::decrypt($ids);
        $bill       = Bill::find($id);
        $categories = Category::where('created_by', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');


        $vendors = User::where('type', 'vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $vendors->prepend('Select Vendor', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('--', '');

        return view('bill.edit', compact('vendors', 'items', 'bill', 'categories'));
    }


    public function update(Request $request, Bill $bill)
    {
        if(\Auth::user()->can('edit bill'))
        {

            if($bill->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'vendor' => 'required',
                                       'bill_date' => 'required',
                                       'due_date' => 'required',
                                       'items' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('bill.index')->with('error', $messages->first());
                }
                $bill->vendor       = $request->vendor;
                $bill->bill_date    = $request->bill_date;
                $bill->due_date     = $request->due_date;
                $bill->order_number = $request->order_number;
                $bill->category     = $request->category;
                $bill->save();
                $items = $request->items;

                for($i = 0; $i < count($items); $i++)
                {
                    $billItem = BillItem::find($items[$i]['id']);
                    if($billItem == null)
                    {
                        $billItem          = new BillItem();
                        $billItem->bill_id = $bill->id;
                        $billItem->item    = $items[$i]['item'];
                    }

                    if(isset($items[$i]['item']))
                    {
                        $billItem->item = $items[$i]['item'];
                    }

                    $billItem->quantity    = $items[$i]['quantity'];
                    $billItem->tax         = $items[$i]['tax_id'];
                    $billItem->discount    = isset($items[$i]['discount']) ? $items[$i]['discount'] : 0;
                    $billItem->price       = $items[$i]['price'];
                    $billItem->description = $items[$i]['description'];
                    $billItem->save();
                }


                return redirect()->route('bill.index')->with('success', __('Bill successfully updated.'));
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


    public function destroy(Bill $bill)
    {
        if(\Auth::user()->can('delete bill'))
        {
            if($bill->created_by == \Auth::user()->creatorId())
            {
                $bill->delete();
                if($bill->vendor != 0)
                {
                    Utility::userBalance('vendor', $bill->vendor, $bill->getTotal(), 'debit');
                }
                BillItem::where('bill_id', '=', $bill->id)->delete();

                return redirect()->route('bill.index')->with('success', __('Bill successfully deleted.'));
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

    function billNumber()
    {
        $latest = Bill::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->bill_id + 1;
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

        $items = BillItem::where('bill_id', $request->bill_id)->where('item', $request->item_id)->first();

        return json_encode($items);
    }

    public function itemDestroy(Request $request)
    {

        BillItem::where('id', '=', $request->id)->delete();

        return redirect()->back()->with('success', __('Bill item successfully deleted.'));
    }

    public function sent($id)
    {
        if(\Auth::user()->can('send bill'))
        {
            $bill            = Bill::where('id', $id)->first();
            $bill->send_date = date('Y-m-d');
            $bill->status    = 1;
            $bill->save();

            $vendor = User::where('id', $bill->vendor)->first();

            $bill->name = !empty($vendor) ? $vendor->name : '';
            $bill->bill = \Auth::user()->billNumberFormat($bill->bill_id);

            $billId    = Crypt::encrypt($bill->id);
            $bill->url = route('bill.pdf', $billId);

            Utility::userBalance('vendor', $vendor->id, $bill->getTotal(), 'credit');

            try
            {
                Mail::to($vendor->email)->send(new BillSend($bill));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Bill successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function resent($id)
    {
        if(\Auth::user()->can('send bill'))
        {
            $bill = Bill::where('id', $id)->first();

            $vendor = User::where('id', $bill->vendor)->first();

            $bill->name = !empty($vendor) ? $vendor->name : '';
            $bill->bill = \Auth::user()->billNumberFormat($bill->bill_id);

            $billId    = Crypt::encrypt($bill->id);
            $bill->url = route('bill.pdf', $billId);

            try
            {
                Mail::to($vendor->email)->send(new BillSend($bill));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Bill successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function statusChange(Request $request, $id)
    {
        $status       = $request->status;
        $bill         = Bill::find($id);
        $bill->status = $status;
        $bill->save();

        return redirect()->back()->with('success', __('Bill status changed successfully.'));
    }

    public function bill($bill_id)
    {

        $settings = Utility::settings();
        $bill_id  = Crypt::decrypt($bill_id);
        $bill     = Bill::where('id', $bill_id)->first();

        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $bill->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $vendor        = $bill->users;
        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];

        foreach($bill->items as $product)
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

        $bill->items         = $items;
        $bill->totalTaxPrice = $totalTaxPrice;
        $bill->totalQuantity = $totalQuantity;
        $bill->totalRate     = $totalRate;
        $bill->totalDiscount = $totalDiscount;
        $bill->taxesData     = $taxesData;

        //Set your logo
        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo');
        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png'));

        if($bill)
        {
            $color      = '#0e3666';
            $font_color = 'white';

            return view('bill.pdf', compact('bill', 'color', 'settings', 'vendor', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function payment($bill_id)
    {
        if(\Auth::user()->can('create payment bill'))
        {
            $bill = Bill::where('id', $bill_id)->first();

            $vendors    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'vendor')->get()->pluck('name', 'id');
            $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('bill.payment', compact('vendors', 'categories', 'accounts', 'bill'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function createPayment(Request $request, $bill_id)
    {
        if(\Auth::user()->can('create payment bill'))
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

            $billPayment              = new BillPayment();
            $billPayment->bill_id     = $bill_id;
            $billPayment->date        = $request->date;
            $billPayment->amount      = $request->amount;
            $billPayment->account_id  = $request->account_id;
            $billPayment->reference   = $request->reference;
            $billPayment->description = $request->description;
            $billPayment->save();

            $bill  = Bill::where('id', $bill_id)->first();
            $due   = $bill->getDue();
            $total = $bill->getTotal();

            if($bill->status == 0)
            {
                $bill->send_date = date('Y-m-d');
                $bill->save();
            }

            if($due <= 0)
            {
                $bill->status = 4;
                $bill->save();
            }
            else
            {
                $bill->status = 3;
                $bill->save();
            }
            $billPayment->user_id    = $bill->vendor;
            $billPayment->user_type  = 'Vendor';
            $billPayment->type       = 'Partial';
            $billPayment->created_by = \Auth::user()->id;
            $billPayment->payment_id = $billPayment->id;
            $billPayment->category   = 'Bill';
            $billPayment->account    = $request->account_id;
            Transaction::addTransaction($billPayment);

            $vendor = Vendor::where('user_id', $bill->vendor_id)->first();

            $payment         = new BillPayment();
            $payment->name   = $vendor['name'];
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = 'bill ' . \Auth::user()->billNumberFormat($billPayment->bill_id);

            Utility::userBalance('vendor', $bill->vendor, $request->amount, 'debit');

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            try
            {
                Mail::to($vendor['email'])->send(new BillPaymentCreate($payment));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Bill payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }

    }

    public function paymentDestroy(Request $request, $bill_id, $payment_id)
    {

        if(\Auth::user()->can('delete payment bill'))
        {
            $payment = BillPayment::find($payment_id);
            BillPayment::where('id', '=', $payment_id)->delete();

            $bill = Bill::where('id', $bill_id)->first();

            $due   = $bill->getDue();
            $total = $bill->getTotal();

            if($due > 0 && $total != $due)
            {
                $bill->status = 3;

            }
            else
            {
                $bill->status = 2;
            }

            Utility::userBalance('vendor', $bill->vendor, $payment->amount, 'credit');
            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $bill->save();
            $type = 'Partial';
            $user = 'Vender';
            Transaction::destroyTransaction($payment_id, $type, $user);

            return redirect()->back()->with('success', __('Payment successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function vendorBill(Request $request)
    {
        if(\Auth::user()->type == 'vendor')
        {

            $status = Bill::$statues;

            $query = Bill::where('vendor', '=', \Auth::user()->id)->where('status', '!=', '0')->where('created_by', \Auth::user()->creatorId());


            if(!empty($request->bill_date))
            {
                $date_range = explode(' - ', $request->bill_date);
                $query->whereBetween('bill_date', $date_range);
            }

            if(!empty($request->status))
            {
                $query->where('status', '=', $request->status);
            }
            $bills = $query->get();


            return view('bill.vendor', compact('bills', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function vendorBillShow($ids)
    {
        if(\Auth::user()->type == 'vendor')
        {
            $id   = \Crypt::decrypt($ids);
            $bill = Bill::find($id);

            $vendor   = $bill->vendor;
            $items    = $bill->items;
            $settings = Utility::settings();
            $status   = Bill::$statues;

            return view('bill.vendor_view', compact('bill', 'vendor', 'items', 'settings', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
