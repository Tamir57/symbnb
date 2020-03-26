$('#add-image').click(function(){
    //je récupère le numéro des futur champs ...
    //const index = $('#ad_images div.form-group').length;
    //le plus + indique que cest un nombre 
    const index = +$('#widgets-counter').val();

    //je récupère les prototype des entrées
    //en html data-nom peut être récupéré avec .data('nom') en jquery
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //jinjecte ce code au sein de la div
    $('#ad_images').append(tmpl);
    //console.log(tmpl);

    $('#widgets-counter').val(index+1);

    //bt supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        //this représente le bouton sur lequelle on click
        //dataset représente tout les attributs "data-"
        //target nom de lattribut data-target du button 
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();