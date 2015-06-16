<?php

//definido un jugador y un equipo averigua si es apto para inscribirlo
	function restringe($equipo,$Jugador,$controlador){
		$repositorio = $controlador->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
		$query = $repositorio->createQueryBuilder('p')
			->select('IDENTITY(p.idCategoria)')
			->where('p.idEquipo ='.$equipo->getIdEquipo())
			->getQuery();
		$Participan = $query->getResult();
		//var_dump($Participan[0][1]);
		
		switch($Participan[0][1]){//Las categorias estan en la tabla categoria de la base de datos
			case 1: //Primera Fuerza
				return true;
				break;
			case 2: //Segunda Fuerza
				//cuantos juagdores del equipo estan tambien en primra fuerza
				
				$repositorio = $controlador->getDoctrine()->getRepository("limubacadministratorBundle:Integra");
				$query = $repositorio->createQueryBuilder('i')
					->select('IDENTITY(i.idJugador)')
					->join('limubacadministratorBundle:ParticipanT', 'P', 'WITH' ,'P.idEquipo = i.idEquipo')
					->groupBy('i.idEquipo')
					->getQuery();
				$Jugadores = $query->getResult();
				
				var_dump($Jugadores);
				
				
				break;
			case 3: //Tercera Fuerza
				
				break;
			case 4: //Estudiantil
				break;
			case 5: //Femenil
				break;
			case 6: //Maxibasket
				break;
			case 7: //Femenil
				break;
			case 8: //Maxibasket
				break;
		}
		return true;
	}

?>