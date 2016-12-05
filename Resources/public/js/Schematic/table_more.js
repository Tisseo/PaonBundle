/**
 * Show / Hide some lines in any table
 */
(function ($) {
    'use strict';

    var
        DEFAULT_LIMIT = 2,
        DEFAULT_LABEL = 'Show more'
        ;

    $.fn.showMore = show_more;

    /**
     * options.limit
     * options.showMoreText
     * options.showLessText
     * @param options
     */
    function show_more(options) {
        
        var
            tableElement = this,
            tableRows = $(tableElement).find('tbody > tr'),
            tableBody = $(tableElement).find('tbody'),
            isFolded = false
            ;

        var limit = options.limit || DEFAULT_LIMIT,
            label = options.label || DEFAULT_LABEL
            ;

        // Footer link
        var showMoreLink = $('<tfoot >' +
            '<tr>' +
            '<th colspan="5" > ' +
            '<a href="#"><i class="glyphicon glyphicon-chevron-down"></i> <span>' + label + '</span></a>' +
            '</th>' +
            '</tr>' +
            '</tfoot>');

        /**
         * Toggle visibility
         */
        var toggleVisible = function () {
            for (var i = limit; i < tableRows.length; i++) {
                if (isFolded) {
                    tableRows.eq(i).show();
                    showMoreLink.addClass('folded');
                    showMoreLink.find('a > span').text(options.showLessText || 'Show less');
                } else {
                    tableRows.eq(i).hide();
                    showMoreLink.removeClass('folded');
                    showMoreLink.find('a > span').text(options.showMoreText || 'Show more');

                }
            }
            isFolded = !isFolded;
        };

        if (tableRows.length > limit) {
            tableBody.after(showMoreLink);
            toggleVisible();
        }

        /**
         * on click show/hide
         */
        showMoreLink.on('click', function (event) {
            event.preventDefault();
            toggleVisible();
        })
    }


})(jQuery);
