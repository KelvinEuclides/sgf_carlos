{{ Form::open(array('url' => 'expense')) }}
<div class="row">
    <div class="form-group col-md-6">
        <div class="input-group">
            {{ Form::label('account', __('Account'),['class'=>'form-control-label']) }}
            {{ Form::select('account',$accounts,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group">
            {{ Form::label('vendor', __('Vendor'),['class'=>'form-control-label']) }}
            {{ Form::select('vendor', $vendors,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('date', __('Date'),['class'=>'form-control-label']) }}
        {{ Form::date('date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount'),['class'=>'form-control-label']) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>

    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
    </div>
    <div class="form-group col-md-6">
        <div class="input-group">
            {{ Form::label('category', __('Category'),['class'=>'form-control-label']) }}
            {{ Form::select('category', $categories,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('reference', __('Reference'),['class'=>'form-control-label']) }}
        {{ Form::text('reference', '', array('class' => 'form-control')) }}
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}
