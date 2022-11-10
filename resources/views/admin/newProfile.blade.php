@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hola') . ' '. auth()->user()->name,
    ])

    <div class="container-fluid mt--5">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{__("Añadir nuevo trabajador")}}</h3>
                        </div>
                        {{-- @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                            <span class="alert-icon"><i class="fas fa-thumbs-down"></i></span>
                            <span class="alert-text"><strong>Cuidado!</strong> {{Session::get('error')}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif --}}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.store') }}" autocomplete="off">
                            @csrf
                            @method('post')

                            <h6 class="heading-small text-muted mb-4">INFO DEL TRABAJADOR</h6>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            <div class="d-flex flex-wrap">
                                <div class="col-xl-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Nombre') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Nombre" value="" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-6 form-group{{ $errors->has('surname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-surname">{{ __('Apellidos') }}</label>
                                    <input type="text" name="surname" id="input-surname" class="form-control form-control-alternative{{ $errors->has('surname') ? ' is-invalid' : '' }}" placeholder="Apellidos" value="" required>

                                    @if ($errors->has('surname'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-6 form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" value="" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-6 form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Contraseña') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" value="" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-6 form-group{{ $errors->has('cargo') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-cargo">{{ __('Cargo') }}</label>
                                    <input type="text" name="cargo" id="input-cargo" class="form-control form-control-alternative{{ $errors->has('cargo') ? ' is-invalid' : '' }}" placeholder="Programador Web" value="" required autofocus>

                                    @if ($errors->has('cargo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cargo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if(auth()->user()->company)
                                    <input type="hidden" name="company" value="{{ auth()->user()->company->id }}">
                                @else
                                    <input type="hidden" name="company" value="0">
                                @endif


                            </div>

                            <hr class="my-4" />

                            <h6 class="heading-small text-muted mb-4">{{__("Horario")}}</h6>

                            <div class="d-flex flex-wrap">
                                <div class="col-xl-1 form-group{{ $errors->has('lunes') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-lunes">{{ __('Lunes') }}</label>
                                    <input type="number" step="0.5" name="lunes" id="input-lunes" class="form-control form-control-alternative{{ $errors->has('lunes') ? ' is-invalid' : '' }}" value="8" required>

                                    @if ($errors->has('lunes'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('lunes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('martes') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-martes">{{ __('Martes') }}</label>
                                    <input type="number" step="0.5" name="martes" id="input-martes" class="form-control form-control-alternative{{ $errors->has('martes') ? ' is-invalid' : '' }}" value="8" required>

                                    @if ($errors->has('martes'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('martes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('miercoles') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-miercoles">{{ __('Miércoles') }}</label>
                                    <input type="number" step="0.5" name="miercoles" id="input-miercoles" class="form-control form-control-alternative{{ $errors->has('miercoles') ? ' is-invalid' : '' }}" value="8" required>

                                    @if ($errors->has('miercoles'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('miercoles') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('jueves') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-jueves">{{ __('Jueves') }}</label>
                                    <input type="number" step="0.5" name="jueves" id="input-jueves" class="form-control form-control-alternative{{ $errors->has('jueves') ? ' is-invalid' : '' }}" value="8" required>

                                    @if ($errors->has('jueves'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('jueves') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('viernes') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-viernes">{{ __('Viernes') }}</label>
                                    <input type="number" step="0.5" name="viernes" id="input-viernes" class="form-control form-control-alternative{{ $errors->has('viernes') ? ' is-invalid' : '' }}" value="8" required>

                                    @if ($errors->has('viernes'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('viernes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('sabado') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-sabado">{{ __('Sábado') }}</label>
                                    <input type="number" step="0.5" name="sabado" id="input-sabado" class="form-control form-control-alternative{{ $errors->has('sabado') ? ' is-invalid' : '' }}" value="0" required>

                                    @if ($errors->has('sabado'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sabado') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-1 form-group{{ $errors->has('domingo') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-domingo">{{ __('Domingo') }}</label>
                                    <input type="number" step="0.5" name="domingo" id="input-domingo" class="form-control form-control-alternative{{ $errors->has('domingo') ? ' is-invalid' : '' }}" value="0" required>

                                    @if ($errors->has('domingo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('domingo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-5 form-group{{ $errors->has('vacaciones') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-vacaciones">{{ __('Días de vacaciones anuales (Laborables)') }}</label>
                                    <input type="number" name="vacaciones" id="input-vacaciones" class="form-control form-control-alternative{{ $errors->has('vacaciones') ? ' is-invalid' : '' }}" value="22" required>

                                    @if ($errors->has('vacaciones'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('vacaciones') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-center w-100">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Guardar') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
