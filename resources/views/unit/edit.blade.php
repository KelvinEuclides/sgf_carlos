{{Form::model($unit, array('route' => array('unit.update', $unit->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),['class'=>'form-control-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter unit'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{Form::close()}}



