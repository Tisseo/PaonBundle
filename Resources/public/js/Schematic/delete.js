require(['jquery'], function($) {
    $(document).ready(function () {
        $(document).on('click', '.delete-schematic', function() {
            $(this).attr('disabled', 'disabled');
            $.ajax({
                'type': 'POST',
                'url': $(this).data('url'),
                'success': function(data) {
                    $('.target').html(data.content);
                }
            });
            return false;
        });

    });
});
