@extends('layouts.app')

@section('page-title')
    {{__('Bank Accounts')}}
@endsection
@section('action-button')
    @can('manage banking')
        <a href="#" data-size="lg" data-url="{{ route('account.create') }}" data-ajax-popup="true" data-title="{{__('Create New Bank Account')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Bank Account')}}</h6>
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
                    <th scope="col" class="sort"> {{__('Name')}}</th>
                    <th scope="col" class="sort"> {{__('Bank')}}</th>
                    <th scope="col" class="sort"> {{__('Account Number')}}</th>
                    <th scope="col" class="sort"> {{__('Current Balance')}}</th>
                    <th scope="col" class="sort"> {{__('Contact')}}</th>
                    <th scope="col" class="sort"> {{__('Branch')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($accounts as $account)

                    <tr class="font-style">
                        <td class="budget">{{ $account->holder_name}}</td>
                        <td class="budget">{{ $account->bank_name}}</td>
                        <td class="budget">{{ $account->account_number}}</td>
                        <td class="budget">{{ \Auth::user()->priceFormat($account->opening_balance)}}</td>
                        <td class="budget">{{ $account->contact_number}}</td>
                        <td class="budget">{{ $account->bank_address}}</td>

                        @if(Gate::check('edit banking') || Gate::check('delete banking'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <a href="#" data-size="lg" data-url="{{ route('account.edit',$account->id) }}" data-ajax-popup="true" data-title="{{__('Edit Bank Account')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$account->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['account.destroy', $account->id],'id'=>'delete-form-'.$account->id]) !!}
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
