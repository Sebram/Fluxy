<?php
namespace App\Fluxy\FluxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;

class FluxyHomeController extends FOSRestController
{

    /**
     * @Get(
     *     path = "/",
     *     name = "fluxy"
     * )
     */
    public function indexAction()
    {
        $appname="Fluxy";

        return $this->render('fluxy/home.html.twig', array(
            'appname' => $appname,
        ));
    }

     /**
     * @Get(
     *     path = "/home",
     *     name = "fluxy_home"
     * )
     */
    public function homeAction()
    {
        $appname="Fluxy";

        return $this->render('fluxy/home.html.twig', array(
            'appname' => $appname,
        ));
    }

}
