@php
use App\Subscription;
$subscriptions = Subscription::pluck('name','id');
@endphp
{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group ">
            {{Form::label('name',('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>('Enter User Name')))}}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('email',('Email'),['class'=>'form-control-label'])}}
            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>('Enter User Email')))}}
            @error('email')
            <small class="invalid-email" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    @if(\Auth::user()->type == 'super admin')
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('nuit',('Nuit'),['class'=>'form-control-label'])}}
            {{Form::text('nuit',null,array('class'=>'form-control','placeholder'=>('Nuit'),'required'=>'required'))}}
            @error('nuit')
            <small class="invalid-nuit" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('endereco',('Endereço'),['class'=>'form-control-label'])}}
            {{Form::text('endereco',null,array('class'=>'form-control','placeholder'=>('Endereço'),'required'=>'required'))}}
            @error('endereco')
            <small class="invalid-endereco" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('subscription',('Subscription'),['class'=>'form-control-label'])}}
            {!! Form::select('subscription',$subscriptions ?? '', null,array('class' => 'form-control select2','required'=>'required')) !!}
     </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('password',('Password'),['class'=>'form-control-label'])}}
            {{Form::password('password',array('class'=>'form-control','placeholder'=>('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            @error('password')
            <small class="invalid-password" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    @endif
    @if(\Auth::user()->type != 'super admin')
        <div class="form-group col-md-12">
            {{ Form::label('role', ('User Role'),['class'=>'form-control-label']) }}
            {!! Form::select('role', $roles, $user->roles,array('class' => 'form-control select2','required'=>'required')) !!}
            @error('role')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    @endif
    <div class="col-md-12 text-right">
        {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}