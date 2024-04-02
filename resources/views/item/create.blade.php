{{Form::open(array('route'=>array('item.store'),'method'=>'post'))}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('sku',__('SKU'),['class'=>'form-control-label']) }}
            {{Form::text('sku',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('sale_price',__('Sale Price'),['class'=>'form-control-label']) }}
            {{Form::text('sale_price',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('purchase_price',__('Purchase Price'),['class'=>'form-control-label']) }}
            {{Form::text('purchase_price',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('tax', __('Tax'),['class'=>'text-type']) }}
            {{ Form::select('tax[]', $tax,null, array('class' => 'form-control select2','multiple','required'=>'required')) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('category', __('Category'),['class'=>'text-type']) }}
            {{ Form::select('category', $category,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('unit', __('Unit'),['class'=>'text-type']) }}
            {{ Form::select('unit', $unit,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}

