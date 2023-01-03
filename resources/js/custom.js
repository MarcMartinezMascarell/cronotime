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

    //Horas trabajadas update
    if($('.btn-salir').length) {
        setTimeout(() => {
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
                }, 59000);
            })
        }, 1000);
    }


    //FECHAS

    //Seleccionar tipo de evento nuevo al añadir
    $('.event-type-btn').on('click', function(e) {
        $('.event-type-form').hide();
        let formId = $(this).find('input').val();
        $('#' + formId).show();
    });

    //Fecha final siempre superior a fecha inicial
    $('.start_date').on('change', function() {
        $('.start_date').val($(this).val());
        $('.end_date').attr('min', $(this).val());
    });
    //Si la fecha final es la misma que la fecha inicial, la hora final no puede ser menor que la hora inicial
    $('.start_time').on('change', function() {
        $('.start_time').val($(this).val());
        if($('.start_date').val() == $('.end_date').val()) {
            $('.end_time').attr('min', $('.start_time').val());
        }
    });
    //Al cambiar la fecha final, cambian todas las fechas finales
    $('.end_date').on('change', function() {
        $('.end_date').val($(this).val());
    });
    //Al cambiar la hora final, cambian todas las horas finales
    $('.end_time').on('change', function() {
        $('.end_time').val($(this).val());
    });





    //CALENDARIO
    let calendarEl = document.getElementById('agenda');
    let locale = document.getElementsByTagName("html")[0].getAttribute("lang");
    let tusEventosText = (locale == 'es') ? 'Tus eventos' : 'Els teus events';
    if(typeof(calendarEl) != 'undefined' && calendarEl != null) {
        let calendar = new FullCalendar.Calendar(calendarEl, {

            initialView: 'dayGridMonth',
            locale: locale,
            customButtons: {
                myCustomButton: {
                    text: tusEventosText,
                    click: function() {
                    alert('clicked the custom button!');
                    }
                },
                filterButton: {
                    text: 'Filtrar',
                    click: function() {
                        alert('clicked the custom button! 2');
                    },
                    el: {
                        id: 'filter-select-button'
                    }
                }
            },
            headerToolbar: {
              left: 'prev,next today myCustomButton',
              center: 'title filterButton',
              right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            selectable: true,
            selectMirror: true,
            selectMinDistance: 10,

            eventDidMount: function(info) {
                if(info.event.extendedProps.event_type == 2) {
                    //Si el evento es de tipo ausencia, muestra el nombre del trabajador
                    if(info.view.type == 'listMonth') {
                        var titleEl = info.el.querySelector('.fc-list-event-title a');
                        titleEl.textContent = info.event.title + ' (' + info.event.extendedProps.owner + ')';
                        info.el.style.backgroundColor = info.event.backgroundColor;
                    } else {
                        var titleEl = info.el.querySelector('.fc-event-title');
                        titleEl.textContent = info.event.title + ' (' + info.event.extendedProps.owner + ')';
                    }
                }
            },

            dateClick: function(info) {
                //Click simple en una fecha
                //Mostrar modal para añadir evento
                $('#new-event').modal('show');
                //Canviar las fecha de todos los inputs de fecha
                let date = info.dateStr.split('T');
                $('.start_date').val(date[0]);
                $('.start_time').val(date[1]?.split('+')[0]);
            },
            select: function(info) {
                //Click largo en una fecha
                //Mostrar modal para añadir evento
                $('#new-event').modal('show');
                //Canviar las fecha de todos los inputs de fecha
                let date_start = info.startStr.split('T');
                let date_end = info.endStr.split('T');
                $('.start_date').val(date_start[0]);
                $('.start_time').val(date_start[1]?.split('+')[0]);
                $('.end_date').val(date_end[0]);
                $('.end_time').val(date_end[1]?.split('+')[0]);
            },
            eventClick: function(info) {
                console.log(info.event);
            },

          //   events: [
          //     {
          //         title: 'All Day Event',
          //         start: '2022-12-24 12:30:00',

          //     }, {
          //         title: 'Long Event',
          //         start: '2022-12-12 12:30:00',
          //         end: '2022-12-15 12:30:00'
          //     }
          //   ]
            events:eventsJSON,

          });
          calendar.render();
    }

    let filterSelect = document.getElementById('filter-select');
    let customButton = document.getElementById('filter-select-button');
    customButton.appendChild(filterSelect);

})

