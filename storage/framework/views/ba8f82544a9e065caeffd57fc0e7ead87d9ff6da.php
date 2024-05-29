<!-- FOOTER SECTION -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="footer-box">
                    <a class="navbar-brand" href="#">
                        <img class="img-fluid" src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>"
                            alt="..." />
                    </a>
                    <?php if(isset($contactUs['contact-us'][0]) && ($contact = $contactUs['contact-us'][0])): ?>
                        <p>
                            <?php echo app('translator')->get(strip_tags(@$contact->description->footer_short_details)); ?>
                        </p>
                    <?php endif; ?>
                    <?php if(isset($contactUs['contact-us'][0]) && ($contact = $contactUs['contact-us'][0])): ?>
                        <ul>
                            <li>
                                <i class="fa fa-phone"></i>
                                <span><?php echo app('translator')->get(@$contact->description->phone); ?></span>
                            </li>
                            <li>
                                <i class="fa fa-envelope-open"></i>
                                <span><?php echo app('translator')->get(@$contact->description->email); ?></span>
                            </li>
                            <li>
                                <i class="fa fa-location-arrow"></i>
                                <span><?php echo app('translator')->get(@$contact->description->address); ?></span>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ps-lg-5">
                <div class="footer-box">
                    <h5><?php echo app('translator')->get('Quick Links'); ?></h5>
                    <ul>
                        <li>
                            <a href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('blog')); ?>"><?php echo app('translator')->get('Blog'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ps-lg-5">
                <div class="footer-box">
                    <?php if(isset($contentDetails['support'])): ?>
                        <h5><?php echo app('translator')->get('Useful Links'); ?></h5>
                        <ul>

                            <li>
                                <a href="<?php echo e(route('faq')); ?>"><?php echo app('translator')->get('FAQ'); ?></a>
                            </li>
                            <?php $__currentLoopData = $contentDetails['support']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a
                                        href="<?php echo e(route('getLink', [slug($data->description->title), $data->content_id])); ?>"><?php echo app('translator')->get($data->description->title); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="footer-box">
                    <h5><?php echo app('translator')->get('subscribe newsletter'); ?></h5>
                    <form action="<?php echo e(route('subscribe')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="<?php echo app('translator')->get('Enter Email'); ?>" aria-label="Subscribe Newsletter" aria-describedby="basic-addon" />
                            <span class="input-group-text" id="basic-addon"><button type="submit"><img src="<?php echo e(asset($themeTrue . '/images/icon/paper-plane.png')); ?>" alt="..." /></button>
                            </span>
                        </div>

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
                    </form>

                    <?php if(isset($contentDetails['social'])): ?>
                    <div class="social-links mt-2">
                            <?php $__currentLoopData = $contentDetails['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(@$data->content->contentMedia->description->link); ?>" title="<?php echo app('translator')->get($data->description->name); ?>">
                                    <i class="<?php echo e(@$data->content->contentMedia->description->icon); ?>"></i>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">
                        <?php echo app('translator')->get('Copyright © 2022'); ?> <a href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get($basic->site_title); ?></a>
                        <?php echo app('translator')->get('All Rights Reserved'); ?>
                    </p>
                </div>

                <div class="col-md-6 language">
                    <?php $__empty_1 = true; $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('language',$lang->short_name)); ?>"><?php echo e(trans($lang->name)); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>


<a href="#" class="scroll-top">
    <i class="fas fa-arrow-up"></i>
</a>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/partials/footer.blade.php ENDPATH**/ ?>