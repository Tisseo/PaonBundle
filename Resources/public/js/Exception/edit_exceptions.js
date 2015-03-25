define(['jquery', 'tid/datatables', 'fosjsrouting'], function($) {

    // re-fill line-number text in colored div
    textfill(15, 30, '.line-big');

    $(document).ready(function() {
        // build searchable/filtered tables for each gridCalendar
        $(".datatable").each(function() {
            $(this).DataTable({
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
        });

        // Display selected gridCalendar and hide others
        $(document).on("change", "#grid-calendar", function() {
            var activePanel = $(document).find(".exception-calendar.active");

            if (activePanel) {
                toggleAllCommentRows();
                activePanel.removeClass("active").hide();
            }

            $(document).find("#"+$(this).val()+".exception-calendar").addClass("active").fadeIn();
        });

        // one-shot trigger a change on select field in order to display first
        // gridCalendar table
        $("#grid-calendar").trigger("change");

        $(document).on("click", ".comment", function() {
            toggleOneCommentRow($(this));
        });

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
                    fillNewComment(newData);
                    toggleAllCommentRows();
                    $("#base-modal").modal('hide');
                }
            });
        });

        function toggleOneCommentRow(row) {
            row.toggleClass("edit-comment");
            toggleButtons();
        }

        function toggleAllCommentRows() {
            rows = $(document).find(".exception-calendar.active .edit-comment");
            rows.each(function() {
                $(this).toggleClass("edit-comment");
            });
            toggleButtons();
        }

        function toggleButtons() {
            var editButtons = $(document).find(".exception-calendar.active .edit-button");
            if ($(document).find(".exception-calendar.active .edit-comment").length === 0) {
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

        function fillNewComment(data) {
            var editedRows = $(document).find(".exception-calendar.active .edit-comment");
            editedRows.each(function() {
                $(this).addClass("edited-row");
                var lastColumns = $(this).find("td").slice(-2);
                $(lastColumns).each(function(index) {
                    if (index === 1)
                        $(this).html(data.commentText);
                    else
                        $(this).html(data.label);
                });
            });
        }
    });
});
