@extends('layouts.app')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Profile')}}
@endsection
@section('action-button')
@endsection
@section('content')
    <div class="card">
        <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
            <li class="nav-item ml-4">
                <a href="#personal_info" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                    {{__('Personal info')}}
                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#billing_detail" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                    {{__('Billing Detail')}}
                </a>
            </li>
            @if(\Auth::user()->type=='customer' || \Auth::user()->type=='vendor')
                <li class="nav-item ml-4">
                    <a href="#shipping_detail" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                        {{__('Shipping Detail')}}
                    </a>
                </li>
                <li class="nav-item ml-4">
                    <a href="#change_password" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                        {{__('Change Password')}}
                    </a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="personal_info" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('name',__('Name'),array('class'=>'form-control-label'))}}
                                    {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter User Name')))}}
                                    @error('name')
                                    <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('email',__('Email'),array('class'=>'form-control-label'))}}
                                    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                    @error('email')
                                    <span class="invalid-email" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6">

                                <div class="form-group">
                                    <div class="choose-file">
                                        <label for="avatar">
                                            <div>{{__('Choose file here')}}</div>
                                            <input type="file" class="form-control" id="avatar" name="profile" data-filename="profiles">
                                        </label>
                                        <p class="profiles"></p>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="{{__('Update')}}">
                    </div>
                    {{Form::close()}}
                </div>
            </div>
            @if(\Auth::user()->type=='customer')
                <div id="billing_detail" class="tab-pane">

                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('customer.billing.detail'), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_name',__('Billing Name'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_name',!empty($userDetail->customers)?$userDetail->customers->billing_name:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Name')))}}
                                    @error('billing_name')
                                    <span class="invalid-billing_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_phone',__('Billing Phone'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_phone',!empty($userDetail->customers)?$userDetail->customers->billing_phone:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Phone')))}}
                                    @error('billing_phone')
                                    <span class="invalid-billing_phone" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_zip',__('Billing Zip'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_zip',!empty($userDetail->customers)?$userDetail->customers->billing_zip:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Zip')))}}
                                    @error('billing_zip')
                                    <span class="invalid-billing_zip" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_country',__('Billing Country'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_country',!empty($userDetail->customers)?$userDetail->customers->billing_country:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Country')))}}
                                    @error('billing_country')
                                    <span class="invalid-billing_country" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_state',__('Billing State'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_state',!empty($userDetail->customers)?$userDetail->customers->billing_state:'',array('class'=>'form-control','placeholder'=>__('Enter Billing State')))}}
                                    @error('billing_state')
                                    <span class="invalid-billing_state" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_city',__('Billing City'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_city',!empty($userDetail->customers)?$userDetail->customers->billing_city:'',array('class'=>'form-control','placeholder'=>__('Enter Billing City')))}}
                                    @error('billing_city')
                                    <span class="invalid-billing_city" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('billing_address',__('Billing Address'),array('class'=>'form-control-label'))}}
                                    {{Form::textarea('billing_address',!empty($userDetail->customers)?$userDetail->customers->billing_address:'',array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Billing Address')))}}
                                    @error('billing_address')
                                    <span class="invalid-billing_address" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 text-right">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-sm btn-primary rounded-pill">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
                <div id="shipping_detail" class="tab-pane">
                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('customer.shipping.detail'), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_name',__('Shipping Name'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_name',!empty($userDetail->customers)?$userDetail->customers->shipping_name:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Name')))}}
                                    @error('shipping_name')
                                    <span class="invalid-shipping_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_phone',__('Shipping Phone'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_phone',!empty($userDetail->customers)?$userDetail->customers->shipping_phone:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Phone')))}}
                                    @error('shipping_phone')
                                    <span class="invalid-shipping_phone" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_zip',__('Shipping Zip'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_zip',!empty($userDetail->customers)?$userDetail->customers->shipping_zip:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Zip')))}}
                                    @error('shipping_zip')
                                    <span class="invalid-shipping_zip" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_country',__('Shipping Country'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_country',!empty($userDetail->customers)?$userDetail->customers->shipping_country:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Country')))}}
                                    @error('shipping_country')
                                    <span class="invalid-shipping_country" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_state',__('Shipping State'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_state',!empty($userDetail->customers)?$userDetail->customers->shipping_state:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping State')))}}
                                    @error('shipping_state')
                                    <span class="invalid-shipping_state" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_city',__('Shipping City'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_city',!empty($userDetail->customers)?$userDetail->customers->shipping_city:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping City')))}}
                                    @error('shipping_city')
                                    <span class="invalid-shipping_city" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('shipping_address',__('Shipping Address'),array('class'=>'form-control-label'))}}
                                    {{Form::textarea('shipping_address',!empty($userDetail->customers)?$userDetail->customers->shipping_address:'',array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Shipping Address')))}}
                                    @error('shipping_address')
                                    <span class="invalid-billing_address" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 text-right">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-sm btn-primary rounded-pill">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='vendor')
                <div id="billing_detail" class="tab-pane">

                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('vendor.billing.detail'), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_name',__('Billing Name'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_name',!empty($userDetail->vendors)?$userDetail->vendors->billing_name:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Name')))}}
                                    @error('billing_name')
                                    <span class="invalid-billing_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_phone',__('Billing Phone'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_phone',!empty($userDetail->vendors)?$userDetail->vendors->billing_phone:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Phone')))}}
                                    @error('billing_phone')
                                    <span class="invalid-billing_phone" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_zip',__('Billing Zip'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_zip',!empty($userDetail->vendors)?$userDetail->vendors->billing_zip:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Zip')))}}
                                    @error('billing_zip')
                                    <span class="invalid-billing_zip" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_country',__('Billing Country'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_country',!empty($userDetail->vendors)?$userDetail->vendors->billing_country:'',array('class'=>'form-control','placeholder'=>__('Enter Billing Country')))}}
                                    @error('billing_country')
                                    <span class="invalid-billing_country" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_state',__('Billing State'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_state',!empty($userDetail->vendors)?$userDetail->vendors->billing_state:'',array('class'=>'form-control','placeholder'=>__('Enter Billing State')))}}
                                    @error('billing_state')
                                    <span class="invalid-billing_state" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('billing_city',__('Billing City'),array('class'=>'form-control-label'))}}
                                    {{Form::text('billing_city',!empty($userDetail->vendors)?$userDetail->vendors->billing_city:'',array('class'=>'form-control','placeholder'=>__('Enter Billing City')))}}
                                    @error('billing_city')
                                    <span class="invalid-billing_city" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('billing_address',__('Billing Address'),array('class'=>'form-control-label'))}}
                                    {{Form::textarea('billing_address',!empty($userDetail->vendors)?$userDetail->vendors->billing_address:'',array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Billing Address')))}}
                                    @error('billing_address')
                                    <span class="invalid-billing_address" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 text-right">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-sm btn-primary rounded-pill">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
                <div id="shipping_detail" class="tab-pane">
                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('vendor.billing.detail'), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_name',__('Shipping Name'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_name',!empty($userDetail->vendors)?$userDetail->vendors->shipping_name:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Name')))}}
                                    @error('shipping_name')
                                    <span class="invalid-shipping_name" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_phone',__('Shipping Phone'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_phone',!empty($userDetail->vendors)?$userDetail->vendors->shipping_phone:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Phone')))}}
                                    @error('shipping_phone')
                                    <span class="invalid-shipping_phone" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_zip',__('Shipping Zip'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_zip',!empty($userDetail->vendors)?$userDetail->vendors->shipping_zip:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Zip')))}}
                                    @error('shipping_zip')
                                    <span class="invalid-shipping_zip" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_country',__('Shipping Country'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_country',!empty($userDetail->vendors)?$userDetail->vendors->shipping_country:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping Country')))}}
                                    @error('shipping_country')
                                    <span class="invalid-shipping_country" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_state',__('Shipping State'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_state',!empty($userDetail->vendors)?$userDetail->vendors->shipping_state:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping State')))}}
                                    @error('shipping_state')
                                    <span class="invalid-shipping_state" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('shipping_city',__('Shipping City'),array('class'=>'form-control-label'))}}
                                    {{Form::text('shipping_city',!empty($userDetail->vendors)?$userDetail->vendors->shipping_city:'',array('class'=>'form-control','placeholder'=>__('Enter Shipping City')))}}
                                    @error('shipping_city')
                                    <span class="invalid-shipping_city" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('shipping_address',__('Shipping Address'),array('class'=>'form-control-label'))}}
                                    {{Form::textarea('shipping_address',!empty($userDetail->vendors)?$userDetail->vendors->shipping_address:'',array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Shipping Address')))}}
                                    @error('shipping_address')
                                    <span class="invalid-billing_address" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12 text-right">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-sm btn-primary rounded-pill">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            @endif
            <div class="tab-pane fade" id="change_password" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        {{Form::model($userDetail,array('route' => array('update.password',$userDetail->id), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('current_password',__('Current Password'),array('class'=>'form-control-label'))}}
                                    {{Form::password('current_password',array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
                                    @error('current_password')
                                    <span class="invalid-current_password" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    {{Form::label('new_password',__('New Password'),array('class'=>'form-control-label'))}}
                                    {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
                                    @error('new_password')
                                    <span class="invalid-new_password" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {{Form::label('confirm_password',__('Re-type New Password'),array('class'=>'form-control-label'))}}
                                    {{Form::password('confirm_password',array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                                    @error('confirm_password')
                                    <span class="invalid-confirm_password" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="{{__('Update')}}">
                    </div>
                    {{Form::close()}}
                </div>
            </div>

        </div>
    </div>
@endsection
