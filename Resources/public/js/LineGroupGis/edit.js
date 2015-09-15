define(['jquery', 'fosjsrouting'], function($) {
    var init = function() {
        $('.line-select').on('change', function() {
            var lineId = $(this).val();
            var self = this;
            $.ajax({
                type: 'GET',
                url: Routing.generate('tisseo_paon_line_group_gis_schematics', {'lineId': lineId}),
                'success': function(data) {
                    var schematicSelect = $(self).parent().parent().next().find('select.schematic-select');
                    schematicSelect.find('option').remove();
                    if (data) {
                        $.each(data, function(id, date) {
                            schematicSelect.append($("<option></option>").attr("value", id).text(date));
                        });
                        schematicSelect.find('option:first').attr('selected', 'selected');
                    }
                }
            });
        });
    };

    return init;
});
