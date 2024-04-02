@foreach($subscriptions as $subscription)
    <div class="list-group-item">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="#" class="avatar rounded-circle">
                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/subscription')).'/'.$subscription->image}}" class="">
                </a>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{$subscription->name}}</a>
                <div>
                    <span class="text-sm">{{\Auth::user()->priceFormat($subscription->price)}} {{' / '. $subscription->duration}}</span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{__('Users')}}</a>
                <div>
                    <span class="text-sm">{{$subscription->max_users}}</span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{__('Customers')}}</a>
                <div>
                    <span class="text-sm">{{$subscription->max_customers}}</span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0">{{__('Vendors')}}</a>
                <div>
                    <span class="text-sm">{{$subscription->max_vendors}}</span>
                </div>
            </div>
            <div class="col-auto">
                @if($user->subscription==$subscription->id)
                    <span class="badge badge-soft-success mr-2">{{__('Active')}}</span>
                @else
                    <a href="{{route('subscription.active',[$user->id,$subscription->id])}}" class="btn btn-xs btn-secondary btn-icon" data-toggle="tooltip" data-original-title="{{__('Click to Subscription Plan')}}">
                        <span class="btn-inner--icon"><i class="fas fa-cart-plus"></i></span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach
