<?php $__env->startSection('title', trans('Buy Now')); ?>

<?php $__env->startSection('content'); ?>
    <!-- SHOP SECTION -->
    <section class="shop-section sell-post">
        <div class="container">
            <div class="row">
                <div class="col-md-4  pe-lg-5">
                    <div class="filter-area">
                        <!-- INPUT FIELD -->
                        <div class="filter-box">
                            <h4><?php echo app('translator')->get('search'); ?></h4>
                            <form action="" method="get" id="searchFormSubmit">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Search items"
                                        value="<?php echo e(old('search',request()->search)); ?>"
                                        aria-label="Subscribe Newsletter"
                                        aria-describedby="basic-addon"
                                    />
                                    <span class="input-group-text" id="basic-addon">
                                      <button>
                                         <img src="<?php echo e(asset($themeTrue).'/images/icon/search.png'); ?>" alt="..."/>
                                      </button>
                                   </span>
                                </div>


                                <input type="hidden" class="js-input-from" name="minPrice" value="0" readonly/>
                                <input type="hidden" class="js-input-to" value="0" name="maxPrice" readonly/>

                            </form>
                        </div>
                        <!-- PRICE RANGE -->
                        <div class="filter-box mt-3">
                            <h4><?php echo app('translator')->get('Filter by price'); ?></h4>
                            <div class="input-box">
                                <input
                                    type="text"
                                    class="js-range-slider"
                                    name="my_range"
                                    value=""/>
                                <label for="customRange1" class="form-label mt-3">
                                    <?php echo e(config('basic.currency_symbol')); ?><?php echo e($min); ?>

                                    - <?php echo e(config('basic.currency_symbol')); ?><?php echo e($max); ?></label>

                            </div>
                        </div>

                        <!-- SEARCH BY CATEGORIES -->
                        <div class="filter-box mt-3">
                            <h4><?php echo app('translator')->get('Categories'); ?></h4>
                            <form action="" method="get" id="sortByCategory">
                                <div class="check-box">
                                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input sortByCategory"
                                                name="sortByCategory"
                                                type="checkbox"
                                                value="<?php echo e($category->id); ?>"
                                                <?php if(isset(request()->sortByCategory) && in_array($category->id,explode(',',request()->sortByCategory))): ?> checked
                                                <?php endif; ?>
                                                id="check<?php echo e($category->id); ?>"
                                            />
                                            <label class="form-check-label cursor-pointer" for="check<?php echo e($category->id); ?>">
                                                <?php echo e(optional($category->details)->name); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mt-5 mt-lg-0">
                    <div class="item-area">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <span><?php echo app('translator')->get('SHOWING ALL'); ?> <?php echo e($sellPost->total()); ?> <?php echo app('translator')->get('RESULTS'); ?></span>
                            </div>
                            <div
                                class="col-md-6 d-flex mt-4 mt-md-0 justify-content-md-end align-items-center">
                                <span class="pe-3"><?php echo app('translator')->get('SORT BY'); ?></span>
                                <form action="" method="get" id="sortBy">
                                    <select name="sortBy"
                                            class="form-control form-select"
                                            aria-label="Default select example">
                                        <option selected value="latest"
                                                <?php if(request()->sortBy =='latest'): ?> selected <?php endif; ?>><?php echo app('translator')->get('Latest'); ?></option>
                                        <option value="low_to_high"
                                                <?php if(request()->sortBy == 'low_to_high'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Price low to high'); ?>
                                        </option>
                                        <option value="high_to_low"
                                                <?php if(request()->sortBy == 'high_to_low'): ?> selected <?php endif; ?>><?php echo app('translator')->get('Price high to low'); ?></option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            <?php $__empty_1 = true; $__currentLoopData = $sellPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-md-12 col-sm-6">
                                    <div class="game-box d-md-flex">
                                        <?php if($item->image): ?>
                                            <div class="img-box image-slider owl-carousel">
                                                <?php for($i = 0; $i<count($item->image); $i++): ?>
                                                    <img
                                                        src="<?php echo e(getFile(config('location.sellingPost.path') . @$item->image[$i])); ?>"
                                                        class="img-fluid"
                                                        alt="..."
                                                    />
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?php echo e(route('sellPost.details',[@slug($item->title),$item->id])); ?>">
                                                <h5 class="name"><?php echo e(\Illuminate\Support\Str::limit($item->title,25)); ?></h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="game-level"
                                                    ><?php echo app('translator')->get('Price'); ?>: <span><?php echo e(getAmount($item->price)); ?> <?php echo e(config('basic.currency')); ?></span></span
                                                    >
                                                    <?php if($item->payment_lock == 1): ?>
                                                        <?php if(Auth::check() && Auth::id()==$item->lock_for): ?>
                                                            <span
                                                                class="badge bg-secondary"><?php echo app('translator')->get('Waiting Payment'); ?></span>
                                                        <?php elseif(Auth::check() &&  Auth::id()==$item->user_id): ?>
                                                            <span
                                                                class="badge bg-warning text-dark"><?php echo app('translator')->get('Payment Processing'); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning"><?php echo app('translator')->get('Going to Sell'); ?></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                            <div class="row g-2 mt-3 more-info">
                                                <?php $__empty_2 = true; $__currentLoopData = $item->post_specification_form; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                    <div class="col-6">
                                                        <span><?php echo e($v->field_name); ?>: <?php echo e($v->field_value); ?></span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if(Auth::check() && $item->user_id!=Auth::user()->id): ?>
                                            <?php if($item->payment_lock == 0): ?>
                                                <button class="game-btn-sm makeOffer" data-resource="<?php echo e($item->id); ?>"
                                                        data-bs-toggle="modal" data-bs-target="#makeOffer">
                                                    <?php echo app('translator')->get('make offer'); ?>
                                                    <img
                                                        src="<?php echo e(asset($themeTrue).'/images/icon/arrow-white.png'); ?>"
                                                        alt="..."
                                                    />
                                                </button>
                                            <?php endif; ?>
                                        <?php elseif(Auth::check()==false): ?>
                                            <?php if($item->payment_lock == 0): ?>
                                                <button class="game-btn-sm makeOffer" data-resource="<?php echo e($item->id); ?>"
                                                        data-bs-toggle="modal" data-bs-target="#makeOffer">
                                                    <?php echo app('translator')->get('make offer'); ?>
                                                    <img
                                                        src="<?php echo e(asset($themeTrue).'/images/icon/arrow-white.png'); ?>"
                                                        alt="..."
                                                    />
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <?php echo e($sellPost->appends($_GET)->links($theme.'partials.pagination')); ?>

                </div>
            </div>
        </div>
    </section>
    <!-- Modal for Make Offer -->
    <div class="modal fade" id="makeOffer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel"><?php echo app('translator')->get('Make Offer'); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="<?php echo e(route('user.sellPostOffer')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body-custom">
                        <div class="customize-modal">
                            <input type="hidden" class="sell_post_id" name="sell_post_id" value="">
                            <div class="form-group">
                                <label for="amount" class="font-weight-bold"> <?php echo app('translator')->get('Amount'); ?> </label>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="amount" class="form-control earn" required></input>
                                        <button class="btn btn-success-custom copy-btn"
                                                type="button"><?php echo e(config('basic.currency')); ?></button>
                                    </div>
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold"> <?php echo app('translator')->get('Description'); ?> </label>
                                    <textarea name="description" rows="4" class="form-control custom earn" value=""
                                              required></textarea>
                                </div>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="submit" class="btn btn-success-custom"><?php echo app('translator')->get('Submit'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>

    <script>
         'use strict';
        $(document).ready(function () {
            $('.makeOffer').on('click', function () {
                $('.sell_post_id').val($(this).data('resource'));
            })
        });

        $(document).ready(function () {

            $('select[name=sortBy]').on('change', function () {
                $("#sortBy").submit();
            })

            $('.form-check-input').on('click', function () {
                var checkedVal = $(this).val();

                if (window.location.href.indexOf("sortByCategory") > -1) {

                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);


                    var sortByCategory = urlParams.get('sortByCategory');
                    var categoryParams = sortByCategory.split(",");

                    var url = new URL('<?php echo e(url()->full()); ?>');
                    var search_params = url.searchParams;
                    var newArr = [];
                    for (let i = 0; i < categoryParams.length; i++) {
                        newArr.push(categoryParams[i])
                    }

                    if (this.checked == false) {
                        for (let i = 0; i < newArr.length; i++) {
                            if (newArr[i] === checkedVal) {
                                newArr.splice(i, 1);
                            }
                        }
                    } else {
                        newArr.push(checkedVal)
                    }
                    var text = newArr.toString();
                    if (text.charAt(0) == ',') {
                        text = text.slice(1);
                    }


                    urlParams.set('sortByCategory', text);
                    var new_url = "<?php echo e(url()->current()); ?>?" + urlParams;
                    let new_set_url = new_url.replaceAll('%2C', ",");
                    window.history.pushState("data", "", new_set_url);

                    setTimeout(function () {
                        window.location.reload()
                    }, 1000)


                } else {
                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);
                    if (urlParams.has('sortByCategory') == false) {
                        var new_url = "<?php echo e(url()->current()); ?>?sortBy=desc&sortByCategory=" + checkedVal;
                        window.history.pushState("data", "", new_url);

                        setTimeout(function () {
                            window.location.reload()
                        }, 1000)
                    }
                }

            })


        });


        var $range = $(".js-range-slider"),
            $inputFrom = $(".js-input-from"),
            $inputTo = $(".js-input-to"),
            instance,
            min = 0,
            max = <?php echo e($max); ?>;

        // RANGE SLIDER
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            from: <?php echo e(request('minPrice') ?? $min); ?>,
            to: <?php echo e(request('maxPrice') ?? $max); ?>,
            onStart: updateInputs,
            onChange: updateInputs,
            onFinish: finishInputs
        });

        function updateInputs(data) {
            $inputFrom.prop("value", data.from);
            $inputTo.prop("value", data.to);
        }

        function finishInputs(data) {
            $inputFrom.prop("value", data.from);
            $inputTo.prop("value", data.to);

            setTimeout(function () {
                $('#searchFormSubmit').submit();
            }, 2000)
        }
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/buy.blade.php ENDPATH**/ ?>