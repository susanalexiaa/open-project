$(function () {
    $("#rateYo").rateYo({
        fullStar: true,
        onSet: function (rating, rateYoInstance) {
            $(this).next('input#rating').val(rating);
        }
    });

    $('#photo').change(function() {
        var filenames = [];
        for (var i = 0; i < this.files.length; i++) {
            filenames.push(this.files[i].name);
        }

        filenames.forEach(function(item){
            var html = $('<p></p>').addClass('mt-1').text(item);
            html.appendTo('.photos_name')
        })
    });

    var isFormValidated = false;
    $('.add-feedback').submit(function(e){

        if( !isFormValidated ){
            e.preventDefault();
            var rating = $("input[name=rating]").val();

            if(rating == ''){
                $('.service_quality').append('<p class="text-sm text-red-600 mt-2">Оцените качество сервиса</p>');
            }else{
                isFormValidated = true;
                $(this).submit();
            }
        }

    })
});