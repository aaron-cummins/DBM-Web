<?php
App::uses('ConnectionManager', 'Model'); 
class DashboardController extends AppController {
	public function index() {
		$this->check_permissions($this);
		$this->loadModel('Planificacion');
		$this->loadModel('Sistema');
		$this->loadModel('IntervencionElementos');
		$this->layout = 'metronic_principal';
		$this->set('titulo', 'Dashboard');
		$this->set('breadcrumb', 'Dashboard');
		
		$params = array(
			'fields' => array(
				'Sistema.nombre',
				'SUM(IntervencionElementos.tiempo) as tiempo'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.sistema_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'Sistema.nombre',
			'order' => array('tiempo' => 'desc')
		);
		$distribucion_sistemas = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion_sistemas as $key => $value) {
			$result[$value["Sistema"]["nombre"]] = $value[0]["tiempo"];
		}
		unset($distribucion_sistemas);
		$this->set('distribucion_sistemas', $result);
		
		$params = array(
			'fields' => array(
				'Sistema.nombre',
				'COUNT(IntervencionElementos.tiempo) as cantidad'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.sistema_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'Sistema.nombre',
			'order' => array('cantidad' => 'desc')
		);
		$distribucion_sistemas = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion_sistemas as $key => $value) {
			$result[$value["Sistema"]["nombre"]] = $value[0]["cantidad"];
		}
		unset($distribucion_sistemas);
		$this->set('distribucion_sistemas_cantidad', $result);
		
		$params = array(
			'fields' => array(
				'TipoElemento.nombre',
				'COUNT(IntervencionElementos.tipo_id) as tipo'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.tipo_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'TipoElemento.nombre',
			'order' => array('tipo' => 'desc')
		);
		$distribucion = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion as $key => $value) {
			$result[$value["TipoElemento"]["nombre"]] = $value[0]["tipo"];
		}
		unset($distribucion);
		$this->set('distribucion_tipo', $result);
		
		$params = array(
			'fields' => array(
				'TipoElemento.nombre',
				'SUM(IntervencionElementos.tiempo) as tiempo'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.tipo_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'TipoElemento.nombre',
			'order' => array('tiempo' => 'desc')
		);
		$distribucion = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion as $key => $value) {
			$result[$value["TipoElemento"]["nombre"]] = $value[0]["tiempo"];
		}
		unset($distribucion);
		$this->set('distribucion_tipo_tiempo', $result);
		
		
		$params = array(
			'fields' => array(
				'Subsistema.nombre',
				'SUM(IntervencionElementos.tiempo) as tiempo'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.subsistema_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'Subsistema.nombre',
			'order' => array('tiempo' => 'desc')
		);
		$distribucion_subsistemas = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion_subsistemas as $key => $value) {
			$result[$value["Subsistema"]["nombre"]] = $value[0]["tiempo"];
		}
		unset($distribucion_subsistemas);
		$this->set('distribucion_subsistemas', $result);
		
		$params = array(
			'fields' => array(
				'Subsistema.nombre',
				'COUNT(IntervencionElementos.tiempo) as cantidad'
			),
			'conditions' => array('tipo_registro' => '0', 'IntervencionElementos.subsistema_id NOT' => null, 'IntervencionElementos.faena_id' => $this->Session->read("faena_id")),
			'recursive' => 1,
			'group' => 'Subsistema.nombre',
			'order' => array('cantidad' => 'desc')
		);
		$distribucion_subsistemas = $this->IntervencionElementos->find('all', $params);
		$result = array();
		foreach($distribucion_subsistemas as $key => $value) {
			$result[$value["Subsistema"]["nombre"]] = $value[0]["cantidad"];
		}
		unset($distribucion_subsistemas);
		$this->set('distribucion_subsistemas_cantidad', $result);
		
		
		// Data grafico trabajos en espera de revisión agrupados por días
		
		$f1=strtotime("-1 day");
		$f2=strtotime("-2 day");
		$f3=strtotime("-3 day");
		$f0=strtotime("now");
		$f1=date("Y-m-d",$f1);
		$f0=date("Y-m-d",$f0);
		$f2=date("Y-m-d",$f2);
		$f3=date("Y-m-d",$f3);
			
		$grafico_data = NULL;		
		$grafico_data = array();
		$grafico_data["cols"][0]["label"] = "Días";
		$grafico_data["cols"][0]["type"] = "string";
		$grafico_data["cols"][1]["label"] = "Trabajos";
		$grafico_data["cols"][1]["type"] = "number";
		$grafico_data["rows"][0]["c"][0]["v"] = "< 1 dia";
		$grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
		$grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
		
		$params = array(
			'conditions' => array('estado' => '7',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha BETWEEN ? and ?' => array("$f0 00:00:00", "$f0 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][0]["c"][1]["v"] = $trabajos;
		
		$params = array(
			'conditions' => array('estado' => '7',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha BETWEEN ? and ?' => array("$f2 00:00:00", "$f1 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][1]["c"][1]["v"] = $trabajos;
		
		$params = array(
			'conditions' => array('estado' => '7',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha <= ? ' => array("$f3 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][2]["c"][1]["v"] = $trabajos;
		
		$this->set("trabajos_espera", $grafico_data);
		
		$grafico_data = NULL;		
		$grafico_data = array();
		$grafico_data["cols"][0]["label"] = "Días";
		$grafico_data["cols"][0]["type"] = "string";
		$grafico_data["cols"][1]["label"] = "Trabajos";
		$grafico_data["cols"][1]["type"] = "number";
		$grafico_data["rows"][0]["c"][0]["v"] = "< 1 dia";
		$grafico_data["rows"][1]["c"][0]["v"] = "1 - 2 dias";
		$grafico_data["rows"][2]["c"][0]["v"] = "> 2 dias";
		
		$params = array(
			'conditions' => array('estado' => '2',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha BETWEEN ? and ?' => array("$f0 00:00:00", "$f0 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][0]["c"][1]["v"] = $trabajos;
		
		$params = array(
			'conditions' => array('estado' => '2',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha BETWEEN ? and ?' => array("$f2 00:00:00", "$f1 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][1]["c"][1]["v"] = $trabajos;
		
		$params = array(
			'conditions' => array('estado' => '2',
				'faena_id' => $this->Session->read("faena_id"),
				'fecha <= ? ' => array("$f3 23:59:59")),
			'recursive' => -1
		);
		$trabajos = $this->Planificacion->find('count', $params);
		$grafico_data["rows"][2]["c"][1]["v"] = $trabajos;
		
		$this->set("trabajos_planificados", $grafico_data);
	}
}
?>