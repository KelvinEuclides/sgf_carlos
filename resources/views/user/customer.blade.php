@extends('layouts.app')
@section('page-title')
    {{__('Customers')}}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

    </script>
@endpush
@section('action-button')
    @can('create customer')
        <a href="#" data-size="md" data-url="{{ route('customers.create') }}" data-ajax-popup="true" data-title="{{__('Create New Customer')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="row">
        @foreach($customers as $customer)

            <div class="col-xl-3 col-lg-3 col-sm-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img src="{{(!empty($customer->avatar))? asset(Storage::url("uploads/avatar/".$customer->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="avatar rounded-circle avatar-md">
                                </a>
                            </div>
                            <div class="col ml-md-n2">
                                <a href="#!" class="d-block h6 mb-0">{{ $customer->name }}</a>
                                <small class="d-block text-muted">{{ $customer->email }}</small>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <span class="h6 text-sm mb-0">{{\Auth::user()->customerNumberFormat(!empty($customer->customers)?$customer->customers->customer_id:'-')}}</span>
                                <span class="d-block text-sm">{{__('Customer ID')}}</span>
                            </div>

                            <div class="col text-right">
                                <span class="h6 text-sm mb-0">{{!empty($customer->customers)?$customer->customers->contact:'-'}}</span>
                                <span class="d-block text-sm">{{__('Contact')}}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-auto">
                                <span class="h6 text-sm mb-0">{{!empty($customer->customers)?\Auth::user()->priceFormat($customer->customers->balance):'-'}}</span>
                                <span class="d-block text-sm">{{__('Balance')}}</span>
                            </div>

                        </div>
                    </div>
                    @if(Gate::check('edit customer') || Gate::check('delete customer'))
                        <div class="card-footer">
                            <div class="row align-items-center">
                                @if($customer->is_active==1)
                                    <div class="col">
                                        @can('edit customer')
                                            <a href="#" data-size="lg" class="dropdown-item text-sm" data-url="{{ route('customers.edit',$customer->id) }}" data-ajax-popup="true" data-title="{{__('Update Customer')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"> <i class="far fa-edit"></i></a>
                                        @endcan
                                    </div>
                                    <div class="col">
                                        @can('show customer')
                                            <a href="{{ route('customers.show',\Crypt::encrypt($customer->id)) }}" class="dropdown-item text-sm"  data-toggle="tooltip" data-original-title="{{__('Show')}}"> <i class="fas fa-eye"></i></a>
                                        @endcan
                                    </div>
                                    <div class="col text-right">
                                        @can('delete customer')
                                            <a data-toggle="tooltip" data-original-title="{{__('Delete')}}" class="dropdown-item text-sm" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$customer['id']}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['customers.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]) !!}
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
