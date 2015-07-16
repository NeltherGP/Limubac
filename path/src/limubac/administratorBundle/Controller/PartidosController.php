<?php
namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\claseForm\hojaAnotacion;
use limubac\administratorBundle\consultas\ConsultasPartidos;
use limubac\administratorBundle\consultas\ConsultasAnotaciones;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use limubac\administratorBundle\Entity\Equipo;
use limubac\administratorBundle\Entity\Torneo;
use limubac\administratorBundle\Entity\Categoria;
use limubac\administratorBundle\Entity\ParticipanT;
use limubac\administratorBundle\Entity\Integra;
use limubac\administratorBundle\Entity\Jugador;
use limubac\administratorBundle\Entity\DetallePartido;
use limubac\administratorBundle\Entity\FaltasEquipo;
use limubac\administratorBundle\Entity\Faltas;
use limubac\administratorBundle\Entity\Asistencia;

class PartidosController extends Controller{

  public function partidoTestAction($idpartido,$idtorneo,$jornada){
    $idTorneo=$idtorneo;
    if(isset($_GET[0])){
    $idTorneo=$_GET[0];
    }
    $consultasManager = new ConsultasPartidos();
    $request = $this->getRequest();
    $doctrineManager= $this -> getDoctrine()->getManager();

    $listPartidos1=$consultasManager->listPartidosByTorneo($idTorneo,$doctrineManager,$jornada);

    $listPartidosCompletos=$consultasManager->listPartidosCompletos($doctrineManager);
    $cantidadJornada=$consultasManager->getCantidadJornadasbyTorneo($idTorneo,$doctrineManager);

    foreach ($listPartidosCompletos as $id => $value) {
      $listPartidosCompletos2[]=$value['idPartido'];
    }
    //print_r($listPartidosCompletos2);
    $aux=0;
    $listPartidos2=array();

    //Ventana emergente
    $listSede=$consultasManager->listCanchas($doctrineManager);
    $listArbitros=$consultasManager->listArbitros($doctrineManager);

    //print_r($listPartidos1);

    for($i=0; $i<count($listPartidos1)-1;){
      for($j=0; $j<2; $j++){
        $listPartidos2[$aux][]=$listPartidos1[$i];
		$equi=$listPartidos1[$i]['idEquipo'];
		$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Finanzas');
			$queryinscrip = $repository->createQueryBuilder('f')
				->select('f.inscripcion')
				->where('f.idTorneo = :torn')
				->andWhere('f.idEquipo = :equi')
				->setParameter('torn',$idTorneo)
				->setParameter('equi',$equi)
				->getQuery();
		$ins =	$queryinscrip->getResult();
		//$ins=$ins[0]['inscripcion'];
		if($ins!=null)$listPartidos2[$aux][$j]['pago']=true;
		else $listPartidos2[$aux][$j]['pago']=false;
        $i++;
      }
      $aux++;
    }


    //array_push($listPartidos2,$listPartidos1);


    //print_r($listPartidos1);

    return $this->render('limubacadministratorBundle:administracion:partidoTest.html.twig',array('listaPartidos'=>$listPartidos2,'listSede'=>$listSede,'listArbitros'=>$listArbitros,'listPartidosCompletos'=>$listPartidosCompletos2,'cantidadJornadas'=>$cantidadJornada[0][1],'jornada'=>$jornada));
  }
}
?>
