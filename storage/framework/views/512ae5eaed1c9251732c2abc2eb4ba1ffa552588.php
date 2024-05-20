

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Subscriber')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white"><?php echo e(__('Manage Subscriber')); ?></h6>
                </div>
                <div class="col text-right">
                    <div class="actions">

                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th scope="col" class="sort"> <?php echo e(__('Subscrib Id')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Date')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Name')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Subscription')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Price')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Payment Type')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Voucher')); ?></th>
                    <th scope="col" class="sort text-right"> <?php echo e(__('Action')); ?></th>
                </tr>
                </thead>
                <tbody class="list">
                <?php $__currentLoopData = $subscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscriber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr class="font-style">
                        <td class="budget"><?php echo e($subscriber->order_id); ?></td>
                        <td class="budget"><?php echo e($subscriber->created_at->format('d M Y')); ?></td>
                        <td class="budget"><?php echo e($subscriber->user_name); ?></td>
                        <td class="budget"><?php echo e($subscriber->plan_name); ?></td>
                        <td class="budget"><?php echo e(env('CURRENCY_SYMBOL').$subscriber->price); ?></td>
                        <td class="budget"><?php echo e($subscriber->payment_type); ?></td>

                        <td class="budget">
                            <?php echo e(!empty($subscriber->total_voucher_used)? !empty($subscriber->total_voucher_used->voucher_detail)?$subscriber->total_voucher_used->voucher_detail->code:'-':'-'); ?>

                        </td>
                        <td class="budget">
                            <?php if($subscriber->receipt != 'free voucher' && $subscriber->payment_type == 'STRIPE'): ?>
                                <a href="<?php echo e($subscriber->receipt); ?>" title="Invoice" target="_blank" class="">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            <?php elseif($subscriber->receipt == 'free voucher'): ?>
                                <p><?php echo e(__('Used 100 % discount voucher code.')); ?></p>
                            <?php elseif($subscriber->payment_type == 'Manually'): ?>
                                <p><?php echo e(__('Manually plan upgraded by super admin')); ?></p>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/subscriber/index.blade.php ENDPATH**/ ?>