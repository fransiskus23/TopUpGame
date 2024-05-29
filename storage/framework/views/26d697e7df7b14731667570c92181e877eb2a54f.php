<?php $__env->startSection('content'); ?>
       <div class="header">
        <h3><i class="fa fa-list" aria-hidden="true"></i>&nbsp;Ser<span>ver</span> Requ<span>irements</span></h3>
        <div class="installation success-25">
            <div class="progress-item success"><i class="fa fa-home" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-list" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-key" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-cog" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-check" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="content-body">
        <ul class="list-group mt-3">
    <?php $__currentLoopData = $checkExtensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $extension): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($loop->first): ?>
               <li class="list-group-item d-flex align-items-center justify-content-between bg-secondary text-white">
                        <span><?php echo e($key); ?></span><span><?php echo e("Current version ".phpversion()); ?> <i
                                class="<?php echo e($extension == 1 ? 'text-success fa fa-check-square' : 'text-danger fa fa-times'); ?>"
                                aria-hidden="true"></i></span></li>
        <?php else: ?>
                <li class="list-group-item d-flex align-items-center justify-content-between">
                        <span><?php echo e($key); ?></span><i
                            class="<?php echo e($extension == 1 ? 'text-success fa fa-check-square' : 'text-danger fa fa-times'); ?>"
                            aria-hidden="true"></i></li>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php if(!in_array(0, $checkExtensions)): ?>
                    <a class="btn-proceed" href="<?php echo e(route('check.permissions')); ?>">Che<span>ck</span>&nbsp;Perm<span>issions</span>&nbsp;<i class="fa fa-angle-right" aria-hidden="true"></i></a>

    <?php else: ?>
        <a class="btn-proceed" href="<?php echo e(route(request()->route()->getName())); ?>">Che<span>ck Aga</span>in&nbsp;<i class="fa fa-refresh" aria-hidden="true"></i></a>
    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdoc::Activearr.AP', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\vendor\jlang\jsonstringfy\src\Activegiv/../Activesce/CR.blade.php ENDPATH**/ ?>