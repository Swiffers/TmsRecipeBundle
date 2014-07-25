// Récupère le div qui contient la collection
var collectionHolder = $('#tms_recipebundle_recipe_ingredients');

// ajoute un lien « add an ingredient »
var $addLink = $('\
    <div class="form-group">\
        <div class="col-sm-12">\
            <a href="#" class="add_ingredient_link">\
                Ajouter un ingrédient\
            </a>\
        </div>\
    </div>\
');

jQuery(document).ready(function() {
    
    collectionHolder.find('.col-sm-10 > div > .form-group').each(function() {
        addFormDeleteLink($(this));
    });

    collectionHolder.append($addLink);
    $addLink.on('click', function(e) {
        e.preventDefault();

        addForm(collectionHolder, $addLink);
    });
});

function addFormDeleteLink($formLi) {
    var $removeFormA = $('<a href="#">Supprimer</a>');
    $formLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();

        $formLi.remove();
    });
}

function addForm(collectionHolder, $addLink) {
    var prototype = collectionHolder.attr('data-prototype');

    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    var $newForm = $(newForm);

    $addLink.before($newForm);
    addFormDeleteLink($newForm);
}