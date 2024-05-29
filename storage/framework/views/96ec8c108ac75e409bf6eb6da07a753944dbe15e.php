<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Register'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <!-- REGISTER SECTION -->
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 pe-md-5">
                    <div class="img-box text-center">
                        <img class="img-fluid" src="<?php echo e(getFile(config('location.logo.path') . 'game-character5.png')); ?>"
                            alt="..." />
                    </div>
                </div>
                <div class="col-md-7">
                    <h2><?php echo app('translator')->get('sign up'); ?></h2>
                    <div class="contact-box">
                        <form action="<?php echo e(route('register')); ?>" method="post">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('First name'); ?>
                                    </label>
                                    <input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="<?php echo app('translator')->get('John'); ?>" /> <?php $__errorArgs = ['firstname'];
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

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Last name'); ?>
                                    </label>
                                    <input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="<?php echo app('translator')->get('Doe'); ?>" /> <?php $__errorArgs = ['lastname'];
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
                            </div>

                            <div class="row">

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Username'); ?>
                                    </label>
                                    <input type="text" name="username" value="<?php echo e(old('username')); ?>"
                                        class="form-control" id="exampleFormControlInput1"
                                        placeholder="<?php echo app('translator')->get('username'); ?>" /> <?php $__errorArgs = ['username'];
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

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your Email'); ?>
                                    </label>
                                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control"
                                        id="exampleFormControlInput1" placeholder="<?php echo app('translator')->get('name@example.com'); ?>" />
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
                            </div>

                            <div class="row">

                                <div class="mb-4">
                                    <?php
                                        $country_code = (string) @getIpInfo()['code'] ?: null;
                                        $myCollection = collect(config('country'))->map(function ($row) {
                                            return collect($row);
                                        });
                                        $countries = $myCollection->sortBy('code');
                                    ?>


                                    <div class="form-group form-box">
                                        <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your Phone Number'); ?>
                                        </label>
                                        <div class="input-group prepend">
                                            <button class="game-btn prepend-btn w-50" type="button" >
                                                <select name="phone_code" class="form-control form-select country_code dialCode-change">
                                                    <?php $__currentLoopData = config('country'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($value['phone_code']); ?>" class="dropdown-item"
                                                                data-name="<?php echo e($value['name']); ?>"
                                                                data-code="<?php echo e($value['code']); ?>"
                                                            <?php echo e($country_code == $value['code'] ? 'selected' : ''); ?>>
                                                            <?php echo e($value['name']); ?> (<?php echo e($value['phone_code']); ?>)

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </button>


                                            <input type="text" name="phone" class="form-control dialcode-set"
                                                   value="<?php echo e(old('phone')); ?>" placeholder="<?php echo app('translator')->get('Your Phone Number'); ?>">


                                        </div>

                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger mt-1"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>


                                    <input type="hidden" name="country_code" value="<?php echo e(old('country_code')); ?>"
                                        class="text-dark">
                                </div>
                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your password'); ?>
                                    </label>
                                    <input type="password" name="password" class="form-control"
                                        id="exampleFormControlInput1" placeholder="********" /> <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-4 col md-6">
                                    <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Confirm password'); ?>
                                    </label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="exampleFormControlInput1" placeholder="********" />
                                </div>
                            </div>

                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <div class="link">
                                    <?php echo app('translator')->get('Already an user?'); ?> <a href="<?php echo e(route('login')); ?>"><?php echo app('translator')->get('Login'); ?></a>
                                </div>
                            </div>

                            <?php if(basicControl()->reCaptcha_status_registration): ?>
                                <div class="col-md-6 box mb-4 form-group">
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

                            <button class="game-btn">
                                <?php echo app('translator')->get('Sign Up'); ?>
                                <img src="<?php echo e(asset($themeTrue.'/images/icon/arrow-white.png')); ?>" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
    <style>
         .form-box .input-group.prepend .form-select {
            -webkit-clip-path: polygon(3% 0, 100% 0, 100% 0, 100% 70%, 100% 100%, 0 100%, 0 100%, 0% 30%);
            clip-path: polygon(3% 0, 100% 0, 100% 0, 100% 70%, 100% 100%, 0 100%, 0 100%, 0% 30%);
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        $(document).ready(function() {
            setDialCode();
            $(document).on('change', '.dialCode-change', function() {
                setDialCode();
            });

            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);
            }

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/auth/register.blade.php ENDPATH**/ ?>