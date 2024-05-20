
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Settings')); ?>

<?php $__env->stopSection(); ?>
<?php
    $logo=asset(Storage::url('uploads/logo/'));
     $lang=\App\Utility::getValByName('default_language');
?>
<?php $__env->startSection('action-button'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
            <li class="nav-item ml-4">
                <a href="#site-setting" id="site-settings" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                    <?php echo e(__('Site Settings')); ?>

                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#email-setting" id="email-settings" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                    <?php echo e(__('Email Setttings')); ?>

                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#payment-setting" id="payment-settings" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                    <?php echo e(__('Payment Setttings')); ?>

                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="site-setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        <?php echo e(Form::model($settings,array('route'=>'site.setting','method'=>'POST','enctype' => "multipart/form-data"))); ?>


                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="logo" class="form-control-label"><?php echo e(__('Logo')); ?></label>
                                    <input type="file" name="logo" id="logo" class="custom-input-file">
                                    <label for="logo">
                                        <i class="fa fa-upload"></i>
                                        <span><?php echo e(__('Choose a file')); ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="favicon" class="form-control-label"><?php echo e(__('Favicon')); ?></label>
                                    <input type="file" name="favicon" id="favicon" class="custom-input-file">
                                    <label for="favicon">
                                        <i class="fa fa-upload"></i>
                                        <span><?php echo e(__('Choose a file')); ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('title_text',__('Title Text'),array('class'=>'form-control-label'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))); ?>

                                <?php $__errorArgs = ['title_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-title_text" role="alert">
                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('footer_text',__('Footer Text'),array('class'=>'form-control-label'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('footer_text',null,array('class'=>'form-control','placeholder'=>__('Footer Text')))); ?>

                                <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-footer_text" role="alert">
                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('default_language',__('Default Language'))); ?>

                                <div class="changeLanguage">
                                    <select name="default_language" id="default_language" class="form-control select2">
                                        <?php $__currentLoopData = \App\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($lang == $language): ?> selected <?php endif; ?> value="<?php echo e($language); ?>"><?php echo e(Str::upper($language)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="<?php echo e(__('Save Settings')); ?>">
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="email-setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        <?php echo e(Form::model($settings,array('route'=>'email.setting','method'=>'post'))); ?>

                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_driver',__('Mail Driver'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_driver',env('MAIL_DRIVER'),array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))); ?>

                                <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_host',__('Mail Host'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_host',env('MAIL_HOST'),array('class'=>'form-control ','placeholder'=>__('Enter Mail Driver')))); ?>

                                <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_driver" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_port',__('Mail Port'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_port',env('MAIL_PORT'),array('class'=>'form-control','placeholder'=>__('Enter Mail Port')))); ?>

                                <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_username',__('Mail Username'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_username',env('MAIL_USERNAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))); ?>

                                <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_username" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_password',__('Mail Password'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_password',env('MAIL_PASSWORD'),array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))); ?>

                                <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_password" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_encryption',__('Mail Encryption'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_encryption',env('MAIL_ENCRYPTION'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))); ?>

                                <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_encryption" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_from_address',__('Mail From Address'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_from_address',env('MAIL_FROM_ADDRESS'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))); ?>

                                <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_address" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                <?php echo e(Form::label('mail_from_name',__('Mail From Name'),array('class'=>'form-control-label'))); ?>

                                <?php echo e(Form::text('mail_from_name',env('MAIL_FROM_NAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))); ?>

                                <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-mail_from_name" role="alert">
                                                 <strong class="text-danger"><?php echo e($message); ?></strong>
                                                 </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="<?php echo e(__('Save Settings')); ?>">
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="payment-setting" role="tabpanel" aria-labelledby="orders-tab">
                <div class="">
                    <div class="card-body">
                        <?php echo e(Form::model($settings,array('route'=>'payment.setting','method'=>'post'))); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('currency_symbol',__('Currency Symbol *'),array('class'=>'form-control-label'))); ?>

                                    <?php echo e(Form::text('currency_symbol',env('CURRENCY_SYMBOL'),array('class'=>'form-control','required','placeholder'=>__('Enter Currency Symbol')))); ?>

                                    <?php $__errorArgs = ['currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-currency_symbol" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('currency',__('Currency *'),array('class'=>'form-control-label'))); ?>

                                    <?php echo e(Form::text('currency',env('CURRENCY'),array('class'=>'form-control font-style','required','placeholder'=>__('Enter Currency')))); ?>

                                    <small> <?php echo e(__('Note: Add currency code as per three-letter ISO code.')); ?><br> <a href="https://stripe.com/docs/currencies" target="_blank"><?php echo e(__('you can find out here..')); ?></a></small> <br>
                                    <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-currency" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-6 py-2">
                                <h5 class="h5"><?php echo e(__('Stripe Payment')); ?></h5>
                            </div>
                            <div class="col-6 py-2 text-right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="enable_stripe" id="enable_stripe" <?php echo e(env('ENABLE_STRIPE') == 'on' ? 'checked="checked"' : ''); ?>>
                                    <label class="custom-control-label form-control-label" for="enable_stripe"><?php echo e(__('Enable Stripe')); ?></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('stripe_key',__('Stripe Key'),array('class'=>'form-control-label'))); ?>

                                    <?php echo e(Form::text('stripe_key',env('STRIPE_KEY'),['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])); ?>

                                    <?php $__errorArgs = ['stripe_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-stripe_key" role="alert">
                                             <strong class="text-danger"><?php echo e($message); ?></strong>
                                         </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('stripe_secret',__('Stripe Secret'),array('class'=>'form-control-label'))); ?>

                                    <?php echo e(Form::text('stripe_secret',env('STRIPE_SECRET'),['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])); ?>

                                    <?php $__errorArgs = ['stripe_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-stripe_secret" role="alert">
                                             <strong class="text-danger"><?php echo e($message); ?></strong>
                                         </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-6 py-2">
                                <h5 class="h5"><?php echo e(__('PayPal Payment')); ?></h5>
                            </div>
                            <div class="col-6 py-2 text-right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="enable_paypal" id="enable_paypal" <?php echo e(env('ENABLE_PAYPAL') == 'on' ? 'checked="checked"' : ''); ?>>
                                    <label class="custom-control-label form-control-label" for="enable_paypal"><?php echo e(__('Enable Paypal')); ?></label>
                                </div>
                            </div>
                            <div class="col-md-12 pb-4">
                                <label class="paypal-label form-control-label" for="paypal_mode"><?php echo e(__('Paypal Mode')); ?></label> <br>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-primary btn-sm  <?php echo e(env('PAYPAL_MODE') == 'sandbox' ? 'active' : ''); ?>">
                                        <input type="radio" name="paypal_mode" value="sandbox"><?php echo e(__('Sandbox')); ?>

                                    </label>
                                    <label class="btn btn-primary btn-sm  <?php echo e(env('PAYPAL_MODE') == 'live' ? 'active' : ''); ?>">
                                        <input type="radio" name="paypal_mode" value="live"><?php echo e(__('Live')); ?>

                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paypal_client_id"><?php echo e(__('Client ID')); ?></label>
                                    <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="<?php echo e(env('PAYPAL_CLIENT_ID')); ?>" placeholder="<?php echo e(__('Client ID')); ?>"/>
                                    <?php if($errors->has('paypal_client_id')): ?>
                                        <span class="invalid-feedback d-block">
                                            <?php echo e($errors->first('paypal_client_id')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paypal_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                    <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="<?php echo e(env('PAYPAL_SECRET_KEY')); ?>" placeholder="<?php echo e(__('Secret Key')); ?>"/>
                                    <?php if($errors->has('paypal_secret_key')): ?>
                                        <span class="invalid-feedback d-block">
                                            <?php echo e($errors->first('paypal_secret_key')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="<?php echo e(__('Save Settings')); ?>">
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/setting/super_admin.blade.php ENDPATH**/ ?>