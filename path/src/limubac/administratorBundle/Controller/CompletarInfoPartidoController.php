<?php
  namespace limubac\administratorBundle\Controller;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use limubac\administratorBundle\consultas\ConsultasPartidos;
  use limubac\administratorBundle\Entity\Arbitran;

  class CompletarInfoPartidoController extends Controller{
    public function completarinfopartidoAction ($idpartido){
      $request=$this->getRequest();
      $consultasManager = new ConsultasPartidos();
      $doctrineManager= $this -> getDoctrine()->getManager();
      $listSede=$consultasManager->listCanchas($doctrineManager);
      $listArbitros=$consultasManager->listArbitros($doctrineManager);
      return $this->render('limubacadministratorBundle:administracion:completarInfoPartidos.html.twig',array('listSede'=>$listSede,'listArbitros'=>$listArbitros,'idPartido'=>$idpartido));
    }

    public function completarinfopartidoPostAction($idpartido){
      $consultasManager = new ConsultasPartidos();
      $idPartido=$idpartido;
      $doctrineManager= $this -> getDoctrine()->getManager();

      $idPartido=$idpartido;
      if(isset($_POST['cancha'])){
        $cancha=$_POST['cancha'];
      }if(isset($_POST['arbitro1'])){
        $arbitro1=$_POST['arbitro1'];
      }if(isset($_POST['arbitro2'])){
        $arbitro2=$_POST['arbitro2'];
      }if(isset($_POST['arbitro3'])){
        $arbitro3=$_POST['arbitro3'];
      }if(isset($_POST['horainicio'])){
        $horainicio=$_POST['horainicio'];
      }if(isset($_POST['HoraFin'])){
        $horafin=$_POST['HoraFin'];
      }

      $Arbitran = new Arbitran();
      $auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Arbitro');
      $auxFinder= $auxRepository->find(''.$arbitro1);
      $Arbitran->setIdArbitro1($auxFinder);

      $auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Arbitro');
      $auxFinder= $auxRepository->find(''.$arbitro2);
      $Arbitran->setIdArbitro2($auxFinder);

      $auxRepository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Arbitro');
      $auxFinder= $auxRepository->find(''.$arbitro3);
      $Arbitran->setIdArbitro3($auxFinder);
      $doctrineManager->persist($Arbitran);
      $doctrineManager-> flush();

      $idArbitran=$consultasManager->getArbitranByIdArbitros($arbitro1,$arbitro2,$arbitro3,$doctrineManager);//Que pasa cuando se repite el orden de los arbitros 

      $consultasManager->updateArbitranByPartido($idArbitran,$idPartido,$doctrineManager);

      $consultasManager->updateSedebyPartido($cancha,$idPartido,$doctrineManager);

      print_r($_POST);
      return $this->redirect($this->generateUrl('limubacadministrator_partidos'));
    }
  }
?>
