define(['jquery', 'fosjsrouting'], function($) {
    $(document).ready(function() {
        $(document).on('click', '#add-calendar', function() {
            var line_id = $(this).children("input").val();
            $.ajax({
                url : Routing.generate('tisseo_datawarehouse_grid_calendar_create')+"/"+line_id,
                type: 'GET',
                data : null,
                success: function(data) {
                    $('#new-grid-calendar').html(data);
                    $("#add-calendar").after("    <button id='cancel-calendar' class='btn btn-xs btn-danger'><span style='font-size:8px;' class='glyphicon glyphicon-minus'></span></button>");
                }
            });
        });
        $(document).on('click', '#cancel-calendar', function() {
            $(document).find("#new-grid-calendar").empty();
            $(this).remove();
        });
        $(document).on('click', '#apply-grid-calendar-form', function() {
            var line_id = $("#add-calendar").children("input").val();
            var $inputs = $("#new-grid-calendar :input");
            var data = {};
            $inputs.each(function() {
                if(!$(this).is(':checkbox') || $(this).is(':checked')) {
                    data[this.name] = $(this).val();
                }
            }); 
            $.ajax({
                url : Routing.generate('tisseo_datawarehouse_grid_calendar_create')+"/"+line_id,
                type: 'POST',
                data : data,
                success: function(data) {
                    if (data.content)
                    {
                        if ($(data.content).is("form"))
                            $("#new-grid-calendar").html(data.content);
                        else 
                            $('.modal-content').html(data.content);
                    }
                }
            });
        });
        $(document).on('click', '#submit-calendars', function() {
            var line_id = $("#add-calendar").children("input").val();
            var $inputs = $("#grid-calendar :input.grid-calendar-row, #grid-calendar :input.grid-mask-type-row");
            var data = {};
            var temp_grid;
            $inputs.each(function() {
                if ($(this).hasClass('grid-calendar-row')) {
                    data[$(this).val()] = [];
                    temp_grid = $(this).val();
                }
                else
                    data[temp_grid].push($(this).val());
            });
            console.log(data);
            $.ajax({
                url : Routing.generate('tisseo_datawarehouse_calendar_edit')+"/"+line_id,
                type: 'POST',
                data : JSON.stringify(data),
                success: function(data) {
                    if (data.content)
                    {
                        $('.modal-content').html(data.content);
                    }
                }
            });
        });
    });
});
