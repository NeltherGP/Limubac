<?php
	namespace limubac\administratorBundle\Controller;
	//use limubac\administratorBundle\Resources\views\administracion\sesiones;
	use limubac\administratorBundle\Controller\SesionesConsultas;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\Security\Core\SecurityContext;
	use Symfony\Component\HttpFoundation\Request;
	use limubac\administratorBundle\Form\Type\SesionType; //Nuevo
	use limubac\administratorBundle\Entity\User; //Nuevo
	use Symfony\Component\HttpFoundation\Session\sfAction;
	use Symfony\Component\HttpFoundation\Response;

	//include 'Contacto.php';


	class sessionsController extends Controller{

 		public function logAction(){
 			
			$request = $this->getRequest();
	        $session = $request->getSession();
	        // obtiene el error de inicio de sesión si lo hay
	        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
	        	//echo "ERROR 1";
	            $error = $request->attributes->get(
	                SecurityContext::AUTHENTICATION_ERROR
	            );
	            //echo $error;
	        } else {
	        	//echo "ERROR 2";
	            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
	            //echo ' -'.$error.' - ';
	            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
	            //print_r($error);
	        }
	        //echo "ERROR 3--";
	       //print_r($error);

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
			//$user = new User();
			$validado = "Registro exitoso";
			$novalidado = "Error, contraseñas no coinciden";
			$asunto = "Nuevo usuario: ";
			$correo = "limubac@gmail.com";
			$mensaje = "Existe un nuevo usuario. Cuando gustes puedes marcarlo como activo. Enseguida la información:<br>";

				//$factory = $this->get('security.encoder_factory');
				//$user = new limubac\administratorBundle\Entity\User();
				//$encoder = $factory->getEncoder($user);
				





			if (isset($_POST["confirmar_password"]) && isset($_POST["nuevo_password"])) {
				$clave1 = $_POST["confirmar_password"];
   				$clave2 = $_POST["nuevo_password"];
   				if ($clave1 == $clave2) {
   					$nombre = $_POST["nombre_nuevo"];
   					$telefono = $_POST["telefono_nuevo"];
   					$correo = $_POST["correo_nuevo"];
   					$direccion = $_POST["direccion_nuevo"];
   					$rol = $_POST["seleccion_nuevo"];
   					$mensajeAdicional = $_POST["mensaje_nuevo"];
   					$mensaje=$mensaje."Nombre: ".$nombre." <br>Telefono: ".$telefono." <br>Direccion: ".$direccion." <br>Correo: ".$correo." <br>Clave: ".$clave2." <br>Rol deseado: ".$rol." <br>Mensaje adicional: ".$mensajeAdicional;
   					
$factory = $this->get('security.encoder_factory');
$user = new User();

$encoder = $factory->getEncoder($user);
$password = $encoder->encodePassword($clave1, $user->getSalt());
$user->setPassword($password);


   					$user->setUsername($nombre);
   					$password = $encoder->encodePassword($clave1, $user->getSalt());
   					//$password=$clave1;
					//$user->setPassword($clave1);
					//$user->setSalt("");
					$user->setRole("ROLE_ADMIN");
					$user->setName($nombre);
					$user->setAddress($direccion);
					$user->setPhone($telefono);
					$user->setEmail($correo);
					$user->setIsactive(true);



   					//$user->setUsuariolim($_POST["correo_nuevo"]);
   					//$user->setCorreolim($_POST["correo_nuevo"]);
   					//$user->setContrasenalim($_POST["confirmar_password"]);
   					$em = $this->getDoctrine()->getManager();
   					$em->persist($user);
   					$em->flush();

   					//enviaCorreo( $asunto, $correo , $mensaje, $this);
   					return $this->render(
		            'limubacadministratorBundle:administracion:adminPanel.html.twig',  array('mensaje' => $validado)
	        		);
		      		//alert("Las dos claves son iguales...\nRealizaríamos las acciones del caso positivo");

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
			$cancelar = "Cancelar y regresar";

			if ( isset($_POST["actual_password"]) && isset($_POST["nuevo_password"]) && isset($_POST["confirmar_password"]) ) {
				$actual = $_POST["actual_password"];
   				$clave1 = $_POST["nuevo_password"];
   				$clave2 = $_POST["confirmar_password"];
   				$validado = "Cambio exitoso. Usa tu nueva contraseña la proxima ves que inicies sesión";
   				$novalidado = "Error de actualización, las contraseñas no coinciden";

   				if ($clave1 == $clave2) {

   					//CODIGO PARA ACTUALIZAR LA ACTUALIZACION DE LA CONTRASEÑA
   					return $this->render(
		            'limubacadministratorBundle:administracion:perfin.html.twig',  array('mensaje' => $validado)
	        		);
		      		//alert("Las dos claves son iguales...\nRealizaríamos las acciones del caso positivo");
			   	}
			   	else {

			   		//
			   		return $this->render(
		            'limubacadministratorBundle:administracion:actContrasena.html.twig', array('mensaje' => $novalidado)
	        		);
			      	//alert("Las dos claves son distintas...\nRealizaríamos las acciones del caso negativo");
			   	}
			}else{

				return $this->render(
		            'limubacadministratorBundle:administracion:actContrasena.html.twig',  array('cancelar' => $cancelar)

	        	);
			}
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
		public function actualizarInformacionAction(){
			$cancelar="Cancelar y regresar";
			$correcto = "Información actualizada";
			$error1= "Compruebe el numero telefonico por favor, debe ser parecido a (012)345 6789";
			$nombre = "busqueda pendiente";
			$correo = "algo@algo.com";
			$direccion = "busqueda pendiente";
			$telefono = "461-123-4567";
			if (isset($_POST["correo"]) && isset($_POST["direccion"]) && isset($_POST["telefono"])) {
				$expresion = '/^([0-9]{3})(-)([0-9]{3})(-)([0-9]{4})$/';
				$correo = $_POST["correo"];
				$direccion = $_POST["direccion"];
				$telefono = $_POST["telefono"];
/*
				if (!preg_match($expresion, $telefono)) {
					return $this->render(
		            'limubacadministratorBundle:administracion:actInformacion.html.twig',
		            array('mensaje' => $error1,
		            	'nombre_actual' => $nombre,
		            	'correo_actual' => $correo,
		            	'direccion_actual' => $direccion,
		            	'telefono_actual'=> $telefono)

	        		);
				}
*/
				//CODIGO DE ACTUALIZACION AQUI
				return $this->render(
		            'limubacadministratorBundle:administracion:perfin.html.twig',
		            array('mensaje' => $correcto

		            	)

	        	);
			}else{
				return $this->render(
		            'limubacadministratorBundle:administracion:actInformacion.html.twig',
		            array(
		            	'nombre_actual' => $nombre,
		            	'correo_actual' => $correo,
		            	'direccion_actual' => $direccion,
		            	'telefono_actual'=> $telefono,
		            	'cancelar' => $cancelar
		            	)
	        	);
			}
		}
		public function contactoAction(){
			$correcto = "Mensaje enviado. Recibirás en tu correo una copia del mensaje, si no la recibes, favor de ponerte en contacto por otro medio.";

			$name = "nothing";


			if (isset($_POST["correo"]) && isset($_POST["asunto"]) && isset($_POST["mensaje"])) {
				$asunto= $_POST["asunto"];
				$correo = $_POST["correo"];
				$mensaje = $_POST["mensaje"];
				$nombre = $_POST["nombre"];
				$mensaje= $nombre." dice: \n".$mensaje;
				enviaCorreo( $asunto, $correo , $mensaje, $this);
				    


				return $this->render(
		            'limubacadministratorBundle:administracion:adminPanel.html.twig',
		            array('mensaje' => $correcto

		            	)

	        	);
				# code...
			}else{
				$cancelar="Cancelar y regresar";
				return $this->render(
		            'limubacadministratorBundle:administracion:Contacto.html.twig',
		            array('cancelar' => $cancelar)

	        	);
			}

		}

	}
?>
