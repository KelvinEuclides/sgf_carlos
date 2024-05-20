

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Voucher')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '.code', function () {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create voucher')): ?>
        <a href="#" data-size="lg" data-url="<?php echo e(route('voucher.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Voucher')); ?>" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0 color_white"><?php echo e(__('Manage Voucher')); ?></h6>
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
                    <th scope="col" class="sort"> <?php echo e(__('Name')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Code')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Discount (%)')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Limit')); ?></th>
                    <th scope="col" class="sort"> <?php echo e(__('Used')); ?></th>
                    <th scope="col" class="sort text-right"> <?php echo e(__('Action')); ?></th>
                </tr>
                </thead>
                <tbody class="list">
                <?php $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="font-style">
                        <td class="budget"><?php echo e($voucher->name); ?></td>
                        <td class="budget"><?php echo e($voucher->code); ?></td>
                        <td class="budget"><?php echo e($voucher->discount); ?></td>
                        <td class="budget"><?php echo e($voucher->limit); ?></td>
                        <td class="budget"><?php echo e($voucher->used_voucher()); ?></td>
                        <?php if(Gate::check('edit voucher') || Gate::check('delete voucher')): ?>
                            <td class="Action text-right">
                                <div class="actions ml-3">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show voucher')): ?>
                                        <a href="<?php echo e(route('voucher.show',$voucher->id)); ?>" class="edit-icon" data-toggle="tooltip" data-original-title="<?php echo e(__('View')); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit voucher')): ?>
                                        <a href="#" data-size="lg" data-url="<?php echo e(route('voucher.edit',$voucher->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Voucher')); ?>" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                            <i class="far fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit voucher')): ?>
                                        <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-<?php echo e($voucher->id); ?>').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['voucher.destroy', $voucher->id],'id'=>'delete-form-'.$voucher->id]); ?>

                                        <?php echo Form::close(); ?>

                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/voucher/index.blade.php ENDPATH**/ ?>