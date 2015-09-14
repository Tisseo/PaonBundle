require(['jquery'], function($) {
    $(document).on('click', '.action-button', function(event) {
        event.preventDefault();

        var modalContent = $(document).find('.modal-body');
        var target = modalContent.find('> .target ');

        modalContent.find(' > .schema-content-list').hide();

        $.ajax({
            'type': 'GET',
            'url': $(this).data('url'),
            'cache': false,
            'success': function(data) {
                console.log('success');
                target.html(data);
                target.show();
            },
            'error':function(xhr) {
                console.log(xhr);
                modalContent.show();
            }
        });
    });
});
