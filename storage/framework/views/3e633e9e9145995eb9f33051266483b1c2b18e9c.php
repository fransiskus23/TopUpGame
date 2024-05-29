<?php if(0 < count($vouchers)): ?>
 <!-- VOUCHER SECTION -->
 <section id="voucher" class="voucher-section">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text-link">
                    <?php if(isset($templates['voucher'][0]) && $voucher = $templates['voucher'][0]): ?>
                      <h2><?php echo app('translator')->get($voucher->description->title); ?></h2>
                    <?php endif; ?>
                    <a href="<?php echo e(route('shop').'?sortByCategory=voucher'); ?>">
                        <?php echo app('translator')->get('Shop more'); ?>
                        <i class="fas  fa-angle-double-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div
            class="row"
            data-aos-duration="800"
            data-aos="zoom-out"
            data-aos-anchor-placement="center-bottom">
            <?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="col-lg-2 col-md-3 col-sm-4 col-4">
                <div class="img-box">
                    <a href="<?php echo e(route('voucher.details',[slug(@$item->details->name),$item->id])); ?>">
                        <img
                            src="<?php echo e(getFile(config('location.voucher.path').@$item->thumb)); ?>"
                            alt="..."
                           title=" <?php echo e($item->details->name); ?>" class="img-fluid"
                        />
                    </a>
                    <div class="tags">
                        <?php if($item->discount_amount): ?>
                            <?php if($item->discount_type =='0'): ?>
                                <span><?php echo e($item->discount_amount); ?></span>
                                <?php else: ?>
                                <span><?php echo e($item->discount_amount); ?>%</span>
                            <?php endif; ?>

                        <?php endif; ?>
                        <?php if($item->featured=='1'): ?>
                        <span><?php echo app('translator')->get('featured'); ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="pt-2 mb-0">
                        <?php echo e(\Illuminate\Support\Str::limit(optional($item->details)->name,15)); ?>

                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/voucher.blade.php ENDPATH**/ ?>