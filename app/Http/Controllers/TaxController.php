<?php

namespace App\Http\Controllers;

use App\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage tax'))
        {
            $taxes = Tax::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('tax.index', compact('taxes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('tax.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create tax'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'rate' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tax             = new Tax();
            $tax->name       = $request->name;
            $tax->rate       = $request->rate;
            $tax->created_by = \Auth::user()->creatorId();
            $tax->save();

            return redirect()->route('tax.index')->with('success', __('Tax successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Tax $tax)
    {
        //
    }

    public function edit(Tax $tax)
    {
        return view('tax.edit', compact('tax'));
    }


    public function update(Request $request, Tax $tax)
    {
        if(\Auth::user()->can('edit tax'))
        {
            if($tax->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                       'rate' => 'required|numeric',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $tax->name = $request->name;
                $tax->rate = $request->rate;
                $tax->save();

                return redirect()->route('tax.index')->with('success', __('Tax successfully updated.'));
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


    public function destroy(Tax $tax)
    {
        if(\Auth::user()->can('delete tax'))
        {
            $tax->delete();

            return redirect()->route('tax.index')->with('success', __('Tax successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
