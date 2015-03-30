define(['jquery', 'datatables', 'tid/textfill', 'fosjsrouting', 'translations/messages'], function($) {

    // re-fill line-number text in colored div
    textfill(15, 30, '.line-big');

    $(document).ready(function() {
        // build searchable/filtered tables for each gridCalendar
        datatables = [];
        $(".datatable").each(function(index) {
            var datatable = $(this).DataTable({
                "aaSorting": [],
                "bAutoWidth": false,
                "iDisplayLength": 25,
                "aoColumnDefs" : [ {
                    "bSortable" : false,
                    "aTargets" : [ "no-sort" ]
                } ],
                "language": {
                    "sProcessing":     "Traitement en cours...",
                    "sSearch":         "Rechercher&nbsp;:",
                    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sInfo":           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    "sInfoEmpty":      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    "sInfoPostFix":    "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                    "oPaginate": {
                        "sFirst":      "Premier",
                        "sPrevious":   "Pr&eacute;c&eacute;dent",
                        "sNext":       "Suivant",
                        "sLast":       "Dernier"
                    },
                    "oAria": {
                        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                    },
                },
                "order": [[6, 'desc'],[0, 'asc']]
            });
            datatables[index] = datatable;
        });

        /**
         * Action launched on select change
         * display selected grid calendar table and hide others
         */
        $(document).on("change", "#grid-calendar", function() {
            var activePanel = $(document).find(".exception-calendar.active");

            if (activePanel.length > 0) {
                toggleAllCommentRows(getActivePanel());
                activePanel.removeClass("active").hide();
            }

            $(document).find("#"+$(this).val()+".exception-calendar").addClass("active").fadeIn();
        });

        /**
         * One-shot triggered change event on select field in order to display
         * first grid calendar table when the page is loaded
         */
        $("#grid-calendar").trigger("change");

        /**
         * Action launched on row click
         * add/remove a 'selected state class' on the row
         */
        $(document).on("click", ".comment", function() {
            toggleOneCommentRow($(this));
        });

        /**
         * Submit comment form and load data in selected rows if it has been
         * validated by the exception controller, if it wasn't, display
         * it again with explicit errors on related fields
         */
        $(document).on("click", "#apply-comment-form", function(event) {
            event.preventDefault();
            var $inputs = $("#new-comment :input");
            var data = {};
            $inputs.each(function() {
                data[this.name] = $(this).val();
            });
            var newData = false;
            $.ajax({
                url : Routing.generate('tisseo_tid_exception_comment'),
                type: 'POST',
                data : data,
                success: function(data) {
                    if (data.content)
                        $(".modal-content").html(data.content);
                    else if (data.label && data.commentText)
                        newData = data;
                }
            }).promise().then(function() {
                if (newData !== false) {
                    var currentPanel = getActivePanel();
                    fillNewComment(newData, currentPanel);
                    toggleAllCommentRows(currentPanel);
                    $("#base-modal").modal('hide');
                }
            });
        });

        /**
         * Action launched after save button has been clicked
         *
         * step1: validate data (no 'zz' comments present)
         * step2: build a json data with new values
         * step3: send json to controller
         */
        $(document).on("click", "#submit-exceptions", function() {
            var validation = true;
            $(".datatable").each(function(index) {
                var panelApi = $(this).dataTable();
                validation = validatePanel(panelApi);
                return validation;
            });

            if (validation) {
                var data = {};
                $.each(datatables, function(index, datatable) {
                    var editedRows = datatable.rows(".edited-row")[0];
                    $.each(editedRows, function(index, row) {
                        var tripId = $(datatable.row(row).nodes()).find('input').val();
                        var label =  $(datatable.cell(row, 6).node()).html();
                        var commentText =  $(datatable.cell(row, 7).node()).html();
                        
                        if (!(label in data))
                            data[label] = {"comment": commentText, "trips": []};
                        data[label].trips.push(tripId);
                    });
                });
                $.ajax({
                    url : Routing.generate('tisseo_tid_exception_edit'),
                    type: 'POST',
                    data : JSON.stringify(data),
                    success: function(data) {
                        if (data.status === true && data.location)
                            window.location.replace(data.location);
                    }
                });
            }
            else {
                if ($(document).find('#main-container .alert-danger').length === 0) {
                    var message = "<div class='alert alert-danger alert-dismissable danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+Translator.trans('exception.invalid_alert', {}, 'messages')+"</div>";
                    $("#main-container").prepend(message);
                }
            }
        });

        /** 
         * Check each row in the panel for validation on comments' values
         * -- failure on : label == zz
         */
        function validatePanel(panelApi) {
            var validation = true;
            $.each(panelApi.fnGetData(), function(index, row) {
                var label = row[6];
                var comment = row[7];

                // empty values: continue
                if (label.length === 0 && comment.length === 0)
                    return validation;

                if (label == "zz") {
                    validation = false;
                    return validation;
                }
            });

            return validation;
        }

        /**
         * Get the active panel using the global array of datatables
         */
        function getActivePanel() {
            var panelId = parseInt($(document).find(".exception-calendar.active").attr("id"));
            var currentPanel = datatables[panelId];

            return currentPanel;
        }

        /**
         * Toggle class for the row passed as argument
         */
        function toggleOneCommentRow(row) {
            row.toggleClass("edit-comment");
            toggleButtons(getActivePanel());
        }

        /**
         * Toggle class for all rows in the current panel
         */
        function toggleAllCommentRows(currentPanel) {
            var editedRows = currentPanel.rows(".edit-comment")[0];

            $.each(editedRows, function(index, row) {
                $(currentPanel.row(row).nodes()).toggleClass("edit-comment");
            });
            toggleButtons(currentPanel);
        }

        /**
         * Activate/Deactivate edit button switch number of selected rows in
         * active table (i.e. if no rows selected edit button is disabled)
         */
        function toggleButtons(currentPanel) {
            var editButtons = $(document).find(".exception-calendar.active .edit-button");
            var editedRows = currentPanel.rows(".edit-comment")[0];

            if (editedRows.length === 0) {
                editButtons.each(function() {
                    if ($(this).attr("disabled") !== "disabled")
                        $(this).attr("disabled","disabled");
                });
            }
            else {
                editButtons.each(function() {
                    if ($(this).attr("disabled") === "disabled")
                        $(this).attr("disabled",false);
                });
            }
        }

        /**
         * Fill new data in each row selected for edition
         */
        function fillNewComment(data, currentPanel) {
            var currentPanelApi = $(document).find(".exception-calendar.active .datatable").dataTable();
            var editedRows = currentPanel.rows(".edit-comment")[0];
        
            $.each(editedRows, function(index, row) {
                $(currentPanel.row(row).nodes()).removeClass("edit-comment").addClass("edited-row");
                currentPanelApi.fnUpdate(data.label, row, 6);
                currentPanelApi.fnUpdate(data.commentText, row, 7);
            });

            currentPanel.order([6, 'desc']).draw(true);
        }
    });
});
