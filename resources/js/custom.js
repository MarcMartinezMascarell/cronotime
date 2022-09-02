$(document).ready(function() {
    $('#delete-entrada, #delete-salida').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let idFichaje = button.data('idfichaje');
        let modal = $(this);
        modal.find('input[name="idFichaje"]').val(idFichaje);
    });

    $('#set-salida').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let idFichaje = button.data('idfichaje');
        let modal = $(this);
        modal.find('input[name="idFichaje"]').val(idFichaje);
    });

    //Tooltip activation
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });


})

