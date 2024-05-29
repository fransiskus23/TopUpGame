    <style>
        .page-header {
            background: url({{getFile(config('location.logo.path').'banner.jpg')}});
        }
    </style>

@if(!request()->routeIs('home'))
    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2>@yield('title')</h2>
                </div>
            </div>
        </div>
    </section>
@endif
