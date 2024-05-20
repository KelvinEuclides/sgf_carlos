<?php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
?>
<div class="sidenav custom-sidenav" id="sidenav-main">
    <!-- Sidenav header -->
    <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e($logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')); ?>" class="navbar-brand-img"/>
        </a>
        <div class="ml-auto">
            <!-- Sidenav toggler -->
            <div class="sidenav-toggler sidenav-toggler-dark d-md-none" data-action="sidenav-unpin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="scrollbar-inner">
        <div class="div-mega">
            <ul class="navbar-nav navbar-nav-docs">
                <li class="nav-item">
                    <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'home') ? 'active' : ''); ?>">
                        <i class="fas fa-home"></i><?php echo e(__('Dashboard')); ?>

                    </a>
                </li>
                <?php if(\Auth::user()->type=='customer'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('customer.estimation')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'customer.estimation' || Request::route()->getName() == 'customer.estimation.show') ? 'active' : ''); ?>">
                            <i class="fas fa-file-invoice"></i><?php echo e(__('Estimations')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('customer.invoice')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'customer.invoice' || Request::route()->getName() == 'customer.invoice.show') ? 'active' : ''); ?>">
                            <i class="fas fa-file-invoice"></i><?php echo e(__('Invoices')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('customer.payment')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'customer.payment') ? 'active' : ''); ?>">
                            <i class="fas fa-money-bill-alt"></i><?php echo e(__('Transactions')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\Auth::user()->type=='vendor'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('vendor.bill')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'vendor.bill' || Request::route()->getName() == 'vendor.bill.show') ? 'active' : ''); ?>">
                            <i class="fas fa-file-invoice"></i><?php echo e(__('Bills')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('vendor.payment')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'vendor.payment') ? 'active' : ''); ?>">
                            <i class="fas fa-money-bill-alt"></i><?php echo e(__('Transactions')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\Auth::user()->type=='super admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'users.index') ? 'active' : ''); ?>">
                            <i class="fas fa-users"></i><?php echo e(__('Company')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if( Gate::check('manage user') || Gate::check('manage customer') || Gate::check('manage vendor')): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed <?php echo e((Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'active' : ''); ?>" href="#navbar-members" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo e(( Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'true' : 'false'); ?>" aria-controls="navbar-members">
                            <i class="fas fa-users"></i><?php echo e(__('Members')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e(( Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'show' : ''); ?>" id="navbar-members">
                            <ul class="nav flex-column">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'users.index') ? 'a-active' : ''); ?>"><?php echo e(__('Users')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage customer')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('customers.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show') ? 'a-active' : ''); ?>"><?php echo e(__('Customers')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage vendor')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('vendors.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'a-active' : ''); ?>"><?php echo e(__('Vendors')); ?></a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage item')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('item.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'item.index') ? 'active' : ''); ?>">
                            <i class="fas fa-sitemap"></i><?php echo e(__('Items')); ?>

                        </a>
                    </li>
                <?php endif; ?>


                <?php if( Gate::check('manage banking')): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed <?php echo e((Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'active' : ''); ?>" href="#navbar-banking" data-toggle="collapse" role="button" aria-expanded="<?php echo e((Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'true' : 'false'); ?>" aria-controls="navbar-banking">
                            <i class="fas fa-money-check"></i><?php echo e(__('Banking')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e((Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'show' : ''); ?>" id="navbar-banking">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(route('account.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'account.index') ? 'a-active' : ''); ?>"><?php echo e(__('Accounts')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('transfer.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'transfer.index') ? 'a-active' : ''); ?>"><?php echo e(__('Transfers')); ?></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if( Gate::check('manage invoice') || Gate::check('manage income') || Gate::check('manage estimation')): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed <?php echo e((Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' || Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show'
                || Request::route()->getName() =='income.index') ?'active' : ''); ?>" href="#navbar-sales" data-toggle="collapse" role="button" aria-expanded="<?php echo e((Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create'
                         || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' || Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create'
                         || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show' || Request::route()->getName() == 'income.index') ? 'true' : 'false'); ?>"
                           aria-controls="navbar-sales">
                            <i class="fas fa-file-invoice"></i><?php echo e(__('Sales')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e((Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show'  ||Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show'
            || Request::route()->getName() == 'income.index') ? 'show'
                         : ''); ?>" id="navbar-sales">
                            <ul class="nav flex-column">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage estimation')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('estimation.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show') ? 'a-active' : ''); ?>">
                                            <?php echo e(__('Estimations')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage invoice')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('invoice.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show') ? 'a-active' : ''); ?>"><?php echo e(__('Invoices')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage income')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('income.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'income.index') ? 'a-active' : ''); ?>"><?php echo e(__('Incomes')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if( Gate::check('manage bill') ||  Gate::check('manage expense')): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed <?php echo e((Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show'  || Request::route()->getName() == 'expense.index') ? 'active' : ''); ?>" href="#navbar-purchses" data-toggle="collapse" role="button" aria-expanded="<?php echo e((Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' ||
                        Request::route()
                        ->getName() == 'bill.edit' ||
                         Request::route()->getName() == 'bill.show') ? 'true' : 'false'); ?>" aria-controls="navbar-purchses">
                            <i class="fas fa-receipt"></i><?php echo e(__('Purchase')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e((Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show'  || Request::route()->getName() == 'expense.index') ? 'show' : ''); ?>" id="navbar-purchses">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(route('bill.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show') ? 'a-active' : ''); ?>"><?php echo e(__('Bills')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('expense.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'expense.index') ? 'a-active' : ''); ?>"><?php echo e(__('Expenses')); ?></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if( Gate::check('manage subscription')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('subscription.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'subscription.index' || Request::route()->getName() == 'stripe') ? 'active' : ''); ?>">
                            <i class="fas fa-swatchbook"></i><?php echo e(__('Subscription')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\Auth::user()->type=='super admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('voucher.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'voucher.index' || Request::route()->getName() == 'voucher.show') ? 'active' : ''); ?>">
                            <i class="fas fa-gift"></i><?php echo e(__('Voucher')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('subscriber.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'subscriber.index') ? 'active' : ''); ?>">
                            <i class="fas fa-cart-plus"></i><?php echo e(__('Subscriber')); ?>

                        </a>
                    </li>
                <?php endif; ?>
                <?php if( Gate::check('manage summary')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e((Request::segment(1) == 'summary' )?' active':'collapsed'); ?>" href="#navbar-summary" data-toggle="collapse" role="button" aria-expanded="<?php echo e((Request::segment(1) == 'summary')?'true':'false'); ?>" aria-controls="navbar-reports">
                            <i class="fas fa-chart-line"></i><?php echo e(__('Summary')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e((Request::segment(1) == 'summary')?'show':''); ?>" id="navbar-summary">
                            <ul class="nav flex-column submenu-ul">
                                <li class="nav-item ">
                                    <a href="<?php echo e(route('estimation.summary')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'estimation.summary' || Request::route()->getName() == 'estimation.summary.list' ) ? 'a-active' : ''); ?>"><?php echo e(__('Estimation')); ?></a>
                                </li>
                                <li class="nav-item ">
                                    <a href="<?php echo e(route('invoice.summary')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'invoice.summary' || Request::route()->getName() == 'invoice.summary.list' ) ? 'a-active' : ''); ?>"><?php echo e(__('Invoice')); ?></a>
                                </li>
                                <li class="nav-item ">
                                    <a href="<?php echo e(route('bill.summary')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'bill.summary' || Request::route()->getName() == 'bill.summary.list' ) ? 'a-active' : ''); ?>"><?php echo e(__('Bill')); ?></a>
                                </li>
                                <li class="nav-item ">
                                    <a href="<?php echo e(route('sales.summary')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'sales.summary' || Request::route()->getName() == 'sales.summary.list' ) ? 'a-active' : ''); ?>"><?php echo e(__('Sales')); ?></a>
                                </li>
                                <li class="nav-item ">
                                    <a href="<?php echo e(route('purchase.summary')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'purchase.summary' || Request::route()->getName() == 'purchase.summary.list' ) ? 'a-active' : ''); ?>"><?php echo e(__('Purchase')); ?></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if( Gate::check('manage category') ||  Gate::check('manage tax') ||  Gate::check('manage unit')): ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed <?php echo e((Request::route()->getName() == 'category.item.index' || Request::route()->getName() == 'category.income.index' || Request::route()->getName() == 'category.expense.index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'active' : ''); ?>" href="#navbar-getting-started" data-toggle="collapse" role="button" aria-expanded="<?php echo e((Request::route()->getName() == 'category.item.index' || Request::route()
                    ->getName() == 'category.income.index' ||
                    Request::route()->getName() == 'category.expense
                    .index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'true' : 'false'); ?>" aria-controls="navbar-getting-started">
                            <i class="fab fa-meetup"></i><?php echo e(__('Setup')); ?>

                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse <?php echo e((Request::route()->getName() == 'category.item.index' || Request::route()->getName() == 'category.income.index' || Request::route()->getName() == 'category.expense.index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'show' : ''); ?>" id="navbar-getting-started">
                            <ul class="nav flex-column">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage category')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('category.item.index')); ?>" class="nav-link  <?php echo e((Request::route()->getName() == 'category.item.index') ? 'a-active' : ''); ?>"><?php echo e(__('Item Category')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage category')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('category.income.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'category.income.index') ? 'a-active' : ''); ?>"><?php echo e(__('Income Category')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage category')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('category.expense.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'category.expense.index') ? 'a-active' : ''); ?>"><?php echo e(__('Expense Category')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage tax')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('tax.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'tax.index') ? 'a-active' : ''); ?>"><?php echo e(__('Taxes')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage unit')): ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route('unit.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'unit.index') ? 'a-active' : ''); ?>"><?php echo e(__('Units')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
                <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='super admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('setting.index')); ?>" class="nav-link <?php echo e((Request::route()->getName() == 'setting.index') ? 'active' : ''); ?>">
                            <i class="fas fa-cog"></i><?php echo e(__('Settings')); ?>

                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/partials/sidebar.blade.php ENDPATH**/ ?>