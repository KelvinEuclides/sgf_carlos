@extends('layouts.app')

@section('page-title')
    {{__('Items')}}
@endsection
@section('action-button')
    <a href="#" data-size="lg" data-url="{{ route('item.create') }}" data-ajax-popup="true" data-title="{{__('Create New Item')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0 action-card-header">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Item')}}</h6>
                </div>
                <div class="col text-right">
                    <div class="actions">
                        {{ Form::open(array('route' => array('item.index'),'method' => 'GET','id'=>'item')) }}
                        <div class="row d-flex justify-content-end mt-2">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="all-select-box">
                                    <div class="btn-box">
                                        {{ Form::select('category', $category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select2','required'=>'required')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn" onclick="document.getElementById('item').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-search-plus"></i></span>
                                </a>
                                <a href="{{route('item.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                                    <span class="btn-inner--icon"><i class="far fa-window-restore"></i></span>
                                </a>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th scope="col" class="sort"> {{__('Name')}}</th>
                    <th scope="col" class="sort"> {{__('Category')}}</th>
                    <th scope="col" class="sort"> {{__('Sku')}}</th>
                    <th scope="col" class="sort"> {{__('Sale Price')}}</th>
                    <th scope="col" class="sort"> {{__('Purchase Price')}}</th>
                    <th scope="col" class="sort"> {{__('Tax')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($items as $item)

                    <tr class="font-style">
                        <td class="budget">{{ $item->name}}</td>
                        <td class="budget">{{ !empty($item->categories)?$item->categories->name:'' }}</td>
                        <td class="budget">{{ $item->sku }}</td>
                        <td class="budget">{{ \Auth::user()->priceFormat($item->sale_price) }}</td>
                        <td class="budget">{{  \Auth::user()->priceFormat($item->purchase_price )}}</td>
                        <td class="budget">
                            @php
                                $taxes=\Utility::tax($item->tax);
                            @endphp
                            @foreach($taxes as $tax)
                                {{ !empty($tax)?$tax->name:''  }}<br>

                            @endforeach
                        </td>
                        @if(Gate::check('edit item') || Gate::check('delete item'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="#"  data-size="lg" data-url="{{ route('item.show',$item->id) }}" data-ajax-popup="true" data-title="{{__('Item Detail')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Show')}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#"  data-size="lg" data-url="{{ route('item.edit',$item->id) }}" data-ajax-popup="true" data-title="{{__('Edit Item')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$item->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['item.destroy', $item->id],'id'=>'delete-form-'.$item->id]) !!}
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
