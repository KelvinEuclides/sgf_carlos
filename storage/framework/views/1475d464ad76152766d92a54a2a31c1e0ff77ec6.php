<?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="list-group-item">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="#" class="avatar rounded-circle">
                    <img alt="Image placeholder" src="<?php echo e(asset(Storage::url('uploads/subscription')).'/'.$subscription->image); ?>" class="">
                </a>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0"><?php echo e($subscription->name); ?></a>
                <div>
                    <span class="text-sm"><?php echo e(\Auth::user()->priceFormat($subscription->price)); ?> <?php echo e(' / '. $subscription->duration); ?></span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0"><?php echo e(__('Users')); ?></a>
                <div>
                    <span class="text-sm"><?php echo e($subscription->max_users); ?></span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0"><?php echo e(__('Customers')); ?></a>
                <div>
                    <span class="text-sm"><?php echo e($subscription->max_customers); ?></span>
                </div>
            </div>
            <div class="col ml-n2">
                <a href="#!" class="d-block h6 mb-0"><?php echo e(__('Vendors')); ?></a>
                <div>
                    <span class="text-sm"><?php echo e($subscription->max_vendors); ?></span>
                </div>
            </div>
            <div class="col-auto">
                <?php if($user->subscription==$subscription->id): ?>
                    <span class="badge badge-soft-success mr-2"><?php echo e(__('Active')); ?></span>
                <?php else: ?>
                    <a href="<?php echo e(route('subscription.active',[$user->id,$subscription->id])); ?>" class="btn btn-xs btn-secondary btn-icon" data-toggle="tooltip" data-original-title="<?php echo e(__('Click to Subscription Plan')); ?>">
                        <span class="btn-inner--icon"><i class="fas fa-cart-plus"></i></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /var/www/resources/views/user/subscription.blade.php ENDPATH**/ ?>