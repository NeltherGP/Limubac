<?php

namespace limubac\administratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use limubac\administratorBundle\Entity\Jugador;
use limubac\administratorBundle\Entity\Fotos;
use limubac\administratorBundle\Entity\TipoSanguineo;
use limubac\administratorBundle\Form\Type\JugadorType;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use limubac\administratorBundle\Models\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;


class DefaultController extends Controller{

 public $idUserPics = 0;

    public function indexAction($name){
        return $this->render('limubacadministratorBundle:Default:index.html.twig', array('name' => $name));
    }

    public function adminAction(){
        return $this->render('limubacadministratorBundle:administracion:adminPanel.html.twig');
    }

    public function hojaAnotacionesAction(){
    	return $this->render('limubacadministratorBundle:administracion:hojaAnotaciones.html.twig');
    }

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
        //$request = $this->getRequest();
        //$workerId = $request->get('number');
        
        /*
        $em = $this->getDoctrine()->getManager();
        //$repository = $em->getRepository('limubacadministratorBundle:Fotos');
        $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');

        $photo = $repository->find($workerId);

        $response = new StreamedResponse(function () use ($photo) {
            //echo stream_get_contents($photo['foto:limubac\administratorBundle\Entity\Fotos:private']);
        });

        $response->headers->set('Content-Type', 'image/jpeg');
        
        return $response;
        
        if (!empty($workerId)) {
            $id = mysql_real_escape_string($workerId);

            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
            $query = $repository->createQueryBuilder('p')
                ->select('p.idFoto','p.nombre','p.foto')
                ->where('p.idFoto = :word')
                ->setParameter('word', $id)
                ->getQuery();
            $entities = $query->getResult();

            foreach ($entities as $row) {
                $imageData = $row['foto'];
            }
            header("content-type: image/jpeg");
            echo '<img src="data:image/gif;base64,' . base64_encode($imageData) . '" />';
        }
        else{
            echo "Error!";
        }
        */
    }

    public function buscarAction(){
        return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
    }

    public function editarAction(){
        print_r($_REQUEST['jugador']);

        $upt = $_REQUEST['jugador'];
        echo("<br>aiidiii: ".$upt['idJugador']);
        $fn = $upt['fNacimiento'];
        $dt = date_create_from_format('Y-m-d', $fn);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Status');
        $category1 = $class_repository->find($upt['idStatus']);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Genero');
        $category2 = $class_repository->find($upt['idGenero']);

        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
        $category3 = $class_repository->find($upt['idTiposanguineo']);

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
            ->set('z.foto', ':fot')
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
            ->setParameter('fot', 0)
            ->getQuery();
        $resul = $q->execute();

        return $this->redirect($this->generateUrl('limubacadministrator_jugadoresAdmin'));
    } 

    public function uploadosAction(Request $request){
        //return $this->render('limubacadministratorBundle:administracion:uploados.html.twig');
        if (isset($_POST['submit'])) {
            $pics = new Fotos();
            $idjug = $_REQUEST['id'];
            $status = "success";
            $message = '';

            
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
                    print_r($resul1);
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
}


/**
 * 
 Array ( 
    [jugador] => Array ( 
                    [nombre] => Fafi 
                    [apPaterno] => Hernandez 
                    [apMaterno] => Carreon 
                    [fNacimiento] => Array ( 
                                        [month] => 1 
                                        [day] => 1 
                                        [year] => 2010 
                                    ) 
                    [correo] => faficarreon@gmail.com 
                    [telefono] => 2342342 
                    [profesion] => Estudiante 
                    [idStatus] => 1 
                    [idGenero] => 1 
                    [estatura] => 1.75 
                    [peso] => 80 
                    [idTiposanguineo] => 1 
                    [foto] => image.png 
                    [_token] => a3Wv3efAZBiw3O353w38qSTY9HDu7NedQ-xTcPZulcE 
                    [Salvar] => 
                ) 
    )

Array ( 
    [0] => Array ( 
                [idFoto] => 38 
                [nombre] => 11 
            ) 
    [1] => Array ( 
                [idFoto] => 39 
                [nombre] => 11 
            ) 
    [2] => Array ( 
                [idFoto] => 40 
                [nombre] => 11 
            )
    )
 */
