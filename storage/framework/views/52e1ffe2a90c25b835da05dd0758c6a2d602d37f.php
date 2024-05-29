<?php if(0 < count($sellPost)): ?>
<!-- Sell Post SECTION -->
<section id="topup" class="topup-section">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text-link">
                    <?php if(isset($templates['sell-post'][0]) && $postSell = $templates['sell-post'][0]): ?>
                        <h2><?php echo app('translator')->get($postSell->description->title); ?></h2>
                    <?php endif; ?>
                    <a href="<?php echo e(route('buy')); ?>">
                        <?php echo app('translator')->get('Shop more'); ?>
                        <i class="fas  fa-angle-double-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row" data-aos-duration="800" data-aos="zoom-in" data-aos-anchor-placement="center-bottom">
            <?php $__empty_1 = true; $__currentLoopData = $sellPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(0 < count($item->activePost)): ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-4">
                    <div class="img-box">
                        <a href="<?php echo e(route('buy').'?sortByCategory='.$item->id); ?>">
                                <img src="<?php echo e(getFile(config('location.sellPostCategory.path').@$item->image)); ?>" alt="..."
                                      class="img-fluid"/>

                        </a>
                        <p class="pt-2 mb-0">
                            <?php echo e(\Illuminate\Support\Str::limit(optional($item->details)->name,20)); ?>

                        </p>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/sell-post.blade.php ENDPATH**/ ?>