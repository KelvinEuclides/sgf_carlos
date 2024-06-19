<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Mail\UserCreate;
use App\Subscription;
use App\User;
use App\UserCompany;
use App\UserSubscriber;
use App\Vendor;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{

    public function index()
    {
        $user = \Auth::user();

        if(\Auth::user()->type == 'super admin')
        {
            $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
        }
        else
        {
            if(\Auth::user()->can('manage user'))
            {

                $users = User::where('created_by', '=', $user->creatorId())->whereNotIn(
                    'type', [
                              'customer',
                              'vendor',
                          ]
                )->get();


            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

        }

        return view('user.index', compact('users'));

    }

    public function create()
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'customer')->where('name', '!=', 'vendor')->get()->pluck('name', 'id');

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
        if(\Auth::user()->type == 'super admin')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:users',
                                   'password' => 'required|min:6',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $user                 = new User();
            $user['name']         = $request->name;
            $user['email']        = $request->email;
            $psw                  = $request->password;
            $user['password']     = Hash::make($request->password);
            $user['address']      = $request->address;
            $user['nuit']        = $request->nuit;
            $user['type']         = 'company';
            $user['lang']         = !empty($default_language) ? $default_language->value : '';
            $user['subscription'] = $request->subscription;
            $user['created_by']   = \Auth::user()->creatorId();
            $user->save();

            $role_r = Role::findByName('company');
            $user->assignRole($role_r);

            return redirect()->route('users.index')->with('success', __('Company successfully created.'));
        }
        else
        {
            if(\Auth::user()->can('create user'))
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:120',
                                       'email' => 'required|email|unique:users',
                                       'password' => 'required|min:6',
                                       'role' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }


                $objUser    = \Auth::user();
                $total_user = $objUser->countUsers();
                $subscription       = Subscription::find($objUser->subscription);
                if($total_user < $subscription->max_users || $subscription->max_users == -1)
                {
                    $role_r                = Role::findById($request->role);
                    $psw                   = $request->password;
                    $request['password']   = Hash::make($request->password);
                    $request['type']       = $role_r->name;
                    $request['lang']       = !empty($default_language) ? $default_language->value : 'en';
                    $request['created_by'] = \Auth::user()->creatorId();

                    $user = User::create($request->all());

                    $user->assignRole($role_r);

                    $user->password = $psw;
                    $user->type     = $role_r->name;
                    try
                    {
                        Mail::to($user->email)->send(new UserCreate($user));
                    }
                    catch(\Exception $e)
                    {

                        $smtp_error = __('Connection could not be established due to Email settings configuration.');
                    }

                    return redirect()->route('users.index')->with('success', __('User successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));


                }
                else
                {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }


            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

        }


    }

    public function edit($id)
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'customer')->where('name', '!=', 'vendor')->get()->pluck('name', 'id');
        $user  = User::findOrFail($id);

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $user = User::findOrFail($id);

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:120',
                                   'email' => 'required|email|unique:users,email,' . $id,
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input = $request->all();
            $user->fill($input)->save();

            return redirect()->route('users.index')->with(
                'success', 'User successfully updated.'
            );
        }
        else
        {

            $user = User::findOrFail($id);
            $this->validate(
                $request, [
                            'name' => 'required|max:120',
                            'email' => 'required|email|unique:users,email,' . $id,
                            'role' => 'required',
                        ]
            );

            $role          = Role::findById($request->role);
            $input         = $request->all();
            $input['type'] = $role->name;
            $user->fill($input)->save();

            $roles[] = $request->role;
            $user->roles()->sync($roles);

            return redirect()->route('users.index')->with(
                'success', 'User successfully updated.'
            );
        }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete user') || \Auth::user()->type == 'super admin')
        {
            $user = User::find($id);
            if($user)
            {
                $user->delete();

                return redirect()->route('users.index')->with('success', __('User successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('user.profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );
        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

        }

        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->route('home')->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {
        if(Auth::Check())
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function upgradeSubscription($user_id)
    {
        $user = User::find($user_id);

        $subscriptions = Subscription::get();

        return view('user.subscription', compact('user', 'subscriptions'));
    }

    public function activeSubscription($user_id, $subscription_id)
    {

        $user               = User::find($user_id);
        $assignSubscription = $user->assignSubscription($subscription_id);
        $subscription       = Subscription::find($subscription_id);
        if($assignSubscription['is_success'] == true && !empty($subscription))
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            UserSubscriber::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'subscription' => $subscription->name,
                    'subscription_id' => $subscription->id,
                    'price' => $subscription->price,
                    'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', __('Subscription successfully upgraded.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Subscription fail to upgrade.'));
        }

    }

    //    -------------------------Customers------------------------------

    public function customer()
    {
        $user = \Auth::user();
        if(\Auth::user()->can('manage customer'))
        {
            $customers = User::where('created_by', '=', $user->creatorId())->where('type', 'customer')->get();

            return view('user.customer', compact('customers'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function customerCreate()
    {
        return view('user.customerCreate');
    }

    public function customerStore(Request $request)
    {

        if(\Auth::user()->can('create customer'))
        {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator        = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required|email|unique:users',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $objUser        = \Auth::user();
            $total_customer = $objUser->countCustomers();
            $subscription           = Subscription::find($objUser->subscription);
            if($total_customer < $subscription->max_customers || $subscription->max_customers == -1)
            {
                $user               = new User();
                $user['name']       = $request->name;
                $user['email']      = $request->email;
                $psw                = $request->password;
                $user['password']   = null;
                $user['type']       = 'customer';
                $user['lang']       = !empty($default_language) ? $default_language->value : '';
                $user['created_by'] = \Auth::user()->creatorId();
                $user->save();

                if(!empty($user))
                {
                    $customer                = new Customer();
                    $customer['user_id']     = $user->id;
                    $customer['contact']     = $request->contact;
                    $customer['customer_id'] = $this->customerNumber();
                    $customer['created_by']  = \Auth::user()->creatorId();
                    $customer->save();

                }

                $role_r = Role::findByName('customer');
                $user->assignRole($role_r);


                return redirect()->route('customers.index')->with('success', __('Customer successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Your customer limit is over, Please upgrade plan.'));
            }


        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function customerEdit($id)
    {
        $user = User::find($id);

        return view('user.customerEdit', compact('user'));
    }

    public function customerUpdate(Request $request, $id)
    {

        if(\Auth::user()->can('edit customer'))
        {

            $rules = [
                'name' => 'required',
            ];


            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('customer.index')->with('error', $messages->first());
            }

            $user       = User::find($id);
            $user->name = $request->name;
            $user->save();
            if(!empty($user))
            {
                $customer                   = Customer::where('user_id', $id)->first();
                $customer->contact          = $request->contact;
                $customer->billing_name     = $request->billing_name;
                $customer->billing_country  = $request->billing_country;
                $customer->billing_state    = $request->billing_state;
                $customer->billing_city     = $request->billing_city;
                $customer->billing_phone    = $request->billing_phone;
                $customer->billing_zip      = $request->billing_zip;
                $customer->billing_address  = $request->billing_address;
                $customer->shipping_name    = $request->shipping_name;
                $customer->shipping_country = $request->shipping_country;
                $customer->shipping_state   = $request->shipping_state;
                $customer->shipping_city    = $request->shipping_city;
                $customer->shipping_phone   = $request->shipping_phone;
                $customer->shipping_zip     = $request->shipping_zip;
                $customer->shipping_address = $request->shipping_address;
                $customer->save();
            }


            return redirect()->route('customers.index')->with('success', __('Customer successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerDestroy($id)
    {

        if(\Auth::user()->can('delete customer'))
        {
            $user = User::find($id);
            if($user)
            {
                $user->delete();
                $customer = Customer::where('user_id', $id)->first();
                $customer->delete();

                return redirect()->route('customers.index')->with('success', __('Customer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerShow($user_id)
    {
        $id = \Crypt::decrypt($user_id);
        if(\Auth::user()->can('show customer'))
        {
            $user = User::find($id);

            return view('user.customerShow', compact('user'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function customerNumber()
    {
        $latest = Customer::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->customer_id + 1;
    }

    public function customerEditBilling(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Customer::where('user_id',$userDetail->id)->first();
        $this->validate(
            $request, [
                        'billing_name' => 'required',
                        'billing_country' => 'required',
                        'billing_state' => 'required',
                        'billing_city' => 'required',
                        'billing_phone' => 'required',
                        'billing_zip' => 'required',
                        'billing_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Customer profile successfully updated.'
        );
    }

    public function customerEditShipping(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = Customer::where('user_id',$userDetail->id)->first();
        $this->validate(
            $request, [
                        'shipping_name' => 'required',
                        'shipping_country' => 'required',
                        'shipping_state' => 'required',
                        'shipping_city' => 'required',
                        'shipping_phone' => 'required',
                        'shipping_zip' => 'required',
                        'shipping_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Profile successfully updated.'
        );
    }

    //    -------------------------Vendors------------------------------

    public function vendor()
    {
        $user = \Auth::user();
        if(\Auth::user()->can('manage vendor'))
        {
            $vendors = User::where('created_by', '=', $user->creatorId())->where('type', 'vendor')->get();

            return view('user.vendor', compact('vendors'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function vendorCreate()
    {
        return view('user.vendorCreate');
    }

    public function vendorStore(Request $request)
    {

        if(\Auth::user()->can('create vendor'))
        {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator        = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'email' => 'required|email|unique:users',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $objUser      = \Auth::user();
            $total_vendor = $objUser->countVendors();
            $subscription         = Subscription::find($objUser->subscription);
            if($total_vendor < $subscription->max_vendors || $subscription->max_vendors == -1)
            {
                $user               = new User();
                $user['name']       = $request->name;
                $user['email']      = $request->email;
                $psw                = $request->password;
                $user['password']   = null;
                $user['type']       = 'vendor';
                $user['lang']       = !empty($default_language) ? $default_language->value : '';
                $user['created_by'] = \Auth::user()->creatorId();
                $user->save();

                if(!empty($user))
                {
                    $vendor               = new Vendor();
                    $vendor['user_id']    = $user->id;
                    $vendor['contact']    = $request->contact;
                    $vendor['vendor_id']  = $this->vendorNumber();
                    $vendor['created_by'] = \Auth::user()->creatorId();
                    $vendor->save();

                }

                $role_r = Role::findByName('vendor');
                $user->assignRole($role_r);

                return redirect()->route('vendors.index')->with('success', __('Vendor successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Your customer limit is over, Please upgrade plan.'));
            }


        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function vendorEdit($id)
    {
        $user = User::find($id);

        return view('user.vendorEdit', compact('user'));
    }

    public function vendorUpdate(Request $request, $id)
    {

        if(\Auth::user()->can('edit vendor'))
        {

            $rules = [
                'name' => 'required',
            ];


            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('vendor.index')->with('error', $messages->first());
            }

            $user       = User::find($id);
            $user->name = $request->name;
            $user->save();
            if(!empty($user))
            {
                $vendor                   = Vendor::where('user_id', $id)->first();
                $vendor->contact          = $request->contact;
                $vendor->billing_name     = $request->billing_name;
                $vendor->billing_country  = $request->billing_country;
                $vendor->billing_state    = $request->billing_state;
                $vendor->billing_city     = $request->billing_city;
                $vendor->billing_phone    = $request->billing_phone;
                $vendor->billing_zip      = $request->billing_zip;
                $vendor->billing_address  = $request->billing_address;
                $vendor->shipping_name    = $request->shipping_name;
                $vendor->shipping_country = $request->shipping_country;
                $vendor->shipping_state   = $request->shipping_state;
                $vendor->shipping_city    = $request->shipping_city;
                $vendor->shipping_phone   = $request->shipping_phone;
                $vendor->shipping_zip     = $request->shipping_zip;
                $vendor->shipping_address = $request->shipping_address;
                $vendor->save();
            }


            return redirect()->route('vendors.index')->with('success', __('Vendor successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function vendorDestroy($id)
    {

        if(\Auth::user()->can('delete vendor'))
        {
            $user = User::find($id);
            if($user)
            {
                $user->delete();
                $vendor = Vendor::where('user_id', $id)->first();
                $vendor->delete();

                return redirect()->route('vendors.index')->with('success', __('Customer successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function vendorShow($user_id)
    {
        $id = \Crypt::decrypt($user_id);
        if(\Auth::user()->can('show vendor'))
        {
            $user = User::find($id);

            return view('user.vendorShow', compact('user'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function vendorNumber()
    {
        $latest = Vendor::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->vendor_id + 1;
    }

    public function vendorEditBilling(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Vendor::where('user_id',$userDetail->id)->first();
        $this->validate(
            $request, [
                        'billing_name' => 'required',
                        'billing_country' => 'required',
                        'billing_state' => 'required',
                        'billing_city' => 'required',
                        'billing_phone' => 'required',
                        'billing_zip' => 'required',
                        'billing_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Vendor profile successfully updated.'
        );
    }

    public function vendorEditShipping(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = Vendor::where('user_id',$userDetail->id)->first();
        $this->validate(
            $request, [
                        'shipping_name' => 'required',
                        'shipping_country' => 'required',
                        'shipping_state' => 'required',
                        'shipping_city' => 'required',
                        'shipping_phone' => 'required',
                        'shipping_zip' => 'required',
                        'shipping_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Profile successfully updated.'
        );
    }
}
