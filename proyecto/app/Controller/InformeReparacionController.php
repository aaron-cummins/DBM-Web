<?php

App::uses('ConnectionManager', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'AppController');

/**
 * Description of InformeReparacionController
 *
 * @author AZUNIGA
 */
class InformeReparacionController extends AppController {

    public $components = array('AWSSES', 'Session');

    public function informe() {
        $this->layout = 'metronic_principal';
        $this->set("titulo", "Informe Reparación");
        $this->loadModel('InformeReparacion');
        $this->loadModel('Faena');
        $this->loadModel('FaenaFlota');
        $this->loadModel('Unidad');
        $this->loadModel('ReporteBase');
        $fecha_inicio = date('Y-m-d', time() - 365 * 24 * 60 * 60);
        $fecha_termino = date("Y-m-d");
        $flota_id = "";
        $unidad_id = "";
        $faena_id = '';
        $correlativo = '';
        $tipointervencion = '';
        $limit = "";
        $reporte = array();

        try {
            if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
                $fecha_inicio = $this->request->query["fecha_inicio"];
                $fecha_termino = $this->request->query["fecha_termino"];
                $conditions = array();

                if (@$this->request->query["correlativo"] != "") {
                    $correlativo = $this->request->query["correlativo"];
                    $conditions["ReporteBase.correlativo"] = $correlativo;
                }

                if (@$this->request->query["tipointervencion"] != "") {
                    $tipointervencion = $this->request->query["tipointervencion"];
                    $conditions["ReporteBase.tipo"] = $tipointervencion;
                }

                if (@$this->request->query["faena_id"] != "") {
                    $faena_id = $this->request->query["faena_id"];
                    $conditions["ReporteBase.faena_id"] = $faena_id;
                }

                if (@$this->request->query["flota_id"] != "0" && @$this->request->query["flota_id"] != "") {
                    $flota_id = explode("_", $this->request->query['flota_id']);
                    $flota_id = $flota_id[1];
                    $conditions["ReporteBase.flota_id"] = $flota_id;
                }
                if (@$this->request->query["unidad_id"] != "0" && @$this->request->query["unidad_id"] != "") {
                    $unidad_id = explode("_", $this->request->query['unidad_id']);
                    $unidad_id = $unidad_id[2];
                    $conditions["ReporteBase.unidad_id"] = $unidad_id;
                }

                if (@$this->request->query["limit"] != "") {
                    $limit = $this->request->query["limit"];
                } else {
                    $limit = 10;
                }
                
                
                $conditions["ReporteBase.categoria"] = array('Inicial');
                $conditions["ReporteBase.fecha_inicio BETWEEN ? AND ? "] = array($fecha_inicio, $fecha_termino);
               

                //$reporte = $this->ReporteBase->find('all',
                $this->paginate =
                        array(
                            'fields' => array("ReporteBase.horometro_cabina", "ReporteBase.horometro_motor", "ReporteBase.tipo", "ReporteBase.actividad", "ReporteBase.responsable", "ReporteBase.correlativo", "ReporteBase.folio", "ReporteBase.faena", "ReporteBase.flota", "ReporteBase.unidad", "esn",
                                "ReporteBase.categoria", "ReporteBase.fecha_inicio", "ReporteBase.hora_inicio", "ReporteBase.fecha_termino", "ReporteBase.hora_termino",
                                "ReporteBase.tiempo", "ReporteBase.motivo_llamado", "ReporteBase.categoria_sintoma", "ReporteBase.sintoma", "ReporteBase.sistema", "ReporteBase.subsistema",
                                "ReporteBase.pos_subsistema", "ReporteBase.id_code", "ReporteBase.elemento", "ReporteBase.pos_elemento", "ReporteBase.diagnostico", "ReporteBase.solucion",
                                "ReporteBase.causa", "ReporteBase.cambio_modulo", "ReporteBase.evento_finalizado", "ReporteBase.lugar_reparacion", "ReporteBase.tecnico_1",
                                "ReporteBase.tecnico_2", "ReporteBase.tecnico_3", "ReporteBase.tecnico_4", "ReporteBase.tecnico_5", "ReporteBase.tecnico_6", "ReporteBase.tecnico_7",
                                "ReporteBase.tecnico_8", "ReporteBase.tecnico_9", "ReporteBase.tecnico_10", "ReporteBase.supervisor_responsable", "ReporteBase.turno",
                                "ReporteBase.periodo", "ReporteBase.supervisor_aprobador", "ReporteBase.status_kch", "ReporteBase.comentario_tecnico",
                                "ReporteBase.numero_os_sap"),
                            'conditions' => $conditions,
                            'order' => array('ReporteBase.faena' => "asc", 'ReporteBase.correlativo' => 'asc', 'ReporteBase.folio' => 'asc', 'ReporteBase.fecha_inicio' => 'asc', 'ReporteBase.hora_inicio' => 'asc', 'ReporteBase.id' => 'asc'),
                            'limit' => $limit,
                            'Recursive' => -1
                );
                $reporte = $this->paginate('ReporteBase');
                
                //die;
            } else {
                $this->set("intervenciones", array());
                $this->set("duracion_filtro", '');
            }
        } catch (Exception $e) {
            $this->logger($this, $e->getMessage());
            print_r($e->getMessage());
        }
        
        
        $this->set("intervenciones", $reporte);
        
        $this->set("faena_id", $faena_id);
        $this->set(compact("duracion"));
        $this->set(compact("limit"));
        $this->set("flota_id", $flota_id);
        $this->set("unidad_id", $unidad_id);
        $this->set("fecha_inicio", $fecha_inicio);
        $this->set("fecha_termino", $fecha_termino);
        $this->set("correlativo", $correlativo);
        $this->set("tipointervencion", $tipointervencion);

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));
    }

    public function descargar($correlativo) {

        try {

            $this->autoRender = false;
            $this->loadModel('ReporteBase');

            $observaciones = '';
            $fallas = array();
            $cambiado_nuevo = array();
            $imagenes = array();
            $pn_salientes = '';

            //sube temporalemente las imagens
            if (!empty($_FILES)) {
                foreach ($_FILES['img']['tmp_name'] as $index => $tmpName) {
                    if (!empty($tmpName) && is_uploaded_file($tmpName)) {
                        $tipo = $_FILES['img']['type'][$index];
                        if((strpos($tipo, "gif") || strpos($tipo, "jpeg") || 
                            strpos($tipo, "jpg") || strpos($tipo, "png"))){
                        //if(($tipo == "image/gif") || ($tipo == "image/jpeg") || 
                        //    ($tipo == "image/jpg") || ($tipo == "image/png")){
                            
                            if (!file_exists('../webroot/img/ir/')){
                                mkdir("../webroot/img/ir/", 0777, true);
                            }
                            
                            move_uploaded_file($_FILES['img']['tmp_name'][$index], '../webroot/img/ir/' . basename($_FILES['img']['name'][$index])); // move to new location perhaps?
                            $imagenes[] = basename($_FILES['img']['name'][$index]);
                        }else{
                            foreach($imagenes as $img){
                                unlink('../webroot/img/ir/'.$img);
                            }
                            
                            $this->Session->setFlash('Tipo de archivo no permitido, solo se deben adjuntar imagenes.', 'guardar_error');
                            $this->redirect("/InformeReparacion/informe");
                        }
                        
                    }
                }
            }

            /*$intervenciones = $this->ReporteBase->query("select *,
                (select fecha_ps from estado_motor em
                Where em.faena_id = reporte_base.faena_id and em.flota_id = reporte_base.flota_id and em.unidad_id = reporte_base.unidad_id and em.estado_motor_id = 2
                order by fecha_ps desc limit 1) fecha_ps,
                CONCAT(u1.nombres, ' ', u1.apellidos) usuario_lider, CONCAT(u2.nombres, ' ', u2.apellidos) aprueba
                from  reporte_base 
                inner join unidad on unidad.unidad = reporte_base.unidad and unidad.e = '1' and reporte_base.faena_id = unidad.faena_id and reporte_base.flota_id = unidad.flota_id
                inner join usuario u1 on u1.usuario = TO_NUMBER(reporte_base.tecnico_1, '999999999')
                inner join usuario u2 on u2.usuario = TO_NUMBER(reporte_base.supervisor_aprobador, '999999999')
                Where correlativo = $correlativo
                order by reporte_base.faena asc, reporte_base.correlativo asc, reporte_base.folio asc, 
                reporte_base.fecha_inicio asc, reporte_base.hora_inicio asc, reporte_base.id asc");*/
            
            $intervenciones = $this->ReporteBase->query("select reporte_base.*, CONCAT(m.nombre, ' ',tipo_emision.nombre)  motor, flota.oem,
                unidad.modelo_equipo, unidad.nserie,
                (select fecha_ps from estado_motor em
                Where em.faena_id = reporte_base.faena_id and em.flota_id = reporte_base.flota_id and em.unidad_id = reporte_base.unidad_id and em.estado_motor_id = 2
                order by fecha_ps desc limit 1) fecha_ps,
                CONCAT(u1.nombres, ' ', u1.apellidos) usuario_lider, CONCAT(u2.nombres, ' ', u2.apellidos) aprueba
                from  reporte_base 
                inner join unidad on unidad.unidad = reporte_base.unidad and unidad.e = '1' and reporte_base.faena_id = unidad.faena_id and reporte_base.flota_id = unidad.flota_id
                inner join Motor m on m.id = unidad.motor_id
                inner join tipo_emision on tipo_emision.id = m.tipo_emision_id
                inner join flota on flota.id = unidad.flota_id
                inner join usuario u1 on u1.usuario = TO_NUMBER(reporte_base.tecnico_1, '999999999')
                inner join usuario u2 on u2.usuario = TO_NUMBER(reporte_base.supervisor_aprobador, '999999999')
                Where correlativo = $correlativo
                order by reporte_base.faena asc, reporte_base.correlativo asc, reporte_base.folio asc, 
                reporte_base.fecha_inicio asc, reporte_base.hora_inicio asc, reporte_base.id asc");

            $comen = '';
            foreach ($intervenciones as $interv) {
                if ($interv[0]["causa"] == "Falla") {
                    $fallas[] = $interv[0];
                    $pn_salientes .= $interv[0]["pn_saliente"] . "-";
                }
                if ($interv[0]["solucion"] == "Cambiado_Nuevo") {
                    $cambiado_nuevo[] = $interv[0];
                }
                
                
                if($interv[0]["categoria"] == "Intervención"){
                    $interv[0]["comentario_tecnico"] = str_replace(";", ":", $interv[0]["comentario_tecnico"]);
                    $interv[0]["comentario_tecnico"] = str_replace("\t", "", $interv[0]["comentario_tecnico"]);
                    $interv[0]["comentario_tecnico"] = str_replace("\r\n", "", $interv[0]["comentario_tecnico"]);
                    $interv[0]["comentario_tecnico"] = str_replace("\n", "", $interv[0]["comentario_tecnico"]);
                    $interv[0]["comentario_tecnico"] = str_replace("\r", "", $interv[0]["comentario_tecnico"]);
                    $interv[0]["comentario_tecnico"] = str_replace('"', '', $interv[0]["comentario_tecnico"]);
                    
                    if(trim($comen) != trim($interv[0]["comentario_tecnico"])){
                        $comen = $interv[0]["comentario_tecnico"];
                        $observaciones .= $interv[0]["comentario_tecnico"] . "\n";
                    }                    
                }
            }


            if (!empty($fallas)) {


                //print_r($intervenciones);

                header('Cache-Control: max-age=0');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0




                $utilReporte = new UtilidadesReporteController();
                $util = new UtilidadesController();
                PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
                $objPHPExcel = new PHPExcel();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="IR_' . $interv[0]["unidad"] . '_' . $interv[0]["esn"] . '_'.$fallas[0]['elemento'].'_'.date('d-m-Y').'.xlsx"');
                $objPHPExcel->
                        getProperties()
                        ->setCreator("DBM")
                        ->setLastModifiedBy("DBM")
                        ->setTitle("Informe Reparación");

                $objPHPExcel->setActiveSheetIndex(0)->setTitle('Informe Reparación');


                $style_center_black = array(
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '000000')),
                    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 16)
                );

                $style_center_red = array(
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF0000')),
                    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 14)
                );

                $style_data = array(
                    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 10)
                );

                $style_data_campo = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'font' => array('bold' => false, 'color' => array('rgb' => '000000'), 'size' => 10)
                );

                $style_data_red = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF0000')),
                    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 10)
                );

                $style_data_red_left = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
                    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF0000')),
                    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'), 'size' => 10)
                );

                //Filas
                $i = 1;
                setlocale(LC_TIME, "spanish");
                
                //Titulo
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'INFORME DE REPARACIÓN FAENA ' . strtoupper($intervenciones[0][0]["faena"]));
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                #$utilReporte->cellColor('A'.$i.':G'.$i, "000000", "FFFFFF", $objPHPExcel);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_center_black);
                $i = $i + 1;

                //Subtitulo
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'Distribuidora Cummins Chile S.A.');
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                #$utilReporte->cellColor('A'.$i.':G'.$i, "FF0000", "FFFFFF", $objPHPExcel);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_center_red);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $i = $i + 1;

                //Fechas, corelativos
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'Os. SAP');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_red);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["numero_os_sap"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'Fecha Emisión');
                $objPHPExcel->getActiveSheet()->getStyle('C' . $i)->applyFromArray($style_data_red);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, date_format(date_create(date('y-m-d')), 'd M, Y'));
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Correlativo DBM');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray($style_data_red);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $intervenciones[0][0]["correlativo"]);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray($style_data);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $i = $i + 1;

                //Informacion General
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.-Informeación General');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.1-Faena/Sucursal');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["faena"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.8-N° Serie del Equipo');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $intervenciones[0][0]["nserie"]);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.2-Cliente');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["faena"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.9-Hrs. pieza fallada');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.3-N° Equipo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["unidad"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.10-Hrs. motor a la falla');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $intervenciones[0][0]["horometro_motor"]);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.4-ESN');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["esn"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.11-Número parte pieza fallada');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $pn_salientes);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.5-Modelo Motor');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["motor"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.12-Fecha puesta en servicio');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, date_format(date_create($intervenciones[0][0]["fecha_ps"]), 'd M, Y'));
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.6-Marca del equipo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["oem"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '113-Fecha falla');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, date_format(date_create($intervenciones[0][0]["fecha_inicio"] . "T" . $intervenciones[0][0]["hora_inicio"]), 'd M, Y H:i:s'));
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '1.7-Modelo del Equipo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $intervenciones[0][0]["modelo_equipo"]);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '1.14-Fecha término reparación');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, date_format(date_create($intervenciones[0][0]["fecha_termino"] . "T" . $intervenciones[0][0]["hora_termino"]), 'd M, Y H:i:s'));
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('F' . $i . ':G' . $i);
                $i = $i + 1;

                $d = 0;
                $diagnostico = "";
                $correcciones = "";
                foreach ($fallas as $falla) {
                    $d++;
                    $diagnostico .= $falla["subsistema"] . "_" . $falla["elemento"] . ($falla["pos_subsistema"] == "Unica" ? '' : "-" .$falla["pos_subsistema"]) . "_" . $falla["diagnostico"] . "\n";
                    $correcciones .= $falla["subsistema"] . "_" . $falla["elemento"] . ($falla["pos_subsistema"] == "Unica" ? '' : "-" .$falla["pos_subsistema"]) . "_" . $falla["solucion"]. "\n";
                }

                //Detalles de trabajo
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.-Detalles del Trabajo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.1-Síntoma de la falla');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $intervenciones[0][0]["sintoma"]);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.2-Descripción del diagnóstico y daños encontrados');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $diagnostico);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $d))->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $d))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . ($i + $d));
                $i = $i + ($d + 1);
                
                /*$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1; */
                
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.3-Descripción de cauza raíz de falla');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'En Investigación');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.4-Correcciones realizadas');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $correcciones);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $d))->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $d))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . ($i + $d));
                $i = $i+ ($d + 1);

                /*$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;*/
                
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '2.5-Otras Observaciones');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                //Resumen del trabajo
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '3.-Resumen del Trabajo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '3.1-Descripción del Trabajo');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                $c = strlen($observaciones);
                $c = intval($c / 100);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, strtoupper($observaciones));
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $c))->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + $c))->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . ($i + $c));
                $i = $i + ($c + 1);

                //Listado de repuestos utilizados
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '4.-Listado de repuestos utilizados en reparación');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '4.1-N° de parte');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '4.2-Cantidad');
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '4.3-Descripción');
                $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':G' . $i);
                $i = $i + 1;

                foreach ($cambiado_nuevo as $CN) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $CN["pn_entrante"]);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '1');
                    $objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray($style_data_campo);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $CN["elemento"]);
                    $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                    $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':G' . $i);
                    $i = $i + 1;
                }

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':G' . $i);
                $i = $i + 1;

                //Registro fotografico
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '5.-Registro Fotográfico');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                $cont = 1;

                foreach ($imagenes as $img) {

                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    //create object for Worksheet drawing
                    $objDrawing->setName('Customer Signature' . $cont);        //set name to image
                    $objDrawing->setDescription('Customer Signature'); //set description to image
                    $signature = '../webroot/img/ir/'.$img; //'../webroot/img/' . basename($_FILES['img']['name'][$index]); //$img['tmp_name'];    //Path to signature .jpg file
                    $objDrawing->setPath($signature);
                    $objDrawing->setOffsetX(10);                       //setOffsetX works properly
                    $objDrawing->setOffsetY(10);

                    if ($cont == 1) {
                        $objDrawing->setCoordinates('A' . $i);
                    } elseif ($cont == 2) {
                        $objDrawing->setCoordinates('E' . $i);        //set image to cell
                    } elseif ($cont == 3) {
                        $objDrawing->setCoordinates('A' . ($i + 14));        //set image to cell
                    } elseif ($cont = 4) {
                        $objDrawing->setCoordinates('E' . ($i + 14));
                    }
                    $objDrawing->setWidth(200);                 //set width, height
                    $objDrawing->setHeight(200);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save
                    $cont++;
                }
                
                if ($cont >= 4) {
                    //$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'IMAGEN');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + 28));
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . ($i + 28));

                    $i = $i + 29;
                } else {
                    //$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'IMAGEN');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . ($i + 14));
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . ($i + 14));

                    $i = $i + 15;
                }
                
               
                //Emisores
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '6.-Emisores');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($style_data_red_left);
                $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':G' . $i);
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '7.1-Autor');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, strtoupper($interv[0]["usuario_lider"]));
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':G' . $i);
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '7.2-Revisado');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, strtoupper($interv[0]["aprueba"]));
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':G' . $i);
                $i = $i + 1;

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '7.3-Aprobado');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':G' . $i)->applyFromArray($style_data_campo);
                $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':G' . $i);
                $i = $i + 1;

                //$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
                //$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                
                 //Elimina las imganes despues de usarlas
                foreach($imagenes as $img){
                    unlink('../webroot/img/ir/'.$img);
                }

                //$this->redirect("/InformeReparacion/informe?fecha_inicio=$fecha_inicio&fecha_termino=$fecha_termino&faena_id=$faena_id&correlativo=$correlativo&duracion=$duracion&flota_id=$flota_id&unidad_id=$unidad_id");
            } else {
                $this->Session->setFlash('No se puede generar el informe de reparaciones, ya que el correlativo no cuenta con elementos registrados como Falla, favor revisar la información del correlativo.', 'guardar_error');
                $this->redirect("/InformeReparacion/informe");
            }
        } catch (Exception $e) {
            $this->logger($this, $e->getMessage());
            print_r($e->getMessage());
        }
    }

}
