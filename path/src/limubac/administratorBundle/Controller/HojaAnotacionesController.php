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
  }

  ?>
