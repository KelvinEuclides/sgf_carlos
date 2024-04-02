<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BillPayment;
use App\Expense;
use App\Income;
use App\InvoicePayment;
use App\Transaction;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage banking'))
        {
            $accounts = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('bankAccount.index', compact('accounts'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('bankAccount.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create banking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'holder_name' => 'required',
                                   'bank_name' => 'required',
                                   'account_number' => 'required',
                                   'opening_balance' => 'required',
                                   'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('bank-account.index')->with('error', $messages->first());
            }

            $account                  = new BankAccount();
            $account->holder_name     = $request->holder_name;
            $account->bank_name       = $request->bank_name;
            $account->account_number  = $request->account_number;
            $account->opening_balance = $request->opening_balance;
            $account->contact_number  = $request->contact_number;
            $account->bank_address    = $request->bank_address;
            $account->created_by      = \Auth::user()->creatorId();
            $account->save();

            return redirect()->route('account.index')->with('success', __('Account successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(BankAccount $bankAccount)
    {
        //
    }

    public function edit($id)
    {
        $bankAccount = BankAccount::find($id);

        return view('bankAccount.edit', compact('bankAccount'));
    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit banking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'holder_name' => 'required',
                                   'bank_name' => 'required',
                                   'account_number' => 'required',
                                   'opening_balance' => 'required',
                                   'contact_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('bank-account.index')->with('error', $messages->first());
            }

            $bankAccount = BankAccount::find($id);
            $bankAccount->holder_name     = $request->holder_name;
            $bankAccount->bank_name       = $request->bank_name;
            $bankAccount->account_number  = $request->account_number;
            $bankAccount->opening_balance = $request->opening_balance;
            $bankAccount->contact_number  = $request->contact_number;
            $bankAccount->bank_address    = $request->bank_address;
            $bankAccount->created_by      = \Auth::user()->creatorId();
            $bankAccount->save();

            return redirect()->route('account.index')->with('success', __('Account successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if(\Auth::user()->can('delete banking'))
        {
            $bankAccount=BankAccount::find($id);
            $income        = Income::where('account', $bankAccount->id)->first();
            $invoicePayment = InvoicePayment::where('account_id', $bankAccount->id)->first();
            $transaction    = Transaction::where('account', $bankAccount->id)->first();
            $expense        = Expense::where('account', $bankAccount->id)->first();
            $billPayment    = BillPayment::first();

            if(!empty($income) && !empty($invoicePayment) && !empty($transaction) && !empty($expense) && !empty($billPayment))
            {
                return redirect()->route('account.index')->with('error', __('Please delete related record of this account.'));
            }
            else
            {

                $bankAccount->delete();

                return redirect()->route('account.index')->with('success', __('Account successfully deleted.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
