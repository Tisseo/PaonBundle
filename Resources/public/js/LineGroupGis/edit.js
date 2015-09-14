define(['jquery', 'fosjsrouting'], function($) {

    var init = function() {
        console.log('begin');
        $(document).on('line-select', 'change', function() {
            /*$.ajax({
                url: $('
                
            });*/
        });
        console.log('end');
    };

    return init;
});
