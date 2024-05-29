
<?php $__env->startSection('title', '404'); ?>


<?php $__env->startSection('content'); ?>
    <section  class="login-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12 text-center">
                    <span class="display-1 d-block "><?php echo e(trans('Opps!')); ?></span>
                    <div class="mb-4 lead mt-3 "><?php echo e(trans('The page you are looking for was not found.')); ?>

                    </div>
                    <a class="btn  btn-base" href="<?php echo e(url('/')); ?>"><?php echo app('translator')->get('Back To Home'); ?></a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/errors/404.blade.php ENDPATH**/ ?>