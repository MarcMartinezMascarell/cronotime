<div class="modal fade" id="fichaje-olvidado" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">{{__("¿Has olvidado un fichaje?")}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="fichajeOlvidadoForm" action="{{ route('fichaje.olvidado')}}" method="POST">
                @csrf
                @method('POST')
            <div class="modal-body">
                <div class="input-daterange datepicker row align-items-start">
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">{{__("Entrada")}}</p>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                @if($ultimoFichaje)
                                <input name="entrada" class="form-control" type="datetime-local" value="{{ $ultimoFichaje->started_at->format('Y-m-d H:i:00') }}">
                                @else
                                <input name="entrada" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">{{__("Salida")}}</p>
                            <div class="collapse" id="exit">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input name="salida" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <label class="custom-toggle mt-2 d-flex" href="#exit" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                                    <input name="salida_yes" type="checkbox" value="true">
                                    <span class="custom-toggle-slider" data-label-off="Aún no he salido" data-label-on="Yes"></span>

                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex small align-items-center mt-2">
                        <i class="text-muted fas fa-clock mx-2"></i>
                        <p class="text-muted m-0">{{__("Quedará registrado como fichaje olvidado")}}</p>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                    <button type="submit" class="btn btn-default">{{__("Crear fichaje olvidado")}}</button>

                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
            </div>
        </form>

        </div>
    </div>
</div>

<div class="modal fade" id="set-salida" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">{{__("Añadir salida al fichaje olvidado")}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="setSalidaForm" action="{{ route('fichaje.salida')}}" method="POST">
                @csrf
                @method('POST')
            <div class="modal-body">
                <div class="input-daterange datepicker row align-items-start">
                    <div class="col">
                        <div class="form-group m-0">
                            <p class="h5 text-center">{{__("Salida")}}</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input type="hidden" name="idFichaje" value="">
                                    <input name="salida" class="form-control" type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:00') }}">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                    <button type="submit" class="btn btn-default">{{__("Crear salida")}}</button>

                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
            </div>
        </form>

        </div>
    </div>
</div>

<div class="modal fade" id="delete-entrada" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">{{__("Atención")}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="bold">{{__("Si el fichaje dispone de entrada y salida, su salida correspondiente será borrada también")}}</p>
            </div>

            <div class="modal-footer">
                <form id="delete-fichaje-form" action="{{ route('fichaje.delete')}}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="idFichaje" value="">
                    <input type="hidden" name="type" value="entrada">
                    <button type="submit" class="btn btn-danger text-white">{{__("Borrar")}}</button>
                </form>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="delete-salida" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">{{__("Atención")}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="bold">{{__("El fichaje de salida será borrado")}}</p>
            </div>

            <div class="modal-footer">
                <form id="delete-fichaje-form" action="{{ route('fichaje.delete')}}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="idFichaje" value="">
                    <input type="hidden" name="type" value="salida">
                    <button type="submit" class="btn btn-danger text-white">{{__("Borrar")}}</button>
                </form>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="update-logo" tabindex="1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-top modal-" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-default">{{__("Sube tu nuevo logo!")}}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="">
            <div class="modal-body">
                    <input type="file">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-default">{{__("Subir")}}</button>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">{{__("Cancelar")}}</button>
            </div>

            </form>

        </div>
    </div>
</div>
