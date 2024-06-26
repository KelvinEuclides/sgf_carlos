<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage unit'))
        {
            $units = Unit::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('unit.index', compact('units'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('unit.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create unit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit             = new Unit();
            $unit->name       = $request->name;
            $unit->created_by = \Auth::user()->creatorId();
            $unit->save();

            return redirect()->route('unit.index')->with('success', __('Unit successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Unit $unit)
    {
        //
    }


    public function edit(Unit $unit)
    {
        return view('unit.edit', compact('unit'));
    }


    public function update(Request $request, Unit $unit)
    {
        if(\Auth::user()->can('edit unit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit->name = $request->name;
            $unit->save();

            return redirect()->route('unit.index')->with('success', __('Unit successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('unit.index')->with('success', __('Unit successfully deleted.'));
    }
}
