<?php if(isset($contentDetails['slider'])): ?>
    <!-- BANNER SECTION -->
    <section id="banner" class="banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="skitter skitter-large with-dots">
                        <ul>
                            <?php $__currentLoopData = $contentDetails['slider']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img
                                            src="<?php echo e(getFile(config('location.content.path').@$item->content->contentMedia->description->image)); ?>"
                                            class="cut"/>
                                    </a>
                                    <div class="label_text">
                                        <div class="text-box">
                                            <h5><?php echo app('translator')->get(@$item->description->sub_title); ?></h5>
                                            <h1>
                                                <?php echo app('translator')->get(@$item->description->title); ?>
                                            </h1>
                                            <a href="<?php echo e(@$item->content->contentMedia->description->button_link); ?>">
                                                <button class="game-btn">
                                                    <?php echo app('translator')->get(@$item->description->button_name); ?>
                                                    <img
                                                        src="<?php echo e(asset($themeTrue.'/images/icon/arrow-white.png')); ?>"
                                                        alt="..."
                                                    /></button
                                                >
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/banner.blade.php ENDPATH**/ ?>