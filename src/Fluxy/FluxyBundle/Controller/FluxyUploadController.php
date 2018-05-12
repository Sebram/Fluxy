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

class FluxyUploadController extends FOSRestController
{

    /**
     * @Route("/import")
     * Method("POST")
     * 
     * 
     */
    public function uploadFormAction() {

        $tables = $this->get('doctrine.dbal.default_connection')->getSchemaManager()->listTableNames();
        
        return $this->render('fluxy/csv/new.html.twig', array(
        
            'tables' => $tables
        ));
    }

    /**
     * @Route("/csv-upload")
     * Method("POST")
     * 
     * 
     */
    public function uploadAction(Request $request)
    {
        $request = Request::createFromGlobals();
        
        $file = $request->files->get ( 'file' );
        
        try {
            if( $file) {

                $fileName = md5 ( uniqid () ) . '.' . $file->guessExtension ();

                $original_name = $file->getClientOriginalName ();

                $file_entity = new Fluxycsv ();

                if($file->move ( $this->container->getParameter ( 'file_directory' ), $fileName )) {

                	$file_entity->setUploaded ( 1 );

                	var_dump($fileName .' '. $original_name);

                	$file_entity->setName ( $fileName );

	                $file_entity->setActualName ( $original_name );

	                $file_entity->setCreated ( time() );

	                $file_entity->setTablename ( $request->request->get('tablename') );

	                $file_entity->setPath ( 'uploaded/'.$fileName );

	                $file_entity->setFilesize ( $request->request->get('filesize') );
	                
			        $manager = $this->getDoctrine ()->getManager ();

			        $manager->persist ( $file_entity );

			        $manager->flush ();

			        $array = array (
			            'status' => 1,

			            'file_id' => $file_entity->getId () 
			        );
			        
			        return $this->redirect('keycheck/'.$file_entity->getId());

                }
                else 
                
		        $response = new JsonResponse ( $array, 200 );
		        
		        return new Response (
                
                '<html>
                <head>
                    <title>
                        Fluxy upload
                    </title>
                </head>
                <body>
                    <h2>Error !</h2>
                    <h3>Error dans le transfert du fichier "'.$fileName.'".</h3>
                    <a href="./upload">Retour</a>
                </body>
                </html>'

                );
            }
        }

        catch(Exception $e) {

           return new Response (
                
                '<html>
                <head>
                    <title>
                        Fluxy upload
                    </title>
                </head>
                <body>
                    <h2>Error !</h2>
                    <h3>Error dans le transfert du fichier "'.$fileName.'".</h3>
                    <a href="./upload">Retour</a>
                    '.print_r($e->getMessage(), 1).'
                </body>
                </html>'

                );
        }
    }

}