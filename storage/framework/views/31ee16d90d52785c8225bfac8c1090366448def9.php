<?php $__env->startSection('title', trans('Shop Now')); ?>

<?php $__env->startSection('content'); ?>
    <section class="shop-section">
        <div class="container">

            <div class="row">
                <div class="col-lg-4 pe-lg-5">
                    <div class="filter-area">
                        <!-- INPUT FIELD -->
                        <div class="filter-box">
                            <h4><?php echo app('translator')->get('search'); ?></h4>
                            <form action="" method="get">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="search"
                                        value="<?php echo e(old('search',request()->search)); ?>"
                                        class="form-control"
                                        placeholder="<?php echo app('translator')->get('Search items'); ?>"
                                        aria-describedby="basic-addon"
                                    />
                                    <span class="input-group-text" id="basic-addon">
                              <button type="submit">
                                 <img src="<?php echo e(asset($themeTrue).'/images/icon/search.png'); ?>" alt="..."/>
                              </button>
                           </span>
                                </div>
                            </form>

                        </div>

                        <!-- SEARCH BY CATEGORIES -->
                        <div class="filter-box mt-3">
                            <h4><?php echo app('translator')->get('Categories'); ?></h4>
                            <form action="" method="get" id="sortByCategory">
                                <div class="check-box">
                                    <?php if(config('basic.top_up')): ?>
                                        <div class="form-check mb-3">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="topUp"
                                                <?php if(isset(request()->sortByCategory) && in_array('topUp',explode(',',request()->sortByCategory))): ?> checked
                                                <?php endif; ?>
                                                id="check1"
                                            />
                                            <label class="form-check-label" for="check1">
                                                <?php echo app('translator')->get('Top Up'); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(config('basic.voucher')): ?>
                                        <div class="form-check mb-3">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="voucher"
                                                <?php if(isset(request()->sortByCategory) && in_array('voucher',explode(',',request()->sortByCategory))): ?> checked
                                                <?php endif; ?>
                                                id="check2"
                                            />
                                            <label class="form-check-label" for="check2">
                                                <?php echo app('translator')->get('Vouchers'); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(config('basic.gift_card')): ?>
                                        <div class="form-check">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="giftCard"
                                                <?php if(isset(request()->sortByCategory) && in_array('giftCard',explode(',',request()->sortByCategory))): ?> checked
                                                <?php endif; ?>
                                                id="check3"
                                            />
                                            <label class="form-check-label" for="check3">
                                                <?php echo app('translator')->get('Gift card'); ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-lg-8 mt-5 mt-lg-0">
                    <div class="item-area">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <span><?php echo app('translator')->get('SHOWING ALL'); ?> <?php echo e($items->total()); ?> <?php echo app('translator')->get('RESULTS'); ?></span>
                            </div>
                            <div class="col-md-6 d-flex mt-4 mt-md-0 justify-content-md-end align-items-center">
                                <span class="pe-3"><?php echo app('translator')->get('SORT BY'); ?></span>
                                <form action="" method="get" id="sortBy">
                                    <select name="sortBy"
                                            class="form-control form-select"
                                            aria-label="Default select example">

                                        <option value="all" <?php if(request()->sortBy == 'all'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('All Type'); ?>
                                        </option>
                                        <option value="popular" <?php if(request()->sortBy == 'popular'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Popular'); ?>
                                        </option>

                                        <option value="latest" <?php if(request()->sortBy == 'latest'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Latest'); ?>
                                        </option>
                                        <option value="featured" <?php if(request()->sortBy == 'featured'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Featured'); ?>
                                        </option>
                                        <option value="discount" <?php if(request()->sortBy == 'discount'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Discount'); ?>
                                        </option>
                                        <option value="date" <?php if(request()->sortBy == 'date'): ?> selected <?php endif; ?>>
                                            <?php echo app('translator')->get('Date'); ?>
                                        </option>

                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-md-3 col-sm-4 col-4">
                                    <a href="<?php echo e($item->detailsRoute); ?>" class="text-white">
                                        <div class="img-box">
                                            <img
                                                src="<?php echo e($item->imgPath); ?>"
                                                alt="<?php echo e(optional($item->details)->name); ?>"
                                                title="<?php echo e(optional($item->details)->name); ?>"
                                                class="img-fluid"
                                            />
                                            <div class="tags">
                                                <?php if($item->discount_amount): ?>
                                                    <?php if($item->discount_type =='0'): ?>
                                                        <span><?php echo e(config('basic.currency_symbol')); ?><?php echo e($item->discount_amount); ?></span>
                                                    <?php else: ?>
                                                        <span><?php echo e($item->discount_amount); ?>%</span>
                                                    <?php endif; ?>

                                                <?php endif; ?>
                                            </div>
                                            <p class="pt-2 mb-0">
                                                <?php echo e(\Illuminate\Support\Str::limit(optional($item->details)->name,17)); ?>

                                            </p>
                                        </div>

                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col">
                    <?php echo e($items->appends($_GET)->links($theme.'partials.pagination')); ?>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>

    <script>
         'use strict';
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
                        var new_url = "<?php echo e(url()->current()); ?>?sortBy=all&sortByCategory=" + checkedVal;
                        window.history.pushState("data", "", new_url);

                        setTimeout(function () {
                            window.location.reload()
                        }, 1000)
                    }
                }

            })


        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($theme . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/shop.blade.php ENDPATH**/ ?>