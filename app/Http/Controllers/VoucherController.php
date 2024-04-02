<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\UserVoucher;
use App\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage voucher'))
        {
            $vouchers = Voucher::get();

            return view('voucher.index', compact('vouchers'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('voucher.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create voucher'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'discount' => 'required|numeric',
                                   'limit' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if(empty($request->manualCode) && empty($request->autoCode))
            {
                return redirect()->back()->with('error', 'Voucher code is required');
            }
            $voucher           = new Voucher();
            $voucher->name     = $request->name;
            $voucher->discount = $request->discount;
            $voucher->limit    = $request->limit;

            if(!empty($request->manualCode))
            {
                $voucher->code = strtoupper($request->manualCode);
            }

            if(!empty($request->autoCode))
            {
                $voucher->code = $request->autoCode;
            }

            $voucher->save();

            return redirect()->route('voucher.index')->with('success', __('Voucher successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Voucher $voucher)
    {
        $userVoucher = UserVoucher::where('voucher', $voucher->id)->get();

        return view('voucher.view', compact('userVoucher'));
    }


    public function edit(Voucher $voucher)
    {
        return view('voucher.edit', compact('voucher'));
    }


    public function update(Request $request, Voucher $voucher)
    {
        if(\Auth::user()->can('edit voucher'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'discount' => 'required|numeric',
                                   'limit' => 'required|numeric',
                                   'code' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $voucher           = Voucher::find($voucher->id);
            $voucher->name     = $request->name;
            $voucher->discount = $request->discount;
            $voucher->limit    = $request->limit;
            $voucher->code     = $request->code;

            $voucher->save();

            return redirect()->route('voucher.index')->with('success', __('Voucher successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Voucher $voucher)
    {
        if(\Auth::user()->can('delete voucher'))
        {
            $voucher->delete();

            return redirect()->route('voucher.index')->with('success', __('Voucher successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyVoucher(Request $request)
    {

        $subscription = Subscription::find(\Illuminate\Support\Facades\Crypt::decrypt($request->subscription_id));

        if($subscription && $request->voucher != '')
        {
            $original_price = self::formatPrice($subscription->price);
            $vouchers        = Voucher::where('code', strtoupper($request->voucher))->where('is_active', '1')->first();

            if(!empty($vouchers))
            {
                $usedCoupun = $vouchers->used_voucher();
                if($vouchers->limit == $usedCoupun)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($subscription->price, \Utility::getValByName('decimal_number')),
                            'message' => __('This voucher has expired.'),
                        ]
                    );
                }
                else
                {
                    $discount_value = ($subscription->price / 100) * $vouchers->discount;
                    $subscription_price     = $subscription->price - $discount_value;
                    $price          = self::formatPrice($subscription->price - $discount_value);
                    $discount_value = '-' . self::formatPrice($discount_value);

                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'price' => number_format($subscription_price, \Utility::getValByName('decimal_number')),
                            'message' => __('Voucher has applied successfully.'),
                        ]
                    );
                }
            }
            else
            {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($subscription->price, \Utility::getValByName('decimal_number')),
                        'message' => __('This voucher is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price)
    {
        return env('CURRENCY_SYMBOL') . number_format($price, \Utility::getValByName('decimal_number'));
    }
}
