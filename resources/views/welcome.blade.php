@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    <div class="header bg-gradient-primary py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mt-7 mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-6">
                        <h1 class="text-white">CRONOTIME</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt--5">
            <div class="card">
                <div class="row">
                    <div class="col col-sm-4 p-4 welcome-icon-box">
                        <div class="card card-stats p-3">
                            <div class="d-flex flex-column justify-content-center">
                                <i class="fas fa-gavel text-center"></i>
                                <div class="text-muted text-center mt-2">{{__("Cumple con la ley de registro horario obligatorio vigente desde Mayo de 2019")}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-4 p-4 welcome-icon-box">
                        <div class="card card-stats p-3">
                            <div class="d-flex flex-column justify-content-center">
                                <i class="fas fa-user-clock text-center"></i>
                                <div class="text-muted text-center mt-2">{{__("Permite a tus trabajadores fichar al entrar y salir del trabajo con un sólo click")}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-4 p-4 welcome-icon-box">
                        <div class="card card-stats p-3">
                            <div class="d-flex flex-column justify-content-center">
                                <i class="fas fa-chart-bar text-center"></i>
                                <div class="text-muted text-center mt-2">{{__("Reportes mensuales automáticos con las estadísticas de tus trabajadores")}}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--10 pb-5"></div>
@endsection
