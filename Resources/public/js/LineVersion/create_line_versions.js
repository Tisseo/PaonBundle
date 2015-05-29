define(['jquery', 'fosjsrouting'], function($) {
    $(document).ready(function() {
        $(document).on('change', '#paon_line_version_line', function() {
            var data = {
                lineId: $(this).val()
            };
            $.ajax({
                url : Routing.generate('tisseo_paon_line_version_create'),
                type: 'POST',
                data : data,
                success: function(data, textStatus) {
                    $('.modal-content').html(data.content);
                }
            });
        });
    });
});
