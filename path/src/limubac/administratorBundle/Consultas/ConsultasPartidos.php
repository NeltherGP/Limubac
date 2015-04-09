<?php

namespace limubac\administratorBundle\consultas;

class ConsultasPartidos{

  function listPartidosByTorneo($idTorneo,$manager){

  		$consulta = $manager ->createQuery('SELECT pa.idPartido, e.nombre, j.side from limubacadministratorBundle:Equipo e
  											    join  limubacadministratorBundle:Juegan j with e.idEquipo=j.idEquipo
  											        join limubacadministratorBundle:ParticipanT p with p.idEquipo=e.idEquipo
  											                    join limubacadministratorBundle:Partido pa with pa.idPartido=j.idPartido
  											            where pa.idTorneo='.$idTorneo);

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



}
