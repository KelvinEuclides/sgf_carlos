{{ Form::open(array('route' => array('invoice.payment', $invoice->id),'method'=>'post')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::date('date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::text('amount',$invoice->getDue(), array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('account_id', __('Account')) }}
        {{ Form::select('account_id',$accounts,null, array('class' => 'form-control select2','required'=>'required')) }}
    </div>

    <div class="form-group  col-md-6">
        {{ Form::label('reference', __('Reference')) }}
        {{ Form::text('reference', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}
