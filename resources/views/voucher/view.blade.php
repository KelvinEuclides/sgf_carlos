@extends('layouts.app')

@section('page-title')
    {{__('Manage Voucher Details')}}
@endsection
@section('action-button')

@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Voucher Details')}}</h6>
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
                    <th scope="col" class="sort"> {{__('User')}}</th>
                    <th scope="col" class="sort"> {{__('Date')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($userVoucher as $voucher)
                    <tr class="font-style">
                        <td class="budget">{{ !empty($voucher->userDetail)?$voucher->userDetail->name:'' }}</td>
                        <td class="budget">{{ $voucher->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
