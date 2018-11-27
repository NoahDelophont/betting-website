var search = document.getElementById('search');
search.onkeyup = function() {
    getData(search.value);
}

function getData(filter) {
    if(filter!="") {
        $.ajax({
            url: ENVIRONNEMENT + '/request/user/' + filter, dataType: 'json',
            type: 'GET',
        }).done(function (response) {
            $('#separator').nextAll('div').remove();
            $('#separator').nextAll('hr').remove();
            createList(response);
        });
    } else {
        $.ajax({
            url: ENVIRONNEMENT + '/request/all/user', dataType: 'json',
            type: 'GET',
        }).done(function (response) {
            $('#separator').nextAll('div').remove();
            $('#separator').nextAll('hr').remove();
            createList(response);
        });
    }
}

function createList(users) {
    var i;
    var addHtml = "";
    for(i=0;i<users.length;i++) {
        var html = '<div class="row">';
        html = html + '<div class="col-md-8 Pari">';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-12 col-md-offset-1 NomChampionnat">Level ' + users[i]['level'] + '</div></div>';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-6 col-md-offset-1 NomEquipe">@' + users[i]['username'] + '</div>';
        html = html + '<div class="col-md-3 col-md-offset-2">';

        html = html + 'Ratio: 10%';

        html = html + '</div></div>';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-3 col-md-offset-1 cote"></div>';
        html = html + '<div class="col-md-3 cote"></div>';
        html = html + '<div class="col-md-3 cote"></div>';
        html = html + '</div></div></div><hr/>';

        addHtml = addHtml + html;
    }
    $( addHtml ).insertAfter( "#separator" );
}