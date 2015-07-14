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
  use limubac\administratorBundle\Entity\DetallePartido;
  use limubac\administratorBundle\Entity\FaltasEquipo;
  use limubac\administratorBundle\Entity\Faltas;
  use limubac\administratorBundle\Entity\Asistencia;
  use limubac\administratorBundle\Entity\Estatus;

  class HojaAnotacionesController extends Controller{

    public function hojaAnotacionesAction($idpartido){

      $consultasManager = new ConsultasAnotaciones();
      $contadorA=0;
      $contadorB=0;
      $marcadorA=0;
      $marcadorB=0;
      $request = $this->getRequest();
      $doctrineManager= $this -> getDoctrine()->getManager();
      $idPartido=$idpartido;//SE SUPONE LO RECIBE DE PARAMETRO...
      $idJugador;
      $Errores = array("AsistenciaA"=>0,"AsistenciaB"=>0,"PlayeraA"=>0,"PlayeraB"=>0,"General"=>0,"AnotacionesA"=>0,"AnotacionesB"=>0,"Estatus"=>0,"Ganador"=>0,"PrimeroA"=>0,"SegundoA"=>0,"TerceroA"=>0,"CuartoA"=>0,"PrimeroB"=>0,"SegundoB"=>0,
      "TerceroB"=>0,"CuartoB"=>0);

      if(count($consultasManager->isCommitedPartido($idPartido,$doctrineManager))!=0){
        return new Response('La hoja de anotacion ya ha sido cargada por favor contacte al administrador');
      }else{
        //echo $request->getMethod() ;//Quitar o comentar

        $List_A=$consultasManager->listJugadoresEquipo('A',$idPartido,$doctrineManager);
        // print_r($List_A);
        $List_B=$consultasManager->listJugadoresEquipo('B',$idPartido,$doctrineManager);

        $datosGenerales = $consultasManager->getEquipoByPartido($idPartido,$doctrineManager);
        //print_r($datosGenerales);

        if($request->getMethod() == 'POST')//si se envia el formulario
        {
          //print_r($_POST);
          $datos = new hojaAnotacion();

          $validator = $this->get('validator');

          $datos->setJuez1($_POST["Juez1"]);
          $datos->setJuez2($_POST["Juez2"]);

          //MARCADORES POR CUARTO

          //END MARCADORES POR CUARTO

          //ESTATUS PARTIDO
          if(isset($_POST['estatus'])  && !empty($_POST['estatus']) ){
            $estatus=$_POST['estatus'];
            $consultasManager->updateEstatusPartido($idPartido,$estatus,$doctrineManager);

            if(isset($_POST['Ganador'])){
              $Ganador=$_POST['Ganador'];
            }else{
              if($estatus!=1){
                $Errores['Ganador']=1;
                $Errores['General']=1;
              }
            }

          }else{
            $Errores['Estatus']=1;
            $Errores['General']=1;
          }


          //END ESTATUS PARTIDO


          //TOMAR ASISTENCIA

          if(isset($_POST['asistA'])){
            $assistA=$_POST['asistA'];
          }

          if(isset($_POST['asistB'])){
            $assistB=$_POST['asistB'];
          }
          if($Errores['General']!=1){
          switch($estatus){
            case 2:
            switch($Ganador){
              case 'A':
              if(!empty($assistA)&&(!(count($assistA)<4))){
                tomarAsistencia($assistA);
                tomarAsistencia($assistB);
              }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaA en el arreglo de errores
                $Errores['AsistenciaA']=1;
                $Errores['General']=1;
              }
              break;

              case 'B':
              if(!empty($assistB)&&(!(count($assistB)<4))){
                tomarAsistencia($assistB);
                tomarAsistencia($assistA);
              }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaB en el arreglo de errores
                $Errores['AsistenciaB']=1;
                $Errores['General']=1;
              }
              break;
            }
            break;

            default:
            if(!empty($assistA)&&(!(count($assistA)<4))){
              $this->tomarAsistencia($assistA,$idPartido,$doctrineManager);
            }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaA en el arreglo de errores
              $Errores['AsistenciaA']=1;
              $Errores['General']=1;
            }

            if(!empty($assistB)&&(!(count($assistB)<4))){
              $this->tomarAsistencia($assistB,$idPartido,$doctrineManager);
            }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaB en el arreglo de errores
              $Errores['AsistenciaB']=1;
              $Errores['General']=1;
            }

          }
        }else{
            print_r($Errores);
            return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales,'errores'=>$Errores,'post'=>$_POST));

        }
          //END TOMAR ASISTENCIA

          //FALTAS
          if($Errores['General']!=1){

            $idsJugadores=array_keys($_POST['faltasA']);

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
                    //print_r($auxConsulta);
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
          }
          //END FALTAS


          if($Errores['General']!=1){
            switch ($estatus) {
              case 1:
              $datos->setPuntos(array_values((array_slice($_POST, 7,119))));

              $puntos = $datos->getPuntos();
              $puntosA=array();
              $puntosB=array();

              if(!empty($puntos)){
                // print_r($puntos);
                for ($j=0; $j < count($puntos); $j++) { //VALIDAR NUMEROS DE

                  if(preg_match("/^([0-9])*([0-9])*$/",$puntos[$j])) {

                    if(($j%2)==0){
                      $puntosA[]=$puntos[$j];
                    }else{
                      $puntosB[]=$puntos[$j];
                    }
                  }else{
                    if(($j%2)==0){
                      $Errores['PlayeraA']=$j+1;
                    }else{
                      $Errores['PlayeraB']=$j+1;
                    }
                    $Errores['General']=1;
                    break;
                  }

                }

                for ($i=0; $i < count($puntosA); $i++) {
                  if($i==0 && $puntosA[$i]!=''){//ANOTACION DE UN PUNTO
                    $marcadorA++;
                    $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosA[$i],'A',$doctrineManager);

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
                        $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosA[$i],'A',$doctrineManager);
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
                          $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosA[$i],'A',$doctrineManager);
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
                            $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosA[$i],'A',$doctrineManager);
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

                          }else{
                            if($contadorA>2){
                              $Errores['AnotacionesA']=1;
                              $Errores['General']=1;
                            }
                          }
                        }
                      }
                    }
                  }
                }
                echo ("marcadorA= {$marcadorA}");
                $consultasManager->updateResultadoByPartido($idPartido,"A",$marcadorA,$doctrineManager);


                for ($i=0; $i < count($puntosB); $i++) {
                  if($i==0 && $puntosB[$i]!=''){
                    $marcadorB++;
                    $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosB[$i],'B',$doctrineManager);

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
                        $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosB[$i],'B',$doctrineManager);

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
                          $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosB[$i],'B',$doctrineManager);
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

                            $x=$consultasManager->getIdJugadorByPlayera($idPartido,$puntosB[$i],'B',$doctrineManager);
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
                          }else{
                            if($contadorB>2){
                              $Errores['AnotacionesB']=1;
                              $Errores['General']=1;
                              break;
                            }
                          }
                        }
                      }
                    }
                  }
                }
                if(isset($_POST['primeroA']) && !empty($_POST['primeroA'])){

                  $primero=$_POST['primeroA'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"primero",$primero, "A",$doctrineManager);
                }else{
                  $Errores['PrimeroA']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['segundoA']) && !empty($_POST['segundoA']) ){
                  $segundo=$_POST['segundoA'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"segundo",$segundo, "A",$doctrineManager);
                }else{
                  $Errores['SegundoA']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['terceroA']) && !empty($_POST['terceroA']) ){
                  $tercero=$_POST['terceroA'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"tercero",$tercero, "A",$doctrineManager);
                }else{
                  $Errores['TerceroA']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['cuartoA']) && !empty($_POST['cuartoA']) ){
                  $cuarto=$_POST['cuartoA'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"cuarto",$cuarto, "A",$doctrineManager);
                }else{
                  $Errores['CuartoA']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['complementarioA'])){
                  $complementario=$_POST['complementarioA'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"complementario",$complementario, "A",$doctrineManager);
                }

                if(isset($_POST['primeroB']) && !empty($_POST['primeroB']) ){
                  $primero=$_POST['primeroB'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"primero",$primero, "B",$doctrineManager);
                }else{
                  $Errores['PrimeroB']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['segundoB'])  && !empty($_POST['segundoB']) ){
                  $segundo=$_POST['segundoB'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"segundo",$segundo, "B",$doctrineManager);
                }else{
                  $Errores['SegundoB']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['terceroB']) && !empty($_POST['terceroB']) ){
                  $tercero=$_POST['terceroB'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"tercero",$tercero, "B",$doctrineManager);
                }else{
                  $Errores['TerceroB']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['cuartoB']) && !empty($_POST['cuartoB']) ){
                  $cuarto=$_POST['cuartoB'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"cuarto",$cuarto, "B",$doctrineManager);
                }else{
                  $Errores['CuartoB']=1;
                  $Errores['General']=1;
                }
                if(isset($_POST['complementarioB'])){
                  $complementario=$_POST['complementarioB'];
                  $consultasManager->MarcadoresCuartosPartidoById($idPartido,"complementario",$complementario, "B",$doctrineManager);
                }
                if($Errores['General']!=1){
                  $consultasManager->updateResultadoByPartido($idPartido,"B",$marcadorB,$doctrineManager);
                  $consultasManager->commitPartidoById($idPartido,$doctrineManager); //todo salio bien aqui debes redireccionar
                  return new Response('HOJA DE ANOTACION CARGADA EXITOSAMENTE!');
                }else{//errores con las anotaciones
                  print_r($Errores);
                  return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales,'errores'=>$Errores,'post'=>$_POST));
                }


              }else{
                //No Hubo anotaciones
              }
              break;

              case 2:
              if($Ganador=="A"){
                $consultasManager->updateResultadoByPartido($idPartido,"A",20,$doctrineManager);
                $consultasManager->updateResultadoByPartido($idPartido,"B",0,$doctrineManager);
              }else{
                $consultasManager->updateResultadoByPartido($idPartido,"B",20,$doctrineManager);
                $consultasManager->updateResultadoByPartido($idPartido,"A",0,$doctrineManager);
              }
              return new Response('HOJA DE ANOTACION CARGADA EXITOSAMENTE!');
              break;
              case 3:
              if($Ganador=="A"){
                $consultasManager->updateResultadoByPartido($idPartido,"A",20,$doctrineManager);
                $consultasManager->updateResultadoByPartido($idPartido,"B",1,$doctrineManager);
              }else{
                $consultasManager->updateResultadoByPartido($idPartido,"B",20,$doctrineManager);
                $consultasManager->updateResultadoByPartido($idPartido,"A",1,$doctrineManager);
              }
              return new Response('HOJA DE ANOTACION CARGADA EXITOSAMENTE!');
              break;
            }

          }else{
            //Algo salio mal, aqui debes recargar la pagina mostrando al usuario los errores
            print_r($Errores);
            return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales,'errores'=>$Errores,'post'=>$_POST));
          }

        }//Renderiza la pagina por primera vez
        return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales));
      }
    }

    public function nombresEquiposAction(){
      $request = $this->container->get('request');
      $idPartido=0;
      $consultasManager = new ConsultasAnotaciones();
      $doctrineManager= $this -> getDoctrine()->getManager();
      $Equipos=json_encode($consultasManager->getEquipoByPartido($idPartido,$doctrineManager));
      return new Response($Equipos);
    }

    public function tomarAsistencia($assistArray,$idPartido,$doctrineManager){
      foreach ($assistArray as $a) {
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

    public function hojapdfAction(){
      $pdf = $this->get("white_october.tcpdf")->create();

      // set names parameters
      $Author="Farid Carreon";
      $Title= "LIGA MUNICIPAL DE BASQUETBOL DE CELAYA A.C.";
      $Subtitle="Calle Insurgentes 410-B, Primer Piso,Zona Centro. Celaya, Gto. Horario: Lunes a Jueves 17:30 a 20:30 hrs. Domingos de 08:00 a 13:00 Tel. (461) 613-5585 ~ www.limubac.org ~ limubac@gmail.com www.facebook.com/limubac ~ www.twitter.com/limubac";
      $Subject="TCPDF Tutorial";
      $Torneo=intval($_REQUEST['pdf']);

      // connect to the db 
      $link = mysql_connect('www.mcflylabs.com','mcfly_nelther','Limubac_bd') or die('Cannot connect to the DB');
      mysql_select_db('mcflylab_limubactest1',$link) or die('Cannot select the DB');

      // set document information
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor($Author);
      $pdf->SetTitle($Title);
      $pdf->SetSubject($Subject);
      $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

      // set default header data
      $pdf->SetHeaderData('logoLIMUBAC.png', 22,$Title, $Subtitle);

      // set header and footer fonts
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      //$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      // set some language-dependent strings (optional)
      if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
      }

      // ---------------------------------------------------------

      // set font
      $pdf->SetFont('helvetica', '', 8);

      // add a page
      $pdf->AddPage();

      // column titles
      $header = array('Nombre', 'Categoria', 'Inscripcion','Fianza','Mes 1','Mes 2','Mes 3','Mes 4','Mes 5','Mes 6', 'Mes 7');

      // grab the posts from the db
      $query = "SELECT j.side, e.nombre, c.nombre as categoria, r.nombre as rama
        FROM `equipo` AS e
        INNER JOIN `juegan` AS j USING (id_equipo)
        INNER JOIN `categoria` AS c USING (id_categoria)
        INNER JOIN `rama_equipo` AS r USING (id_rama)
        WHERE j.id_partido='".$Torneo."';";
      $result1 = mysql_query($query) or die('Errant query:  '.$query);

      $query="SELECT t.nombre AS torneo, s.nombre AS sede, b1.nombre AS juezuno, b2.nombre AS juezdos, p.h_inicio AS horain, p.h_termino AS horafn, p.jornada
        FROM `partido` AS p
        INNER JOIN `sede` AS s USING (id_sede)
        INNER JOIN `torneo` AS t USING (id_torneo)
        INNER JOIN `arbitran` AS a USING (id_arbitran)
        INNER JOIN `arbitro` as b1 ON a.id_arbitro1 = b1.id_arbitro
        INNER JOIN `arbitro` as b2 ON a.id_arbitro2 = b2.id_arbitro
        WHERE p.id_partido='".$Torneo."';";
      $result2 = mysql_query($query) or die('Errant query:  '.$query);

      $query="SELECT e.nombre, a.id_jugador, a.nombre as nam, a.ap_paterno, a.ap_materno, i.no_playera, j.side, i.id_equipo
        FROM `juegan` AS j
        INNER JOIN `equipo` AS e USING (id_equipo)
        INNER JOIN `integra` AS i USING (id_equipo)
        INNER JOIN `jugador` AS a ON i.id_jugador = a.id_jugador
        WHERE j.id_partido='".$Torneo."' AND j.side='A';";
      $result3 = mysql_query($query) or die('Errant query:  '.$query);

      $query="SELECT e.nombre, a.id_jugador, a.nombre as nam, a.ap_paterno, a.ap_materno, i.no_playera, j.side, i.id_equipo
        FROM `juegan` AS j
        INNER JOIN `equipo` AS e USING (id_equipo)
        INNER JOIN `integra` AS i USING (id_equipo)
        INNER JOIN `jugador` AS a ON i.id_jugador = a.id_jugador
        WHERE j.id_partido='".$Torneo."' AND j.side='B';";
      $result4 = mysql_query($query) or die('Errant query:  '.$query);

      // fill data array
      $num_fields = mysql_num_fields($result1); 
      $j=0;
      $x=1;
      $object1 = array();
      while($row=mysql_fetch_array($result1)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result1, $j);
         $object1[$x][$name]=$row[$name];
        }$x++;
      }

      $num_fields = mysql_num_fields($result2); 
      $j=0;
      $x=1;
      $object2 = array();
      while($row=mysql_fetch_array($result2)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result2, $j);
         $object2[$x][$name]=$row[$name];
        }$x++;
      }

      $num_fields = mysql_num_fields($result3); 
      $j=0;
      $x=1;
      $object3 = array();
      while($row=mysql_fetch_array($result3)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result3, $j);
         $object3[$x][$name]=$row[$name];
        }$x++;
      }

      $num_fields = mysql_num_fields($result4); 
      $j=0;
      $x=1;
      $object4 = array();
      while($row=mysql_fetch_array($result4)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result4, $j);
         $object4[$x][$name]=$row[$name];
        }$x++;
      }

      // data loading
      $data1 = array();
      for ($i=1; $i <=count($object1) ; $i++) { 
        $data1[$i]['side']=$object1[$i]['side'];
        $data1[$i]['nombre']=$object1[$i]['nombre'];
        $data1[$i]['categoria']=$object1[$i]['categoria'];
        $data1[$i]['rama']=$object1[$i]['rama'];
      }

      $data2 = array();
      for ($i=1; $i <=count($object2) ; $i++) { 
        $data2[$i]['torneo']=$object2[$i]['torneo'];
        $data2[$i]['sede']=$object2[$i]['sede'];
        $data2[$i]['juezuno']=$object2[$i]['juezuno'];
        $data2[$i]['juezdos']=$object2[$i]['juezdos'];
        $data2[$i]['horain']=$object2[$i]['horain'];
        $data2[$i]['horafn']=$object2[$i]['horafn'];
        $data2[$i]['jornada']=$object2[$i]['jornada'];
      }

      $jugadoresA = array();
      $idA="";
      for ($i=1; $i <=12 ; $i++) { 
        $jugadoresA[$i]['nombre']="";
        $jugadoresA[$i]['id_jugador']="";
        $jugadoresA[$i]['nam']="";
        $jugadoresA[$i]['ap_paterno']="";
        $jugadoresA[$i]['ap_materno']="";
        $jugadoresA[$i]['no_playera']="";
        $jugadoresA[$i]['side']="";
      }
      for ($i=1; $i <=count($object3) ; $i++) { 
        $jugadoresA[$i]['nombre']=$object3[$i]['nombre'];
        $jugadoresA[$i]['id_jugador']=$object3[$i]['id_jugador'];
        $jugadoresA[$i]['nam']=$object3[$i]['nam'];
        $jugadoresA[$i]['ap_paterno']=$object3[$i]['ap_paterno'];
        $jugadoresA[$i]['ap_materno']=$object3[$i]['ap_materno'];
        $jugadoresA[$i]['no_playera']=$object3[$i]['no_playera'];
        $jugadoresA[$i]['side']=$object3[$i]['side'];
        $idA=$object3[$i]['id_equipo'];
      }

      $jugadoresB = array();
      $idB="";
      for ($i=1; $i <=12 ; $i++) { 
        $jugadoresB[$i]['nombre']="";
        $jugadoresB[$i]['id_jugador']="";
        $jugadoresB[$i]['nam']="";
        $jugadoresB[$i]['ap_paterno']="";
        $jugadoresB[$i]['ap_materno']="";
        $jugadoresB[$i]['no_playera']="";
        $jugadoresB[$i]['side']="";
      }
      for ($i=1; $i <=count($object4) ; $i++) { 
        $jugadoresB[$i]['nombre']=$object4[$i]['nombre'];
        $jugadoresB[$i]['id_jugador']=$object4[$i]['id_jugador'];
        $jugadoresB[$i]['nam']=$object4[$i]['nam'];
        $jugadoresB[$i]['ap_paterno']=$object4[$i]['ap_paterno'];
        $jugadoresB[$i]['ap_materno']=$object4[$i]['ap_materno'];
        $jugadoresB[$i]['no_playera']=$object4[$i]['no_playera'];
        $jugadoresB[$i]['side']=$object4[$i]['side'];
        $idB=$object4[$i]['id_equipo'];
      }

      $query="SELECT i.no_playera, d.anotaciones, d.cantidad
        FROM `detalle_partido` AS d
        LEFT JOIN `integra` AS i USING (id_jugador)
        LEFT JOIN `juegan` AS j USING (id_partido)
        WHERE d.id_partido='".$Torneo."' AND i.id_equipo='".$idA."'
        GROUP BY d.id_detalle;";
      $result5 = mysql_query($query) or die('Errant query:  '.$query);
      $query="SELECT i.no_playera, d.anotaciones, d.cantidad
        FROM `detalle_partido` AS d
        LEFT JOIN `integra` AS i USING (id_jugador)
        LEFT JOIN `juegan` AS j USING (id_partido)
        WHERE d.id_partido='".$Torneo."' AND i.id_equipo='".$idB."'
        GROUP BY d.id_detalle;";
      $result6 = mysql_query($query) or die('Errant query:  '.$query);

      $num_fields = mysql_num_fields($result5); 
      $j=0;
      $x=1;
      $object5 = array();
      while($row=mysql_fetch_array($result5)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result5, $j);
         $object5[$x][$name]=$row[$name];
        }$x++;
      }
      $num_fields = mysql_num_fields($result6); 
      $j=0;
      $x=1;
      $object6 = array();
      while($row=mysql_fetch_array($result6)){  
        for($j=0;$j<$num_fields;$j++){
         $name = mysql_field_name($result6, $j);
         $object6[$x][$name]=$row[$name];
        }$x++;
      }

      $puntosA = array();
      $puntosB = array();
      for ($i=1; $i <=120; $i++) { 
        $puntosA[$i]="";
        $puntosB[$i]="";
      }
      $posA=0;
      for ($i=1; $i <=count($object5) ; $i++) { 
        for($j=1; $j<=$object5[$i]['cantidad']; $j++){
          $posA+= $object5[$i]['anotaciones'];
          $puntosA[$posA]=$object5[$i]['no_playera'];
        }
      }
      $posB=0;
      for ($i=1; $i <=count($object6) ; $i++) { 
        for($j=1; $j<=$object6[$i]['cantidad']; $j++){
          $posB+= $object6[$i]['anotaciones'];
          $puntosB[$posB]=$object6[$i]['no_playera'];
        }
      }
      $ganador="";
      if ($posA==$posB) {
        $ganador="EMPATE";
      }elseif ($posA > $posB) {
        $ganador=$data1[1]['nombre'];
      }else{
        $ganador=$data1[2]['nombre'];
      }

      // print html content
      $eA=$data1[1]['nombre'];
      $eB=$data1[2]['nombre'];
      $rA=$data1[1]['categoria'];
      $cA=$data1[1]['rama'];
      $lug=$data2[1]['sede'];
      $tor=$data2[1]['torneo'];
      $pjz=$data2[1]['juezuno'];
      $fec="null";
      $hor=$data2[1]['horain'];
      $sjz=$data2[1]['juezdos'];
      $jor=$data2[1]['jornada'];
      $par="null";

      $html='<br><br><table cellpadding="2" cellspacing="2" border="0">
            <tr>
              <td align="center">
                <b>'.$eA.'</b>
              </td>
              <td align="center">
                 VS
              </td>
              <td align="center">
                <b>'.$eB.'</b>
              </td>
              <td align="center">
                RAMA:
              </td>
              <td align="center">
                <b>'.$rA.'</b>
              </td>
              <td align="center">
                CATEGORIA:
              </td>
              <td align="center">
                <b>'.$cA.'</b>
              </td>
            </tr>
           </table>';
      $pdf->writeHTML($html,true, false,true, false,'');

      $html='<br><table cellpadding="2" cellspacing="2" border="1">
            <tr>
              <td>
                LUGAR: <b>'.$lug.'</b>
              </td>
              <td>
                TORNEO: <b>'.$tor.'</b>
              </td>
              <td width="175px">
                1er JUEZ: <b>'.$pjz.'</b>
              </td>
              <td align="center" colspan="2" width="200px">
                No. JUEGO
              </td>
            </tr>
            <tr>
              <td>
                FECHA: <b>'.$fec.'</b>
              </td>
              <td>
                HORA: <b>'.$hor.'</b>
              </td>
              <td>
                2do JUEZ: <b>'.$sjz.'</b>
              </td>
              <td>
                J: <b>'.$jor.'</b>
              </td>
              <td>
                P: <b>'.$par.'</b>
              </td>
            </tr>
           </table>';
      $pdf->writeHTML($html,true, false,true, false,'');
      
      $html='<table border="1">
            <tr>
              <td width="325px">
                <b>EQUIPO "A" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$jugadoresA[1]["nombre"].'</u></b>
                <br>
                <table border="0">
                  <tr>
                    <td colspan="3" align="center">
                      <font size="7px">Detenciones</font>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="4" align="center"><font size="7px">Faltas de Equipo</font></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="center">Primer Tiempo</td>
                    <td colspan="2">1
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="2">2
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="4" align="center">Segundo Tiempo</td>
                    <td colspan="2">3
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="2">4
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="9" align="center">&nbsp;</td>
                  </tr>
                </table>
                <table border="1">
                  <tr>
                    <td rowspan="2" width="30px" align="center">
                      No. Lic.
                    </td>
                    <td rowspan="2" width="180px" align="center">
                      NOMBRE DE JUGADORES
                    </td>
                    <td rowspan="2" width="20px" align="center">
                      No.
                    </td>
                    <td rowspan="2" width="20px" align="center">
                      E
                    </td>
                    <td  width="65px" align="center">FALTAS
                    </td>
                  </tr>
                  <tr>
                    <td width="13px" align="center">1</td>
                    <td width="13px" align="center">2</td>
                    <td width="13px" align="center">3</td>
                    <td width="13px" align="center">4</td>
                    <td width="13px" align="center">5</td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[1]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[1]["nam"].' '.$jugadoresA[1]["ap_paterno"].' '.$jugadoresA[1]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[1]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[2]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[2]["nam"].' '.$jugadoresA[2]["ap_paterno"].' '.$jugadoresA[2]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[2]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[3]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[3]["nam"].' '.$jugadoresA[3]["ap_paterno"].' '.$jugadoresA[3]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[3]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[4]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[4]["nam"].' '.$jugadoresA[4]["ap_paterno"].' '.$jugadoresA[4]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[4]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[5]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[5]["nam"].' '.$jugadoresA[5]["ap_paterno"].' '.$jugadoresA[5]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[5]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[6]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[6]["nam"].' '.$jugadoresA[6]["ap_paterno"].' '.$jugadoresA[6]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[6]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[7]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[7]["nam"].' '.$jugadoresA[7]["ap_paterno"].' '.$jugadoresA[7]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[7]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[8]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[8]["nam"].' '.$jugadoresA[8]["ap_paterno"].' '.$jugadoresA[8]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[8]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[9]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[9]["nam"].' '.$jugadoresA[9]["ap_paterno"].' '.$jugadoresA[9]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[9]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[10]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[10]["nam"].' '.$jugadoresA[10]["ap_paterno"].' '.$jugadoresA[10]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[10]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[11]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[11]["nam"].' '.$jugadoresA[11]["ap_paterno"].' '.$jugadoresA[11]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[11]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresA[12]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresA[12]["nam"].' '.$jugadoresA[12]["ap_paterno"].' '.$jugadoresA[12]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresA[12]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td width="276px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ENTRENADOR</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="276px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ASISTENTE</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                  </tr>
                </table>
              </td>
              <td rowspan="2" width="312px" align="center">
                <table border="1">
                  <tr bgcolor="black">
                    <td colspan="3" align="center" height="18px">
                      <font color="white" size="12px"><b>CLASIFICACION DEL JUEGO</b></font>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" height="30px">&nbsp;<br>NORMAL&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|</td>
                    <td align="center">&nbsp;<br>DEFAULT&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|</td>
                    <td align="center">&nbsp;<br>EN MESA&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|</td>
                  </tr>
                </table>
                <table cellspacing="2">
                  <tr>
                    <td>
                      <table border="1">
                        <tr>
                          <td colspan="2" align="center" height="17px">A</td>
                          <td colspan="2" align="center">B</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[1].'</td>
                          <td align="center">1</td>
                          <td align="center">1</td>
                          <td align="center">'.$puntosB[1].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[2].'</td>
                          <td align="center">2</td>
                          <td align="center">2</td>
                          <td align="center">'.$puntosB[2].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[3].'</td>
                          <td align="center">3</td>
                          <td align="center">3</td>
                          <td align="center">'.$puntosB[3].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[4].'</td>
                          <td align="center">4</td>
                          <td align="center">4</td>
                          <td align="center">'.$puntosB[4].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[5].'</td>
                          <td align="center">5</td>
                          <td align="center">5</td>
                          <td align="center">'.$puntosB[5].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[6].'</td>
                          <td align="center">6</td>
                          <td align="center">6</td>
                          <td align="center">'.$puntosB[6].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[7].'</td>
                          <td align="center">7</td>
                          <td align="center">7</td>
                          <td align="center">'.$puntosB[7].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[8].'</td>
                          <td align="center">8</td>
                          <td align="center">8</td>
                          <td align="center">'.$puntosB[8].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[9].'</td>
                          <td align="center">9</td>
                          <td align="center">9</td>
                          <td align="center">'.$puntosB[9].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[10].'</td>
                          <td align="center">10</td>
                          <td align="center">10</td>
                          <td align="center">'.$puntosB[10].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[11].'</td>
                          <td align="center">11</td>
                          <td align="center">11</td>
                          <td align="center">'.$puntosB[11].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[12].'</td>
                          <td align="center">12</td>
                          <td align="center">12</td>
                          <td align="center">'.$puntosB[12].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[13].'</td>
                          <td align="center">13</td>
                          <td align="center">13</td>
                          <td align="center">'.$puntosB[13].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[14].'</td>
                          <td align="center">14</td>
                          <td align="center">14</td>
                          <td align="center">'.$puntosB[14].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[15].'</td>
                          <td align="center">15</td>
                          <td align="center">15</td>
                          <td align="center">'.$puntosB[15].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[16].'</td>
                          <td align="center">16</td>
                          <td align="center">16</td>
                          <td align="center">'.$puntosB[16].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[17].'</td>
                          <td align="center">17</td>
                          <td align="center">17</td>
                          <td align="center">'.$puntosB[17].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[18].'</td>
                          <td align="center">18</td>
                          <td align="center">18</td>
                          <td align="center">'.$puntosB[18].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[19].'</td>
                          <td align="center">19</td>
                          <td align="center">19</td>
                          <td align="center">'.$puntosB[19].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[20].'</td>
                          <td align="center">20</td>
                          <td align="center">20</td>
                          <td align="center">'.$puntosB[20].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[21].'</td>
                          <td align="center">21</td>
                          <td align="center">21</td>
                          <td align="center">'.$puntosB[21].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[22].'</td>
                          <td align="center">22</td>
                          <td align="center">22</td>
                          <td align="center">'.$puntosB[22].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[23].'</td>
                          <td align="center">23</td>
                          <td align="center">23</td>
                          <td align="center">'.$puntosB[23].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[24].'</td>
                          <td align="center">24</td>
                          <td align="center">24</td>
                          <td align="center">'.$puntosB[24].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[25].'</td>
                          <td align="center">25</td>
                          <td align="center">25</td>
                          <td align="center">'.$puntosB[25].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[26].'</td>
                          <td align="center">26</td>
                          <td align="center">26</td>
                          <td align="center">'.$puntosB[26].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[27].'</td>
                          <td align="center">27</td>
                          <td align="center">27</td>
                          <td align="center">'.$puntosB[27].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[28].'</td>
                          <td align="center">28</td>
                          <td align="center">28</td>
                          <td align="center">'.$puntosB[28].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[29].'</td>
                          <td align="center">29</td>
                          <td align="center">29</td>
                          <td align="center">'.$puntosB[29].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[30].'</td>
                          <td align="center">30</td>
                          <td align="center">30</td>
                          <td align="center">'.$puntosB[30].'</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td colspan="2" align="center" height="17px">A</td>
                          <td colspan="2" align="center">B</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[31].'</td>
                          <td align="center">31</td>
                          <td align="center">31</td>
                          <td align="center">'.$puntosB[31].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[32].'</td>
                          <td align="center">32</td>
                          <td align="center">32</td>
                          <td align="center">'.$puntosB[32].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[33].'</td>
                          <td align="center">33</td>
                          <td align="center">33</td>
                          <td align="center">'.$puntosB[33].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[34].'</td>
                          <td align="center">34</td>
                          <td align="center">34</td>
                          <td align="center">'.$puntosB[34].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[35].'</td>
                          <td align="center">35</td>
                          <td align="center">35</td>
                          <td align="center">'.$puntosB[35].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[36].'</td>
                          <td align="center">36</td>
                          <td align="center">36</td>
                          <td align="center">'.$puntosB[36].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[37].'</td>
                          <td align="center">37</td>
                          <td align="center">37</td>
                          <td align="center">'.$puntosB[37].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[38].'</td>
                          <td align="center">38</td>
                          <td align="center">38</td>
                          <td align="center">'.$puntosB[38].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[39].'</td>
                          <td align="center">39</td>
                          <td align="center">39</td>
                          <td align="center">'.$puntosB[39].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[40].'</td>
                          <td align="center">40</td>
                          <td align="center">40</td>
                          <td align="center">'.$puntosB[40].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[41].'</td>
                          <td align="center">41</td>
                          <td align="center">41</td>
                          <td align="center">'.$puntosB[41].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[42].'</td>
                          <td align="center">42</td>
                          <td align="center">42</td>
                          <td align="center">'.$puntosB[42].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[43].'</td>
                          <td align="center">43</td>
                          <td align="center">43</td>
                          <td align="center">'.$puntosB[43].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[44].'</td>
                          <td align="center">44</td>
                          <td align="center">44</td>
                          <td align="center">'.$puntosB[44].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[45].'</td>
                          <td align="center">45</td>
                          <td align="center">45</td>
                          <td align="center">'.$puntosB[45].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[46].'</td>
                          <td align="center">46</td>
                          <td align="center">46</td>
                          <td align="center">'.$puntosB[46].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[47].'</td>
                          <td align="center">47</td>
                          <td align="center">47</td>
                          <td align="center">'.$puntosB[47].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[48].'</td>
                          <td align="center">48</td>
                          <td align="center">48</td>
                          <td align="center">'.$puntosB[48].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[49].'</td>
                          <td align="center">49</td>
                          <td align="center">49</td>
                          <td align="center">'.$puntosB[49].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[50].'</td>
                          <td align="center">50</td>
                          <td align="center">50</td>
                          <td align="center">'.$puntosB[50].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[51].'</td>
                          <td align="center">51</td>
                          <td align="center">51</td>
                          <td align="center">'.$puntosB[51].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[52].'</td>
                          <td align="center">52</td>
                          <td align="center">52</td>
                          <td align="center">'.$puntosB[52].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[53].'</td>
                          <td align="center">53</td>
                          <td align="center">53</td>
                          <td align="center">'.$puntosB[53].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[54].'</td>
                          <td align="center">54</td>
                          <td align="center">54</td>
                          <td align="center">'.$puntosB[54].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[55].'</td>
                          <td align="center">55</td>
                          <td align="center">55</td>
                          <td align="center">'.$puntosB[55].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[56].'</td>
                          <td align="center">56</td>
                          <td align="center">56</td>
                          <td align="center">'.$puntosB[56].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[57].'</td>
                          <td align="center">57</td>
                          <td align="center">57</td>
                          <td align="center">'.$puntosB[57].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[58].'</td>
                          <td align="center">58</td>
                          <td align="center">58</td>
                          <td align="center">'.$puntosB[58].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[59].'</td>
                          <td align="center">59</td>
                          <td align="center">59</td>
                          <td align="center">'.$puntosB[59].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[60].'</td>
                          <td align="center">60</td>
                          <td align="center">60</td>
                          <td align="center">'.$puntosB[60].'</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td colspan="2" align="center" height="17px">A</td>
                          <td colspan="2" align="center">B</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[61].'</td>
                          <td align="center">61</td>
                          <td align="center">61</td>
                          <td align="center">'.$puntosB[61].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[62].'</td>
                          <td align="center">62</td>
                          <td align="center">62</td>
                          <td align="center">'.$puntosB[62].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[63].'</td>
                          <td align="center">63</td>
                          <td align="center">63</td>
                          <td align="center">'.$puntosB[63].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[64].'</td>
                          <td align="center">64</td>
                          <td align="center">64</td>
                          <td align="center">'.$puntosB[64].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[65].'</td>
                          <td align="center">65</td>
                          <td align="center">65</td>
                          <td align="center">'.$puntosB[65].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[66].'</td>
                          <td align="center">66</td>
                          <td align="center">66</td>
                          <td align="center">'.$puntosB[66].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[67].'</td>
                          <td align="center">67</td>
                          <td align="center">67</td>
                          <td align="center">'.$puntosB[67].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[68].'</td>
                          <td align="center">68</td>
                          <td align="center">68</td>
                          <td align="center">'.$puntosB[68].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[69].'</td>
                          <td align="center">69</td>
                          <td align="center">69</td>
                          <td align="center">'.$puntosB[69].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[70].'</td>
                          <td align="center">70</td>
                          <td align="center">70</td>
                          <td align="center">'.$puntosB[70].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[71].'</td>
                          <td align="center">71</td>
                          <td align="center">71</td>
                          <td align="center">'.$puntosB[71].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[72].'</td>
                          <td align="center">72</td>
                          <td align="center">72</td>
                          <td align="center">'.$puntosB[72].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[73].'</td>
                          <td align="center">73</td>
                          <td align="center">73</td>
                          <td align="center">'.$puntosB[73].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[74].'</td>
                          <td align="center">74</td>
                          <td align="center">74</td>
                          <td align="center">'.$puntosB[74].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[75].'</td>
                          <td align="center">75</td>
                          <td align="center">75</td>
                          <td align="center">'.$puntosB[75].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[76].'</td>
                          <td align="center">76</td>
                          <td align="center">76</td>
                          <td align="center">'.$puntosB[76].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[77].'</td>
                          <td align="center">77</td>
                          <td align="center">77</td>
                          <td align="center">'.$puntosB[77].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[78].'</td>
                          <td align="center">78</td>
                          <td align="center">78</td>
                          <td align="center">'.$puntosB[78].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[79].'</td>
                          <td align="center">79</td>
                          <td align="center">79</td>
                          <td align="center">'.$puntosB[79].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[80].'</td>
                          <td align="center">80</td>
                          <td align="center">80</td>
                          <td align="center">'.$puntosB[80].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[81].'</td>
                          <td align="center">81</td>
                          <td align="center">81</td>
                          <td align="center">'.$puntosB[81].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[82].'</td>
                          <td align="center">82</td>
                          <td align="center">82</td>
                          <td align="center">'.$puntosB[82].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[83].'</td>
                          <td align="center">83</td>
                          <td align="center">83</td>
                          <td align="center">'.$puntosB[83].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[84].'</td>
                          <td align="center">84</td>
                          <td align="center">84</td>
                          <td align="center">'.$puntosB[84].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[85].'</td>
                          <td align="center">85</td>
                          <td align="center">85</td>
                          <td align="center">'.$puntosB[85].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[86].'</td>
                          <td align="center">86</td>
                          <td align="center">86</td>
                          <td align="center">'.$puntosB[86].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[87].'</td>
                          <td align="center">87</td>
                          <td align="center">87</td>
                          <td align="center">'.$puntosB[87].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[88].'</td>
                          <td align="center">88</td>
                          <td align="center">88</td>
                          <td align="center">'.$puntosB[88].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[89].'</td>
                          <td align="center">89</td>
                          <td align="center">89</td>
                          <td align="center">'.$puntosB[89].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[90].'</td>
                          <td align="center">90</td>
                          <td align="center">90</td>
                          <td align="center">'.$puntosB[90].'</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td colspan="2" align="center" height="17px">A</td>
                          <td colspan="2" align="center">B</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[91].'</td>
                          <td align="center">91</td>
                          <td align="center">91</td>
                          <td align="center">'.$puntosB[91].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[92].'</td>
                          <td align="center">92</td>
                          <td align="center">92</td>
                          <td align="center">'.$puntosB[92].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[93].'</td>
                          <td align="center">93</td>
                          <td align="center">93</td>
                          <td align="center">'.$puntosB[93].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[94].'</td>
                          <td align="center">94</td>
                          <td align="center">94</td>
                          <td align="center">'.$puntosB[94].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[95].'</td>
                          <td align="center">95</td>
                          <td align="center">95</td>
                          <td align="center">'.$puntosB[95].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[96].'</td>
                          <td align="center">96</td>
                          <td align="center">96</td>
                          <td align="center">'.$puntosB[96].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[97].'</td>
                          <td align="center">97</td>
                          <td align="center">97</td>
                          <td align="center">'.$puntosB[97].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[98].'</td>
                          <td align="center">98</td>
                          <td align="center">98</td>
                          <td align="center">'.$puntosB[98].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[99].'</td>
                          <td align="center">99</td>
                          <td align="center">99</td>
                          <td align="center">'.$puntosB[99].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[100].'</td>
                          <td align="center">100</td>
                          <td align="center">100</td>
                          <td align="center">'.$puntosB[100].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[101].'</td>
                          <td align="center">101</td>
                          <td align="center">101</td>
                          <td align="center">'.$puntosB[101].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[102].'</td>
                          <td align="center">102</td>
                          <td align="center">102</td>
                          <td align="center">'.$puntosB[102].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[103].'</td>
                          <td align="center">103</td>
                          <td align="center">103</td>
                          <td align="center">'.$puntosB[103].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[104].'</td>
                          <td align="center">104</td>
                          <td align="center">104</td>
                          <td align="center">'.$puntosB[104].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[105].'</td>
                          <td align="center">105</td>
                          <td align="center">105</td>
                          <td align="center">'.$puntosB[105].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[106].'</td>
                          <td align="center">106</td>
                          <td align="center">106</td>
                          <td align="center">'.$puntosB[106].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[107].'</td>
                          <td align="center">107</td>
                          <td align="center">107</td>
                          <td align="center">'.$puntosB[107].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[108].'</td>
                          <td align="center">108</td>
                          <td align="center">108</td>
                          <td align="center">'.$puntosB[108].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[109].'</td>
                          <td align="center">109</td>
                          <td align="center">109</td>
                          <td align="center">'.$puntosB[109].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[110].'</td>
                          <td align="center">110</td>
                          <td align="center">110</td>
                          <td align="center">'.$puntosB[110].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[111].'</td>
                          <td align="center">111</td>
                          <td align="center">111</td>
                          <td align="center">'.$puntosB[111].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[112].'</td>
                          <td align="center">112</td>
                          <td align="center">112</td>
                          <td align="center">'.$puntosB[112].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[113].'</td>
                          <td align="center">113</td>
                          <td align="center">113</td>
                          <td align="center">'.$puntosB[113].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[114].'</td>
                          <td align="center">114</td>
                          <td align="center">114</td>
                          <td align="center">'.$puntosB[114].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[115].'</td>
                          <td align="center">115</td>
                          <td align="center">115</td>
                          <td align="center">'.$puntosB[115].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[116].'</td>
                          <td align="center">116</td>
                          <td align="center">116</td>
                          <td align="center">'.$puntosB[116].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[117].'</td>
                          <td align="center">117</td>
                          <td align="center">117</td>
                          <td align="center">'.$puntosB[117].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[118].'</td>
                          <td align="center">118</td>
                          <td align="center">118</td>
                          <td align="center">'.$puntosB[118].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[119].'</td>
                          <td align="center">119</td>
                          <td align="center">119</td>
                          <td align="center">'.$puntosB[119].'</td>
                        </tr>
                        <tr>
                          <td align="center">'.$puntosA[120].'</td>
                          <td align="center">120</td>
                          <td align="center">120</td>
                          <td align="center">'.$puntosB[120].'</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table border="1">
                  <tr bgcolor="black">
                    <td colspan="3" align="center" height="18px">
                      <font color="white" size="12px"><b>FIRMA EN CASO DE PROTESTA</b></font>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" height="55px">
                      <b>
                        <font size="11">EQUIPO "A"<br>
                          SI&nbsp;|__|&nbsp;&nbsp;NO&nbsp;|__|                
                        </font>
                      </b>
                    </td>
                    <td align="center">
                      <b>
                        <font size="11">EQUIPO "B"<br>
                          SI&nbsp;|__|&nbsp;&nbsp;NO&nbsp;|__|                
                        </font>
                      </b>
                    </td>
                    <td align="center">&nbsp;<br>&nbsp;<br><br>_________________<br>
                      <font size="4">FIRMA DEL CAPITAN QUE PROTESTA</font>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <b>EQUIPO "B" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$jugadoresB[1]["nombre"].'</u></b>
                <br>
                <table border="0">
                  <tr>
                    <td colspan="3" align="center">
                      <font size="7px">Detenciones</font>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="4" align="center"><font size="7px">Faltas de Equipo</font></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="center">Primer Tiempo</td>
                    <td colspan="2">1
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="2">2
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="4" align="center">Segundo Tiempo</td>
                    <td colspan="2">3
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="2">4
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table border="1">
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    </td>
                    <td colspan="9" align="center">&nbsp;</td>
                  </tr>
                </table>
                <table border="1">
                  <tr>
                    <td rowspan="2" width="30px" align="center">
                      No. Lic.
                    </td>
                    <td rowspan="2" width="180px" align="center">
                      NOMBRE DE JUGADORES
                    </td>
                    <td rowspan="2" width="20px" align="center">
                      No.
                    </td>
                    <td rowspan="2" width="20px" align="center">
                      E
                    </td>
                    <td  width="65px" align="center">FALTAS
                    </td>
                  </tr>
                  <tr>
                    <td width="13px" align="center">1</td>
                    <td width="13px" align="center">2</td>
                    <td width="13px" align="center">3</td>
                    <td width="13px" align="center">4</td>
                    <td width="13px" align="center">5</td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[1]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[1]["nam"].' '.$jugadoresB[1]["ap_paterno"].' '.$jugadoresB[1]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[1]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[2]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[2]["nam"].' '.$jugadoresB[2]["ap_paterno"].' '.$jugadoresB[2]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[2]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[3]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[3]["nam"].' '.$jugadoresB[3]["ap_paterno"].' '.$jugadoresB[3]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[3]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[4]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[4]["nam"].' '.$jugadoresB[4]["ap_paterno"].' '.$jugadoresB[4]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[4]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[5]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[5]["nam"].' '.$jugadoresB[5]["ap_paterno"].' '.$jugadoresB[5]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[5]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[6]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[6]["nam"].' '.$jugadoresB[6]["ap_paterno"].' '.$jugadoresB[6]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[6]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[7]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[7]["nam"].' '.$jugadoresB[7]["ap_paterno"].' '.$jugadoresB[7]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[7]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[8]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[8]["nam"].' '.$jugadoresB[8]["ap_paterno"].' '.$jugadoresB[8]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[8]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[9]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[9]["nam"].' '.$jugadoresB[9]["ap_paterno"].' '.$jugadoresB[9]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[9]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[10]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[10]["nam"].' '.$jugadoresB[10]["ap_paterno"].' '.$jugadoresB[10]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[10]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[11]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[11]["nam"].' '.$jugadoresB[11]["ap_paterno"].' '.$jugadoresB[11]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[11]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td align="center" width="30px">'.$jugadoresB[12]["id_jugador"].'</td>
                    <td align="center" width="180px">'.$jugadoresB[12]["nam"].' '.$jugadoresB[12]["ap_paterno"].' '.$jugadoresB[12]["ap_materno"].'</td>
                    <td align="center" width="20px">'.$jugadoresB[12]["no_playera"].'</td>
                    <td align="center" width="20px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                    <td align="center" width="13px"></td>
                  </tr>
                  <tr>
                    <td width="276px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ENTRENADOR</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="276px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ASISTENTE</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                    <td width="13px">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
           </table>';
      $pdf->writeHTML($html,true, false,true, false,'');

      $html='<table cellpadding="2" border="1">
            <tr>
              <td width="120px" align="center">HORA INICIO<br></td>
              <td width="350px" rowspan="2">
                ANOTACIONES 1er TIEMPO "A" _____________&nbsp;&nbsp;&nbsp;&nbsp;"B"_____________<br>
                ANOTACIONES 2do TIEMPO "A" _____________&nbsp;&nbsp;&nbsp;&nbsp;"B"_____________<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUPLEMENTARIO "A" _____________&nbsp;&nbsp;&nbsp;&nbsp;"B"_____________
              </td>
              <td align="center" rowspan="2" width="167px">
                RESULTADO FINAL
                <br>EQUIPO "A" ________
                <br>EQUIPO "B" ________
              </td>
            </tr>
            <tr>
              <td width="120px" align="center">HORA FINAL<br></td>
            </tr>
            <tr>
              <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ANOTADOR ______________________________<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CRONOMETRO ___________________________&nbsp;&nbsp;&nbsp;&nbsp;1er JUEZ ____________________________<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;24 SEGUNDOS ___________________________&nbsp;&nbsp;&nbsp;&nbsp;2do JUEZ ____________________________
              </td>
              <td align="center">EQUIPO VENCEDOR<br><u>'.$ganador.'</u></td>
            </tr>
           </table>';
      $pdf->writeHTML($html,true, false,true, false,'');

      $pdf->Output('finanzas.pdf', 'I');
      
    }
  }

  ?>
