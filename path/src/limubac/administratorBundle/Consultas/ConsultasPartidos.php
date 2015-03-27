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

}
