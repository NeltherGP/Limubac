<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\Entity\Equipo;
use limubac\administratorBundle\Entity\Jugador;
use limubac\administratorBundle\Entity\TipoSanguineo;
use limubac\administratorBundle\Form\Type\JugadorType;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Validator\Constraints\DateTime;

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

	//-----------------------INICIO CONTROLADOR DE FAFI---------------------------------------------------
	public function jugadoresAdminAction(){
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
        $queryPlayers = $repository->createQueryBuilder('p')
            ->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno','p.fNacimiento','p.correo','p.telefono','p.estatura','p.peso','IDENTITY(p.idTiposanguineo)')
            
            ->orderBy('p.idJugador', 'DESC')
            ->getQuery();
        $entities = $queryPlayers->getResult();

        $queryTotal = $repository->createQueryBuilder('t')
            ->select('count(t.idJugador)')
            ->getQuery();
        $tot = $queryTotal->getResult();

        $queryTotHom = $repository->createQueryBuilder('h')
            ->select('count(h.idJugador)')
            ->where('h.idGenero = 1')
            ->getQuery();
        $hom = $queryTotHom->getResult();

        $queryTotMuj = $repository->createQueryBuilder('m')
            ->select('count(m.idJugador)')
            ->where('m.idGenero = 2')
            ->getQuery();
        $muj = $queryTotMuj->getResult();

        $queryTotAct = $repository->createQueryBuilder('a')
            ->select('count(a.idJugador)')
            ->where('a.idStatus = 1')
            ->getQuery();
        $act = $queryTotAct->getResult();

        return $this->render('limubacadministratorBundle:administracion:jugadoresAdmin.html.twig', 
            array('entities' => $entities, 'total' => $tot, 'hombres' => $hom, 'mujeres' => $muj, 'activo' => $act));
    }

    public function addjugadorAction(){
        $jugador = new Jugador();
        $form = $this->createForm(new JugadorType(), $jugador);

        $request = $this->get('request');
        $form->handleRequest($request);

        if ($request->getMethod() == 'GET') {
            $url_to_parse = $_SERVER['REQUEST_URI'];
            $parsed_url = parse_url($url_to_parse);
            if (empty($parsed_url['query'])) {
                return $this->render('limubacadministratorBundle:administracion:addjugador.html.twig',array('form' => $form->createView()));
            }
            else{
                $url_query = $parsed_url['query'];
                parse_str($url_query,$out);
                if (is_array($out) && !empty($out)) {
                    $player = new Jugador();
                    $player -> setNombre($out['jugador']['nombre']);
                    $player -> setApPaterno($out['jugador']['apPaterno']);
                    $player -> setApMaterno($out['jugador']['apMaterno']);
                    $fn = $out['jugador']['fNacimiento'];
                    $dt = date_create_from_format('Y-m-d', $fn);
                    $player -> setFNacimiento(new \DateTime($fn));
                    $player -> setCorreo($out['jugador']['correo']);
                    $player -> setTelefono($out['jugador']['telefono']);
                    $player -> setProfesion($out['jugador']['profesion']);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Status');
                        $category = $class_repository->find($out['jugador']['idStatus']);
                    $player -> setIdStatus($category);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Genero');
                        $category = $class_repository->find($out['jugador']['idGenero']);
                    $player -> setIdGenero($category);
                    $player -> setEstatura($out['jugador']['estatura']);
                    $player -> setPeso($out['jugador']['peso']);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
                        $category = $class_repository->find($out['jugador']['idTiposanguineo']);
                    $player -> setIdTiposanguineo($category);
                    $player -> setFoto('null');

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($player);
                    $em -> flush();

                    return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
                }
                else{
                    return new SymfonyResponse('Algo Fallo!');   
                }
            }
        }
        return $this->render('limubacadministratorBundle:administracion:addjugador.html.twig',array('form' => $form->createView()));
    }

    public function eliminarAction($page){
        $url_to_parse = $_SERVER['REQUEST_URI'];
        $parsed_url = parse_url($url_to_parse);
        
        if (!empty($_REQUEST['Search'])) {
            $sr = $_REQUEST['Search'];
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryFind = $repository->createQueryBuilder('p')
            ->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno','p.fNacimiento','p.correo','p.telefono','p.estatura','p.peso','IDENTITY(p.idTiposanguineo)')
            ->where('p.nombre LIKE :word')
            ->orWhere('p.apPaterno LIKE :word')
            ->orWhere('p.apMaterno LIKE :word')
            ->setParameter('word', $sr)
            ->getQuery();
            $resul = $queryFind->getResult();
            $ar = [$sr];
            return $this->render('limubacadministratorBundle:administracion:buscar.html.twig', array('busca' => $resul));
        }
        elseif (!empty($_REQUEST['check'])) {
            $ch = $_REQUEST['check'];
            foreach ($ch as $key => $value) {
                $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
                $queryDelete = $repository->createQueryBuilder('j')
                    ->delete()
                    ->where('j.idJugador = '.$key)
                    ->getQuery();
                $entities = $queryDelete->getResult();
            }
            return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
        }
        elseif (empty($parsed_url['query']) || empty($_REQUEST['Search'])) {
            return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
        }
    }

    public function buscarAction(){
        return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
    }
    //-----------------------FINAL CONTROLADOR DE FAFI---------------------------------------------------
}
