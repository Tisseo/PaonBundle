require(['jquery', 'fosjsrouting', 'translations/messages'], function($){

    var init = function() {
        console.log('init form_schema');
    };


    $(document).on('click', '.action-button', function() {
        var modalContent = $(document).find('.modal-body');
        var target = modalContent.find('> .schematic-content-form ');

        modalContent.find(' > .schema-content-list').hide();

        var params = {
            'lineId': $(this).data('line_id')
        };

        var url = null;
        switch ( $(this).data('action') ) {
            case 'edit-schema':
                url = Routing.generate('tisseo_tid_schema_edit', params);
                break;
            case 'ask-schema':
                url = Routing.generate('tisseo_tid_schema_ask', params);
                break;
            default:
                console.log('data-action undefined');
                modalContent.show();
                return false;
        }

        $.ajax({
            'type': 'GET',
            'url': url,
            'cache': false,
            'success': function(data){
                target.replaceWith(data);
                target.show();
            },
            'error':function(xhr) {
                console.log(xhr.responseText);
                modalContent.show();
            }
        });
        return false;
    });


    init();

});