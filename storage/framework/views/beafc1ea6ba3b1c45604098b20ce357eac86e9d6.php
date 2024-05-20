
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Login')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="col-md-12 text-center mb-3">
        <a class="navbar-brand" href="#">
            <img src="<?php echo e(asset(Storage::url('uploads/logo/')).'/logo.png'); ?>" class="navbar-brand-img big-logo"  alt="logo">
        </a>
    </div>

    <div class="card shadow zindex-100 mb-0">

        <div class="card-body px-md-5 py-5">
            <div class="mb-5">
                <h6 class="h3"><?php echo e(__('Login')); ?></h6>
            </div>
            <span class="clearfix"></span>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-control-label"><?php echo e(__('Email')); ?></label>
                    <div class="input-group input-group-merge">


                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($message); ?></strong>
                </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-control-label"><?php echo e(__('Password')); ?></label>
                        </div>
                        <?php if(Route::has('password.request')): ?>
                            <div class="mb-2">
                                <a href="<?php echo e(route('password.request')); ?>" class="small text-muted text-underline--dashed border-primary"><?php echo e(__('Forgot Password?')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group input-group-merge">

                        <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($message); ?></strong>
                </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="input-group-append">
            <span class="input-group-text">
              <a href="#" data-toggle="password-text" data-target="#password">
                <i class="fas fa-eye"></i>
              </a>
            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>

                            <label class="form-check-label" for="remember">
                                <?php echo e(__('Remember Me')); ?>

                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-sm btn-primary btn-icon rounded-pill">
                        <span class="btn-inner--text"><?php echo e(__('Sign in')); ?></span>

                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer px-md-5"><small><?php echo e(__('Not registered?')); ?></small>
            <a href="<?php echo e(route('register')); ?>" class="small font-weight-bold"><?php echo e(__('Create account')); ?></a></div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/login.blade.php ENDPATH**/ ?>