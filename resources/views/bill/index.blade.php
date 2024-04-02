@extends('layouts.app')
@section('page-title')
    {{__('Bills')}}
@endsection

@section('action-button')
    <a href="{{ route('bill.create') }}" data-size="lg" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Bill')}}</h6>
                </div>
                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('bill.index'),'method' => 'GET','id'=>'frm_submit')) }}
                        <div class="row d-flex justify-content-end">

                            <div class="col-auto">
                                <div class="all-select-box">
                                    <div class="btn-box">
                                        {{ Form::text('bill_date', isset($_GET['bill_date'])?$_GET['bill_date']:null, array('class' => 'month-btn form-control datepicker-range')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="all-select-box">
                                    <div class="btn-box">
                                        {{ Form::select('vendor',$vendor,isset($_GET['vendor'])?$_GET['vendor']:'', array('class' => 'form-control select2')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="all-select-box">
                                    <div class="btn-box">
                                        {{ Form::select('status', [''=>'All Status'] + $status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('frm_submit').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('bill.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                    <th scope="col" class="sort"> {{__('Bill')}}</th>
                    <th scope="col" class="sort"> {{__('Vendor')}}</th>
                    <th scope="col" class="sort"> {{__('Category')}}</th>
                    <th scope="col" class="sort"> {{__('Bill Date')}}</th>
                    <th scope="col" class="sort"> {{__('Due Date')}}</th>
                    <th scope="col" class="sort"> {{__('Status')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($bills as $bill)
                    <tr class="font-style">
                        <td class="budget">{{ \Auth::user()->billNumberFormat($bill->bill_id)}}</td>
                        <td class="budget">{{!empty($bill->users)? $bill->users->name:'-' }}</td>
                        <td class="budget">{{!empty($bill->categories)? $bill->categories->name:'-' }}</td>
                        <td class="budget">{{ \Auth::user()->dateFormat($bill->bill_date) }}</td>
                        <td class="budget">{{ \Auth::user()->dateFormat($bill->due_date) }}</td>
                        <td>
                            @if($bill->status == 0)
                                <span class="badge badge-pill badge-primary">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                            @elseif($bill->status == 1)
                                <span class="badge badge-pill badge-info">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                            @elseif($bill->status == 2)
                                <span class="badge badge-pill badge-success">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                            @elseif($bill->status == 3)
                                <span class="badge badge-pill badge-warning">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                            @elseif($bill->status == 4)
                                <span class="badge badge-pill badge-danger">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                            @endif
                        </td>
                        @if(Gate::check('edit bill') || Gate::check('delete bill'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="{{ route('bill.show',\Crypt::encrypt($bill->id)) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Show')}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bill.edit',\Crypt::encrypt($bill->id)) }}" data-size="lg" data-title="{{__('Edit Bill')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$bill->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['bill.destroy', $bill->id],'id'=>'delete-form-'.$bill->id]) !!}
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
