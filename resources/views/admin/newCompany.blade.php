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
                            <h3 class="mb-0">{{ __('Añadir nueva Empresa al sistema') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('company.store') }}" autocomplete="off">
                            @csrf
                            @method('post')

                            <h6 class="heading-small text-muted mb-4">{{ __('Company info') }}</h6>

                            {{-- @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif --}}


                            <div class="d-flex flex-wrap">
                                <div class="col-xl-6 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Nombre de la Empresa') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Nombre" value="" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-6 form-group{{ $errors->has('limit') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Límite de trabajadores') }}</label>
                                    <input type="number" name="limit" id="input-limit" class="form-control form-control-alternative{{ $errors->has('limit') ? ' is-invalid' : '' }}" placeholder="10" value="" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xl-12 form-group{{ $errors->has('logo') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-logo">{{ __('Subir logo') }}</label>
                                    <input type="file" name="logo" id="input-logo" class="form-control form-control-alternative{{ $errors->has('logo') ? ' is-invalid' : '' }}" value="">

                                    @if ($errors->has('logo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('logo') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>

                            <hr class="my-4" />

                            <h6 class="heading-small text-muted mb-4">{{ __('Administrador info') }}</h6>

                            <div class="d-flex flex-wrap">
                                <div class="col-xl-6 form-group{{ $errors->has('adminName') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-adminName">{{ __('Nombre del administrador') }}</label>
                                    <input type="text" name="adminName" id="input-adminName" class="form-control form-control-alternative{{ $errors->has('adminName') ? ' is-invalid' : '' }}" placeholder="Nombre" value="" required>

                                    @if ($errors->has('adminName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('adminName') }}</strong>
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
                                    <label class="form-control-label" for="input-password">{{ __('Password') }}</label>
                                    <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" value="" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
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
