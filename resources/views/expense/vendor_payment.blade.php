@extends('layouts.app')

@section('page-title')
    {{__('Payment')}}
@endsection
@section('action-button')

@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">

                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('vendor.payment'),'method' => 'GET','id'=>'payment_form')) }}
                        <div class="row d-flex justify-content-end">
                            <div class="col-auto">
                                <div class="btn-box">
                                    {{ Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'month-btn form-control datepicker-range')) }}
                                </div>
                            </div>

                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('payment_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('vendor.payment')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                                    <span class="btn-inner--icon"><i class="far fa-window-restore"></i></span>
                                </a>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th> {{__('Date')}}</th>
                    <th> {{__('Amount')}}</th>
                    <th> {{__('Category')}}</th>
                    <th> {{__('Description')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($payments as $payment)

                    <tr>
                        <td>{{  \Auth::user()->dateFormat($payment->date)}}</td>
                        <td>{{  \Auth::user()->priceFormat($payment->amount)}}</td>
                        <td>{{  !empty($payment->category)?$payment->category:'-'}}</td>
                        <td>{{  !empty($payment->description)?$payment->description:'-'}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
