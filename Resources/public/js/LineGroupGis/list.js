define(['jquery', 'fosjsrouting'], function ($) {

    var init = function (deprecated) {
        var checkbox = $('.deprecated-checkbox');

        if(!checkbox.length){
            return;
        }

        if(deprecated){
            checkbox.attr('checked', true);
        }else{
            checkbox.removeAttr('checked');
        }

        checkbox.on('click', function (evt){
            evt.preventDefault();
            if(deprecated){
                location.href = Routing.generate('tisseo_paon_line_group_gis_list');
            }else{
                location.href = Routing.generate('tisseo_paon_line_group_gis_list', {'deprecated':'deprecated'});
            }
        });
    };

    return {
        voussure_checkbox : init
    };
});
