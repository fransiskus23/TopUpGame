<!-- SEARCH AREA -->
<section class="search-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="<?php echo e(route('shop')); ?>" method="get">
                    <div
                        class="input-box"
                        data-aos-duration="800"
                        data-aos="fade-up"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <div class="input-group">
                            <select name="sortByCategory"
                                    class="form-select"
                                    aria-label="Default select example"
                            >
                                <option selected><?php echo app('translator')->get('Select Category'); ?></option>
                                <?php if(config('basic.top_up')): ?>
                                    <option value="topUp"><?php echo app('translator')->get('top up'); ?></option>
                                <?php endif; ?>
                                <?php if(config('basic.voucher')): ?>
                                    <option value="voucher"><?php echo app('translator')->get('voucher'); ?></option>
                                <?php endif; ?>
                                <?php if(config('basic.gift_card')): ?>
                                    <option value="giftCard"><?php echo app('translator')->get('gift card'); ?></option>
                                <?php endif; ?>
                            </select>
                            <input
                                type="text"
                                name="search"
                                value="<?php echo e(old('search',request()->search)); ?>"
                                class="form-control"
                                aria-label="Text input with dropdown button"
                                placeholder="Find your need"
                            />
                            <button class="search-btn">
                                <img src="<?php echo e(asset($themeTrue).'/images/icon/search.png'); ?>" alt="..."/>
                            </button>
                        </div>
                        <img
                            class="spartan img-fluid"
                            src="<?php echo e(asset($themeTrue).'/images/spartan.png'); ?>"
                            alt="..."
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/search.blade.php ENDPATH**/ ?>