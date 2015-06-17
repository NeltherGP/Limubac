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

class FinanzasController extends Controller{
    //****************************INICIO CONTRALADOR FINANZAS****************************
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
    //****************************FINAL CONTRALADOR FINANZAS****************************
}
?>