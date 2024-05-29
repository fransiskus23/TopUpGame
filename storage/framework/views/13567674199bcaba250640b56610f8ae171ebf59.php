    <style>
        .page-header {
            background: url(<?php echo e(getFile(config('location.logo.path').'banner.jpg')); ?>);
        }
    </style>

<?php if(!request()->routeIs('home')): ?>
    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2><?php echo $__env->yieldContent('title'); ?></h2>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\TopUp\resources\views/themes/gameshop/partials/banner.blade.php ENDPATH**/ ?>