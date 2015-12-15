define(['jquery', 'translations/messages'], function($) {

    $(document).on('click', '#schematic-export-button', function() {
        if (!validateExportForm()){
            return false;
        }
        var startDate = $('#export-form #start-date').val().replace(/\//g,'-');
        var url = $(this).attr('data-url') + '/' + startDate;
        window.location.replace(url);
    });

    function validateExportForm() {
        $('#export-form').parent().find('div.alert').remove();
        var check = true;

        var error = "<div class='alert alert-danger alert-dismissable danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

        if ($('#export-form #start-date').val().length < 1) {
            error += Translator.trans('tisseo.paon.schematic.validation.date_not_filled')+"<br>";
            check = false;
        }

        error += "</div>";

        if (!check) {
            $('#export-form').before(error);
        }

        return check;
    }

});
