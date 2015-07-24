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
		use limubac\administratorBundle\Entity\Finanzas;
		use limubac\administratorBundle\Entity\Partido;
		use limubac\administratorBundle\Entity\Juegan;
		use limubac\administratorBundle\Entity\TipoSanguineo;
		use limubac\administratorBundle\Entity\DetallePartido;
		use limubac\administratorBundle\Entity\FaltasEquipo;
		use limubac\administratorBundle\Entity\Faltas;
		use limubac\administratorBundle\Entity\Asistencia;
		use limubac\administratorBundle\Form\Type\JugadorType;
		use limubac\administratorBundle\Form\Type\JugadorAType;
		use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
		use Symfony\Component\Validator\Constraints\DateTime;
        use limubac\administratorBundle\Form\Type\TorneoType;
        use limubac\administratorBundle\Form\Type\CategoriaType;
        use limubac\administratorBundle\Form\Type\TorneoBType;


class DefaultController extends Controller{

	public function indexAction($name){
        return $this->render('limubacadministratorBundle:Default:index.html.twig', array('name' => $name));
    }

    public function adminAction(){
        return $this->render('limubacadministratorBundle:administracion:adminPanel.html.twig');
    }

    public function hojaAnotacionesAction(){

        $consultasManager = new ConsultasAnotaciones();
        $contadorA=0;
        $contadorB=0;
        $marcadorA=0;
        $marcadorB=0;
        $request = $this->getRequest();
        $doctrineManager= $this -> getDoctrine()->getManager();
        $idPartido=1;//SE SUPONE LO RECIBE DE PARAMETRO...
        $idJugador;

        //echo $request->getMethod() ;//Quitar o comentar

         $List_A=$consultasManager->listJugadoresEquipo('A',$idPartido,$doctrineManager);
        // print_r($List_A);
         $List_B=$consultasManager->listJugadoresEquipo('B',$idPartido,$doctrineManager);

        if($request->getMethod() == 'POST')//si se envia el formulario
            {
              $datos = new hojaAnotacion();

              $validator = $this->get('validator');

              /*$datos->setEqA($_POST["EqA"]);
                $datos->setEqB($_POST["EqB"]);
                $datos->setRama($_POST["Rama"]);
                $datos->setCategoria($_POST["Categoria"]);
                $datos->setLugar($_POST["Lugar"]);
                $datos->setTorneo($_POST["Torneo"]);
                $datos->setFecha($_POST["Fecha"]);
                $datos->setHora($_POST["Hora"]);*/
                $datos->setJuez1($_POST["Juez1"]);
                $datos->setJuez2($_POST["Juez2"]);

                //TOMAR ASISTENCIA

                if(isset($_POST['asistA'])){
	                	$assistA=$_POST['asistA'];
	                }

	                if(isset($_POST['asistB'])){
	                	$assistB=$_POST['asistB'];
	                }

	                if(!empty($assistA)){

	                	foreach ($assist as $a) {
	                	  	$asistencia = new Asistencia();
	                		$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
												$auxFinder= $auxRepository->find(''.$idPartido);
	                		$asistencia->setIdPartido($auxFinder);

	                		$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
							$auxFinder= $auxRepository->find($a);
							$asistencia->setIdJugador($auxFinder);

							$doctrineManager-> persist($asistencia);
							$doctrineManager-> flush();
	               		}
	               	}

	               	if(!empty($assistB)){
		               	foreach ($assist as $a) {
		                	  	$asistencia = new Asistencia();
		                		$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
													$auxFinder= $auxRepository->find(''.$idPartido);
		                		$asistencia->setIdPartido($auxFinder);

		                		$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
								$auxFinder= $auxRepository->find($a);
								$asistencia->setIdJugador($auxFinder);

								$doctrineManager-> persist($asistencia);
								$doctrineManager-> flush();
		               	}
	              }
	            //END TOMAR ASISTENCIA

	            //FALTAS

	              $idsJugadores=array_keys($_POST['faltasA']);
	              print_r($idsJugadores);
	               if(isset($_POST['faltasA'])){
	                	$faltasA=$_POST['faltasA'];
	                }

	                if(isset($_POST['faltasB'])){
	                	$faltasB=$_POST['faltasB'];
	                }

	                foreach ($idsJugadores as $jugador => $Idjugador) {
	                	foreach ($faltasA[$Idjugador] as $falta=> $Idfalta) {

	                	if($Idfalta!=0){

	                		$queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador)
		                    												FROM limubacadministratorBundle:FaltasEquipo d
		                    												 WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$Idjugador.
		                    												 	'AND d.idFalta= '.$Idfalta);
								$falta=$queryDetalleList->getResult();

							if(empty($falta)){
								$faltasEquipo= new FaltasEquipo();

								$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Falta');
								$auxFinder= $auxRepository->find(''.$Idfalta);
								$faltasEquipo->setIdFalta($auxFinder);

								$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
								$auxFinder= $auxRepository->find(''.$Idjugador);
								$faltasEquipo->setIdJugador($auxFinder);

								$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
								$auxFinder= $auxRepository->find(''.$idPartido);
								$faltasEquipo->setIdPartido($auxFinder);

								$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
								$auxConsulta=$consultasManager->GetIdEquipoByIdJugadorAndIdPartido($Idjugador,$idPartido,$doctrineManager);
								print_r($auxConsulta);
								$auxFinder= $auxRepository->find(''.$auxConsulta[0][1]);
								$faltasEquipo->setIdEquipo($auxFinder);
								$faltasEquipo->setTiempo(0);
								$faltasEquipo->setDescFalta("");

								$doctrineManager-> persist($faltasEquipo);
                    			$doctrineManager -> flush();

							}else{
								$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Equipo');
								$auxConsulta=$consultasManager->GetIdEquipoByIdJugadorAndIdPartido($Idjugador,$idPartido,$doctrineManager);
								$consultasManager-> updateCantidadFalta($Idjugador,$idPartido,$Idfalta,$auxConsulta[0][1],$doctrineManager);

							}
	                	}
	                }
	                }


	            //END FALTAS

                $datos->setPuntos(array_values((array_slice($_POST, 6,40))));

                $puntos = $datos->getPuntos();
                $puntosA=array();
                $puntosB=array();

               if(!empty($puntos)){
               	print_r($puntos);
                for ($j=0; $j < count($puntos); $j++) { //VALIDAR NUMEROS DE PLAYERA

	                if(preg_match("/^([0-9])*([0-9])*$/",$puntos[$j])) {
						//echo 'CORRECTO';
	                    if(($j%2)==0){
	                        $puntosA[]=$puntos[$j];
	                    }else{
	                        $puntosB[]=$puntos[$j];
	                    }
	                }else{
	                	//echo 'INCORRECTO';
	                	break;
	                }

                }

               for ($i=0; $i < count($puntosA); $i++) {
                 if($i==0 && $puntosA[$i]!=''){//ANOTACION DE UN PUNTO
                    $marcadorA++;
                    $x=$consultasManager->getIdJugadorByPlayera($puntosA[$i],'A',$doctrineManager);

                    $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 1');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(1);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,1,1,$doctrineManager);
								}

                 }else{
                    if($puntosA[$i]==''){//BRINCOS, ESPACIOS EN BLANCO
                        $contadorA++;

                    }else{
                        if($contadorA==0){
                            $marcadorA=$marcadorA+1;//ANOTACION DE UN PUNTO
                            $x=$consultasManager->getIdJugadorByPlayera($puntosA[$i],'A',$doctrineManager);
                            	$queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 1');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(1);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,1,1,$doctrineManager);
								}

                        }else{
                        if($contadorA==1){
                            $marcadorA=$marcadorA+2;//ANOTACION DE DOS PUNTOS
                            $contadorA=0;
                            $x=$consultasManager->getIdJugadorByPlayera($puntosA[$i],'A',$doctrineManager);
                            $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 2');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(2);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,2,1,$doctrineManager);
								}
                        }else{
                            if($contadorA==2){
                                $marcadorA=$marcadorA+3;//ANOTACION DE TRES PUTO
                                $contadorA=0;
                                $x=$consultasManager->getIdJugadorByPlayera($puntosA[$i],'A',$doctrineManager);
                                $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 3');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(3);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,2,1,$doctrineManager);
								}

                            }
                        }
                     }
                    }
                 }
               }

              print_r($puntosA);
              print_r($puntosB);


                for ($i=0; $i < count($puntosB); $i++) {
                 if($i==0 && $puntosB[$i]!=''){
                    $marcadorB++;
                    $x=$consultasManager->getIdJugadorByPlayera($puntosB[$i],'B',$doctrineManager);

                    $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 1');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(1);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,1,1,$doctrineManager);
								}
                 }else{
                    if($puntosB[$i]==''){
                        $contadorB++;

                    }else{
                        if($contadorB==0){
                            $marcadorB=$marcadorB+1;
                            $x=$consultasManager->getIdJugadorByPlayera($puntosB[$i],'B',$doctrineManager);

                   			 $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    														FROM limubacadministratorBundle:DetallePartido d
		                    															WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 1');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(1);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,1,1,$doctrineManager);
								}
                        }else{
                        if($contadorB==1){
                            $marcadorB=$marcadorB+2;
                            $contadorB=0;
                            $x=$consultasManager->getIdJugadorByPlayera($puntosB[$i],'B',$doctrineManager);
                            $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 2');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(2);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,2,1,$doctrineManager);
								}
                        }else{
                            if($contadorB==2){
                                $marcadorB=$marcadorB+3;
                                $contadorB=0;

                                $x=$consultasManager->getIdJugadorByPlayera($puntosB[$i],'B',$doctrineManager);
                                $queryDetalleList = $doctrineManager->createQuery('SELECT IDENTITY (d.idJugador),d.anotaciones,d.cantidad
		                    													FROM limubacadministratorBundle:DetallePartido d
		                    														WHERE d.idPartido=' . $idPartido .'AND d.idJugador=' .$x[0]["id"]. 'AND d.anotaciones= 3');
								$anotacion=$queryDetalleList->getResult();

								if(count($anotacion)<=0){//verificar
									$detallePartido = new DetallePartido();
									$detallePartido->setAnotaciones(3);
									$detallePartido->setCantidad(1);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Partido');
											$auxFinder= $auxRepository->find(''.$idPartido);
												$detallePartido->setIdPartido($auxFinder);

										$auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
											$auxFinder= $auxRepository->find(''.$x[0]["id"]);
												$detallePartido->setIdJugador($auxFinder);

									$doctrineManager-> persist($detallePartido);
                    				$doctrineManager -> flush();

								}else{
									$consultasManager->updateCantidadAnotacion($x[0]["id"],$idPartido,2,1,$doctrineManager);
								}
                            }
                        }
                     }
                    }
                 }
               }
            }


                $errors = $validator->validate($datos);

                    if (count($errors) > 0) {
                      $errorsString = (string) $errors;
                    //echo $errorsString;
                    }
            }

    	return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B));

    }

   public function nombresEquiposAction(){
   	$request = $this->container->get('request');
   	$idPartido=1;
   	$consultasManager = new ConsultasAnotaciones();
	$doctrineManager= $this -> getDoctrine()->getManager();

	$Equipos=json_encode($consultasManager->getEquipoByPartido($idPartido,$doctrineManager));


	//echo ($EquipoA);


	return new Response($Equipos);

   }

    // TORNEO

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
            ->select('t.idTorneo','t.nombre','t.fInicio','t.fTermino','t.costo','t.inscripcionAbierta')
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

                    $tor -> setInscripcionAbierta("1");

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
	//print_r($_REQUEST);
        if(!empty($_REQUEST['edit'])){
            $torneo = new Torneo();
            $form = $this->createForm(new TorneoType(), $torneo);
            $ed = $_REQUEST['edit'][0];
            
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idTorneo','e.nombre','e.fInicio','e.fTermino','e.costo')
            ->where('e.idTorneo = :word')
            ->setParameter('word', $ed)
            ->getQuery();
            $resul = $queryEdit->getResult();
            //print_r($resul);
            
            return $this->render('limubacadministratorBundle:administracion:acTorneo.html.twig',array('form' => $form->createView(), 'edita' => $resul));
        }else if(!empty($_REQUEST['rol'])){
			$idtorn = array($_REQUEST['rol'][0]);
			//select * from participan_t where id_torneo = 1 GROUP BY id_rama 
			$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:ParticipanT');
			$queryCategorias = $repository->createQueryBuilder('h')
				->select('e.idCategoria,e.nombre')
				->join('limubacadministratorBundle:Equipo','eq','WITH','h.idEquipo = eq.idEquipo')
				->join('limubacadministratorBundle:Categoria', 'e', 'WITH' ,'e.idCategoria = eq.idCategoria')
				->where('h.idTorneo = :torn')
				->groupBy('eq.idCategoria')
				->setParameter('torn',$idtorn[0])
				->getQuery();
			$n1 = $queryCategorias->getResult();			
			//print_r($n1);
$queryRamas = $repository->createQueryBuilder('l')
				->select('o.idRama,o.nombre')
				->join('limubacadministratorBundle:Equipo','ez','WITH','l.idEquipo = ez.idEquipo')
				->join('limubacadministratorBundle:RamaEquipo', 'o', 'WITH' ,'o.idRama = ez.idRama')
				->where('l.idTorneo = :torn')
				->groupBy('ez.idRama')
				->setParameter('torn',$idtorn[0])
				->getQuery();
			$n2 = $queryRamas->getResult();
			return $this->render('limubacadministratorBundle:administracion:roldejuego.html.twig',array('rols'=>$idtorn,'categs'=>$n1,'ramas'=>$n2));
		}else if(!empty($_REQUEST['noInsc'])){
            $idtoor = $_REQUEST['noInsc'][0];
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
	        $queryAct = $repository->createQueryBuilder('z');
	        $q = $queryAct->update('limubacadministratorBundle:Torneo', 'z')
	            ->set('z.inscripcionAbierta', '0')   
	            ->where('z.idTorneo= :idt')
	            ->setParameter('idt', $idtoor)
	            ->getQuery();
	        $resul = $q->execute();

		$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
        $queryTorneos = $repository->createQueryBuilder('t')
            ->select('t.idTorneo','t.nombre','t.fInicio','t.fTermino','t.costo','t.inscripcionAbierta')
            ->orderBy('t.idTorneo', 'DESC')
            ->getQuery();
        $entities = $queryTorneos->getResult();

	        return $this->render('limubacadministratorBundle:administracion:torneos.html.twig',array('entities' => $entities));
	    }else if(!empty($_REQUEST['insc'])){
            $idtooor = $_REQUEST['insc'][0];
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
	        $queryAct = $repository->createQueryBuilder('z');
	        $q = $queryAct->update('limubacadministratorBundle:Torneo', 'z')
	            ->set('z.inscripcionAbierta', '1')   
	            ->where('z.idTorneo= :idt')
	            ->setParameter('idt', $idtooor)
	            ->getQuery();
	        $resul = $q->execute();

		$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
        $queryTorneos = $repository->createQueryBuilder('t')
            ->select('t.idTorneo','t.nombre','t.fInicio','t.fTermino','t.costo','t.inscripcionAbierta')
            ->orderBy('t.idTorneo', 'DESC')
            ->getQuery();
        $entities = $queryTorneos->getResult();

	        return $this->render('limubacadministratorBundle:administracion:torneos.html.twig',array('entities' => $entities));			
		}else if(!empty($_REQUEST['ver'])){
            $idtor = array($_REQUEST['ver'][0]);
                /*SELECT  e.nombre AS equipo, c.nombre AS categoria, r.nombre AS rama
                FROM participan_t p 
                INNER JOIN equipo e  ON p.id_equipo=e.id_equipo
                INNER JOIN categoria c  ON p.id_categoria=c.id_categoria
                INNER JOIN rama_equipo r ON p.id_rama=r.id_rama
                WHERE p.id_torneo=6;*/
            
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:ParticipanT');
            $queryEdit = $repository->createQueryBuilder('p')
            ->select('e.nombre AS equipo','c.nombre AS categoria','r.nombre AS rama','t.nombre AS torneo')
            ->join('limubacadministratorBundle:Equipo', 'e', 'WITH' ,'p.idEquipo = e.idEquipo')
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'e.idCategoria = c.idCategoria')
            ->join('limubacadministratorBundle:RamaEquipo', 'r', 'WITH' ,'e.idRama = r.idRama')
            ->join('limubacadministratorBundle:Torneo', 't', 'WITH' ,'p.idTorneo = t.idTorneo')
            ->where('p.idTorneo = :word')
            ->orderBy('categoria')
            ->setParameter('word', $idtor[0])
            ->getQuery();
            $resul = $queryEdit->getResult();
            //print_r($resul);
            
            if(!empty($resul)){
                return $this->render('limubacadministratorBundle:administracion:participan.html.twig',array( 'ver' => $resul));
            }else{
                return $this->redirect('limubacadministratorBundle:administracion:torneos.html.twig');
            }


        }
    }

    public function editTorneoAction(){
        $upt = $_REQUEST['torneo'];

        $fni = $upt['fInicio'];
        $di = date_create_from_format('Y-m-d', $fni);

        $fnt = $upt['fTermino'];
        $dt = date_create_from_format('Y-m-d', $fnt);


        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
        $queryAct = $repository->createQueryBuilder('z');
        $q = $queryAct->update('limubacadministratorBundle:Torneo', 'z')
            ->set('z.nombre', ':nom')   
            ->set('z.fInicio', ':fni')
            ->set('z.fTermino', ':fnt')
            ->set('z.costo', ':cos')
            ->where('z.idTorneo= :idt')
            ->setParameter('idt', $upt['idTorneo'])
            ->setParameter('nom', $upt['nombre'])
            ->setParameter('fni', new \DateTime($fni))
            ->setParameter('fnt', new \DateTime($fnt))
            ->setParameter('cos', $upt['costo'])
            ->getQuery();
        $resul = $q->execute();

        return $this->redirect($this->generateUrl('limubacadministrator_torneos'));
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

    public function acCategoriaAction(){
        if(!empty($_REQUEST['edit'])){
            $categoria = new Categoria();
            $form = $this->createForm(new CategoriaType(), $categoria);
            $ed = $_REQUEST['edit'][0];
            
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Categoria');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idCategoria','e.nombre','e.edad','e.limiteEquipo','e.refEdad')
            ->where('e.idCategoria = :word')
            ->setParameter('word', $ed)
            ->getQuery();
            $resul = $queryEdit->getResult();
            //print_r($resul);
            
            return $this->render('limubacadministratorBundle:administracion:acCategoria.html.twig',array('form' => $form->createView(), 'edita' => $resul));
        }
    }

    public function editCategoriaAction(){
        $upt = $_REQUEST['categoria'];

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Categoria');
        $queryAct = $repository->createQueryBuilder('z');
        $q = $queryAct->update('limubacadministratorBundle:Categoria', 'z')
            ->set('z.nombre', ':nom')   
            ->set('z.edad', ':edd')
            ->set('z.limiteEquipo', ':lme')
            ->set('z.refEdad', ':red')
            ->where('z.idCategoria= :idc')
            ->setParameter('idc', $upt['idCategoria'])
            ->setParameter('nom', $upt['nombre'])
            ->setParameter('edd', $upt['edad'])
            ->setParameter('lme', $upt['limiteEquipo'])
            ->setParameter('red', $upt['refEdad'])
            ->getQuery();
        $resul = $q->execute();

        return $this->redirect($this->generateUrl('limubacadministrator_categorias'));
    }

    //FINAL CONTROLADOR TORNEO


    //****************************INICIO CONTROLADOR FINANZAS****************************
    public function finanzasAction(){
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
            $querytorn = $repository->createQueryBuilder('e')
            ->select('e.idTorneo as ide','e.nombre AS nombre')
            ->orderBy('nombre')
            ->getQuery();
        $resultor = $querytorn->getResult();

    	$ing[0][1]= 0;
    	$ins[0][1]= 0;
    	$pen[0][1]= 0;

    	$resul=null;

        return $this->render('limubacadministratorBundle:administracion:finanzas.html.twig', array('ingresos' => $ing, 'inscritos' => $ins, 'pendientes' => $pen, 'query' => $resul, 'torneos' => $resultor));
    }

    public function actfinAction(){
    	$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
            $querytorn = $repository->createQueryBuilder('e')
            ->select('e.idTorneo as ide','e.nombre AS nombre')
            ->orderBy('nombre')
            ->getQuery();
        $resultor = $querytorn->getResult();

    	if (!empty($_REQUEST['edit'])) {
    		$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Finanzas');
            $queryEdit = $repository->createQueryBuilder('f')
            ->select('e.idEquipo as ide','e.nombre AS equipo','c.nombre AS categoria','f.inscripcion','f.dia','f.hora','f.monto','f.cuenta','f.manejo','f.enero','f.febrero','f.marzo','f.abril','f.mayo','f.junio')
            ->join('limubacadministratorBundle:Equipo', 'e', 'WITH' ,'f.idEquipo = e.idEquipo')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = f.idEquipo')
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'p.idCategoria = c.idCategoria')
            ->where('f.idFinanzas = :aidi')
            ->setParameter('aidi', $_REQUEST['edit'])
            ->orderBy('categoria')
            ->getQuery();
        	$resul = $queryEdit->getResult();

    	 	return $this->render('limubacadministratorBundle:administracion:editaFinanzas.html.twig', array('query' => $resul));
    	}
    	elseif (!empty($_REQUEST['pdf'])) {
			
    	}
    	elseif (!empty($_REQUEST['sel'])) {
    	 	$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Finanzas');
            $queryEdit = $repository->createQueryBuilder('f')
            ->select('e.idEquipo as ide','e.nombre AS equipo','c.nombre AS categoria','f.inscripcion','f.dia','f.hora','f.monto','f.cuenta','f.manejo','f.enero','f.febrero','f.marzo','f.abril','f.mayo','f.junio')
            ->join('limubacadministratorBundle:Equipo', 'e', 'WITH' ,'f.idEquipo = e.idEquipo')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = f.idEquipo')
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'p.idCategoria = c.idCategoria')
            ->where('f.idTorneo = :word AND p.idTorneo = :word')
            ->setParameter('word', $_REQUEST['sel'])
            ->orderBy('categoria')
            ->getQuery();
        	$resul = $queryEdit->getResult();

        	$queryIngresos = $repository->createQueryBuilder('i')
            ->select('sum(i.inscripcion + i.cuenta + i.enero + i.febrero + i.marzo + i.abril + i.mayo + i.junio)')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = i.idEquipo')
            ->where('i.idTorneo = :word AND  p.idTorneo = :word')
            ->setParameter('word', $_REQUEST['sel'])
            ->getQuery();
        	$ing = $queryIngresos->getResult();

        	$queryInscritos = $repository->createQueryBuilder('n')
            ->select('count(n.idEquipo)')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = n.idEquipo')
            ->where('n.idTorneo = :word AND  p.idTorneo = :word')
            ->setParameter('word', $_REQUEST['sel'])
            ->getQuery();
        	$ins = $queryInscritos->getResult();

        	$queryPendientes = $repository->createQueryBuilder('n')
            ->select('count(n.idEquipo)')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = n.idEquipo')
            ->where('n.idTorneo = :word AND  p.idTorneo = :word AND n.inscripcion != 500')
            ->setParameter('word', $_REQUEST['sel'])
            ->getQuery();
        	$pen = $queryPendientes->getResult();

    	 	return $this->render('limubacadministratorBundle:administracion:finanzas.html.twig', array('ingresos' => $ing, 'inscritos' => $ins, 'pendientes' => $pen, 'query' => $resul, 'torneos' => $resultor));
    	}
    	elseif (empty($_REQUEST['pdf']) || empty($_REQUEST['sel'])) {
            return $this->redirect($this->generateUrl('limubacadministrator_finanzas'));
        }
    }

    public function editfinAction(){
    	$eqp = $_REQUEST['equipo'];

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Finanzas');
        $queryUpt = $repository->createQueryBuilder('f');
        $qu = $queryUpt->update('limubacadministratorBundle:Finanzas', 'f')
            ->set('f.inscripcion', ':ins')   
            ->set('f.dia', ':dia')
            ->set('f.hora', ':hra')
            ->set('f.monto', ':mnt')
            ->set('f.cuenta', ':cnt')
            ->set('f.manejo', ':mnj')
            ->set('f.enero', ':ene')
            ->set('f.febrero', ':feb')
            ->set('f.marzo', ':mar')
            ->set('f.abril', ':abr')
            ->set('f.mayo', ':may')
            ->set('f.junio', ':jun')
            ->where('f.idFinanzas= :idc')
            ->setParameter('ins', $_REQUEST['inscripcion'])
            ->setParameter('dia', $_REQUEST['dia'])
            ->setParameter('hra', $_REQUEST['hora'])
            ->setParameter('mnt', $_REQUEST['monto'])
            ->setParameter('cnt', $_REQUEST['acuenta'])
            ->setParameter('mnj', $_REQUEST['manejo'])
            ->setParameter('ene', $_REQUEST['enero'])
            ->setParameter('feb', $_REQUEST['febrero'])
            ->setParameter('mar', $_REQUEST['marzo'])
            ->setParameter('abr', $_REQUEST['abril'])
            ->setParameter('may', $_REQUEST['mayo'])
            ->setParameter('jun', $_REQUEST['junio'])
            ->setParameter('idc', $_REQUEST['ides'])
            ->getQuery();
        $res = $qu->execute();

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Torneo');
            $querytorn = $repository->createQueryBuilder('e')
            ->select('e.idTorneo as ide','e.nombre AS nombre')
            ->orderBy('nombre')
            ->getQuery();
        $resultor = $querytorn->getResult();

    	$ing[0][1]= 0;
    	$ins[0][1]= 0;
    	$pen[0][1]= 0;

    	return $this->redirect($this->generateUrl('limubacadministrator_finanzas'));
    }
    //****************************FINAL CONTROLADOR FINANZAS****************************

	//****************************INICIO CONTROLADOR DESCARGAS****************************    
	public function descargasAction(){
		$dr = __DIR__.'/../../../../web/upload/documentos/';
		$dir = opendir($dr);
		$archivo = array();
		$cont = 0;
		while ($arch = readdir($dir)){
			if ($arch != '.' && $arch != '..'){
				$v_arc_tem = $arch; //imagen.png
				list($v_name,$v_ext) = explode(".",$v_arc_tem);
				
				$archivo[$cont]['nombre'] = $v_name;
				$archivo[$cont]['tipo'] = $v_ext;
				$archivo[$cont]['tamano'] = round (filesize($dr.$arch)/1024,1);
				$archivo[$cont]['fecha'] = date ("m/d (H:i)", filemtime ($dr.$arch));
				$cont+=1;
			}
		}

		return $this->render('limubacadministratorBundle:administracion:descargas.html.twig',array('datos' => $archivo));
	}

	public function subirAction(){
		if (isset($_POST['up'])){
			if (isset($_FILES["file"])) {
				$dir = __DIR__.'/../../../../web/upload/documentos/';
				$validextensions = array("jpeg", "jpg", "png","txt", "pdf", "xlsx", "ppt", "doc", "pptx");
	            $temporary = explode(".", $_FILES["file"]["name"]);
	            $file_extension = end($temporary);

	            if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg") 
	            	|| ($_FILES["file"]["type"] == "application/octet-stream") || ($_FILES["file"]["type"] == "text/plain") || ($_FILES["file"]["type"] == "application/pdf")
	            ) && ($_FILES["file"]["size"] < 500000)//Approx. 100kb files can be uploaded.
	            && in_array($file_extension, $validextensions)) {
	                        
	                if ($_FILES["file"]["error"] > 0) {
	                    echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
	                }else{
	                    if (file_exists($dir. $_FILES["file"]["name"])) {
	                        echo $_FILES["file"]["name"] . " <b> ya existe.</b> ";
	                    }else{
	                        move_uploaded_file($_FILES["file"]["tmp_name"], $dir . $_FILES["file"]["name"]);
	                    }
	                }
	            }else{
	            	print_r($_FILES["file"]["type"]);
	                echo "<span>***Invalid file Size or Type***<span>";
	            }
			}else{
				print_r("<span>***No disponible***<span>");
			}
		}elseif (!empty($_REQUEST['eliminar'])){
			$dir = __DIR__.'/../../../../web/upload/documentos/';
			$path = $dir.$_REQUEST['eliminar'][0];
            unlink($path);
		}else{
			print_r("<span>***Problemas de envio***<span>");
		}

		$dr = __DIR__.'/../../../../web/upload/documentos/';
		$dir = opendir($dr);
		$archivo = array();
		$cont = 0;
		while ($arch = readdir($dir)){
			if ($arch != '.' && $arch != '..'){
				$v_arc_tem = $arch; //imagen.png
				list($v_name,$v_ext) = explode(".",$v_arc_tem);
				
				$archivo[$cont]['nombre'] = $v_name;
				$archivo[$cont]['tipo'] = $v_ext;
				$archivo[$cont]['tamano'] = round (filesize($dr.$arch)/1024,1);
				$archivo[$cont]['fecha'] = date ("m/d (H:i)", filemtime ($dr.$arch));
				$cont+=1;
			}
		}

		return $this->render('limubacadministratorBundle:administracion:descargas.html.twig',array('datos' => $archivo));
	}
	//****************************FINAL CONTROLADOR DESCARGAS****************************    
}