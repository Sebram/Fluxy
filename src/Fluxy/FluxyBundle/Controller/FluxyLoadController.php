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
use App\Fluxy\Entity\User;
use App\Fluxy\Entity\Client;
use Symfony\Doctrine\DBAL\Schema\MySqlSchemaManager;
use App\Fluxy\Entity\Fluxycsv;


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

        $newpath = $request->request->get('newpath');
        
        $tablename = $request->request->get('tablename');

        $postvalue = $request->request;

        $csv_keystab = $this->csvKeys( $this->handle( $newpath ) );
        
        $cles = $csv_keystab;
        
        $cles = $cles[0];
        
        array_pop($cles);


        $query =  $this->loadDataHead($newpath, $tablename);
        
        $query = $this->loadDataRefListing($query, $cles);
         
        $query = $this->loadDataSetting($query, $postvalue);


        $lastid =  "";

        $sql = strtr($query,array( 'Ré'=>'Re', 'é'=>'e', 'à'=>'a', 'ô'=>'o', 'î'=>'i') );
    

        $message = $this->insertLoadFileQuery($sql);


                return new Response (
                
                '<html>
                <head>
                    <title>
                        Fluxy load
                    </title>
                </head>
                <body>
                    <h2>Congratulations it\'s '.$message.'  ...</h2>
                    '.$newpath.'
                    
                    <br><br>
                    <a href="/fluxy/public/">Retour</a> 
                    <a href="/fluxy/public/maj/form/'. $tablename.'">Mettre des champs à jour</a>
                    <br>

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

        $valuequery = "";

        array_walk($cles, $loop=function($cle, $key) use (&$valuequery) {
            
            $valuequery .= '@'.strtr( $cle, array('"'=>'', ' '=>'') ).', '; 
        
        });

        $valuequery = substr( $valuequery, 0, -2 );

        $query .= $valuequery . ")" .chr( 13 ).chr( 13 );

        return $query;
    }


    private function loadDataSetting(&$query, &$post)

    {
        $x=1;

        $postvalue = $post;

        $query .=   " SET " .chr(13);

        foreach ($postvalue as $item) {
            
            if( $postvalue->get("csvref-$x") != "" ) {

                $query .= strtolower( $postvalue->get("tableref-$x") )." = ";
        
                if( $postvalue->get("csvref-$x") == "" ) { 

                    $query .= "'',"; 

                } elseif($x == (count($postvalue)-1)) {
                    
                    $query .= $postvalue->get("csvref-$x") .chr(13); 
                
                } else {

                    $query .= $postvalue->get("csvref-$x").','.chr(13);

                }
            }
            $x++;
        }

        $query = substr( $query, 0, -2 );   

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


    private function insertLoadFileQuery($query) {
        
        $server = "localhost";
        
        $username =  "root";
        
        $password =  "bramskone";
        
        $bdd =  "Fluxy";
        
        $sqlcnx = mysqli_connect($server,$username,$password) or die ("No Connection");
        
        if($sqlcnx) {
        
            mysqli_select_db($sqlcnx, $bdd) or die ( "No Database found!" );
        
            $db = mysqli_query($sqlcnx, $query) or die ("<H4> ERROR ...".mysqli_error($sqlcnx)."</H4>"); 
        
            if( $db ){ return "registred"; }
        }
        
        else return "<h3> ERROR ...".mysqli_error($sqlcnx) ;
    }


}
