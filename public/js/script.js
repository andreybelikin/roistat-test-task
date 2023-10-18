$(document).ready(function () {
    $(".form").submit(function (event) {

        var formData = {
            name: $(".name").val(),
            email: $(".email").val(),
            phone: $(".phone").val(),
            sum: $(".sum").val(),
        };

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            encode: true,
            success: function (data) {
                $(".response-box").addClass('response-box-success');
                $(".response-box").removeClass('response-box-failure');
                $(".response-box").fadeIn(700);
                $(".response-box p").text(data.message);
            },
            error: function (data) {
                $(".response-box").addClass('response-box-failure');
                $(".response-box").removeClass('response-box-success');
                $(".response-box").fadeIn(700);
                $(".response-box p").text(data.responseJSON.message);
            }
        });

        event.preventDefault();
    });
});
