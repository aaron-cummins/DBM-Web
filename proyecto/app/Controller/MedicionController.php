<?php

/**
 * Description of MedicionController
 *
 * @author AZUNIGA
 */
App::uses('ConnectionManager', 'Model');
App::uses('File', 'Utility');
App::import('Controller', 'Utilidades');
App::uses('CakeEmail', 'Network/Email');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'UtilidadesReporte');
require_once '../../vendor/autoload.php';

use Aws\Ses;

class MedicionController extends AppController {

    //put your code here
    public $components = array('AWSSES');

    public function index() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');

        //$componentes = $this->MedicionComponente->find('all', array('fields'=>array('id', 'nombre'), 'conditions' => array('e' => '1'), 'order' => 'nombre', 'recursive' => -1));
        //$this->set('componentes', $componentes);
        $this->set('titulo', 'Tipo de Medición');
    }

    public function medicion_normal() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');

        $componentes = $this->MedicionComponente->find('all', array('fields' => array('id', 'nombre'), 'conditions' => array('e' => '1'), 'order' => 'nombre', 'recursive' => -1));
        $this->set('componentes', $componentes);
        $this->set('titulo', 'Medición por componente');
    }

    public function medicion_mantencion() {
        $this->layout = 'metronic_principal';
        $this->check_permissions($this);
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Unidad');
        $this->loadModel('FaenaFlota');

        $faena_id = $this->Session->read('faena_id');

        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id' => $faena_id, 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));

        $flotas = $this->FaenaFlota->find('all', array(
            'fields' => array("flota_id", "faena_id", "Flota.nombre", "FaenaFlota.motor_id"),
            'conditions' => array('FaenaFlota.faena_id' => $faena_id, 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        //'conditions' => array('FaenaFlota.faena_id' => $faena_id, 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1', 'FaenaFlota.motor_id IN' => array(1,3,4)), 'order' => array("Flota.nombre"), 'recursive' => 1));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $faena_id, 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"), 'recursive' => -1));


        $componentes = $this->MedicionComponente->find('all', array('fields' => array('id', 'nombre'), 'conditions' => array('e' => '1'), 'order' => 'nombre', 'recursive' => -1));

        if (isset($_GET['corr']) && $_GET['corr'] != "") {
            $query = "SELECT Medicion.*, estado_motor.esn_placa, Faena.nombre faena, Flota.nombre flota, Unidad.unidad, Medicion_Componente.nombre, 
                            Medicion_Componente.unidad_medida ,Medicion_Componente.medicion_inicial , 
                            (CASE WHEN tabla.fecha IS NULL THEN 'NO' ELSE 'SI' END) valido, 
                            (select m.medicion from medicion m Where m.ps = true and m.faena_id = Medicion.faena_id and m.flota_id = Medicion.flota_id
                             and m.unidad_id = Medicion.unidad_id and m.componente_id = Medicion.componente_id order by fecha DESC limit 1) as val_ps ,
                             concat(usuario.nombres, ' ', usuario.apellidos) usuario 
                            from Medicion 
                            left join (select unidad_id, max(fecha) fecha, fechastr 
                                        from(select *, to_char(fecha, 'YYYY-mm-dd') FechaStr, 
                                                     to_char(fecha,'HH24:MM:SS') HoraStr 
                                                     from medicion Where unidad_id = Medicion.unidad_id 
                                                     and componente_id = Medicion.componente_id and ps = 'false') as tabla 
                                        group by faena_id, flota_id, unidad_id, componente_id, fechastr, ps) as tabla on 
                            tabla.unidad_id = medicion.unidad_id and tabla.fecha = medicion.fecha 
                            INNER JOIN Faena ON Faena.id = Medicion.faena_id 
                            INNER JOIN Flota ON Flota.id = Medicion.flota_id 
                            INNER JOIN Unidad ON Unidad.id = Medicion.unidad_id 
                            INNER JOIN Medicion_Componente ON Medicion_Componente.id = Medicion.componente_id 
                            INNER JOIN usuario on usuario.id = medicion.usuario_id 
                            INNER JOIN estado_motor ON estado_motor.unidad_id = Medicion.unidad_id  
                            AND estado_motor.fecha_ps = (SELECT max(fecha_ps) 
							  FROM estado_motor WHERE unidad_id = Medicion.unidad_id AND fecha_ps <= Medicion.fecha) 
                            WHERE Medicion.faena_id = " . $faena_id . " and Medicion.flota_id = " . $_GET['flota'] . " and Medicion.unidad_id = " . $_GET['unidad'] . "
                            AND Medicion.correlativo_intervencion = " . $_GET['corr'];
            //print_r($query);
            $registros = $this->Medicion->query($query);
            $this->set('registros', $registros);
        }

        $this->set('componentes', $componentes);
        $this->set('flotas', $flotas);
        $this->set('faenas', $faenas);
        $this->set('unidades', $unidades);
        $this->set('faena_id', $faena_id);
        $this->set('titulo', 'Medición por mantención');
    }

    public function selectflota($id) {
        $this->set('titulo', 'Medición');
        $this->layout = 'metronic_principal';
        //$this->check_permissions($this);
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Unidad');
        $this->loadModel('FaenaFlota');

        $faena_id = $this->Session->read('faena_id');


        $flotas = $this->FaenaFlota->find('all', array(
            'fields' => array("flota_id", "faena_id", "Flota.nombre", "FaenaFlota.motor_id"),
            'conditions' => array('FaenaFlota.faena_id' => $faena_id, 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        //'conditions' => array('FaenaFlota.faena_id' => $faena_id, 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1', 'FaenaFlota.motor_id IN' => array(1,3,4)), 'order' => array("Flota.nombre"), 'recursive' => 1));

        $medicion_componente = $this->MedicionComponente->find('all', array(
            'fields' => array("nombre", "unidad_medida", "optimo_min", "optimo_max", "optimo_mensaje", "medio_min", "medio_max", "medio_mensaje", "alto_min", "alto_max", "alto_mensaje"),
            'conditions' => array("MedicionComponente.id" => $id)));

        $this->set('componente_str', $medicion_componente[0]['MedicionComponente']['nombre']);
        $this->set('flotas', $flotas);
        $this->set('componente', $id);
    }

    public function medir($flota, $idcomponente, $motor) {
        $this->set('titulo', 'Medición');
        //$this->check_permissions($this);
        $datagrafico = [];
        $posiciones = [];
        $motivo = [];
        $opciones = [];
        
        $fecha = date('Y-m-d');

        $this->layout = 'metronic_principal';
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');
        $this->loadModel('MedicionComponenteMotor');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Unidad');
        $this->loadModel('FaenaFlota');

        $faena_id = $this->Session->read('faena_id');

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $faena_id, 'Unidad.e' => '1', 'Unidad.flota_id' => $flota), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set('unidades', $unidades);
        $this->set('componente', $idcomponente);
        $this->set('flota', $flota);
        $this->set('grafico', $datagrafico);

        /* $medicion_componente = $this->MedicionComponente->find('all', array(
          'fields' =>array("nombre", "unidad_medida", "optimo_min", "optimo_max", "optimo_mensaje", "medio_min", "medio_max", "medio_mensaje",
          "alto_min", "alto_max", "alto_mensaje", "optimo_alerta", "medio_alerta", "alto_alerta",
          "medicion_inicial", "MedicionComponenteMotor.posiciones", "MedicionComponenteMotor.cambio", "MedicionComponenteMotor.motivo_cambio"),
          'conditions' => array("MedicionComponente.id" => $idcomponente, "MedicionComponenteMotor.motor_id" => $motor)
          )); */

        $medicion_componente1 = $this->MedicionComponente->find('all', array(
            'conditions' => array("MedicionComponente.id" => $idcomponente)
        ));
        $medicion_componente = $this->MedicionComponenteMotor->find('all', array(
            'conditions' => array("MedicionComponente.id" => $idcomponente, "MedicionComponenteMotor.motor_id" => $motor)
        ));
        $compara_medicion = $medicion_componente1[0]['MedicionComponente']['compara_medidas'];

        if (count($medicion_componente) <= 0 && $compara_medicion == true) {
            $html = '<div class="alert alert-danger">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                    . '<span aria-hidden="true">&times;</span></button>'
                    . '<strong><i class="fa fa-times"></i> Error!</strong>'
                    . ' No existen registro de componente para este motor, favor ponerse en contacto con soporte DBM. '
                    . '</div>';
            $this->set('alert', $html);
            $this->set('block', true);
            #$this->set('id_unidad', $medicion['unidad_id']);
            #$this->set('val_medicion', $medicion['medicion']);
            #$this->set('posicion_id', $medicion['posicion']);
            return false;
        }

        //POSICIONES
        $pns = explode(',', $medicion_componente[0]['MedicionComponenteMotor']['posiciones']);
        $count = 1;
        if (isset($pns) && count($pns) >= 1 && $pns[0] != "") {
            foreach ($pns as $posicion) {
                $posiciones[$count]['numero'] = $posicion;
                $posiciones[$count]['nombre'] = $posicion;
                $count++;
            }
        } else {
            $posiciones[$count]['numero'] = 'unica';
            $posiciones[$count]['nombre'] = 'unica';
        }
        //Opciones CAMBIO
        $opci = explode(',', $medicion_componente[0]['MedicionComponenteMotor']['opciones_cambio']);
        $c = 1;
        if (isset($opci) && count($opci) >= 1) {
            foreach ($opci as $opcion) {
                $opciones[$c]['numero'] = $opcion;
                $opciones[$c]['nombre'] = $opcion;
                $c++;
            }
        }

        //MOTIVOS CAMBIO
        $mtvo = explode(',', $medicion_componente[0]['MedicionComponenteMotor']['motivo_cambio']);
        $c = 1;
        if (isset($mtvo) && count($mtvo) >= 1) {
            foreach ($mtvo as $motivos) {
                $motivo[$c]['numero'] = $motivos;
                $motivo[$c]['nombre'] = $motivos;
                $c++;
            }
        }

        $cambio = $medicion_componente[0]['MedicionComponenteMotor']['cambio'];

        $this->set('fecha', $fecha);
        $this->set('cambios', $cambio);
        $this->set('compara_medicion', $compara_medicion);
        $this->set('ingresa_medicion', $medicion_componente1[0]['MedicionComponente']['ingresa_medicion']);
        $this->set('selecciona_fecha', $medicion_componente1[0]['MedicionComponente']['selecciona_fecha']);

        $this->set('posiciones', $posiciones);
        $this->set('opciones_cambio', $opciones);
        $this->set('motivos_cambios', $motivo);
        $this->set('uni_med', $medicion_componente1[0]['MedicionComponente']['unidad_medida']);
        $this->set('componente_str', $medicion_componente1[0]['MedicionComponente']['nombre']);
        $this->set('imagen', $medicion_componente1[0]['MedicionComponente']['imagen']);

        $this->set('medi_inicial', $medicion_componente[0]['MedicionComponente']['medicion_inicial']);

        $this->set('optimo_max', $medicion_componente[0]['MedicionComponente']['optimo_max']);
        $this->set('optimo_alerta', $medicion_componente[0]['MedicionComponente']['optimo_alerta']);

        $this->set('medio_max', $medicion_componente[0]['MedicionComponente']['medio_max']);
        $this->set('medio_alerta', $medicion_componente[0]['MedicionComponente']['medio_alerta']);

        $this->set('alto_max', $medicion_componente[0]['MedicionComponente']['alto_max']);
        $this->set('alto_alerta', $medicion_componente[0]['MedicionComponente']['alto_alerta']);

        $this->set('especiales', html_entity_decode($medicion_componente1[0]['MedicionComponente']['especiales'], ENT_QUOTES));


        $medicion_inicial = $medicion_componente[0]['MedicionComponente']['medicion_inicial'];

        //print_r($this->request->data);

        if ($this->request->is('post') && isset($this->request->data)) {
            $data = $this->request->data;

            $medicion['faena_id'] = $faena_id;
            $medicion['flota_id'] = $data['flota'];
            $medicion['unidad_id'] = $data['unidad_ids'];
            $medicion['componente_id'] = $data['componente'];
            $medicion['fecha'] = $data['fecha'] . date(' H:i:s', time()); #date('Y-m-d H:i:s', time());
            $medicion['ps'] = 'false';
            $medicion['usuario_id'] = $this->Session->read('usuario_id');
            $medicion['motivo_cambio'] = trim($data['motivo_cambio']);

            $medicion['fecha_guardado'] = date('Y-m-d H:i:s', time());
            $medicion['correlativo_intervencion'] = $data['intervencion'];
            //$medicion['especiales'] = implode(';', $data['especiales']);
            //print_r($medicion['especiales']);

            foreach ($data['especiales'] as $key => $value) {
                $medicion['especiales'] .= $key . ':' . $value . ';';
            }
            $medicion['especiales'] = substr($medicion['especiales'], 0, -1);
            #print_r($medicion['especiales']);

            foreach ($data['posiciones_ids'] as $key => $value) {
                $medicion['posicion'] = $value;
                $medicion['medicion'] = str_replace(',', '.', $data['medicion'][$key]);
                $medicion['cambio'] = ($data['cambios'][$key] != '' ? $data['cambios'][$key] : "NO");

                $this->Medicion->create();
                $this->Medicion->save($medicion);
            }


            $medicion_anterior = $this->Medicion->find('first', array(
                'fields' => array("Medicion.medicion"),
                'conditions' => array('Medicion.faena_id' => $medicion['faena_id'],
                    'Medicion.flota_id' => $medicion['flota_id'],
                    'Medicion.unidad_id' => $medicion['unidad_id'],
                    'Medicion.ps' => 'true'),
                'order' => array("Medicion.fecha DESC")));

            //si el componente no es axial tomo la medida incial guardada
            if (isset($medicion['componente_id']) && $medicion['componente_id'] != 1) {
                $medicion_anterior['Medicion']['medicion'] = $medicion_inicial;
            }


            // SI Se comparan las medidas entrega alerta segun corresponda, si no, solo alerta de guardado
            if ($compara_medicion) {

                if (isset($medicion_anterior) && $medicion_anterior['Medicion']['medicion'] > 0) {
                    $delta = floatval($medicion['medicion']) - floatval($medicion_anterior['Medicion']['medicion']);

                    //Si NO requieres cambio, se hace las comparaciones normales
                    if (!$cambio) {

                        if (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["optimo_max"])) {
                            $html = '<img src="/img/ok.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i> ÉXITO!</strong> ' .
                                    //$medicion_componente[0]['MedicionComponente']["optimo_mensaje"].
                                    //' Medición dentro de los parámetros..'.
                                    $medicion_componente[0]['MedicionComponente']['optimo_alerta'] .
                                    '</div>';
                        } elseif (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["medio_max"])) {
                            $html = '<img src="/img/medio.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-exclamation"></i> ALERTA! </strong>  - ' .
                                    //$medicion_componente[0]['MedicionComponente']["medio_mensaje"].
                                    //' Informar a supervisor.'.
                                    $medicion_componente[0]['MedicionComponente']['medio_alerta'] .
                                    '</div>';

                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Alerta");
                        } elseif (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["alto_max"])) {
                            $html = '<img src="/img/alto.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-times"></i> ERROR!</strong>  - ' .
                                    //$medicion_componente[0]['MedicionComponente']["alto_mensaje"].
                                    //' Informar a supervisor.'. 
                                    $medicion_componente[0]['MedicionComponente']['alto_alerta'] .
                                    '</div>';


                            // GRAFICO
                            $datagrafico = $this->Medicion->query("select medicion.*, tabla.fechastr
                                                                        from medicion
                                                                        inner join (select unidad_id, max(fecha) fecha, fechastr
                                                                        from(select *, to_char(fecha, 'YYYY-mm-dd') FechaStr, to_char(fecha,'HH24:MM:SS')  HoraStr
                                                                        from medicion Where unidad_id = " . $medicion['unidad_id'] . " and componente_id = " . $medicion['componente_id'] . " and fecha >= (Select max(fecha) from medicion Where unidad_id = " . $medicion['unidad_id'] . " and ps= 'true' and componente_id = " . $medicion['componente_id'] . ")) as tabla
                                                                        group by faena_id, flota_id, unidad_id, componente_id, fechastr, ps) as tabla
                                                                        on tabla.unidad_id = medicion.unidad_id and tabla.fecha = medicion.fecha
                                                                        order by fecha asc");
                            $this->set('grafico', $datagrafico);
                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Error");
                        } else {
                            $html = '<img src="/img/alto.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-times"></i> ERROR!</strong>  -' .
                                    //$medicion_componente[0]['MedicionComponente']["alto_mensaje"].
                                    ' Informar a supervisor.' .
                                    '</div>';
                            // GRAFICO
                            $datagrafico = $this->Medicion->query("select medicion.*, tabla.fechastr
                                                                            from medicion
                                                                            inner join (select unidad_id, max(fecha) fecha, fechastr
                                                                            from(select *, to_char(fecha, 'YYYY-mm-dd') FechaStr, to_char(fecha,'HH24:MM:SS')  HoraStr
                                                                            from medicion Where unidad_id = " . $medicion['unidad_id'] . " and componente_id = " . $medicion['componente_id'] . " and fecha >= (Select max(fecha) from medicion Where unidad_id = " . $medicion['unidad_id'] . " and ps= 'true' and componente_id = " . $medicion['componente_id'] . ")) as tabla
                                                                            group by faena_id, flota_id, unidad_id, componente_id, fechastr, ps) as tabla
                                                                            on tabla.unidad_id = medicion.unidad_id and tabla.fecha = medicion.fecha
                                                                            order by fecha asc");
                            $this->set('grafico', $datagrafico);
                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Error");
                        }
                    } else {
                        //SI requiere cambio las validaciones se hacen en el formulario
                        if (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["optimo_max"])) {
                            
                        } elseif (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["medio_max"])) {
                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Alerta");
                        } elseif (floatval($delta) <= floatval($medicion_componente[0]['MedicionComponente']["alto_max"])) {
                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Error");
                        } else {
                            $this->Mail($medicion['faena_id'], $medicion['unidad_id'], $medicion_componente[0]['MedicionComponente']['nombre'],
                                    $medicion['medicion'], $medicion['fecha'], $medicion_componente[0]['MedicionComponente']["medio_mensaje"], "Error");
                        }

                        $html = '<div class="alert alert-success">'
                                . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                . '<span aria-hidden="true">&times;</span></button>'
                                . '<strong><i class="fa fa-check"></i> ÉXITO!</strong>'
                                . ' Registro guardado correctamente. '
                                . '</div>';
                    }
                    $this->set('alert', $html);
                    $this->set('id_unidad', $medicion['unidad_id']);
                    $this->set('val_medicion', $medicion['medicion']);
                    $this->set('posicion_id', $medicion['posicion']);
                    $this->set('block', $medicion['correlativo_intervencion'] != 0);
                } else {
                    /// si no tiene medicion ingresada no deja ingresar informacion
                    $html = '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-times"></i> ALERTA!</strong>' .
                            //$medicion_componente[0]['MedicionComponente']["optimo_mensaje"].
                            ' No existe medición para este equipo, por favor informe a supervisor.' .
                            '</div>';
                    $this->set('alert', $html);
                    $this->set('id_unidad', $medicion['unidad_id']);
                    $this->set('val_medicion', $medicion['medicion']);
                    $this->set('posicion_id', $medicion['posicion']);
                    $this->set('block', true);
                }
            } else {
                //COMPARA MEDICION
                $html = '<div class="alert alert-success">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        . '<span aria-hidden="true">&times;</span></button>'
                        . '<strong><i class="fa fa-check"></i> ÉXITO!</strong>'
                        . ' Registro guardado correctamente. '
                        . '</div>';
                $this->set('alert', $html);
                $this->set('id_unidad', $medicion['unidad_id']);
                $this->set('val_medicion', $medicion['medicion']);
                $this->set('posicion_id', $medicion['posicion']);
                $this->set('block', $medicion['correlativo_intervencion'] != 0);
            }
        }
    }

    public function Mail($faena_id, $unidad_id, $componente, $medicion, $fecha, $mensaje, $tipo) {
        $util = new UtilidadesController();
        $email = new CakeEmail();
        $this->loadModel('Unidad');
        $this->loadModel('Usuario');
        $email->config('amazon');
        $email->emailFormat('html');
        $destinatarios = array();
        try {

            $unidad = $this->Unidad->query("select faena, flota, unidad, esn, ps, tabla.id 
                                from (select Faena.nombre faena, Flota.nombre flota, unidad.unidad, max(estado_motor.fecha_ps) ps,unidad.id 
                                from unidad 
                                inner join Faena on Faena.id = unidad.faena_id
                                inner join Flota on Flota.id = unidad.flota_id
                                inner join estado_motor on estado_motor.unidad_id = unidad.id and estado_motor.esn is not null 
                                group by faena, flota, unidad, unidad.id) as tabla
                                inner join estado_motor on estado_motor.unidad_id = tabla.id and estado_motor.esn is not null and estado_motor.fecha_ps = tabla.ps 
                                Where tabla.id= $unidad_id
                                order by faena, flota, unidad ");

            $usuarios = $this->Usuario->query("select distinct u.id, u.correo_electronico, pu.faena_id
                                from usuario u
                                inner join permisos_usuarios pu on pu.usuario_id = u.id
                                WHere pu.faena_id = $faena_id and u.e= '1' and pu.cargo_id in (2,6) and pu.e = '1'");


            foreach ($usuarios as $usuario) {
                if (isset($usuario[0]["correo_electronico"])) {
                    $destinatarios[] = $usuario[0]["correo_electronico"];
                }
            }


            #$destinatarios[] = "aaron.zuniga@cummins.cl";
            $html = "<html>";
            $html .= "<body>";
            $html .= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
            $html .= "<tr style=\"background-color: red; color: white;\">";
            $html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">$componente Medición</td>";
            $html .= "</tr>";
            $html .= "</table>";

            $html .= '<table border="1" width="100%">';
            $html .= '	<tr>';
            $html .= '		<th>Faena</th>';
            $html .= "           <td nowrap>{$unidad[0][0]["faena"]}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Flota</th>';
            $html .= "		<td nowrap>{$unidad[0][0]["flota"]}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Equipo</th>';
            $html .= "		<td nowrap>{$unidad[0][0]["unidad"]}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>ESN</th>';
            $html .= "		<td nowrap>{$unidad[0][0]["esn"]}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Medición</th>';
            $html .= "		<td nowrap>{$medicion}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Fecha</th>';
            $html .= "		<td nowrap>{$fecha}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Tipo</th>';
            $html .= "		<td nowrap>{$tipo}</td>";
            $html .= '	</tr>';
            $html .= '   <tr>';
            $html .= '		<th>Recomendaciones</th>';
            $html .= "		<td nowrap>{$mensaje}</td>";
            $html .= '	</tr>';

            $html .= '</table>';

            $html .= "</body>";
            $html .= "</html>";

            $asunto = $componente . ' - ' . $unidad[0][0]["unidad"]; //. $int["Faena"];

            /* if(MAIL_DEBUG == ""){
              if(is_array($destinatarios) && count($destinatarios) > 0) {

              $this->AWSSES->to = $destinatarios;
              $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
              $this->AWSSES->charset = 'UTF-8';

              }
              } else {
              $destinatarios = array();
              $destinatarios[] = MAIL_DEBUG;
              $this->AWSSES->to = $destinatarios;
              $this->AWSSES->sendRaw(utf8_encode($asunto), utf8_encode($html));
              } */
            if (MAIL_DEBUG == "") {
                if (is_array($destinatarios) && count($destinatarios) > 0) {

                    $this->sendMail($destinatarios, $asunto, $html);
                }
            }
            if (MAIL_DEBUG != "") {
                $destinatarios = array();
                $destinatarios[] = MAIL_DEBUG;
                $this->sendMail($destinatarios, $asunto, $html2);
            }
            //$this->AWSSES->reset();
            $email->reset();
        } catch (Exception $e) {
            //$this->Session->setFlash('No se pudo enviar correo', 'guardar_error');
            $this->logger($this, $e->getMessage());
        }
        //$this->redirect('/Medicion');
    }

    public function mediciones() {
        $this->set('titulo', 'Mediciones');
        $this->layout = 'metronic_principal';
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Unidad');
        $this->loadModel('FaenaFlota');

        if (isset($this->request->query)) {
            if($this->request->query['id'] && $this->request->query['medicion_new']) {
                try {
                    $medi = str_replace(',', '.', $this->request->query['medicion_new']);

                    $this->Medicion->query("UPDATE medicion SET  medicion = {$medi}, fecha = '{$this->request->query['fecha_new']}' WHERE id = {$this->request->query["id"]} ");
                    $this->Session->setFlash('La medición ha sido modificada correctamente.', 'guardar_exito');

                } catch (Exception $e) {
                    $this->Session->setFlash('No se pudo modificar la medición, por favor revise la información e intente nuevamente.', 'guardar_error');
                    $this->logger($this, $e->getMessage());


                }
            }
        }

        $util = new UtilidadesController();
        
        $faena_id = $this->Session->read('faena_id');
        $cargos = $this->Session->read('PermisosCargos');
        $cargo_id = $cargos[$faena_id][0];
        $this->set("cargo", $cargo_id);
        
        $limit = 10;
        $unidad_id = '';
        $faena_id = '';
        $flota_id = '';
        $componente_id = '';
        $fecha_ini = date("Y-m-d", strtotime("-30 days"));
        $fecha_fin = date("Y-m-d");
        
        
        
        $faenas = $this->Session->read("NombresFaenas");

        foreach ($faenas as $key => $value) {
            $f_id[] = $key;
        }

        $registros = array();
        $conditions = array();
        if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $faena_id = $this->request->query['faena_id'];
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
            }
            if (isset($this->request->query['componente_id']) && $this->request->query['componente_id'] != ''  && is_numeric($this->request->query['componente_id'])) {
                $componente_id = $this->request->query['componente_id'];                
            }else{
                $this->Session->setFlash('Debe seleccionar un componente valido.', 'guardar_error');
                $this->logger($this, "INTENTO DE INTRUSIÓN: componente_id valor ingresado = (".$this->request->query['componente_id']) .")" ;
                $this->redirect(array('action' => 'mediciones'));
            }

            if (isset($this->request->query['fecha_ini']) && $util->validaFecha($this->request->query['fecha_ini'], 'Y-m-d') ) {
                $fecha_ini = $this->request->query['fecha_ini'];
            }else{
                $this->Session->setFlash('Debe seleccionar una fecha de inicio valida.', 'guardar_error');
                $this->logger($this, "INTENTO DE INTRUSIÓN: fecha_inicio valor ingresado = (".$this->request->query['fecha_ini']) .")" ;
                $this->redirect(array('action' => 'mediciones'));
            }

            if (isset($this->request->query['fecha_fin']) && $util->validaFecha($this->request->query['fecha_fin'], 'Y-m-d')) {
                $fecha_fin = $this->request->query['fecha_fin'];
            }else{
                $this->Session->setFlash('Debe seleccionar una fecha de termino valida.', 'guardar_error');
                $this->logger($this, "INTENTO DE INTRUSIÓN: fecha_inicio valor ingresado = (".$this->request->query['fecha_ini']) .")" ;
                $this->redirect(array('action' => 'mediciones'));
            }

            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }

            $conditions["Medicion.faena_id IN"] = $this->Session->read("FaenasFiltro");

            $query = "SELECT Medicion.*, estado_motor.esn_placa, Faena.nombre faena, Flota.nombre flota, Unidad.unidad, Medicion_Componente.nombre, 
                            Medicion_Componente.unidad_medida ,Medicion_Componente.medicion_inicial , 
                            (CASE WHEN tabla.fecha IS NULL THEN 'NO' ELSE 'SI' END) valido, 
                            (select m.medicion from medicion m Where m.ps = true and m.faena_id = Medicion.faena_id and m.flota_id = Medicion.flota_id
                             and m.unidad_id = Medicion.unidad_id and m.componente_id = Medicion.componente_id order by fecha DESC limit 1) as val_ps ,
                             concat(usuario.nombres, ' ', usuario.apellidos) usuario 
                            from Medicion 
                            left join (select unidad_id, max(fecha) fecha, fechastr 
                                        from(select *, to_char(fecha, 'YYYY-mm-dd') FechaStr, 
                                                     to_char(fecha,'HH24:MM:SS') HoraStr 
                                                     from medicion Where unidad_id = Medicion.unidad_id 
                                                     and componente_id = Medicion.componente_id and ps = 'false') as tabla 
                                        group by faena_id, flota_id, unidad_id, componente_id, fechastr, ps) as tabla on 
                            tabla.unidad_id = medicion.unidad_id and tabla.fecha = medicion.fecha 
                            INNER JOIN Faena ON Faena.id = Medicion.faena_id 
                            INNER JOIN Flota ON Flota.id = Medicion.flota_id 
                            INNER JOIN Unidad ON Unidad.id = Medicion.unidad_id 
                            INNER JOIN Medicion_Componente ON Medicion_Componente.id = Medicion.componente_id 
                            INNER JOIN usuario on usuario.id = medicion.usuario_id 
                            INNER JOIN estado_motor ON estado_motor.unidad_id = Medicion.unidad_id  
                            AND estado_motor.fecha_ps = (SELECT max(fecha_ps) 
							  FROM estado_motor WHERE unidad_id = Medicion.unidad_id AND fecha_ps <= Medicion.fecha) 
                            WHERE Medicion.faena_id IN (" . implode(",", $f_id) . ")";

            if ($faena_id != '') {
                $query .= ' AND Medicion.faena_id = ' . $faena_id;
            }
            if ($flota_id != '') {
                $query .= ' AND Medicion.flota_id = ' . $flota_id;
            }
            if ($unidad_id != '') {
                $query .= ' AND Medicion.unidad_id = ' . $unidad_id;
            }
            if ($componente_id != '') {
                $query .= ' AND Medicion.componente_id = ' . $componente_id;
            }
            if ($fecha_ini != '') {
                $query .= " AND to_char(Medicion.fecha,'YYYY-MM-DD') >= '$fecha_ini'";
            }

            if ($fecha_fin != '') {
                $query .= " AND to_char(Medicion.fecha,'YYYY-MM-DD') <= '$fecha_fin'";
            }

            $query .= ' order by Medicion.faena_id, Medicion.unidad_id, Medicion.fecha ASC';

            
            $registros = $this->Medicion->query($query);


            if (isset($this->request->query["btn-descargar"])) {
                $this->redirect("/Medicion/descargar?faena_id=$faena_id&flota_id=$flota_id&unidad_id=$unidad_id&componente_id=$componente_id&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin");
            }


        }


        $this->set('registros', $registros);
        $this->set('limit', $limit);




        //foreach ($faenas_permiso as $key => $value) {}
        //$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        //$this->set(compact('faenas'));

        //print_r($this->Session->read("FaenasFiltro"));
        //$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        //$this->set(compact('flotas'));
        
        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        //$unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro")), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        //$this->set(compact('unidades'));
        
        $unidades = $this->Unidad->find('all', array('fields' => array("id","flota_id","faena_id","Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"),'recursive' => -1));
        $this->set(compact('unidades'));

        $componentes = $this->MedicionComponente->find('all', array('fields' => array('id', 'nombre'), 'recursive' => -1));
        $this->set(compact('componentes'));

        $this->set('unidad_id', $unidad_id);
        $this->set('flota_id', $flota_id);
        $this->set('faena_id', $faena_id);
        $this->set('componente_id', $componente_id);
        $this->set('fecha_ini', $fecha_ini);
        $this->set('fecha_fin', $fecha_fin);
    }

    public function agregar() {
        $this->set('titulo', 'Nueva Medición');
        //$this->check_permissions($this);
        $this->layout = 'metronic_principal';
        $this->loadModel('Medicion');
        $this->loadModel('MedicionComponente');
        $this->loadModel('Faena');
        $this->loadModel('Flota');
        $this->loadModel('Unidad');
        $this->loadModel('FaenaFlota');


        $faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
        $this->set(compact('faenas'));

        $flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id", "faena_id", "Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
        $this->set(compact('flotas'));

        $unidades = $this->Unidad->find('all', array('fields' => array("id", "flota_id", "faena_id", "Unidad.unidad"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro")), 'order' => array("Unidad.unidad"), 'recursive' => -1));
        $this->set(compact('unidades'));

        $componentes = $this->MedicionComponente->find('all', array('fields' => array('id', 'nombre', 'unidad_medida'), 'recursive' => -1));
        $this->set(compact('componentes'));

        $this->set('uni_med', $componentes[0]['MedicionComponente']['unidad_medida']);

        if ($this->request->is('post') && isset($this->request->data)) {
            $data = $this->request->data;

            try {
                $flota_id = explode("_", $data['flota_id']);
                $flota = $flota_id[1];

                $unidad_id = explode("_", $data['unidad_id']);
                $unidad = $unidad_id[2];

                $medicion['faena_id'] = $data["faena_id"];
                $medicion['flota_id'] = $flota;
                $medicion['unidad_id'] = $unidad;
                $medicion['componente_id'] = $data["componente_id"];
                $medicion['medicion'] = str_replace(',', '.', $data['medicion']);
                $medicion['fecha'] = $data["fecha"];
                $medicion['posicion'] = $data["posicion"];
                $medicion['ps'] = 'true';
                $medicion['usuario_id'] = $this->Session->read('usuario_id');

                $this->Medicion->save($medicion);


                $this->Session->setFlash('La medición ha sido ingresada correctamente.', 'guardar_exito');
                $this->redirect(array('action' => 'mediciones'));
            } catch (Exception $e) {
                $this->Session->setFlash('No se pudo ingresar la medición, por favor revise la información e intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
                $this->redirect(array('action' => 'mediciones'));
            }
        }
    }

    public function editar($id) {
        $this->loadModel('Medicion');
        if (isset($this->request->data)) {
            $data = $this->request->data;
            try {
                $medi = str_replace(',', '.', $data['medicion_new'.$id]);

                $this->Medicion->query("UPDATE medicion SET  medicion = {$medi}, fecha = '{$data['fecha_new'.$id]}' WHERE id = {$id} ");

                $this->Session->setFlash('La medición ha sido modificada correctamente.', 'guardar_exito');
                $this->redirect(array('action' => 'mediciones'));

            } catch (Exception $e) {
                $this->Session->setFlash('No se pudo modificar la medición, por favor revise la información e intente nuevamente.', 'guardar_error');
                $this->logger($this, $e->getMessage());
                $this->redirect(array('action' => 'mediciones'));

            }
        }


    }

    function descargar() {
        $faenas = $this->Session->read("NombresFaenas");
        $util = new UtilidadesController();
        foreach ($faenas as $key => $value) {
            $f_id[] = $key;
        }

        $query = "SELECT Medicion.*, estado_motor.esn_placa, Faena.nombre faena, Flota.nombre flota, Unidad.unidad, Medicion_Componente.nombre, 
                Medicion_Componente.unidad_medida ,Medicion_Componente.medicion_inicial , 
                (CASE WHEN tabla.fecha IS NULL THEN 'NO' ELSE 'SI' END) valido, 
                (select m.medicion from medicion m Where m.ps = true and m.faena_id = Medicion.faena_id and m.flota_id = Medicion.flota_id
                and m.unidad_id = Medicion.unidad_id and m.componente_id = Medicion.componente_id order by fecha DESC limit 1) as val_ps ,
                concat(usuario.nombres, ' ', usuario.apellidos) usuario 
                from Medicion 
                left join (select unidad_id, max(fecha) fecha, fechastr 
                            from(select *, to_char(fecha, 'YYYY-mm-dd') FechaStr, 
                                         to_char(fecha,'HH24:MM:SS') HoraStr 
                                         from medicion Where unidad_id = Medicion.unidad_id 
                                         and componente_id = Medicion.componente_id and ps = 'false') as tabla 
                            group by faena_id, flota_id, unidad_id, componente_id, fechastr, ps) as tabla on 
                tabla.unidad_id = medicion.unidad_id and tabla.fecha = medicion.fecha 
                INNER JOIN Faena ON Faena.id = Medicion.faena_id 
                INNER JOIN Flota ON Flota.id = Medicion.flota_id 
                INNER JOIN Unidad ON Unidad.id = Medicion.unidad_id 
                INNER JOIN Medicion_Componente ON Medicion_Componente.id = Medicion.componente_id 
                INNER JOIN usuario on usuario.id = medicion.usuario_id 
                INNER JOIN estado_motor ON estado_motor.unidad_id = Medicion.unidad_id  
                AND estado_motor.fecha_ps = (SELECT max(fecha_ps) 
                                              FROM estado_motor WHERE unidad_id = Medicion.unidad_id AND fecha_ps <= Medicion.fecha) 
                WHERE Medicion.faena_id IN (" . implode(",", $f_id) . ")";

        if ($this->request->query['faena_id'] != '') {
            $query .= ' AND Medicion.faena_id = ' . $this->request->query['faena_id'];
        }
        if ($this->request->query['flota_id'] != '') {
            $query .= ' AND Medicion.flota_id = ' . $this->request->query['flota_id'];
        }
        if ($this->request->query['unidad_id'] != '') {
            $query .= ' AND Medicion.unidad_id = ' . $this->request->query['unidad_id'];
        }
        if ($this->request->query['componente_id'] != '' && is_numeric($this->request->query['componente_id'])) {
            $query .= ' AND Medicion.componente_id = ' . $this->request->query['componente_id'];
        }else{
            $this->Session->setFlash('Debe seleccionar un componente valido..', 'guardar_error');
            $this->logger($this, "INTENTO DE INTRUSIÓN: componente_id valor ingresado = (".$this->request->query['componente_id']) .")" ;
            $this->redirect(array('action' => 'mediciones'));
        }
        if (isset($this->request->query['fecha_ini']) && $util->validaFecha($this->request->query['fecha_ini'], 'Y-m-d') ) {
            $fecha_ini = $this->request->query['fecha_ini'];
            $query .= " AND to_char(Medicion.fecha,'YYYY-MM-DD') >= '$fecha_ini'";
        }else{
            $this->Session->setFlash('Debe seleccionar una fecha de inicio valida.', 'guardar_error');
            $this->logger($this, "INTENTO DE INTRUSIÓN: fecha_inicio valor ingresado = (".$this->request->query['fecha_ini']) .")" ;
            $this->redirect(array('action' => 'mediciones'));
        }

        if (isset($this->request->query['fecha_fin']) && $util->validaFecha($this->request->query['fecha_fin'], 'Y-m-d')) {
            $fecha_fin = $this->request->query['fecha_fin'];
            $query .= " AND to_char(Medicion.fecha,'YYYY-MM-DD') <= '$fecha_fin'";
        }else{
            $this->Session->setFlash('Debe seleccionar una fecha de termino valida.', 'guardar_error');
            $this->logger($this, "INTENTO DE INTRUSIÓN: fecha_inicio valor ingresado = (".$this->request->query['fecha_ini']) .")" ;
            $this->redirect(array('action' => 'mediciones'));
        }

        $query .= ' order by Medicion.faena_id, Medicion.unidad_id, Medicion.fecha ASC';

        //print_r($query);

        $registros = $this->Medicion->query($query);

        $dataArray = array();

        $utilReporte = new UtilidadesReporteController();
        $util = new UtilidadesController();

        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        $objPHPExcel = new PHPExcel();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment;filename="Mediciones-' . date("Y-m-d") . '".xlsx');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objPHPExcel->getProperties()
                ->setCreator("DBM")
                ->setLastModifiedBy("DBM")
                ->setTitle("Mediciones");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle('Mediciones');

        $i = 1;
        $filaInicial = 2;
        $enc = array();
        foreach ($registros as $value) {
            $dataTemp = array();
            $linea = i + $filaInicial++;
            #$dataTemp[] = $value[0]['id'];
            $dataTemp[] = $value[0]['faena'];
            $dataTemp[] = $value[0]['flota'];
            $dataTemp[] = $value[0]['unidad'];
            $dataTemp[] = $value[0]['esn_placa'];
            $dataTemp[] = $value[0]['medicion'] == "" || $value[0]['medicion'] == 0 ? 'N/A' : $value[0]['medicion'];
            $dataTemp[] = $value[0]['unidad_medida'];
            $dataTemp[] = $value[0]['fecha'];
            if ($value[0]['ps'] == '1') {
                $dataTemp[] = 'PS';
                $utilReporte->cellColor("A$linea:O$linea", "000000", "FFFFFF", $objPHPExcel);
            } elseif ($value[0]['valido'] == 'SI') {
                $dataTemp[] = 'Válida';
                $utilReporte->cellColor("A$linea:O$linea", "C5D9F1", "000000", $objPHPExcel);
            } else {
                $dataTemp[] = 'Medición';
                $utilReporte->cellColor("A$linea:O$linea", "FFFFFF", "000000", $objPHPExcel);
            }
            $dataTemp[] = $value[0]['nombre'];
            $dataTemp[] = $value[0]['posicion'];
            $dataTemp[] = $value[0]['usuario'];
            $dataTemp[] = isset($value[0]['val_ps']) ? $value[0]['val_ps'] : $value[0]['medicion_inicial'];

            $dataTemp[] = $value[0]['cambio'] == " " || $value[0]['cambio'] == null || $value[0]['cambio'] == 'false' ? "NO" : $value[0]['cambio'] == 'true' ? 'SI' : $value[0]['cambio'];
            $dataTemp[] = $value[0]['motivo_cambio'];
            $dataTemp[] = $value[0]['correlativo_intervencion'];

            #$esp = str_replace("_", " ", '- '.$value[0]['especiales']);
            #$esp = str_replace(";", PHP_EOL.'- ', $esp); 
            #$esp = str_replace(":", " : ", $esp); 

            $esp = split(";", $value[0]['especiales']);
            foreach ($esp as $cmp) {
                $temp = split(":", $cmp);
                $t = str_replace("_", " ", $temp[0]);
                $t = ucwords($t);
                if (!in_array($t, $enc)) {
                    $enc[] = $t;
                }
                $dataTemp[] = $temp[1];
            }

            //$dataTemp[] = $esp;

            $dataArray[] = $dataTemp;
        }


        // Encabezados
        #$encabezados = array("Id","Faena","Flota","Unidad","ESN","Medicion","U. Medida","Fecha","Tipo","Componente","Posición", "usuario", "valor PS", "Cambio", "Motivo Cambio", "Atrb. Especiales");
        $encabezados = array("Faena", "Flota", "Unidad", "ESN", "Medicion", "U. Medida", "Fecha", "Tipo", "Componente", "Posición", "usuario", "valor PS", "Cambio", "Motivo Cambio", "Correlativo MP");
        foreach ($enc as $e) {
            $encabezados[] = $e;
        }
        $count = count($encabezados);
        for ($i = 0; $i < $count; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $encabezados[$i]);
        }
        $colString = PHPExcel_Cell::stringFromColumnIndex($count - 1);
        $utilReporte->cellColor("A1:" . $colString . "1", "92D050", "FFFFFF", $objPHPExcel);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . '1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true); //->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

        $style_cell = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $style_border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),)
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . (count($registros) + 1))->applyFromArray($style_cell);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . (count($registros) + 1))->applyFromArray($style_border);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    function sendMail($to, $subject, $body) {

        $this->ses = new Aws\Ses\SesClient([
            'version' => '2010-12-01',
            'region' => AMAZON_SES_REGION,
            'credentials' => [
                'key' => AMAZON_SES_ACCESS_KEY_ID,
                'secret' => AMAZON_SES_SECRET_ACCESS_KEY
            ]
        ]);

        if (is_string($to)) {
            $to = [$to];
        }

        $destination = array(
            'ToAddresses' => $to
        );
        $message = array(
            'Subject' => array(
                'Data' => $subject
            ),
            'Body' => array()
        );



        if ($body != NULL) {
            $message['Body']['Html'] = array(
                'Data' => $body
            );
        }

        $char_set = 'UTF-8';
        $ok = true;

        try {

            $response = $this->ses->sendEmail([
                'Destination' => $destination,
                'Source' => AMAZON_SES_FROM_EMAIL,
                'Message' => $message
            ]);
        } catch (AwsException $e) {
            $ok = false;
            $this->log('Error sending email from AWS SES: ' . $e->getMessage(), 'error');
            $this->log('Error sending email from AWS SES: ' . $e->getAwsErrorMessage(), 'error');
        }

        return $ok;
    }

    function eliminarmedicion($id) {
        $this->loadModel('Medicion');
        
        if ($this->request->is('get') && isset($this->request->query) && count($this->request->query) >= 2) {
            if (isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
                $faena_id = $this->request->query['faena_id'];
            }
            if (isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
                $flota_id = explode("_", $this->request->query['flota_id']);
                $flota_id = $flota_id[1];
                $flota =  $this->request->query['flota_id'];
            }
            if (isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
                $unidad_id = explode("_", $this->request->query['unidad_id']);
                $unidad_id = $unidad_id[2];
                $unidad =  $this->request->query['unidad_id'];
            }
            if (isset($this->request->query['componente_id']) && $this->request->query['componente_id'] != '') {
                $componente_id = $this->request->query['componente_id'];
                ;
            }

            if (isset($this->request->query['fecha_ini']) && $this->request->query['fecha_ini']) {
                $fecha_ini = $this->request->query['fecha_ini'];
            }

            if (isset($this->request->query['fecha_fin']) && $this->request->query['fecha_fin']) {
                $fecha_fin = $this->request->query['fecha_fin'];
            }

            foreach ($this->request->query as $key => $value) {
                $this->set($key, $value);
            }
        }
        
        $this->Medicion->delete($id);
        $this->Session->setFlash("Medición eliminada con éxito " , 'guardar_exito');
        $this->redirect("/Medicion/mediciones?faena_id=$faena_id&flota_id=$flota&unidad_id=$unidad&componente_id=$componente_id&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin");
        
    }

}
