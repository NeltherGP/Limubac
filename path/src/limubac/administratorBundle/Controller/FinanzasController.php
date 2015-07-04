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
        use limubac\administratorBundle\tcpdf\finanzas;

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
            ->select('e.idEquipo as ide','e.nombre AS equipo','c.nombre AS categoria','f.inscripcion','f.dia','f.hora','f.monto','f.cuenta','f.manejo','f.mes1','f.mes2','f.mes3','f.mes4','f.mes5','f.mes6','f.mes7')
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
            $pdf = $this->get("white_october.tcpdf")->create();

            // set names parameters
            $Author="Farid Carreon";
            $Title="Finanzas Limubac";
            $Subject="TCPDF Tutorial";
            $Torneo=intval($_REQUEST['pdf']);
            $NomTorneo="";

            // connect to the db 
            $link = mysql_connect('www.mcflylabs.com','mcfly_nelther','Limubac_bd') or die('Cannot connect to the DB');
            mysql_select_db('mcflylab_limubactest1',$link) or die('Cannot select the DB');

            // grab the posts from the db
            $query = "SELECT *
              FROM `torneo` AS t
              WHERE t.id_torneo='".$Torneo."';";
            $result = mysql_query($query) or die('Errant query:  '.$query);

            // get tournament name 
            $x=0;
            while($row=mysql_fetch_array($result)){  
                $NomTorneo=$row['nombre'];
            }

            // create new PDF document
            //$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($Author);
            $pdf->SetTitle($Title);
            $pdf->SetSubject($Subject);
            $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // set default header data
            $pdf->SetHeaderData('logoLIMUBAC.png', 25,$NomTorneo, $Title);

            // set header and footer fonts
            //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
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
            $query = "SELECT e.nombre AS equipo, c.nombre AS categoria, f.inscripcion, f.cuenta, f.mes_1, f.mes_2, f.mes_3, f.mes_4, f.mes_5, f.mes_6, f.mes_7
               FROM `finanzas` AS f
              JOIN `equipo` AS e USING (id_equipo)
              JOIN `categoria` AS c ON e.id_categoria=c.id_categoria
              JOIN `participan_t` AS p ON p.id_equipo=f.id_equipo
              WHERE f.id_torneo=$Torneo AND p.id_torneo=$Torneo;";
            $result = mysql_query($query) or die('Errant query:  '.$query);

            // fill data array
            $num_fields = mysql_num_fields($result); 
            $j=0;
            $x=1;
            $object = array();
            while($row=mysql_fetch_array($result)){  
              for($j=0;$j<$num_fields;$j++){
               $name = mysql_field_name($result, $j);
               $object[$x][$name]=$row[$name];
              }$x++;
            }

            // data loading
            $data = array();
            for ($i=1; $i <=count($object) ; $i++) { 
                $data[$i]['equipo']=$object[$i]['equipo'];
                $data[$i]['categoria']=$object[$i]['categoria'];
                $data[$i]['inscripcion']=$object[$i]['inscripcion'];
                $data[$i]['cuenta']=$object[$i]['cuenta'];
                $data[$i]['mes_1']=$object[$i]['mes_1'];
                $data[$i]['mes_2']=$object[$i]['mes_2'];
                $data[$i]['mes_3']=$object[$i]['mes_3'];
                $data[$i]['mes_4']=$object[$i]['mes_4'];
                $data[$i]['mes_5']=$object[$i]['mes_5'];
                $data[$i]['mes_6']=$object[$i]['mes_6'];
                $data[$i]['mes_7']=$object[$i]['mes_7'];
            }

            // sum data
                $inscritos=count($object);
                $pendientes=0;
                $inscripcion=0;
                $fianza=0;
                $mensualidad=0;
                $total=0;
                for ($i=1; $i<=count($object) ; $i++) { 
                    if ($object[$i]['inscripcion'] == 0) {
                        $pendientes++;
                    }
                    $inscripcion= $inscripcion + $object[$i]['inscripcion'];
                    $fianza= $fianza + $object[$i]['cuenta'];
                    $mensualidad= $mensualidad + $object[$i]['mes_1'] + $object[$i]['mes_2'] + $object[$i]['mes_3'] + $object[$i]['mes_4'] + $object[$i]['mes_5'] + $object[$i]['mes_6'] + $object[$i]['mes_7'];
                }
                $total = $inscripcion + $fianza + $mensualidad;

            // print html content
            $html = '<br><br><br>
            <table cellpadding="2" cellspacing="2" border="1">
                <tr>
                    <th align="center" colspan="2" bgcolor="#DCDCDC"><b>INFORMACI&Oacute;N GENERAL</b></th>
                </tr>
                <tr>
                    <td>Equipos inscritos</td>
                    <td align="center">'.$inscritos.'</td>
                </tr>
                <tr>
                    <td>Equipos pendientes de pago</td>
                    <td align="center">'.$pendientes.'</td>
                    
                </tr>
                <tr>
                    <td>Ingresos por inscripci&oacute;n</td>
                    <td align="center">$ '.$inscripcion.'</td>
                </tr>
                <tr>
                    <td>Ingresos por fianza</td>
                    <td align="center">$ '.$fianza.'</td>
                </tr>
                <tr>
                    <td>Ingresos por mesualidad</td>
                    <td align="center">$ '.$mensualidad.'</td>
                </tr>
                <tr>
                    <td align="center"><b>Ingresos totales</b></td>
                    <td align="center"><b>$ '.$total.'</b></td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            <h2>Detalle de pagos por equipo</h2>';
            $pdf->writeHTML($html,true, false,true, false,'');

            // print colored table
                // Colors, line width and bold font
            $pdf->SetFillColor(224, 224, 224);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.3);
            $pdf->SetFont('', 'B');
            // Header
            $w = array(30, 30, 22, 13, 12, 12, 12, 12, 12, 12, 12);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $pdf->Ln();
            // Color and font restoration
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            // Data
            $fill = 0;
            for ($i=1; $i <= count($data); $i++) { 
                $pdf->Cell($w[0],6,$data[$i]['equipo'], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[1],6,$data[$i]['categoria'], 'LR', 0, 'L', $fill);
                $pdf->Cell($w[2],6,number_format($data[$i]['inscripcion']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[3],6,number_format($data[$i]['cuenta']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[4],6,number_format($data[$i]['mes_1']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[5],6,number_format($data[$i]['mes_2']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[6],6,number_format($data[$i]['mes_3']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[7],6,number_format($data[$i]['mes_4']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[8],6,number_format($data[$i]['mes_5']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[9],6,number_format($data[$i]['mes_6']), 'LR', 0, 'R', $fill);
                $pdf->Cell($w[10],6,number_format($data[$i]['mes_7']), 'LR', 0, 'R', $fill);
                $pdf->Ln();
                $fill=!$fill;
            }
            $pdf->Cell(array_sum($w), 0, '', 'T');

            // ---------------------------------------------------------

            // close and output PDF document
            $pdf->Output('finanzas_'.$NomTorneo.'.pdf', 'I');

            //============================================================+
            // END OF FILE
            //============================================================+*/
        }
        elseif (!empty($_REQUEST['sel'])) {
            $repository = $this->getDoctrine()->getRepository('limubacadministratorBundle:Finanzas');
            $queryEdit = $repository->createQueryBuilder('f')
            ->select('e.idEquipo as ide','e.nombre AS equipo','c.nombre AS categoria','f.inscripcion','f.dia','f.hora','f.monto','f.cuenta','f.manejo','f.mes1','f.mes2','f.mes3','f.mes4','f.mes5','f.mes6','f.mes7')
            ->join('limubacadministratorBundle:Equipo', 'e', 'WITH' ,'f.idEquipo = e.idEquipo')
            ->join('limubacadministratorBundle:ParticipanT', 'p', 'WITH' ,'p.idEquipo = f.idEquipo')
            ->join('limubacadministratorBundle:Categoria', 'c', 'WITH' ,'e.idCategoria = c.idCategoria')
            ->where('f.idTorneo = :word AND p.idTorneo = :word')
            ->setParameter('word', $_REQUEST['sel'])
            ->orderBy('categoria')
            ->getQuery();
            $resul = $queryEdit->getResult();

            $queryIngresos = $repository->createQueryBuilder('i')
            ->select('sum(i.inscripcion + i.cuenta + i.mes1 + i.mes2 + i.mes3 + i.mes4 + i.mes5 + i.mes6 + i.mes7)')
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

            return $this->render('limubacadministratorBundle:administracion:finanzas.html.twig', array('ingresos' => $ing, 'inscritos' => $ins, 'pendientes' => $pen, 'query' => $resul, 'torneos' => $resultor, 'aide' => $_REQUEST['sel']));
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
            ->set('f.mes1', ':mes1')
            ->set('f.mes2', ':mes2')
            ->set('f.mes3', ':mes3')
            ->set('f.mes4', ':mes4')
            ->set('f.mes5', ':mes5')
            ->set('f.mes6', ':mes6')
            ->set('f.mes7', ':mes7')
            ->where('f.idFinanzas= :idc')
            ->setParameter('ins', $_REQUEST['inscripcion'])
            ->setParameter('dia', $_REQUEST['dia'])
            ->setParameter('hra', $_REQUEST['hora'])
            ->setParameter('mnt', $_REQUEST['monto'])
            ->setParameter('cnt', $_REQUEST['acuenta'])
            ->setParameter('mnj', $_REQUEST['manejo'])
            ->setParameter('mes1', $_REQUEST['mes1'])
            ->setParameter('mes2', $_REQUEST['mes2'])
            ->setParameter('mes3', $_REQUEST['mes3'])
            ->setParameter('mes4', $_REQUEST['mes4'])
            ->setParameter('mes5', $_REQUEST['mes5'])
            ->setParameter('mes6', $_REQUEST['mes6'])
            ->setParameter('mes7', $_REQUEST['mes7'])
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