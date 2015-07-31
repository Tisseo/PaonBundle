define(['jquery', 'fosjsrouting'], function($) {
    $(document).ready(function() {
        setInterval(function() {
            $('#loading-indicator').show().promise().then(function() {
                $.ajax({
                    url : Routing.generate('tisseo_paon_data_exchange_jobs'),
                    type: 'POST',
                    data : null,
                    success: function(data) {
                        if (data.content)
                        $("tbody").fadeOut().promise().then(function() {
                            $(this).html(data.content).fadeIn();
                        });
                    }
                }).promise().then(function() {
                    $('#loading-indicator').hide();
                });
            });
        }, 10000);
    });
});
