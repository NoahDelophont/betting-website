function updateMatchSelected(id,competition,team1,team2,d) {
    var teams = document.getElementById('nomEquipes');
    var comp = document.getElementById('competition');
    var date = document.getElementById('date');
    var titre = document.getElementById('titre-match');
    var betAwayTeam = document.getElementById('betAwayTeam');
    var betHomeTeam = document.getElementById('betHomeTeam');
    var container = (document.getElementById('buttons').childNodes)[1];

    teams.innerHTML = team1 + ' - ' + team2;
    comp.innerHTML = competition;
    date.innerHTML = d;
    titre.innerHTML = team1 + ' - ' + team2;
    betAwayTeam.innerHTML = 'Parier sur ' + team2.split(' ')[0]+'<br/>1.4';
    betHomeTeam.innerHTML = 'Parier sur ' + team1.split(' ')[0]+'<br/>1.5';
    container.id = "button-wrap-"+id;
}

function updateBetSelected(id,competition,team1,team2,d,bet) {
    var teams = document.getElementById('nomEquipes');
    var comp = document.getElementById('competition');
    var date = document.getElementById('date');
    var titre = document.getElementById('titre-match');
    var sousTitre = document.getElementById('sous-titre-match');

    teams.innerHTML = team1 + ' - ' + team2;
    comp.innerHTML = competition;
    date.innerHTML = d;
    titre.innerHTML = team1 + ' - ' + team2;
    if(bet==0) {
        sousTitre.innerHTML = "Vous avez parié sur une victoire de "+team1;
    } else if(bet==1) {
        sousTitre.innerHTML = "Vous avez parié sur un match nul";
    } else if (bet==2) {
        sousTitre.innerHTML = "Vous avez parié sur une victoire de "+team2;
    }
}

function getInfo(id) {

    $.ajax({
        url: 'http://localhost:8000/request/'+id,dataType: 'json',
        type: 'GET',
    }).done(function(response) {
        var competition = response['match']['competition']['name'];

        var team1 = response['match']['homeTeam']['name'];
        var team2 = response['match']['awayTeam']['name'];

        var date = response['match']['utcDate'];
        var tmp = date.split('-');
        var tmp2 = tmp[2].split('T');
        var tmp3 = response['match']['utcDate'].split('T');
        var tmp4 = tmp3[1].split(':');
        date = tmp2[0] + '/' + tmp[1] + ' ' + tmp4[0] + ':' + tmp4[1];

        updateMatchSelected(id,competition,team1,team2,date);
    });
}

function getBetInfo(id) {

    $.ajax({
        url: 'http://localhost:8000/request/'+id,dataType: 'json',
        type: 'GET',
    }).done(function(response) {
        var competition = response['match']['competition']['name'];

        var team1 = response['match']['homeTeam']['name'];
        var team2 = response['match']['awayTeam']['name'];

        var date = response['match']['utcDate'];
        var tmp = date.split('-');
        var tmp2 = tmp[2].split('T');
        var tmp3 = response['match']['utcDate'].split('T');
        var tmp4 = tmp3[1].split(':');
        date = tmp2[0] + '/' + tmp[1] + ' ' + tmp4[0] + ':' + tmp4[1];

        $.ajax({
            url: 'http://localhost:8000/request/bets/'+id,dataType: 'json',
            type: 'GET',
        }).done(function(response2) {

            updateBetSelected(id,competition,team1,team2,date,response2['team']);

        });
    });
}

function matchClicked(elt,bet = "false") {
    if(bet=="false")
        getInfo(elt.id);
    else if(bet=="true")
        getBetInfo(elt.id)
}

function betOnGame(elt,n) {
    var idGame = elt.parentElement.id.split('-')[2];
    window.location.href = "/bets/"+idGame+"/"+n;
}