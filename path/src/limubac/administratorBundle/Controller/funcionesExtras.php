<?php

//definido un jugador y un equipo averigua si es apto para inscribirlo
	function restringe($equipo,$torneo,$controlador){
		$em = $controlador->getDoctrine()->getManager();
		$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i where i.idEquipo='.$equipo->getIdEquipo());
		$Jugadores = $query->getResult();
		
		switch($equipo->getIdCategoria()->getIdCategoria()){//Variables que se necesitan dependiendo la categoria del equipo
			case 1:
			break;
			case 2:
				$JugadoresEnPrimera=0;  
			break;
			case 3:
			break;
			
		}
		
		foreach($Jugadores as $Jugador){
			//var_dump($Jugador);
			switch($equipo->getIdCategoria()->getIdCategoria()){
			case 1:
			break;
			case 2:
				//El jugadore juega en primera? ->
				$query =$em->createQuery('Select IDENTITY(i.idJugador) FROM limubacadministratorBundle:Integra i join limubacadministratorBundle:Equipo e with i.idEquipo=e.idEquipo
join limubacadministratorBundle:ParticipanT p with i.idEquipo = p.idEquipo where i.idJugador='.$Jugador[1].' and p.idTorneo='.$torneo->getIdTorneo());
				$resultado = $query->getResult();
				if(count($resultado)!=0){
					$JugadoresEnPrimera++;
				}
				
				
				echo "-";
			break;
			case 3:
			break;
			
			}
		}
		
		//Resultados Registrar o no Registrar
		switch($equipo->getIdCategoria()->getIdCategoria()){
			case 1:
			break;
			case 2:
				if($JugadoresEnPrimera>3){
					echo "<script type='text/javascript'>alert('No puede Registrarse el equipo, ');</script>";
					return false;
				}
				
			break;
		}
		
		echo $JugadoresEnPrimera;
		
		return true;
	}

?>