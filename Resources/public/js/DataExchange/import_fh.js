define(['jquery', 'bootbox', 'fosjsrouting', 'translations/messages'], function($, bootbox) {
    var composant = {};

    function countSelectedCk() {
        return $("input.ckLine:checked").length;
    }

    $(document).on('click', '#launch-job-import-fh', function(event) {
        event.preventDefault();
        var self = this;

        if (countSelectedCk() == 0) {
            bootbox.alert(Translator.trans('tisseo.paon.data_exchange.alert.import_fh'));
            return false;
        }

        /* Get selection */
        var lines= $("input.ckLine:checked");
        var params = [];
        lines.each(function(index, line) {
            params.push(line.value);
        });

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('tisseo_paon_data_exchange_launch', {'jobName': 'Import FH'}),
            'data': JSON.stringify(params),
            'ProcessData': false,
            'contentType': false,
            'success': function (data) {
                if (data.status === true) {
                    window.location = self.href;
                }
            },
            'error': function (xhr) {
                console.log(xhr.responseText);
            }
        });
        return true;
    });

    return (composant);

});
