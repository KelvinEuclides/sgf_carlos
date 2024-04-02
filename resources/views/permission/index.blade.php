@extends('layouts.app')

@section('page-title')
    {{__('Permissions')}}
@endsection
@section('action-button')
    <a href="#" data-url="{{ route('permissions.create') }}" data-ajax-popup="true" data-title="{{__('Create New Role')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Permissions')}}</h6>
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
                    <th scope="col" class="sort"> {{__('Permissions')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="budget">{{ $permission->name }} </td>
                        <td class="text-right">
                            <div class="actions ml-3">
                                <a href="#"  data-size="md" data-url="{{ route('permissions.edit',$permission->id) }}" data-ajax-popup="true" data-title="{{__('Edit Payment')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$permission->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id],'id'=>'delete-form-'.$permission->id]) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
