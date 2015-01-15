<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\Entity\Equipo;
use limubac\administratorBundle\Entity\Jugador;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('limubacadministratorBundle:Default:index.html.twig', array('name' => $name));
    }

    public function adminAction()
    {
        return $this->render('limubacadministratorBundle:administracion:adminPanel.html.twig');
    }

    public function hojaAnotacionesAction(){
    	return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig');
    }
	//Edgar
	
	public function equiposAction(){
		if(isset($_POST['NuevoEquipo'])){
			$equipo = new Equipo();
			$equipo->setNombre($_POST['NuevoEquipo']);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		
		$ListaEquipos = $repositorio->findAll();
    	return $this->render('limubacadministratorBundle:administracion:equipos.html.twig', array('listEquip' =>$ListaEquipos));
    }
	
	public function equipoAction(){
		if(isset($_POST['opciones'])){
		
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			
			$equipo = $repositorio->find($_POST['opciones']);
			
			
			$Manager = $this->getDoctrine()->getManager();
			$q = "Select j.id_jugador,j.nombre FROM limubacadministratorBundle:Jugador j JOIN limubacadministratorBundle:integra i ON j.id_jugador=i.id_jugador where i.id_equipo='".$_POST['opciones']."'";

			$query = $Manager->createQuery($q); 
			$jugadores = $query->getResult();
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$equipo,'jugadores'=>$jugadores));
		
		}else{//si no esta definido el valor del equipo
		
		}
	}
	//End Edgar
}
