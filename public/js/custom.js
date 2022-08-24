/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
$(document).ready(function () {
  $('#delete-entrada, #delete-salida').on('show.bs.modal', function (event) {
    console.log('click');
    var button = $(event.relatedTarget);
    var idFichaje = button.data('idfichaje');
    var modal = $(this);
    modal.find('input[name="idFichaje"]').val(idFichaje);
  }); //Tooltip activation

  $("body").tooltip({
    selector: '[data-toggle=tooltip]'
  });
});
/******/ })()
;