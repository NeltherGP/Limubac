<?php

//definido un jugador y un equipo averigua si es apto para inscribirlo
	function restringe($equipo,$torneo,$controlador){
		$em = $controlador->getDoctrine()->getManager();
		$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i where i.idEquipo='.$equipo->getIdEquipo());
		$Jugadores = $query->getResult();
		
		switch($equipo->getIdCategoria()->getIdCategoria()){//Variables que se necesitan dependiendo la categoria del equipo
			case 1:
				$JugadoresEnSegunda=0;
				
				//No Jugadores de Tercera en Primera, NI UNO!!!!
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Equipo e with i.idEquipo=e.idEquipo
join limubacadministratorBundle:ParticipanT p with i.idEquipo = p.idEquipo where e.idCategoria=3 and p.idTorneo='.$torneo->getIdTorneo());
				$resultado = $query->getResult();
				if(count($resultado)>0){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes al menos un Jugador en Tercera Fuerza');</script>";
					return false;
				}
				
			break;
			case 2:
				$JugadoresEnPrimera=0;  
			break;
			case 3:
				//No Jugadores de Primera en Tercera, NI UNO!!!!
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Equipo e with i.idEquipo=e.idEquipo
join limubacadministratorBundle:ParticipanT p with i.idEquipo = p.idEquipo where e.idCategoria=1 and p.idTorneo='.$torneo->getIdTorneo());
				$resultado = $query->getResult();
				if(count($resultado)>0){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes al menos un Jugador en Primera Fuerza');</script>";
					return false;
				}
			break;
			case 4://Estudiantil
				//Solamente Jugadores menores de 22 años
				$dia = date("d");
				$mes = date("m");
				$year= date("Y");
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Jugador j 
with i.idJugador = j.idJugador where i.idEquipo='.$equipo->getIdEquipo().' and j.fNacimiento<'.$dia."-".$mes."-".($year-22));
				$resultado = $query->getResult();
				
				//echo 'Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Jugador j 
//with i.idJugador = j.idJugador where i.idEquipo='.$equipo->getIdEquipo().' and j.fNacimiento<'.$dia."-".$mes."-".($year-22);
				
				if(count($resultado)>0){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes al menos un jugador mayor de 22 años');</script>";
					return false;
				}
				
			break;
			case 5://Femenil
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Jugador j 
with i.idJugador = j.idJugador where i.idEquipo='.$equipo->getIdEquipo()." and j.idGenero=1");
				$resultado = $query->getResult();
				
				if(count($resultado)>0){//Si hay al menos un hombre en el equipo
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes al menos un hombre en el equipo');</script>";
					return false;
				}
			break;
			case 6://Unicamente mayores de 35 años
				$dia = date("d");
				$mes = date("m");
				$year= date("Y");
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Jugador j 
with i.idJugador = j.idJugador where i.idEquipo='.$equipo->getIdEquipo()." and j.fNacimiento>".$dia."-".$mes."-".($year-35));
				$resultado = $query->getResult();
				
				if(count($resultado)>0){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes al menos un jugador menor de 35 años');</script>";
					return false;
				}
				
			break;
		}
		
		foreach($Jugadores as $Jugador){ // Buscando Jugadores que incumplen
		
			//Si el jugador tiene menos de 18 años a la mierda las restricciones
			$repositorio = $controlador->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$temporal = $repositorio->find($Jugador[1]);
			
			$hoy = new DateTime(date("Y/m/d"));
			$diferencia = $temporal->getFNacimiento()->diff($hoy);
			$años = $diferencia->y;
			
		if($años>=18){
			
			switch($equipo->getIdCategoria()->getIdCategoria()){
			case 1:
				//El jugadore juega en Segunda? ->
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Equipo e with i.idEquipo=e.idEquipo
join limubacadministratorBundle:ParticipanT p with i.idEquipo = p.idEquipo where e.idCategoria=2 and i.idJugador='.$Jugador[1].' and p.idTorneo='.$torneo->getIdTorneo());
				$resultado = $query->getResult();
				if(count($resultado)!=0){
					$JugadoresEnSegunda++;
				}
			break;
			case 2:
				//El jugadore juega en primera? ->
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Equipo e with i.idEquipo=e.idEquipo
join limubacadministratorBundle:ParticipanT p with i.idEquipo = p.idEquipo where e.idCategoria=1 and i.idJugador='.$Jugador[1].' and p.idTorneo='.$torneo->getIdTorneo());
				$resultado = $query->getResult();
				if(count($resultado)!=0){
					$JugadoresEnPrimera++;
				}
			break;
			case 3:
			break;
			
			}
		}//fin años
		
		}
		
		//Resultados Registrar o no Registrar
		switch($equipo->getIdCategoria()->getIdCategoria()){
			case 1:
			if($JugadoresEnSegunda>3){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes mas de 3 jugadores que ya estan registrados en segunda categoria');</script>";
					return false;
				}
			break;
			case 2:
				if($JugadoresEnPrimera>3){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo debido a que tienes mas de 3 jugadores que ya estan registrados en primera categoria');</script>";
					return false;
				}
				
			break;
			case 3:
			
			break;
		}
		
		return true;
	}
	
	
	function activandoModificacion($idEquipo,$controlador){
		$Manager = $controlador->getDoctrine()->getManager();
		$query = $Manager->createQuery("UPDATE limubac\administratorBundle\Entity\Equipo as e SET e.modificable=1 where e.idEquipo=".$idEquipo);
		$query->getResult();
		echo "<script type='text/javascript'>alert('Se Enviara un correo al dueño del equipo avisandole del permiso');</script>";
		$body = 'Esta es una respuesta automática confirmando que ahora dispone de permiso para modificar el equipo <b>'.$Equipo.'</b>';
		$asunto= 'Ahora puede modificar el equipo';
		enviaCorreo($asunto,$destino,$body,$controlador);
	}
	
	function enviaCorreo($asunto,$destino,$body,$controlador){
		$mailer = $controlador->get('mailer');
		$message = $mailer->createMessage()
        ->setSubject($asunto)
        ->setFrom('Limubac@gmail.com')
		->setCC('Limubac@gmail.com')
        ->setTo($destino)
        ->setBody($body,'text/html')
    ;
    $mailer->send($message);	
	}
?>