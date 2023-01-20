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
                            <h3 class="mb-0">{{ __('Asigna tu tiempo a un proyecto!') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="assignMinutesForm" method="post" action="{{ route('project.saveProjectHours') }}" >
                            @csrf
                            @method('post')

                            {{-- @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif --}}

                            <h6 class="heading-small text-muted mb-4">Tienes <span id="minutesToAssign">{{auth()->user()->minutes_to_assign}}</span> minutos para asignar</h6>

                            <hr class="my-4" />

                            <div class="d-flex flex-wrap">
                            <?php $iterator = 0 ?>
                            @foreach($projects as $project)
                                    <div class="col-xl-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">{{ $project->name }}</label>
                                        <input type="hidden" name="{{$iterator}}[]" value="{{$project->id}}">
                                        <input type="number" name="{{$iterator}}[]" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" min="0" value="0" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                            <?php $iterator++; ?>
                            @endforeach
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
