@extends('layouts.app')
@section('page-title')
    {{__('Estimations')}}
@endsection

@section('action-button')
    <a href="{{ route('estimation.create') }}" data-size="lg" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Estimation')}}</h6>
                </div>
                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('estimation.index'),'method' => 'GET','id'=>'frm_submit')) }}
                        <div class="row d-flex justify-content-end mt-2">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="btn-box">
                                    {{ Form::text('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:'', array('class' => 'form-control datepicker-range')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::select('status', [''=>'All Status']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('frm_submit').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('estimation.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                    <th scope="col" class="sort"> {{__('Estimation')}}</th>
                    <th scope="col" class="sort"> {{__('Customer')}}</th>
                    <th scope="col" class="sort"> {{__('Category')}}</th>
                    <th scope="col" class="sort"> {{__('Issue Date')}}</th>
                    <th scope="col" class="sort"> {{__('Status')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($estimations as $estimation)

                    <tr class="font-style">
                        <td class="budget">{{ \Auth::user()->estimationNumberFormat($estimation->estimation_id)}}</td>
                        <td class="budget">{{!empty($estimation->users)? $estimation->users->name:'-' }}</td>
                        <td class="budget">{{!empty($estimation->categories)? $estimation->categories->name:'-' }}</td>
                        <td class="budget">{{ Auth::user()->dateFormat($estimation->issue_date) }}</td>
                        <td>
                            @if($estimation->status == 0)
                                <span class="badge badge-pill badge-primary">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                            @elseif($estimation->status == 1)
                                <span class="badge badge-pill badge-info">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                            @elseif($estimation->status == 2)
                                <span class="badge badge-pill badge-success">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                            @elseif($estimation->status == 3)
                                <span class="badge badge-pill badge-warning">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                            @elseif($estimation->status == 4)
                                <span class="badge badge-pill badge-danger">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                            @endif
                        </td>
                        @if(Gate::check('edit estimation') || Gate::check('delete estimation'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="{{ route('estimation.show',\Illuminate\Support\Facades\Crypt::encrypt($estimation->id)) }}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Show')}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('estimation.edit',\Illuminate\Support\Facades\Crypt::encrypt($estimation->id)) }}" data-size="lg" data-title="{{__('Edit Estimation')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$estimation->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['estimation.destroy', $estimation->id],'id'=>'delete-form-'.$estimation->id]) !!}
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
