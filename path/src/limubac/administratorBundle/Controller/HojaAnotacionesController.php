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
    $Errores = array("AsistenciaA"=>0,"AsistenciaB"=>0,"Playera"=>0,"General"=>0);


    //echo $request->getMethod() ;//Quitar o comentar

    $List_A=$consultasManager->listJugadoresEquipo('A',$idPartido,$doctrineManager);
    // print_r($List_A);
    $List_B=$consultasManager->listJugadoresEquipo('B',$idPartido,$doctrineManager);

    $datosGenerales = $consultasManager->getEquipoByPartido($idPartido,$doctrineManager);

    if($request->getMethod() == 'POST')//si se envia el formulario
    {
      $datos = new hojaAnotacion();

      $validator = $this->get('validator');

      print_r($consultasManager);

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

      if(!empty($assistA)&&(!count($assistA<4))){

        foreach ($assistA as $a) {
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
      }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaA en el arreglo de errores
        $Errores['AsistenciaA']=1;
        $Errores['General']=1;
      }

      if(!empty($assistB)&&(!count($assistB)<4)){
        foreach ($assistB as $a) {
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
      }else{//Si hay menos de 4 asistencias cambia a 1 el valor de asistenciaB en el arreglo de errores
        $Errores['AsistenciaB']=1;
        $Errores['General']=1;
      }
      //END TOMAR ASISTENCIA

      //FALTAS
      if($Errores['General']==1){

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

      //Marcador por cuartos

      if(isset($_POST['MarcadorCuartoA'])){
        $marcadorCuartoA=$_POST['MarcadorCuartoA'];
      }
      if(isset($_POST['MarcadorCuartoB'])){
        $marcadorCuartoB=$_POST['MarcadorCuartoB'];
      }

      if(!empty($marcadorCuartoA)&&(!count($marcadorCuartoA)<=4)){
        for ($i=0; $i<count($marcadorCuartoA); $i++) {
          switch ($i) {
            case 0:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"primero",$marcadorCuartoA[$i],"A",$doctrineManager);
              break;
            case 1:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"segundo",$marcadorCuartoA[$i],"A",$doctrineManager);
              break;
            case 2:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"tercero",$marcadorCuartoA[$i],"A",$doctrineManager);
              break;
            case 3:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"cuarto",$marcadorCuartoA[$i],"A",$doctrineManager);
              break;
            case 4:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"complementario",$marcadorCuartoB[$i],"A",$doctrineManager);
              break;
          }
        }
      }else{
        echo("error con los marcadores de cuartos A");
      }

      if(!empty($marcadorCuartoB)&&(!count($marcadorCuartoB)<=4)){
        for ($i=0; $i<count($marcadorCuartoB); $i++) {
          switch ($i) {
            case 0:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"primero",$marcadorCuartoB[$i],"B",$doctrineManager);
              break;
            case 1:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"segundo",$marcadorCuartoB[$i],"B",$doctrineManager);
              break;
            case 2:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"tercero",$marcadorCuartoB[$i],"B",$doctrineManager);
              break;
            case 3:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"cuarto",$marcadorCuartoB[$i],"B",$doctrineManager);
              break;
            case 4:
                $consultasManager->MarcadoresCuartosPartidoById($idPartido,"complementario",$marcadorCuartoB[$i],"B",$doctrineManager);
              break;
          }
        }
      }else{
        echo("error con los marcadores de cuartos B");
      }

      //END Marcador por cuartos
      $datos->setPuntos(array_values((array_slice($_POST, 8,119))));

      $puntos = $datos->getPuntos();
      $puntosA=array();
      $puntosB=array();

      if(!empty($puntos)){
        // print_r($puntos);
        for ($j=0; $j < count($puntos); $j++) { //VALIDAR NUMEROS DE PLAYERA

          if(preg_match("/^([0-9])*([0-9])*$/",$puntos[$j])) {

            if(($j%2)==0){
              $puntosA[]=$puntos[$j];
            }else{
              $puntosB[]=$puntos[$j];
            }
          }else{
            $Errores['Playera']=1;
            $Errores['General']=1;
            break;
          }

        }
      if($Errores['General']!=1){
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
        $consultasManager->commitPartidoById($idPartido,$doctrineManager); //todo salio bien aqui debes redireccionar
      }else{
        //Algo salio mal, aqui debes recargar la pagina mostrando al usuario los errores
        print_r($Errores);
        return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales,'errores'=>$Errores));
      }

    }else{
      //No Hubo anotaciones
    }
      //
      //
      // $errors = $validator->validate($datos);
      //
      // if (count($errors) > 0) {
      //   $errorsString = (string) $errors;
      //   //echo $errorsString;
      // }
    }
    return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig',array('ListA'=>$List_A,'ListB'=>$List_B,'datosGenerales'=>$datosGenerales));
  }

  public function nombresEquiposAction(){
    $request = $this->container->get('request');
    $idPartido=0;
    $consultasManager = new ConsultasAnotaciones();
    $doctrineManager= $this -> getDoctrine()->getManager();

    $Equipos=json_encode($consultasManager->getEquipoByPartido($idPartido,$doctrineManager));

    return new Response($Equipos);

  }


}
?>
