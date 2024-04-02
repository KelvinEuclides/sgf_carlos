{{Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{Form::label('name',__('Name'))}}
        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Permission Name')))}}
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Upload'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}

