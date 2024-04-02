<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage subscription'))
        {
            $subscriptions = Subscription::get();

            return view('subscription.index', compact('subscriptions'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $arrDuration = Subscription::$arrDuration;

        return view('subscription.create', compact('arrDuration'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create subscription'))
        {

            if((env('ENABLE_STRIPE') == 'on') || (env('ENABLE_PAYPAL') == 'on'))
            {

                $validation                  = [];
                $validation['name']          = 'required|unique:subscriptions';
                $validation['price']         = 'required|numeric|min:0';
                $validation['duration']      = 'required';
                $validation['max_users']     = 'required|numeric';
                $validation['max_customers'] = 'required|numeric';
                $validation['max_vendors']   = 'required|numeric';
                if($request->image)
                {
                    $validation['image'] = 'required|max:20480';
                }
                $request->validate($validation);

                $post = $request->all();

                if($request->hasFile('image'))
                {
                    $filenameWithExt = $request->file('image')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('image')->getClientOriginalExtension();
                    $fileNameToStore = 'plan_' . time() . '.' . $extension;

                    $dir = storage_path('uploads/subscription/');
                    if(!file_exists($dir))
                    {
                        mkdir($dir, 0777, true);
                    }
                    $path          = $request->file('image')->storeAs('uploads/subscription/', $fileNameToStore);
                    $post['image'] = $fileNameToStore;
                }

                if(Subscription::create($post))
                {
                    return redirect()->back()->with('success', __('Subscription Successfully created.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }

            }
            else
            {
                return redirect()->back()->with('error', __('Please set stripe or paypal api key & secret key for create new subscription.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Subscription $subscription)
    {
        //
    }


    public function edit(Subscription $subscription)
    {
        $arrDuration = Subscription::$arrDuration;


        return view('subscription.edit', compact('subscription', 'arrDuration'));
    }


    public function update(Request $request, Subscription $subscription)
    {
        if(\Auth::user()->can('edit subscription'))
        {
            if(empty(env('STRIPE_KEY')) || empty(env('STRIPE_SECRET')))
            {
                return redirect()->back()->with('error', __('Please set stripe api key & secret key for add new subscription.'));
            }
            else
            {

                if(!empty($subscription))
                {
                    $validation                  = [];
                    $validation['name']          = 'required|unique:subscriptions,name,' . $subscription->id;
                    $validation['duration']      = 'required';
                    $validation['max_users']     = 'required|numeric';
                    $validation['max_customers'] = 'required|numeric';
                    $validation['max_vendors']   = 'required|numeric';

                    $request->validate($validation);

                    $post = $request->all();

                    if($request->hasFile('image'))
                    {
                        $filenameWithExt = $request->file('image')->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('image')->getClientOriginalExtension();
                        $fileNameToStore = 'plan_' . time() . '.' . $extension;

                        $dir = storage_path('uploads/subscription/');
                        if(!file_exists($dir))
                        {
                            mkdir($dir, 0777, true);
                        }
                        $image_path = $dir . '/' . $subscription->image;  // Value is not URL but directory file path
                        if(File::exists($image_path))
                        {

                            chmod($image_path, 0755);
                            File::delete($image_path);
                        }
                        $path = $request->file('image')->storeAs('uploads/subscription/', $fileNameToStore);

                        $post['image'] = $fileNameToStore;
                    }

                    if($subscription->update($post))
                    {
                        return redirect()->back()->with('success', __('Subscription successfully updated.'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }
                else
                {
                    return redirect()->back()->with('error', __('Subscription not found.'));
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function userSubscription(Request $request)
    {
        $objUser = \Auth::user();
        $subscriptionID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
        $subscription    = Subscription::find($subscriptionID);
        if($subscription)
        {
            if($subscription->price <= 0)
            {
                $objUser->assignSubscription($subscription->id);

                return redirect()->route('subscription.index')->with('success', __('Subscription successfully activated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Subscription not found.'));
        }
    }
}
