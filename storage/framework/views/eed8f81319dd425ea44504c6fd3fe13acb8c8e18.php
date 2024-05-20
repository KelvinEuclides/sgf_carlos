<?php
    $users=\Auth::user();
    $profile=asset(Storage::url('uploads/avatar/'));
    $currantLang = $users->currentLanguage();
    $languages=\App\Utility::languages();
?>
<nav class="navbar navbar-main navbar-expand-lg navbar-border n-top-header" id="navbar-main">
    <div class="container-fluid">
        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbar-main-collapse"
                aria-controls="navbar-main-collapse"
                aria-expanded="false"
                aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- User's navbar -->
        <div class="navbar-user d-lg-none ml-auto">

            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item dropdown">

                    <a class="nav-link " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="h6 text-sm mb-0"><i class="fas fa-award"></i>  <?php echo e(Str::upper($currantLang)); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">

                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('change.language',$language)); ?>" class="dropdown-item <?php if($language == $currantLang): ?> active-language <?php endif; ?>">
                                <span> <?php echo e(Str::upper($language)); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        href="#"
                        class="nav-link nav-link-icon sidenav-toggler"
                        data-action="sidenav-pin"
                        data-target="#sidenav-main"
                    ><i class="fas fa-bars"></i
                        ></a>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a
                        class="nav-link pr-lg-0"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                    <span class="avatar avatar-sm rounded-circle">
                      <img src="<?php echo e((!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')); ?>"/>
                    </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0"><?php echo e(\Auth::user()->name); ?></h6>
                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-item has-icon">
                            <i class="fa fa-user"></i> <span><?php echo e(__('My Profile')); ?></span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span><?php echo e(__('Logout')); ?></span>
                        </a>
                        <form id="frm-logout" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
            <ul class="navbar-nav align-items-center d-none d-lg-flex">
                <li class="nav-item">
                    <a href="#" class="nav-link nav-link-icon sidenav-toggler" data-action="sidenav-pin" data-target="#sidenav-main"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <ul class="navbar-nav align-items-center d-none d-lg-flex navbar-nav ml-lg-auto align-items-lg-center">
                <li class="nav-item dropdown">

                    <a class="nav-link " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="h6 text-sm mb-0"><i class="fas fa-award"></i>  <?php echo e(Str::upper($currantLang)); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <?php if(\Auth::user()->type=='super admin'): ?>
                            <a class="dropdown-item" href="<?php echo e(route('manage.language',[$currantLang])); ?>"><?php echo e(__('Create New Language')); ?></a>
                        <?php endif; ?>
                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('change.language',$language)); ?>" class="dropdown-item <?php if($language == $currantLang): ?> active-language <?php endif; ?>">
                                <span> <?php echo e(Str::upper($language)); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-animate">
                    <a class="nav-link pr-lg-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media media-pill align-items-center">
                    <span class="avatar rounded-circle">
                       <img src="<?php echo e((!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')); ?>"/>
                    </span>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right dropdown-menu-arrow">
                        <h6 class="dropdown-header px-0"><?php echo e(Auth::user()->name); ?></h6>
                        <a href="<?php echo e(route('profile')); ?>" class="dropdown-item">
                            <i class="fa fa-user"></i> <span><?php echo e(__('My Profile')); ?></span>
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage role')): ?>
                            <a href="<?php echo e(route('roles.index')); ?>" class="dropdown-item">
                                <i class="fas fa-user-tag"></i> <span><?php echo e(__('Roles')); ?></span>
                            </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span><?php echo e(__('Logout')); ?></span>
                        </a>

                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</nav>
<?php /**PATH /var/www/resources/views/partials/header.blade.php ENDPATH**/ ?>