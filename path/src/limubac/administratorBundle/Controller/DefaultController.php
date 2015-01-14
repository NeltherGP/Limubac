<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
	public function equiposAction(){
    	return $this->render('limubacadministratorBundle:administracion:equipos.html.twig');
    }
}
