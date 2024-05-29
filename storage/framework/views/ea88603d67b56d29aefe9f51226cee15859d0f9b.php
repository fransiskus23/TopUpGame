<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Login'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <!-- LOGIN SECTION -->
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pe-md-5">
                    <div class="img-box text-center">
                        <img class="img-fluid" src="<?php echo e(getFile(config('location.logo.path') . 'game-character5.png')); ?>"
                            alt="..." />
                    </div>
                </div>
                <div class="col-md-6">
                    <h2><?php echo app('translator')->get('sign in'); ?></h2>
                    <p>
                        <?php echo app('translator')->get('Please sign-in to your account and start the adventure...'); ?>
                    </p>
                    <div class="contact-box">
                        <form action="<?php echo e(route('login')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your Email or Username'); ?>
                                </label>
                                <input type="text" name="username" class="form-control" id="exampleFormControlInput1"
                                    placeholder="<?php echo app('translator')->get('name@example.com'); ?>" />
                                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger  mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger  mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your password'); ?>
                                </label>
                                <input type="password" name="password" class="form-control" id="exampleFormControlInput1"
                                    placeholder="********" />
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="link" align="right">
                                    <a href="<?php echo e(route('password.request')); ?>"><?php echo app('translator')->get('Forgot Password'); ?></a>
                                </div>
                            </div>

                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>

                                        type="checkbox" value="" id="remember" />
                                    <label class="form-check-label" for="remember">
                                        <?php echo app('translator')->get('Remember me'); ?>
                                    </label>
                                </div>
                                <div class="link">
                                    <?php echo app('translator')->get("Don't have any account?"); ?> <a href="<?php echo e(route('register')); ?>"><?php echo app('translator')->get('Register'); ?></a>
                                </div>
                            </div>

                            <?php if(basicControl()->reCaptcha_status_login): ?>
                                <div class="box mb-4 form-group">
                                    <?php echo NoCaptcha::renderJs(session()->get('trans')); ?>

                                    <?php echo NoCaptcha::display(['data-theme' => 'dark']); ?>

                                    <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger mt-1"><?php echo app('translator')->get($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php endif; ?>

                            <button class="game-btn" type="submit">
                                <?php echo app('translator')->get('sign in'); ?>
                                <img src="<?php echo e(asset($themeTrue.'/images/icon/arrow-white.png')); ?>" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/auth/login.blade.php ENDPATH**/ ?>