<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\Entity\equipo;

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
			$equipo = new equipo();
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
		
		$Equi = array(
			'NumEquipo' =>$_POST['opciones'],
			'NomEquipo' =>'valedores',
			'Capitan'=>'',
			'Representante'=>'',
			'Auxiliar'=>'',
			$jugadores = array(
				array("numero"=>1,"nombre"=>"Pablo")
			)
		);
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$Equi));
		
		}else{//si no esta definido el valor del equipo
		
		}
	}
	//End Edgar
}
