$(document).ready(function() {
    $('#reply_box').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/add',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function(data) {
                if (data.error) {
                    $("#form_error").html(data.error);
                    return;
                } else {
                     window.location.reload();
                }               
            }
        });
    }); 
});