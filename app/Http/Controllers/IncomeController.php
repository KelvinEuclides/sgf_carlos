<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Category;
use App\Customer;
use App\Income;
use App\InvoicePayment;
use App\Mail\InvoicePaymentCreate;
use App\Transaction;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IncomeController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage income'))
        {
            $customer = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'customer')->get()->pluck('name', 'id');
            $customer->prepend('All Customer', '');

            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All Account', '');

            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'income')->get()->pluck('name', 'id');
            $category->prepend('All Category', '');


            $query = Income::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->customer))
            {
                $query->where('customer', '=', $request->customer);
            }
            if(!empty($request->account))
            {
                $query->where('account', '=', $request->account);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }

            $incomes = $query->get();

            return view('income.index', compact('incomes', 'customer', 'account', 'category'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $customers = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'customer')->get()->pluck('name', 'id');
        $customers->prepend('--', 0);

        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'income')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('income.create', compact('customers', 'categories', 'accounts'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create income'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account' => 'required',
                                   'category' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $income              = new Income();
            $income->date        = $request->date;
            $income->amount      = $request->amount;
            $income->account     = $request->account;
            $income->customer    = $request->customer;
            $income->category    = $request->category;
            $income->reference   = $request->reference;
            $income->description = $request->description;
            $income->created_by  = \Auth::user()->creatorId();
            $income->save();

            $category           = Category::where('id', $request->category)->first();
            $income->payment_id = $income->id;
            $income->type       = 'Income';
            $income->category   = $category->name;
            $income->user_id    = $income->customer;
            $income->user_type  = 'Customer';
            $income->account    = $request->account;
            Transaction::addTransaction($income);

            $user            = User::where('id', $request->customer)->first();
            $customer        = Customer::where('user_id', $request->vendor)->first();
            $income          = new InvoicePayment();
            $income->name    = !empty($user) ? $user['name'] : '';
            $income->date    = \Auth::user()->dateFormat($request->date);
            $income->amount  = \Auth::user()->priceFormat($request->amount);
            $income->invoice = '';

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account, $request->amount, 'credit');

            try
            {
                Mail::to($user['email'])->send(new InvoicePaymentCreate($income));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->route('income.index')->with('success', __('Income successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Income $income)
    {
        //
    }


    public function edit(Income $income)
    {
        $customers = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'customer')->get()->pluck('name', 'id');
        $customers->prepend('--', 0);

        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'income')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('income.edit', compact('customers', 'categories', 'accounts', 'income'));
    }


    public function update(Request $request, Income $income)
    {
        if(\Auth::user()->can('edit income'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account' => 'required',
                                   'category' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customer = User::where('id', $request->customer)->first();
            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $income->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $income->amount, 'debit');

            $income->date        = $request->date;
            $income->amount      = $request->amount;
            $income->account     = $request->account;
            $income->customer    = $request->customer;
            $income->category    = $request->category;
            $income->reference   = $request->reference;
            $income->description = $request->description;
            $income->save();

            $category           = Category::where('id', $request->category)->first();
            $income->category   = $category->name;
            $income->payment_id = $income->id;
            $income->type       = 'Income';
            $income->account    = $request->account;
            Transaction::editTransaction($income);

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account, $request->amount, 'credit');


            return redirect()->route('income.index')->with('success', __('Income successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Income $income)
    {
        if(\Auth::user()->can('delete income'))
        {
            if($income->created_by == \Auth::user()->creatorId())
            {
                $income->delete();
                $type = 'Income';
                $user = 'Customer';
                Transaction::destroyTransaction($income->id, $type, $user);

                if($income->customer_id != 0)
                {
                    Utility::userBalance('customer', $income->customer_id, $income->amount, 'debit');
                }

                Utility::bankAccountBalance($income->account_id, $income->amount, 'debit');

                return redirect()->route('income.index')->with('success', __('Income successfully deleted.'));
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

    public function customerPayment(Request $request)
    {

        if(\Auth::user()->type=='customer')
        {

            $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Customer');
            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }
            $payments = $query->get();

            return view('income.customer_payment', compact('payments', 'category'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
