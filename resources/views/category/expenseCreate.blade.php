{{Form::open(array('route'=>array('category.expense.store'),'method'=>'post'))}}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Item Category Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('color', __('Color'),['class'=>'form-control-label']) }}
            {{ Form::color('color', '', array('class' => 'form-control jscolor','required'=>'required')) }}
        </div>
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}

