define(['jquery', 'fosjsrouting'], function($) {
    $(document).ready(function() {
        $(document).on('change', '.select-offer', function() {
            $.ajax({
                url : Routing.generate('tisseo_paon_line_version_show')+"/"+$(this).children(":selected").val(),
                type: 'GET',
                data : { history: true },
                success: function(data) {
                    $('.modal-content').html(data);
                    $('#base-modal').modal("show");
                }
            });
            $(this).prop('selectedIndex', 0);
        });
    });
});
