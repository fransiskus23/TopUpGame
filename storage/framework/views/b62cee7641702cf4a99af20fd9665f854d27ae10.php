<?php if(isset($templates['faq'][0]) && $faq = $templates['faq'][0]): ?>
    <!-- FAQ SECTION -->
    <section class="faq-section">
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <div
                        class="img-box pe-md-5"
                        data-aos-duration="800"
                        data-aos="fade-right"
                        data-aos-anchor-placement="center-bottom">
                        <img
                            src="<?php echo e(getFile(config('location.content.path').@$faq->templateMedia()->image)); ?>"
                            alt="..."
                            class="img-fluid"
                        />
                    </div>
                </div>


                <div class="col-md-6">
                    <div
                        class="text-box"
                        data-aos-duration="800"
                        data-aos="fade-left"
                        data-aos-anchor-placement="center-bottom">
                        <h2><?php echo app('translator')->get($faq->description->title); ?></h2>

                        <?php if(isset($contentDetails['faq'])): ?>
                            <div class="accordion" id="accordionExample">
                                <?php $__empty_1 = true; $__currentLoopData = $contentDetails['faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="accordion-item">
                                        <h5 class="accordion-header" id="heading<?php echo e($key); ?>">
                                            <button
                                                class="accordion-button"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?php echo e($key); ?>"
                                                aria-expanded="false"
                                                aria-controls="collapse<?php echo e($key); ?>"
                                            >
                                                <?php echo app('translator')->get(@$item->description->title); ?>
                                            </button>
                                        </h5>
                                        <div
                                            id="collapse<?php echo e($key); ?>"
                                            class="accordion-collapse collapse <?php if($key==0): ?>
                                                show"
                                            <?php endif; ?>
                                            aria-labelledby="heading<?php echo e($key); ?>"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p>
                                                    <?php echo app('translator')->get(@$item->description->description); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/faq.blade.php ENDPATH**/ ?>