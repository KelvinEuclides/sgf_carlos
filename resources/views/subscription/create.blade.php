{{ Form::open(array('url' => 'subscription', 'enctype' => "multipart/form-data")) }}
<div class="row">
    <div class="form-group col-md-6">
        {{Form::label('name',__('Name'),['class'=>'form-control-label'])}}
        {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('price',__('Price'),['class'=>'form-control-label'])}}
        {{Form::number('price',null,array('class'=>'form-control','placeholder'=>__('Enter Plan Price')))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('duration', __('Duration'),['class'=>'form-control-label']) }}
        {!! Form::select('duration', $arrDuration, null,array('class' => 'form-control select2','required'=>'required')) !!}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('max_users',__('Maximum Users'),['class'=>'form-control-label'])}}
        {{Form::number('max_users',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('max_customers',__('Maximum Customers'),['class'=>'form-control-label'])}}
        {{Form::number('max_customers',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('max_vendors',__('Maximum Vendors'),['class'=>'form-control-label'])}}
        {{Form::number('max_vendors',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('image', __('Image'),['class'=>'form-control-label']) }}
        {{Form::file('image',array('class'=>'form-control','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>

    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}

