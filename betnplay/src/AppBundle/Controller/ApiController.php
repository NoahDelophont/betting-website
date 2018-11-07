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

        $uri = 'http://api.football-data.org/v2/competitions/2015/matches?status=SCHEDULED';
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