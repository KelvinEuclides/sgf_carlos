@php
use App\Subscription;
$subscriptions = Subscription::pluck('name','id');
@endphp
{{Form::open(array('url'=>'users','method'=>'post'))}}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('email',__('Email'),['class'=>'form-control-label'])}}
            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
            @error('email')
            <small class="invalid-email" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('nuit',__('Nuit'),['class'=>'form-control-label'])}}
            {{Form::text('nuit',null,array('class'=>'form-control','placeholder'=>__('Nuit'),'required'=>'required'))}}
            @error('nuit')
            <small class="invalid-nuit" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('endereco',__('Endereço'),['class'=>'form-control-label'])}}
            {{Form::text('endereco',null,array('class'=>'form-control','placeholder'=>__('Endereço'),'required'=>'required'))}}
            @error('endereco')
            <small class="invalid-endereco" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    @if(\Auth::user()->type == 'super admin')
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('subscription',__('Subscription'),['class'=>'form-control-label'])}}
            {!! Form::select('subscription',$subscriptions, null,array('class' => 'form-control select2','required'=>'required')) !!}
     </div>
     @endif
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('password',__('Password'),['class'=>'form-control-label'])}}
            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
            @error('password')
            <small class="invalid-password" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
    @if(\Auth::user()->type != 'super admin')
        <div class="form-group col-md-12">
            {{ Form::label('role', __('User Role'),['class'=>'form-control-label']) }}
            {!! Form::select('role', $roles, null,array('class' => 'form-control select2','required'=>'required')) !!}
            @error('role')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    @endif
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}

