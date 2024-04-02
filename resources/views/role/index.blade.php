@extends('layouts.app')

@section('page-title')
    {{__('Roles')}}
@endsection
@section('action-button')
    <a href="#" data-size="lg" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-title="{{__('Create New Role')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white">{{__('Manage Roles')}}</h6>
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
                    <th scope="col" class="sort"> {{__('Role')}}</th>
                    <th scope="col" class="sort"> {{__('Permissions')}}</th>
                    <th scope="col" class="sort text-right"> {{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($roles as $role)
                    <tr class="font-style">
                        <td  class="budget">{{ $role->name }}</td>
                        <td class="Permission">
                            @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                <a href="#" class="absent-btn">{{$role->permissions()->pluck('name')[$j]}}</a>
                            @endfor
                        </td>
                        <td class="Action text-right">
                            <span>
                            @can('edit role')
                                    <a href="#" data-size="lg" class="edit-icon" data-url="{{ route('roles.edit',$role->id) }}" data-size="xl" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-title="{{__('Edit Role')}}">
                                    <i class="far fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete role')
                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Want to delete?').'|'.__('Are you sure you want to delete this record ?')}}" data-confirm-yes="document.getElementById('delete-form-{{$role->id}}').submit();"><i class="fas fa-trash"></i></a>

                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </span>
                        </td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection
