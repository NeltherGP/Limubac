<?php

namespace limubac\administratorBundle\consultas;

class ConsultasAnotaciones{

	function getIdJugadorByPlayera ($playera,$side,$manager){

		$consulta = $manager ->createQuery('SELECT IDENTITY(i.idJugador) as id, e.idEquipo
		FROM limubacadministratorBundle:Juegan j
		JOIN limubacadministratorBundle:Equipo e
		WITH j.idEquipo = e.idEquipo
		JOIN limubacadministratorBundle:Integra i
		WITH i.idEquipo=j.idEquipo
		JOIN limubacadministratorBundle:Jugador p WITH p.idJugador = i.idJugador
		where i.noPlayera= '. $playera. " and j.side ='".$side."'");
		return $id = $consulta->getResult();

	}

	function getEquipoByPartido($idPartido,$manager){
		//echo $idPartido;
		$consulta = $manager ->createQuery('SELECT e.nombre, j.side, r.nombre as rama, c.nombre as categoria, s.nombre as lugar, t.nombre as torneo, IDENTITY(j.idPartido) as idpartido from limubacadministratorBundle:Equipo e
		join  limubacadministratorBundle:Juegan j with e.idEquipo=j.idEquipo
		join limubacadministratorBundle:ParticipanT p with p.idEquipo=e.idEquipo
		join limubacadministratorBundle:RamaEquipo r with r.idRama = p.idRama
		join limubacadministratorBundle:Categoria c with c.idCategoria=p.idCategoria
		join limubacadministratorBundle:Partido pa with pa.idPartido=j.idPartido
		join limubacadministratorBundle:Sede s with s.idSede=pa.idSede
		join limubacadministratorBundle:Torneo t with t.idTorneo=pa.idTorneo
		where j.idPartido='.$idPartido);
		/*$consulta = $manager ->createQuery('SELECT e.nombre, j.side from limubacadministratorBundle:Equipo e
		join limubacadministratorBundle:Juegan j with e.idEquipo=j.idEquipo
		where j.idPartido='.$idPartido);*/
		return $consulta->getResult();
	}

	function updateCantidadAnotacion($idJugador,$idPartido,$idAnotacion,$suma,$manager){
		$consulta= $manager->createQuery('UPDATE limubacadministratorBundle:DetallePartido d  set d.cantidad = d.cantidad+'.$suma.'
		where d.idJugador='.$idJugador.'AND d.idPartido='.$idPartido.'AND d.anotaciones='.$idAnotacion);
		$Updated = $consulta->execute();
	}

	function listJugadoresEquipo($side,$idPartido,$manager){
		$consulta= $manager->createQuery("SELECT  p.nombre,p.apPaterno, p.apMaterno, p.idJugador FROM limubacadministratorBundle:Juegan j
		JOIN limubacadministratorBundle:Equipo e WITH j.idEquipo= e.idEquipo
		JOIN limubacadministratorBundle:Integra i  WITH e.idEquipo=i.idEquipo
		JOIN limubacadministratorBundle:Jugador p WITH i.idJugador=p.idJugador
		WHERE j.idPartido=".$idPartido."AND j.side='".$side."'") ;

		return $consulta->getResult();

	}

	function GetIdEquipoByIdJugadorAndIdPartido($idJugador,$idPartido,$manager){
		$consulta= $manager->createQuery("SELECT IDENTITY(j.idEquipo) FROM limubacadministratorBundle:Juegan j
		JOIN limubacadministratorBundle:Integra i  WITH j.idEquipo=i.idEquipo
		WHERE j.idPartido=".$idPartido."AND i.idJugador=".$idJugador) ;

		return $consulta->getResult();

	}

	function updateCantidadFalta($idJugador,$idPartido,$idFalta,$idEquipo,$manager){
		$consulta= $manager->createQuery('UPDATE limubacadministratorBundle:FaltasEquipo f set f.tiempo=f.tiempo+1
		WHERE f.idFalta ='.$idFalta.' AND f.idJugador = '.$idJugador.'
		AND f.idPartido='.$idPartido.' AND f.idEquipo ='.$idEquipo);
		$Updated = $consulta->execute();
	}

	function commitPartidoById($idPartido,$manager){
		$consulta= $manager->createQuery("UPDATE limubacadministratorBundle:Partido p set p.commited = 1
		WHERE p.idPartido=".$idPartido);
		$Updated = $consulta->execute();
	}


}


/*$repository = $this->getDoctrine()->getManager();
$queryDetalleList = $repository->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
FROM limubacadministratorBundle:DetallePartido d');
$update=$queryDetalleList->getResult();

print_r($update);

$em = $this -> getDoctrine()->getManager();
$q = $em->createQuery('UPDATE limubacadministratorBundle:DetallePartido d  set d.anotaciones = 1 where d.idJugador=12');
$numUpdated = $q->execute();*/
