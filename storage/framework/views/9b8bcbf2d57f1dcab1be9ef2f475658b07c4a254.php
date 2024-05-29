<?php if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0]): ?>
    <!-- ABOUT SECTION -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <div
                        class="img-box pe-md-5"
                        data-aos-duration="800"
                        data-aos="fade-right"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <img
                            src="<?php echo e(getFile(config('location.content.path').@$aboutUs->templateMedia()->image)); ?>"
                            alt="..."
                            class="img-fluid"
                        />
                    </div>
                </div>

                <div class="col-md-6">
                    <div
                        class="text-box"
                        data-aos-duration="800"
                        data-aos="fade-left"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <?php if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0]): ?>
                            <h2><?php echo app('translator')->get($aboutUs->description->title); ?></h2>
                            <p>
                                <?php echo app('translator')->get($aboutUs->description->short_description); ?>
                            </p>
                        <?php endif; ?>

                        <?php if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0]): ?>

                            <a href="<?php echo e((@$aboutUs->templateMedia()->button_link)); ?>">
                                <button class="game-btn">
                                    <?php echo app('translator')->get($aboutUs->description->button_name); ?>
                                    <img src="<?php echo e(asset($themeTrue.'/images/icon/arrow-white.png')); ?>" alt="..."/>
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\gamersArena\project\resources\views/themes/gameshop/sections/about.blade.php ENDPATH**/ ?>