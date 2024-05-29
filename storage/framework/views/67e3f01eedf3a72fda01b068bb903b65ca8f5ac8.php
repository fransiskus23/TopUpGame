
<?php if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0]): ?>
 <section class="choose-section">
    <div class="container">


        <div class="row align-items-center">
            <div class="col-md-6">
                <div
                    class="text-box"
                    data-aos-duration="800"
                    data-aos="fade-right"
                    data-aos-anchor-placement="center-bottom">
                    <h2><?php echo app('translator')->get(optional($whyChooseUs->description)->title); ?></h2>

                <?php if(isset($contentDetails['why-choose-us'])): ?>
                    <?php $__currentLoopData = $contentDetails['why-choose-us']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="choose-box">
                        <img src="<?php echo e(getFile(config('location.content.path').@$item->content->contentMedia->description->image)); ?>" alt="..."/>
                        <div class="text">
                            <h5> <?php echo app('translator')->get(optional($item->description)->title); ?></h5>
                            <span><?php echo app('translator')->get(optional($item->description)->information); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                    <a href="<?php echo e(optional($whyChooseUs->templateMedia())->button_link); ?>"><button class="game-btn">
                            <?php echo app('translator')->get(optional($whyChooseUs->description)->button_name); ?>
                            <img src="<?php echo e(asset($themeTrue).'/images/icon/arrow-white.png'); ?>" alt="..."/>
                        </button></a>
                </div>
            </div>

            <?php if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0]): ?>
            <div class="col-md-6">
                <div
                    class="img-box ps-md-5"
                    data-aos-duration="800"
                    data-aos="fade-left"
                    data-aos-anchor-placement="center-bottom"
                >
                    <img
                        src="<?php echo e(getFile(config('location.content.path').@optional($whyChooseUs->templateMedia())->image)); ?>"
                        alt="..."
                        class="img-fluid"
                    />
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/why-choose-us.blade.php ENDPATH**/ ?>