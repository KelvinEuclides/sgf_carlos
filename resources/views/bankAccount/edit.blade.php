{{Form::model($bankAccount, array('route' => array('account.update', $bankAccount->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('holder_name', __('Holder Name'),['class'=>'form-control-label']) }}
        {{ Form::text('holder_name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('bank_name', __('Bank Name'),['class'=>'form-control-label']) }}
        {{ Form::text('bank_name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('account_number', __('Account Number'),['class'=>'form-control-label']) }}
        {{ Form::text('account_number', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('opening_balance', __('Opening Balance'),['class'=>'form-control-label']) }}
        {{ Form::number('opening_balance', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('contact_number', __('Contact'),['class'=>'form-control-label']) }}
        {{ Form::text('contact_number', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('bank_address', __('Bank Address'),['class'=>'form-control-label']) }}
        {{ Form::textarea('bank_address', null, array('class' => 'form-control','rows'=>3,'required'=>'required')) }}
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}





