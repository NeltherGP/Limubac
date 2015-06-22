<?php
	namespace limubac\administratorBundle\Controller;

	use limubac\administratorBundle\Controller\SesionesConsultas;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\Request;
	use limubac\administratorBundle\Form\Type\SesionType; //Nuevo
	use limubac\administratorBundle\Entity\Userlim; //Nuevo



	class sessionsController extends Controller{


		public function sexAction(){
			$sesion=new Session();

			$consultas = new SesionesConsultas();
			$mngr = $this->getDoctrine()->getManager();
			$request = $this->getRequest();
			$usuario=$_POST['user_nuevo'];
			$contrasena=md5($_POST['password_nuevo']);

			if ($request->getMethod()=='POST') {
				echo "->request";
				if (isset($_POST['user_nuevo'])) {

					echo "->isset usuario ";
					echo $usuario;

					if (isset($_POST['password_nuevo'])) {
						echo "->isset password ";
						echo $contrasena;

						$arreglo=$consultas->iniciarSesion($usuario,$contrasena,$mngr);

						print_R($arreglo);

						if (count($arreglo) > 0) {
							echo "->inicia sesion->";
							print_r ($arreglo);
							echo "->";
							$sesion->set('user',$arreglo);
							$sesion->set('pwd',$contrasena);
							
							$sesion->getFlashBag()->add('notice','Bienvenido');

						}else{
							$sesion->getFlashBag()->add('notice','Falla de usuario y contraseña, favor de revisar');
							echo "->Error de usuario->";
						}

					}else{
						$sesion->getFlashBag()->add('notice','Ingrese la contraseña por favor');
					}
				}else{
					$sesion->getFlashBag()->add('notice','Ingrese el usuario por favor');
				}

				//echo md5("1234");

				return $this->render('limubacadministratorBundle:administracion:sesiones.html.twig');
			}else{


				return $this->render('limubacadministratorBundle:administracion:sesiones.html.twig');
			}


		}
	}





?>
