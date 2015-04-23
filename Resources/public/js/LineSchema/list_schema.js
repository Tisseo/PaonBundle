require(['jquery','fosjsrouting'], function($) {
    $(document).ready(function () {
        $(document).on('click', '.choose-schematic', function() {
            var target = $(document).find('.' + $(this).data('target') + ' > .listSchematic');
            var params = {
                'lineId': $('#tid_line_version_line').find('option:selected').val()
            };

            var url = Routing.generate('tisseo_tid_schema_choice_list', params);

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
                    target.hide();
                },
                'complete': function() {
                    initTable();
                }
            });
            return false;
        });

        var initTable = function() {
            $(document).find('.table-schematic tbody tr').not('.empty').on('click mouseover mouseout', function(event) {
                var elem = $(this);
                switch(event.type) {
                    case 'mouseover':
                        elem.toggleClass('info');
                        break;
                    case 'mouseout':
                        elem.toggleClass('info');
                        break;
                    case 'click':
                        elem.siblings().removeClass('danger');
                        elem.toggleClass('danger');
                        console.log(elem.data('schematic_id'));
                        $(document).find('#tid_line_version_schematic').val(elem.data('schematic_id'));
                        break;
                    default:
                        return false;
                }

            });
        };



    });
});