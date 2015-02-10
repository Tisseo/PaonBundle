define(['jquery', 'fosjsrouting'], function($) {
    $(document).ready(function() {
        $(document).on('change', '#datawarehouse_line_version_line', function() {
            var data = {
                line_id: $(this).val()
            };
            $.ajax({
                url : Routing.generate('tisseo_datawarehouse_select_line_version_by_line'),
                type: 'POST',
                data : data,
                success: function(data, textStatus) {
                    $('.modal-content').html(data.content);
                }
            });
        });
    });
});
