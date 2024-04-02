@extends('layouts.app')
@section('page-title')
    {{__('Bank Transfers')}}
@endsection
@section('action-button')
    @can('manage banking')
        <a href="#" data-size="lg" data-url="{{ route('transfer.create') }}" data-ajax-popup="true" data-title="{{__('Create New Bank Transfer')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Bank Transfer')}}</h6>
                </div>
                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('transfer.index'),'method' => 'GET','id'=>'transfer_form')) }}
                        <div class="row d-flex justify-content-end mt-2">
                            <div class="col-auto">
                                <div class="btn-box">
                                    {{ Form::text('date', isset($_GET['date'])?$_GET['date']:'', array('class' => 'form-control month-btn datepicker-range')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('f_account',$from_account,isset($_GET['f_account'])?$_GET['f_account']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('t_account', $to_account,isset($_GET['t_account'])?$_GET['t_account']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('transfer_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('transfer.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                    <th scope="col" class="sort"> {{__('Date')}}</th>
                    <th scope="col" class="sort"> {{__('From Account')}}</th>
                    <th scope="col" class="sort"> {{__('To Account')}}</th>
                    <th scope="col" class="sort"> {{__('Amount')}}</th>
                    <th scope="col" class="sort"> {{__('Reference')}}</th>
                    <th scope="col" class="sort"> {{__('Description')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($transfers as $transfer)
                    <tr class="font-style">
                        <td class="budget">{{ \Auth::user()->dateFormat( $transfer->date) }}</td>
                        <td class="budget">{{ !empty($transfer->fromBankAccount())? $transfer->fromBankAccount()->bank_name.' '.$transfer->fromBankAccount()->holder_name:''}}</td>
                        <td class="budget">{{!empty( $transfer->toBankAccount())? $transfer->toBankAccount()->bank_name.' '. $transfer->toBankAccount()->holder_name:''}}</td>
                        <td class="budget">{{  \Auth::user()->priceFormat( $transfer->amount)}}</td>
                        <td class="budget">{{  $transfer->reference}}</td>
                        <td class="budget">{{  $transfer->description}}</td>

                        @if(Gate::check('edit banking') || Gate::check('delete banking'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="#" data-size="lg" data-url="{{ route('transfer.edit',$transfer->id) }}" data-ajax-popup="true" data-title="{{__('Edit Bank Account')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$transfer->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['transfer.destroy', $transfer->id],'id'=>'delete-form-'.$transfer->id]) !!}
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
