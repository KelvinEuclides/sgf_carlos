@extends('layouts.app')

@section('page-title')
    {{__('Expenses')}}
@endsection
@section('action-button')
    @can('manage expense')
        <a href="#" data-size="lg" data-url="{{ route('expense.create') }}" data-ajax-popup="true" data-title="{{__('Create New Expense')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('expense.index'),'method' => 'GET','id'=>'payment_form')) }}
                        <div class="row d-flex justify-content-end">
                            <div class="col-auto">
                                <div class="btn-box">
                                    {{ Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'form-control month-btn datepicker-range')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('account',$account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('vendor',$vendor,isset($_GET['vendor'])?$_GET['vendor']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('category',$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('payment_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('expense.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                    <th scope="col" class="sort"> {{__('Amount')}}</th>
                    <th scope="col" class="sort"> {{__('Account')}}</th>
                    <th scope="col" class="sort"> {{__('Vendor')}}</th>
                    <th scope="col" class="sort"> {{__('Category')}}</th>
                    <th scope="col" class="sort"> {{__('Reference')}}</th>
                    <th scope="col" class="sort"> {{__('Description')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($expenses as $expense)
                    <tr class="font-style">
                        <td class="budget">{{  \Auth::user()->dateFormat($expense->date)}}</td>
                        <td class="budget">{{  \Auth::user()->priceFormat($expense->amount)}}</td>
                        <td class="budget">{{ !empty($expense->bankAccount)?$expense->bankAccount->bank_name.' '.$expense->bankAccount->holder_name:''}}</td>
                        <td class="budget">{{  (!empty($expense->user)?$expense->user->name:'-')}}</td>
                        <td class="budget">{{  !empty($expense->categories)?$expense->categories->name:'-'}}</td>
                        <td class="budget">{{  !empty($expense->reference)?$expense->reference:'-'}}</td>
                        <td class="budget">{{  !empty($expense->description)?$expense->description:'-'}}</td>
                        @if(Gate::check('edit expense') || Gate::check('delete expense'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="#" data-size="lg" data-url="{{ route('expense.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$expense->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['expense.destroy', $expense->id],'id'=>'delete-form-'.$expense->id]) !!}
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
