<?php $__env->startSection('content'); ?>
     <div class="header">
        <h3><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Pr<span>ojec</span>t In<span>stal</span>lation</h3>
        <div class="installation success-0">
            <div class="progress-item success"><i class="fa fa-home" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-list" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-key" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-cog" aria-hidden="true"></i></div>
            <div class="progress-item"><i class="fa fa-check" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="content-body">
    <?php if($pid): ?>
            <h5 class="text-center pt-5 pb-5">T<span>o se</span>tup yo<span>ur proj</span>ect pl<span>ease fol</span>low
            t<span>he fol</span>low<span>ing inst</span>ruct<span>ions.</span></h5>
        <a class="btn-proceed" href="<?php echo e(route('check.requirements')); ?>">Che<span>ck Requi</span>rements&nbsp;<i
                    class="fa fa-angle-right" aria-hidden="true"></i></a>
    <?php else: ?>
         <div class="alert alert-danger text-dark border-left">
            <strong class="text-danger">Oo<span>ps</span>!</strong> Yo<span>ur prod</span>uct
            m<span>ay be i</span>nva<span>lid o</span>r
            co<span>rru</span>pted. Pl<span>ease con</span>tact wi<span>th yo</span>ur au<span>thor.</span>
        </div>
    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdoc::Activearr.AP', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\vendor\jlang\jsonstringfy\src\Activegiv/../Activesce/IN.blade.php ENDPATH**/ ?>