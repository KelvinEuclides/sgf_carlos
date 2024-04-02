{{ Form::open(array('route' => array('store.language'))) }}
<div class="form-group col-md-12">
    {{ Form::label('code', __('Language Code')) }}
    {{ Form::text('code', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="col-md-12 text-right">
    {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>
{{ Form::close() }}

