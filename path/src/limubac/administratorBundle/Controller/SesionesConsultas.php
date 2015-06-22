<?php
	namespace limubac\administratorBundle\Controller;

	class SesionesConsultas{



		public function iniciarSesion($usr,$ctrñ,$mngr){

			echo "->Entra a la funcion de IniciarSesion ";
			echo $usr;
			echo " ";
			echo $ctrñ;


			$resultado=$mngr->createQuery("SELECT u.nombre as id
				FROM limubacadministratorBundle:CuentasSesion s
				JOIN limubacadministratorBundle:Usuario u
				WITH s.idUsuario = u.idUsuario
				WHERE u.correo='"."{$usr}' AND s.contrasena= '"."{$ctrñ}'");


			return $id=$resultado->getResult();
		}
	}
?>
