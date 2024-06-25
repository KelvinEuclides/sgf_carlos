@extends('layouts.app')
@section('page-title')
    @if(\Auth::user()->type=='super admin')
        {{__('Company')}}
    @else
        {{__('Users')}}
    @endif
@endsection
@section('action-button')
    <a href="#" data-size="md" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-title="{{__('Create New User')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="row">
        @foreach($users as $user)
            <div class="col-xl-3 col-lg-3 col-sm-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img src="{{(!empty($user->avatar))? asset(Storage::url("uploads/avatar/".$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="avatar rounded-circle avatar-md">
                                </a>
                            </div>
                            <div class="col ml-md-n2">
                                <a href="#!" class="d-block h6 mb-0">{{ $user->name }}</a>
                                <small class="d-block text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        @if(\Auth::user()->type=='super admin')
                            <div class="row">
                                <div class="col-auto">
                                    <span class="h6 text-sm mb-0">{{!empty($user->currentSubscription)?$user->currentSubscription->name:''}}</span>
                                    <span class="d-block text-sm">{{__('Subscription')}}</span>
                                </div>

                                <div class="col text-right">
                                    <span class="h6 text-sm mb-0"><a href="#" data-url="{{ route('subscription.upgrade',$user->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Upgrade Subscription')}}"><i class="fa fa-wrench" style="font-size: 22px;"></i></a></span>
                                    <span class="d-block text-sm">{{__('Upgrade')}}</span>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-auto text-center">
                                    <span class="h6 mb-0">{{$user->totalCompanyUser($user->id)}}</span>
                                    <span class="d-block text-sm">{{__('User')}}</span>
                                </div>
                                <div class="col text-center">
                                    <span class="h6 mb-0">{{$user->totalCompanyCustomer($user->id)}}</span>
                                    <span class="d-block text-sm">{{__('Customer')}}</span>
                                </div>
                                <div class="col-auto text-center">
                                    <span class="h6 mb-0">{{$user->totalCompanyVendor($user->id)}}</span>
                                    <span class="d-block text-sm">{{__('Vendor')}}</span>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col text-center">
                                    <span class="text-dark text-xs">{{__('Subscription Expired : ') }} {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date): __('Unlimited')}}</span>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-auto">
                                    <span class="h6 text-sm mb-0">{{$user->type}}</span>
                                    <span class="d-block text-sm">{{__('Type')}}</span>
                                </div>

                                <div class="col text-right">
                                    <span class="h6 text-sm mb-0">{{\Auth::user()->dateFormat($user->created_at)}}</span>
                                    <span class="d-block text-sm">{{__('Created At')}}</span>
                                </div>
                            </div>
                        @endif

                    </div>
                    @if(Gate::check('edit user') || Gate::check('delete user') || \Auth::user()->type=='super admin')
                        <div class="card-footer">
                            <div class="row align-items-center">
                                @if($user->is_active==1)
                                    <div class="col-8">
                                        @if(Gate::check('edit user') || \Auth::user()->type=='super admin')
                                            <a href="#" class="dropdown-item text-sm" data-url="{{ route('users.edit',$user->id) }}" data-ajax-popup="true" data-title="{{__('Update User')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"> <i class="far fa-edit"></i></a>
                                        @endcan
                                    </div>
                                    <div class="col-2 text-right">
                                        @if(Gate::check('disable user') || \Auth::user()->type=='super admin')
                                            <a data-toggle="tooltip" data-original-title="{{__('Disable')}}" class="dropdown-item text-sm" data-confirm="{{__('Warning').'|'.__('Are you sure you want to disable ?')}}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                            {!! Form::open(['method' => 'POST', 'route' => ['customers.disable', $user['id']],'id'=>'disable-form-'.$user['id']]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                    <div class="col-2 text-right">
                                        @if(Gate::check('delete user') || \Auth::user()->type=='super admin')
                                            <a data-toggle="tooltip" data-original-title="{{__('Delete')}}" class="dropdown-item text-sm" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$user['id']}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
