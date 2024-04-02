@extends('layouts.app')
@section('page-title')
    {{__('Purchase')}}
@endsection

@section('action-button')
    <a class="btn btn-sm btn-white btn-icon-only rounded-circle" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <i data-toggle="tooltip" data-title="{{__('Filter')}}" class="fas fa-filter"></i>
    </a>
    <a href="{{route('purchase.summary.list')}}"  data-toggle="tooltip" data-title="{{__('Chart View')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle "><i class="fas fa-chart-bar"></i></a>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="collapse {{isset($_GET['start_month']) || isset($_GET['end_month']) || isset($_GET['customer']) || isset($_GET['status'])?'show':''}}" id="collapseExample">
                <div class="card card-body">
                    <div class="row d-flex justify-content-end">
                        <div class="col">
                            {{ Form::open(array('route' => array('purchase.summary.list'),'method' => 'GET','id'=>'estimation_summary')) }}
                            <div class="all-select-box">
                                {{ Form::label('year', __('Year'),['class'=>'text-type']) }}
                                {{ Form::select('year',$yearList,isset($_GET['year'])?$_GET['year']:'', array('class' => 'form-control select2')) }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="all-select-box">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'),['class'=>'text-type']) }}
                                    {{ Form::select('category',$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select2')) }}
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
                        <div class="col-auto my-auto">
                            <a href="#" class="btn btn-sm btn-primary mr-auto" onclick="document.getElementById('estimation_summary').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('Apply Now')}}">
                                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            </a>
                            <a href="{{route('purchase.summary.list')}}" class="btn btn-sm btn-primary mr-auto" data-toggle="tooltip" data-original-title="{{__('Reset Now')}}">
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
                    <h6 class="report-text mb-0 text-muted">{{__('Purchase Report')}}</h6>
                </div>
                <div class="col">
                    <h6 class="report-text gray-text mb-0">{{__('Duration')}} :</h6>
                    <h6 class="report-text mb-0 text-muted">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="header__title">{{__('Expense')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" id="dataTable-manual">
                                <thead>
                                <tr>
                                    <th>{{__('Category')}}</th>
                                    @foreach($monthList as $month)
                                        <th>{{$month}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($expenseArr as $i=>$expense)
                                    <tr>
                                        <td>{{$expense['category']}}</td>
                                        @foreach($expense['data'] as $j=>$data)
                                            <td>{{\Auth::user()->priceFormat($data)}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="header__title">{{__('Bill')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" id="dataTable-manual">
                                <thead>
                                <tr>
                                    <th>{{__('Category')}}</th>
                                    @foreach($monthList as $month)
                                        <th>{{$month}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($billArray as $i=>$bill)
                                    <tr>
                                        <td>{{$bill['category']}}</td>
                                        @foreach($bill['data'] as $j=>$data)
                                            <td>{{\Auth::user()->priceFormat($data)}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="header__title">{{__('Purchase = Expense + Bill')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" id="dataTable-manual">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    @foreach($monthList as $month)
                                        <th>{{$month}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td class="text-dark">{{__('Total')}}</td>
                                    @foreach($chartExpenseArr as $i=>$expense)
                                        <td>{{\Auth::user()->priceFormat($expense)}}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
