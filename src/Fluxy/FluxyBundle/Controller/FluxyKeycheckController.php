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


class FluxyKeycheckController extends FOSRestController
{

    /**
     * @Get(
     *     path = "/keycheck/{fileid}/",
     *     name = "fluxy_keycheck",
     *     requirements = {"fileid"}
     * )
     * 
     */
    public function keycheckAction(Request $request)
    {
        /*$authenticationErrorResponse = $this->checkAuthAndGetErrorResponse($request);
        
        if ($authenticationErrorResponse) {
        
            return $authenticationErrorResponse;
        }*/
        $fileid = $request->get('fileid');

        $em = $this->getDoctrine()->getManager();

        $csvRepository = $em->getRepository("App:Fluxycsv");

        $file = $csvRepository->findOneBy(array('id' => $fileid));

        if($file) {

            $filename = $file->getName();

            return $this->render('fluxy/csv/keycheck.html.twig', array(
            
                'filename' => $filename,
                'fileid' => $request->get('fileid')
            ));

        }
    }


    /**
     * @Get(
     *     path = "/keycheck/match/{fileid}/",
     *     name = "fluxy_keycheck_match",
     *     requirements = {"fileid"}
     * )
     * 
     */
    public function matchAction(Request $request) {
        
        # 'uploaded' base directory

        $upload_dir = $this->container->getParameter ( 'file_directory' );


        $fileid = $request->get('fileid');

        $em = $this->getDoctrine()->getManager();

        $csvRepository = $em->getRepository("App:Fluxycsv");

        $file = $csvRepository->findOneBy(array('id' => $fileid));

        $filename = $file->getName();

        $path = $file->getPath();

        $lines = $this->lireLigne($path);

        # NewPathName

        $newfilename = explode('.', strtr($path, array('uploaded/'=>'')))[0].'copy.txt';

        $newpath = $upload_dir.'/'.$newfilename;

        if(file_exists($newpath)) {
            unlink($newpath);
        }
        
        $ok = $this->traiterEcrireNouveauFichier($lines, $newpath);
       
        # Csv keys ...

        $csv_keystab = $this->csvKeys( $this->handle( $newpath ) );
        
        $csv_keys = $csv_keystab[0];

        # Updade fluxycsv ...    

        $file->setCsvkeys( $csv_keys );

        $file->setUpdated ( time() );

        $manager = $this->getDoctrine ()->getManager ();

        $manager->merge ( $file );

        $manager->flush ();

        # File info

        $arrayfileinfo = [];
        
        $arrayfileinfo[] = $file->getId();

        $arrayfileinfo[] = $file->getActualName();
        
        $arrayfileinfo[] = $file->getTablename();

        $arrayfileinfo[] = $file->getPath();
        
        $arrayfileinfo[] = $file->getUploaded() == 1 ? 'true' : 'false';
        
        $arrayfileinfo[] = date('d-m-Y | H:i',$file->getCreated());

        $arrayfileinfo[] = date('d-m-Y | H:i',$file->getUpdated());

        # table columns names

        $columnstab = $this->getRefTable($file->getTablename());

        $columns = [];
        
        foreach ($columnstab as $key => $value) {

            $columns [] = $value->getName();
        }

        if($file) {

            return $this->render('fluxy/csv/match.html.twig', array(
            
                'files' => $arrayfileinfo,

                'filename' => $newfilename,

                'csvkeys' => $csv_keys,

                'columns' => $columns
            ));
        }
    }



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

                $cles [] = explode( ";", strtr($line, array('"'=>'')) );
                break;
            }
        
            fclose( $handle );
        
        } else {
        
            echo  " error opening the file.";
        }

        return $cles;
    }



    private function getRefTable( $tablename )
    {
        $sm = $this->get('doctrine.dbal.default_connection')->getSchemaManager();

        $columnsname = $sm->listTableColumns( $tablename );

        return  $columnsname;
    } 



    
    #
    private function lireLigne(&$path)
    
    {
        $tabline=[];
        if(($handle = fopen ( $path, "r" )) !== FALSE ) {
            while (($line = fgetcsv( $handle, 0, "\n" )) !== FALSE ) {
                $tabline []= $line;
            }
        }
        return $tabline;
    }


    private function traiterEcrireNouveauFichier($lines, $newpath) {

       

        foreach ($lines as $uneligne) {

            if( preg_match('#!\##', $uneligne[0]) ) {

                file_put_contents( $newpath, utf8_encode( strtr( $uneligne[0], array('!#'=>';')) ).chr(13).chr(10), FILE_APPEND);
            }
        }
        return true;
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
