@extends('layouts.app')
@section('page-title')
    {{__('Vendors')}}
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
    @can('create vendor')
        <a href="#" data-size="md" data-url="{{ route('vendors.create') }}" data-ajax-popup="true" data-title="{{__('Create New Vendor')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="row">
        @foreach($vendors as $vendor)
            <div class="col-xl-3 col-lg-3 col-sm-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img src="{{(!empty($vendor->avatar))? asset(Storage::url("uploads/avatar/".$vendor->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))}}" class="avatar rounded-circle avatar-md">
                                </a>
                            </div>
                            <div class="col ml-md-n2">
                                <a href="#!" class="d-block h6 mb-0">{{ $vendor->name }}</a>
                                <small class="d-block text-muted">{{ $vendor->email }}</small>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <span class="h6 text-sm mb-0">{{\Auth::user()->vendorNumberFormat(!empty($vendor->vendors)?$vendor->vendors->vendor_id:'-')}}</span>
                                <span class="d-block text-sm">{{__('Vendor ID')}}</span>
                            </div>

                            <div class="col text-right">
                                <span class="h6 text-sm mb-0">{{!empty($vendor->vendors)?$vendor->vendors->contact:'-'}}</span>
                                <span class="d-block text-sm">{{__('Contact')}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <span class="h6 text-sm mb-0">{{!empty($vendor->vendors)?\Auth::user()->priceFormat($vendor->vendors->balance):'-'}}</span>
                                <span class="d-block text-sm">{{__('Balance')}}</span>
                            </div>

                        </div>
                    </div>
                    @if(Gate::check('edit vendor') || Gate::check('delete vendor'))
                        <div class="card-footer">
                            <div class="row align-items-center">
                                @if($vendor->is_active==1)
                                    <div class="col">
                                        @can('edit vendor')
                                            <a href="#" data-size="lg" class="dropdown-item text-sm" data-url="{{ route('vendors.edit',$vendor->id) }}" data-ajax-popup="true" data-title="{{__('Update Vendor')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"> <i class="far fa-edit"></i></a>
                                        @endcan
                                    </div>
                                    <div class="col">
                                        @can('show vendor')
                                            <a href="{{ route('vendors.show',\Crypt::encrypt($vendor->id)) }}" class="dropdown-item text-sm"  data-toggle="tooltip" data-original-title="{{__('Show')}}"> <i class="fas fa-eye"></i></a>
                                        @endcan
                                    </div>
                                    <div class="col text-right">
                                        @can('delete vendor')
                                            <a data-toggle="tooltip" data-original-title="{{__('Delete')}}" class="dropdown-item text-sm" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$vendor['id']}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['vendors.destroy', $vendor['id']],'id'=>'delete-form-'.$vendor['id']]) !!}
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
