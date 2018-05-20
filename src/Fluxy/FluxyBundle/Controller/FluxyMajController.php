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

class FluxyMajController extends FOSRestController
{

	/**
     * @Get(
     *     path = "/maj/form/{table}",
     *     name = "fluxy_maj_form"
     *	   
     * )
     * 
     */
    public function formAction(Request $request)
    {	
    	$table= $request->get('table');

        return $this->render('fluxy/csv/majform.html.twig', array(
        	'tablename'=>$request->get('table'),
        	)
        );
	}



	 /**
     * @Get(
     *     path = "/maj/{champ}/{table}",
     *     name = "fluxy_maj",
     *     requirements = {"champ", "table"}
     * )
     * 
     */
    public function majAction(Request $request)
    {	
        $i=0;

		if( $request->get('champ') !== null ) {
			
			$champ =$request->get('champ');
			
			$table = $request->get('table');
        	
        	$em = $this->getDoctrine()->getManager ();
            
        	$repository = $em->getRepository("App:".ucfirst($table));

        	$values = $repository->findByDistinctFields($champ);
 
        	$mytab =array();
        	

        	foreach ($values as $key => $value) {
        		$mytab[] = $value;
        		
        	} 

			return new JsonResponse($mytab);
        }
	}



	/**
     * @Get(
     *     path = "/domaj/{field}/{valtomodify}/{newval}/{table}",
     *     name = "fluxy_domaj",
     *     requirements = {"field", "valtomodify", "newval", "table"}
     * )
     * 
     */
	public function domajAction(Request $request) {

		if($request->get('field') && $request->get('valtomodify') && $request->get('newval')  && $request->get('table')  ) {

			$table = $request->get('table');
			
			$field = $request->get('field');
			
			$oldval = $request->get('valtomodify');
			
			$newval = $request->get('newval');        
			
            $em = $this->getDoctrine()->getManager ();
            
            $repository = $em->getRepository("App:".ucfirst($table));

            $update = $repository->updateFields($field, $oldval, $newval);
          //  


           if($update) {
                $values = $repository->findByDistinctFields($field);
                //var_dump($values);

    	        return new JsonResponse(array('updated'));

            } else {
                return new JsonResponse(array('ERROR'));
            }
		}

	}

}