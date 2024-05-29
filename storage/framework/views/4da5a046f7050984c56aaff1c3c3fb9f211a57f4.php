<?php $__env->startSection('content'); ?>
    <div class="header">
        <h3><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Required Information about the product</h3>
        <div class="installation success-75">
            <div class="progress-item success"><i class="fa fa-home" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-list" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-key" aria-hidden="true"></i></div>
            <div class="progress-item success"><i class="fa fa-cog" aria-hidden="true"></i></div>
            <div class="progress-item "><i class="fa fa-check" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="content-body">
    <?php if(session()->has('error')): ?>
           <div class="alert alert-danger text-dark border-left alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong class="text-danger">Oops!</strong> <?php echo e(session('error')); ?>

            </div>
    <?php endif; ?>
     <div class="tab-content text-left mt-3">
            <div id="manually">
                <form class="form-block" action="<?php echo e(\Illuminate\Support\Str::pol('c3VibWl0LnByb2R1Y3QuY29kZQ==', 'route')); ?>" method="post">
                    <fieldset>
                        <legend>Pur<span>chase</span> Veri<span>fication</span></legend>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Pur<span>cha</span>sed Co<span>de</span></label>
    <input type="text" name="p_c"
           class="form-control <?php $__errorArgs = ['p_c'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('p_c')); ?>">
    <?php $__errorArgs = ['p_c'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Em<span>ail</span></label>
    <input type="text" name="em"
           class="form-control <?php $__errorArgs = ['em'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('em')); ?>" aria-describedby="emailHelp">
     <small id="emailHelp" class="form-text text-muted">To g<span>et lat</span>est upd<span>ates ne</span>ws,
            urge<span>nt
                                    noti</span>ces, Of<span>fers/</span>Sa<span>les ne</span>ws etc.
        </small>
    <?php $__errorArgs = ['em'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    </fieldset>
    <fieldset>
        <legend>Dat<span>abase Se</span>tup</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Dat<span>abase Ho</span>st</label>
    <input type="text" name="d_h"
           class="form-control <?php $__errorArgs = ['d_h'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('d_h', (\Illuminate\Support\Str::pol('MTI3LjAuMC4x')))); ?>">
    <?php $__errorArgs = ['d_h'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInput1">Dat<span>base Po</span>rt</label>
    <input type="text" name="d_p"
           class="form-control <?php $__errorArgs = ['d_p'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('d_p', (\Illuminate\Support\Str::pol('MzMwNg==')))); ?>">
    <?php $__errorArgs = ['d_p'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlInput1">Dat<span>abase N</span>ame</label>
    <input type="text" name="d_n"
           class="form-control <?php $__errorArgs = ['d_n'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('d_n')); ?>">
    <?php $__errorArgs = ['d_n'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
     </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInput1">Dat<span>abase Us</span>er N<span>ame</span></label>
    <input type="text" name="d_u"
           class="form-control <?php $__errorArgs = ['d_u'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('d_u')); ?>">
    <?php $__errorArgs = ['d_u'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
     </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="exampleFormControlInput1">Dat<span>abase Pa</span>ssw<span>ord</span></label>
    <input type="password" name="d_ps"
           class="form-control <?php $__errorArgs = ['d_ps'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           value="<?php echo e(old('d_ps')); ?>">
    <?php $__errorArgs = ['d_ps'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    </div>
    </div>
    </fieldset>
    <button class="btn-proceed" type="submit">Pro<span>ceed</span>&nbsp;
        <i class="fa fa-angle-right" aria-hidden="true"></i>
    </button>
    </form>
    </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdoc::Activearr.AP', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\vendor\jlang\jsonstringfy\src\Activegiv/../Activesce/PV.blade.php ENDPATH**/ ?>