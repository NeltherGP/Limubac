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

	include 'funcionesExtras.php';	
		
class EquiposController extends Controller{
		
	public function equiposAction(){
		
		//Registrar a Torneo
		
		if(isset($_REQUEST['Torneo'])){ //valor del torneo al que se reitrara el equipo
			$Manager = $this->getDoctrine()->getManager();
			//echo "Torneo: ".$_REQUEST['Torneo']."   equipo: ".$_REQUEST['idEquipo']."<br>";
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['idEquipo']);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
			$Torneo = $repositorio->find($_REQUEST['Torneo']);
			
			//Restricciones
			if(restringe($equipo,$Torneo,$this)){
				$Participan = new ParticipanT();
				$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
				$query = $repositorio->createQueryBuilder('p')
					->select('p.idRegistro')
					->where('p.idEquipo = '.$_REQUEST['idEquipo'])
					->getQuery();
				$temp = $query->getResult();
				$Participan = $repositorio->find($temp[0]['idRegistro']);

				$equipo->setRegistrado(true);
				$Participan->setIdTorneo($Torneo);
				$Manager->persist($Participan);
				$Manager->persist($Torneo);
				$Manager->flush();
			}
		}
		
		//Lista de Equipos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		$ListaEquipos = $repositorio->findAll();
		
		//Conseguir Torneos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
		$Torneos = $repositorio->findAll();
		
		//Conseguir Ramas
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
		$Ramas = $repositorio->findAll();
		
		//Editando Equipo
		if(isset($_REQUEST['editarEquipo'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
			$Participan = $repositorio->find($_REQUEST['editarEquipo']);
			
			
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($Participan);
			$Manager->flush();
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$Equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
			$Rama =$repositorio->find($_REQUEST['rama']);
			$Equipo->setIdRama($Rama);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
			$Categoria =$repositorio->find($_REQUEST['Categoria']);
			$Equipo->setIdCategoria($Categoria);
			
			$Equipo->setNombre($_REQUEST['NuevoEquipo']);
			$Manager->persist($Equipo);
			$Manager->flush();
			
		}
		
		//Cantidad Jugadores en un equipo
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Integra");
		$query = $repositorio->createQueryBuilder('i')
				->select('IDENTITY(i.idEquipo)','count(i.idEquipo)')
				->groupBy('i.idEquipo')
				->getQuery();
			$jugadores = $query->getResult();
		//print_r($jugadores);
    	return $this->render('limubacadministratorBundle:administracion:equipos.html.twig', array('Jugadores'=>$jugadores,'listEquip' =>$ListaEquipos,'Torneos'=>$Torneos,'Ramas'=>$Ramas));
    }
	
	public function equipoAction(){
		$resul ="";
		$nj="";
		$Mensaje = null;
		$jugador = new Jugador();
        $form = $this->createForm(new JugadorType(), $jugador);

        $request = $this->get('request');
        $form->handleRequest($request);
		
        //Agregar fotografia
        if (isset($_POST['fotosup'])) {
        	$idjug = isset($_POST['idJugador']);
        	print_r($idjug);
        }

		//Agregando nuevo equipo
		if(isset($_POST['NuevoEquipo'])){
			$equipo = new Equipo();
			$equipo->setNombre($_POST['NuevoEquipo']);
			$equipo->setRegistrado(false);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
			$cat = $repositorio->find($_POST['Categoria']);
			$equipo->setIdCategoria($cat);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
			$ram = $repositorio->find($_POST['rama']);
			$equipo->setIdRama($ram);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();

			$_REQUEST['opciones'] = $equipo->getIdEquipo();
		}
		
		//Modificando Jugador 
		if(isset($_REQUEST['opciones']) && isset($_REQUEST['NoJugador']) && isset($_REQUEST['idJugador'])){
			$Manager = $this->getDoctrine()->getManager();
			$query = $Manager->createQuery("UPDATE limubac\administratorBundle\Entity\Integra as i SET i.noPlayera=".$_REQUEST['NoJugador']." where i.idEquipo='".$_REQUEST['opciones']."' and i.idJugador='".$_REQUEST['idJugador']."'");
			$query->getResult();
			 
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$jugador = $repositorio->find($_REQUEST['idJugador']);
			
			//--------------------------------------de aqui sigue fafi

			$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idJugador','e.nombre','e.apPaterno','e.apMaterno','e.fNacimiento','e.correo','e.telefono','e.profesion','IDENTITY(e.idStatus)','IDENTITY(e.idGenero)','e.estatura','e.peso','IDENTITY(e.idTiposanguineo)','IDENTITY(e.idFoto)')
            //->join('limubacadministratorBundle:Fotos', 'fot', 'WITH' ,'fot.idFoto = e.idFoto')
            ->where('e.idJugador = :word')
            ->setParameter('word', $_REQUEST['idJugador'])
            ->getQuery();
            $resul = $queryEdit->getResult();
            $nj = $_REQUEST['NoJugador'];

		}
		
        if ($request->getMethod() == 'GET') {
            $url_to_parse = $_SERVER['REQUEST_URI'];
            $parsed_url = parse_url($url_to_parse);
            if (empty($parsed_url['query'])) {
                return $this->render('limubacadministratorBundle:administracion:addjugador.html.twig',array('form' => $form->createView()));
            }
            else{
                $out = $_REQUEST['jugador'];
                if (is_array($out) && !empty($out)) {
                		$fn = $out['fNacimiento'];
                    	$dt = date_create_from_format('Y-m-d', $fn);
                    $nom = strtoupper (substr($out['nombre'],0,1));
                	$app = strtoupper (substr($out['apPaterno'],0,2));
                	$apm = strtoupper (substr($out['apMaterno'],0,1));
                	$ani = substr($fn,2,2);
                	$mes = substr($fn,5,2);
                	$dia = substr($fn,8,2);
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
                       	if ($out['idGenero'] == 1)
                       		$sex = "H";
                       	elseif ($out['idGenero'] == 2)
                       		$sex = "F";
                       	else
                       		$sex = "G";
                    $player -> setIdGenero($category);
                    $player -> setEstatura($out['estatura']);
                    $player -> setPeso($out['peso']);
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
                        $category = $class_repository->find($out['idTiposanguineo']);
                    $player -> setIdTiposanguineo($category);
                    $curp = $app.$apm.$nom.$ani.$mes.$dia.$sex;
                    $player -> setCurp($curp);
                        //$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
                        //$category = $class_repository->find($out['idFoto']);
                    //$player -> setIdFoto($category);

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($player);
                    $em -> flush();
					//Fin de agregar jugador
					
					//Agregar Al equipo
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
					$equipo = $repositorio->find($_REQUEST['opciones']);
					
						//Agregando el jugador al equipo
						$integra = new Integra();
						$integra->setNoPlayera(intval($out['numero']));
						$integra->setIdEquipo($equipo);
						$integra->setIdJugador($player);
					
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
			
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('form' => $form->createView(),'equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'NoEquipo'=>$_REQUEST['opciones'],'res'=>$resul, 'nj' => $nj));
		
		}else{//si no esta definido el valor del equipo
			
			echo "cuatro";
		}
		//return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'form' => $form->createView()));

	}
	
	public function equipoNuevoAction(){
		//Conseguir Categorias
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
		$Categorias = $repositorio->findAll();
		
		return $this->render('limubacadministratorBundle:administracion:equipoNuevo.html.twig',array('Categorias'=>$Categorias));
	}
	
	public function editarEquipoAction(){
		//Conseguir Categorias
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
		$Categorias = $repositorio->findAll();
		
		
		//Conseguir Equipo
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		$Equipo = $repositorio->find($_REQUEST['opciones']);
		
		//Conseguir ParticipanT
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
		$query = $repositorio->createQueryBuilder('p')
				->select('p.idRegistro')
				->where('p.idEquipo = '.$Equipo->getIdEquipo() )
				->getQuery();
			
		$Participan= $query->getResult();
		//var_dump($Participan);
		return $this->render('limubacadministratorBundle:administracion:editarEquipo.html.twig',array('Participan'=>$Participan,'Equipo'=>$Equipo,'Categorias'=>$Categorias));
	}
	
	public function equipoATorneoAction(){
		//Registrar en torneo
		if(isset($_REQUEST['Registro'])){//id del equipo
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
			$query = $repositorio->createQueryBuilder('t')
				->select('t.idTorneo','t.nombre','t.costo','t.fInicio','t.fTermino')
				->where('t.inscripcionAbierta = 1')
				->getQuery();
			$Torneos= $query->getResult();
		}else{
			echo "<script type='text/javascript'>alert('No Deberias estar aqui!!');</script>";
			return $this->render('limubacadministratorBundle:administracion:admin.html.twig');
		}
		//var_dump($Torneos);
		return $this->render('limubacadministratorBundle:administracion:equipoATorneo.html.twig',array('Torneos'=>$Torneos,'equipo'=>$_REQUEST['Registro']));
	}
	
	
}	
?>