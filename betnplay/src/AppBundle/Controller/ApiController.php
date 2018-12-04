<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bet;
use AppBundle\Entity\Game;
use AppBundle\Entity\LastUpdated;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


class ApiController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function dataAction(){

        if ($this->container->has('profiler'))
        {
            $this->container->get('profiler')->disable();
        }
        $em = $this->getDoctrine()->getManager();

        $upToDate = true;
        $date = date('d/m/y');
        $bdd_lastUpdated = $this->getDoctrine()->getRepository('AppBundle:LastUpdated');
        $lastUpdate = $bdd_lastUpdated->findOneBy(array('data'=>'game')) ;
        if($lastUpdate!=NULL) {
            $up_date = $lastUpdate->getUtcDate();
            if($up_date!=$date) {
                $upToDate = false;
                $lastUpdate->setUtcDate($date);
            }
        } else {
            $entity = new LastUpdated();
            $entity->setData('game');
            $entity->setUtcDate($date);
            $upToDate = false;
            $em->persist($entity);
            $em->flush();
        }

        $bdd_game = $this->getDoctrine()->getRepository('AppBundle:Game');
        $date_current = date('Y-m-d').'T'.date('h:m:00').'Z';
        if(!$upToDate) {
            $uri = 'http://api.football-data.org/v2/competitions/2015/matches';//?status=FINISHED
            $reqPrefs['http']['method'] = 'GET';
            $reqPrefs['http']['header'] = 'X-Auth-Token: 839c5a615c954184bf1a858a5f49005e';
            $stream_context = stream_context_create($reqPrefs);
            $response = file_get_contents($uri, false, $stream_context);
            $matches = json_decode($response, true);
            $json = [];

            for ($i = 0;$i<count($matches['matches']);$i++) {
                $result = $bdd_game->findOneBy(array('apiId'=>$matches['matches'][$i]['id']));
                if($result==NULL) {
                    $match = new Game();
                    $match->setApiId($matches['matches'][$i]['id']);
                    $match->setUtcDate($matches['matches'][$i]['utcDate']);
                    $match->setAwayTeam(json_encode($matches['matches'][$i]['awayTeam']));
                    $match->setCompetition(json_encode($matches['competition']));
                    $match->setCote('{1.4,1.5,1.3}');
                    $match->setHomeTeam(json_encode($matches['matches'][$i]['homeTeam']));
                    $match->setMatchDay($matches['matches'][$i]['matchday']);
                    $match->setScore(json_encode($matches['matches'][$i]['score']));

                    $em->persist($match);
                } elseif ( ($result->getScore())!=(json_encode($matches['matches'][$i]['score'])) ) {
                    $result->setScore(json_encode($matches['matches'][$i]['score']));
                }
                if(strcmp($matches['matches'][$i]['utcDate'],$date_current)>=0)
                    array_push($json,$matches['matches'][$i]);
            }
            $em->flush();
            $matches = array('competition'=>array('id'=>2015,'name'=>'Ligue 1'),'matches'=>$json);


        } else {
            $result = $bdd_game->findAll();
            $json = [];
            for($i=0;$i<count($result);$i++) {
                $date_game = $result[$i]->getUtcDate();
                if(strcmp($date_game,$date_current)>=0)
                    array_push($json,array(  'id'=>$result[$i]->getApiId(),
                                                    'awayTeam' => json_decode($result[$i]->getAwayTeam(),true),
                                                    'cote'=>$result[$i]->getCote(),
                                                    'utcDate'=>$result[$i]->getUtcDate(),
                                                    'homeTeam'=> json_decode($result[$i]->getHomeTeam(),true),
                                                    'matchday'=> $result[$i]->getMatchDay(),
                                                    'score'=> json_decode($result[$i]->getScore()),true));
            }
            $matches = array('competition'=>array('id'=>2015,'name'=>'Ligue 1'),'matches'=>$json);
        }


        $bdd_bet = $this->getDoctrine()->getRepository('AppBundle:Bet');
        $lastBetUpdate = $bdd_lastUpdated->findOneBy(array('data'=>'bet')) ;
        if($lastBetUpdate!=NULL) {
            $up_date = $lastBetUpdate->getUtcDate();
            if($up_date!=$date) {
                $upToDate = false;
                $lastBetUpdate->setUtcDate($date);
            }
        } else {
            $entity = new LastUpdated();
            $entity->setData('bet');
            $entity->setUtcDate($date);
            $upToDate = false;
            $em->persist($entity);
            $em->flush();
        }

        if(!$upToDate) {
            $results = $bdd_game->findAll();
            for($i=0;$i<count($results);$i++) {
                $idGame = $results[$i]->getApiId();
                $bets = $bdd_bet->findByIdGame($idGame);
                $winGame = (json_decode($results[$i]->getScore(), true))['winner'];
                if ($winGame != null) {
                    for ($j = 0; $j < count($bets); $j++) {
                        $winBet = $bets[$j]->getWin();
                        if ($winGame != null && $winBet != 0) {
                            break;
                        } else {
                            $betUser = $bets[$j]->getTeam();
                            if( ($winGame=="HOME_TEAM" && $betUser==0) ||
                                ($winGame=="AWAY_TEAM" && $betUser==2) ||
                                ($winGame=="DRAW" && $betUser==1)) {
                                $bets[$j]->setWin(1);
                            } else {
                                $bets[$j]->setWin(-1);
                            }
                        }
                    }
                }
            }
        }
        $em->flush();



        return $this->render(
            'home/home.html.twig',
            array("matches" => $matches,"homepage"=>true)
        );
    }

    /**
     * @Route("/request/{request}", name="ajax")
     */
    public function ajaxRequestAction($request) {
        $request = urldecode($request);
        $em = $this->getDoctrine()->getManager();
        $bdd_game = $this->getDoctrine()->getRepository('AppBundle:Game');
        $date_current = date('Y-m-d').'T'.date('h:m:00').'Z';

        if($request=="all") {
            $result = $bdd_game->findAll();
            $json = [];
            for ($i = 0; $i < count($result); $i++) {
                if(strcmp($result[$i]->getUtcDate(),$date_current)>=0)
                    array_push($json, array('id' => $result[$i]->getApiId(),
                        'awayTeam' => json_decode($result[$i]->getAwayTeam(), true),
                        'cote' => $result[$i]->getCote(),
                        'utcDate' => $result[$i]->getUtcDate(),
                        'homeTeam' => json_decode($result[$i]->getHomeTeam(), true),
                        'matchday' => $result[$i]->getMatchDay(),
                        'score' => json_decode($result[$i]->getScore()), true));
            }
            $matches = array('competition' => array('id' => 2015, 'name' => 'Ligue 1'), 'matches' => $json);
        } elseif (!preg_match('~[0-9]~', $request)) {

            $repository = $em->getRepository('AppBundle:Game');
            $query = $repository->createQueryBuilder('g')
                ->where('g.homeTeam LIKE :word')
                ->orWhere('g.awayTeam LIKE :word')
                ->setParameter('word', '%'.$request.'%')
                ->getQuery();
            $result = $query->getResult();
            $json = [];
            for ($i = 0; $i < count($result); $i++) {
                if(strcmp($result[$i]->getUtcDate(),$date_current)>=0)
                    array_push($json, array('id' => $result[$i]->getApiId(),
                        'awayTeam' => json_decode($result[$i]->getAwayTeam(), true),
                        'cote' => $result[$i]->getCote(),
                        'utcDate' => $result[$i]->getUtcDate(),
                        'homeTeam' => json_decode($result[$i]->getHomeTeam(), true),
                        'matchday' => $result[$i]->getMatchDay(),
                        'score' => json_decode($result[$i]->getScore()), true));
            }
            $matches = array('competition' => array('id' => 2015, 'name' => 'Ligue 1'), 'matches' => $json);
        } else {
            $result = $bdd_game->findOneBy(array('apiId'=>$request));
            $json = array(  'id' => $request,
                            'utcDate' => $result->getUtcDate(),
                            'homeTeam' => json_decode($result->getHomeTeam(), true),
                            'awayTeam' => json_decode($result->getAwayTeam(), true),
                            'competition' => array('name'=> "Ligue 1"),
                            'score' => json_decode($result->getScore(),true));
            $matches = array('match'=>$json);
        }

        return new JsonResponse($matches);
    }


    /**
     * @Route("/request/user/{user}", name="UserRequest")
     */
    public function requestUsersAction($user) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:User');
        $query = $repository->createQueryBuilder('u')
            ->where('u.username LIKE :user')
            ->setParameter('user', '%'.$user.'%')
            ->getQuery();
        $users = $query->getResult();
        $result = $this->convertUserArrayToArray($users);

        return new JsonResponse($result);
    }


    /**
     * @Route("/request/all/user", name="AllUserRequest")
     */
    public function requestAllUsersAction() {
        $bdd_user = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $bdd_user->findAll();
        $result = $this->convertUserArrayToArray($users);

        return new JsonResponse($result);
    }


    /**
     * @Route("/request/bets/{idMatch}", name="betrequest")
     */
    public function requestBetOnMatchAction($idMatch) {
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $result = array();

        $bdd_bet = $this->getDoctrine()->getRepository('AppBundle:Bet');
        $bet = $bdd_bet->findOneBy(array('idUser'=>$user,'idGame'=>$idMatch));

        if($bet!=NULL) {
            $result = array("id_game"=>$idMatch,"team"=>$bet->getTeam(),"win"=>$bet->getWin());
        }

        return new JsonResponse($result);
    }



    /**
     * @Route("/request/all/bets", name="allbetrequest")
     */
    public function requestAllBetsOnMatchAction() {
        $user = $this->getUser()->getId();
        //$em = $this->getDoctrine()->getManager();
        $result = array();

        /*$date_current = date('Y-m-d').'T'.date('h:m:00').'Z';
        $bdd_game = $this->getDoctrine()->getRepository('AppBundle:Game');*/
        $bdd_bet = $this->getDoctrine()->getRepository('AppBundle:Bet');
        $bets = $bdd_bet->findBy(array('idUser'=>$user));

        for($i=0;$i<count($bets);$i++) {
            $apiId = $bets[$i]->getIdGame();
            /*$game = $bdd_game->findOneBy(array('apiId'=>$apiId));
            $date = $game->getUtcDate();
            $status = 0;
            if(strcmp($date_current,$date)>=0) {
                $status = $bets[$i]->getWin();
                $team = $bets[$i]->getTeam();
                $score = json_decode($game->getScore(), true);
                if($score['winner']=='HOME_TEAM' && $team == 0)
                    $status = 1;
                elseif ($score['winner']=='AWAY_TEAM' && $team == 2)
                    $status = 1;
                else
                    $status = -1;
            }*/
            $result[$apiId] = $bets[$i]->getWin();
        }

        return new JsonResponse($result);
    }


    /**
     * @Route("/bets", name="betpage")
     */
    public function betAction() {
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();


        $bdd_bet = $this->getDoctrine()->getRepository('AppBundle:Bet');
        $bets = $bdd_bet->findBy(array('idUser'=>$user));
        $id_bets = [];
        for($i=0;$i<count($bets);$i++)
            array_push($id_bets,$bets[$i]->getIdGame());

        $team = -1;
        if(count($bets)>0) $team = $bets[0]->getTeam();


        $repository = $em->getRepository('AppBundle:Game');
        $query = $repository->createQueryBuilder('g')
            ->where('g.apiId IN(:games)')
            ->setParameter('games', array_values($id_bets))
            ->getQuery();
        $result = $query->getResult();


        $json = [];
        for ($i = 0; $i < count($result); $i++) {
            array_push($json, array('id' => $result[$i]->getApiId(),
                'awayTeam' => json_decode($result[$i]->getAwayTeam(), true),
                'cote' => $result[$i]->getCote(),
                'utcDate' => $result[$i]->getUtcDate(),
                'homeTeam' => json_decode($result[$i]->getHomeTeam(), true),
                'matchday' => $result[$i]->getMatchDay(),
                'score' => json_decode($result[$i]->getScore()), true));
        }


        $json = array_reverse($json);
        $matches = array('competition' => array('id' => 2015, 'name' => 'Ligue 1'), 'matches' => $json);
        return $this->render(
            'home/home.html.twig',
            array("matches" => $matches,"team"=>$team)
        );
    }


    /**
     * @Route("/bets/{idMatch}/{team}", name="betaction")
     */
    public function betOnMatchAction($idMatch,$team) {
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $bdd_bet = $this->getDoctrine()->getRepository('AppBundle:Bet');
        $bets = $bdd_bet->findOneBy(array('idUser'=>$user,'idGame'=>$idMatch));

        if($bets==NULL) {
            $bet = new Bet();
            $bet->setIdGame($idMatch);
            $bet->setIdUser($user);
            $bet->setTeam($team);
            $em->persist($bet);
            $em->flush();
        }

        // redirects to the "homepage" route
        return $this->redirectToRoute('betpage');
    }


    public function convertUserToArray($user) {
        $username = $user->getUsername();
        $level = $user->getLevel();
        $id = $user->getId();
        return array("username"=>$username,"level"=>$level,"id"=>$id);
    }

    public function convertUserArrayToArray($users) {
        $result = array();
        for($i=0;$i<count($users);$i++) {
            array_push($result,$this->convertUserToArray($users[$i]));
        }
        return $result;
    }

    public function getUserInfo($idUser) {

    }

    /**
     * @Route("/users", name="usersAction")
     */
    public function usersAction() {

        $bdd_user = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $bdd_user->findAll();
        $result = $this->convertUserArrayToArray($users);
        $fstUser = null;
        if(count($result)>0) $fstUser = array();

        return $this->render(
            'home/home.html.twig',
            array("users" => $result,"first_user"=>$fstUser)
        );
    }
    
    /**
     * @Route("/fiche", name="fiche_user")
     */
    public function ficheAction(){
        $user = $this->getUser();
        $matches = $this->getDoctrine()->getRepository('AppBundle:Bet')->getThreeLastBets($user);
        $datas = [];
        foreach ($matches as $match){
            $id = $match->getIdGame();
            $info_match = $this->getDoctrine()->getRepository('AppBundle:Game')->getInfos($id);
            array_push($datas, $info_match);
        }

        return $this->render('home/elements/fiche_user.html.twig');
    }
    
    
    public function winner($idMatch){
        $bdd_game = $this->getDoctrine()->getRepository('AppBundle:Game');
        $game = $bdd_game->findOneBy(array('apiId' => $idMatch));
        $gameScore = $game->getScore();
        $score = json_decode($gameScore, TRUE);
        if($score["winner"] == "HOME_TEAM"){
            return(0);
        }
        else if($score["winner"] == "AWAY_TEAM"){
            return(2);
        }
        else{
            return(1);
        }
    }
}