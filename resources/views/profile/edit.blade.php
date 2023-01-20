@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
    ])

    <div class="container-fluid mt--2">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="https://www.business2community.com/wp-content/uploads/2017/08/blank-profile-picture-973460_640.png" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-sm btn-info mr-4">{{ __('Connect') }}</a>
                            <a href="#" class="btn btn-sm btn-default float-right">{{ __('Message') }}</a>
                        </div>
                    </div> -->
                    <div class="card-body mt-5 pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                    <div>
                                        <span class="text-muted">{{__("Dado de alto el")}}: {{ auth()->user()->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3>
                                {{ auth()->user()->name }}<span class="font-weight-light"></span>
                            </h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{auth()->user()->job}}
                            </div>
                            @if(auth()->user()->company)
                            <div>
                                <i class="ni education_hat mr-2"></i>{{auth()->user()->company->nombre}}
                            </div>
                            @endif
                            <form method="POST" action="{{route('locale.change')}}" class="mt-2">
                                @csrf
                                @method('post')
                                <select name="locale" >
                                    <option @if(auth()->user()->locale == 'es') echo selected @endif value="es">Castellano</option>
                                    <option @if(auth()->user()->locale == 'ca') echo selected @endif value="ca">Català</option>
                                </select>
                                <input type="submit" class="btn btn-success" value="{{__("Cambiar")}}">

                            </form>
                            @hasanyrole('administrador|superAdmin')
                            <hr class="my-4" />
                            <form action="{{route('company.toggleProjects')}}" method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" name="company_id" value="{{auth()->user()->company->id}}">
                                <input type="submit" class="btn btn-{{ (auth()->user()->company->has_projects == 1) ? 'danger' :'success' }}" value="{{ (auth()->user()->company->has_projects == 1) ? 'Desactivar proyectos' :'Activar proyectos' }}">
                            </form>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
                @if(Auth::user()->horas)
                <div class="card card-profile shadow mt-4">
                    <div class="card-header">
                        <div class="row align-items-center justify-content-center">
                            <h3 class="text-center">{{__("Horario")}}:</h3>
                        </div>
                        <div class="row align-items-center justify-content-center">
                            <p class="text-center m-0">{{__("Lunes")}}: {{Auth::user()->horas->Monday}}h</p>
                            <p class="text-center m-0">{{__("Martes")}}: {{Auth::user()->horas->Tuesday}}h</p>
                            <p class="text-center m-0">{{__("Miércoles")}}: {{Auth::user()->horas->Wednesday}}h</p>
                            <p class="text-center m-0">{{__("Jueves")}}: {{Auth::user()->horas->Thursday}}h</p>
                            <p class="text-center m-0">{{__("Viernes")}}: {{Auth::user()->horas->Friday}}h</p>
                            <p class="text-center m-0">{{__("Sábado")}}: {{Auth::user()->horas->Saturday}}h</p>
                            <p class="text-center m-0">{{__("Domingo")}}: {{Auth::user()->horas->Sunday}}h</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('Editar Perfil') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Información del usuario') }}</h6>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Nombre') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('surname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-surname">{{ __('Apellidos') }}</label>
                                    <input type="text" name="surname" id="input-surname" class="form-control form-control-alternative{{ $errors->has('surname') ? ' is-invalid' : '' }}" placeholder="{{ __('Surname') }}" value="{{ old('name', auth()->user()->surname) }}" required autofocus>

                                    @if ($errors->has('surname'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Guardar') }}</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4" />
                        <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Constraseña') }}</h6>

                            @if (session('password_status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('password_status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-current-password">{{ __('Contraseña actual') }}</label>
                                    <input type="password" name="old_password" id="input-current-password" class="form-control form-control-alternative{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Contraseña actual') }}" value="" required>

                                    @if ($errors->has('old_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Nueva contraseña') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Nueva contraseña') }}" value="" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Confirma nueva contraseña') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirma nueva contraseña') }}" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Cambiar contraseña') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
