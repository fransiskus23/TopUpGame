<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/bootstrap.min.css')); ?>"/>
    <?php echo $__env->yieldPushContent('css-lib'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/animate.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/owl.carousel.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/owl.theme.default.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/skitter.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/aos.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/ion.range-slider.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset($themeTrue . 'css/style.css')); ?>"/>

    <?php echo $__env->yieldPushContent('style'); ?>

</head>

<body>
<!-- prelaoder -->
<div id="preloader" class="preloader">
    <div id="loader" class="wrapper-triangle">
        <div class="pen">
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
            <div class="line-triangle">
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
                <div class="triangle"></div>
            </div>
        </div>
    </div>
</div>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(getFile(config('location.logoIcon.path') . 'logo.png')); ?>" alt="..."/>
        </a>
        <?php if(auth()->guard()->check()): ?>
            <span class="navbar-text">
                    <!-- notification panel -->
                <?php echo $__env->make($theme.'partials.pushNotify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            <!-- user panel -->
                    <div class="user-panel">
                        <button class="user-icon">
                            <img src="<?php echo e(asset($themeTrue . '/images/icon/user2.png')); ?>" alt="..."/>
                        </button>
                        <div class="user-drop-dropdown">
                            <ul>
                                <li>
                                    <a href="<?php echo e(route('user.home')); ?>"><img class="me-2"
                                                                            src="<?php echo e(asset($themeTrue . '/images/icon/block.png')); ?>"
                                                                            alt="..."/>
                                        <?php echo app('translator')->get('Dashboard'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('user.profile')); ?>"><img class="me-2"
                                                                               src="<?php echo e(asset($themeTrue . '/images/icon/editing.png')); ?>"
                                                                               alt="..."/>
                                        <?php echo app('translator')->get('My Profile'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                                            class="me-2" src="<?php echo e(asset($themeTrue . '/images/icon/logout.png')); ?>"
                                            alt="..."/>
                                        <?php echo app('translator')->get('Sign out'); ?></a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                          class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </span>

        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <img src="<?php echo e(asset($themeTrue . '/images/icon/menu.png')); ?>" alt="..."/>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('home')); ?>" href="<?php echo e(route('home')); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('about')); ?>" href="<?php echo e(route('about')); ?>"><?php echo app('translator')->get('About Us'); ?></a>
                </li>
                <?php if(config('basic.top_up')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(request()->routeIs('shop') && request()->sortByCategory == 'topUp'): ?> active <?php endif; ?>"
                           href="<?php echo e(route('shop') . '?sortByCategory=topUp'); ?>"><?php echo app('translator')->get('Top Up'); ?></a>
                    </li>
                <?php endif; ?>

                <?php if(config('basic.voucher')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(request()->routeIs('shop') && request()->sortByCategory == 'voucher'): ?> active <?php endif; ?>"
                           href="<?php echo e(route('shop') . '?sortByCategory=voucher'); ?>"><?php echo app('translator')->get('Voucher'); ?> </a>
                    </li>
                <?php endif; ?>

                <?php if(config('basic.gift_card')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(request()->routeIs('shop') && request()->sortByCategory == 'giftCard'): ?> active <?php endif; ?>"
                           href="<?php echo e(route('shop') . '?sortByCategory=giftCard'); ?>"><?php echo app('translator')->get('Gift Card'); ?></a>
                    </li>
                <?php endif; ?>

                <?php if(config('basic.sell_post')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(menuActive('buy')); ?>" href="<?php echo e(route('buy')); ?>"><?php echo app('translator')->get('Buy ID'); ?></a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(menuActive('contact')); ?>" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                </li>

                <?php if(auth()->guard()->guest()): ?>

                    <li class="nav-item">
                        <a class="nav-link <?php echo e(menuActive('login')); ?>" href="<?php echo e(route('login')); ?>"><?php echo app('translator')->get('Sign In'); ?></a>
                    </li>
                    <?php if(config('basic.registration')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(menuActive('register')); ?>"
                               href="<?php echo e(route('register')); ?>"><?php echo app('translator')->get('Sign Up'); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>


            </ul>
        </div>
    </div>
</nav>

<?php echo $__env->make($theme . 'partials.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make($theme . 'partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<script src="<?php echo e(asset($themeTrue . 'js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery-3.6.0.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('extra-js'); ?>

<script src="<?php echo e(asset($themeTrue . 'js/fontawesome.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.waypoints.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.counterup.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.easing.1.3.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/jquery.skitter.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/aos.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/ion.range-slider.min.js')); ?>"></script>
<script src="<?php echo e(asset($themeTrue . 'js/script.js')); ?>"></script>


<script src="<?php echo e(asset('assets/global/js/notiflix-aio-2.7.0.min.js')); ?>"></script>

<?php echo $__env->make('plugins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make($theme . 'partials.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('assets/global/js/pusher.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/vue.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/axios.min.js')); ?>"></script>

<?php echo $__env->yieldPushContent('script'); ?>


</body>

</html>
<?php /**PATH C:\TopUp\resources\views/themes/gameshop/layouts/app.blade.php ENDPATH**/ ?>