
{{ Form::open(array('url' => 'roles')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{Form::label('name',__('Name'),['class'=>'form-control-label'])}}
        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Role Name')))}}
    </div>
    <div class="form-group  col-md-12">
        @if(!empty($permissions))
            <h6 class="my-3">{{__('Assign Permission to Roles')}}</h6>
            <table class="table table-striped mb-0" id="dataTable-1">
                <thead>
                <tr>
                    <th>{{__('Module')}} </th>
                    <th>{{__('Permissions')}} </th>
                </tr>
                </thead>
                <tbody>
                @php
                    $modules=['role','user','customer','vendor','item','estimation','banking','invoice','income','bill','expense','category','tax','unit'];
                @endphp
                @foreach($modules as $module)
                    <tr>
                        <td>{{ ucfirst($module) }}</td>
                        <td>
                            <div class="row ">
                                @if(in_array('manage '.$module,(array) $permissions))
                                    @if($key = array_search('manage '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Manage',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('create '.$module,(array) $permissions))
                                    @if($key = array_search('create '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Create',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('edit '.$module,(array) $permissions))
                                    @if($key = array_search('edit '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Edit',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('delete '.$module,(array) $permissions))
                                    @if($key = array_search('delete '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Delete',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('show '.$module,(array) $permissions))
                                    @if($key = array_search('show '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Show',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif


                                @if(in_array('buy '.$module,(array) $permissions))
                                    @if($key = array_search('buy '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Buy',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('send '.$module,(array) $permissions))
                                    @if($key = array_search('send '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Send',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif

                                @if(in_array('create payment '.$module,(array) $permissions))
                                    @if($key = array_search('create payment '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Create Payment',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('delete payment '.$module,(array) $permissions))
                                    @if($key = array_search('delete payment '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Delete Payment',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('income '.$module,(array) $permissions))
                                    @if($key = array_search('income '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Income',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('expense '.$module,(array) $permissions))
                                    @if($key = array_search('expense '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Expense',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('income vs expense '.$module,(array) $permissions))
                                    @if($key = array_search('income vs expense '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Income VS Expense',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('loss & profit '.$module,(array) $permissions))
                                    @if($key = array_search('loss & profit '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('tax '.$module,(array) $permissions))
                                    @if($key = array_search('tax '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Tax',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('loss & profit '.$module,(array) $permissions))
                                    @if($key = array_search('loss & profit '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Loss & Profit',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('invoice '.$module,(array) $permissions))
                                    @if($key = array_search('invoice '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Invoice',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('bill '.$module,(array) $permissions))
                                    @if($key = array_search('bill '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Bill',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                                @if(in_array('duplicate '.$module,(array) $permissions))
                                    @if($key = array_search('duplicate '.$module,$permissions))
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'custom-control-input','id' =>'permission'.$key])}}
                                            {{Form::label('permission'.$key,'Duplicate',['class'=>'custom-control-label'])}}<br>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="col-md-12 text-right">
        {{Form::submit(__('Create'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
    </div>
</div>
{{ Form::close() }}

