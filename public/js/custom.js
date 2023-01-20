/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

$(document).ready(function () {
  $('#delete-entrada, #delete-salida').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idFichaje = button.data('idfichaje');
    var modal = $(this);
    modal.find('input[name="idFichaje"]').val(idFichaje);
  });
  $('#set-salida').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idFichaje = button.data('idfichaje');
    var modal = $(this);
    modal.find('input[name="idFichaje"]').val(idFichaje);
  }); //Tooltip activation

  $("body").tooltip({
    selector: '[data-toggle=tooltip]'
  }); //Get worker id of selected worker on fecha_informe click

  $('#workerId').on('change', function (e) {
    e.preventDefault();
    $('.fecha_informe').each(function () {
      var link = $(this).attr('href');
      var newLink = link.replace(/userId=\d+/, 'userId=' + $('#workerId').val());
      $(this).attr('href', newLink);
    });
  }); //Horas trabajadas update

  if ($('.btn-salir').length) {
    setTimeout(function () {
      $('.totalTime').each(function () {
        var _this = this;

        var totalTime = $(this).text().split(':');
        var minutes = totalTime[1];
        var hours = totalTime[0];
        setInterval(function () {
          minutes++;

          if (minutes < 10) {
            minutes = '0' + minutes;
          }

          if (minutes == 60) {
            minutes = 0;
            hours++;

            if (hours < 10) {
              hours = '0' + hours;
            }
          }

          $(_this).text(hours + ':' + minutes);
        }, 59000);
      });
    }, 1000);
  } //Assignar horas a proyecto


  $('#assignMinutesForm').on('submit', function (e) {
    e.preventDefault();
    var minutesToAssign = parseInt($('#minutesToAssign').text());
    var minutesAssigned = 0;
    $(this).find('input[type="number"]').each(function (e) {
      minutesAssigned += parseInt($(this).val());
    });

    if (minutesAssigned > minutesToAssign) {
      alert('No puedes asignar más horas de las que has trabajado');
      return false;
    }

    $(this).unbind('submit').submit();
  }); //On change input number, update minutes to assign

  var minutesToAssign = parseInt($('#minutesToAssign').text());
  $('input[type="number"]').on('change', function (e) {
    var minutesAssigned = 0;
    var newMinutesToAssign = minutesToAssign;

    if (e.target.value == '') {
      e.target.value = 0;
    }

    $('#assignMinutesForm').find('input[type="number"]').each(function (e) {
      minutesAssigned += parseInt($(this).val());
    });
    newMinutesToAssign -= minutesAssigned;

    if (Number.isInteger(newMinutesToAssign)) {
      $('#minutesToAssign').text(newMinutesToAssign);
    } else {
      $('#minutesToAssign').text(minutesToAssign);
    }
  }); //FECHAS
  //Seleccionar tipo de evento nuevo al añadir

  $('.event-type-btn').on('click', function (e) {
    $('.event-type-form').hide();
    var formId = $(this).find('input').val();
    $('#' + formId).show();
  }); //Fecha final siempre superior a fecha inicial

  $('.start_date').on('change', function () {
    $('.start_date').val($(this).val());
    $('.end_date').attr('min', $(this).val());
  }); //Si la fecha final es la misma que la fecha inicial, la hora final no puede ser menor que la hora inicial

  $('.start_time').on('change', function () {
    $('.start_time').val($(this).val());

    if ($('.start_date').val() == $('.end_date').val()) {
      $('.end_time').attr('min', $('.start_time').val());
    }
  }); //Al cambiar la fecha final, cambian todas las fechas finales

  $('.end_date').on('change', function () {
    $('.end_date').val($(this).val());
  }); //Al cambiar la hora final, cambian todas las horas finales

  $('.end_time').on('change', function () {
    $('.end_time').val($(this).val());
  }); //CALENDARIO

  var calendarEl = document.getElementById('agenda');
  var locale = document.getElementsByTagName("html")[0].getAttribute("lang");
  var tusEventosText = locale == 'es' ? 'Tus eventos' : 'Els teus events';

  if (typeof calendarEl != 'undefined' && calendarEl != null) {
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: locale,
      customButtons: {
        myCustomButton: {
          text: tusEventosText,
          click: function click() {
            alert('clicked the custom button!');
          }
        }
      },
      headerToolbar: {
        left: 'prev,next today myCustomButton',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listMonth'
      },
      selectable: true,
      selectMirror: true,
      selectMinDistance: 10,
      eventDidMount: function eventDidMount(info) {
        if (info.event.extendedProps.event_type == 2) {
          //Si el evento es de tipo ausencia, muestra el nombre del trabajador
          if (info.view.type == 'listMonth') {
            var titleEl = info.el.querySelector('.fc-list-event-title a');
            titleEl.textContent = info.event.title + ' (' + info.event.extendedProps.owner + ')';
            info.el.style.backgroundColor = info.event.backgroundColor;
          } else {
            var titleEl = info.el.querySelector('.fc-event-title');
            titleEl.textContent = info.event.title + ' (' + info.event.extendedProps.owner + ')';
          }
        }
      },
      dateClick: function dateClick(info) {
        var _date$;

        //Click simple en una fecha
        //Mostrar modal para añadir evento
        $('#new-event').modal('show'); //Canviar las fecha de todos los inputs de fecha

        var date = info.dateStr.split('T');
        $('.start_date').val(date[0]);
        $('.start_time').val((_date$ = date[1]) === null || _date$ === void 0 ? void 0 : _date$.split('+')[0]);
      },
      select: function select(info) {
        var _date_start$, _date_end$;

        //Click largo en una fecha
        //Mostrar modal para añadir evento
        $('#new-event').modal('show'); //Canviar las fecha de todos los inputs de fecha

        var date_start = info.startStr.split('T');
        var date_end = info.endStr.split('T');
        $('.start_date').val(date_start[0]);
        $('.start_time').val((_date_start$ = date_start[1]) === null || _date_start$ === void 0 ? void 0 : _date_start$.split('+')[0]);
        $('.end_date').val(date_end[0]);
        $('.end_time').val((_date_end$ = date_end[1]) === null || _date_end$ === void 0 ? void 0 : _date_end$.split('+')[0]);
      },
      eventClick: function eventClick(info) {
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
      events: eventsJSON
    });
    calendar.render();
  } //CHART


  var ctx = document.getElementById('myChart').getContext('2d');
  var maxYValue = Math.max.apply(Math, _toConsumableArray(chartData)) + 2;
  var chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Barras',
        data: chartData,
        backgroundColor: 'rgba(244, 100, 95, 0.2)',
        borderColor: 'rgba(244, 100, 95, 1)',
        borderWidth: 1,
        pointStyle: 'circle'
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            max: Math.ceil(maxYValue)
          },
          scaleLabel: {
            display: true,
            labelString: 'Horas'
          }
        }]
      }
    }
  });
});
/******/ })()
;