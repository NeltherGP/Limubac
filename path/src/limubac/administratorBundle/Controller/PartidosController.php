<?php
namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\claseForm\hojaAnotacion;
use limubac\administratorBundle\consultas\ConsultasPartidos;
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

  public function partidoTestAction(){
    $idTorneo=1;
    $consultasManager = new ConsultasPartidos();
    $request = $this->getRequest();
    $doctrineManager= $this -> getDoctrine()->getManager();

    $listPartidos1=$consultasManager->listPartidosByTorneo($idTorneo,$doctrineManager);
    $aux=0;
    $listPartidos2=array();

    for($i=1; $i<count($listPartidos1);){
      for($j=0; $j<2; $j++){
        $listPartidos2[$aux][]=$listPartidos1[$i];
        $i++;
      }
      $aux++;
    }


    //array_push($listPartidos2,$listPartidos1);

    // print_r($listPartidos2);
    //print_r($listPartidos1);
    return $this->render('limubacadministratorBundle:administracion:partidoTest.html.twig',array('listaPartidos'=>$listPartidos2));
  }
}
?>
