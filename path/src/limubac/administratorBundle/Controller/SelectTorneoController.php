<?php
namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use limubac\administratorBundle\consultas\ConsultasPartidos;

class SelectTorneoController extends Controller{
  function seleccionarTorneoAction(){
    $consultasManager = new ConsultasPartidos();
    $doctrineManager= $this -> getDoctrine()->getManager();
    $listTorneos=$consultasManager->listTorneos($doctrineManager);

    return $this->render('limubacadministratorBundle:administracion:seleccionTorneo.html.twig',array('listTorneos'=>$listTorneos));
  }
}
