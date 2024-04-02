@extends('layouts.app')

@section('page-title')
    {{__('Voucher')}}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.code', function () {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('action-button')
    @can('create voucher')
        <a href="#" data-size="lg" data-url="{{ route('voucher.create') }}" data-ajax-popup="true" data-title="{{__('Create New Voucher')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
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
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Voucher')}}</h6>
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
                    <th scope="col" class="sort"> {{__('Code')}}</th>
                    <th scope="col" class="sort"> {{__('Discount (%)')}}</th>
                    <th scope="col" class="sort"> {{__('Limit')}}</th>
                    <th scope="col" class="sort"> {{__('Used')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($vouchers as $voucher)
                    <tr class="font-style">
                        <td class="budget">{{ $voucher->name }}</td>
                        <td class="budget">{{ $voucher->code }}</td>
                        <td class="budget">{{ $voucher->discount }}</td>
                        <td class="budget">{{ $voucher->limit }}</td>
                        <td class="budget">{{ $voucher->used_voucher() }}</td>
                        @if(Gate::check('edit voucher') || Gate::check('delete voucher'))
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    @can('show voucher')
                                        <a href="{{ route('voucher.show',$voucher->id) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('edit voucher')
                                        <a href="#" data-size="lg" data-url="{{ route('voucher.edit',$voucher->id) }}" data-ajax-popup="true" data-title="{{__('Edit Voucher')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('edit voucher')
                                        <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$voucher->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['voucher.destroy', $voucher->id],'id'=>'delete-form-'.$voucher->id]) !!}
                                        {!! Form::close() !!}
                                    @endcan
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
