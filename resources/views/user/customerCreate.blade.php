{{Form::open(array('route'=>array('customers.store'),'method'=>'post'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{Form::label('contact',__('Contact'),['class'=>'form-control-label'])}}
            {{Form::text('contact',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{Form::label('email',__('Email'),['class'=>'form-control-label'])}}
            {{Form::text('email',null,array('class'=>'form-control'))}}
        </div>
    </div>

    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>


{{Form::close()}}

