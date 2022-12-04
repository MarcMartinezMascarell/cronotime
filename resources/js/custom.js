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



    //Get worker id of selected worker on fecha_informe click
    $('#workerId').on('change', function(e) {
        e.preventDefault();
        $('.fecha_informe').each(function() {
            let link = $(this).attr('href');
            let newLink = link.replace(/userId=\d+/, 'userId=' + $('#workerId').val());
            $(this).attr('href', newLink);
        });

    });


    $('.totalTime').each(function() {
        let totalTime = $(this).text().split(':');
        let minutes = totalTime[1];
        let hours = totalTime[0];
        setInterval(() => {
            minutes++;
            if(minutes < 10) {
                minutes = '0' + minutes;
            }
            if (minutes == 60) {
                minutes = 0;
                hours++;
                if(hours < 10) {
                    hours = '0' + hours;
                }
            }
            $(this).text(hours + ':' + minutes);
        }, 60000);
    })



})

