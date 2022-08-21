$(document).ready(function() {
    $('#delete-entrada, #delete-salida').on('show.bs.modal', function (event) {
        console.log('click');
        let button = $(event.relatedTarget);
        let idFichaje = button.data('idfichaje');
        let modal = $(this);
        modal.find('input[name="idFichaje"]').val(idFichaje);
    });


})

