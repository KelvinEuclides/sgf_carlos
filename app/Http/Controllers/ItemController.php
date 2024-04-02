<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\Tax;
use App\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage item'))
        {
            $items = Item::where('created_by', '=', \Auth::user()->creatorId());
            if(!empty($request->category))
            {
                $items->where('category', $request->category);
            }
            $items = $items->get();
            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'item')->get()->pluck('name', 'id');
            $category->prepend('All Category','');
            return view('item.index', compact('items', 'category'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'item')->get()->pluck('name', 'id');
        $unit     = Unit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tax      = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('item.create', compact('category', 'unit', 'tax'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create item'))
        {

            $rules = [
                'name' => 'required',
                'sku' => 'required',
                'sale_price' => 'required|numeric',
                'purchase_price' => 'required|numeric',
                'tax' => 'required',
                'category' => 'required',
                'unit' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('item.index')->with('error', $messages->first());
            }

            $item                 = new Item();
            $item->name           = $request->name;
            $item->description    = $request->description;
            $item->sku            = $request->sku;
            $item->sale_price     = $request->sale_price;
            $item->purchase_price = $request->purchase_price;
            $item->tax            = implode(',', $request->tax);
            $item->unit           = $request->unit;
            $item->category       = $request->category;
            $item->created_by     = \Auth::user()->creatorId();
            $item->save();


            return redirect()->route('item.index')->with('success', __('Item successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }


    public function edit(Item $item)
    {

        $category  = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 0)->get()->pluck('name', 'id');
        $unit      = Unit::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $tax       = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $item->tax = explode(',', $item->tax);

        return view('item.edit', compact('category', 'unit', 'tax', 'item'));
    }


    public function update(Request $request, Item $item)
    {

        if(\Auth::user()->can('edit item'))
        {

            $rules = [
                'name' => 'required',
                'sku' => 'required',
                'sale_price' => 'required|numeric',
                'purchase_price' => 'required|numeric',
                'tax' => 'required',
                'category' => 'required',
                'unit' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('item.index')->with('error', $messages->first());
            }


            $item->name           = $request->name;
            $item->description    = $request->description;
            $item->sku            = $request->sku;
            $item->sale_price     = $request->sale_price;
            $item->purchase_price = $request->purchase_price;
            $item->tax            = implode(',', $request->tax);
            $item->unit           = $request->unit;
            $item->category       = $request->category;
            $item->save();


            return redirect()->route('item.index')->with('success', __('Item successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Item $item)
    {
        if(\Auth::user()->can('delete item'))
        {
            $item->delete();

            return redirect()->route('item.index')->with('success', __('Item successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
