<?php

namespace AppBundle\Controller;

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

        $bdd_lastUpdated = $this->getDoctrine()->getRepository('AppBundle:LastUpdated');
        $lastUpdate = $bdd_lastUpdated->findOneBy(array('data'=>'game')) ;
        if($lastUpdate!=NULL) {
            $date = $lastUpdate->getUtcDate();
        } else {
            
        }

        $uri = 'http://api.football-data.org/v2/competitions/2015/matches?status=FINISHED';
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: 839c5a615c954184bf1a858a5f49005e';
        $stream_context = stream_context_create($reqPrefs);
        $response = file_get_contents($uri, false, $stream_context);
        $matches = json_decode($response, true);
        //var_dump($matches);

        return $this->render(
            'home/home.html.twig',
            array("matches" => $matches)
        );
    }


}