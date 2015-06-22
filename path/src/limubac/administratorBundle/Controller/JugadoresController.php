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

class JugadoresController extends Controller{
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
            ->where('e.idJugador = :word')
            ->setParameter('word', $ed)
            ->getQuery();
            $resul = $queryEdit->getResult();

            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
            $queryEdit = $repository->createQueryBuilder('h')
            ->select('h.idFoto','h.foto','h.nombre')
            ->where('h.idFoto = :word')
            ->setParameter('word', $resul[0][4])
            ->getQuery();
            $resulpho = $queryEdit->getResult();

            return $this->render('limubacadministratorBundle:administracion:edita.html.twig',array('form' => $form->createView(), 'edita' => $resul, 'photo' => $resulpho));
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


//-----------------------FINAL CONTROLADRO DE FAFI---------------------------------------------------
}
?>