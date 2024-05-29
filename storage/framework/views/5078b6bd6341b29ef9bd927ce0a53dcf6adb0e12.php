<?php $__env->startSection('content'); ?>
    <div class="header">
        <h3><i class="fa fa-key" aria-hidden="true"></i>&nbsp;Permi<span>ssions</span></h3>
        <div class="installation success-50">
            <div class="progress-item success"><i class="fa fa-home" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-list" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-key" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-cog" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-check" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="content-body">
        <ul class="list-group">
    <?php $__currentLoopData = $chekPermissions['exts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="list-group-item d-flex align-items-center justify-content-between">
                    <span><?php echo e($key); ?></span><i
                        class="<?php echo e(isset($permission['value']) && $permission['value'] == 1 ? 'text-success fa fa-check-square' : 'text-danger fa fa-times'); ?>"
                        aria-hidden="true">&nbsp;<?php echo e(isset($permission['permission']) ? $permission['permission'] : ''); ?> <?php echo e($permission['value'] == 0 ? '(Required '.$permission['required'].')' : ''); ?></i>
                </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php if($chekPermissions['grantPermission'] == 1): ?>
        <a class="btn-proceed" href="<?php echo e(route('product.code')); ?>">Se<span>tup pr</span>oduct <i class="fa fa-angle-right" aria-hidden="true"></i></a>
    <?php else: ?>
        <a class="btn-proceed" href="<?php echo e(route(request()->route()->getName())); ?>">Ch<span>eck Aga</span>in&nbsp;<i class="fa fa-refresh" aria-hidden="true"></i></a>
    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdoc::Activearr.AP', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\vendor\jlang\jsonstringfy\src\Activegiv/../Activesce/CP.blade.php ENDPATH**/ ?>