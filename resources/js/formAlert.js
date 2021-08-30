window.toastr = require('toastr');

addEventListener('formAlert', event => {
    window.toastr[event.detail.type](event.detail.message, event.detail.title ?? '', {
        "closeButton": true,
        "progressBar": true,
    })
})
