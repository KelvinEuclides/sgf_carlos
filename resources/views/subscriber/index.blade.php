@extends('layouts.app')

@section('page-title')
    {{__('Subscriber')}}
@endsection
@section('action-button')
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Subscriber')}}</h6>
                </div>
                <div class="col text-right">
                    <div class="actions">

                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th scope="col" class="sort"> {{__('Subscrib Id')}}</th>
                    <th scope="col" class="sort"> {{__('Date')}}</th>
                    <th scope="col" class="sort"> {{__('Name')}}</th>
                    <th scope="col" class="sort"> {{__('Subscription')}}</th>
                    <th scope="col" class="sort"> {{__('Price')}}</th>
                    <th scope="col" class="sort"> {{__('Payment Type')}}</th>
                    <th scope="col" class="sort"> {{__('Voucher')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($subscribers as $subscriber)

                    <tr class="font-style">
                        <td class="budget">{{$subscriber->order_id}}</td>
                        <td class="budget">{{$subscriber->created_at->format('d M Y')}}</td>
                        <td class="budget">{{$subscriber->user_name}}</td>
                        <td class="budget">{{$subscriber->plan_name}}</td>
                        <td class="budget">{{env('CURRENCY_SYMBOL').$subscriber->price}}</td>
                        <td class="budget">{{$subscriber->payment_type}}</td>

                        <td class="budget">
                            {{!empty($subscriber->total_voucher_used)? !empty($subscriber->total_voucher_used->voucher_detail)?$subscriber->total_voucher_used->voucher_detail->code:'-':'-'}}
                        </td>
                        <td class="budget">
                            @if($subscriber->receipt != 'free voucher' && $subscriber->payment_type == 'STRIPE')
                                <a href="{{$subscriber->receipt}}" title="Invoice" target="_blank" class="">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            @elseif($subscriber->receipt == 'free voucher')
                                <p>{{__('Used 100 % discount voucher code.')}}</p>
                            @elseif($subscriber->payment_type == 'Manually')
                                <p>{{__('Manually plan upgraded by super admin')}}</p>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
