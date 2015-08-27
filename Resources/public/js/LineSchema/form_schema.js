require(['jquery', 'fosjsrouting'], function($) {
    $(document).on('click', '.action-button', function() {
        var modalContent = $(document).find('.modal-body');
        var target = modalContent.find('> .target ');

        modalContent.find(' > .schema-content-list').hide();

        var params = {
            'lineId': $(this).data('line-id')
        };

        var url = null;
        switch ( $(this).data('action') ) {
            case 'edit-schema':
                url = Routing.generate('tisseo_paon_schema_edit', params);
                break;
            case 'add-info':
                params.addInfo = 'true';
                url = Routing.generate('tisseo_paon_schema_edit', params);
                break;
            case 'ask-schema':
                url = Routing.generate('tisseo_paon_schema_ask', params);
                break;
            case 'deprecated-schema':
                url = Routing.generate('tisseo_paon_schema_deprecated', params);
                break;
            default:
                modalContent.show();
                return false;
        }

        $.ajax({
            'type': 'GET',
            'url': url,
            'cache': false,
            'success': function(data){
                target.html(data);
                target.show();
            },
            'error':function(xhr) {
                modalContent.show();
            }
        });
        return false;
    });
});
