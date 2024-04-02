<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Bill;
use App\BillProduct;
use App\Category;
use App\Customer;
use App\Estimation;
use App\Expense;
use App\Income;
use App\Invoice;
use App\InvoiceProduct;
use App\Item;
use App\Payment;
use App\ProductServiceCategory;
use App\Revenue;
use App\Tax;
use App\User;
use App\Vender;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    //    -------------------Estimation Summary---------------------------------------------
    public function estimationSummary(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $filter['customer'] = __('All');
            $filter['status']   = __('All');


            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $status = Estimation::$statues;

            $estimations = Estimation::selectRaw('estimations.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if($request->status != '')
            {
                $estimations->where('status', $request->status);

                $filter['status'] = Estimation::$statues[$request->status];
            }
            else
            {
                $estimations->where('status', '!=', 0);
            }

            $estimations->where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $estimations->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));


            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->customer))
            {
                $estimations->where('customer', $request->customer);
                $cust = Customer::find($request->customer);

                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }


            $estimations = $estimations->get();


            $totalEstimation      = 0;
            $estimationTotalArray = [];
            foreach($estimations as $estimation)
            {
                $totalEstimation                            += $estimation->getTotal();
                $estimationTotalArray[$estimation->month][] = $estimation->getTotal();
            }

            for($i = 1; $i <= 12; $i++)
            {
                $estimationTotal[] = array_key_exists($i, $estimationTotalArray) ? array_sum($estimationTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.estimation', compact('estimations', 'customer', 'status', 'totalEstimation', 'estimationTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function estimationSummaryList(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $filter['customer'] = __('All');
            $filter['status']   = __('All');


            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $status = Estimation::$statues;

            $estimations = Estimation::selectRaw('estimations.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if($request->status != '')
            {
                $estimations->where('status', $request->status);

                $filter['status'] = Estimation::$statues[$request->status];
            }
            else
            {
                $estimations->where('status', '!=', 0);
            }

            $estimations->where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $estimations->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));


            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->customer))
            {
                $estimations->where('customer', $request->customer);
                $cust = Customer::find($request->customer);

                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }


            $estimations = $estimations->get();


            $totalEstimation      = 0;
            $estimationTotalArray = [];
            foreach($estimations as $estimation)
            {
                $totalEstimation                            += $estimation->getTotal();
                $estimationTotalArray[$estimation->month][] = $estimation->getTotal();
            }

            for($i = 1; $i <= 12; $i++)
            {
                $estimationTotal[] = array_key_exists($i, $estimationTotalArray) ? array_sum($estimationTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.estimation_list', compact('estimations', 'customer', 'status', 'totalEstimation', 'estimationTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    //    -------------------Invoice Summary---------------------------------------------

    public function invoiceSummary(Request $request)
    {

        if(\Auth::user()->can('manage summary'))
        {
            $filter['customer'] = __('All');
            $filter['status']   = __('All');


            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $status = Invoice::$statues;

            $invoices = Invoice::selectRaw('invoices.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if($request->status != '')
            {
                $invoices->where('status', $request->status);

                $filter['status'] = Invoice::$statues[$request->status];
            }
            else
            {
                $invoices->where('status', '!=', 0);
            }

            $invoices->where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $invoices->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));


            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->customer))
            {
                $invoices->where('customer', $request->customer);
                $cust = Customer::find($request->customer);

                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }


            $invoices = $invoices->get();


            $totalInvoice      = 0;
            $totalDueInvoice   = 0;
            $invoiceTotalArray = [];
            foreach($invoices as $invoice)
            {
                $totalInvoice    += $invoice->getTotal();
                $totalDueInvoice += $invoice->getDue();

                $invoiceTotalArray[$invoice->month][] = $invoice->getTotal();
            }
            $totalPaidInvoice = $totalInvoice - $totalDueInvoice;

            for($i = 1; $i <= 12; $i++)
            {
                $invoiceTotal[] = array_key_exists($i, $invoiceTotalArray) ? array_sum($invoiceTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.invoice', compact('invoices', 'customer', 'status', 'totalInvoice', 'totalDueInvoice', 'totalPaidInvoice', 'invoiceTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function invoiceSummaryList(Request $request)
    {

        if(\Auth::user()->can('manage summary'))
        {
            $filter['customer'] = __('All');
            $filter['status']   = __('All');


            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $status = Invoice::$statues;

            $invoices = Invoice::selectRaw('invoices.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if($request->status != '')
            {
                $invoices->where('status', $request->status);

                $filter['status'] = Invoice::$statues[$request->status];
            }
            else
            {
                $invoices->where('status', '!=', 0);
            }

            $invoices->where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $invoices->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));


            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->customer))
            {
                $invoices->where('customer', $request->customer);
                $cust = Customer::find($request->customer);

                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }


            $invoices = $invoices->get();


            $totalInvoice      = 0;
            $totalDueInvoice   = 0;
            $invoiceTotalArray = [];
            foreach($invoices as $invoice)
            {
                $totalInvoice    += $invoice->getTotal();
                $totalDueInvoice += $invoice->getDue();

                $invoiceTotalArray[$invoice->month][] = $invoice->getTotal();
            }
            $totalPaidInvoice = $totalInvoice - $totalDueInvoice;

            for($i = 1; $i <= 12; $i++)
            {
                $invoiceTotal[] = array_key_exists($i, $invoiceTotalArray) ? array_sum($invoiceTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.invoice_list', compact('invoices', 'customer', 'status', 'totalInvoice', 'totalDueInvoice', 'totalPaidInvoice', 'invoiceTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    //    -------------------Bill Summary---------------------------------------------

    public function billSummary(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {

            $filter['vendor'] = __('All');
            $filter['status'] = __('All');


            $vendor = User::where('type', 'vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vendor->prepend('All', '');
            $status = Bill::$statues;

            $bills = Bill::selectRaw('bills.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $bills->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));

            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->vendor))
            {
                $bills->where('vendor', $request->vendor);
                $vend = User::find($request->vendor);

                $filter['vendor'] = !empty($vend) ? $vend->name : '';
            }

            if($request->status != '')
            {
                $bills->where('status', '=', $request->status);

                $filter['status'] = Bill::$statues[$request->status];
            }
            else
            {
                $bills->where('status', '!=', 0);
            }

            $bills->where('created_by', '=', \Auth::user()->creatorId());
            $bills = $bills->get();


            $totalBill      = 0;
            $totalDueBill   = 0;
            $billTotalArray = [];
            foreach($bills as $bill)
            {
                $totalBill    += $bill->getTotal();
                $totalDueBill += $bill->getDue();

                $billTotalArray[$bill->month][] = $bill->getTotal();
            }
            $totalPaidBill = $totalBill - $totalDueBill;

            for($i = 1; $i <= 12; $i++)
            {
                $billTotal[] = array_key_exists($i, $billTotalArray) ? array_sum($billTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.bill', compact('bills', 'vendor', 'status', 'totalBill', 'totalDueBill', 'totalPaidBill', 'billTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function billSummaryList(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {

            $filter['vendor'] = __('All');
            $filter['status'] = __('All');


            $vendor = User::where('type', 'vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vendor->prepend('All', '');
            $status = Bill::$statues;

            $bills = Bill::selectRaw('bills.*,MONTH(send_date) as month,YEAR(send_date) as year');

            if(!empty($request->start_month) && !empty($request->end_month))
            {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            }
            else
            {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $bills->where('send_date', '>=', date('Y-m-01', $start))->where('send_date', '<=', date('Y-m-t', $end));

            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);


            if(!empty($request->vendor))
            {
                $bills->where('vendor', $request->vendor);
                $vend = User::find($request->vendor);

                $filter['vendor'] = !empty($vend) ? $vend->name : '';
            }

            if($request->status != '')
            {
                $bills->where('status', '=', $request->status);

                $filter['status'] = Bill::$statues[$request->status];
            }
            else
            {
                $bills->where('status', '!=', 0);
            }

            $bills->where('created_by', '=', \Auth::user()->creatorId());
            $bills = $bills->get();


            $totalBill      = 0;
            $totalDueBill   = 0;
            $billTotalArray = [];
            foreach($bills as $bill)
            {
                $totalBill    += $bill->getTotal();
                $totalDueBill += $bill->getDue();

                $billTotalArray[$bill->month][] = $bill->getTotal();
            }
            $totalPaidBill = $totalBill - $totalDueBill;

            for($i = 1; $i <= 12; $i++)
            {
                $billTotal[] = array_key_exists($i, $billTotalArray) ? array_sum($billTotalArray[$i]) : 0;
            }

            $monthList = $month = $this->yearMonth();

            return view('summary.bill_list', compact('bills', 'vendor', 'status', 'totalBill', 'totalDueBill', 'totalPaidBill', 'billTotal', 'monthList', 'filter'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    //    -------------------Sales Summary---------------------------------------------

    public function salesSummary(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All', '');
            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'item')->get()->pluck('name', 'id');
            $category->prepend('All', '');

            $data['monthList']  = $month = $this->yearMonth();
            $data['yearList']   = $this->yearList();
            $filter['category'] = __('All');
            $filter['customer'] = __('All');


            if(isset($request->year))
            {
                $year = $request->year;
            }
            else
            {
                $year = date('Y');
            }
            $data['currentYear'] = $year;

            // ------------------------------INCOME-----------------------------------
            $incomes = Income::selectRaw('sum(incomes.amount) as amount,MONTH(date) as month,YEAR(date) as year,incomes.category')->leftjoin('items', 'incomes.category', '=', 'items.id');

            $incomes->where('incomes.created_by', '=', \Auth::user()->creatorId());
            $incomes->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $incomes->where('incomes.category', '=', $request->category);
                $cat                = Category::find($request->category);
                $filter['category'] = !empty($cat) ? $cat->name : '';
            }

            if(!empty($request->customer))
            {
                $incomes->where('customer', '=', $request->customer);
                $cust               = User::find($request->customer);
                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }
            $incomes->groupBy('month', 'year', 'category');
            $incomes = $incomes->get();

            $tmpArray = [];
            foreach($incomes as $income)
            {
                $tmpArray[$income->category][$income->month] = $income->amount;
            }
            $array = [];
            foreach($tmpArray as $cat_id => $record)
            {
                $tmp             = [];
                $tmp['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $tmp['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {
                    $tmp['data'][$i] = array_key_exists($i, $record) ? $record[$i] : 0;
                }
                $array[] = $tmp;
            }


            $incomesData = Income::selectRaw('sum(	incomes.amount) as amount,MONTH(date) as month,YEAR(date) as year');
            $incomesData->where('incomes.created_by', '=', \Auth::user()->creatorId());
            $incomesData->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $incomesData->where('incomes.category', '=', $request->category);
            }
            if(!empty($request->customer))
            {
                $incomesData->where('customer', '=', $request->customer);
            }
            $incomesData->groupBy('month', 'year');
            $incomesData = $incomesData->get();
            $incomeArr   = [];
            foreach($incomesData as $k => $incomeData)
            {
                $incomeArr[$incomeData->month] = $incomeData->amount;
            }
            for($i = 1; $i <= 12; $i++)
            {
                $incomeTotal[] = array_key_exists($i, $incomeArr) ? $incomeArr[$i] : 0;
            }

            //---------------------------INVOICE INCOME-----------------------------------------------

            $invoices = Invoice:: selectRaw('MONTH(send_date) as month,YEAR(send_date) as year,category,invoice_id,id')->where('created_by', \Auth::user()->creatorId())->where('status', '!=', 0);

            $invoices->whereRAW('YEAR(send_date) =?', [$year]);

            if(!empty($request->customer))
            {
                $invoices->where('customer', '=', $request->customer);
            }

            if(!empty($request->category))
            {
                $invoices->where('category', '=', $request->category);
            }

            $invoices        = $invoices->get();
            $invoiceTmpArray = [];
            foreach($invoices as $invoice)
            {
                $invoiceTmpArray[$invoice->category][$invoice->month][] = $invoice->getTotal();
            }

            $invoiceArray = [];
            foreach($invoiceTmpArray as $cat_id => $record)
            {

                $invoice             = [];
                $invoice['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $invoice['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {

                    $invoice['data'][$i] = array_key_exists($i, $record) ? array_sum($record[$i]) : 0;
                }
                $invoiceArray[] = $invoice;
            }

            $invoiceTotalArray = [];
            foreach($invoices as $invoice)
            {
                $invoiceTotalArray[$invoice->month][] = $invoice->getTotal();
            }
            for($i = 1; $i <= 12; $i++)
            {
                $invoiceTotal[] = array_key_exists($i, $invoiceTotalArray) ? array_sum($invoiceTotalArray[$i]) : 0;
            }

            $chartIncomeArr = array_map(
                function (){
                    return array_sum(func_get_args());
                }, $incomeTotal, $invoiceTotal
            );

            $data['chartIncomeArr'] = $chartIncomeArr;
            $data['incomeArr']      = $array;
            $data['invoiceArray']   = $invoiceArray;
            $data['account']        = $account;
            $data['customer']       = $customer;
            $data['category']       = $category;

            $filter['startDateRange'] = 'Jan-' . $year;
            $filter['endDateRange']   = 'Dec-' . $year;


            return view('summary.sales', compact('filter'), $data);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function salesSummaryList(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All', '');
            $customer = User::where('type', 'customer')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');
            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'item')->get()->pluck('name', 'id');
            $category->prepend('All', '');

            $data['monthList']  = $month = $this->yearMonth();
            $data['yearList']   = $this->yearList();
            $filter['category'] = __('All');
            $filter['customer'] = __('All');


            if(isset($request->year))
            {
                $year = $request->year;
            }
            else
            {
                $year = date('Y');
            }
            $data['currentYear'] = $year;

            // ------------------------------INCOME-----------------------------------
            $incomes = Income::selectRaw('sum(incomes.amount) as amount,MONTH(date) as month,YEAR(date) as year,incomes.category')->leftjoin('items', 'incomes.category', '=', 'items.id');

            $incomes->where('incomes.created_by', '=', \Auth::user()->creatorId());
            $incomes->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $incomes->where('incomes.category', '=', $request->category);
                $cat                = Category::find($request->category);
                $filter['category'] = !empty($cat) ? $cat->name : '';
            }

            if(!empty($request->customer))
            {
                $incomes->where('customer', '=', $request->customer);
                $cust               = User::find($request->customer);
                $filter['customer'] = !empty($cust) ? $cust->name : '';
            }
            $incomes->groupBy('month', 'year', 'category');
            $incomes = $incomes->get();

            $tmpArray = [];
            foreach($incomes as $income)
            {
                $tmpArray[$income->category][$income->month] = $income->amount;
            }
            $array = [];
            foreach($tmpArray as $cat_id => $record)
            {
                $tmp             = [];
                $tmp['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $tmp['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {
                    $tmp['data'][$i] = array_key_exists($i, $record) ? $record[$i] : 0;
                }
                $array[] = $tmp;
            }


            $incomesData = Income::selectRaw('sum(	incomes.amount) as amount,MONTH(date) as month,YEAR(date) as year');
            $incomesData->where('incomes.created_by', '=', \Auth::user()->creatorId());
            $incomesData->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $incomesData->where('incomes.category', '=', $request->category);
            }
            if(!empty($request->customer))
            {
                $incomesData->where('customer', '=', $request->customer);
            }
            $incomesData->groupBy('month', 'year');
            $incomesData = $incomesData->get();
            $incomeArr   = [];
            foreach($incomesData as $k => $incomeData)
            {
                $incomeArr[$incomeData->month] = $incomeData->amount;
            }
            for($i = 1; $i <= 12; $i++)
            {
                $incomeTotal[] = array_key_exists($i, $incomeArr) ? $incomeArr[$i] : 0;
            }

            //---------------------------INVOICE INCOME-----------------------------------------------

            $invoices = Invoice:: selectRaw('MONTH(send_date) as month,YEAR(send_date) as year,category,invoice_id,id')->where('created_by', \Auth::user()->creatorId())->where('status', '!=', 0);

            $invoices->whereRAW('YEAR(send_date) =?', [$year]);

            if(!empty($request->customer))
            {
                $invoices->where('customer', '=', $request->customer);
            }

            if(!empty($request->category))
            {
                $invoices->where('category', '=', $request->category);
            }

            $invoices        = $invoices->get();
            $invoiceTmpArray = [];
            foreach($invoices as $invoice)
            {
                $invoiceTmpArray[$invoice->category][$invoice->month][] = $invoice->getTotal();
            }

            $invoiceArray = [];
            foreach($invoiceTmpArray as $cat_id => $record)
            {

                $invoice             = [];
                $invoice['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $invoice['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {

                    $invoice['data'][$i] = array_key_exists($i, $record) ? array_sum($record[$i]) : 0;
                }
                $invoiceArray[] = $invoice;
            }

            $invoiceTotalArray = [];
            foreach($invoices as $invoice)
            {
                $invoiceTotalArray[$invoice->month][] = $invoice->getTotal();
            }
            for($i = 1; $i <= 12; $i++)
            {
                $invoiceTotal[] = array_key_exists($i, $invoiceTotalArray) ? array_sum($invoiceTotalArray[$i]) : 0;
            }

            $chartIncomeArr = array_map(
                function (){
                    return array_sum(func_get_args());
                }, $incomeTotal, $invoiceTotal
            );

            $data['chartIncomeArr'] = $chartIncomeArr;
            $data['incomeArr']      = $array;
            $data['invoiceArray']   = $invoiceArray;
            $data['account']        = $account;
            $data['customer']       = $customer;
            $data['category']       = $category;

            $filter['startDateRange'] = 'Jan-' . $year;
            $filter['endDateRange']   = 'Dec-' . $year;


            return view('summary.sales_list', compact('filter'), $data);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    //    -------------------Purchase Summary---------------------------------------------

    public function purchaseSummary(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All', '');
            $vendor = User::where('type','vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vendor->prepend('All', '');
            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
            $category->prepend('All', '');

            $data['monthList']  = $month = $this->yearMonth();
            $data['yearList']   = $this->yearList();
            $filter['category'] = __('All');
            $filter['vendor']   = __('All');

            if(isset($request->year))
            {
                $year = $request->year;
            }
            else
            {
                $year = date('Y');
            }
            $data['currentYear'] = $year;

            //   -----------------------------------------EXPENSE ------------------------------------------------------------
            $expenses = Expense::selectRaw('sum(expenses.amount) as amount,MONTH(date) as month,YEAR(date) as year,expenses.category')
                               ->leftjoin('items', 'expenses.category', '=', 'items.id');
            $expenses->where('expenses.created_by', '=', \Auth::user()->creatorId());
            $expenses->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $expenses->where('expenses.category', '=', $request->category);
                $cat                = Category::find($request->category);
                $filter['category'] = !empty($cat) ? $cat->name : '';
            }
            if(!empty($request->vendor))
            {
                $expenses->where('vendor', '=', $request->vendor);

                $vend             = User::find($request->vendor);
                $filter['vendor'] = !empty($vend) ? $vend->name : '';
            }

            $expenses->groupBy('month', 'year', 'category');
            $expenses = $expenses->get();
            $tmpArray = [];
            foreach($expenses as $expense)
            {
                $tmpArray[$expense->category][$expense->month] = $expense->amount;
            }
            $array = [];
            foreach($tmpArray as $cat_id => $record)
            {
                $tmp             = [];
                $tmp['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $tmp['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {
                    $tmp['data'][$i] = array_key_exists($i, $record) ? $record[$i] : 0;
                }
                $array[] = $tmp;
            }
            $expensesData = Expense::selectRaw('sum(expenses.amount) as amount,MONTH(date) as month,YEAR(date) as year');
            $expensesData->where('expenses.created_by', '=', \Auth::user()->creatorId());
            $expensesData->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $expensesData->where('category', '=', $request->category);
            }
            if(!empty($request->vendor))
            {
                $expensesData->where('vendor', '=', $request->vendor);
            }
            $expensesData->groupBy('month', 'year');
            $expensesData = $expensesData->get();

            $expenseArr = [];
            foreach($expensesData as $k => $expenseData)
            {
                $expenseArr[$expenseData->month] = $expenseData->amount;
            }
            for($i = 1; $i <= 12; $i++)
            {
                $expenseTotal[] = array_key_exists($i, $expenseArr) ? $expenseArr[$i] : 0;
            }

            //     ------------------------------------BILL EXPENSE----------------------------------------------------

            $bills = Bill:: selectRaw('MONTH(send_date) as month,YEAR(send_date) as year,category,bill_id,id')->where('created_by', \Auth::user()->creatorId())->where('status', '!=', 0);
            $bills->whereRAW('YEAR(send_date) =?', [$year]);

            if(!empty($request->vendor))
            {
                $bills->where('vendor', '=', $request->vendor);
            }

            if(!empty($request->category))
            {
                $bills->where('category', '=', $request->category);
            }
            $bills        = $bills->get();
            $billTmpArray = [];
            foreach($bills as $bill)
            {
                $billTmpArray[$bill->category][$bill->month][] = $bill->getTotal();
            }

            $billArray = [];
            foreach($billTmpArray as $cat_id => $record)
            {

                $bill             = [];
                $bill['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $bill['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {

                    $bill['data'][$i] = array_key_exists($i, $record) ? array_sum($record[$i]) : 0;
                }
                $billArray[] = $bill;
            }

            $billTotalArray = [];
            foreach($bills as $bill)
            {
                $billTotalArray[$bill->month][] = $bill->getTotal();
            }
            for($i = 1; $i <= 12; $i++)
            {
                $billTotal[] = array_key_exists($i, $billTotalArray) ? array_sum($billTotalArray[$i]) : 0;
            }

            $chartExpenseArr = array_map(
                function (){
                    return array_sum(func_get_args());
                }, $expenseTotal, $billTotal
            );


            $data['chartExpenseArr'] = $chartExpenseArr;
            $data['expenseArr']      = $array;
            $data['billArray']       = $billArray;
            $data['account']         = $account;
            $data['vendor']          = $vendor;
            $data['category']        = $category;

            $filter['startDateRange'] = 'Jan-' . $year;
            $filter['endDateRange']   = 'Dec-' . $year;

            return view('summary.purchase', compact('filter'), $data);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function purchaseSummaryList(Request $request)
    {
        if(\Auth::user()->can('manage summary'))
        {
            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All', '');
            $vendor = User::where('type','vendor')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vendor->prepend('All', '');
            $category = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get()->pluck('name', 'id');
            $category->prepend('All', '');

            $data['monthList']  = $month = $this->yearMonth();
            $data['yearList']   = $this->yearList();
            $filter['category'] = __('All');
            $filter['vendor']   = __('All');

            if(isset($request->year))
            {
                $year = $request->year;
            }
            else
            {
                $year = date('Y');
            }
            $data['currentYear'] = $year;

            //   -----------------------------------------EXPENSE ------------------------------------------------------------
            $expenses = Expense::selectRaw('sum(expenses.amount) as amount,MONTH(date) as month,YEAR(date) as year,expenses.category')
                               ->leftjoin('items', 'expenses.category', '=', 'items.id');
            $expenses->where('expenses.created_by', '=', \Auth::user()->creatorId());
            $expenses->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $expenses->where('expenses.category', '=', $request->category);
                $cat                = Category::find($request->category);
                $filter['category'] = !empty($cat) ? $cat->name : '';
            }
            if(!empty($request->vendor))
            {
                $expenses->where('vendor', '=', $request->vendor);

                $vend             = User::find($request->vendor);
                $filter['vendor'] = !empty($vend) ? $vend->name : '';
            }

            $expenses->groupBy('month', 'year', 'category');
            $expenses = $expenses->get();
            $tmpArray = [];
            foreach($expenses as $expense)
            {
                $tmpArray[$expense->category][$expense->month] = $expense->amount;
            }
            $array = [];
            foreach($tmpArray as $cat_id => $record)
            {
                $tmp             = [];
                $tmp['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $tmp['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {
                    $tmp['data'][$i] = array_key_exists($i, $record) ? $record[$i] : 0;
                }
                $array[] = $tmp;
            }
            $expensesData = Expense::selectRaw('sum(expenses.amount) as amount,MONTH(date) as month,YEAR(date) as year');
            $expensesData->where('expenses.created_by', '=', \Auth::user()->creatorId());
            $expensesData->whereRAW('YEAR(date) =?', [$year]);

            if(!empty($request->category))
            {
                $expensesData->where('category', '=', $request->category);
            }
            if(!empty($request->vendor))
            {
                $expensesData->where('vendor', '=', $request->vendor);
            }
            $expensesData->groupBy('month', 'year');
            $expensesData = $expensesData->get();

            $expenseArr = [];
            foreach($expensesData as $k => $expenseData)
            {
                $expenseArr[$expenseData->month] = $expenseData->amount;
            }
            for($i = 1; $i <= 12; $i++)
            {
                $expenseTotal[] = array_key_exists($i, $expenseArr) ? $expenseArr[$i] : 0;
            }

            //     ------------------------------------BILL EXPENSE----------------------------------------------------

            $bills = Bill:: selectRaw('MONTH(send_date) as month,YEAR(send_date) as year,category,bill_id,id')->where('created_by', \Auth::user()->creatorId())->where('status', '!=', 0);
            $bills->whereRAW('YEAR(send_date) =?', [$year]);

            if(!empty($request->vendor))
            {
                $bills->where('vendor', '=', $request->vendor);
            }

            if(!empty($request->category))
            {
                $bills->where('category', '=', $request->category);
            }
            $bills        = $bills->get();
            $billTmpArray = [];
            foreach($bills as $bill)
            {
                $billTmpArray[$bill->category][$bill->month][] = $bill->getTotal();
            }

            $billArray = [];
            foreach($billTmpArray as $cat_id => $record)
            {

                $bill             = [];
                $bill['category'] = !empty(Category::where('id', '=', $cat_id)->first()) ? Category::where('id', '=', $cat_id)->first()->name : '';
                $bill['data']     = [];
                for($i = 1; $i <= 12; $i++)
                {

                    $bill['data'][$i] = array_key_exists($i, $record) ? array_sum($record[$i]) : 0;
                }
                $billArray[] = $bill;
            }

            $billTotalArray = [];
            foreach($bills as $bill)
            {
                $billTotalArray[$bill->month][] = $bill->getTotal();
            }
            for($i = 1; $i <= 12; $i++)
            {
                $billTotal[] = array_key_exists($i, $billTotalArray) ? array_sum($billTotalArray[$i]) : 0;
            }

            $chartExpenseArr = array_map(
                function (){
                    return array_sum(func_get_args());
                }, $expenseTotal, $billTotal
            );


            $data['chartExpenseArr'] = $chartExpenseArr;
            $data['expenseArr']      = $array;
            $data['billArray']       = $billArray;
            $data['account']         = $account;
            $data['vendor']          = $vendor;
            $data['category']        = $category;

            $filter['startDateRange'] = 'Jan-' . $year;
            $filter['endDateRange']   = 'Dec-' . $year;

            return view('summary.purchase_list', compact('filter'), $data);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function yearMonth()
    {

        $month[] = __('January');
        $month[] = __('February');
        $month[] = __('March');
        $month[] = __('April');
        $month[] = __('May');
        $month[] = __('June');
        $month[] = __('July');
        $month[] = __('August');
        $month[] = __('September');
        $month[] = __('October');
        $month[] = __('November');
        $month[] = __('December');

        return $month;
    }

    public function yearList()
    {
        $starting_year = date('Y', strtotime('-5 year'));
        $ending_year   = date('Y');

        foreach(range($ending_year, $starting_year) as $year)
        {
            $years[$year] = $year;
        }

        return $years;
    }


}
