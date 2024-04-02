@extends('layouts.app')
@section('page-title')
    {{__('Settings')}}
@endsection
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_favicon=Utility::getValByName('company_favicon');
@endphp
@section('action-button')
@endsection
@section('content')
    <div class="card">
        <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
            <li class="nav-item ml-4">
                <a href="#site_setting" id="site-settings" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                    {{__('Site Settings')}}
                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#common_setting" id="common-settings" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                    {{__('Common Setttings')}}
                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#system_setting" id="system-settings" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                    {{__('System Setttings')}}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="site_setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        {{Form::model($settings,array('route'=>'site.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_logo" class="form-control-label">{{__('Logo')}}</label>
                                    <input type="file" name="company_logo" id="company_logo" class="custom-input-file">
                                    <label for="company_logo">
                                        <i class="fa fa-upload"></i>
                                        <span>{{__('Choose a file')}}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_favicon" class="form-control-label">{{__('Favicon')}}</label>
                                    <input type="file" name="company_favicon" id="company_favicon" class="custom-input-file">
                                    <label for="company_favicon">
                                        <i class="fa fa-upload"></i>
                                        <span>{{__('Choose a file')}}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::label('title_text',__('Title Text'),array('class'=>'form-control-label'),array('class'=>'form-control-label')) }}
                                {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                @error('title_text')
                                <span class="invalid-title_text" role="alert">
                                 <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('footer_text',__('Footer Text'),array('class'=>'form-control-label'),array('class'=>'form-control-label')) }}
                                {{Form::text('footer_text',null,array('class'=>'form-control','placeholder'=>__('Footer Text')))}}
                                @error('footer_text')
                                <span class="invalid-footer_text" role="alert">
                                 <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="Save Change">
                    </div>
                    {{Form::close()}}
                </div>
            </div>
            <div class="tab-pane fade" id="common_setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        {{Form::model($settings,array('route'=>'common.setting','method'=>'post'))}}
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::label('company_name',__('Company Name'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                                @error('company_name')
                                <span class="invalid-company_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_address',__('Address'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                                @error('company_address')
                                <span class="invalid-company_address" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_city',__('City'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                                @error('company_city')
                                <span class="invalid-company_city" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_state',__('State'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                                @error('company_state')
                                <span class="invalid-company_state" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_zipcode',__('Post Code'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                @error('company_zipcode')
                                <span class="invalid-company_zipcode" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group  col-md-6">
                                {{Form::label('company_country',__('Country'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                                @error('company_country')
                                <span class="invalid-company_country" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_telephone',__('Phone'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                @error('company_telephone')
                                <span class="invalid-company_telephone" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_email',__('System Email'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                @error('company_email')
                                <span class="invalid-company_email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('company_email_from_name',__('Email (From Name)'),array('class'=>'form-control-label')) }}
                                {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                                @error('company_email_from_name')
                                <span class="invalid-company_email_from_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="Save Change">
                    </div>
                    {{Form::close()}}
                </div>
            </div>
            <div class="tab-pane fade" id="system_setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        {{Form::model($settings,array('route'=>'system.setting','method'=>'post'))}}
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{Form::label('site_currency',__('Currency'),array('class'=>'form-control-label')) }}
                                {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                                <small> {{__('Note: Add currency code as per three-letter ISO code.')}}<br> <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a></small> <br>
                                @error('site_currency')
                                <span class="invalid-site_currency" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('site_currency_symbol',__('Currency Symbol'),array('class'=>'form-control-label')) }}
                                {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                @error('site_currency_symbol')
                                <span class="invalid-site_currency_symbol" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">

                                                <input type="radio" id="customRadio5" name="site_currency_symbol_position" value="pre" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">
                                                <input type="radio" id="customRadio6" name="site_currency_symbol_position" value="post" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
                                <select type="text" name="site_date_format" class="form-control select2" id="site_date_format">
                                    <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                    <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                    <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                    <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
                                <select type="text" name="site_time_format" class="form-control select2" id="site_time_format">
                                    <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                    <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                    <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('estimation_prefix',__('Estimation Prefix'),array('class'=>'form-control-label')) }}
                                {{Form::text('estimation_prefix',null,array('class'=>'form-control'))}}
                                @error('estimation_prefix')
                                <span class="invalid-estimation_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('invoice_prefix',__('Invoice Prefix'),array('class'=>'form-control-label')) }}
                                {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                @error('invoice_prefix')
                                <span class="invalid-invoice_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                {{Form::label('bill_prefix',__('Bill Prefix'),array('class'=>'form-control-label')) }}
                                {{Form::text('bill_prefix',null,array('class'=>'form-control'))}}
                                @error('bill_prefix')
                                <span class="invalid-bill_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('customer_prefix',__('Customer Prefix'),array('class'=>'form-control-label')) }}
                                {{Form::text('customer_prefix',null,array('class'=>'form-control'))}}
                                @error('customer_prefix')
                                <span class="invalid-customer_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{Form::label('vendor_prefix',__('Vendor Prefix'),array('class'=>'form-control-label')) }}
                                {{Form::text('vendor_prefix',null,array('class'=>'form-control'))}}
                                @error('vendor_prefix')
                                <span class="invalid-vendor_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="{{__('Save Changes')}}">
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
