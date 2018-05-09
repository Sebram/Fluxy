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
use Symfony\Doctrine\DBAL\Schema\MySqlSchemaManager;
use App\Entity\Csvfiles;


class FluxyKeycheckController extends FOSRestController
{

    /**
     * @Get(
     *     path = "/import",
     *     name = "fluxy_import_home"
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
            <title>Fluxy KeyCheck</title>
        </head>
        <body>
        <h2>Welcome !</h2>
        <h3> Nous allons commencer par faire un "Matching" entre les clés du fichier csv et de la base de données.</h3>
        
        <a href="upload">upload</a>
        </body>
        </html>');
    }



    /**
     * @Get(
     *     path = "/upload",
     *     name = "fluxy_keycheck_upload"
     * )
     */
    public function uploadAction(Request $request)
    {
        

        if($request->get('filename')) {

            $filename = "uploaded/".trim($request->get('filename'));

            $csvkeys = $this->csvKeys($this->handle($filename));

            return new Response(
                '<html>
                <head>
                    <title>
                        Fluxy upload
                    </title>
                </head>
                <body>
                    <h2>Upload !</h2>
                    <h3>Le fichier csv "'.$filename.'" a bien été récupéré.</h3>
                    '.print_r($csvkeys).'
                </body>
                </html>'
            );
        }



        if($request->get('file')) {

            if(move_uploaded_file($request->get('file'), 'uploaded'))
            { 
                return new Response(
                '<html>
                <head>
                    <title>
                        Fluxy upload
                    </title>
                </head>
                <body>
                    <h2>Uploaded !</h2>
                    <h3>Le fichier "'.$request->get('file').'" a bien été uploadé.</h3>
                    
                </body>
                </html>'
                );
            }

            return new Response(
                '<html>
                <head>
                    <title>
                        Fluxy upload
                    </title>
                </head>
                <body>
                    <h2>Error !</h2>
                    <h3>Error dans le transfert du fichier "'.$request->get('file').'".</h3>
                    <a href="./upload">Retour</a>
                </body>
                </html>'
                );

        }
       
        

        $tables = $this->get('doctrine.dbal.default_connection')->getSchemaManager()->listTableNames();

       // var_dump($tables);
        $response = '<html>
                    <head>
                        <title>Fluxy upload</title>
                    </head>
                    <body>
                        <h1>Upload</h1>
                        <div class="uploadform" style="margin-left:33.3%;width:33.3%">
                        <form action"upload">
                        <fieldset>
                        <legend><strong>Upload & table check:</strong></legend>
                        <p>Sélectionnez votre fichier ci-dessous</p>
                        <input type="file" name="file">
                        <br>
                    ';

        $response .= '
                        <p>
                            Sinon placez le fichier dans "public/uploaded" puis entrez son nom ci-dessous :
                        </p>
                        <input type="text" placeholder="Filename.csv" name="filename">
                        <br><hr>
                        <p>Sélectionnez la table à remplir:</p>
                        <select name="table">
                    ';

        foreach ($tables as $key => $value) {

            if($value != 'oauth2_access_tokens'
                && $value != 'oauth2_auth_codes'
                && $value != 'oauth2_clients'
                && $value != 'oauth2_refresh_tokens'
                && $value != 'test'
                && $value != 'user'
                && $value != 'migration_versions'
                )
                $response .= '           <option>'.$value.'</option>';

        }

        $response .= '      </select>
                            <hr><br>
                            <input type="submit" style="margin-left:80%">
                            <br>
                            <br>
                            </fieldset>
                        </form>
                        </div>
                    </body>
                    </html>';

        return new Response(
            $response
        );
      
    }




    // TODO COPY & INTEGRATE /Weborium/reparecsv/*


    private function handle($path)

    {
        $handle = fopen( $path, "r" );

        return $handle;
    }


    private function csvKeys($handle) 

    {
        $cles = [];

        if ( $handle ) {        
        
            while ( ( $line = fgets($handle) ) !== false) {
                // process the line read.
                // echo $line.'<br>';
                $cles [] = explode( ";", $line );
                break;
            }
        
            fclose( $handle );
        
        } else {
        
            echo  " error opening the file.";
        }

        return $cles;
    }




// -------------------------------------------------------------------------------------    
// -------------------------------------------------------------------------------------    

    // ----------------- AUTH...

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
