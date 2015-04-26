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
  
  function estadisticasEquipo($idTorneo,$idEquipo,$manager){
	$pfe=PFequipo($idTorneo,$idEquipo,$manager);
	$pce=PCequipo($idTorneo,$idEquipo,$manager);
	//$pj=consulta de jesus;
	$pg=0;
	$pp=0;
	for($par=0;$par<$pj;$par++){
		if($pfe[$par]>$pce[$par])$pg++;
		else $pp++;
	}
	$pf=0;
	$pc=0;
	for($par=0;$par<$pj;$par++){
		$pf+=$pfe[$par];
		$pc-=$pce[$par];
	}
	$dif=$pf+$pc;
	$total=($pg*3)+($pp);
	
	$lista=array('pj'=>$pj,'pg'=>$pg,'pp'=>$pp,'pf'=>$pf,'pc'=>$pc,'dif'=>$dif,'total'=>$total);
	
	return $lista;
  
  }
  
  function PFequipo($idTorneo,$idEquipo,$manager){
			$consulta = $manager ->createQuery("select j.resultado from juegan j 
				join partido p on p.id_partido=j.id_partido
				where j.id_equipo={$idEquipo}
				and p.id_torneo={$idTorneo}");
				
			return $list=$consulta->getResult();
	}
  function PCequipo($idTorneo,$idEquipo,$manager){
			$consulta = $manager ->createQuery("select k.resultado from (select p.id_partido from juegan j 
				join partido p on p.id_partido=j.id_partido
				where j.id_equipo={$idEquipo}
				and p.id_torneo={$idTorneo}) AS pa,juegan k
				join partido pr on pr.id_partido=k.id_partido
				where pa.id_partido=pr.id_partido
				and not k.id_equipo={$idEquipo}");
				
			return $list=$consulta->getResult();
	}

}
