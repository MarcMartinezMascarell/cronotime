@extends('layouts.app')

@section('content')

@include('layouts.headers.cards')


<div class="container-fluid mt--8">

    <div class="card p-4">
        <div id="agenda"></div>
    </div>

</div>

<script>
    var eventsJSON = @json($events);
</script>


<!-- <div id="filter-select">
    <select name="filter" id="">
        <option value="all">{{__("Todos")}}</option>
        <option value="normal">{{__("Normales")}}</option>
        <option value="ausencia">{{__("Ausencias")}}</option>
        <option value="vacaciones">{{__("Vacaciones")}}</option>
    </select>
</div> -->

<div class="modal fade" id="new-event" tabindex="1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-top modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-default">{{__("Añadir evento al calendario")}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>

            <div class="mt-2">
                <div class="row w-100">
                    <div class="col-12">
                        <div class="btn-toolbar btn-group-toggle d-flex justify-content-center" data-toggle="buttons">
                            <label class="btn btn-default active event-type-btn">
                                <input type="radio" name="event-type" value="evento-normal" autocomplete="off" checked> {{__("Normal")}}
                            </label>
                            <label class="btn btn-default event-type-btn">
                                <input type="radio" name="event-type" value="evento-ausencia" autocomplete="off"> {{__("Ausencia")}}
                            </label>
                            <label class="btn btn-default event-type-btn">
                                <input class="btn btn-default" type="radio" name="event-type"  value="evento-vacaciones" autocomplete="off"> {{__("Vacaciones")}}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-2">

            <div class="modal-body">
                {{-- FORMULARIO EVENTO NORMAL --}}
                <form id="evento-normal" class=" event-type-form" action="{{ route('calendar.addEvent')}}"  method="POST">
                    @csrf
                    @method('POST')
                        <input type="hidden" name="empresa_id" value="{{Auth::user()->company->id}}">
                        <input type="hidden" name="owner_id" value="{{Auth::id()}}">
                        <input type="hidden" name="event_type" value="1">
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" name="title" id="title" placeholder="{{__("Título*")}}" requried>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2">
                                <textarea id="description" class="form-control" name="description" rows="2" placeholder="{{__("Descripción")}}"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-8">
                                        <label class="mb-1 small" for="start_date">{{__("Fecha inicio")}}</label>
                                        <input id="start_date" class="form-control start_date" type="date" name="start_date" aria-label="Small">
                                    </div>
                                    <div class="col-xs-6 col-sm-4">
                                        <label class="mb-1 small" for="start_time">{{__("Hora inicio")}}</label>
                                        <input id="start_time" class="form-control start_time" type="time" name="start_time" aria-label="Small" step="any">
                                    </div>
                                    <div class="col-xs-6 col-sm-8">
                                        <label class="mb-1 small" for="end_date">{{__("Fecha final")}}</label>
                                        <input id="end_date" class="form-control end_date" type="date" name="end_date" aria-label="Small">
                                    </div>
                                    <div class="col-xs-6 col-sm-4">
                                        <label class="mb-1 small" for="end_time">{{__("Hora final")}}</label>
                                        <input id="end_time" class="form-control end_time" type="time" name="end_time" aria-label="Small" step="any">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-2">
                                        <i class="bi bi-circle-fill"></i>
                                        <input id="color" class="form-control" type="color" name="color" value="#219ebc">
                                    </div>
                                    <div class="col-xs-12 col-sm-10">
                                        <select id="my-select" class="form-control" name="">
                                            <option selected disabled>{{__("Visibilidad")}}</option>
                                            <option value="">{{__("Público")}}</option>
                                            <option value="">{{__("Privado")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">{{__("Añadir")}}</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
                    </div>
                </form>

                {{-- FORMULARIO AUSENCIAS --}}
                <form id="evento-ausencia" class=" event-type-form" action="{{ route('calendar.addEvent')}}"  method="POST" style="display:none">
                    @csrf
                    @method('POST')
                        <input type="hidden" name="empresa_id" value="{{Auth::user()->company->id}}">
                        <input type="hidden" name="owner_id" value="{{Auth::id()}}">
                        <input type="hidden" name="event_type" value="2">
                        <input type="hidden" name="color" value="#e76f51">
                        <input class="start_date" type="hidden" name="start_date">
                        <input class="start_date" type="hidden" name="end_date">
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" name="title" id="title" placeholder="{{__('Motivo*')}}" required>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-1 small" for="start_date">{{__("Fecha")}}</label>
                                        <input id="start_date" class="form-control start_date" type="date" name="start_date" aria-label="Small">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                    <label class="mb-1 small" for="start_date">{{__("Hora inicio")}}</label>
                                        <input id="start" class="form-control" type="time" name="start_time" aria-label="Small">
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                    <label class="mb-1 small" for="start_date">{{__("Hora final")}}</label>
                                        <input id="end" class="form-control" type="time" name="end_time" aria-label="Small">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2">
                                <textarea id="description" class="form-control" name="description" rows="2" placeholder="{{__("Comentarios")}}"></textarea>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">{{__("Añadir")}}</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
                    </div>
                </form>

        </div>
    </div>
</div>

@stop
