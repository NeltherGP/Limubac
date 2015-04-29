<?php

namespace limubac\administratorBundle\consultas;

class ConsultasPartidos{

  function listPartidosByTorneo($idTorneo,$manager,$jornada){

  		$consulta = $manager ->createQuery('SELECT pa.idPartido, e.nombre, j.side, pa.jornada, IDENTITY(pa.idTorneo) as torneo, j.resultado, j.primero, j.segundo, j.tercero, j.cuarto from limubacadministratorBundle:Equipo e
  											    join  limubacadministratorBundle:Juegan j with e.idEquipo=j.idEquipo

  											                    join limubacadministratorBundle:Partido pa with pa.idPartido=j.idPartido
  											            where pa.idTorneo='.$idTorneo . 'AND pa.jornada= '.$jornada);

  		return $list=$consulta->getResult();
  }

  function listEquiposByTorneo($idTorneo,$manager){

  		$consulta = $manager ->createQuery("SELECT  e.nombre AS equipo, c.nombre AS categoria
                FROM limubacadministratorBundle:ParticipanT p
                INNER JOIN limubacadministratorBundle:Equipo e  WITH p.idEquipo=e.idEquipo
                INNER JOIN limubacadministratorBundle:Torneo t  WITH p.idTorneo=t.idTorneo
				INNER JOIN limubacadministratorBundle:Categoria c  WITH p.idCategoria=c.idCategoria
                WHERE p.idTorneo={$idTorneo}
				ORDER BY c.idCategoria");

  		return $list=$consulta->getResult();
  }

  function listEqCategoriaByTorneo($idTorneo,$manager){
    $consulta = $manager ->createQuery("SELECT  e.idEquipo, e.nombre AS equipo, c.nombre AS categoria
                FROM limubacadministratorBundle:ParticipanT p
                INNER JOIN limubacadministratorBundle:Equipo e  WITH p.idEquipo=e.idEquipo
                INNER JOIN limubacadministratorBundle:Torneo t  WITH p.idTorneo=t.idTorneo
		            INNER JOIN limubacadministratorBundle:Categoria c  WITH p.idCategoria=c.idCategoria
                WHERE p.idTorneo={$idTorneo}");
    return $consulta->getResult();
  }

  function estadisticasEquipo($idTorneo,$idEquipo,$manager){
	$pfe=$this->PFequipo($idTorneo,$idEquipo,$manager);
	$pce=$this->PCequipo($idTorneo,$idEquipo,$manager);
	$consulta = $manager ->createQuery("SELECT COUNT(j.idPartido)
		FROM limubacadministratorBundle:Juegan j
		INNER JOIN limubacadministratorBundle:Partido p WITH j.idPartido=p.idPartido
		WHERE j.idEquipo={$idEquipo} and p.idTorneo={$idTorneo}");

	$pj=$consulta->getResult();
  $pj=$pj[0][1];
	$pg=0;
	$pp=0;

	for($par=0;$par<$pj;$par++){
		if($pfe[$par]>$pce[$par])$pg++;
		else $pp++;
	}
	$pf=0;
	$pc=0;
	for($par=0;$par<$pj;$par++){
		$pf+=$pfe[$par]['resultado'];
		$pc-=$pce[$par]['resultado'];
	}
	$dif=$pf+$pc;
	$total=($pg*3)+($pp);

	$lista=array('pj'=>$pj,'pg'=>$pg,'pp'=>$pp,'pf'=>$pf,'pc'=>$pc,'dif'=>$dif,'total'=>$total);

	return $lista;

  }

  function PFequipo($idTorneo,$idEquipo,$manager){
			$consulta = $manager ->createQuery("select j.resultado from limubacadministratorBundle:Juegan j
				join limubacadministratorBundle:Partido p with p.idPartido=j.idPartido
				where j.idEquipo={$idEquipo}
				and p.idTorneo={$idTorneo}");

			return $list=$consulta->getResult();
	}
  function PCequipo($idTorneo,$idEquipo,$manager){
    			$consulta = $manager ->createQuery("select k.resultado from limubacadministratorBundle:Juegan k
          				join limubacadministratorBundle:Partido pr with pr.idPartido=k.idPartido
          				where pr.idPartido in (select p.idPartido from limubacadministratorBundle:Juegan j
          				join limubacadministratorBundle:Partido p with p.idPartido=j.idPartido
          				where j.idEquipo={$idEquipo}
          				and p.idTorneo={$idTorneo})
          				and not k.idEquipo={$idEquipo}");

			return $list=$consulta->getResult();
	}

  function listCanchas($manager){
    $consulta=$manager->createQuery('SELECT s.idSede, s.nombre from limubacadministratorBundle:Sede s');
    return $consulta->getResult();
  }
  function listArbitros($manager){
    $consulta=$manager->createQuery('SELECT a.idArbitro, a.nombre from limubacadministratorBundle:Arbitro a');
    return $consulta->getResult();
  }

  function getArbitranByIdArbitros($a1,$a2,$a3,$manager){
    $consulta=$manager->createQuery('SELECT a.idArbitran from limubacadministratorBundle:Arbitran a where a.idArbitro1= '.$a1.' and a.idArbitro2= '.$a2.' and a.idArbitro3= '.$a3);
    return $consulta->getResult();
  }

  function updateArbitranByPartido($idArbitran,$idPartido,$manager){
    print_r($idArbitran);
    $consulta=$manager->createQuery('UPDATE limubacadministratorBundle:Partido p set p.idArbitran = '.$idArbitran[0]['idArbitran'] .' where p.idPartido= '.$idPartido);
     $consulta->execute();
  }

  function updateSedebyPartido($idSede,$idPartido,$manager){
    $consulta=$manager->createQuery('UPDATE limubacadministratorBundle:Partido p set p.idSede = '.$idSede .' where p.idPartido= '.$idPartido);
    $consulta->execute();
  }

  function listPartidosCompletos($manager){//filtrar por torneo???
    $consulta=$manager->createQuery('SELECT p.idPartido from limubacadministratorBundle:Partido p  where p.idArbitran is not null and p.idSede is not null');
    return $consulta->getResult();
  }

  function listTorneos($manager){
    $consulta=$manager->createQuery('SELECT T.nombre, T.idTorneo from limubacadministratorBundle:Torneo T ');
    return $consulta->getResult();
  }

  function getCantidadJornadasbyTorneo($idTorneo,$manager){
    $consulta=$manager->createQuery('SELECT MAX(p.jornada) from limubacadministratorBundle:Partido p where p.idTorneo='.$idTorneo);

    return $consulta->getResult();
  }




}
