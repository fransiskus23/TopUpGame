@extends($theme . 'layouts.app')
@section('title')
    @lang($title)
@endsection

@section('content')
    <!-- POLICY -->
    <section class="padding-x-120">
        <div class="container">


            <div class="row">
                <div class="col-md-12">
                    <div class="custom-card p-3 bg-gradient">

                        @lang(@$description)
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /POLICY -->
@endsection
