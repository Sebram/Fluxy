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

        $cles = 
        $request = Request::createFromGlobals();

        echo $newpath = $request->request->get('newpath');
        echo ' into ';
        echo $tablename = $request->request->get('tablename');

        foreach ($request->request as $key => $value) {
        	echo '<pre> '.$key.' ';
        	print_r( $value );
        	echo '</pre>';
        }

        $query =  $this->loadDataHead($newpath, $tablename);
        
        
        
       	return new Response (
                
                '<html>
                <head>
                    <title>
                        Fluxy load
                    </title>
                </head>
                <body>
                    <h2>Now we have to load ...</h2>
                    
                    '.$query.'
                    
                </body>
                </html>'

                );

    }





    private function loadDataHead($path, $tablename) 

    {
        $query  = "LOAD DATA LOCAL INFILE '".$path."' ".chr(13);
        
        # Put the table name here.
        
        $query .= " INTO TABLE ".$tablename." ".chr(13);
        
        # $separated ( Dans ce cas chaque est séparé par un ';' )
        
        $query .= " FIELDS TERMINATED BY ';' ".chr(13);
        
        # $enclosed ( Chaque champ est entouré de double-quote )
        
        $query .= " ENCLOSED BY '\"' ".chr(13);
        
        # $terminated ( Chaque ligne se termine par un retour chariot ) 
        
        $query .= " LINES TERMINATED BY '\\n' ".chr(13);
        
        # Si TRUE On ignore la première ligne.
        
        $query .= " IGNORE 1 LINES ".chr(13).chr(13);

        return (string)$query;
    }

    private function loadDataRefListing(&$query, &$cles) 

    {
        # On liste les références des champs du fichier CSV

        $query .=   " (";

        $valuequery="";

        array_walk($cles[0], $loop=function($cle, $key) use (&$valuequery) {
            
            $valuequery .= '@'.str_replace( '"','',$cle ).', '; 
        
        });

        $valuequery = substr( $valuequery, 0, -2 );

        $query .= $valuequery . ")" .chr( 13 ).chr( 13 );

        return $query;
    }

    private function loadDataSetting(&$query, &$post)

    {
        $x=0;

        $postvalue = $post;

        $query .=   " SET " .chr(13);

        array_walk($postvalue, $loop_post = function($item, $key) use (&$query, &$postvalue, &$x) {

            if( @$postvalue["csvref-$x"] != "" ) {

                $query .= strtolower( @$postvalue["tableref-$x"] )." = ";

                if( @$postvalue["csvref-$x"] == "" ) { 

                    $query .= "'',"; 

                } elseif($x == (count($postvalue)-1)) {
                    
                    $query .= @$postvalue["csvref-$x"] .chr(13); 
                
                } else {

                    $query .= @$postvalue["csvref-$x"].','.chr(13);

                }
            }
            $x++;
        });

        $query = substr( $query, 0, -2 ).';';   

        return $query;
    }

    private function initQuery( &$post, &$path, &$tablename, $telhab="" )
    
    {
        $handle = $this->handle($path);
        
        $cles = $this->csvKeys($handle);
        
        $query = $this->loadDataHead($path, $tablename);
        
        $query = $this->loadDataRefListing($query, $cles);
        
        $query = $this->loadDataSetting($query, $post);

        return $query;
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

}	
