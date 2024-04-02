@extends('layouts.app')
@section('page-title')
    {{__('Create Invoice').' '.\Auth::user()->invoiceNumberFormat($invoiceId)}}
@endsection
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')
    <script src="{{asset('assets/js/jquery.repeater.min.js')}}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {

            var $repeater = $(selector + ' .repeater').repeater({

                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function () {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);

            }

        }


        $(document).on('change', '.item', function () {
            var item_id = $(this).val();

            var url = $(this).data('url');
            var el = $(this);
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'item_id': item_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);
                    $(el.parent().parent().find('.quantity')).val(1);
                    $(el.parent().parent().find('.price')).val(item.item.sale_price);
                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;
                    for (var i = 0; i < item.taxes.length; i++) {
                        taxes += '<span class="badge badge-primary mr-1 mt-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span> </br>';
                        tax.push(item.taxes[i].id);
                        totalItemTaxRate += parseFloat(item.taxes[i].rate);
                    }

                    var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.item.sale_price * 1));

                    $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().find('.discount')).val(0);
                    $(el.parent().parent().find('.amount')).html(item.totalAmount);


                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));


                    var totalItemPrice = 0;
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += parseFloat(priceInput[j].value);
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                    }

                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));


                },
            });
        });

        $(document).on('keyup', '.quantity', function () {
            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();


            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $('.totalTax').html(itemTaxPrice.toFixed(2));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup', '.price', function () {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();

            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })


        $(document).on('keyup', '.discount', function () {
            var el = $(this).parent().parent().parent().parent();
            var discount = $(this).val();
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();

            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice) - parseFloat(totalItemDiscountPrice)).toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

        })

    </script>
@endpush
@section('action-btn')
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            {{ Form::open(array('url' => 'invoice')) }}
            <div class="row">
                <div class="col-md-12 order-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('customer', __('Customer'),['class'=>'form-control-label']) }}
                                        {{ Form::select('customer', $customers,null, array('class' => 'form-control select2','required'=>'required')) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {{ Form::label('category', __('Category'),['class'=>'form-control-label']) }}
                                    {{ Form::select('category', $categories,'', array('class' => 'form-control select2','required'=>'required')) }}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Ref Number'),['class'=>'form-control-label']) }}
                                        {{ Form::text('ref_number', '', array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-control-label']) }}
                                        {{Form::date('issue_date',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'),['class'=>'form-control-label']) }}
                                        {{Form::date('due_date',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card repeater">
                        <div class="card-header border-0">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mb-0">{{__('Item')}}</h5>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4" data-repeater-create="" data-toggle="modal" data-target="#add-bank">
                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-striped" data-repeater-list="items">
                                <thead class="thead-light">
                                <tr>
                                    <th width="20%">{{__('Items')}}</th>
                                    <th width="12%">{{__('Quantity')}}</th>
                                    <th width="13%">{{__('Price')}}</th>
                                    <th>{{__('Tax')}}</th>
                                    <th width="10%">{{__('Discount')}}</th>
                                    <th width="17%">{{__('Discription')}}</th>
                                    <th width="10%" class="text-right">{{__('Amount')}}</th>
                                    <th width="4%"></th>
                                </tr>
                                </thead>
                                <tbody class="ui-sortable">
                                <tr data-repeater-item>
                                    <td width="25%">
                                        {{ Form::select('item', $items,null, array('class' => 'form-control item select2','data-url'=>route('estimation.product'),'required'=>'required')) }}

                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {{ Form::text('quantity','', array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group colorpickerinput">
                                                {{ Form::text('price','', array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group colorpickerinput">
                                                <div class="taxes"></div>
                                                {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                                {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                                {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="discount-field">
                                        <div class="form-group">
                                            <div class="input-group colorpickerinput">
                                                {{ Form::text('discount','', array('class' => 'form-control discount','required'=>'required')) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group colorpickerinput">
                                                {{ Form::text('description','', array('class' => 'form-control','placeholder'=>__('Description'))) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right amount">
                                        0.00
                                    </td>
                                    <td>
                                        <a href="#" class="action-item fas fa-trash" data-repeater-delete></a>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>

                                    <td colspan="4">&nbsp;</td>
                                    <td class="discount-field"></td>
                                    <td class="text-right"><strong>{{__('Sub Total')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                    <td class="text-right subTotal">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="discount-field"></td>
                                    <td class="text-right"><strong>{{__('Discount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                    <td class="text-right totalDiscount">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="discount-field"></td>
                                    <td class="text-right"><strong>{{__('Tax')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                    <td class="text-right totalTax">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="discount-field"></td>
                                    <td class="text-right"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                    <td class="text-right totalAmount">0.00</td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="card-footer text-right">
                            {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                        </div>
                    </div>
                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>

@endsection

