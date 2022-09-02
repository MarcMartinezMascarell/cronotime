<div class="header pb-5 pt-5 pt-lg-8 d-flex align-items-center">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ $title }}</h1>
                @if (isset($description) && $description)
                    <p class="text-white mt-0 mb-0">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@if(Session::has('error'))
<div class="flotate-alert alert alert-danger alert-dismissible fade show" role="alert">
    <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
    <span class="alert-inner--text"><strong>Cuidado!</strong> {{Session::get('error')}}</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

