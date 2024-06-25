<?php
use App\Subscription;
$subscriptions = Subscription::pluck('name','id');
?>
<?php echo e(Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT'))); ?>

<div class="row">
    <div class="col-md-12">
        <div class="form-group ">
            <?php echo e(Form::label('name',('Name'),['class'=>'form-control-label'])); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>('Enter User Name')))); ?>

            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-name" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('email',('Email'),['class'=>'form-control-label'])); ?>

            <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>('Enter User Email')))); ?>

            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-email" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <?php if(\Auth::user()->type == 'super admin'): ?>
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('nuit',('Nuit'),['class'=>'form-control-label'])); ?>

            <?php echo e(Form::text('nuit',null,array('class'=>'form-control','placeholder'=>('Nuit'),'required'=>'required'))); ?>

            <?php $__errorArgs = ['nuit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-nuit" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('endereco',('Endereço'),['class'=>'form-control-label'])); ?>

            <?php echo e(Form::text('endereco',null,array('class'=>'form-control','placeholder'=>('Endereço'),'required'=>'required'))); ?>

            <?php $__errorArgs = ['endereco'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-endereco" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('subscription',('Subscription'),['class'=>'form-control-label'])); ?>

            <?php echo Form::select('subscription',$subscriptions ?? '', null,array('class' => 'form-control select2','required'=>'required')); ?>

     </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('password',('Password'),['class'=>'form-control-label'])); ?>

            <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>('Enter User Password'),'required'=>'required','minlength'=>"6"))); ?>

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-password" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if(\Auth::user()->type != 'super admin'): ?>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('role', ('User Role'),['class'=>'form-control-label'])); ?>

            <?php echo Form::select('role', $roles, $user->roles,array('class' => 'form-control select2','required'=>'required')); ?>

            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-role" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endif; ?>
    <div class="col-md-12 text-right">
        <?php echo e(Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))); ?>

    </div>
</div>
<?php echo e(Form::close()); ?><?php /**PATH /var/www/resources/views/user/edit.blade.php ENDPATH**/ ?>