<?php
namespace limubac\administratorBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\consultas\ConsultasPartidos;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class overviewController extends Controller{
  public function OverviewPartidosAction($idpartido,$idtorneo,$jornada){

    $consultasManager = new ConsultasPartidos();
    $request = $this->getRequest();
    $doctrineManager= $this -> getDoctrine()->getManager();
    $aux=0;

    $listPartidos1=$consultasManager->listPartidosByTorneo($idtorneo,$doctrineManager,$jornada);
    $cantidadJornadas=$consultasManager->getCantidadJornadasbyTorneo($idtorneo,$doctrineManager);
    $listEqCategoria=$consultasManager->listEqCategoriaByTorneo($idtorneo,$doctrineManager);

    for($i=0;$i<count($listEqCategoria);$i++){
      $listEqCategoria[$i]['stats']=$consultasManager->estadisticasEquipo(1,$listEqCategoria[$i]['idEquipo'],$doctrineManager);;
    }
    //print_r($listEqCategoria);

    for($i=0; $i<count($listPartidos1)-1;){
      for($j=0; $j<2; $j++){
        $listPartidos2[$aux][]=$listPartidos1[$i];
        $i++;
      }
      $aux++;
    }


    return $this->render('limubacadministratorBundle:administracion:overview.html.twig',array('listaPartidos'=>$listPartidos2,'listStats'=>$listEqCategoria,'cantidadJornadas'=>$cantidadJornadas[0][1],'jornada'=>$jornada));
  }

}

?>
