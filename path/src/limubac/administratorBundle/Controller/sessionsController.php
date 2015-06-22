<?php
	namespace limubac\administratorBundle\Controller;

	//use limubac\administratorBundle\Resources\views\administracion\sesiones;

	use limubac\administratorBundle\Controller\SesionesConsultas;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\Security\Core\SecurityContext;
	use Symfony\Component\HttpFoundation\Request;
	use limubac\administratorBundle\Form\Type\SesionType; //Nuevo
	use limubac\administratorBundle\Entity\Userlim; //Nuevo
	use Symfony\Component\HttpFoundation\Session\sfAction;


	class sessionsController extends Controller{




		public function logAction(){
			$request = $this->getRequest();
	        $session = $request->getSession();

	        // obtiene el error de inicio de sesión si lo hay
	        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
	        	echo "ERROR 1";
	            $error = $request->attributes->get(
	                SecurityContext::AUTHENTICATION_ERROR
	            );
	            echo $error;
	        } else {
	        	echo "ERROR 2";
	            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
	            //echo ' -'.$error.' - ';
	            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
	            //print_r($error);
	        }

	        echo "ERROR 3";
	        //print_r($session);

	        //var_dump($session->get(SecurityContext::LAST_USERNAME));

	        	return $this->render(
		            'limubacadministratorBundle:administracion:sesiones.html.twig',
		            array(
		                // último nombre de usuario ingresado
		                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
		                'error'         => $error
		            )
	        	);

	        
		}
}




?>
