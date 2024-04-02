<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BillPayment;
use App\Category;
use App\Expense;
use App\Mail\BillPaymentCreate;
use App\Transaction;
use App\User;
use App\Utility;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ExpenseController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage expense'))
        {
            $vendor = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'vendor')->get()->pluck('name', 'id');
            $vendor->prepend('All Vendor', '');

            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All Account', '');

            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
            $category->prepend('All Category', '');


            $query = Expense::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->vendor))
            {
                $query->where('vendor', '=', $request->vendor);
            }
            if(!empty($request->account))
            {
                $query->where('account', '=', $request->account);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }


            $expenses = $query->get();


            return view('expense.index', compact('expenses', 'account', 'category', 'vendor'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $vendors = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'vendor')->get()->pluck('name', 'id');
        $vendors->prepend('--', 0);
        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('expense.create', compact('vendors', 'categories', 'accounts'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create expense'))
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

            $expense              = new Expense();
            $expense->date        = $request->date;
            $expense->amount      = $request->amount;
            $expense->account     = $request->account;
            $expense->vendor      = $request->vendor;
            $expense->category    = $request->category;
            $expense->reference   = $request->reference;
            $expense->description = $request->description;
            $expense->created_by  = \Auth::user()->creatorId();
            $expense->save();

            $category            = Category::where('id', $request->category)->first();
            $expense->payment_id = $expense->id;
            $expense->type       = 'Expense';
            $expense->category   = $category->name;
            $expense->user_id    = $expense->vendor;
            $expense->user_type  = 'Vendor';
            $expense->account    = $request->account;
            Transaction::addTransaction($expense);

            $user            = User::where('id', $request->vendor)->first();
            $vendor          = Vendor::where('user_id', $request->vendor)->first();
            $payment         = new BillPayment();
            $payment->name   = $user['name'];
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = '';

            if(!empty($vendor))
            {
                Utility::userBalance('vendor', $vendor->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account, $request->amount, 'debit');

            try
            {
                Mail::to($vendor['email'])->send(new BillPaymentCreate($payment));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('Connection could not be established due to Email settings configuration.');
            }

            return redirect()->route('expense.index')->with('success', __('Expense successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Expense $expense)
    {
        //
    }

    public function edit(Expense $expense)
    {
        $vendors = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'vendor')->get()->pluck('name', 'id');
        $vendors->prepend('--', 0);
        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
        $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('expense.edit', compact('vendors', 'categories', 'accounts', 'expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if(\Auth::user()->can('edit expense'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account' => 'required',
                                   'vendor' => 'required',
                                   'category' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $expense->amount, 'credit');
            }
            Utility::bankAccountBalance($request->account, $expense->amount, 'credit');

            $expense->date        = $request->date;
            $expense->amount      = $request->amount;
            $expense->account     = $request->account;
            $expense->vendor      = $request->vendor;
            $expense->category    = $request->category;
            $expense->reference   = $request->reference;
            $expense->description = $request->description;
            $expense->save();

            $category            = Category::where('id', $request->category)->first();
            $expense->category   = $category->name;
            $expense->payment_id = $expense->id;
            $expense->type       = 'Expense';
            $expense->account    = $request->account;
            Transaction::editTransaction($expense);

            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vendor->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account, $request->amount, 'debit');

            return redirect()->route('expense.index')->with('success', __('Expense successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Expense $expense)
    {
        if(\Auth::user()->can('delete expense'))
        {
            if($expense->created_by == \Auth::user()->creatorId())
            {
                $expense->delete();
                $type = 'Expense';
                $user = 'Vendor';
                Transaction::destroyTransaction($expense->id, $type, $user);

                if($expense->vendor_id != 0)
                {
                    Utility::userBalance('vendor', $expense->vendor_id, $expense->amount, 'credit');
                }
                Utility::bankAccountBalance($expense->account_id, $expense->amount, 'credit');

                return redirect()->route('expense.index')->with('success', __('Expense successfully deleted.'));
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

    public function vendorPayment(Request $request)
    {
        if(\Auth::user()->type=='vendor')
        {

            $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Vendor');
            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            $payments = $query->get();


            return view('expense.vendor_payment', compact('payments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
