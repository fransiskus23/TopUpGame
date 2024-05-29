<?php if(isset($contentDetails['statistics'])): ?>
    <!-- COUNTER SECTION -->
    <section class="counter-section">
        <div class="container">
            <div class="row">
                <?php $__currentLoopData = $contentDetails['statistics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-3">
                        <div
                            class="counter-box"
                            data-aos-duration="800"
                            data-aos="fade-up"
                            data-aos-anchor-placement="center-bottom"
                        >
                            <h2><span class="counter"><?php echo app('translator')->get(@$item->description->number); ?></span><sup>+</sup></h2>
                            <h5><?php echo app('translator')->get(@$item->description->title); ?></h5>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/counter.blade.php ENDPATH**/ ?>