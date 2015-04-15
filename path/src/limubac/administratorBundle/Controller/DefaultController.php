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


	//-----------------------INICIO CONTROLADOR DE FAFI---------------------------------------------------
		public function jugadoresAdminAction(){

        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
        $queryPlayers = $repository->createQueryBuilder('p')
            ->select('p.idJugador','p.nombre','p.apPaterno','p.apMaterno','p.fNacimiento','p.correo','p.telefono','p.estatura','p.peso','tsan.tipoSangre')
            ->join('limubacadministratorBundle:TipoSanguineo', 'tsan', 'WITH' ,'tsan.idTiposanguineo = p.idTiposanguineo')
            ->setMaxResults(10)
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
        elseif (!empty($_REQUEST['edit'])) {
            $jugador = new Jugador();
            $form = $this->createForm(new JugadorAType(), $jugador);
            $ed = $_REQUEST['edit'][0];

            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idJugador','e.nombre','e.apPaterno','e.apMaterno','e.fNacimiento','e.correo','e.telefono','e.profesion','IDENTITY(e.idStatus)','IDENTITY(e.idGenero)','e.estatura','e.peso','IDENTITY(e.idTiposanguineo)','IDENTITY(e.idFoto)')
            //->join('limubacadministratorBundle:Fotos', 'fot', 'WITH' ,'fot.idFoto = e.idFoto')
            ->where('e.idJugador = :word')
            ->setParameter('word', $ed)
            ->getQuery();
            $resul = $queryEdit->getResult();
            //print_r($resul);

            //$images = array();

            //$images[0] = base64_encode(stream_get_contents($resul[0]['foto']));

            return $this->render('limubacadministratorBundle:administracion:edita.html.twig',array('form' => $form->createView(), 'edita' => $resul));
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
            //->set('z.idFoto', ':fot')
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
            //->setParameter('fot', $resul[0]['idFoto'])
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
				->join('limubacadministratorBundle:Categoria', 'e', 'WITH' ,'e.idCategoria = h.idCategoria')
				->where('h.idTorneo = :torn')
				->groupBy('h.idCategoria')
				->setParameter('torn',$idtorn[0])
				->getQuery();
			$n1 = $queryCategorias->getResult();			
			//print_r($n1);
			return $this->render('limubacadministratorBundle:administracion:roldejuego.html.twig',array('rols'=>$idtorn,'categs'=>$n1));
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
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'p.idCategoria = c.idCategoria')
            ->join('limubacadministratorBundle:RamaEquipo', 'r', 'WITH' ,'p.idRama = r.idRama')
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

        //print_r($resul[0]['idFoto']);

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
            ->select('e.idCategoria','e.nombre','e.edad','e.limiteEquipo')
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
            ->where('z.idCategoria= :idc')
            ->setParameter('idc', $upt['idCategoria'])
            ->setParameter('nom', $upt['nombre'])
            ->setParameter('edd', $upt['edad'])
            ->setParameter('lme', $upt['limiteEquipo'])
            ->getQuery();
        $resul = $q->execute();

        return $this->redirect($this->generateUrl('limubacadministrator_categorias'));
    }

    //FINAL CONTROLADOR TORNEO

}
