<?php
namespace limubac\administratorBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\consultas\ConsultasPartidos;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TorneoSelectController extends Controller{
  function seleccionarTorneoAction (){

    return $this->render('limubacadministratorBundle:administracion:seleccionTorneo.html.twig');
  }
}

?>
