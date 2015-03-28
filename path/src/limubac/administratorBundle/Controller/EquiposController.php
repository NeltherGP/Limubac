<?php

namespace limubac\administratorBundle\Controller;
		use Symfony\Bundle\FrameworkBundle\Controller\Controller;
		use limubac\administratorBundle\claseForm\hojaAnotacion;
		use limubac\administratorBundle\consultas\ConsultasAnotaciones;
		use Symfony\Component\HttpFoundation\Response;
		use Symfony\Component\HttpFoundation\Request;
		use limubac\administratorBundle\Entity\Equipo;
		use limubac\administratorBundle\Entity\Torneo;
		use limubac\administratorBundle\Entity\Categoria;
		use limubac\administratorBundle\Entity\ParticipanT;
		use limubac\administratorBundle\Entity\Integra;
		use limubac\administratorBundle\Entity\Jugador;
		use limubac\administratorBundle\Entity\TipoSanguineo;
		use limubac\administratorBundle\Entity\DetallePartido;
		use limubac\administratorBundle\Entity\FaltasEquipo;
		use limubac\administratorBundle\Entity\Faltas;
		use limubac\administratorBundle\Entity\Asistencia;
		use limubac\administratorBundle\Form\Type\JugadorType;
		use limubac\administratorBundle\Form\Type\JugadorAType;
		use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
		use Symfony\Component\Validator\Constraints\DateTime;

class EquiposController extends Controller{
		
	public function equiposAction(){
		//Agregando nuevo equipo
		if(isset($_POST['NuevoEquipo'])){
			$equipo = new Equipo();
			$equipo->setNombre($_POST['NuevoEquipo']);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
			
			$participacion = new ParticipanT();
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
			$cat = $repositorio->find($_POST['category']);
			$participacion->setIdCategoria($cat);
			
			$participacion->setIdEquipo($equipo);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
			$tor = $repositorio->find($_POST['torneo']);
			$participacion->setIdTorneo($tor);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
			$ram = $repositorio->find($_POST['rama']);
			$participacion->setIdRama($ram);
			
			$Manager->persist($participacion);
			$Manager->flush();
		}
		
		//Lista de Equipos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		$ListaEquipos = $repositorio->findAll();
		
		//Conseguir Categorias
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
		$ListaCategorias = $repositorio->findAll();
		
		//Conseguir Torneos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
		$Torneos = $repositorio->findAll();
		
		//Conseguir Ramas
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
		$Ramas = $repositorio->findAll();
		
    	return $this->render('limubacadministratorBundle:administracion:equipos.html.twig', array('listEquip' =>$ListaEquipos,'Categorias'=>$ListaCategorias,'Torneos'=>$Torneos,'Ramas'=>$Ramas));
    }
	
	public function equipoAction(){
		$Mensaje = null;
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
                $out = $_REQUEST['jugador'];
                if (is_array($out) && !empty($out)) {
                    $player = new Jugador();
                    $player -> setNombre($out['nombre']);
                    $player -> setApPaterno($out['apPaterno']);
                    $player -> setApMaterno($out['apMaterno']);
                    $fn = $out['fNacimiento'];
                    $dt = date_create_from_format('Y-m-d', $fn);
                    $player -> setFNacimiento(new \DateTime($fn));
                    $player -> setCorreo($out['correo']);
                    $player -> setTelefono($out['telefono']);
                    $player -> setProfesion($out['profesion']);
				        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Status');
                        $category = $class_repository->findByStatus("Activo");
                    $player -> setIdStatus($category[0]);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Genero');
                       	$category = $class_repository->find($out['idGenero']);
                    $player -> setIdGenero($category);
                    $player -> setEstatura($out['estatura']);
                    $player -> setPeso($out['peso']);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
                        $category = $class_repository->find($out['idTiposanguineo']);
                    $player -> setIdTiposanguineo($category);
                        //$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
                        //$category = $class_repository->find($out['idFoto']);
                    //$player -> setIdFoto($category);

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($player);
                    $em -> flush();
					
					//Agregar Al equipo
					$integra = new Integra();
					$integra->setNoPlayera(intval($out['numero']));
					
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
					$equipo = $repositorio->find($_REQUEST['opciones']);
					$integra->setIdEquipo($equipo);
					
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
					$jugador = $repositorio->find($player->getIdJugador());
					$integra->setIdJugador($jugador);
					
					$Manager = $this->getDoctrine()->getManager();
					$Manager->persist($integra);
					$Manager->flush();
					//Fin Agregar Al Equipo
					
					
                }
                else{
                    return new SymfonyResponse('Algo Fallo!');
                }
            }
        }
		
		
		//Agregar o modificar capitan
		if(isset($_REQUEST['opciones']) and isset($_POST['idCapitan'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Capi = $repositorio->find($_POST['idCapitan']);
			
			$equipo->setIdCapitan($Capi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Representante
		if(isset($_REQUEST['opciones']) and isset($_POST['idRepresentante'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Repre= $repositorio->find($_POST['idRepresentante']);

			$equipo->setRepresentante($Repre);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Auxiliar
		if(isset($_REQUEST['opciones']) and isset($_POST['idAuxiliar'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Auxi= $repositorio->find($_POST['idAuxiliar']);
			
			$equipo->setAuxiliar($Auxi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Llenado de los expacios
		if(isset($_REQUEST['opciones'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			//Capitan
			if($equipo->getIdCapitan()==null){
				$Capi = array( array(
					'idJugador'=>0,
					'nombre'=>"No Asignado",
					'apPaterno'=>"",
					'apMaterno'=>""
				));
			}else{
			
				$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
				$query = $repositorio->createQueryBuilder('p')
				->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno')
				->where('p.idJugador = '.$equipo->getIdCapitan()->getIdJugador())
				->getQuery();
			
				$Capi = $query->getResult();
			}//end capi
			
			//Jugadores
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Integra");
				$query = $repositorio->createQueryBuilder('i')
				->select('IDENTITY(i.idJugador)','j.nombre','j.apPaterno','j.apMaterno','i.noPlayera')
				->join('limubacadministratorBundle:Jugador', 'j', 'WITH' ,'j.idJugador = i.idJugador')
				->where('i.idEquipo = '.$equipo->getIdEquipo())
				->getQuery();
			
			$jugadores = $query->getResult();
			//end Jugadores
			
			//Representante
			if($equipo->getRepresentante()==null){
				$Representante = array(array(
					'idJugador'=>0,
					'nombre'=>'No Asignado',
					'apPaterno'=>"",
					'apMaterno'=>""
				));
			}else{
				$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
				$query = $repositorio->createQueryBuilder('p')
				->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno')
				->where('p.idJugador = '.$equipo->getRepresentante()->getIdJugador())
				->getQuery();
			
				$Representante = $query->getResult();
			}//end Representante
			
			//Auxiliar
			if($equipo->getAuxiliar()==null){
				$Auxiliar = array(array(
					'idJugador'=>0,
					'nombre'=>'No Asignado',
					'apPaterno'=>"",
					'apMaterno'=>""
				));
			}else{
				$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
				$query = $repositorio->createQueryBuilder('p')
				->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno')
				->where('p.idJugador = '.$equipo->getAuxiliar()->getIdJugador())
				->getQuery();
			
				$Auxiliar = $query->getResult();
			}
			
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('form' => $form->createView(),'equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'NoEquipo'=>$_REQUEST['opciones']));
		
		}else{//si no esta definido el valor del equipo
			
			echo "cuatro";
		}
		//return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'form' => $form->createView()));

	}
	
}				
?>