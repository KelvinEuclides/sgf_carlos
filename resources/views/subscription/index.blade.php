@extends('layouts.app')

@section('page-title')
    {{__('Subscription')}}
@endsection
@section('action-button')
    @can('create subscription')
        @if(env('ENABLE_STRIPE') == 'on' || env('ENABLE_PAYPAL') == 'on' )
            <a href="#" data-size="lg" data-url="{{ route('subscription.create') }}" data-ajax-popup="true" data-title="{{__('Create New Subscription')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
            </a>
        @endif
    @endcan
@endsection
@section('content')
    <div class="row">
        @foreach($subscriptions as $subscription)
            <div class="col-md-3">
                <div class="card card-fluid">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{$subscription->name}}</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    @if( \Auth::user()->type == 'super admin')
                                        <a title="Edit Plan" data-size="lg" href="#" class="action-item" data-url="{{ route('subscription.edit',$subscription->id) }}" data-ajax-popup="true" data-title="{{__('Edit Subscription')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-edit"></i></a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center {{!empty(\Auth::user()->type != 'super admin')?'plan-box':''}}">
                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                            <img alt="Image placeholder" src="{{ asset(Storage::url('uploads/subscription')).'/'.$subscription->image}}" class="">
                        </a>

                        <h5 class="h6 my-4"> {{env('CURRENCY_SYMBOL').$subscription->price.' / '.$subscription->duration}}</h5>

                        @if(\Auth::user()->type=='company' && \Auth::user()->subscription == $subscription->id)
                            <h5 class="h6 my-4">
                                {{__('Expired : ')}} {{\Auth::user()->subscription_expire_date ? \Auth::user()->dateFormat(\Auth::user()->subscription_expire_date):__('Unlimited')}}
                            </h5>

                        @endif

                        <h5 class="h6 my-4">{{$subscription->description}}</h5>

                        @if(\Auth::user()->type == 'company' && \Auth::user()->subscription == $subscription->id)
                            <span class="clearfix"></span>
                            <span class="badge badge-pill badge-success">{{__('Active')}}</span>
                        @endif
                        @if(($subscription->id != \Auth::user()->subscription) && \Auth::user()->type!='super admin' )
                            @if($subscription->price > 0)
                                <a class="badge badge-pill badge-primary" href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($subscription->id))}}" data-toggle="tooltip" data-original-title="{{__('Buy Plan')}}">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            @endif
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto text-center">
                                <span class="h5 mb-0">{{$subscription->max_users}}</span>
                                <span class="d-block text-sm">{{__('User')}}</span>
                            </div>
                            <div class="col-auto text-center">
                                <span class="h5 mb-0">{{$subscription->max_customers}}</span>
                                <span class="d-block text-sm"> {{__('Customer')}}</span>
                            </div>
                            <div class="col-auto text-center">
                                <span class="h5 mb-0">{{$subscription->max_vendors}}</span>
                                <span class="d-block text-sm"> {{__('Vendor')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
