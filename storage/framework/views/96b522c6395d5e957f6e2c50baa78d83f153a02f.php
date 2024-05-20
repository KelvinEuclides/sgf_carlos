
<?php $__env->startSection('page-title'); ?>
    <?php if(\Auth::user()->type=='super admin'): ?>
        <?php echo e(__('Company')); ?>

    <?php else: ?>
        <?php echo e(__('Users')); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <a href="#" data-size="md" data-url="<?php echo e(route('users.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New User')); ?>" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
    </a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-lg-3 col-sm-6">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img src="<?php echo e((!empty($user->avatar))? asset(Storage::url("uploads/avatar/".$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))); ?>" class="avatar rounded-circle avatar-md">
                                </a>
                            </div>
                            <div class="col ml-md-n2">
                                <a href="#!" class="d-block h6 mb-0"><?php echo e($user->name); ?></a>
                                <small class="d-block text-muted"><?php echo e($user->email); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <?php if(\Auth::user()->type=='super admin'): ?>
                            <div class="row">
                                <div class="col-auto">
                                    <span class="h6 text-sm mb-0"><?php echo e(!empty($user->currentSubscription)?$user->currentSubscription->name:''); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('Subscription')); ?></span>
                                </div>

                                <div class="col text-right">
                                    <span class="h6 text-sm mb-0"><a href="#" data-url="<?php echo e(route('subscription.upgrade',$user->id)); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Upgrade Subscription')); ?>"><i class="fas fa-pen-nib" style="font-size: 22px;"></i></a></span>
                                    <span class="d-block text-sm"><?php echo e(__('Upgrade')); ?></span>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-auto text-center">
                                    <span class="h6 mb-0"><?php echo e($user->totalCompanyUser($user->id)); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('User')); ?></span>
                                </div>
                                <div class="col text-center">
                                    <span class="h6 mb-0"><?php echo e($user->totalCompanyCustomer($user->id)); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('Customer')); ?></span>
                                </div>
                                <div class="col-auto text-center">
                                    <span class="h6 mb-0"><?php echo e($user->totalCompanyVendor($user->id)); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('Vendor')); ?></span>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col text-center">
                                    <span class="text-dark text-xs"><?php echo e(__('Subscription Expired : ')); ?> <?php echo e(!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date): __('Unlimited')); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-auto">
                                    <span class="h6 text-sm mb-0"><?php echo e($user->type); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('Type')); ?></span>
                                </div>

                                <div class="col text-right">
                                    <span class="h6 text-sm mb-0"><?php echo e(\Auth::user()->dateFormat($user->created_at)); ?></span>
                                    <span class="d-block text-sm"><?php echo e(__('Created At')); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                    <?php if(Gate::check('edit user') || Gate::check('delete user') || \Auth::user()->type=='super admin'): ?>
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <?php if($user->is_active==1): ?>
                                    <div class="col-6">
                                        <?php if(Gate::check('edit user') || \Auth::user()->type=='super admin'): ?>
                                            <a href="#" class="dropdown-item text-sm" data-url="<?php echo e(route('users.edit',$user->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Update User')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>"> <i class="far fa-edit"></i></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-6 text-right">
                                        <?php if(Gate::check('delete user') || \Auth::user()->type=='super admin'): ?>
                                            <a data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" class="dropdown-item text-sm" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($user['id']); ?>').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]); ?>

                                            <?php echo Form::close(); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/user/index.blade.php ENDPATH**/ ?>