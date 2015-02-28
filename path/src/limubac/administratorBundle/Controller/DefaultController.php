<?php

namespace limubac\administratorBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\claseForm\hojaAnotacion;
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
use limubac\administratorBundle\Form\Type\JugadorType;
use limubac\administratorBundle\Form\Type\TorneoType;
use limubac\administratorBundle\Form\Type\CategoriaType;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Validator\Constraints\DateTime;



class DefaultController extends Controller{
    
	public function indexAction($name){
        return $this->render('limubacadministratorBundle:Default:index.html.twig', array('name' => $name));
    }

    public function adminAction(){
        return $this->render('limubacadministratorBundle:administracion:adminPanel.html.twig');
    }

    public function hojaAnotacionesAction(){

        $detallePartido= new DetallePartido();
        $contadorA=0;
        $contadorB=0;
        $marcadorA=0;
        $marcadorB=0;
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

                $datos->setPuntos(array_values((array_slice($_POST, 10,20))));
                
                $puntos = $datos->getPuntos();
                $puntosA=array();
                $puntosB=array();

                for ($j=0; $j < count($puntos); $j++) { 
                    if(($j%2)==0){
                        $puntosA[]=$puntos[$j];
                    }else{
                        $puntosB[]=$puntos[$j];
                    }
                }

                print_r($puntosA);
               // print_r($puntosB);

               for ($i=0; $i < count($puntosA); $i++) { 
                 if($i==0 && $puntosA[$i]!=''){
                    $marcadorA++;

                 }else{
                    if($puntosA[$i]==''){
                        $contadorA++; echo "hola";
                    }else{
                        if($contadorA==0){
                            $marcadorA=$marcadorA+1;
                        }else{
                        if($contadorA==1){
                            $marcadorA=$marcadorA+2;
                            $contadorA=0;
                        }else{
                            if($contadorA==2){
                                $marcadorA=$marcadorA+3;
                                $contadorA=0;
                            }
                        }
                     }
                    }
                 }
               }

               echo $marcadorA;

                for ($i=0; $i < count($puntosB); $i++) { 
                 if($i==0 && $puntosB[$i]!=''){
                    $marcadorB++;
                 }else{
                    if($puntosA[$i]==''){
                        $contadorB++; echo "hola";
                    }else{
                        if($contadorB==0){
                            $marcadorB=$marcadorB+1;
                        }else{
                        if($contadorB==1){
                            $marcadorB=$marcadorB+2;
                            $contadorB=0;
                        }else{
                            if($contadorB==2){
                                $marcadorB=$marcadorB+3;
                                $contadorB=0;
                            }
                        }
                     }
                    }
                 }
               }
             

                $errors = $validator->validate($datos);

                    if (count($errors) > 0) {

                      $errorsString = (string) $errors;
                    
                
                    echo $errorsString;

                    }

            }

    	return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig');
    }  //Ends Hoja de anotacion


	//Edgar
	
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
		$Mensaje =null;
		if(isset($_POST['accion'])){
			
			switch($_POST['accion']){
				case 'Nuevo':
					$integra = new Integra();
					$integra->setNoPlayera(intval($_POST['NoJugador']));
					
					
					//Jugador
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
					$jugador = $repositorio->find($_POST['idJugador']);
					
					//Equipo
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
					$equipo = $repositorio->find($_POST['opciones']);
					
					//Categoria
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
					$query = $repositorio->createQueryBuilder('P')
						->select('IDENTITY(P.idCategoria)','IDENTITY(P.idRama)')
						->where('P.idEquipo = '.$equipo->getIdEquipo())
						->getQuery();
					$IdCategoria = $query->getResult();//Realmente mantiene el ID de la categoria y la rama 
					//var_dump($IdCategoria[0][1]);
					$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
					$Categoria = $repositorio->find($IdCategoria[0][1]);
					
					
					$actual = new \DateTime(Date("Y-m-d H:i:s"));
					
					$edad=$actual->diff($jugador->getFNacimiento())->format('%Y');
					
						//Comprobar limite de edad
						if($Categoria->getRefEdad()){//true = igual o mayor que
							
							
							if($edad>=$Categoria->getEdad() && $jugador->getIdGenero()->getIdGenero() ==$IdCategoria[0][2]){//si la edad es mayorigual a la de la categoria
								$Booleano = true;
							}else{
								$Booleano = false;
								$Mensaje ="El jugador no cumple los requerimientos para participar en este equipo";
							}
							
						}else{//false = igual o menor que
							
							if($edad<=$Categoria->getEdad()&& $jugador->getIdGenero()->getIdGenero() ==$IdCategoria[0][2]){//si la edad es mayorigual a la de la categoria
								$Booleano = true;
							}else{
								$Booleano = false;
								$Mensaje ="El jugador no cumple los requerimientos para participar en este equipo";
							}
							
						}
					
					if($Booleano){
						$integra->setIdJugador($jugador);
						$integra->setIdEquipo($equipo);
						$Manager = $this->getDoctrine()->getManager();
						$Manager->persist($integra);
						$Manager->flush();
						
					}
					
				break;
			}
		
		}
		
		//Agregar o modificar capitan
		if(isset($_POST['opciones']) and isset($_POST['idCapitan'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_POST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Capi = $repositorio->find($_POST['idCapitan']);
			
			$equipo->setIdCapitan($Capi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Representante
		if(isset($_POST['opciones']) and isset($_POST['idRepresentante'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_POST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Repre= $repositorio->find($_POST['idRepresentante']);

			$equipo->setRepresentante($Repre);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Auxiliar
		if(isset($_POST['opciones']) and isset($_POST['idAuxiliar'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_POST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Auxi= $repositorio->find($_POST['idAuxiliar']);
			
			$equipo->setAuxiliar($Auxi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Llenado de los expacios
		if(isset($_POST['opciones'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_POST['opciones']);
			
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
			
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje));
		
		}else{//si no esta definido el valor del equipo
			
			echo "cuatro";
		}
	}
	//End Edgar

	//-----------------------INICIO CONTROLADOR DE FAFI---------------------------------------------------
	public function jugadoresAdminAction(){

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
        $queryPlayers = $repository->createQueryBuilder('p')
            ->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno','p.fNacimiento','p.correo','p.telefono','p.estatura','p.peso','tsan.tipoSangre')
            ->join('limubacadministratorBundle:TipoSanguineo', 'tsan', 'WITH' ,'tsan.idTiposanguineo = p.idTiposanguineo')
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
                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
                        $category = $class_repository->find($out['jugador']['idFoto']);
                    $player -> setIdFoto($category);

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

    public function updateAction($page){
        $url_to_parse = $_SERVER['REQUEST_URI'];
        $parsed_url = parse_url($url_to_parse);
        
        if (!empty($_REQUEST['Search'])) {
            $sr = $_REQUEST['Search'];
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryFind = $repository->createQueryBuilder('p')
            ->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno','p.fNacimiento','p.correo','p.telefono','p.estatura','p.peso','tsan.tipoSangre')
            ->join('limubacadministratorBundle:TipoSanguineo', 'tsan', 'WITH' ,'tsan.idTiposanguineo = p.idTiposanguineo')
            ->where('p.nombre LIKE :word')
            ->orWhere('p.apPaterno LIKE :word')
            ->orWhere('p.apMaterno LIKE :word')
            ->setParameter('word', $sr)
            ->getQuery();
            $resul = $queryFind->getResult();
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
        elseif (!empty($_REQUEST['edit'])) {
            $jugador = new Jugador();
            $form = $this->createForm(new JugadorType(), $jugador);
            $ed = $_REQUEST['edit'][0];
            
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idJugador','e.nombre','e.apPaterno','e.apMaterno','e.fNacimiento','e.correo','e.telefono','e.profesion','IDENTITY(e.idStatus)','IDENTITY(e.idGenero)','e.estatura','e.peso','IDENTITY(e.idTiposanguineo)','fot.foto','IDENTITY(e.idFoto)')
            ->join('limubacadministratorBundle:Fotos', 'fot', 'WITH' ,'fot.idFoto = e.idFoto')
            ->where('e.idJugador = :word')
            ->setParameter('word', $ed)
            ->getQuery();
            $resul = $queryEdit->getResult();
            //print_r($resul);

            $images = array();

            $images[0] = base64_encode(stream_get_contents($resul[0]['foto']));
            
            return $this->render('limubacadministratorBundle:administracion:edita.html.twig',array('form' => $form->createView(), 'edita' => $resul, 'im' => $images));
        }
        elseif (!empty($_REQUEST['foto'])) {
            $per = $_REQUEST['foto'];
            $idUserPics = $per[0];
            
            return $this->render('limubacadministratorBundle:administracion:uploados.html.twig', array('person' => $per));
        }
        elseif (empty($parsed_url['query']) || empty($_REQUEST['Search'])) {
            return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
        }
    }

    /**
     * @Route("/photo/{number}", name="photo", requirements={"id" = "\d+"})
     */
    public function photoAction(Request $request){
        $request = $this->getRequest();
        $workerId = $request->get('number');
        $images = array();

            $repositor = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
            $queryFotin = $repositor->createQueryBuilder('f')
            ->select('f.foto')
            ->where('f.idFoto = :word')
            ->setParameter('word', $workerId)
            ->getQuery();
            $photo = $queryFotin->getResult();

        $images[0] = base64_encode(stream_get_contents($photo[0]['foto']));

        return $this->render('limubacadministratorBundle:administracion:edita.html.twig',array('images' => $images));
        /*
        $request = $this->getRequest();
        $workerId = $request->get('number');
        if (empty($workerId)){
            throw $this->createNotFoundException('Error, problema con su solicitud. Intentalo mas tarde.');
        }
        else{
            $repositor = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
            $queryFotin = $repositor->createQueryBuilder('f')
            ->select('f.foto')
            ->where('f.idFoto = :word')
            ->setParameter('word', $workerId)
            ->getQuery();
            $photo = $queryFotin->getResult();
            
            if (empty($photo)){
                throw $this->createNotFoundException('No se encontro el recurso solicitado.');
            }
            else{
                $response = new \Symfony\Component\HttpFoundation\Response(stream_get_contents($photo[0]['foto']),200,array( 
                    'Content-Type' => 'application/octet-stream',
                ));

                /*
                $response = new StreamedResponse(function () use ($photo) {
                    echo stream_get_contents($photo[0]['foto']);
                });

                $response->headers->set('Content-Type', 'image/jpeg');
                
                return $response;
            }
        }
        
        $em = $this->get('doctrine')->getManager();
        $image_obj = $em->getRepository('limubacadministratorBundle:Fotos')->find('foto',$request->get('number'));
        return new Response(
            $image_obj->getImg(),
            Response::HTTP_OK,
            array('content-type' => 'image/jpg')
        );
        
        /*
        -------------------------------------------------------------------------------------
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
        $foto = $repository->find('id_foto',$request->get('number'));
        if (count($foto)>0) {
            $response = $this->getResponse();
            $response -> setContent('image');
            $response->headers->set('Content-Type', 'image/jpeg');
            echo $foto -> getInhalt();
        }else{
            return false;
        }

        */
    }

   
	
    public function buscarAction(){
        return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
    }

    public function editarAction(){
        //print_r($_REQUEST['jugador']);

        $upt = $_REQUEST['jugador'];
        //echo("<br>aiidiii: ".$upt['idJugador']);
        $fn = $upt['fNacimiento'];
        $dt = date_create_from_format('Y-m-d', $fn);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Status');
        $category1 = $class_repository->find($upt['idStatus']);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Genero');
        $category2 = $class_repository->find($upt['idGenero']);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
        $category3 = $class_repository->find($upt['idTiposanguineo']);

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
        $queryF = $repository->createQueryBuilder('f')
        ->select('f.idFoto')
        ->where('f.nombre = :word')
        ->setParameter('word', $upt['idJugador'])
        ->getQuery();
        $resul = $queryF->getResult();

        //print_r($resul[0]['idFoto']);

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
        $queryAct = $repository->createQueryBuilder('z');
        $q = $queryAct->update('limubacadministratorBundle:Jugador', 'z')
            ->set('z.nombre', ':nom')
            ->set('z.apPaterno', ':app')
            ->set('z.apMaterno', ':apm')    
            ->set('z.fNacimiento', ':fna')
            ->set('z.correo', ':cor')
            ->set('z.telefono', ':tel')
            ->set('z.profesion', ':pro')
            ->set('z.idStatus', ':ist')
            ->set('z.idGenero', ':ige')
            ->set('z.estatura', ':est')
            ->set('z.peso', ':pes')
            ->set('z.idTiposanguineo', ':iti')
            ->set('z.idFoto', ':fot')
            ->where('z.idJugador= :idj')
            ->setParameter('idj', $upt['idJugador'])
            ->setParameter('nom', $upt['nombre'])
            ->setParameter('app', $upt['apPaterno'])
            ->setParameter('apm', $upt['apMaterno'])
            ->setParameter('fna', new \DateTime($fn))
            ->setParameter('cor', $upt['correo'])
            ->setParameter('tel', $upt['telefono'])
            ->setParameter('pro', $upt['profesion'])
            ->setParameter('ist', $category1)
            ->setParameter('ige', $category2)
            ->setParameter('est', $upt['estatura'])
            ->setParameter('pes', $upt['peso'])
            ->setParameter('iti', $category3)
            ->setParameter('fot', $resul[0]['idFoto'])
            ->getQuery();
        $resul = $q->execute();

        return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));

    }

	

    public function uploadosAction(Request $request){
        if (isset($_POST['submit'])) {
            $pics = new Fotos();
            $idjug = $_REQUEST['id'];
            $status = "success";
            $message = '';
            $per = array(0 => $idjug);
            
            if ($_FILES['img']['error'] == 0) {
                $imageName = mysql_real_escape_string($_FILES['img']['name']);
                $imageData = mysql_real_escape_string(file_get_contents($_FILES['img']['tmp_name']));
                $imageType = mysql_real_escape_string($_FILES['img']['type']);

                if (substr($imageType,0,5) == "image") {
                    $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
                    $queryEdit = $repository->createQueryBuilder('e')
                        ->select('e.idFoto','e.nombre')
                        ->where('e.nombre = :word')
                        ->setParameter('word', $idjug)
                        ->getQuery();
                    $resul1 = $queryEdit->getResult();

                    $pics -> setNombre($idjug);
                    $pics -> setFoto($imageData);

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($pics);
                    $em -> flush();
                    $pics -> getIdFoto();

                    
                    $category = $pics->getIdFoto();

                    $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
                    $queryAct = $repository->createQueryBuilder('f');
                    $q = $queryAct->update('limubacadministratorBundle:Jugador', 'f')
                        ->set('f.idFoto', ':fot')
                        ->where('f.idJugador= :idj')
                        ->setParameter('fot', $category)
                        ->setParameter('idj', $idjug)
                        ->getQuery();
                    $resul = $q->execute();
                    $per = array(0 => $idjug);

                    if (is_array($resul1) and !empty($resul1)) {
                        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
                        $queryDelete = $repository->createQueryBuilder('d')
                            ->delete()
                            ->where('d.idFoto = '.$resul1[0]['idFoto'])
                            ->getQuery();
                        $entities = $queryDelete->getResult();    
                    }

                    $status = "success";
                    $message = "Imagen guardada correctamente";
                }
                else{
                    $status = "failed";
                    $message = "Solo se permiten imagenes.<br>Intentalo de nuevo con un archivo valido.";
                }
            }
            else{
                echo('Formato invalido');
                $status = "failed";
                $message = "Solo se permiten imagenes en formato JPG.<br>Intentalo de nuevo con un archivo valido.";
            }
            return $this->render('limubacadministratorBundle:administracion:uploados.html.twig',array('status'=>$status,'message'=>$message, 'person' => $per));
        }
    }
    public function uploadAction(Request $request){
        if ($request->getMethod() == 'POST') {
            $image = $request->files->get('img');
            $status = 'success';
            $uploadedURL='';
            $message='';
            if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
                if (($image->getSize() < 200000000000)) {
                    $originalName = $image->getClientOriginalName();
                    $name_array = explode('.', $originalName);
                    $file_type = $name_array[sizeof($name_array) - 1];
                    $valid_filetypes = array('jpg', 'jpeg', 'bmp', 'png');
                    if (in_array(strtolower($file_type), $valid_filetypes)) {
                        //Start Uploading Image
                        //$document = new Document();
                        //$document->setFile($image);
                        //$document->setSubDirectory('/upload');
                        //$document->processFile();
                        //$uploadedURL=$uploadedURL = $document->getUploadDirectory() . DIRECTORY_SEPARATOR . $document->getSubDirectory() . DIRECTORY_SEPARATOR . $image->getBasename();
                    }else{
                        $status="failed";
                        $message="Tipo de archivo invalido";
                    }
                }else{
                   $status="failed";
                   $message="Tamaño demasiado grande";
                }
            }else{
                $status="failed";
                $message="Error en el archivo";
            }
            return $this->render('limubacadministratorBundle:administracion:upload.html.twig',array('status'=>$status,'message'=>$message,'uploadedURL'=>$uploadedURL));
        } else{
            return $this->render('limubacadministratorBundle:administracion:upload.html.twig');
        }

    }
    //-----------------------FINAL CONTROLADRO DE FAFI---------------------------------------------------

    //CONTROLADOR TORNEO

    public function torneosAction(){
/*
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:ParticipanT');
        $queryTorneos = $repository->createQueryBuilder('p')
            ->select('t.idTorneo','t.nombre','t.fInicio','t.fTermino','t.costo','r.nombre AS rama', 'c.nombre AS categ')
            ->join('limubacadministratorBundle:Torneo', 't', 'WITH' ,'t.idTorneo = p.idTorneo')
            ->join('limubacadministratorBundle:RamaEquipo', 'r', 'WITH' ,'r.idRama = p.idRama')
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'c.idCategoria = p.idCategoria')
            ->orderBy('t.idTorneo', 'DESC')
            ->getQuery();
        $entities = $queryTorneos->getResult();
*/
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
        $queryTorneos = $repository->createQueryBuilder('t')
            ->select('t.idTorneo','t.nombre','t.fInicio','t.fTermino','t.costo')
            ->orderBy('t.idTorneo', 'DESC')
            ->getQuery();
        $entities = $queryTorneos->getResult();
        return $this->render('limubacadministratorBundle:administracion:torneos.html.twig',array('entities' => $entities));
    }

    public function crearTorneoAction(){   
        $torneo = new Torneo();
        $form = $this->createForm(new TorneoType(), $torneo);

        $request = $this->get('request');
        $form->handleRequest($request);

        if ($request->getMethod() == 'GET') {
            $url_to_parse = $_SERVER['REQUEST_URI'];
            $parsed_url = parse_url($url_to_parse);
            if (empty($parsed_url['query'])) {
                return $this->render('limubacadministratorBundle:administracion:crearTorneo.html.twig',array('form' => $form->createView()));
            }
            else{
                $url_query = $parsed_url['query'];
                parse_str($url_query,$out);
                if (is_array($out) && !empty($out)) {
                    $tor = new Torneo();
                    //print_r($out);
                    $tor -> setNombre($out['torneo']['nombre']);
                    $fn = $out['torneo']['fInicio'];
                    $dt = date_create_from_format('Y-m-d', $fn);
                    $tor -> setFInicio(new \DateTime($fn));

                    $fn = $out['torneo']['fTermino'];
                    $dt = date_create_from_format('Y-m-d', $fn);
                    $tor -> setFTermino(new \DateTime($fn));

                    $tor -> setCosto($out['torneo']['costo']);

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($tor);
                    $em -> flush();

                    return $this->redirect($this->generateUrl('limubacadministrator_torneos'));
                }
                else{
                    return new SymfonyResponse('Algo Fallo!');   
                }
            }
        }

        return $this->render('limubacadministratorBundle:administracion:crearTorneo.html.twig',array('form' => $form->createView()));
    }

    public function acTorneoAction(){
        if(!empty($_REQUEST['edit'])){
            echo('entro');
            break;
        }
        else{
            echo('no entro');
            break;
        }
    }

    public function categoriasAction(){
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Categoria');
        $queryCategorias = $repository->createQueryBuilder('c')
            ->select('c.idCategoria','c.nombre','c.edad','c.limiteEquipo')
            ->orderBy('c.idCategoria', 'DESC')
            ->getQuery();
        $entities = $queryCategorias->getResult();


        return $this->render('limubacadministratorBundle:administracion:categorias.html.twig',array('entities'=>$entities));
    }

    public function crearCategoriaAction(){
        $categoria = new Categoria();
        $form = $this->createForm(new CategoriaType(), $categoria);

        $request = $this->get('request');
        $form->handleRequest($request);

        if ($request->getMethod() == 'GET') {
            $url_to_parse = $_SERVER['REQUEST_URI'];
            $parsed_url = parse_url($url_to_parse);
            if (empty($parsed_url['query'])) {
                return $this->render('limubacadministratorBundle:administracion:crearCategoria.html.twig',array('form' => $form->createView()));
            }
            else{
                $url_query = $parsed_url['query'];
                parse_str($url_query,$out);
                if (is_array($out) && !empty($out)) {
                    $cat = new Categoria();
                    //print_r($out);
                    $cat -> setNombre($out['categoria']['nombre']);

                    $cat -> setLimiteEquipo($out['categoria']['limiteEquipo']);

                    $cat -> setEdad($out['categoria']['edad']);

                    $cat -> setRefEdad($out['categoria']['refEdad']);
                    

                    $em = $this->getDoctrine()->getManager();
                    $em -> persist($cat);
                    $em -> flush();

                    return $this->redirect($this->generateUrl('limubacadministrator_categorias'));
                }
                else{
                    return new SymfonyResponse('Algo Fallo!');   
                }
            }
        }

        return $this->render('limubacadministratorBundle:administracion:crearCategoria.html.twig',array('form' => $form->createView()));
    }

    //FINAL CONTROLADOR TORNEO

}
