<?php if(isset($contentDetails['whats-clients-say'])): ?>
    <!-- TESTIMONIAL SECTION -->
    <section class="testimonial-section">
        <div class="container">
            <?php if(isset($templates['whats-clients-say'][0]) && ($whatClientsSay = $templates['whats-clients-say'][0])): ?>
                <div class="row">
                    <div class="col">
                        <div class="header-text">
                            <h2><?php echo app('translator')->get($whatClientsSay->description->title); ?></h2>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col">
                    <div class="owl-carousel testimonials owl-loaded owl-drag">
                        <?php $__currentLoopData = $contentDetails['whats-clients-say']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="review-box" data-aos-duration="800" data-aos="fade-up"
                                 data-aos-anchor-placement="center-bottom">
                                <div class="img-box">
                                    <img
                                        src="<?php echo e(getFile(config('location.content.path') . @$item->content->contentMedia->description->image)); ?>"
                                        alt="..." class="img-fluid"/>
                                </div>
                                <div class="text-box">
                                    <p class="description">
                                        <?php echo app('translator')->get(@$item->description->description); ?>
                                    </p>
                                    <h5 class="name"><?php echo app('translator')->get(@$item->description->name); ?></h5>
                                    <span class="title"><?php echo app('translator')->get(@$item->description->designation); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/testimonial.blade.php ENDPATH**/ ?>