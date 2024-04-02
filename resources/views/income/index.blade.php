@extends('layouts.app')

@section('page-title')
    {{__('Incomes')}}
@endsection
@section('action-button')
    @can('manage income')
        <a href="#" data-size="lg" data-url="{{ route('income.create') }}" data-ajax-popup="true" data-title="{{__('Create New Income')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">

                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('income.index'),'method' => 'GET','id'=>'revenue_form')) }}
                        <div class="row d-flex justify-content-end">
                            <div class="col-auto">
                                <div class="btn-box">
                                    {{ Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'month-btn form-control datepicker-range')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('account',$account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('category',$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('revenue_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('income.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                    <th scope="col" class="sort"> {{__('Customer')}}</th>
                    <th scope="col" class="sort"> {{__('Category')}}</th>
                    <th scope="col" class="sort"> {{__('Reference')}}</th>
                    <th scope="col" class="sort"> {{__('Description')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($incomes as $income)

                    <tr class="font-style">
                        <td class="budget">{{  Auth::user()->dateFormat($income->date)}}</td>
                        <td class="budget">{{  Auth::user()->priceFormat($income->amount)}}</td>
                        <td class="budget">{{ !empty($income->bankAccount)?$income->bankAccount->bank_name.' '.$income->bankAccount->holder_name:''}}</td>
                        <td class="budget">{{  (!empty($income->user)?$income->user->name:'-')}}</td>
                        <td class="budget">{{  !empty($income->categories)?$income->categories->name:'-'}}</td>
                        <td class="budget">{{  !empty($income->reference)?$income->reference:'-'}}</td>
                        <td class="budget">{{  !empty($income->description)?$income->description:'-'}}</td>
                        @if(Gate::check('edit income') || Gate::check('delete income'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="#" data-size="lg" data-url="{{ route('income.edit',$income->id) }}" data-ajax-popup="true" data-title="{{__('Edit Bank Income')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$income->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['income.destroy', $income->id],'id'=>'delete-form-'.$income->id]) !!}
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
