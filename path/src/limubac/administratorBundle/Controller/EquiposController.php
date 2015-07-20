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
		use limubac\administratorBundle\Entity\Fotos;
		use limubac\administratorBundle\Entity\Finanzas;

	include 'funcionesExtras.php';	
		
class EquiposController extends Controller{
		
	public function equiposAction(){
		
		//Solicitando Modificacion
		if(isset($_POST['activar'])){
			activandoModificacion($_POST['activar'],$this);
		}
		
		//Solicitando Modificacion
		if(isset($_POST['solicitando'])){
			$body = 'Gracias por contactarnos. Esta es una respuesta automática confirmando la solicutud para modificar el equipo <b>'.$_POST['solicitando'].'</b>. Nuestro equipo se pondrá en contacto con usted tan pronto como sea posible.
		  <br>';		  
			enviaCorreo('Se ha recibido notificacion de la peticion de cambio','edgar_5_11@hotmail.com',$body,$this);
		}
		
		//Registrar a Torneo
		if(isset($_REQUEST['Torneo'])){ //valor del torneo al que se reitrara el equipo
			$Manager = $this->getDoctrine()->getManager();
			//echo "Torneo: ".$_REQUEST['Torneo']."   equipo: ".$_REQUEST['idEquipo']."<br>";
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['idEquipo']);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
			$Torneo = $repositorio->find($_REQUEST['Torneo']);
			
			//Restricciones
			if(restringe($equipo,$Torneo,$this)){
				$Participan = new ParticipanT();

				$equipo->setRegistrado(true);
				$Participan->setIdTorneo($Torneo);
				$Participan->setIdEquipo($equipo);
				$Manager->persist($Participan);
				$Manager->persist($Torneo);
				$Manager->flush();

				$finanzas = new Finanzas();
				$finanzas -> setIdEquipo($equipo);
				$finanzas -> setIdTorneo($Torneo);
				$finanzas -> setInscripcion(0);
				$finanzas -> setCuenta("");
				$finanzas -> setManejo("");
				$finanzas -> setHora("");
				$finanzas -> setMonto("");
				$finanzas -> setDia("");
				$finanzas -> setMes1(0);
				$finanzas -> setMes2(0);
				$finanzas -> setMes3(0);
				$finanzas -> setMes4(0);
				$finanzas -> setMes5(0);
				$finanzas -> setMes6(0);
				$finanzas -> setMes7(0);
				$em = $this->getDoctrine()->getManager();
	            $em -> persist($finanzas);
	            $em -> flush();
			}
		}
		
		//Lista de Equipos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		$ListaEquipos = $repositorio->findAll();
		
		//Conseguir Torneos
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
		$Torneos = $repositorio->findAll();
		
		//Conseguir Ramas
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
		$Ramas = $repositorio->findAll();
		
		//Editando Equipo
		if(isset($_REQUEST['editarEquipo'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
			$Participan = $repositorio->find($_REQUEST['editarEquipo']);
			
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($Participan);
			$Manager->flush();
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$Equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
			$Rama =$repositorio->find($_REQUEST['rama']);
			$Equipo->setIdRama($Rama);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
			$Categoria =$repositorio->find($_REQUEST['Categoria']);
			$Equipo->setIdCategoria($Categoria);
			
			$Equipo->setNombre($_REQUEST['NuevoEquipo']);
			$Manager->persist($Equipo);
			$Manager->flush();
			
		}
		
		//Cantidad Jugadores en un equipo
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Integra");
		$query = $repositorio->createQueryBuilder('i')
				->select('IDENTITY(i.idEquipo)','count(i.idEquipo)')
				->groupBy('i.idEquipo')
				->getQuery();
			$jugadores = $query->getResult();
		//print_r($jugadores);
    	return $this->render('limubacadministratorBundle:administracion:equipos.html.twig', array('Jugadores'=>$jugadores,'listEquip' =>$ListaEquipos,'Torneos'=>$Torneos,'Ramas'=>$Ramas));
    }

	protected function getUploadDir(){
		return '\upload\images/';
	}

	public function equipoAction(){
		$resuly ="";
		$nj="";
		$Mensaje = null;
		$jugador = new Jugador();
        $form = $this->createForm(new JugadorType(), $jugador);

        $request = $this->get('request');
        $form->handleRequest($request);
		
		//Agregando nuevo equipo
		if(isset($_POST['NuevoEquipo'])){
			$equipo = new Equipo();
			$equipo->setNombre($_POST['NuevoEquipo']);
			$equipo->setRegistrado(false);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
			$cat = $repositorio->find($_POST['Categoria']);
			$equipo->setIdCategoria($cat);
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:RamaEquipo");
			$ram = $repositorio->find($_POST['rama']);
			$equipo->setIdRama($ram);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();

			$_REQUEST['opciones'] = $equipo->getIdEquipo();
		}
		
		//Modificando Jugador 
		if(isset($_REQUEST['opciones']) && isset($_REQUEST['NoJugador']) && isset($_REQUEST['idJugador'])){
			$Manager = $this->getDoctrine()->getManager();
			$query = $Manager->createQuery("UPDATE limubac\administratorBundle\Entity\Integra as i SET i.noPlayera=".$_REQUEST['NoJugador']." where i.idEquipo='".$_REQUEST['opciones']."' and i.idJugador='".$_REQUEST['idJugador']."'");
			$query->getResult();
			 
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$jugador = $repositorio->find($_REQUEST['idJugador']);
			
			//--------------------------------------de aqui sigue fafi

			$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
            $queryEdit = $repository->createQueryBuilder('e')
            ->select('e.idJugador','e.nombre','e.apPaterno','e.apMaterno','e.fNacimiento','e.correo','e.telefono','e.profesion','IDENTITY(e.idStatus)','IDENTITY(e.idGenero)','e.estatura','e.peso','IDENTITY(e.idTiposanguineo)','IDENTITY(e.idFoto)')
            //->join('limubacadministratorBundle:Fotos', 'fot', 'WITH' ,'fot.idFoto = e.idFoto')
            ->where('e.idJugador = :word')
            ->setParameter('word', $_REQUEST['idJugador'])
            ->getQuery();
            $resuly = $queryEdit->getResult();
            $nj = $_REQUEST['NoJugador'];

		}

		//Agregar fotografia
        if (isset($_POST['fotosup'])) {
	   		$dir = __DIR__.'/../../../../web/upload/images/';

			$validextensions = array("jpeg", "jpg", "png");
			$temporary = explode(".", $_FILES["file"]["name"]);
			$file_extension = end($temporary);

			if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
			) && ($_FILES["file"]["size"] < 300000)//Approx. 100kb files can be uploaded.
			&& in_array($file_extension, $validextensions)) {
						
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
				}else{
					if (file_exists($dir. $_FILES["file"]["name"])) {
						echo $_FILES["file"]["name"] . " <b> ya existe.</b> ";
					}else{
						//validar foto
						$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
	                    $queryEdit = $repository->createQueryBuilder('f')
	                        ->select('f.idFoto','f.foto','f.nombre')
	                        ->where('f.nombre = :word')
	                        ->setParameter('word', "foto_".$_REQUEST['idi'])
	                        ->getQuery();
	                    $resul1 = $queryEdit->getResult();

	                    if (count($resul1) >= 1) {
							$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
					        $queryAct = $repository->createQueryBuilder('o');
					        $q = $queryAct->update('limubacadministratorBundle:Fotos', 'o')
					            ->set('o.foto', ':fot')
					            ->where('o.idFoto= :idf')
					            ->setParameter('idf', $resul1[0]['idFoto'])
					            ->setParameter('fot', $_FILES["file"]["name"])
					            ->getQuery();
					        $resul = $q->execute();

					        echo "<span>Imagen subida exitosamente...!!</span><br/>";
							echo "<br/><b>Nombre de la imagen:</b> " . $_FILES["file"]["name"] . "<br>";
							echo "<b>Tipo:</b> " . $_FILES["file"]["type"] . "<br>";
							echo "<b>Tamano:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
							//echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";

							move_uploaded_file($_FILES["file"]["tmp_name"], $dir . $_FILES["file"]["name"]);
							//$imgFullpath = $dir . $_FILES["file"]["name"];
							//echo "<b>Stored in:</b><a href = '$imgFullpath' target='_blank'> " .$imgFullpath.'<a>';

					        $path = $dir.$resul1[0]['foto'];
							unlink($path);
	                    }else{
	                    	//insertar url de imagen en base de datos
							$foto = new Fotos();
							$foto -> setFoto($_FILES["file"]["name"]);
							$foto -> setNombre("foto_".$_REQUEST['idi']);

							$em = $this ->getDoctrine() ->getManager();
							$em -> persist($foto);
							$em -> flush();

							$repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
					        $queryAct = $repository->createQueryBuilder('z');
					        $q = $queryAct->update('limubacadministratorBundle:Jugador', 'z')
					            ->set('z.idFoto', ':fot')
					            ->where('z.idJugador= :idj')
					            ->setParameter('idj', $_REQUEST['idi'])
					            ->setParameter('fot', $foto ->getIdFoto())
					            ->getQuery();
					        $resul = $q->execute();

					        echo "<span>Imagen subida exitosamente...!!</span><br/>";
							echo "<br/><b>Nombre de la imagen:</b> " . $_FILES["file"]["name"] . "<br>";
							echo "<b>Tipo:</b> " . $_FILES["file"]["type"] . "<br>";
							echo "<b>Tamano:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
							//echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";

							move_uploaded_file($_FILES["file"]["tmp_name"], $dir . $_FILES["file"]["name"]);
							//$imgFullpath = $dir . $_FILES["file"]["name"];
							//echo "<b>Stored in:</b><a href = '$imgFullpath' target='_blank'> " .$imgFullpath.'<a>';
	                    }

				        return $this->redirect($this->generateUrl('limubacadministrator_equipo',array('opciones'=>$_REQUEST['equipoi'])));
					}
				}
			}else{
				echo "<span>***Invalid file Size or Type***<span>";
			}
		}
		//fin de fotografia
		
		//Borrar campos
		if (isset($_REQUEST['borrar'])) {
			return $this->redirect($this->generateUrl('limubacadministrator_equipo',array('opciones'=>$_REQUEST['equipoid'])));
		}

        if (isset($_REQUEST['jugador'])) {
            $url_to_parse = $_SERVER['REQUEST_URI'];
            $parsed_url = parse_url($url_to_parse);
            if (empty($parsed_url['query'])) {
                return $this->render('limubacadministratorBundle:administracion:addjugador.html.twig',array('form' => $form->createView()));
            }
            else{
                $out = $_REQUEST['jugador'];
                if (is_array($out) && !empty($out)) {
                	//Genera el curp
                		$fn = $out['fNacimiento'];
                    	$dt = date_create_from_format('Y-m-d', $fn);
                    $nom = strtoupper (substr($out['nombre'],0,1));
                	$app = strtoupper (substr($out['apPaterno'],0,2));
                	$apm = strtoupper (substr($out['apMaterno'],0,1));
                	$ani = substr($fn,2,2);
                	$mes = substr($fn,5,2);
                	$dia = substr($fn,8,2);
                	if ($out['idGenero'] == 1)
                      	$sex = "H";
                   	elseif ($out['idGenero'] == 2)
                   		$sex = "F";
                   	else
                   		$sex = "G";
                    $curp = $app.$apm.$nom.$ani.$mes.$dia.$sex;

                   	//Buscar coincidencias
                    $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Jugador');
		            $queryEdit = $repository->createQueryBuilder('e')
		            ->select('e.idJugador','e.nombre','e.apPaterno','e.apMaterno','e.fNacimiento','e.correo','e.telefono','e.profesion','IDENTITY(e.idStatus)','IDENTITY(e.idGenero)','e.estatura','e.peso','IDENTITY(e.idTiposanguineo)','IDENTITY(e.idFoto)', 'e.curp')
		            //->join('limubacadministratorBundle:Fotos', 'fot', 'WITH' ,'fot.idFoto = e.idFoto')
		            ->where('e.curp = :word OR e.idJugador = :wordd')
		            ->setParameter('word', $curp)
		            ->setParameter('wordd', $out['idJugador'])
		            ->getQuery();
		            $resul = $queryEdit->getResult();

                    //Validacion de actualizacion o insercion
                    if (count($resul) == 0){
                    	//Inicio de agregar jugador
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
	                    $player -> setIdGenero($category);
	                    $player -> setEstatura($out['estatura']);
	                    $player -> setPeso($out['peso']);
	                        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
	                        $category = $class_repository->find($out['idTiposanguineo']);
	                    $player -> setIdTiposanguineo($category);
	                    $player -> setCurp($curp);
	                        //$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Fotos');
	                        //$category = $class_repository->find($out['idFoto']);
	                    //$player -> setIdFoto($category);

	                    $em = $this->getDoctrine()->getManager();
	                    $em -> persist($player);
	                    $em -> flush();
						//Fin de agregar jugador

						//Agregar Al equipo
						$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
						$equipo = $repositorio->find($_REQUEST['opciones']);
					
						//Agregando el jugador al equipo
						$integra = new Integra();
						$integra->setNoPlayera(intval($out['numero']));
						$integra->setIdEquipo($equipo);
						$integra->setIdJugador($player);
					
						$Manager = $this->getDoctrine()->getManager();
						$Manager->persist($integra);
						$Manager-> flush();
					
					
					//Fin Agregar Al Equipo
                    }else{
                    	//Inicio de actualizar jugadores
                    	$fn = $out['fNacimiento'];

        				$class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Genero');
				        $category2 = $class_repository->find($out['idGenero']);

				        $class_repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:TipoSanguineo');
				        $category3 = $class_repository->find($out['idTiposanguineo']);

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
				            ->set('z.idGenero', ':ige')
				            ->set('z.estatura', ':est')
				            ->set('z.peso', ':pes')
				            ->set('z.idTiposanguineo', ':iti')
				            ->set('z.curp', ':crp')
				            //->set('z.idFoto', ':fot')
				            ->where('z.idJugador= :idj')
				            ->setParameter('idj', $out['idJugador'])
				            ->setParameter('nom', $out['nombre'])
				            ->setParameter('app', $out['apPaterno'])
				            ->setParameter('apm', $out['apMaterno'])
				            ->setParameter('fna', new \DateTime($fn))
				            ->setParameter('cor', $out['correo'])
				            ->setParameter('tel', $out['telefono'])
				            ->setParameter('pro', $out['profesion'])
				            ->setParameter('ige', $category2)
				            ->setParameter('est', $out['estatura'])
				            ->setParameter('pes', $out['peso'])
				            ->setParameter('iti', $category3)
				            ->setParameter('crp', $curp)
				            //->setParameter('fot', $resul[0]['idFoto'])
				            ->getQuery();
				        $resul = $q->execute();
				        //Fin de actualizar jugadores
                    }
                }
                else{
                    return new SymfonyResponse('Algo Fallo!');
                }
            }
        }
		
		
		//Agregar o modificar capitan
		if(isset($_REQUEST['opciones']) and isset($_POST['idCapitan'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Capi = $repositorio->find($_POST['idCapitan']);
			
			$equipo->setIdCapitan($Capi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Representante
		if(isset($_REQUEST['opciones']) and isset($_POST['idRepresentante'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Repre= $repositorio->find($_POST['idRepresentante']);

			$equipo->setRepresentante($Repre);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Agregar o modificar Auxiliar
		if(isset($_REQUEST['opciones']) and isset($_POST['idAuxiliar'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Jugador");
			$Auxi= $repositorio->find($_POST['idAuxiliar']);
			
			$equipo->setAuxiliar($Auxi);
			
			$Manager = $this->getDoctrine()->getManager();
			$Manager->persist($equipo);
			$Manager->flush();
		}
		
		//Llenado de los expacios
		if(isset($_REQUEST['opciones'])){
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
			$equipo = $repositorio->find($_REQUEST['opciones']);
			
			
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
		return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('form' => $form->createView(),'equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'NoEquipo'=>$_REQUEST['opciones'],'res'=>$resuly, 'nj' => $nj));
		
		}else{//si no esta definido el valor del equipo
			
			echo "cuatro";
		}
		//return $this->render('limubacadministratorBundle:administracion:equipo.html.twig',array('equipo'=>$equipo,'jugadores'=>$jugadores,'capitan'=>$Capi,'representante'=>$Representante,'auxiliar'=>$Auxiliar,'mensaje'=>$Mensaje,'form' => $form->createView()));

	}
	
	public function equipoNuevoAction(){
		//Conseguir Categorias
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
		$Categorias = $repositorio->findAll();
		
		return $this->render('limubacadministratorBundle:administracion:equipoNuevo.html.twig',array('Categorias'=>$Categorias));
	}
	
	public function editarEquipoAction(){
		//Conseguir Categorias
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Categoria");
		$Categorias = $repositorio->findAll();
		
		
		//Conseguir Equipo
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Equipo");
		$Equipo = $repositorio->find($_REQUEST['opciones']);
		
		//Conseguir ParticipanT
		$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:ParticipanT");
		$query = $repositorio->createQueryBuilder('p')
				->select('p.idRegistro')
				->where('p.idEquipo = '.$Equipo->getIdEquipo() )
				->getQuery();
			
		$Participan= $query->getResult();
		//var_dump($Participan);
		return $this->render('limubacadministratorBundle:administracion:editarEquipo.html.twig',array('Participan'=>$Participan,'Equipo'=>$Equipo,'Categorias'=>$Categorias));
	}
	
	public function equipoATorneoAction(){
		//Registrar en torneo
		if(isset($_REQUEST['Registro'])){//id del equipo
			$repositorio = $this->getDoctrine()->getRepository("limubacadministratorBundle:Torneo");
			$query = $repositorio->createQueryBuilder('t')
				->select('t.idTorneo','t.nombre','t.costo','t.fInicio','t.fTermino')
				->where('t.inscripcionAbierta = 1')
				->getQuery();
			$Torneos= $query->getResult();
		}else{
			echo "<script type='text/javascript'>alert('No Deberias estar aqui!!');</script>";
			return $this->render('limubacadministratorBundle:administracion:admin.html.twig');
		}
		//var_dump($Torneos);
		return $this->render('limubacadministratorBundle:administracion:equipoATorneo.html.twig',array('Torneos'=>$Torneos,'equipo'=>$_REQUEST['Registro']));
	}
	
	
}	
?>