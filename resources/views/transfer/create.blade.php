{{ Form::open(array('url' => 'transfer')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('from_account', __('From Account'),['class'=>'form-control-label']) }}
        {{ Form::select('from_account', $bankAccount,null, array('class' => 'form-control select2','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('to_account', __('To Account'),['class'=>'form-control-label']) }}
        {{ Form::select('to_account', $bankAccount,null, array('class' => 'form-control select2','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('amount', __('Amount'),['class'=>'form-control-label']) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('date', __('Date'),['class'=>'form-control-label']) }}
        {{ Form::date('date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('reference', __('Reference'),['class'=>'form-control-label']) }}
        {{ Form::text('reference', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}
