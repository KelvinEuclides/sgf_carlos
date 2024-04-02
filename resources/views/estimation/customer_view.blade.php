@extends('layouts.app')
@section('page-title')
    {{\Auth::user()->estimationNumberFormat($estimation->estimation_id).' '.__('Details')}}
@endsection

@section('action-button')

    @if($estimation->status!=0)

        <a href="{{ route('estimation.pdf', Crypt::encrypt($estimation->id))}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-2" class="action-item" data-toggle="tooltip" data-original-title="{{__('Download')}}" target="_blank">
            <i class="fas fa-download"></i>
        </a>
    @endif
@endsection
@section('content')
    <div class="card card-body p-md-5">
        <div class="row mb-4">
            <div class="col-md-6">

            </div>
            <div class="col-sm-6 text-sm-right">
                <h3 class="d-inline-block m-0 d-print-none">{{__('Estimation')}}</h3>
                @if($estimation->status == 0)
                    <span class="badge badge-primary badge-pill ml-3">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                @elseif($estimation->status == 1)
                    <span class="badge badge-info badge-pill ml-3">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                @elseif($estimation->status == 2)
                    <span class="badge badge-success badge-pill ml-3">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                @elseif($estimation->status == 3)
                    <span class="badge badge-warning badge-pill ml-3">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                @elseif($estimation->status == 4)
                    <span class="badge badge-danger badge-pill ml-3">{{ __(\App\Estimation::$statues[$estimation->status]) }}</span>
                @endif
            </div>
        </div>

        <div class="row mb-5">
            <div class="col">
                <small class="font-style">
                    <strong>{{__('From')}} :</strong><br>
                    {{$settings['company_name']}}<br>
                    {{$settings['company_telephone']}}<br>
                    {{$settings['company_address']}}<br>
                    {{$settings['company_zipcode']}}<br>
                    {{$settings['company_city']}} {{$settings['company_state']}} {{$settings['company_country']}}
                </small>
            </div>

            @if(!empty($estimation->customers))
                <div class="col text-center">
                    <small class="font-style">
                        <strong>{{__('Billed To')}} :</strong><br>
                        {{$estimation->customers->billing_name}}<br>
                        {{$estimation->customers->billing_phone}}<br>
                        {{$estimation->customers->billing_address}}  {{$estimation->customers->billing_zip}}<br>
                        {{$estimation->customers->billing_city}} {{$estimation->customers->billing_state}} <br>
                    </small>
                </div>

                <div class="col text-md-right">
                    <small>
                        <strong>{{__('Shipped To')}} :</strong><br>
                        {{$estimation->customers->shipping_name}}<br>
                        {{$estimation->customers->shipping_phone}}<br>
                        {{$estimation->customers->shipping_address}}  {{$estimation->customers->shipping_zip}}<br>
                        {{$estimation->customers->shipping_city}} {{$estimation->customers->shipping_state}}<br>
                    </small>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col">
                <small>
                    <strong>{{__('Estimation')}} :</strong><br>
                    {{\Auth::user()->estimationnumberFormat($estimation->estimation_id)}}
                </small>
            </div>
            <div class="col text-center">
                <small>
                    <strong>{{__('Created Date')}} :</strong><br>
                    {{\Auth::user()->dateFormat($estimation->created_at)}}
                </small>
            </div>
            <div class="col text-center">
                <small>
                    <strong>{{__('Issue Date')}} :</strong><br>
                    {{\Auth::user()->dateFormat($estimation->issue_date)}}
                </small>
            </div>
            @if(!empty($estimation->send_date))
                <div class="col text-center">
                    <small>
                        <strong>{{__('Send Date')}} :</strong><br>
                        {{\Auth::user()->dateFormat($estimation->send_date)}}
                    </small>
                </div>
            @endif
            <div class="col text-right">
                <small>
                    @if(\Auth::user()->type=='company')
                        <select class="form-control status_change select2" name="status" data-url="{{route('estimation.status.change',$estimation->id)}}">
                            @foreach($status as $k=>$val)
                                <option value="{{$k}}" {{($estimation->status==$k)?'selected':''}}>{{$val}}</option>
                            @endforeach
                        </select>
                    @endif
                </small>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5>{{__('Items')}}</h5>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th class="px-0 bg-transparent border-top-0">{{__('Item')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Quantity')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Rate')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Tax')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Discount')}}</th>
                            <th class="px-0 bg-transparent border-top-0">{{__('Description')}}</th>
                            <th class="px-0 bg-transparent border-top-0 text-right">{{__('Price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalQuantity=0;
                            $totalRate=0;
                            $totalAmount=0;
                            $totalTaxPrice=0;
                            $totalDiscount=0;
                            $taxesData=[];
                        @endphp

                        @foreach($estimation->items as $item)
                            @php
                                $tax=!empty($item->items)?$item->items->tax:0;
                                $taxes=\App\Utility::tax($tax);

                                $totalQuantity+=$item->quantity;
                                $totalRate+=$item->price;
                                $totalDiscount+=$item->discount;

                                foreach($taxes as $taxe){
                                    $taxDataPrice=\App\Utility::taxRate($taxe->rate,$item->price,$item->quantity);
                                    if (array_key_exists($taxe->name,$taxesData))
                                    {
                                        $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                    }
                                    else
                                    {
                                        $taxesData[$taxe->name] = $taxDataPrice;
                                    }
                                }

                            @endphp
                            <tr>
                                <td class="px-0">{{!empty($item->items)?$item->items->name:''}} </td>
                                <td class="px-0">{{$item->quantity}} </td>
                                <td class="px-0">{{\Auth::user()->priceFormat($item->price)}} </td>
                                <td class="px-0">
                                    <div class="col">
                                        @foreach($taxes as $tax)
                                            @php
                                                $taxPrice=\App\Utility::taxRate($tax->rate,$item->price,$item->quantity);
                                                $totalTaxPrice+=$taxPrice;
                                            @endphp
                                            <a href="#!" class="d-block text-sm text-muted">{{$tax->name .' ('.$tax->rate .'%)'}} &nbsp;&nbsp;{{\Auth::user()->priceFormat($taxPrice)}}</a>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-0">{{\Auth::user()->priceFormat($item->discount)}} </td>
                                <td class="px-0">{{$item->description}} </td>
                                <td class="text-right"> {{\Auth::user()->priceFormat(($item->price*$item->quantity))}}</td>
                                @php
                                    $totalQuantity+=$item->quantity;
                                    $totalRate+=$item->price;
                                    $totalDiscount+=$item->discount;
                                    $totalAmount+=($item->price*$item->quantity);
                                @endphp
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Sub Total')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimation->getSubTotal())}}</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Discount')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimation->getTotalDiscount())}}</td>
                        </tr>
                        @if(!empty($taxesData))
                            @foreach($taxesData as $taxName => $taxPrice)
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="px-0"></td>
                                    <td class="text-right"><b>{{$taxName}}</b></td>
                                    <td class="text-right">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Total')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimation->getTotal())}}</td>
                        </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                                <div class="d-flex align-items-center justify-content-md-end">
                                    <span class="h6 text-muted d-inline-block mr-3 mb-0">{{__('Total Estimation')}}:</span>
                                    <span class="h4 mb-0">{{\Auth::user()->priceFormat($estimation->getTotal())}}</span>
                                </div>
                            </div>
                            <div class="col-md-6 order-md-1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
