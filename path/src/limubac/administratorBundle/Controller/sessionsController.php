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

	include 'Contacto.php';


	class sessionsController extends Controller{
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------INICIAR SESION-----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
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

//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------NUEVO USUARIO -----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------

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
   					$curp = $_POST["curp_nuevo"];
   					$mensajeAdicional = $_POST["mensaje_nuevo"];
   					$mensaje=$mensaje."Nombre: ".$nombre." <br>Telefono: ".$telefono." <br>Direccion: ".$direccion." <br>Correo: ".$correo." <br>Clave: ".$clave2." <br>Rol deseado: ".$rol." <br>Mensaje adicional: ".$mensajeAdicional;
   				
$user = new User();

$factory = $this->get('security.encoder_factory');
$encoder = $factory->getEncoder($user);
$password = $encoder->encodePassword($clave1, $user->getSalt());

// ESTE METODO SOLO FUNCIONA A PARTIR DE SYMFONY 2.6
//$encoder = $this->container->get('security.password_encoder');
//$encoded = $encoder->encodePassword($user, $clave2);
//$user->setPassword($encoded);



   					$user->setUsername($curp);

   					$user->setPassword($password);
   					//$user->setPassword($clave1);
   					//$user->setPassword(md5($clave1));
   					
					
					//$user->setSalt("");


					switch ($rol) {
						case 'Representante de equipo':
							$user->setRoles("ROLE_USER");
							break;
						case 'Comité administrativo':
							$user->setRoles("ROLE_FINANZAS");
							break;
						case 'Comité de disciplina':
							$user->setRoles("ROLE_DISCIPLINA");
							break;
						case 'Capturista':
							$user->setRoles("ROLE_CAPTURISTA");
							break;
						case 'Otros':
							$user->setRoles("ROLE_OTRO");
							break;
						
						default:
							# code...
							break;
					}

						





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

   					enviaCorreo( $asunto, $correo , $mensaje, $this);
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
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------ACTUALIZAR CONTRASEÑA -----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
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
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------CERRAR SESION -----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------

		public function logoutAction(){
			return $this->render(
		            'limubacadministratorBundle:administracion:logout.html.twig'
	        	);
		}
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------VER MI PERFIL-----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
		public function miPerfilAction(){
			return $this->render(
		            'limubacadministratorBundle:administracion:perfin.html.twig'
	        	);
		}
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------ACTUALIZAR INFORMACION-----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
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
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------CONTACTO -----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
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
//------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------VER USUARIOS -----------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------
		public function showUserAction(){

	        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:User');
	        $queryTorneos = $repository->createQueryBuilder('u')
            ->select('u.name','u.username','u.email','u.phone','u.address','u.roles','u.isActive')
            ->orderBy('u.name', 'DESC')
            ->getQuery();
	        $entities = $queryTorneos->getResult();
	        return $this->render('limubacadministratorBundle:administracion:mostrarUsuarios.html.twig',array('entities' => $entities));

		}


	}
?>
