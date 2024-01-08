<?php
	App::uses('ConnectionManager', 'Model'); 
	/* Esta clase defines funciones utilitarias para el uso en el sistema */
	class VerificacionController extends AppController {
		function revisarContinuaciones(){
			$this->loadModel('Planificacion');
			$this->layout = null;
			$db=ConnectionManager::getDataSource('default');
			// Se obtienen todas las intervenciones que tengan una modificacion en el tipo de intervencion, tenga hijo y no tenga padre (intervencion principal).
			$p = $this->Planificacion->find('all', array(
				'fields' => array('Planificacion.id','Planificacion.tipointervencion','Planificacion.padre','Planificacion.hijo', 'Planificacion.tipointervencion_original', 'Planificacion.correlativo'),
				'conditions' => array("Planificacion.id = Planificacion.correlativo AND 
									   Planificacion.tipointervencion_original IS NOT NULL AND 
									   Planificacion.tipointervencion_original <> '' AND 
									   Planificacion.tipointervencion_original <> Planificacion.tipointervencion AND 
									   Planificacion.hijo IS NOT NULL AND 
									   Planificacion.hijo <> '' AND 
									   (Planificacion.padre IS NULL OR Planificacion.padre = '')"),
				'recursive' => -1,
				'limit' => 20
			));
			foreach($p as $planificacion){
				// Se actualiza intervenciones al nuevo tipo de intervencion, tomando como identificados el correlativo (intervencion principal e hijos).
				$query = "UPDATE Planificacion SET tipointervencion='{$planificacion["Planificacion"]["tipointervencion"]}', tipointervencion_original='{$planificacion["Planificacion"]["tipointervencion_original"]}' WHERE correlativo = '{$planificacion["Planificacion"]["correlativo"]}' AND tipointervencion <> '{$planificacion["Planificacion"]["tipointervencion"]}';";
				//$db->query($query);
				print_r($planificacion);
				print_r("<br />");
				print_r($query);
				print_r("<hr />");
			}
			
			// Se obtienen todas las intervenciones EX que tengan hijo o padre (las intervenciones EX no deberian tener padre ni hijo).
			$p = $this->Planificacion->find('all', array(
				'fields' => array('Planificacion.id','Planificacion.tipointervencion','Planificacion.padre','Planificacion.hijo', 'Planificacion.tipointervencion_original', 'Planificacion.correlativo'),
				'conditions' => array("Planificacion.tipointervencion='EX' AND 
									   ((Planificacion.hijo IS NOT NULL AND Planificacion.hijo <> '') OR 
									   (Planificacion.padre IS NOT NULL AND Planificacion.padre <> ''))"),
				'recursive' => -1,
				'limit' => 20
			));
			foreach($p as $planificacion){
				// Se actualizan intervenciones EX, se definen como nulos el campo hijo y padre y se asigna al correlativo el mismo valor del folio.
				$query = "UPDATE Planificacion SET hijo='', padre='', correlativo='{$planificacion["Planificacion"]["id"]}' WHERE id = '{$planificacion["Planificacion"]["id"]}';";
				print_r($planificacion);
				print_r("<br />");
				print_r($query);
				print_r("<hr />");
			}
			//print_r($p);
			exit;
		}
	}
?>