<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0 d-none d-md-block" href="{{ route('home') }}">
            CronoTime
        </a>
        <div class="company-logo d-md-flex justify-content-center align-items-center flex-column">
            @hasanyrole('administrador|superAdmin')
            <div class="d-flex justify-content-center align-items-center"  data-toggle="tooltip" data-placement="bottom" title="{{__("Cambiar logo")}}">
                <a href=""  data-toggle="modal" data-target="#update-logo">
                    <img class="sidebar-logo" alt="Logo" src="{{asset('images/logos/'.auth()->user()->company->logo_url)}}" onerror="this.style.display='none'">
                </a>
            </div>
            @else
                <img class="sidebar-logo" alt="Logo" src="{{asset('images/logos/'.auth()->user()->company->logo_url)}}" onerror="this.style.display='none'">
            @endhasanyrole
            <p class="text-center m-0">{{auth()->user()->company->nombre}}</p>
        </div>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="https://www.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('¡Bienvenido!') }}</h6>

                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Perfil') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse justify-content-between" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a class="navbar-brand pt-0" href="{{ route('home') }}">
                            CronoTime
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            {{-- <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form> --}}
            <div class="company-logo d-flex justify-content-center align-items-center flex-column  d-md-none">
                @hasanyrole('administrador|superAdmin')
                <div class="d-flex justify-content-center align-items-center"  data-toggle="tooltip" data-placement="bottom" title="{{__("Cambiar logo")}}">
                    <a href=""  data-toggle="modal" data-target="#update-logo">
                        <img class="sidebar-logo" alt="Logo" src="{{asset('storage/images/logos/'.auth()->user()->company->logo_url)}}">
                    </a>
                </div>
                @else
                    <img class="sidebar-logo" alt="Logo" src="{{asset('storage/images/logos/'.auth()->user()->company->logo_url)}}">
                @endhasanyrole
                <p class="text-center m-0">{{auth()->user()->company->nombre}}</p>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('fichar.view') }}">
                        <i class="fas fa-clock" style="color: #f4645f;"></i> {{__("Fichar")}}
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('estadisticas.informe') }}" disabled>
                        <i class="fas fa-clipboard" style="color: #f4645f;"></i> {{__("Tus Informes")}}
                    </a>
                </li>
                <li class="nav-item coming-soon">
                    <a class="nav-link" >
                        <i class="fas fa-calendar-alt" style="color: #f4645f;"></i> {{__("Calendario")}}
                    </a>
                </li>
                @hasanyrole('administrador|superAdmin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('estadisticas.dashboard') }}">
                        <i class="fas fa-desktop" style="color: #f4645f;"></i> {{__("Dashboard")}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#administrador" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                        <i class="fa fa-briefcase" style="color: #f4645f;"></i>
                        <span class="nav-link-text" style="color: #f4645f;">{{__("Administración")}}</span>
                    </a>

                    <div class="collapse" id="administrador">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.create') }}">
                                    {{__("Añadir Usuario")}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('workers.show', [auth()->user()->company->id]) }}">
                                    {{__("Lista Usuarios")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @hasrole('superAdmin')
                <li class="nav-item">
                    <a class="nav-link active" href="#superAdmin" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                        <i class="fas fa-user-lock" style="color: #f4645f;"></i>
                        <span class="nav-link-text" style="color: #f4645f;">SUPER ADMIN</span>
                    </a>

                    <div class="collapse" id="superAdmin">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.create') }}">
                                    {{__("Nueva Empresa")}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.index') }}">
                                    {{__("Empresas")}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endhasrole
                @endhasanyrole
            </ul>
            <!-- Divider -->
            <hr class="mt-6">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">{{__('Ayudanos a mejorar!')}}</h6>
            <!-- Navigation -->
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link" href="mailto:marcmartinezmascarell@gmail.com">
                        <i class="ni ni-spaceship"></i> {{__('¿Has encontrado algún error?')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mailto:marcmartinezmascarell@gmail.com">
                        <i class="ni ni-spaceship"></i> {{__('¿Crees que podemos mejorar algo?')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/changelog">
                        <i class="ni ni-spaceship"></i> {{__('Changelog')}}
                    </a>
                </li>
            </ul>
            <p class="text-muted text-center small">
                v1.0.1
            </p>
        </div>
    </div>

</nav>


