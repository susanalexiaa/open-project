import flatpickr from "flatpickr";

$(function () {
    $('input[name=phone], input.phone').mask('+0 000 000 00 00', {placeholder: "+Х ХХХ ХХХ ХХ ХХ"});

    flatpickr("#datepicker-integration", {
        enableTime: true
    });

    flatpickr("input.datepicker");
    
    flatpickr("input.datepickerWithTime", {
        enableTime: true
    });

});
