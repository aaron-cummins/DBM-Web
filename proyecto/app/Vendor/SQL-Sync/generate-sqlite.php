<?php 
	$start = time();
	include("../../Config/database.php");
	$base_config = get_class_vars('DATABASE_CONFIG');
	$fecha = date("Y-m-h");
	@unlink("DBM-test.sqlite");
	@unlink("../../webroot/bcZybqbNT7RyfGYWSLDd95VpExqHmp/Lh9FkYLVCCpkQjA9Uw7n683QY3LKgF/DBM.sqlite.zip");
	function sqlite_open($location) 
	{ 
	    $handle = new SQLite3($location); 
	    return $handle; 
	} 
	function sqlite_query($dbhandle,$query) 
	{ 
	    $array['dbhandle'] = $dbhandle; 
	    $array['query'] = $query; 
	    $result = $dbhandle->query($query); 
	    return $result; 
	} 
	
	$db = sqlite_open("DBM-test.sqlite");
	
	$tables[] = "CREATE TABLE usuario (
				    id INTEGER,
				    usuario integer ,
				    pin varchar(33),
				    ultimoacceso TIMESTAMP,
				    nivelusuario_id integer,
				    apellidos varchar(100),
				    nombres varchar(100),
				    correo_electronico varchar(100),
				    e varchar(1) DEFAULT 1,
				    updated TIMESTAMP,
				    UNIQUE (id)
				)";
	$tables[] = "CREATE TABLE sistema_subsistema_motor_elemento (
				    id INTEGER,
				    motor_id integer,
				    sistema_id integer,
				    subsistema_id integer,
				    codigo varchar(3),
				    elemento_id integer,
				    posicion_id integer,
				    e character(1) DEFAULT 1,
				    mtime integer DEFAULT 0,
				    updated TIMESTAMP,
				    UNIQUE (id)
				)";
	$tables[] = "CREATE TABLE motor_sistema_subsistema_elemento_diagnostico (
				    id INTEGER PRIMARY KEY,
				    motor_id INTEGER ,
				    sistema_id INTEGER ,
				    subsistema_id INTEGER ,
				    elemento_id INTEGER ,
				    diagnostico_id INTEGER ,
				    e varchar(1) DEFAULT 1 ,
				    mtime integer DEFAULT 0,
				    updated TIMESTAMP,
				    UNIQUE(id)
				)";
	foreach($tables as $table){
		sqlite_query($db, $table);
	}
	
	// Conectando y seleccionado la base de datos  
	$dbconn = pg_connect("host={$base_config["default"]["host"]} dbname={$base_config["default"]["database"]} user={$base_config["default"]["login"]} password={$base_config["default"]["password"]}") or die('No se ha podido conectar: ' . pg_last_error());
	
	// Tabla usuarios
	$query = "SELECT id, usuario, pin, nivelusuario_id, apellidos, nombres, correo_electronico, updated FROM usuario WHERE e = '1';";
	$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
	
	while ($row = pg_fetch_row($result, NULL, PGSQL_ASSOC)) {
		$row["apellidos"] = trim($row["apellidos"]);
		$row["nombres"] = trim($row["nombres"]);
		$row["correo_electronico"] = trim($row["correo_electronico"]);
		$row["apellidos"] = str_replace("  ", " ", $row["apellidos"]);
		$row["nombres"] = str_replace("  ", " ", $row["nombres"]);
		$row["correo_electronico"] = str_replace("  ", " ", $row["correo_electronico"]);
		
		$sql  = "INSERT into usuario (id, usuario, pin, nivelusuario_id, apellidos, nombres, correo_electronico, updated) VALUES ";
		$sql .= "('{$row["id"]}','{$row["usuario"]}','{$row["pin"]}','{$row["nivelusuario_id"]}','{$row["apellidos"]}','{$row["nombres"]}','{$row["correo_electronico"]}','{$row["updated"]}');";
		sqlite_query($db, $sql);
	}
	pg_free_result($result);
	
	// Tabla elementos
	$query = "SELECT id, motor_id, sistema_id, subsistema_id, codigo, elemento_id, posicion_id, updated FROM sistema_subsistema_motor_elemento WHERE e = '1';";
	$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
	while ($row = pg_fetch_row($result, NULL, PGSQL_ASSOC)) {
		$row["codigo"] = trim($row["codigo"]);
		
		$sql  = "INSERT into sistema_subsistema_motor_elemento (id, motor_id, sistema_id, subsistema_id, codigo, elemento_id, posicion_id, updated) VALUES ";
		$sql .= "('{$row["id"]}','{$row["motor_id"]}','{$row["sistema_id"]}','{$row["subsistema_id"]}','{$row["codigo"]}','{$row["elemento_id"]}','{$row["posicion_id"]}','{$row["updated"]}');";
		sqlite_query($db, $sql);
	}
	pg_free_result($result);
	
	// Tabla elementos
	$query = "SELECT id, motor_id, sistema_id, subsistema_id, elemento_id, diagnostico_id, updated FROM motor_sistema_subsistema_elemento_diagnostico WHERE e = '1';";
	$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
	while ($row = pg_fetch_row($result, NULL, PGSQL_ASSOC)) {		
		$sql  = "INSERT into motor_sistema_subsistema_elemento_diagnostico (id, motor_id, sistema_id, subsistema_id, elemento_id, diagnostico_id, updated) VALUES ";
		$sql .= "('{$row["id"]}','{$row["motor_id"]}','{$row["sistema_id"]}','{$row["subsistema_id"]}','{$row["elemento_id"]}','{$row["diagnostico_id"]}','{$row["updated"]}');";
		sqlite_query($db, $sql);
	} 		
	pg_free_result($result);
	
	pg_close($dbconn);
	shell_exec("zip ../../webroot/bcZybqbNT7RyfGYWSLDd95VpExqHmp/Lh9FkYLVCCpkQjA9Uw7n683QY3LKgF/DBM-test.sqlite.zip DBM-test.sqlite");
	echo time() - $start;
	echo "\n";
?>