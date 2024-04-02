{{Form::open(array('route'=>array('tax.store'),'method'=>'post'))}}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter tax name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('rate',__('Rate'),['class'=>'form-control-label']) }}
            {{Form::text('rate',null,array('class'=>'form-control','placeholder'=>__('Enter tax rate'),'required'=>'required','step'=>'0.01'))}}
        </div>
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}

