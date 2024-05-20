
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        var SalesChart = {
            series: [
                {
                    name: "<?php echo e(__('Subscriber')); ?>",
                    data: <?php echo json_encode($chartData['data']); ?>

                }
            ],
            colors: ['#36B37E', '#FF5630'],
            chart: {
                type: 'bar',
                height: 430
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories:<?php echo json_encode($chartData['label']); ?>,
            },
        };
        var sales = new ApexCharts(document.querySelector("#subscriber-chart"), SalesChart);
        sales.render();

    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1"><?php echo e(__('Total Users')); ?></h6>
                            <span class="h5 font-weight-bold mb-0 "><?php echo e($user->total_user); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1"><?php echo e(__('Total Subscriber')); ?></h6>
                            <span class="h5 font-weight-bold mb-0 "><?php echo e($user->total_subscriber); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1"><?php echo e(__('Total Subscriber Amount')); ?></h6>
                            <span class="h5 font-weight-bold mb-0 "><?php echo e(\Auth::user()->priceFormat($user['total_subscriber_price'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="h4 font-weight-400"><?php echo e(__('Subscriber')); ?></h4>
            <div class="card bg-none">
                <div class="chart">
                    <div id="subscriber-chart" data-color="primary"  class="p-3"></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/dashboard/super_admin.blade.php ENDPATH**/ ?>