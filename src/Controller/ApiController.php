<?php 
<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/*use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;*/
/*use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;*/
/*use FOS\RestBundle\Controller\FOSRestController as Controller;*/


class ApiController extends Controller
{
    /**
     * @Get(
     *     path = "/api/home",
     *     name = "app_home"
     * )
     *
     * @View(serializerGroups={"home"})
     */
    public function homeAction()
    {
        return new Reponse ("Hello world");
    }
}