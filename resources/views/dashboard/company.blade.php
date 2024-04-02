@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
    <script>

        var SalesChart = {
            series: [
                {
                    name: "{{__('Sales')}}",
                    data: {!! json_encode($incExpBarChartData['income']) !!}
                },
                {
                    name: "{{__('Purchase')}}",
                    data: {!! json_encode($incExpBarChartData['expense']) !!}
                }
            ],
            colors: ['#36B37E', '#FF5630'],
            chart: {
                type: 'bar',
                height: 430
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: {!! json_encode($incExpBarChartData['month']) !!},
            },
        };
        var sales = new ApexCharts(document.querySelector("#salesPurchaseChart"), SalesChart);
        sales.render();

    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Customers')}}</h6>
                            <span class="h3 font-weight-bold mb-0 ">{{\Auth::user()->countCustomers()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon bg-gradient-success text-white rounded-circle icon-shape">
                                <i class="fas fa-user-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Vendors')}}</h6>
                            <span class="h3 font-weight-bold mb-0 ">{{\Auth::user()->countVendors()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon bg-gradient-danger text-white rounded-circle icon-shape">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Invoice')}}</h6>
                            <span class="h3 font-weight-bold mb-0 ">{{\Auth::user()->countInvoices()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon bg-gradient-success text-white rounded-circle icon-shape">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Bill')}}</h6>
                            <span class="h3 font-weight-bold mb-0 ">{{\Auth::user()->countBills()}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon bg-gradient-danger text-white rounded-circle icon-shape">
                                <i class="fas fa-file"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div>
                <h4 class="h4 font-weight-400 ">{{__('Sales Vs Purchase').' - '.$currentYear}}</h4>
            </div>
            <div class="card">
                <div id="salesPurchaseChart" class="chart-scroll" height="250"></div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="">
                <h4 class="h4 font-weight-400">{{__('Latest Invoices')}}</h4>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Customer')}}</th>
                            <th>{{__('Issue Date')}}</th>
                            <th>{{__('Due Date')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Status')}}</th>
                        </tr>
                        </thead>

                        <tbody class="list">
                        @forelse($recentInvoice as $invoice)

                            <tr>
                                <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                <td>{{!empty($invoice->users)? $invoice->users->name:'' }} </td>
                                <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                <td>
                                    @if($invoice->status == 0)
                                        <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 1)
                                        <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 2)
                                        <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 3)
                                        <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 4)
                                        <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center">
                                        <h6>{{__('Record not found')}}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="">
                <h4 class="h4 font-weight-400">{{__('Latest Bills')}}</h4>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Vendor')}}</th>
                            <th>{{__('Bill Date')}}</th>
                            <th>{{__('Due Date')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Status')}}</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($recentBill as $bill)

                            <tr>
                                <td>{{\Auth::user()->billNumberFormat($bill->bill_id)}}</td>
                                <td>{{!empty($bill->users)? $bill->users->name:'' }} </td>
                                <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                                <td>{{ Auth::user()->dateFormat($bill->due_date) }}</td>
                                <td>{{\Auth::user()->priceFormat($bill->getTotal())}}</td>
                                <td>
                                    @if($bill->status == 0)
                                        <span class="badge badge-pill badge-primary">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                    @elseif($bill->status == 1)
                                        <span class="badge badge-pill badge-warning">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                    @elseif($bill->status == 2)
                                        <span class="badge badge-pill badge-danger">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                    @elseif($bill->status == 3)
                                        <span class="badge badge-pill badge-info">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                    @elseif($bill->status == 4)
                                        <span class="badge badge-pill badge-success">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center">
                                        <h6>{{__('Record not found')}}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="">
                <h4 class="h4 font-weight-400">{{__('Latest Income')}}</h4>

            </div>
            <div class="card card-fluid">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Customer')}}</th>
                            <th>{{__('Amount Due')}}</th>
                            <th>{{__('Description')}}</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($latestIncome as $income)
                            <tr>
                                <td>{{\Auth::user()->dateFormat($income->date)}}</td>
                                <td>{{!empty($income->user)?$income->user->name:''}}</td>
                                <td>{{\Auth::user()->priceFormat($income->amount)}}</td>
                                <td>{{$income->description}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center">
                                        <h6>{{__('Record not found')}}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="">
                <h4 class="h4 font-weight-400">{{__('Latest Expense')}}</h4>

            </div>
            <div class="card card-fluid">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Customer')}}</th>
                            <th>{{__('Amount Due')}}</th>
                            <th>{{__('Description')}}</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($latestExpense as $expense)
                            <tr>
                                <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                <td>{{!empty($expense->user)?$expense->user->name:''}}</td>
                                <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                <td>{{$expense->description}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center">
                                        <h6>{{__('Record not found')}}</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
