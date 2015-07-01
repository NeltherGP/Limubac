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
 			$count=0;
			$request = $this->getRequest();
	        $session = $request->getSession();
	        // obtiene el error de inicio de sesión si lo hay
	        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
	        	//echo "ERROR 1";
	            $error = $request->attributes->get(
	                SecurityContext::AUTHENTICATION_ERROR
	            );
	            echo $error;
	        } else {
	        	//echo "ERROR 2";
	            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
	            //echo ' -'.$error.' - ';
	            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
	            //print_r($error);
	        }
	        //echo "ERROR 3--";
	       print_r($error);
	        
	        	return $this->render(
		            'limubacadministratorBundle:administracion:sesiones.html.twig',
		            array(
		                // último nombre de usuario ingresado
		                //'last_username' => $session->getName(),
		                'error'         => $error
		            )
	        	);
	        
			 			
		}
	
		public function newuserAction(){

			if (isset($_POST["confirmar_password"]) && isset($_POST["nuevo_password"])) {
				$clave1 = $_POST["confirmar_password"];
   				$clave2 = $_POST["nuevo_password"];

   				$validado = "Registro exitoso";
   				$novalidado = "Error, contraseñas no coinciden";

   				if ($clave1 == $clave2) {
   					return $this->render(
		            'limubacadministratorBundle:administracion:adminPanel.html.twig',  array('mensaje' => $validado)
	        		);
		      		alert("Las dos claves son iguales...\nRealizaríamos las acciones del caso positivo"); 
			   	}
			   	else {
			   		return $this->render(
		            'limubacadministratorBundle:administracion:NuevoUsuario.html.twig', array('mensaje' => $novalidado)
	        		);
			      	alert("Las dos claves son distintas...\nRealizaríamos las acciones del caso negativo"); 
			   	}
			}else{
				return $this->render(
		            'limubacadministratorBundle:administracion:NuevoUsuario.html.twig'
	        	);
			}
			
		}

		public function actualizarContrasenaAction(){
			
			return $this->render(
		            'limubacadministratorBundle:administracion:actContrasena.html.twig'
	        	);
		}
			
			
		

		public function logoutAction(){
			return $this->render(
		            'limubacadministratorBundle:administracion:logout.html.twig'
	        	);
		}


		public function miPerfilAction(){
			return $this->render(
		            'limubacadministratorBundle:administracion:perfin.html.twig'
	        	);
		}

		
	}
?>
