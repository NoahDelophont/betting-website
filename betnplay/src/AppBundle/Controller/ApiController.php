<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\LastUpdated;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        if(!$upToDate) {
            $uri = 'http://api.football-data.org/v2/competitions/2015/matches?status=FINISHED';
            $reqPrefs['http']['method'] = 'GET';
            $reqPrefs['http']['header'] = 'X-Auth-Token: 839c5a615c954184bf1a858a5f49005e';
            $stream_context = stream_context_create($reqPrefs);
            $response = file_get_contents($uri, false, $stream_context);
            $matches = json_decode($response, true);

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
                }
            }
            $em->flush();


        } else {
            $result = $bdd_game->findAll();
            $json_string = "{'competition':{'id':2015,'name':'Ligue 1'},'matches':[";
            $json = [];
            for($i=0;$i<count($result);$i++) {
                    $json_string = $json_string . "{'id':".$result[$i]->getApiId() . ','                                            . "'utcDate':".$result[$i]->getApiId()
                                                . "'awayTeam':".$result[$i]->getAwayTeam() . ','
                                                . "'cote':".$result[$i]->getCote(). ','
                                                . "'homeTeam':".$result[$i]->getHomeTeam(). ','
                                                . "'matchday':".$result[$i]->getMatchDay(). ','
                                                . "'score':".$result[$i]->getScore() . '}';
                    if($i<count($result)-1) $json_string = $json_string . ',';
                    array_push($json,array(  'id'=>$result[$i]->getApiId(),
                                                    'awayTeam' => json_decode($result[$i]->getAwayTeam(),true),
                                                    'cote'=>$result[$i]->getCote(),
                                                    'utcDate'=>$result[$i]->getUtcDate(),
                                                    'homeTeam'=> json_decode($result[$i]->getHomeTeam(),true),
                                                    'matchday'=> $result[$i]->getMatchDay(),
                                                    'score'=> json_decode($result[$i]->getScore()),true));
            }
            $json_string = $json_string . ']}';
            $matches = array('competition'=>array('id'=>2015,'name'=>'Ligue 1'),'matches'=>$json);
            //var_dump($matches);
            //exit(0);
        }

        return $this->render(
            'home/home.html.twig',
            array("matches" => $matches)
        );
    }


}