/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
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
  });
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
    }, 60000);
  });
});
/******/ })()
;