@extends('layouts.app')
@section('page-title')
    {{__('Customer Detail')}}
@endsection
@section('action-button')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="card card-fluid">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{\Auth::user()->customerNumberFormat(!empty($user->customers)?$user->customers->customer_id:0)}}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                        <img alt="Image placeholder" src="{{(!empty($user->avatar))? asset(Storage::url("uploads/avatar/".$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="">
                    </a>
                    <h5 class="h6 my-4">{{$user->name}}</h5>
                    <h6 class="h6 my-4 text-muted">{{$user->email}}</h6>
                    <h6 class="h6 my-4 text-muted">{{!empty($user->customers)?$user->customers->contact:'-'}}</h6>

                </div>

            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0">{{__('Billing Address')}}</h6>
                </div>
                <div class="card-footer py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-control-label">{{__('Name')}}:</span>
                                </div>
                                <div class="col-6 text-right">
                                    {{!empty($user->customers)?$user->customers->billing_name:'-'}}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-control-label">{{__('Contact')}}:</span>
                                </div>
                                <div class="col-6 text-right">
                                    {{!empty($user->customers)?$user->customers->billing_phone:'-'}}
                                </div>
                            </div>
                        </li>
                        <li>
                            <p class="text-sm my-2">
                                {{!empty($user->customers)?$user->customers->billing_address:'-'}}
                            </p>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small>{{__('City')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->billing_city:'-'}}</div>
                                </div>
                                <div class="col-6 text-right">
                                    <small>{{__('State')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->billing_state:'-'}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small>{{__('Country')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->billing_country:'-'}}</div>
                                </div>
                                <div class="col-6 text-right">
                                    <small>{{__('Zip')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->billing_zip:'-'}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="card card-fluid">
                <div class="card-header">
                    <h6 class="mb-0">{{__('Shipping Address')}}</h6>
                </div>
                <div class="card-footer py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-control-label">{{__('Name')}}:</span>
                                </div>
                                <div class="col-6 text-right">
                                    {{!empty($user->customers)?$user->customers->shipping_name:'-'}}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="form-control-label">{{__('Contact')}}:</span>
                                </div>
                                <div class="col-6 text-right">
                                    {{!empty($user->customers)?$user->customers->shipping_phone:'-'}}
                                </div>
                            </div>
                        </li>
                        <li>
                            <p class="text-sm my-2">
                                {{!empty($user->customers)?$user->customers->shipping_address:'-'}}
                            </p>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small>{{__('City')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->shipping_city:'-'}}</div>
                                </div>
                                <div class="col-6 text-right">
                                    <small>{{__('State')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->shipping_state:'-'}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small>{{__('Country')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->shipping_country:'-'}}</div>
                                </div>
                                <div class="col-6 text-right">
                                    <small>{{__('Zip')}}:</small>
                                    <div class="h6 mb-0"> {{!empty($user->customers)?$user->customers->shipping_zip:'-'}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
