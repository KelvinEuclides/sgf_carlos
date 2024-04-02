<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Estimation;
use App\EstimationItem;
use App\Item;
use App\Mail\EstimationSend;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EstimationController extends Controller
{

    public function index(Request $request)
    {
        $customer = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'customer')->get()->pluck('name', 'id');
        $customer->prepend('All Customer', '');

        $status = Estimation::$statues;

        $query = Estimation::where('created_by', '=', \Auth::user()->creatorId());

        if(!empty($request->customer))
        {
            $query->where('customer', '=', $request->customer);
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
        $estimations = $query->get();

        return view('estimation.index', compact('estimations', 'status', 'customer'));
    }


    public function create()
    {
        $customers = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $customers->prepend('Select Customer', '');


        $categories = Category::where('type', 'income')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('Select Item', '');
        $estimateId = $this->estimationNumber();

        return view('estimation.create', compact('customers', 'categories', 'estimateId', 'items'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create estimation'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'customer' => 'required',
                                   'issue_date' => 'required',
                                   'category' => 'required',
                                   'items' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $status = Estimation::$statues;

            $estimation                = new Estimation();
            $estimation->estimation_id = $this->estimationNumber();
            $estimation->customer      = $request->customer;
            $estimation->status        = 0;
            $estimation->issue_date    = $request->issue_date;
            $estimation->category      = $request->category;
            $estimation->created_by    = \Auth::user()->creatorId();
            $estimation->save();
            $items = $request->items;

            for($i = 0; $i < count($items); $i++)
            {
                $estimationItem                = new EstimationItem();
                $estimationItem->estimation_id = $estimation->id;
                $estimationItem->item          = $items[$i]['item'];
                $estimationItem->quantity      = $items[$i]['quantity'];
                $estimationItem->tax           = $items[$i]['tax'];
                $estimationItem->discount      = isset($items[$i]['discount']) ? $items[$i]['discount'] : 0;
                $estimationItem->price         = $items[$i]['price'];
                $estimationItem->description   = $items[$i]['description'];

                $estimationItem->save();
            }

            return redirect()->route('estimation.index', $estimation->id)->with('success', __('Estimation successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($eid)
    {
        $id         = Crypt::decrypt($eid);
        $estimation = Estimation::find($id);
        $settings   = Utility::settings();

        $status = Estimation::$statues;

        return view('estimation.view', compact('estimation', 'settings', 'status'));
    }


    public function edit($ids)
    {
        $id         = \Crypt::decrypt($ids);
        $estimation = Estimation::find($id);

        $customers = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $customers->prepend('Select Customer', '');


        $categories = Category::where('type', 'income')->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        $items = Item::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $items->prepend('Select Item', '');

        return view('estimation.edit', compact('customers', 'categories', 'items', 'estimation'));
    }

    public function update(Request $request, Estimation $estimation)
    {
        if(\Auth::user()->can('edit estimation'))
        {
            if($estimation->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'customer' => 'required',
                                       'issue_date' => 'required',
                                       'category' => 'required',
                                       'items' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('estimation.index')->with('error', $messages->first());
                }

                $estimation->customer   = $request->customer;
                $estimation->issue_date = $request->issue_date;
                $estimation->category   = $request->category;
                $estimation->save();
                $items = $request->items;

                for($i = 0; $i < count($items); $i++)
                {
                    $estimationItem = EstimationItem::find($items[$i]['id']);
                    if($estimationItem == null)
                    {
                        $estimationItem                = new EstimationItem();
                        $estimationItem->estimation_id = $estimation->id;
                    }

                    if(isset($items[$i]['item']))
                    {
                        $estimationItem->item = $items[$i]['item'];
                    }

                    $estimationItem->quantity    = $items[$i]['quantity'];
                    $estimationItem->tax         = $items[$i]['tax'];
                    $estimationItem->discount    = isset($items[$i]['discount']) ? $items[$i]['discount'] : 0;
                    $estimationItem->price       = $items[$i]['price'];
                    $estimationItem->description = $items[$i]['description'];
                    $estimationItem->save();
                }

                return redirect()->route('estimation.index', $estimation->id)->with('success', __('Estimation successfully created.'));

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


    public function destroy(Estimation $estimation)
    {
        //
    }

    function estimationNumber()
    {
        $latest = Estimation::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->estimation_id + 1;
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

        $items = EstimationItem::where('estimation_id', $request->estimation_id)->where('item', $request->item_id)->first();

        return json_encode($items);
    }

    public function itemDestroy(Request $request)
    {

        EstimationItem::where('id', '=', $request->id)->delete();

        return redirect()->back()->with('success', __('Estimation item successfully deleted.'));
    }

    public function sent($id)
    {
        if(\Auth::user()->can('send estimation'))
        {
            $estimation            = Estimation::where('id', $id)->first();
            $estimation->send_date = date('Y-m-d');
            $estimation->status    = 1;
            $estimation->save();

            $customer               = User::where('id', $estimation->customer)->first();
            $estimation->name       = !empty($customer) ? $customer->name : '';
            $estimation->estimation = \Auth::user()->estimationNumberFormat($estimation->estimation_id);

            $estimationId    = \Crypt::encrypt($estimation->id);
            $estimation->url = route('estimation.pdf', $estimationId);

            try
            {
                Mail::to($customer->email)->send(new EstimationSend($estimation));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->back()->with('success', __('Estimation successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function resent($id)
    {
        if(\Auth::user()->can('send estimation'))
        {
            $estimation = Estimation::where('id', $id)->first();

            $customer             = User::where('id', $estimation->customer)->first();
            $estimation->name     = !empty($customer) ? $customer->name : '';
            $estimation->proposal = \Auth::user()->estimationNumberFormat($estimation->estimation_id);

            $estimationId    = \Crypt::encrypt($estimation->id);
            $estimation->url = route('estimation.pdf', $estimationId);
            try
            {
                Mail::to($customer->email)->send(new EstimationSend($estimation));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }


            return redirect()->back()->with('success', __('Estimation successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function statusChange(Request $request, $id)
    {
        $status             = $request->status;
        $estimation         = Estimation::find($id);
        $estimation->status = $status;
        $estimation->save();

        return redirect()->back()->with('success', __('Estimation status changed successfully.'));
    }

    public function estimation($estimation_id)
    {

        $settings      = Utility::settings();
        $estimation_id = Crypt::decrypt($estimation_id);
        $estimation    = Estimation::where('id', $estimation_id)->first();

        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $estimation->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $customer      = $estimation->users;
        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];

        foreach($estimation->items as $product)
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

        $estimation->items         = $items;
        $estimation->totalTaxPrice = $totalTaxPrice;
        $estimation->totalQuantity = $totalQuantity;
        $estimation->totalRate     = $totalRate;
        $estimation->totalDiscount = $totalDiscount;
        $estimation->taxesData     = $taxesData;

        //Set your logo
        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo');
        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png'));

        if($estimation)
        {
            $color      = '#0e3666';
            $font_color = 'white';

            return view('estimation.pdf', compact('estimation', 'color', 'settings', 'customer', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function customerEstimation(Request $request)
    {
        if(\Auth::user()->type == 'customer')
        {

            $status = Estimation::$statues;

            $query = Estimation::where('customer', '=', \Auth::user()->id)->where('status', '!=', '0')->where('created_by', \Auth::user()->creatorId());

            if(!empty($request->issue_date))
            {
                $date_range = explode(' - ', $request->issue_date);
                $query->whereBetween('issue_date', $date_range);
            }

            if(!empty($request->status))
            {
                $query->where('status', '=', $request->status);
            }
            $estimations = $query->get();

            return view('estimation.customer', compact('estimations', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function customerEstimationShow($estimation_id)
    {
        if(\Auth::user()->type == 'customer')
        {
            $id         = Crypt::decrypt($estimation_id);
            $estimation = Estimation::find($id);
            $settings   = Utility::settings();

            $status = Estimation::$statues;

            return view('estimation.customer_view', compact('estimation', 'settings', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
