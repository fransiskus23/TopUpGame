<?php $__env->startSection('title', trans($title)); ?>

<?php $__env->startSection('content'); ?>

    <!-- CONTACT SECTION -->
    <section class="contact-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pe-md-5 mb-5 mb-md-0">
                    <h2><?php echo app('translator')->get(@$contact->heading); ?></h2>
                    <p>
                        <?php echo app('translator')->get(@$contact->sub_heading); ?>
                    </p>
                    <div class="mt-5">
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="<?php echo e(asset($themeTrue . '/images/icon/email2.png')); ?>" alt="..." />
                            </div>
                            <div class="text">
                                <h5><?php echo app('translator')->get('email'); ?></h5>
                                <span><?php echo app('translator')->get(@$contact->email); ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="<?php echo e(asset($themeTrue . '/images/icon/phone2.png')); ?>" alt="..." />
                            </div>
                            <div class="text">
                                <h5><?php echo app('translator')->get('Phone'); ?></h5>
                                <span><?php echo app('translator')->get(@$contact->phone); ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline mb-4">
                            <div class="me-3">
                                <img src="<?php echo e(asset($themeTrue . '/images/icon/address.png')); ?>" alt="..." />
                            </div>
                            <div class="text">
                                <h5><?php echo app('translator')->get('address'); ?></h5>
                                <span><?php echo app('translator')->get(@$contact->address); ?></span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="contact-box">
                        <form action="<?php echo e(route('contact.send')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('name'); ?>
                                </label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control"
                                    id="exampleFormControlInput1" placeholder="<?php echo app('translator')->get('John doe'); ?>" />
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Your Email'); ?>
                                </label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control"
                                    id="exampleFormControlInput1" placeholder="<?php echo app('translator')->get('name@example.com'); ?>" />
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput1" class="form-label"><?php echo app('translator')->get('Subject'); ?>
                                </label>
                                <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" class="form-control"
                                    id="exampleFormControlInput1" placeholder="<?php echo app('translator')->get('Subject'); ?>" />
                                <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label"><?php echo app('translator')->get('Your message'); ?></label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="message"
                                    placeholder="<?php echo app('translator')->get('Opinion'); ?>"></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <button class="game-btn" type="submit">
                                <?php echo e(trans('Submit now')); ?>

                                <img src="<?php echo e(asset($themeTrue . '/images/icon/arrow-white.png')); ?>" alt="..." />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/contact.blade.php ENDPATH**/ ?>