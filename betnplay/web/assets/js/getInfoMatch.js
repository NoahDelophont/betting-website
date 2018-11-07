function updateMatchSelected(competition,team1,team2,d) {
    var teams = document.getElementById('nomEquipes');
    var comp = document.getElementById('competition');
    var date = document.getElementById('date');
    var titre = document.getElementById('titre-match');
    var betAwayTeam = document.getElementById('betAwayTeam');
    var betHomeTeam = document.getElementById('betHomeTeam');

    teams.innerHTML = team1 + ' - ' + team2;
    comp.innerHTML = competition;
    date.innerHTML = d;
    titre.innerHTML = team1 + ' - ' + team2;
    betAwayTeam.innerHTML = 'Parier sur ' + team2.split(' ')[0]+'<br/>1.4';
    betHomeTeam.innerHTML = 'Parier sur ' + team1.split(' ')[0]+'<br/>1.5';
}

function getInfo(id) {

    $.ajax({
        headers: { 'X-Auth-Token': '839c5a615c954184bf1a858a5f49005e' },
        url: 'https://api.football-data.org/v2/matches/'+id,dataType: 'json',
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

        updateMatchSelected(competition,team1,team2,date);
    });

}

function matchClicked(elt) {
    getInfo(elt.id);
}