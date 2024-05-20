

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Subscription')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create subscription')): ?>
        <?php if(env('ENABLE_STRIPE') == 'on' || env('ENABLE_PAYPAL') == 'on' ): ?>
            <a href="#" data-size="lg" data-url="<?php echo e(route('subscription.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Subscription')); ?>" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
                <div class="card card-fluid">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?php echo e($subscription->name); ?></h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    <?php if( \Auth::user()->type == 'super admin'): ?>
                                        <a title="Edit Plan" data-size="lg" href="#" class="action-item" data-url="<?php echo e(route('subscription.edit',$subscription->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Subscription')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-edit"></i></a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center <?php echo e(!empty(\Auth::user()->type != 'super admin')?'plan-box':''); ?>">
                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                            <img alt="Image placeholder" src="<?php echo e(asset(Storage::url('uploads/subscription')).'/'.$subscription->image); ?>" class="">
                        </a>

                        <h5 class="h6 my-4"> <?php echo e(env('CURRENCY_SYMBOL').$subscription->price.' / '.$subscription->duration); ?></h5>

                        <?php if(\Auth::user()->type=='company' && \Auth::user()->subscription == $subscription->id): ?>
                            <h5 class="h6 my-4">
                                <?php echo e(__('Expired : ')); ?> <?php echo e(\Auth::user()->subscription_expire_date ? \Auth::user()->dateFormat(\Auth::user()->subscription_expire_date):__('Unlimited')); ?>

                            </h5>

                        <?php endif; ?>

                        <h5 class="h6 my-4"><?php echo e($subscription->description); ?></h5>

                        <?php if(\Auth::user()->type == 'company' && \Auth::user()->subscription == $subscription->id): ?>
                            <span class="clearfix"></span>
                            <span class="badge badge-pill badge-success"><?php echo e(__('Active')); ?></span>
                        <?php endif; ?>
                        <?php if(($subscription->id != \Auth::user()->subscription) && \Auth::user()->type!='super admin' ): ?>
                            <?php if($subscription->price > 0): ?>
                                <a class="badge badge-pill badge-primary" href="<?php echo e(route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($subscription->id))); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Buy Plan')); ?>">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-auto text-center">
                                <span class="h5 mb-0"><?php echo e($subscription->max_users); ?></span>
                                <span class="d-block text-sm"><?php echo e(__('User')); ?></span>
                            </div>
                            <div class="col-auto text-center">
                                <span class="h5 mb-0"><?php echo e($subscription->max_customers); ?></span>
                                <span class="d-block text-sm"> <?php echo e(__('Customer')); ?></span>
                            </div>
                            <div class="col-auto text-center">
                                <span class="h5 mb-0"><?php echo e($subscription->max_vendors); ?></span>
                                <span class="d-block text-sm"> <?php echo e(__('Vendor')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/subscription/index.blade.php ENDPATH**/ ?>