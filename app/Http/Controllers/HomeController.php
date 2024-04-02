<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Expense;
use App\Income;
use App\Invoice;
use App\Subscription;
use App\UserSubscriber;
use Illuminate\Http\Request;
use Stripe;

class HomeController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    public function index()
    {
        if(\Auth::user()->type == 'super admin')
        {
            $user                           = \Auth::user();
            $user['total_user']             = $user->countCompany();
            $user['total_paid_user']        = $user->countPaidCompany();
            $user['total_subscriber']       = UserSubscriber::total_subscriber();
            $user['total_subscriber_price'] = UserSubscriber::total_subscriber_price();
            $user['total_subscription']     = Subscription::total_subscription();
            $chartData                      = $this->getSubscriberChart(['duration' => 'week']);

            return view('dashboard.super_admin', compact('user', 'chartData'));
        }
        elseif(\Auth::user()->type == 'customer')
        {
            $data['invoiceChartData'] = \Auth::user()->customerInvoiceChartData();

            return view('dashboard.customer', $data);
        }
        elseif(\Auth::user()->type == 'vendor')
        {
            $data['billChartData'] = \Auth::user()->billChartData();

            return view('dashboard.vendor', $data);
        }
        else
        {
            $data['currentYear']  = date('Y');
            $data['currentMonth'] = date('M');

            $data['incExpBarChartData'] = \Auth::user()->getincExpBarChartData();

            $data['recentInvoice'] = Invoice::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
            $data['recentBill']    = Bill::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();

            $data['latestIncome']  = Income::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();
            $data['latestExpense'] = Expense::where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'desc')->limit(5)->get();

            return view('dashboard.company', $data);
        }

    }

    public function getSubscriberChart($arrParam)
    {
        $arrDuration = [];
        if($arrParam['duration'])
        {
            if($arrParam['duration'] == 'week')
            {
                $previous_week = strtotime("-2 week +1 day");
                for($i = 0; $i < 14; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach($arrDuration as $date => $label)
        {

            $data               = UserSubscriber::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }


}

