var follow = document.getElementById('follow');
follow.onclick = function() {
    window.location.href = followString;
};

function updateUserSelected1(username,level,nbLost,nbWin) {
    var name = document.getElementById('username');
    var userLevel = document.getElementById('userLevel');
    var wons = document.getElementById('wons');
    var losts = document.getElementById('losts');

    name.innerHTML = '@'+username;
    userLevel.innerHTML = "This player is on the level "+level;
    wons.innerHTML = nbWin+' win bets';
    losts.innerHTML = nbLost+' lost bets';
}

function updateUserSelected2(match,pari,score,nb) {
    var matchBet = document.getElementById('matchBet'+nb);
    var pariVND = document.getElementById('pariVND'+nb);
    var realScore = document.getElementById('realScore'+nb);

    matchBet.innerHTML = match;
    pariVND.innerHTML = pari;
    realScore.innerHTML = score;
}

function getUserInfo(id) {

    var str = id;
    $.ajax({
        url: ENVIRONNEMENT+'/users/'+id,dataType: 'json',
        type: 'GET',
    }).done(function(response) {
        followString = ENVIRONNEMENT+"/follow/"+str;
        var username = response["users"]['username'];
        var level = response["users"]['level'];
        var id = response["users"]['id'];
        var nbLost = response["fstUser"]['lost'];
        var nbWin = response["fstUser"]['win'];
        updateUserSelected1(username,level,nbLost,nbWin);

        var fstUserBets = response["fstUserBets"];
        var i;
        for(i=0;i<3;i++) {
            if(i<fstUserBets.length) {
                var homeTeam = fstUserBets[i]["homeTeam"];
                var awayTeam = fstUserBets[i]["awayTeam"];
                var team = fstUserBets[i]["team"];
                var win = fstUserBets[i]["win"];
                var fullTimeHomeTeam =  fstUserBets[i]["fullTimeHomeTeam"];
                var fullTimeAwayTeam =  fstUserBets[i]["fullTimeAwayTeam"];

                var match = homeTeam+' - '+awayTeam;
                var pari = "Pari: ";
                if(team==0)
                    pari = pari+'Domicile';
                else if(team==1)
                    pari = pari+'Nul';
                else if(team==2)
                    pari = pari+'Exterieur';
                var score = fullTimeHomeTeam+' - '+fullTimeAwayTeam;
                updateUserSelected2(match,pari,"Score réel: "+score,i);
            } else {
                updateUserSelected2("","","",i);
            }
        }
    });
}

function userClicked(elt) {
    getUserInfo(elt.id);
}