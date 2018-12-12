var search = document.getElementById('search');
search.onkeyup = function() {
    getData(search.value);
}

function getData(filter) {
    if(filter!="") {
        $.ajax({
            url: ENVIRONNEMENT + '/request/' + filter, dataType: 'json',
            type: 'GET',
        }).done(function (response) {
            $('#separator').nextAll('div').remove();
            $('#separator').nextAll('hr').remove();
            createList(response);
        });
    } else {
        $.ajax({
            url: ENVIRONNEMENT + '/request/all', dataType: 'json',
            type: 'GET',
        }).done(function (response) {
            $('#separator').nextAll('div').remove();
            $('#separator').nextAll('hr').remove();
            createList(response);
        });
    }
}

function createList(matches) {
    var i;
    var addHtml = "";
    for(i=0;i<matches["matches"].length;i++) {
        var html = '<div class="row" id="' + matches["matches"][i]['id'] + '" style="cursor:pointer;" onclick="javascript:matchClicked(this)" >';
        html = html + '<div class="col-md-8 Pari">';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-12 col-md-offset-1 NomChampionnat">' + matches['competition']['name'] + '</div></div>';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-6 col-md-offset-1 NomEquipe">' + matches["matches"][i]['homeTeam']['name'] + ' - ' + matches["matches"][i]['awayTeam']['name'] + '</div>';
        html = html + '<div class="col-md-3 col-md-offset-2">';

        var cote = matches["matches"][i]['cote'];
        var cote1 = cote.split(',');
        var cote11 = cote1[0].split('{');
        var cote2 = cote1[2].split('}');

        var date1 = matches["matches"][i]['utcDate'].split('-');
        var date1_1 = date1[2].split('T');
        var date2 = matches["matches"][i]['utcDate'].split('T');
        var date3 = date2[1].split(':');
        var date_finale = date1_1[0] + '/' + date1[1] + ' ' + date3[0] + ':' + date3[1];
        html = html + date_finale;

        html = html + '</div></div>';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-3 col-md-offset-1 cote">'+cote11[1]+'</div>';
        html = html + '<div class="col-md-3 cote">'+cote1[1]+'</div>';
        html = html + '<div class="col-md-3 cote">'+cote2[0]+'</div>';
        html = html + '</div></div></div><hr/>';

        addHtml = addHtml + html;
        //document.getElementById('listMatches').innerHTML = document.getElementById('listMatches').innerHTML + html;
    }
    //document.body.innerText = addHtml;
    $( addHtml ).insertAfter( "#separator" );
}


function getIdDisplayedGame() {
    var array = $('#separator').nextAll('div');
    return array;
}

function changeColorGame(idGame,win = 0) {
    var game = document.getElementById(idGame);
    if(win==1)
        game.style.backgroundColor = "green";
    else if(win==-1)
        game.style.backgroundColor = "red";
    else
        game.style.backgroundColor = "DarkOrange";
}

function displayGameUserBetOn() {
    var array = getIdDisplayedGame();
    var i;

    $.ajax({
        url: ENVIRONNEMENT+'/request/all/bets', dataType: 'json',
        type: 'GET',
    }).done(function (response) {
        for(i=0;i<array.length;i++) {
            var idGame = array[i].id;
            if (JSON.stringify(response).includes(idGame)) {
                changeColorGame(idGame,response[idGame]);
            }
        }
    });
}

displayGameUserBetOn();

function getPsg(match){
    var bande = document.getElementById('bandeau');

    bande.innerHTML = match;
}

function getMatch() {
    var texte;
    $.ajax({
        url: ENVIRONNEMENT+'/request/'+'Paris',dataType: 'json',
        type: 'GET',
    }).done(function(response) {
        if (response["matches"].length >0){
            texte = '<b>Pariez sur le match ' + response["matches"][0]['homeTeam']['name'] + ' - ' + response["matches"][0]['awayTeam']['name'] + ' pour augmenter vos gains!  ' + '<img src ="https://image.flaticon.com/icons/svg/639/639365.svg" width="20px"/></b>';
            getPsg(texte);
        }
    });
}

getMatch();