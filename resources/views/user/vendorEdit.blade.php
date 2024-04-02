{{Form::model($user,array('route' => array('vendors.update', $user->id), 'method' => 'PUT')) }}

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="form-group">
            {{Form::label('contact',__('Contact'),['class'=>'form-control-label'])}}
            {{Form::text('contact',!empty($user->vendors)?$user->vendors->contact:'',array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
</div>
<h6 class="sub-title text-muted">{{__('Billing Address')}}</h6>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-control-label')) }}
            {{Form::text('billing_name',!empty($user->vendors)?$user->vendors->billing_name:'',array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_country',__('Country'),array('class'=>'form-control-label')) }}
            {{Form::text('billing_country',!empty($user->vendors)?$user->vendors->billing_name:'',array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_state',__('State'),array('class'=>'form-control-label')) }}
            {{Form::text('billing_state',!empty($user->vendors)?$user->vendors->billing_state:'',array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_city',__('City'),array('class'=>'form-control-label')) }}
            {{Form::text('billing_city',!empty($user->vendors)?$user->vendors->billing_city:'',array('class'=>'form-control'))}}
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_phone',__('Phone'),array('class'=>'form-control-label')) }}
            {{Form::text('billing_phone',!empty($user->vendors)?$user->vendors->billing_phone:'',array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="form-group">
            {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-control-label')) }}
            {{Form::text('billing_zip',!empty($user->vendors)?$user->vendors->billing_zip:'',array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{Form::label('billing_address',__('Address'),array('class'=>'form-control-label')) }}
            {{Form::textarea('billing_address',!empty($user->vendors)?$user->vendors->billing_address:'',array('class'=>'form-control','rows'=>2))}}
        </div>
    </div>
</div>

@if(Utility::getValByName('shipping_display')=='on')
    <div class="col-md-12 text-right">
        <a href="#" id="billing_data"data-toggle="tooltip" data-original-title="{{__('Copy to Billing Address')}}" ><i class="fas fa-copy" ></i></a>
    </div>
    <h6 class="sub-title text-muted">{{__('Shipping Address')}}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_name',__('Name'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_name',!empty($user->vendors)?$user->vendors->shipping_name:'',array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_country',__('Country'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_country',!empty($user->vendors)?$user->vendors->shipping_country:'',array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_state',__('State'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_state',!empty($user->vendors)?$user->vendors->shipping_state:'',array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_city',__('City'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_city',!empty($user->vendors)?$user->vendors->shipping_city:'',array('class'=>'form-control'))}}
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_phone',!empty($user->vendors)?$user->vendors->shipping_phone:'',array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-control-label')) }}
                {{Form::text('shipping_zip',!empty($user->vendors)?$user->vendors->shipping_zip:'',array('class'=>'form-control'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('shipping_address',__('Address'),array('class'=>'form-control-label')) }}
                {{Form::textarea('shipping_address',!empty($user->vendors)?$user->vendors->shipping_address:'',array('class'=>'form-control','rows'=>2))}}
            </div>
        </div>
    </div>
@endif
<div class="col-md-12 text-right">
    {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>

{{Form::close()}}

