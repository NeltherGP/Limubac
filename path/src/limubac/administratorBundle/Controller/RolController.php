<?php
//CONTROLADOR ROL DE JUEGOS
namespace limubac\administratorBundle\Controller;
		use Symfony\Bundle\FrameworkBundle\Controller\Controller;
		use limubac\administratorBundle\Entity\Equipo;
		use limubac\administratorBundle\Entity\Torneo;
		use limubac\administratorBundle\Entity\Categoria;
		use limubac\administratorBundle\Entity\ParticipanT;
		use limubac\administratorBundle\Entity\Integra;
		use limubac\administratorBundle\Entity\Jugador;
		use limubac\administratorBundle\Entity\Partido;
		use limubac\administratorBundle\Entity\Juegan;
		use limubac\administratorBundle\Entity\TipoSanguineo;
		use limubac\administratorBundle\Entity\DetallePartido;
		use limubac\administratorBundle\Entity\FaltasEquipo;
		use limubac\administratorBundle\Entity\Faltas;
		use limubac\administratorBundle\Entity\Asistencia;
		use limubac\administratorBundle\Form\Type\JugadorType;
		use limubac\administratorBundle\Form\Type\JugadorAType;
		use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
		use Symfony\Component\Validator\Constraints\DateTime;



class RolController extends Controller{
	
	public function addrolAction(){ 
	$contJ=1;
	if($_REQUEST['rol'][0]!=0&&$_REQUEST['categoria'][0]!=0&&$_REQUEST['rama'][0]!=0){
	$torn=$_REQUEST['rol'][0];
	$cate=$_REQUEST['categoria'][0];
	$rama=$_REQUEST['rama'][0];
	$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
			$queryPartido = $repository->createQueryBuilder('h')
				->select('max(h.jornada)')
				->where('h.idTorneo = :torn')
				->setParameter('torn',$torn)
				->getQuery();
		$jornada =	$queryPartido->getResult();
		print_r($jornada);
		$jorn=$jornada[0][1]+1;
		
	$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:ParticipanT');
		$queryCuantEqui = $repository->createQueryBuilder('h')
            ->select('count(h.idRegistro)')
            ->where('h.idTorneo = :torn')
			->andWhere('h.idCategoria = :cate')
			->andWhere('h.idRama = :rama')
			->setParameter('torn',$torn)
			->setParameter('cate',$cate)
			->setParameter('rama',$rama)
            ->getQuery();
        $n1 = $queryCuantEqui->getResult();
		$n = $n1[0][1];

		$queryEquis = $repository->createQueryBuilder('k')
            ->select('e.idEquipo')
			->join('limubacadministratorBundle:Equipo', 'e', 'WITH' ,'e.idEquipo = k.idEquipo')
            ->where('k.idTorneo = :torn')
			->andWhere('k.idCategoria = :cate')
			->andWhere('k.idRama = :rama')
			->setParameter('torn',$torn)
			->setParameter('cate',$cate)
			->setParameter('rama',$rama)
            ->getQuery();
        $equipos = $queryEquis->getResult(); 
		
		if(($n%2)==0){
			$r=($n-1)*2; 
				for($i=0;$i<$n/2;$i++){
					$p[$i][0]=$equipos[$i];
					$p2[$i][0]=$p[$i][0];
				}
				for($i=0;$i<$n/2;$i++){
					$p[$i][1]=$equipos[$i+($n/2)];
					$p2[($n/2)-1-$i][1]=$p[$i][1];
				}
			}
		else {
			$r=$n*2;
			//print_r ($n);
			$n++;
			//print_r ($n);
				for($i=0;$i<$n/2;$i=$i+1){
					$p[$i][0]=$equipos[$i];
					$p2[$i][0]=$p[$i][0];
				}
				for($i=0;$i<$n/2-1;$i++){
					$p[$i][1]=$equipos[$i+($n/2)];
					$p2[$n/2-1-$i][1]=$p[$i][1];
				}
				$p[$n/2-1][1]=0;
				$p2[0][1]=0;
		}
		for($cont=1;$cont<=$r/2;$cont++){
			if($cont==$jorn){
			for($c=0;$c<$n/2;$c++){
				if($p[$c][0]!=0&&$p[$c][1]!=0){
					//insert $p[$c][0]  vs  $p[$c][1] en la jornada cont
					if($cont<10){
						$claveJ="J0{$cont}_";
					}else {
						$claveJ="J{$cont}_";
					}
					if($contJ<10){
						$claveJ.="0".$contJ;
					}else {
						$claveJ.=$contJ;
					}
					$contJ++;
					$partido = new Partido();
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
                        $torneo = $class_repository->find($torn);
					$partido -> setIdTorneo($torneo);
					$partido -> setJornada($cont);
					$partido -> setCategoria($cate);
					$partido -> setRama($rama);
					//$partido -> setClavep($claveJ);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($partido);
                    $em -> flush();

					$juegana = new Juegan();
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
                        $Iequipo= $class_repository->find($p[$c][0]);
					$juegana -> setidEquipo($Iequipo);
					$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
					$queryPartido = $repository->createQueryBuilder('h')
						->select('max(h.idPartido)')
						->getQuery();
					$idpar =	$queryPartido->getResult();
					$idpart=$idpar[0][1];
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
                        $idparti= $class_repository->find($idpart);
					$juegana -> setidPartido($idparti);
					$juegana -> setside('A');
					$juegana -> setResultado(-1);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($juegana);
                    $em -> flush();

					$jueganb = new Juegan();
					$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
                        $Iequipo= $class_repository->find($p[$c][1]);
					$jueganb -> setidEquipo($Iequipo);
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
                        $idparti= $class_repository->find($idpart);
					$jueganb -> setidPartido($idparti);
					$jueganb -> setside('B');
					$jueganb -> setResultado(-1);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($jueganb);
                    $em -> flush();

				}
			}}
			$j=2;
			$t1=$p[1][0];
			for($i=1;$i<$n-1;$i++){
				if($i<$n/2-1){
					$t2=$p[$j][0];
					$p[$j][0]=$t1;
					$t1=$t2;
					$j++;
				}
				else{
					if($i==$n/2-1){
						$j--;
						$t2=$p[$j][1];
						$p[$j][1]=$t1;
						$t1=$t2;
						$j--;
					}
					else{
						$t2=$p[$j][1];
						$p[$j][1]=$t1;
						$t1=$t2;
						$j--;
					}
				}
			}
			$p[1][0]=$t1;
		}
		for($cont=$r/2+1;$cont<=$r;$cont++){
			if($cont==$jorn){
			for($c=0;$c<$n/2;$c++){

				if($p2[$c][0]!=0&&$p2[$c][1]!=0) {
					//insert $p2[$c][0]  vs  $p2[$c][1] en la jornada $cont
					if($cont<10){
						$claveJ="J0{$cont}_";
					}else {
						$claveJ="J{$cont}_";
					}
					if($contJ<10){
						$claveJ.="0".$contJ;
					}else {
						$claveJ.=$contJ;
					}
					$contJ++;
					$partido = new Partido();
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
                        $torneo = $class_repository->find($torn);
					$partido -> setidTorneo($torneo);
					$partido -> setJornada($cont);
					$partido -> setCategoria($cate);
					$partido -> setRama($rama);
					//$partido -> setClavep($claveJ);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($partido);
                    $em -> flush();

					$juegan1 = new Juegan();
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
                        $Iequipo= $class_repository->find($p2[$c][0]);
					$juegan1 -> setidEquipo($Iequipo);
					$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
					$queryPartido = $repository->createQueryBuilder('h')
						->select('max(h.idPartido)')
						->getQuery();
					$idpar =	$queryPartido->getResult();
					$idpart=$idpar[0][1];
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
                        $idparti= $class_repository->find($idpart);
					$juegan1 -> setidPartido($idparti);
					$juegan1 -> setside('B');
					$juegan1 -> setResultado(-1);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($juegan1);
                    $em -> flush();

					$juegan2 = new Juegan();
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
                        $Iequipo= $class_repository->find($p2[$c][1]);
					$juegan2 -> setidEquipo($Iequipo);
						$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
                        $idparti= $class_repository->find($idpart);
					$juegan2 -> setidPartido($idparti);
					$juegan2 -> setside('A');
					$juegan2 -> setResultado(-1);
					$em = $this->getDoctrine()->getManager();
                    $em -> persist($juegan2);
                    $em -> flush();
				}
			}}
			$j=2;
			$t1=$p2[1][0];
			for($i=1;$i<$n-1;$i++){
				if($i<$n/2-1){
					$t2=$p2[$j][0];
					$p2[$j][0]=$t1;
					$t1=$t2;
					$j++;
				}
				else{
					if($i==$n/2-1){
						$j--;
						$t2=$p2[$j][1];
						$p2[$j][1]=$t1;
						$t1=$t2;
						$j--;
					}
					else{
						$t2=$p2[$j][1];
						$p2[$j][1]=$t1;
						$t1=$t2;
						$j--;
					}
				}
			}
			$p2[1][0]=$t1;
		}
		//return $this->redirect($this->generateUrl('limubacadministrator_rolhecho'));
	}
	return $this->redirect($this->generateUrl('limubacadministrator_rolhecho'));
	/*else {
		$idtorn = array($_REQUEST['rol'][0]);
			//select * from participan_t where id_torneo = 1 GROUP BY id_rama 
			$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:ParticipanT');
			$queryCategorias = $repository->createQueryBuilder('h')
				->select('e.idCategoria,e.nombre')
				->join('limubacadministratorBundle:Categoria', 'e', 'WITH' ,'e.idCategoria = h.idCategoria')
				->where('h.idTorneo = :torn')
				->groupBy('h.idCategoria')
				->setParameter('torn',$idtorn[0])
				->getQuery();
			$n1 = $queryCategorias->getResult();			
			//print_r($n1);
			$queryRamas = $repository->createQueryBuilder('l')
				->select('o.idRama,o.nombre')
				->join('limubacadministratorBundle:RamaEquipo', 'o', 'WITH' ,'o.idRama = l.idRama')
				->where('l.idTorneo = :torn')
				->groupBy('l.idRama')
				->setParameter('torn',$idtorn[0])
				->getQuery();
			$n2 = $queryRamas->getResult()
			return $this->render('limubacadministratorBundle:administracion:roldejuego.html.twig',array('rols'=>$idtorn,'categs'=>$n1,'ramas'=>$n2));	
	}*/
	}
	
	public function rolhechoAction(){
		$roles= array("Se agrego una semana de partidos al Rol");
		return $this->render('limubacadministratorBundle:administracion:rolhecho.html.twig',array('rols'=>$roles));
	}
	
	//FINAL CONTROLADOR  ROL DE JUEGOS
	
}
	
?>