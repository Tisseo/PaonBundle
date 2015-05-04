require(['jquery'], function($){
    var collectionHolder = $('ul.lines');
    var $addSubFormLink = $('<button type="submit" class="btn btn-success small-button"><span class="glyphicon glyphicon-plus"></span></button>');
    var $newLinkLi = $('<li></li>').append($addSubFormLink);

    var init = function() {
        collectionHolder.append($newLinkLi);
        collectionHolder.find('li').each(function(){
            addSubFormDeleteLink($(this));
        });

        $addSubFormLink.on('click', function (e) {
            e.preventDefault();
            addSubForm(collectionHolder, $newLinkLi);
        });

        // Remove data-target attribute
        $('.modal').find('button[type=submit]').removeData('target');
    };

    var addSubForm = function(collectionHolder, $newLinkLi) {
        var prototype = collectionHolder.attr('data-prototype');
        var newSubForm = prototype.replace(/__name__/g, collectionHolder.children().length);
        var $newSubFormLi = $('<li></li>').append(newSubForm);
        $newLinkLi.before($newSubFormLi);
        addSubFormDeleteLink($newSubFormLi);
    };

    function addSubFormDeleteLink($subFormLi) {
        var $removeFormA = $('<a href="#">Supprimer ce tag</a>');
        $subFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            e.preventDefault();
            $subFormLi.remove();
        });
    }


    init();
});

