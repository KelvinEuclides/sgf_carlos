@extends('layouts.app')
@section('page-title')
    {{\Auth::user()->invoiceNumberFormat($invoice->invoice_id).' '.__('Details')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '.status_change', function () {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                    location.reload();
                },
            });
        });
    </script>
@endpush
@section('action-button')
    @if($invoice->status==0)
        <div class="Action">
            @can('send invoice')
                <a href="{{ route('invoice.sent',$invoice->id) }}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-2" data-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="fa fa-paper-plane"></i></a>
            @endcan
        </div>
    @endif
    @if($invoice->status!=0)
        @can('create payment invoice')
            <a href="#" data-size="lg" data-url="{{ route('invoice.payment',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle" data-toggle="tooltip" data-original-title="{{__('Add Payment')}}"><i class="far fa-credit-card"></i></a>
        @endcan
    @endif
    @if(\Auth::user()->type=='company')
        @if($invoice->status!=0)
            <a href="{{ route('invoice.resent',$invoice->id) }}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-2" data-toggle="tooltip" data-original-title="{{__('Resend Invoice')}}"><i class="fa fa-paper-plane"></i></a>
            <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-2" class="action-item" data-toggle="tooltip" data-original-title="{{__('Download')}}" target="_blank">
                <i class="fas fa-download"></i>
            </a>
        @endif
    @endif
    @can('edit invoice')
        <a href="{{ route('invoice.edit',$invoice->id) }}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-2" data-size="lg" data-title="{{__('Edit Invoice')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
            <i class="far fa-edit"></i>
        </a>
    @endcan
@endsection
@section('content')
    <div class="card card-body p-md-5">
        <div class="row mb-4">
            <div class="col-md-6">

            </div>
            <div class="col-sm-6 text-sm-right">
                <h3 class="d-inline-block m-0 d-print-none">{{__('Invoice')}}</h3>
                @if($invoice->status == 0)
                    <span class="badge badge-primary badge-pill ml-3">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                @elseif($invoice->status == 1)
                    <span class="badge badge-info badge-pill ml-3">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                @elseif($invoice->status == 2)
                    <span class="badge badge-success badge-pill ml-3">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                @elseif($invoice->status == 3)
                    <span class="badge badge-warning badge-pill ml-3">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                @elseif($invoice->status == 4)
                    <span class="badge badge-danger badge-pill ml-3">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
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

            @if(!empty($invoice->customers))
                <div class="col text-center">
                    <small class="font-style">
                        <strong>{{__('Billed To')}} :</strong><br>
                        {{$invoice->customers->billing_name}}<br>
                        {{$invoice->customers->billing_phone}}<br>
                        {{$invoice->customers->billing_address}}  {{$invoice->customers->billing_zip}}<br>
                        {{$invoice->customers->billing_city}} {{$invoice->customers->billing_state}} <br>
                    </small>
                </div>

                <div class="col text-md-right">
                    <small>
                        <strong>{{__('Shipped To')}} :</strong><br>
                        {{$invoice->customers->shipping_name}}<br>
                        {{$invoice->customers->shipping_phone}}<br>
                        {{$invoice->customers->shipping_address}}  {{$invoice->customers->shipping_zip}}<br>
                        {{$invoice->customers->shipping_city}} {{$invoice->customers->shipping_state}}<br>
                    </small>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col">
                <small>
                    <strong>{{__('Invoice')}} :</strong><br>
                    {{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}
                </small>
            </div>
            <div class="col text-center">
                <small>
                    <strong>{{__('Created Date')}} :</strong><br>
                    {{\Auth::user()->dateFormat($invoice->created_at)}}
                </small>
            </div>
            <div class="col text-center">
                <small>
                    <strong>{{__('Issue Date')}} :</strong><br>
                    {{\Auth::user()->dateFormat($invoice->issue_date)}}
                </small>
            </div>
            @if(!empty($invoice->send_date))
                <div class="col text-center">
                    <small>
                        <strong>{{__('Send Date')}} :</strong><br>
                        {{\Auth::user()->dateFormat($invoice->send_date)}}
                    </small>
                </div>
            @endif
            <div class="col text-right">
                <small>
                    @if(\Auth::user()->type =='company')
                        <select class="form-control status_change select2" name="status" data-url="{{route('invoice.status.change',$invoice->id)}}">
                            @foreach($status as $k=>$val)
                                <option value="{{$k}}" {{($invoice->status==$k)?'selected':''}}>{{$val}}</option>
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

                        @foreach($invoice->items as $item)
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
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($invoice->getSubTotal())}}</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Discount')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($invoice->getTotalDiscount())}}</td>
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
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Paid')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat(($invoice->getTotal()-$invoice->getDue()))}}</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td class="px-0"></td>
                            <td class="text-right"><strong>{{__('Due')}}</strong></td>
                            <td class="text-right subTotal">{{\Auth::user()->priceFormat($invoice->getDue())}}</td>
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
                                    <span class="h6 text-muted d-inline-block mr-3 mb-0">{{__('Total value')}}:</span>
                                    <span class="h4 mb-0">{{\Auth::user()->priceFormat($invoice->getTotal())}}</span>
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
    <div class="col-12">
        <h6 class="h4 d-inline-block font-weight-400 mb-4">{{__('Payment History')}}</h6>
        <div class="card">
            <div class="card-body py-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th class="text-dark">{{__('Date')}}</th>
                            <th class="text-dark">{{__('Amount')}}</th>
                            <th class="text-dark">{{__('Payment Type')}}</th>
                            <th class="text-dark">{{__('Account')}}</th>
                            <th class="text-dark">{{__('Reference')}}</th>
                            <th class="text-dark">{{__('Description')}}</th>
                            <th class="text-dark">{{__('Receipt')}}</th>
                            <th class="text-dark">{{__('OrderId')}}</th>
                            @can('delete payment invoice')
                                <th class="text-dark">{{__('Action')}}</th>
                            @endcan
                        </tr>
                        @forelse($invoice->payments as $key =>$payment)
                            <tr>
                                <td>{{\Auth::user()->dateFormat($payment->date)}}</td>
                                <td>{{\Auth::user()->priceFormat($payment->amount)}}</td>
                                <td>{{$payment->payment_type}}</td>
                                <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:'--'}}</td>
                                <td>{{!empty($payment->reference)?$payment->reference:'--'}}</td>
                                <td>{{!empty($payment->description)?$payment->description:'--'}}</td>
                                <td>@if(!empty($payment->receipt))<a href="{{$payment->receipt}}" target="_blank"> <i class="fas fa-file"></i></a>@else -- @endif</td>
                                <td>{{!empty($payment->order_id)?$payment->order_id:'--'}}</td>
                                @can('delete payment invoice')
                                    <td>
                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (Gate::check('delete invoice product') ? '9' : '8') }}" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
