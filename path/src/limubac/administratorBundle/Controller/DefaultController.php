<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\claseForm\hojaAnotacion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

        

        $request = $this->getRequest();      

        echo $request->getMethod() ;//Quitar o comentar


        if($request->getMethod() == 'POST')//si se envia el formulario
            {
              $datos = new hojaAnotacion();

              $validator = $this->get('validator'); 
            
              

                $datos->setEqA($_POST["EqA"]);
                $datos->setEqB($_POST["EqB"]);
                $datos->setRama($_POST["Rama"]);
                $datos->setCategoria($_POST["Categoria"]);
                $datos->setLugar($_POST["Lugar"]);
                $datos->setTorneo($_POST["Torneo"]);
                $datos->setFecha($_POST["Fecha"]);
                $datos->setHora($_POST["Hora"]);
                $datos->setJuez1($_POST["Juez1"]);
                $datos->setJuez2($_POST["Juez2"]);
               

                $errors = $validator->validate($datos);

                    if (count($errors) > 0) {

                      $errorsString = (string) $errors;
                    
                    echo $errorsString;

                    }

            }

    	return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig');
    }
	public function equiposAction(){
    	return $this->render('limubacadministratorBundle:administracion:Equipos.html.twig');
    }
}
