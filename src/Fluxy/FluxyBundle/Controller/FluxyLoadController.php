<?php

namespace App\Fluxy\FluxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use App\Entity\User;
use App\Entity\Client;
use Symfony\Doctrine\DBAL\Schema\MySqlSchemaManager;
use App\Entity\Fluxycsv;


class FluxyLoadController extends Controller
{


    /**
     * @Route("/load")
     * Method("POST")
     * 
     * 
     */
    public function loadAction(Request $request)
    {	
    	$request = Request::createFromGlobals();

        foreach ($request->request as $key => $value) {
        	echo '<pre> '.$key.' ';
        	print_r( $value );
        	echo '</pre>';
        }
        
       	return new Response (
                
                '<html>
                <head>
                    <title>
                        Fluxy load
                    </title>
                </head>
                <body>
                    <h2>Now we have to load ...</h2>
                    
                    
                </body>
                </html>'

                );
       
    }
}	
