define(['jquery', 'jquery_ui_droppable', 'fosjsrouting', 'translations/messages'], function($) {
    $(document).ready(function() {
        /*
         * delete calendar button removes gridCalendar's <tbody> from left table in
         * calendars.html.twig, then when submitting data, the gridCalendar will
         * be deleted from the current LineVersion in database.
         */
        $(document).on('click', '.delete-calendar', function() {
            // handle no-data message placed after right table
            if ($(this).closest('tbody').next().hasClass("grid-mask-type") && $(".grid-mask-type-table").next().hasClass("no-data")) {
                $(".grid-mask-type-table").next().fadeOut(300, function() {
                    $(this).remove();
                });
            }

            $(this).closest('tbody').fadeOut(300, function() {
                while ($(this).next().hasClass("grid-mask-type"))
                {
                    var gridCalendar = $(this).next();
                    if (gridCalendar.find("tr:first td").length == 3) {
                        gridCalendar.find("tr").find("td:first").attr('colspan',1);
                        gridCalendar.find("tr").find("td:last").remove();
                    }
                    $(".grid-mask-type-table").prepend(gridCalendar);
                }
                $(this).closest('tbody').remove();
            });
        });

        /*
         * submit button for gridCalendar form validation. If the validation
         * fails, the called controller will resend the same form with errors
         * on concerned inputs.
         * Otherwise, a new gridCalendar <tbody> is sent
         */
        $(document).on('click', '#apply-grid-calendar-form', function() {
            var line_id = $("#line-version-id").val();
            var $inputs = $("#new-grid-calendar :input");
            var data = {};
            $inputs.each(function() {
                if(!$(this).is(':checkbox') || $(this).is(':checked')) {
                    data[this.name] = $(this).val();
                }
            });
            var getForm = false;
            $.ajax({
                url : Routing.generate('tisseo_tid_calendar_create')+"/"+line_id,
                type: 'POST',
                data : data,
                success: function(data) {
                    if (data.content)
                    {
                        if ($(data.content).is("form"))
                            $("#new-grid-calendar").html(data.content);
                        else if ($(data.content).is("tbody"))
                        {
                            getForm = true;
                            $(data.content).insertBefore("#grid-calendar tbody:last-child");
                            /* This function is called in Calendar/edit.html
                             * view, so it could be great to declare it and
                             * call it from only one source */
                            $(".new-droppable").droppable({
                                over: function(event, ui) {
                                    $(this).find("tr:first").addClass("success");
                                },
                                out: function(event, ui) {
                                    $(this).find("tr:first").removeClass("success");
                                },
                                drop: function(event, ui) {
                                    if ($(ui.draggable).find("tr:first td").length == 2) {
                                        $(ui.draggable).find("tr").append("<td></td>");
                                    }
                                    $(this).find("tr:first").removeClass("success");
                                    $(this).after(ui.draggable);
                                    if ($(".grid-mask-type-table").children().length == 2)
                                    {
                                        $(".grid-mask-type-table").after("<span style='display:none;' class='no-data'>"+Translator.trans('calendar.labels.no_data', {}, 'messages')+"</span>");
                                        $(".grid-mask-type-table").next().fadeIn();
                                    }
                                }
                            }).removeClass("new-droppable");
                        }
                    }
                }
            }).promise().then(function() {
                if (getForm === true)
                {
                    $.ajax({
                        url : Routing.generate('tisseo_tid_calendar_form')+"/"+line_id,
                        type: 'GET',
                        success: function(data) {
                            $("#new-grid-calendar").html(data);
                        }
                    });
                }
            });
        });

        /*
         * submit the whole view and send JSON data in order to delete/create
         * GridCalendar and link/unlink them to GridMaskType.
         */
        $(document).on('click', '#submit-calendars', function() {
            var line_id = $("#line-version-id").val();
            var $inputs = $("#grid-calendar :input.grid-calendar-row, #grid-calendar :input.grid-mask-type-row");
            var data = {};
            var gridCalendar;
            var lastIndex = null;
            $inputs.each(function(index) {
                if ($(this).hasClass('grid-calendar-row')) {
                    gridCalendar = $(this).val();
                    if (gridCalendar === "new") {
                        lastIndex = gridCalendar+"_"+index;
                        data[lastIndex] = { 'gmt': [], 'name': null, 'days': {} };
                        data[lastIndex].name = $(this).parent().find('td.name').html(); 
                        $(this).parent().find('td.day').each(function(idx) {
                            data[lastIndex].days[idx] = ($(this).children().hasClass('green-day') ? 1 : 0);
                        });
                    }
                    else {
                        lastIndex = gridCalendar;
                        data[lastIndex] = { 'gmt': [] };
                    }
                }
                else
                    data[lastIndex].gmt.push($(this).val());
            });
            $.ajax({
                url : Routing.generate('tisseo_tid_calendar_edit')+"/"+line_id,
                type: 'POST',
                data : JSON.stringify(data),
                success: function(data) {
                    if (data.status === true && data.location)
                        $("#base-modal").modal('hide');
                    if (data.content)
                        $('.modal-content').html(data.content);
                }
            });
        });
    });
});
