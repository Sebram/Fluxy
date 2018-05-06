<?php

namespace App\Fluxy\KeycheckBundle\Controller;

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


class FluxyKeycheckController extends FOSRestController
{

    /**
     * @Get(
     *     path = "/keycheck",
     *     name = "fluxy_keycheck_home"
     * )
     */
    public function homeAction(Request $request)
    {
        /*$authenticationErrorResponse = $this->checkAuthAndGetErrorResponse($request);
        
        if ($authenticationErrorResponse) {
        
            return $authenticationErrorResponse;
        }*/

        return new Response('<html>
        <head>
            <title>KeyCheck</title>
        </head>
        <body>
        <h2>Welcome ! Nous allons faire un "Matching" entre les clés du fichier csv et de la base de données.</h2>

        </body>
        </html>');
    }




    private function checkAuthAndGetErrorResponse(Request $request)
    {
        $tokenManager = $this->get('fos_oauth_server.access_token_manager.default');

        $bearerToken = $this->get('fos_oauth_server.server')->getBearerToken($request);
        
        if (!$bearerToken) {

            return new JsonResponse(['status' => 400, 'message' => 'Bearer token not supplied'], 400);
        }

        $accessToken = $tokenManager->findTokenByToken($bearerToken);

        if (!$accessToken) {

            return new JsonResponse(['status' => 400, 'message' => 'Bearer token not valid'], 400);
        }

        if ($accessToken->hasExpired()) {

            return new JsonResponse(['status' => 400, 'message' => 'Access token has expired'], 400);
        }
        // may want to validate something else about the client, but that is beyond OAuth2 scope
        
        # $client = $accessToken->getClient();
        
        return null;
    }



}
