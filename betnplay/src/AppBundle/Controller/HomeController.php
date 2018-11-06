<?php
/**
 * Created by PhpStorm.
 * User: mauillonedwin
 * Date: 17/10/2018
 * Time: 13:06
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/test", name="home")
     */
    public function homepage(Request $request)
    {
        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', [
         //   'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        //]);

        return $this->render('default/liste.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

}