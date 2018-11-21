var search = document.getElementById('search');
search.onkeyup = function() {
    getData(search.value);
}

function getData(filter) {
    $.ajax({
        url: 'http://localhost:8000/request/'+filter,dataType: 'json',
        type: 'GET',
    }).done(function(response) {
        $('#separator').nextAll('div').remove();
        createList(response);
    });
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


        var date1 = matches["matches"][i]['utcDate'].split('-');
        var date1_1 = date1[2].split('T');
        var date2 = matches["matches"][i]['utcDate'].split('T');
        var date3 = date2[1].split(':');
        var date_finale = date1_1[0] + '/' + date1[1] + ' ' + date3[0] + ':' + date3[1];
        html = html + date_finale;

        html = html + '</div></div>';
        html = html + '<div class="row">';
        html = html + '<div class="col-md-3 col-md-offset-1 cote">1.5</div>';
        html = html + '<div class="col-md-3 cote">1.3</div>';
        html = html + '<div class="col-md-3 cote">1.4</div>';
        html = html + '</div><hr/></div></div>';

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
        url: 'http://localhost:8000/request/all/bets', dataType: 'json',
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