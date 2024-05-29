<?php if(isset($contentDetails['blog'])): ?>
    <!-- BLOG SECTION -->
    <section class="blog-section">
        <div class="container">

            <?php if(!request()->routeIs('blog')): ?>
                <?php if(isset($templates['blog'][0]) && ($ourLatestPost = $templates['blog'][0])): ?>
                    <div class="row">
                        <div class="col">
                            <div class="header-text-link">
                                <h2><?php echo app('translator')->get($ourLatestPost->description->title); ?></h2>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <?php $__currentLoopData = $contentDetails['blog']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-box" data-aos-duration="800" data-aos="fade-right"
                             data-aos-anchor-placement="center-bottom">
                            <div class="img-box">
                                <img
                                    src="<?php echo e(getFile(config('location.content.path') . @$item->content->contentMedia->description->image)); ?>"
                                    alt="..." class="img-fluid"/>
                                <span class="author"><?php echo app('translator')->get('Admin'); ?></span>
                            </div>
                            <div class="text-box">
                                <h5 class="title">
                                    <a href="<?php echo e(route('blogDetails', [slug(@$item->description->title), $item->content_id])); ?>">
                                        <?php echo app('translator')->get(@$item->description->title); ?></a>
                                </h5>
                                <span class="date"> <?php echo app('translator')->get(@$item->description->date_time); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/blog.blade.php ENDPATH**/ ?>