@extends('layouts.app')
@section('page-title')
    {{__('Bill')}}
@endsection

@push('theme-script')
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
@endpush

@push('script-page')
    <script>

        var SalesChart = {
            series: [
                {
                    name: "{{__('Bill')}}",
                    data: {!! json_encode($billTotal) !!}
                }
            ],
            colors: ['#FF5630'],
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
                categories: {!! json_encode($monthList) !!},
            },
        };
        var sales = new ApexCharts(document.querySelector("#chart-sales"), SalesChart);
        sales.render();

    </script>
@endpush
@section('action-button')
    <a class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i data-toggle="tooltip" data-title="{{__('Filter')}}" class="fas fa-filter"></i>
    </a>
    <a href="{{route('bill.summary.list')}}"  data-toggle="tooltip" data-title="{{__('List View')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle "><i class="fas fa-th-list"></i></a>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="collapse {{isset($_GET['start_month']) || isset($_GET['end_month']) || isset($_GET['customer']) || isset($_GET['status'])?'show':''}}" id="collapseExample">
                <div class="card card-body">
                    <div class="row d-flex justify-content-end">
                        <div class="col">
                            {{ Form::open(array('route' => array('bill.summary'),'method' => 'GET','id'=>'bill_summary')) }}
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
                            <a href="{{route('bill.summary')}}" class="btn btn-sm btn-primary mr-auto" data-toggle="tooltip" data-original-title="{{__('Reset Now')}}">
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
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="chart-sales" class="chart-scroll" height="250"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
