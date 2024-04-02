@extends('layouts.app')
@section('page-title')
    {{__('Bill')}}
@endsection

@section('action-button')
    <a class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i data-toggle="tooltip" data-title="{{__('Filter')}}" class="fas fa-filter"></i>
    </a>
    <a href="{{route('bill.summary')}}"  data-toggle="tooltip" data-title="{{__('Chart View')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle "><i class="fas fa-chart-bar"></i></a>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="collapse {{isset($_GET['start_month']) || isset($_GET['end_month']) || isset($_GET['customer']) || isset($_GET['status'])?'show':''}}" id="collapseExample">
                <div class="card card-body">
                    <div class="row d-flex justify-content-end">
                        <div class="col">
                            {{ Form::open(array('route' => array('bill.summary.list'),'method' => 'GET','id'=>'bill_summary')) }}
                            <div class="all-select-box">
                                {{ Form::label('start_month', __('Start Month'),['class'=>'text-type']) }}
                                {{ Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'', array('class' => 'month-btn form-control')) }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('end_month', __('End Month'),['class'=>'text-type']) }}
                                    {{ Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'', array('class' => 'month-btn form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('vendor', __('Vendor'),['class'=>'text-type']) }}
                                    {{ Form::select('vendor',$vendor,isset($_GET['vendor'])?$_GET['vendor']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'),['class'=>'text-type']) }}
                                    {{ Form::select('status', [''=>'All']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <a href="#" class="btn btn-sm btn-primary mr-auto" onclick="document.getElementById('bill_summary').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('Apply Now')}}">
                                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            </a>
                            <a href="{{route('bill.summary.list')}}" class="btn btn-sm btn-primary mr-auto" data-toggle="tooltip" data-original-title="{{__('Reset Now')}}">
                                <span class="btn-inner--icon"><i class="fas fa-minus-circle"></i></span>
                            </a>

                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div id="printableArea">
        <div class="card p-4 mt-4">
            <div class="row">
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Summary')}} :</h6>
                    <h6 class="report-text mb-0 text-muted">{{__('Bill Report')}}</h6>
                </div>
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Duration')}} :</h6>
                    <h6 class="report-text mb-0 text-muted">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Total Bill')}}</h6>
                    <h6 class="report-text mb-0 text-muted">{{Auth::user()->priceFormat($totalBill)}}</h6>
                </div>
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Total Paid')}}</h6>
                    <h6 class="report-text mb-0 text-muted">{{Auth::user()->priceFormat($totalPaidBill)}}</h6>
                </div>
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Total Due')}}</h6>
                    <h6 class="report-text mb-0 text-muted">{{Auth::user()->priceFormat($totalDueBill)}}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-flush dataTable">
                                <thead>
                                <tr>
                                    <th> {{__('Bill')}}</th>
                                    <th> {{__('Date')}}</th>
                                    <th> {{__('Customer')}}</th>
                                    <th> {{__('Category')}}</th>
                                    <th> {{__('Status')}}</th>
                                    <th> {{__('	Paid Amount')}}</th>
                                    <th> {{__('Due Amount')}}</th>
                                    <th> {{__('Payment Date')}}</th>
                                    <th> {{__('Amount')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($bills as $bill)
                                    <tr>
                                        <td class="Id">
                                            <a href="{{ route('bill.show',$bill->id) }}">
                                                {{ AUth::user()->billNumberFormat($bill->bill_id) }}
                                            </a>
                                        </td>
                                        <td>{{ Auth::user()->dateFormat($bill->send_date) }}</td>
                                        <td> {{!empty($bill->user)? $bill->user->name:'-' }} </td>
                                        <td>{{ !empty($bill->categories)?$bill->categories->name:'-'}}</td>
                                        <td>
                                            @if($bill->status == 0)
                                                <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 1)
                                                <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 2)
                                                <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 3)
                                                <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                                            @elseif($bill->status == 4)
                                                <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$bill->status]) }}</span>
                                            @endif
                                        </td>
                                        <td> {{\Auth::user()->priceFormat($bill->getTotal()-$bill->getDue())}}</td>
                                        <td> {{\Auth::user()->priceFormat($bill->getDue())}}</td>
                                        <td>{{!empty($bill->lastPayments)?\Auth::user()->dateFormat($bill->lastPayments->date):''}}</td>
                                        <td> {{\Auth::user()->priceFormat($bill->getTotal())}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
