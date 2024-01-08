<?php
App::uses('ConnectionManager', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'AppController');
App::import('Console', 'Command/ReporteShell');

/**
 * Description of InformeConciliacionController
 *
 * @author AZUNIGA
 */
class InformeConciliacionController extends AppController  {
    public $components = array('AWSSES', 'Session');
    
    function index(){
        $this->check_permissions($this);
        $this->layout = 'metronic_principal';
        $this->set("titulo", "Informe Conciliación");
        $this->loadModel('InformeConciliacion');
        $this->loadModel('ReporteBase');
        $fecha_inicio = date('Y-m-d', time() - 8 * 60 * 60);        
        $h_inicio = array('07:00' => '07:00', '08:00' => '08:00','19:00' => '19:00',  '20:00' => '20:00');
        $fecha_termino = date("Y-m-d");
        $h_termino = array('07:00' => '07:00', '08:00' => '08:00','19:00' => '19:00',  '20:00' => '20:00');
        $hora_inicio = '07:00';
        $hora_termino = '19:00';
        $faena_id = '';
        $reporte = array();
        
        try{
            if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
                $fecha_inicio = $this->request->query["fecha_inicio"];
                $fecha_termino = $this->request->query["fecha_termino"];
                
                $hora_inicio = $this->request->query["hora_inicio"];
                $hora_termino = $this->request->query["hora_termino"];
                
                $faena_id = $this->request->query["faena_id"];
                                
                if(isset($this->request->query["btn-descargar"])){
                    //$this->redirect("/InformeConciliacion/Descargar?faena_id=$faena_id&fecha_inicio=$fecha_inicio&hora_inicio=$hora_inicio&fecha_termino=$fecha_termino&hora_termino=$hora_termino");
                    $this->descargar($faena_id, $fecha_inicio, $hora_inicio, $fecha_termino, $hora_termino);
                    exit;
                    $this->Session->setFlash("Cueck");
                }
                //$this->descargar($faena_id, $fecha_inicio, $hora_inicio, $fecha_termino, $hora_termino);                
                
            }
        } catch (Exception $e) {
            $this->logger($this, $e->getMessage());
            print_r($e->getMessage());
        }
        
        
        $this->set("intervenciones", $reporte);
        
        $this->set("faena_id", $faena_id);
        $this->set("fecha_inicio", $fecha_inicio);
        $this->set("fecha_termino", $fecha_termino);
        $this->set("hora_inicio", $hora_inicio);
        $this->set("hora_termino", $hora_termino);
        $this->set("h_inicio", $h_inicio);
        $this->set("h_termino", $h_termino);

        $faenas = $this->Session->read("NombresFaenas");
        $this->set(compact('faenas'));
    }
    
    
    function descargar(){
        $this->layout = NULL;
        //$this->autoRender = false;
        $this->loadModel('ReporteBase');
        $arreglo = array();
        $faena_id = $this->request->query["faena_id"];
        $fecha_inicio = $this->request->query["fecha_inicio"];
        $hora_inicio = $this->request->query["hora_inicio"];
        $fecha_termino = $this->request->query["fecha_termino"];
        $hora_termino = $this->request->query["hora_termino"];
        
        try{
            //$o = $shell->generar_base_manual($faena_id, $fecha_ini, $hora_ini, $fecha_fin, $hora_fin);
            $cmd = 'cd /var/www/html/app/ && Console/cake reporte generar_base_manual '. $faena_id .' ' . $fecha_inicio .' ' . $hora_inicio .' ' . $fecha_termino .' ' . $hora_termino. ' -app /var/www/html/app/';
            $o = shell_exec($cmd);
            $o = str_replace('Welcome to CakePHP v2.5.6 Console', ' ', $o);
            $o = str_replace('---------------------------------------------------------------', ' ', $o);
            $o = str_replace('App : app', ' ', $o);
            $o = str_replace('Se entra a generar delta operacion', ' ', $o);
            $newOut = str_replace('Path: /var/www/html/app/', ' ', $o);
            $newOut = trim($newOut);
            $arreglo[] = json_decode($newOut, true);
            
            //if(count($newOut) > 1 ){
            //    print_r($newOut);
            //}else{
            //    print_r('NO PASO NADA');
            //}
            
            if(count($arreglo[0]) > 1 ){
                
                ob_start();
                header('Cache-Control: max-age=0');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $utilReporte = new UtilidadesReporteController();
                $util = new UtilidadesController();
                PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
                $objPHPExcel = new PHPExcel();
                header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header ('Content-Disposition: attachment;filename="Informe-conciliacion-'.$arreglo[0][0]['faena'].'.xlsx"');
                $objPHPExcel->
                getProperties()
                    ->setCreator("DBM")
                    ->setLastModifiedBy("DBM")
                    ->setTitle("Reporte Base");

                $objPHPExcel->setActiveSheetIndex(0)->setTitle('Reporte Base');
                $style_top = array(
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ),
                        'borders' => array(
                           'allborders' => array(
                               'style' => PHPExcel_Style_Border::BORDER_THIN, 
                               'color' => array('argb' => '000000'),)),
                     );
                $style = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN, 
                            'color' => array('argb' => '000000'),)),
                    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 11),
                );
                $border_style= array('borders' => array('allborders' => array('style' => 
                    PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
                
                $style_title = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ),
                    'font' => array('bold' => false, 'color' => array('rgb' => '000000'), 'size' => 26),
                );
                
                $style_centrado = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ),
                );
                
                $objPHPExcel->getActiveSheet()->setShowGridlines(false);
                $i = 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Periodo a Conciliar');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($style_top);
                $utilReporte->cellColor("A".$i.":C".$i,"ACAAAA","FFFFFF",$objPHPExcel);
                $i+= 1;
                
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Periodo');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($style_top);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Fecha');
                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($style_top);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Hora');
                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style_top);
                $utilReporte->cellColor("A".$i.":C".$i,"FF0000","FFFFFF",$objPHPExcel);
                
                
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                    //create object for Worksheet drawing
                $objDrawing->setName('Customer Signature' . 'dbm');        //set name to image
                $objDrawing->setDescription('Customer Signature'); //set description to image
                $signature = '../webroot/img/logo-dbm-default.png'; //'../webroot/img/' . basename($_FILES['img']['name'][$index]); //$img['tmp_name'];    //Path to signature .jpg file
                $objDrawing->setPath($signature);
                $objDrawing->setOffsetX(0);                       //setOffsetX works properly
                $objDrawing->setOffsetY(0);
                $objDrawing->setCoordinates('I' . $i);
                $objDrawing->setWidth(255);                 //set width, height
                $objDrawing->setHeight(75);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'INFORME DE CONCILIACIÓN CUMMINS');
                $objPHPExcel->getActiveSheet()->getStyle('J' . $i . ':R' . $i)->applyFromArray($style_title);
                $objPHPExcel->getActiveSheet()->mergeCells('J' . $i . ':R' . ($i + 2));
                
                $objDrawing2 = new PHPExcel_Worksheet_Drawing();
                $objDrawing2->setName('Customer Signature' . 'cummins');        //set name to image
                $objDrawing2->setDescription('Customer Signature'); //set description to image
                $signature2 = '../webroot/images/logo.png'; //'../webroot/img/' . basename($_FILES['img']['name'][$index]); //$img['tmp_name'];    //Path to signature .jpg file
                $objDrawing2->setPath($signature2);
                $objDrawing2->setOffsetX(120);                       //setOffsetX works properly
                $objDrawing2->setOffsetY(0);
                $objDrawing2->setCoordinates('Y' . $i);
                $objDrawing2->setWidth(108);                 //set width, height
                $objDrawing2->setHeight(75);
                $objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
                
                
                $i+= 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'inicio');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $fecha_inicio);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($style_top);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $hora_inicio);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style_top);
                $i+= 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Término');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $fecha_termino);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($style_top);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $hora_termino);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style_top);
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:C'.$i)->applyFromArray($border_style);
                $i+= 2;
                
                //ENCABEZADOS
                $objPHPExcel->getActiveSheet()->setCellValue("A".$i, "Faena");
                $objPHPExcel->getActiveSheet()->mergeCells("A".$i.":A".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("B".$i, "Flota");
                $objPHPExcel->getActiveSheet()->mergeCells("B".$i.":B".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("C".$i, "Unidad");
                $objPHPExcel->getActiveSheet()->mergeCells("C".$i.":C".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("D".$i, "Correlativo");
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
                $objPHPExcel->getActiveSheet()->mergeCells("D".$i.":D".($i + 1));
                //$objPHPExcel->getActiveSheet()->setCellValue("E".$i, "Folio");
                //$objPHPExcel->getActiveSheet()->mergeCells("E".$i.":E".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("E".$i, "Tipo");
                $objPHPExcel->getActiveSheet()->mergeCells("E".$i.":E".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("F".$i, "Turno");
                $objPHPExcel->getActiveSheet()->mergeCells("F".$i.":F".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("G".$i, "Periodo");
                $objPHPExcel->getActiveSheet()->mergeCells("G".$i.":G".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("H".$i, "Lugar");
                $objPHPExcel->getActiveSheet()->mergeCells("H".$i.":H".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("I".$i, "Sintoma");
                $objPHPExcel->getActiveSheet()->mergeCells("I".$i.":I".($i + 1));
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
                $objPHPExcel->getActiveSheet()->setCellValue("J".$i, "Finalizado");
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $objPHPExcel->getActiveSheet()->mergeCells("J".$i.":J".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("K".$i, "Estado");
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
                $objPHPExcel->getActiveSheet()->mergeCells("K".$i.":K".($i + 1));
                
                $objPHPExcel->getActiveSheet()->setCellValue("L".$i, "Fecha inicio");
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
                $objPHPExcel->getActiveSheet()->mergeCells("L".$i.":L".($i + 1));
                
                $objPHPExcel->getActiveSheet()->setCellValue("M".$i, "Hora inicio");
                $objPHPExcel->getActiveSheet()->mergeCells("M".$i.":M".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("N".$i, "Fecha término");
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
                $objPHPExcel->getActiveSheet()->mergeCells("N".$i.":N".($i + 1));
                $objPHPExcel->getActiveSheet()->setCellValue("O".$i, "Hora término");
                $objPHPExcel->getActiveSheet()->mergeCells("O".$i.":O".($i + 1));
                
                $utilReporte->cellColor("A".$i.":O".$i,"FF0000","FFFFFF",$objPHPExcel);
                
                //$encabezados = array("Esn","Horometro Cabina","Horometro Motor","Actividad","Responsable","Categoría","Motivo Llamado","Categoría Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa", "Cambio Módulo","Tecnico Nº1","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Tecnico N°7","Tecnico N°8","Tecnico N°9","Tecnico N°10","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Estado Intervención","Comentario Técnico", "OS SAP", "Codigo KCH", "PN Entrante", "PN Saliente");
                $objPHPExcel->getActiveSheet()->setCellValue('p'.$i, 'Tiempo Periodo');
                $objPHPExcel->getActiveSheet()->mergeCells('p' . $i . ':S' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('p' . $i . ':S' . $i)->applyFromArray($style);
                $utilReporte->cellColor("P".$i.":S".$i,"ACAAAA","FFFFFF",$objPHPExcel);
                
                $objPHPExcel->getActiveSheet()->setCellValue("P".($i +1), "DCC");
                $objPHPExcel->getActiveSheet()->setCellValue("Q".($i +1), "OEM");
                $objPHPExcel->getActiveSheet()->setCellValue("R".($i +1), "MINA");
                $objPHPExcel->getActiveSheet()->setCellValue("S".($i +1), "Total");
                
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Tiempo total evento');
                $objPHPExcel->getActiveSheet()->mergeCells('T' . $i . ':W' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('T' . $i . ':W' . $i)->applyFromArray($style);
                $utilReporte->cellColor("T".$i.":W".$i,"000000","FFFFFF",$objPHPExcel);
                
                $objPHPExcel->getActiveSheet()->setCellValue("T".($i +1), "DCC");
                $objPHPExcel->getActiveSheet()->setCellValue("U".($i +1), "OEM");
                $objPHPExcel->getActiveSheet()->setCellValue("V".($i +1), "MINA");
                $objPHPExcel->getActiveSheet()->setCellValue("W".($i +1), "Total");
                
                $utilReporte->cellColor("P".($i + 1).":W".($i + 1),"FF0000","FFFFFF",$objPHPExcel);
                
                $objPHPExcel->getActiveSheet()->setCellValue("X".$i, "Comentarios Contratiempos");
                $objPHPExcel->getActiveSheet()->mergeCells("X".$i.":X".($i + 1));
                $utilReporte->cellColor("X".$i,"FF0000","FFFFFF",$objPHPExcel);
                $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
                
                $objPHPExcel->getActiveSheet()->setCellValue("Y".$i, "Comentario Intervención");
                $objPHPExcel->getActiveSheet()->mergeCells("Y".$i.":Y".($i + 1));
                $utilReporte->cellColor("Y".$i,"FF0000","FFFFFF",$objPHPExcel);
                $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(30);
                               
                $i += 1;
                
                $objPHPExcel->getActiveSheet()->getStyle('A6:Y'.$i)->applyFromArray($style);
                $objPHPExcel->getActiveSheet()->getStyle('A6:Y'.$i)->getAlignment()->setWrapText(true); 
                                
                
                $i += 1;
                // Carga de Datos desde Array
                $dataArray = array();
                $reporte = array();
                $comen = '';
                $observaciones = '';
                $finalizado = false;
                $total = 0; $tot_dcc = 0; $tot_oem = 0; $tot_mina = 0;
                $tot = 0; $dcc = 0; $oem = 0; $mina = 0;
                $f_i = ""; $f_t = ""; $h_i = ""; $h_t = "";
                $corr = array();
                foreach($arreglo[0] as $interv) {
                    
                    $dataArray[$interv['correlativo']]['faena'] = $interv['faena'];
                    $dataArray[$interv['correlativo']]['flota'] = $interv['flota'];
                    $dataArray[$interv['correlativo']]['Unidad'] = $interv['unidad'];
                    $dataArray[$interv['correlativo']]['correlativo'] = $interv['correlativo'];
                    $dataArray[$interv['correlativo']]['tipo'] = $interv['tipo'];
                    $dataArray[$interv['correlativo']]['turno'] = $interv['turno'];
                    $dataArray[$interv['correlativo']]['periodo'] = $interv['periodo'];
                    $dataArray[$interv['correlativo']]['lugar_reparacion'] = $interv['lugar_reparacion'];
                    $dataArray[$interv['correlativo']]['sintoma'] = $interv['sintoma'];
                    
                    //INTERVENCION TERMINADA Y BITACORA TERMINADA
                    if($interv['evento_finalizado'] == 'SI'){
                        if($interv['intervencion_terminada_bitacora'] == 'SI'){
                            $dataArray[$interv['correlativo']]['evento_finalizado'] = 'SI';
                            $finalizado = true;
                            
                            if(count($corr[$interv['correlativo']]) > 0){
                                foreach($corr[$interv['correlativo']] as $folio){
                                    $dataArray[$folio]['evento_finalizado'] = 'SI'; 
                                }
                            }
                            
                        }else{
                            $dataArray[$interv['correlativo']]['evento_finalizado'] = 'NO';
                            $finalizado = false;
                            $corr[$interv['correlativo']] = $interv['correlativo'];
                        }
                    }else{
                        $dataArray[$interv['correlativo']]['evento_finalizado'] = 'NO';
                        $finalizado = false;
                    }
                    
                    $dataArray[$interv['correlativo']]['estado'] = $interv['estado'];

                    if($interv["categoria"] == "Inicial"){
                        $f_i = $interv['fecha_inicio'];
                        $h_i = $interv['hora_inicio'];
                        
                        $tot_dcc = 0; 
                        $tot_oem = 0; 
                        $tot_mina = 0;
                        $tot = 0;
                        
                        $dcc = 0; 
                        $oem = 0; 
                        $mina = 0;
                        $total = 0;
                    }
                    if($interv["categoria"] == "Continuación"){
                         if($interv["fecha_inicio"].'T'.$interv["hora_inicio"] >= $fecha_inicio.'T'.$hora_inicio
                                && $interv["fecha_inicio"].'T'.$interv["hora_inicio"] <= $fecha_termino.'T'.$hora_termino
                                ){
                            $f_i = $interv['fecha_inicio'];
                            $h_i = $interv['hora_inicio'];

                            $dcc = 0; 
                            $oem = 0; 
                            $mina = 0;
                            $tot = 0;
                        }
                    }
                    
                    
                    
                    
                    //PERIODO
                    if($interv["fecha_inicio"].'T'.$interv["hora_inicio"] >= $fecha_inicio.'T'.$hora_inicio 
                            && $interv["fecha_termino"].'T'.$interv["hora_termino"] <= $fecha_termino.'T'.$hora_termino
                            ){
                    
                        if($interv["categoria"] != "Inicial" && $interv["categoria"] != "Continuación"){
                            if($interv["responsable"] == 'DCC'){
                                $tot_dcc += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                                $dcc += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_dcc += 0;
                                $dcc += 0;
                            }

                            if($interv["responsable"] == 'OEM'){
                                $tot_oem += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                                $oem += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_oem += 0;
                                $oem += 0;
                            }

                            if($interv["responsable"] == 'MINA'){
                                $tot_mina += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                                $mina += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_mina += 0;
                                $mina += 0;
                            }
                            if($interv["responsable"] == 'DCC' || $interv["responsable"] == 'MINA' || $interv["responsable"] == 'OEM'){
                                $tot +=  $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                                $total +=  $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot += 0;
                                $total += 0;
                            }
                        }else{
                            $f_t = $interv['fecha_termino'];
                            $h_t = $interv['hora_termino'];
                            $observaciones = '';
                        
                        }
                        
                        $dataArray[$interv['correlativo']]['fecha_inicio'] = $f_i;
                        $dataArray[$interv['correlativo']]['hora_inicio'] = $h_i;
                        $dataArray[$interv['correlativo']]['fecha_termino'] = $f_t;
                        $dataArray[$interv['correlativo']]['hora_termino'] = $h_t;
                        
                        //periodo4
                        $dataArray[$interv['correlativo']]['dcc'] = $dcc;
                        $dataArray[$interv['correlativo']]['oem'] = $oem;
                        $dataArray[$interv['correlativo']]['mina'] = $mina;
                        $dataArray[$interv['correlativo']]['total'] = $tot;

                        //correlativo
                        $dataArray[$interv['correlativo']]['dcct'] = $tot_dcc;
                        $dataArray[$interv['correlativo']]['oemt'] = $tot_oem;
                        $dataArray[$interv['correlativo']]['minat'] = $tot_mina;
                        $dataArray[$interv['correlativo']]['totalt'] = $total;
                        
                    }else{
                        //FUERA PERIODO
                        
                        if($interv["categoria"] == "Continuación"){
                            if($interv["fecha_inicio"].'T'.$interv["hora_inicio"] <= $dataArray[$interv['correlativo']]['fecha_inicio'].'T'.$dataArray[$interv['correlativo']]['hora_inicio'] 
                                    && $interv["fecha_inicio"].'T'.$interv["hora_inicio"] >= $fecha_inicio.'T'.$hora_inicio
                                    ){
                                $f_i = $interv['fecha_inicio'];
                                $h_i = $interv['hora_inicio'];

                                $tot_dcc = 0; 
                                $tot_oem = 0; 
                                $tot_mina = 0;
                                $tot = 0;

                            }
                        }
                        
                        $dataArray[$interv['correlativo']]['fecha_inicio'] = $f_i;
                        $dataArray[$interv['correlativo']]['hora_inicio'] = $h_i;
                        $dataArray[$interv['correlativo']]['fecha_termino'] = $f_t;
                        $dataArray[$interv['correlativo']]['hora_termino'] = $h_t;
                        
                        //periodo
                        $dataArray[$interv['correlativo']]['dcc'] = $dcc;
                        $dataArray[$interv['correlativo']]['oem'] = $oem;
                        $dataArray[$interv['correlativo']]['mina'] = $mina;
                        $dataArray[$interv['correlativo']]['total'] = $tot;
                        
                        if($interv["categoria"] != "Inicial" && $interv["categoria"] != "Continuación"){
                            if($interv["responsable"] == 'DCC'){
                                $tot_dcc += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_dcc += 0;
                            }

                            if($interv["responsable"] == 'OEM'){
                                $tot_oem += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_oem += 0;
                            }

                            if($interv["responsable"] == 'MINA'){
                                $tot_mina += $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $tot_mina += 0;
                            }
                            if($interv["responsable"] == 'DCC' || $interv["responsable"] == 'MINA' || $interv["responsable"] == 'OEM'){
                                $total +=  $interv['tiempo'] == '' ? 0 : $interv['tiempo'];
                            }else{
                                $total += 0;
                            }
                        }
                        
                        //correlativo
                        $dataArray[$interv['correlativo']]['dcct'] = $tot_dcc;
                        $dataArray[$interv['correlativo']]['oemt'] = $tot_oem;
                        $dataArray[$interv['correlativo']]['minat'] = $tot_mina;
                        $dataArray[$interv['correlativo']]['totalt'] = $total;
                    }
                    
                    

                    if($interv["categoria"] == "Tiempo"){
                        $interv["comentario_tecnico"] = str_replace(";", ":", $interv["comentario_tecnico"]);
                        $interv["comentario_tecnico"] = str_replace("\t", "", $interv["comentario_tecnico"]);
                        $interv["comentario_tecnico"] = str_replace("\r\n", "", $interv["comentario_tecnico"]);
                        $interv["comentario_tecnico"] = str_replace("\n", "", $interv["comentario_tecnico"]);
                        $interv["comentario_tecnico"] = str_replace("\r", "", $interv["comentario_tecnico"]);
                        $interv["comentario_tecnico"] = str_replace('"', '', $interv["comentario_tecnico"]);

                        if(trim($comen) != trim($interv["comentario_tecnico"])){
                            $comen = $interv["comentario_tecnico"];
                            $observaciones .= $interv["comentario_tecnico"]. '; ';
                        }   
                        if($interv["cambio_modulo_bitacora"] == 'SI'){
                            $observaciones .= 'Cambio de módulo' . "\n";
                        }
                    }
                    $dataArray[$interv['correlativo']]['comentario_tecnico'] = strtoupper($observaciones);
                    
                    
                    if($interv["categoria"] == "Inicial" || $interv["categoria"] == "Continuación"){
                        $comentario_inter = $interv["comentario_tecnico"]; //$util->getComentario($interv['folio_unico']);
                        $comentario_inter = str_replace(";", ":", $comentario_inter);
                        $comentario_inter = str_replace("\t", "", $comentario_inter);
                        $comentario_inter = str_replace("\r\n", "", $comentario_inter);
                        $comentario_inter = str_replace("\n", "", $comentario_inter);
                        $comentario_inter = str_replace("\r", "", $comentario_inter);
                        $comentario_inter = str_replace('"', '', $comentario_inter);
                       
                    }
                    
                    $dataArray[$interv['correlativo']]['comentario'] = strtoupper($comentario_inter);
                    
                    $reporte = $dataArray;    
                    
                    
                    if($finalizado == false){
                        $dataArray[$interv['correlativo']]['fecha_termino'] = $fecha_termino;
                        $dataArray[$interv['correlativo']]['hora_termino'] = $hora_termino;

                        $f_inicio = strtotime($f_t.' '.$h_t);
                        $f_termino = strtotime($fecha_termino.' '.$hora_termino);
                        $t_trabajo = (($f_termino - $f_inicio) / 3600);

                        $dataArray[$interv['correlativo']]['dcc'] += $t_trabajo; //$tot_dcc + $t_trabajo;
                        $dataArray[$interv['correlativo']]['oem'] += 0;
                        $dataArray[$interv['correlativo']]['mina'] += 0;

                        $dataArray[$interv['correlativo']]['total'] += $t_trabajo;

                        //correlativo
                        $dataArray[$interv['correlativo']]['dcct'] += $t_trabajo;
                        $dataArray[$interv['correlativo']]['oemt'] += 0 ;
                        $dataArray[$interv['correlativo']]['minat'] += 0;
                        $dataArray[$interv['correlativo']]['totalt'] += $t_trabajo;

                        $dataArray[$interv['correlativo']]['comentario_tecnico'] .= 'Tiempo a término de turno ('.$t_trabajo.' hrs.).';

                        //$reporte = $dataArray[$interv['correlativo'].'T'];
                    }                    
                }
                
                $total = count($reporte) + 7;
                $objPHPExcel->getActiveSheet()->getStyle('A8:Y'.$total)->applyFromArray($style_top);
                $objPHPExcel->getActiveSheet()->getStyle('S8:S'.$total)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('W8:W'.$total)->getFont()->setBold(true);
                
                $style_left = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                    ),
                );
                
                $objPHPExcel->getActiveSheet()->getStyle('X8:Y'.$total)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('X8:Y'.$total)->applyFromArray($style_left);
                
                $objPHPExcel->getActiveSheet()->fromArray($reporte, NULL, 'A'.$i);
                
                
                $a = $total + 4;
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, '_______________________________________');
                $objPHPExcel->getActiveSheet()->mergeCells('C' . $a . ':F' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $a . ':F' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, '_______________________________________');
                $objPHPExcel->getActiveSheet()->mergeCells('J' . $a . ':M' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('J' . $a . ':M' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$a, '_______________________________________');
                $objPHPExcel->getActiveSheet()->mergeCells('R' . $a . ':U' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('R' . $a . ':U' . $a)->applyFromArray($style_centrado);
                
                $a += 1;
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, 'Nombre y firma');
                $objPHPExcel->getActiveSheet()->mergeCells('C' . $a . ':F' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $a . ':F' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, 'Nombre y firma');
                $objPHPExcel->getActiveSheet()->mergeCells('J' . $a . ':M' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('J' . $a . ':M' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$a, 'Nombre y firma');
                $objPHPExcel->getActiveSheet()->mergeCells('R' . $a . ':U' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('R' . $a . ':U' . $a)->applyFromArray($style_centrado);
                
                $a += 1;
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, 'Cummins');
                $objPHPExcel->getActiveSheet()->mergeCells('C' . $a . ':F' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $a . ':F' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, 'OEM');
                $objPHPExcel->getActiveSheet()->mergeCells('J' . $a . ':M' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('J' . $a . ':M' . $a)->applyFromArray($style_centrado);
                
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$a, 'Mina');
                $objPHPExcel->getActiveSheet()->mergeCells('R' . $a . ':U' . $a);
                $objPHPExcel->getActiveSheet()->getStyle('R' . $a . ':U' . $a)->applyFromArray($style_centrado);
                
                
                
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
                ob_end_clean();
                $objWriter->save('php://output');
                
                $this->redirect("/InformeConciliacion/Index?faena_id=$faena_id&fecha_inicio=$fecha_inicio&hora_inicio=$hora_inicio&fecha_termino=$fecha_termino&hora_termino=$hora_termino");
                
            }else{
                $this->Session->setFlash('No existen trabajos para este día.', 'guardar_error');
                $this->redirect("/InformeConciliacion/Index?faena_id=$faena_id&fecha_inicio=$fecha_inicio&hora_inicio=$hora_inicio&fecha_termino=$fecha_termino&hora_termino=$hora_termino");
            }
        } catch (Exception $e) {
            $this->logger($this, $e->getMessage());
            print_r($e->getMessage());
            $this->redirect("/InformeConciliacion/Index?faena_id=$faena_id&fecha_inicio=$fecha_inicio&hora_inicio=$hora_inicio&fecha_termino=$fecha_termino&hora_termino=$hora_termino");
        }
        
        exit;
        
    }
}
