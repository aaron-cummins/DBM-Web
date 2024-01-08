<?php
	error_reporting(0);
?>
<?php if($intervencion['Planificacion']['estado']!=7&&$intervencion['Planificacion']['estado']!=6){ ?>
<style>
	select{
		background: #fff;
		cursor:normal;
	}

	select option{
		display:none;
	}
</style>
<?php } ?>
<?php
//	print_r($fechas);
?>
<script type="text/javascript">
$(document).ready(function(){
	var totalDelta1 = 0;
	var totalDelta2 = 0;
	var totalDelta3 = 0;
	var totalDelta4 = 0;
	var totalDelta5 = 0;
	var totalDelta6 = 0;
	
	/***
		Inicio verificacion de fechas para despliegue de deltas
	***/
	var hayDelta = false;
	$(".columna_delta_1").hide();
	if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
		if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
			var inicio = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
			var termino = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
			if(termino >= inicio) {
				$(".columna_delta_1").show();
				hayDelta = true;
			}
		}
	}
	$(".columna_delta_2").hide();
	if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
		if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
			var inicio = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
			var termino = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
			if(termino >= inicio) {
				$(".columna_delta_2").show();
				hayDelta = true;
			}
		}
	}
	$(".columna_delta_3").hide();
	<?php
	if ($intervencion["Planificacion"]["tipointervencion"] != "MP") {
	?>
	if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
		if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
			var inicio = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
			var termino = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
			if(termino >= inicio) {
				if($("#existe_elemento").val() == '1'){
					hayDelta = true;
					$(".columna_delta_3").show();
				}
				if($("#existe_elemento").val() == '0'){
					//hayDelta = false;
					$(".columna_delta_3").hide();
				}
			}
		}
	}
	<?php
	}
	?>
	$(".columna_delta_4").hide();
	if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
		if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
			var inicio = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
			var termino = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
			if(termino >= inicio) {
				$(".columna_delta_4").show();
				hayDelta = true;
			}
		}
	}
	$(".columna_delta_5").hide();
	if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
		if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
			var inicio = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
			var termino = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
			if(termino >= inicio) {
				$(".columna_delta_5").show();
				hayDelta = true;
			}
		}
	}
	$(".columna_delta_6").hide();
	<?php
	if ($intervencion["Planificacion"]["tipointervencion"] == "MP" && $intervencion["Planificacion"]["tipomantencion"] != "1500") {
	?>
	if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
		if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
			var inicio = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
			var termino = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
			if(termino >= inicio) {
				$(".columna_delta_6").show();
				hayDelta = true;
			}
		}
	}
	<?php
	}
	?>
	
	if(!hayDelta) {
		$(".despliegue_deltas").hide();
	}
	/***
		Fin verificacion de fechas para despliegue de deltas
	***/
	
	$(".imprimir").click(function(){
		var f = $(this).attr("f");
		window.open('/Planificacion/p/'+f, "Imprimir Folio", 'width=800,height=600,scrollbars=yes,menubar=no,toolbars=no');
		return false;
	});
	
	$(".elint").click(function(){
		return false;
		var f = $(this).attr("f");
		var e = $(this).attr("e"); 
		var h = $(this).attr("h");
		var p = $(this).attr("p");
		var mensaje = "";
		if (h != "0") {
			mensaje = " Además se eliminarán las intervenciones con folio " + h + ".";
		}
		
		if (e == "2" && p != "") {
			alert("Esta intervención no puede ser eliminada.");
			return false;	
		}
		
		if (e == "2") {
			if (confirm("La intervención con folio "+f+" será eliminada, ¿Confirma esta operación?" + mensaje)) {
				//window.location = '/Planificacion/elint/'+f;
				return false;
			} else {
				return false;
			}
		} else {
			if (confirm("La intervención con folio "+f+" será eliminada, ¿Confirma esta operación?"+mensaje)) {
				//window.location = '/Planificacion/elint/'+f;
				return false;
			} else {
				return false;
			}
		}
	});
	
	$("#flota_id").unbind("change");
	var json_string = '<?php echo str_replace(array('\\"',"'",'\\n','%','\\r','\\t'),array('','','','','',''),json_encode($json));?>';
	var json = JSON.parse(json_string);
	
	$('input[type=text]').each(function(){
	});
	
	$(".v_d").click(function(){
		var tiempo_total = 0;
		var fechas_validas = true;
		var delta_1_valido = true;
		var fecha_termino_global = "";
		var fecha_inicio_global = "";
		if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				if (i_t >= i_i) {
					tiempo_total += i_t - i_i;
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val();
					}
					fecha_termino_global = $("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val();
				}else{
					fechas_validas = false;
				}
			}
		}
		
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				if (i_t >= i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK i_i_f es mayor o igual a llegada_fecha " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val();
					}
					fecha_termino_global = $("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val();
				}else{
					console.log("NOK i_i_f no es mayor o igual a llegada_fecha");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK i_t_f es mayor a i_i_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val();
					}
					fecha_termino_global = $("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val();
				}else{
					console.log("NOK i_t_f no es menor o igual a i_i_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
				if (i_t >= i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK pp_i_f es mayor a i_t_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val();
					}
					fecha_termino_global = $("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val();
				}else{
					console.log("NOK pp_i_f no es menor o igual a i_t_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
			if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
				var i_i = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
				var i_t = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK pp_t_f es mayor a pp_i_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val();
					}
					fecha_termino_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
				}else{
					console.log("NOK pp_t_f no es menor o igual a pp_i_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#desc_f") != null && $("#desc_f") != undefined && $("#desc_f").val() != "" && $("#desc_f").val() != undefined) {
			if ($("#desct_f") != null && $("#desct_f") != undefined && $("#desct_f").val() != "" && $("#desct_f").val() != undefined) {
				var i_i = (new Date($("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val())).getTime()/60000;
				var i_t = (new Date($("#desct_f").val() + " " + $("#desct_h").val() + ":"+$("#desct_m").val() +":00 "+$("#desct_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK desct_f es mayor a desct_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val();
					}
					fecha_termino_global = $("#desct_f").val() + " " + $("#desct_h").val() + ":"+$("#desct_m").val() +":00 "+$("#desct_p").val();
				}else{
					console.log("NOK desct_f no es menor o igual a desct_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#desc_f") != null && $("#desc_f") != undefined && $("#desc_f").val() != "" && $("#desc_f").val() != undefined) {
			if ($("#cont_f") != null && $("#cont_f") != undefined && $("#cont_f").val() != "" && $("#cont_f").val() != undefined) {
				var i_i = (new Date($("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val())).getTime()/60000;
				var i_t = (new Date($("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val())).getTime()/60000;
				if (i_t >= i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK desc_f es mayor a cont_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
					}
					fecha_termino_global = $("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val();
				}else{
					console.log("NOK desc_f no es menor o igual a cont_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#con_f") != null && $("#con_f") != undefined && $("#con_f").val() != "" && $("#con_f").val() != undefined) {
			if ($("#cont_f") != null && $("#cont_f") != undefined && $("#cont_f").val() != "" && $("#cont_f").val() != undefined) {
				var i_i = (new Date($("#con_f").val() + " " + $("#con_h").val() + ":"+$("#con_m").val() +":00 "+$("#con_p").val())).getTime()/60000;
				var i_t = (new Date($("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK cont_f es mayor a con_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#con_f").val() + " " + $("#con_h").val() + ":"+$("#con_m").val() +":00 "+$("#con_p").val();
					}
					fecha_termino_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
				}else{
					console.log("NOK cont_f no es menor o igual a con_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#pm_i_f") != null && $("#pm_i_f") != undefined && $("#pm_i_f").val() != "" && $("#pm_i_f").val() != undefined) {
			if ($("#cont_f") != null && $("#cont_f") != undefined && $("#cont_f").val() != "" && $("#cont_f").val() != undefined) {
				var i_i = (new Date($("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val())).getTime()/60000;
				var i_t = (new Date($("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val())).getTime()/60000;
				if (i_t >= i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK pm_i_f es mayor a cont_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
					}
					fecha_termino_global = $("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val();
				}else{
					console.log("NOK pm_i_f no es menor o igual a cont_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#pm_i_f") != null && $("#pm_i_f") != undefined && $("#pm_i_f").val() != "" && $("#pm_i_f").val() != undefined) {
			if ($("#pm_t_f") != null && $("#pm_t_f") != undefined && $("#pm_t_f").val() != "" && $("#pm_t_f").val() != undefined) {
				var i_i = (new Date($("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val())).getTime()/60000;
				var i_t = (new Date($("#pm_t_f").val() + " " + $("#pm_t_h").val() + ":"+$("#pm_t_m").val() +":00 "+$("#pm_t_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK pm_t_f es mayor a pm_i_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val();
					}
					fecha_termino_global = $("#pm_t_f").val() + " " + $("#pm_t_h").val() + ":"+$("#pm_t_m").val() +":00 "+$("#pm_t_p").val();
				}else{
					console.log("NOK pm_t_f no es menor o igual a pm_i_f");
					fechas_validas = false;
				}
			}
		}
		
		if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
			if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
				var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
				var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
				if (i_t > i_i) {
					tiempo_total += i_t - i_i;
					console.log("OK repro_f es mayor a pp_t_f " + (i_t - i_i));
					if(fecha_inicio_global==''){
						fecha_inicio_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
					}
					fecha_termino_global = $("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val();
				}else{
					console.log("NOK repro_f no es menor o igual a pp_t_f");
					fechas_validas = false;
				}
			}
		}
		
		if(!fechas_validas){
			alert("Las fechas seleccionadas no son correctas, cada una debe ser mayor que la anterior.");
			console.log("Las fechas ingresadas no son válidas.");
			return false;
		}
		if(totalDelta1 != 0) {
			alert("La información ingresada en Delta 1 no es correcta, por favor revisar.");
			console.log("Delta1 incorrecto");
			return false;
		}
		if(totalDelta2 != 0) {
			alert("La información ingresada en Delta 2 no es correcta, por favor revisar.");
			console.log("Delta2 incorrecto");
			return false;
		}
		if ($("#tipo_intervencion").val() != 'MP' && $("#existe_elemento").val() == '1') {
			if(totalDelta3 != 0) {
				alert("La información ingresada en Delta Intervención no es correcta, por favor revisar.");
				console.log("Delta3 incorrecto");
				return false;
			}
		}
		
		if(totalDelta4 != 0) {
			alert("La información ingresada en Delta 4 no es correcta, por favor revisar.");
			console.log("Delta4 incorrecto");
			return false;
		}
		
		if(totalDelta5 != 0) {
			alert("La información ingresada en Delta 5 no es correcta, por favor revisar.");
			console.log("Delta5 incorrecto");
			return false;
		}
		if ($("#tipo_intervencion").val() == 'MP') {
			if(totalDelta6 != 0) {
				alert("La información ingresada en Delta Mantención no es correcta, por favor revisar.");
				console.log("Delta6 incorrecto");
				return false;
			}
		}
		
		// Validacion de responsables por delta
		// Delta 1
		if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var d = 0;
				d = parseInt($("#d_traslado_dcc_h").val()) * 60 + parseInt($("#d_traslado_dcc_m").val());
				if (d > 0 && $("#d_traslado_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_traslado_oem_h").val()) * 60 + parseInt($("#d_traslado_oem_m").val());
				if (d > 0 && $("#d_traslado_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_tronadura_h").val()) * 60 + parseInt($("#d_tronadura_m").val());
				if (d > 0 && $("#d_tronadura_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_clima_h").val()) * 60 + parseInt($("#d_clima_m").val());
				if (d > 0 && $("#d_clima_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_logistica_dcc_h").val()) * 60 + parseInt($("#d_logistica_dcc_m").val());
				if (d > 0 && $("#d_logistica_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_logistica_oem_h").val()) * 60 + parseInt($("#d_logistica_oem_m").val());
				if (d > 0 && $("#d_logistica_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_personal_h").val()) * 60 + parseInt($("#d_personal_m").val());
				if (d > 0 && $("#d_personal_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
			}
		}
		
		// Delta 2
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var d = 0;
				d = parseInt($("#d2_tronadura_h").val()) * 60 + parseInt($("#d2_tronadura_m").val());
				if (d > 0 && $("#d2_tronadura_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_clima_h").val()) * 60 + parseInt($("#d2_clima_m").val());
				if (d > 0 && $("#d2_clima_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_logistica_dcc_h").val()) * 60 + parseInt($("#d2_logistica_dcc_m").val());
				if (d > 0 && $("#d2_logistica_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_logistica_oem_h").val()) * 60 + parseInt($("#d2_logistica_oem_m").val());
				if (d > 0 && $("#d2_logistica_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d_logistica_dcc_h").val()) * 60 + parseInt($("#d_logistica_dcc_m").val());
				if (d > 0 && $("#d_logistica_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_cliente_h").val()) * 60 + parseInt($("#d2_cliente_m").val());
				if (d > 0 && $("#d2_cliente_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_oem_h").val()) * 60 + parseInt($("#d2_oem_m").val());
				if (d > 0 && $("#d2_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_zona_segura_h").val()) * 60 + parseInt($("#d2_zona_segura_m").val());
				if (d > 0 && $("#d2_zona_segura_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d2_charla_h").val()) * 60 + parseInt($("#d2_charla_m").val());
				if (d > 0 && $("#d2_charla_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
			}
		}
		
		// Delta 3
		if ($("#tipo_intervencion").val() != 'MP') {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
					var d = 0;
					d = parseInt($("#d3_cliente_h").val()) * 60 + parseInt($("#d3_cliente_m").val());
					if (d > 0 && $("#d3_cliente_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_oem_h").val()) * 60 + parseInt($("#d3_oem_m").val());
					if (d > 0 && $("#d3_oem_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_personal_dcc_h").val()) * 60 + parseInt($("#d3_personal_dcc_m").val());
					if (d > 0 && $("#d3_personal_dcc_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_fluidos_h").val()) * 60 + parseInt($("#d3_fluidos_m").val());
					if (d > 0 && $("#d3_fluidos_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_reparacion_diagnostico_h").val()) * 60 + parseInt($("#d3_reparacion_diagnostico_m").val());
					if (d > 0 && $("#d3_reparacion_diagnostico_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_repuestos_h").val()) * 60 + parseInt($("#d3_repuestos_m").val());
					if (d > 0 && $("#d3_repuestos_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_herramientas_dcc_h").val()) * 60 + parseInt($("#d3_herramientas_dcc_m").val());
					if (d > 0 && $("#d3_herramientas_dcc_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d3_herramientas_panol_h").val()) * 60 + parseInt($("#d3_herramientas_panol_m").val());
					if (d > 0 && $("#d3_herramientas_panol_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
				}
			}
		}
		
		// Delta 4
		if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				var d = 0;
				d = parseInt($("#d4_tronadura_h").val()) * 60 + parseInt($("#d4_tronadura_m").val());
				if (d > 0 && $("#d4_tronadura_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_clima_h").val()) * 60 + parseInt($("#d4_clima_m").val());
				if (d > 0 && $("#d4_clima_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_logistica_dcc_h").val()) * 60 + parseInt($("#d4_logistica_dcc_m").val());
				if (d > 0 && $("#d4_logistica_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_cliente_h").val()) * 60 + parseInt($("#d4_cliente_m").val());
				if (d > 0 && $("#d4_cliente_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_oem_h").val()) * 60 + parseInt($("#d4_oem_m").val());
				if (d > 0 && $("#d4_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_operador_h").val()) * 60 + parseInt($("#d4_operador_m").val());
				if (d > 0 && $("#d4_operador_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d4_infraestructura_h").val()) * 60 + parseInt($("#d4_infraestructura_m").val());
				if (d > 0 && $("#d4_infraestructura_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
			}
		}
		
		// Delta 5
		if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
			if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
				var d = 0;
				d = parseInt($("#d5_cliente_h").val()) * 60 + parseInt($("#d5_cliente_m").val());
				if (d > 0 && $("#d5_cliente_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_oem_h").val()) * 60 + parseInt($("#d5_oem_m").val());
				if (d > 0 && $("#d5_oem_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_personal_dcc_h").val()) * 60 + parseInt($("#d5_personal_dcc_m").val());
				if (d > 0 && $("#d5_personal_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_fluidos_h").val()) * 60 + parseInt($("#d5_fluidos_m").val());
				if (d > 0 && $("#d5_fluidos_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_reparacion_diagnostico_h").val()) * 60 + parseInt($("#d5_reparacion_diagnostico_m").val());
				if (d > 0 && $("#d5_reparacion_diagnostico_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_repuestos_h").val()) * 60 + parseInt($("#d5_repuestos_m").val());
				if (d > 0 && $("#d5_repuestos_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_herramientas_dcc_h").val()) * 60 + parseInt($("#d5_herramientas_dcc_m").val());
				if (d > 0 && $("#d5_herramientas_dcc_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
				d = parseInt($("#d5_herramientas_panol_h").val()) * 60 + parseInt($("#d5_herramientas_panol_m").val());
				if (d > 0 && $("#d5_herramientas_panol_r").val() == '0') {
					alert("Debe asignar un responsable a todos los deltas ingresados.");
					return false;
				}
			}
		}
		
		// Delta 6
		if ($("#tipo_intervencion").val() == 'MP') {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
					var d = 0;
					d = parseInt($("#d6_cliente_h").val()) * 60 + parseInt($("#d6_cliente_m").val());
					if (d > 0 && $("#d6_cliente_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_oem_h").val()) * 60 + parseInt($("#d6_oem_m").val());
					if (d > 0 && $("#d6_oem_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_charla_h").val()) * 60 + parseInt($("#d6_charla_m").val());
					if (d > 0 && $("#d6_charla_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_personal_dcc_h").val()) * 60 + parseInt($("#d6_personal_dcc_m").val());
					if (d > 0 && $("#d6_personal_dcc_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_fluidos_h").val()) * 60 + parseInt($("#d6_fluidos_m").val());
					if (d > 0 && $("#d6_fluidos_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_mantencion_h").val()) * 60 + parseInt($("#d6_mantencion_m").val());
					if (d > 0 && $("#d6_mantencion_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_repuestos_h").val()) * 60 + parseInt($("#d6_repuestos_m").val());
					if (d > 0 && $("#d6_repuestos_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_herramientas_panol_h").val()) * 60 + parseInt($("#d6_herramientas_panol_m").val());
					if (d > 0 && $("#d6_herramientas_panol_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_operador_h").val()) * 60 + parseInt($("#d6_operador_m").val());
					if (d > 0 && $("#d6_operador_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
					d = parseInt($("#d6_infraestructura_h").val()) * 60 + parseInt($("#d6_infraestructura_m").val());
					if (d > 0 && $("#d6_infraestructura_r").val() == '0') {
						alert("Debe asignar un responsable a todos los deltas ingresados.");
						return false;
					}
				}
			}
		}
		
		console.log("Tiempo total ingresado: " + tiempo_total);
		$("#tiempo_trabajo").val(tiempo_total);
		$("#fecha_inicio_global").val(fecha_inicio_global);
		$("#fecha_termino_global").val(fecha_termino_global); 
		
		
		if ($(this).val() == "Guardar") {
			if (!confirm("¿Realmente desea guardar los cambios ingresados?")) {
				return false;
			}
		} else if ($(this).val() == "Siguiente") {
			if (!confirm("¿Realmente desea guardar los cambios ingresados y continuar a la siguiente página?")) {
				return false;
			}
		}
	
		return true;
	});
	
	$("#llamado_fecha,#llamado_hora,#llamado_min,#llamado_periodo,#llegada_fecha,#llegada_hora,#llegada_min,#llegada_periodo").change(function(){
		if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				var m = i_t - i_i;
				var hours = Math.floor(m / 60);          
				var minutes = m % 60;
				$(".delta1_duracion").text(hours+"h "+minutes+"m")
				$("#d_traslado_dcc_h").change();
			}
		}
	});
	$("#llamado_fecha").change();
	$("#i_i_f,#i_i_h,#i_i_m,#i_i_p,#llegada_fecha,#llegada_hora,#llegada_min,#llegada_periodo").change(function(){
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
				var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				var m = i_t - i_i;
				var hours = Math.floor(m / 60);          
				var minutes = m % 60;
				$(".delta2_duracion").text(hours+"h "+minutes+"m")
				$("#d2_tronadura_h").change();
			}
		}
	});
	$("#i_i_f").change();
	$("#i_i_f,#i_i_h,#i_i_m,#i_i_p,#i_t_f,#i_t_h,#i_t_m,#i_t_p").change(function(){
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
				var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				var m = i_t - i_i;
				var hours = Math.floor(m / 60);          
				var minutes = m % 60;
				if ($("#tipo_intervencion").val() != 'MP') {
					$(".delta3_duracion").text(hours+"h "+minutes+"m")
					$("#d3_cliente_h").change();
				} else {
					$(".delta6_duracion").text(hours+"h "+minutes+"m")
					$("#d6_cliente_h").change();
				}
			}
		}
	});
	$("#i_t_f").change();
	
	$("#pp_i_f,#pp_i_h,#pp_i_m,#pp_i_p,#i_t_f,#i_t_h,#i_t_m,#i_t_p").change(function(){
		if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
				var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
				var m = i_t - i_i;
				var hours = Math.floor(m / 60);          
				var minutes = m % 60;
				$(".delta4_duracion").text(hours+"h "+minutes+"m")
				$("#d4_tronadura_h").change();
			}
		}
	});
	$("#pp_i_f").change();
	
	$("#pp_t_f,#pp_t_h,#pp_t_m,#pp_t_p,#repro_f,#repro_h,#repro_m,#repro_p").change(function(){
		if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
			if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
				var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
				var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
				var m = i_t - i_i;
				var hours = Math.floor(m / 60);          
				var minutes = m % 60;
				$(".delta5_duracion").text(hours+"h "+minutes+"m")
				$("#d5_cliente_h").change();
			}
		}
	});
	$("#pp_t_f").change();				
	
	$("input,select").change(function(){
		// Delta 1
		if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				if($(this).attr("id") != undefined && $(this).attr("id").substr(0,2) == "d_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
					var d = 0;
					d += parseInt($("#d_traslado_dcc_h").val()) * 60 + parseInt($("#d_traslado_dcc_m").val());
					d += parseInt($("#d_traslado_oem_h").val()) * 60 + parseInt($("#d_traslado_oem_m").val());
					d += parseInt($("#d_tronadura_h").val()) * 60 + parseInt($("#d_tronadura_m").val());
					d += parseInt($("#d_clima_h").val()) * 60 + parseInt($("#d_clima_m").val());
					d += parseInt($("#d_logistica_dcc_h").val()) * 60 + parseInt($("#d_logistica_dcc_m").val());
					d += parseInt($("#d_logistica_oem_h").val()) * 60 + parseInt($("#d_logistica_oem_m").val());
					d += parseInt($("#d_personal_h").val()) * 60 + parseInt($("#d_personal_m").val());
					var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
					var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
					var m = i_t - i_i;
					d = m - d;
					totalDelta1 = d;
					var hours = Math.floor(d / 60);          
					var minutes = d % 60;
					if(hours==0&&minutes==0){
						$("#d1_ing").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$("#d1_ing").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$("#d1_ing").text("Faltan "+hours+"h por ingresar");	
						}else{
							$("#d1_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$("#d1_ing").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$("#d1_ing").text("Hay "+hours+"h adicional ingresada");
						}else{
							$("#d1_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				}
			}
		}
		
		// Delta 2
		if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
			if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
				if($(this).attr("id") != undefined && $(this).attr("id").substr(0,3) == "d2_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
					var d = 0;
					d += parseInt($("#d2_tronadura_h").val()) * 60 + parseInt($("#d2_tronadura_m").val());
					d += parseInt($("#d2_clima_h").val()) * 60 + parseInt($("#d2_clima_m").val());
					d += parseInt($("#d2_logistica_dcc_h").val()) * 60 + parseInt($("#d2_logistica_dcc_m").val());
					d += parseInt($("#d2_logistica_oem_h").val()) * 60 + parseInt($("#d2_logistica_oem_m").val());
					d += parseInt($("#d2_cliente_h").val()) * 60 + parseInt($("#d2_cliente_m").val());
					d += parseInt($("#d2_oem_h").val()) * 60 + parseInt($("#d2_oem_m").val());
					d += parseInt($("#d2_zona_segura_h").val()) * 60 + parseInt($("#d2_zona_segura_m").val());
					d += parseInt($("#d2_charla_h").val()) * 60 + parseInt($("#d2_charla_m").val());
					var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
					var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
					var m = i_t - i_i;
					d = m - d;
					totalDelta2 = d;
					var hours = Math.floor(d / 60);          
					var minutes = d % 60;
					if(hours==0&&minutes==0){
						$("#d2_ing").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$("#d2_ing").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$("#d2_ing").text("Faltan "+hours+"h por ingresar");	
						}else{
							$("#d2_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$("#d2_ing").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$("#d2_ing").text("Hay "+hours+"h adicional ingresada");
						}else{
							$("#d2_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				}
			}
		}
		
		// Delta 3
		if ($("#tipo_intervencion").val() != 'MP' && $("#existe_elemento").val() == '1') {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
					if($(this).attr("id") != undefined && $(this).attr("id").substr(0,3) == "d3_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
						var d = 0;
						d += parseInt($("#d3_cliente_h").val()) * 60 + parseInt($("#d3_cliente_m").val());
						d += parseInt($("#d3_oem_h").val()) * 60 + parseInt($("#d3_oem_m").val());
						d += parseInt($("#d3_personal_dcc_h").val()) * 60 + parseInt($("#d3_personal_dcc_m").val());
						d += parseInt($("#d3_fluidos_h").val()) * 60 + parseInt($("#d3_fluidos_m").val());
						d += parseInt($("#d3_reparacion_diagnostico_h").val()) * 60 + parseInt($("#d3_reparacion_diagnostico_m").val());
						d += parseInt($("#d3_repuestos_h").val()) * 60 + parseInt($("#d3_repuestos_m").val());
						d += parseInt($("#d3_herramientas_dcc_h").val()) * 60 + parseInt($("#d3_herramientas_dcc_m").val());
						d += parseInt($("#d3_herramientas_panol_h").val()) * 60 + parseInt($("#d3_herramientas_panol_m").val());
						var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
						var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
						var m = i_t - i_i;
						d = m - d;
						totalDelta3 = d;
						var hours = Math.floor(d / 60);          
						var minutes = d % 60;
						if(hours==0&&minutes==0){ 
							$("#d3_ing").text("Todo el tiempo está asignado");
						}else if (hours>=0&&minutes>=0){
							if(hours==0){
								$("#d3_ing").text("Faltan "+minutes+"m por ingresar");
							}else if(minutes==0){
								$("#d3_ing").text("Faltan "+hours+"h por ingresar");	
							}else{
								$("#d3_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
							}
						}else if(hours<=0&&minutes<=0){
							if(minutes!=0){
								hours = hours + 1;
							}
							hours = hours * -1;
							minutes = minutes * -1;
							if(hours==0){
								$("#d3_ing").text("Hay "+minutes+"m adicionales ingresado");
							}else if(minutes==0){
								$("#d3_ing").text("Hay "+hours+"h adicional ingresada");
							}else{
								$("#d3_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
							}
						}
					}
				}
			}
		}
		
		// Delta 4
		if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
			if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
				if($(this).attr("id") != undefined && $(this).attr("id").substr(0,3) == "d4_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
					var d = 0;
					d += parseInt($("#d4_tronadura_h").val()) * 60 + parseInt($("#d4_tronadura_m").val());
					d += parseInt($("#d4_clima_h").val()) * 60 + parseInt($("#d4_clima_m").val());
					d += parseInt($("#d4_logistica_dcc_h").val()) * 60 + parseInt($("#d4_logistica_dcc_m").val());
					d += parseInt($("#d4_cliente_h").val()) * 60 + parseInt($("#d4_cliente_m").val());
					d += parseInt($("#d4_oem_h").val()) * 60 + parseInt($("#d4_oem_m").val());
					d += parseInt($("#d4_operador_h").val()) * 60 + parseInt($("#d4_operador_m").val());
					d += parseInt($("#d4_infraestructura_h").val()) * 60 + parseInt($("#d4_infraestructura_m").val());
					var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
					var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
					var m = i_t - i_i;
					d = m - d;
					totalDelta4 = d;
					var hours = Math.floor(d / 60);          
					var minutes = d % 60;
					if(hours==0&&minutes==0){ 
						$("#d4_ing").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$("#d4_ing").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$("#d4_ing").text("Faltan "+hours+"h por ingresar");	
						}else{
							$("#d4_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$("#d4_ing").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$("#d4_ing").text("Hay "+hours+"h adicional ingresada");
						}else{
							$("#d4_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				}
			}
		}
		
		// Delta 5
		if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
			if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
				if($(this).attr("id") != undefined && $(this).attr("id").substr(0,3) == "d5_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
					var d = 0;
					d += parseInt($("#d5_cliente_h").val()) * 60 + parseInt($("#d5_cliente_m").val());
					d += parseInt($("#d5_oem_h").val()) * 60 + parseInt($("#d5_oem_m").val());
					d += parseInt($("#d5_personal_dcc_h").val()) * 60 + parseInt($("#d5_personal_dcc_m").val());
					d += parseInt($("#d5_fluidos_h").val()) * 60 + parseInt($("#d5_fluidos_m").val());
					d += parseInt($("#d5_reparacion_diagnostico_h").val()) * 60 + parseInt($("#d5_reparacion_diagnostico_m").val());
					d += parseInt($("#d5_repuestos_h").val()) * 60 + parseInt($("#d5_repuestos_m").val());
					d += parseInt($("#d5_herramientas_dcc_h").val()) * 60 + parseInt($("#d5_herramientas_dcc_m").val());
					d += parseInt($("#d5_herramientas_panol_h").val()) * 60 + parseInt($("#d5_herramientas_panol_m").val());
					var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
					var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
					var m = i_t - i_i;
					d = m - d;
					totalDelta5 = d;
					var hours = Math.floor(d / 60);          
					var minutes = d % 60;
					if(hours==0&&minutes==0){ 
						$("#d5_ing").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$("#d5_ing").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$("#d5_ing").text("Faltan "+hours+"h por ingresar");	
						}else{
							$("#d5_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$("#d5_ing").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$("#d5_ing").text("Hay "+hours+"h adicional ingresada");
						}else{
							$("#d5_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				}
			}
		}
		
		// Delta 6
		if ($("#tipo_intervencion").val() == 'MP') {
			if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
				if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
					if($(this).attr("id") != undefined && $(this).attr("id").substr(0,3) == "d6_" && ($(this).attr("id").substr($(this).attr("id").length-2)=="_h"||$(this).attr("id").substr($(this).attr("id").length-2)=="_m")){
						var d = 0;
						d += parseInt($("#d6_cliente_h").val()) * 60 + parseInt($("#d6_cliente_m").val());
						d += parseInt($("#d6_oem_h").val()) * 60 + parseInt($("#d6_oem_m").val());
						d += parseInt($("#d6_charla_h").val()) * 60 + parseInt($("#d6_charla_m").val());
						d += parseInt($("#d6_personal_dcc_h").val()) * 60 + parseInt($("#d6_personal_dcc_m").val());
						d += parseInt($("#d6_fluidos_h").val()) * 60 + parseInt($("#d6_fluidos_m").val());
						d += parseInt($("#d6_mantencion_h").val()) * 60 + parseInt($("#d6_mantencion_m").val());
						d += parseInt($("#d6_repuestos_h").val()) * 60 + parseInt($("#d6_repuestos_m").val());
						d += parseInt($("#d6_herramientas_panol_h").val()) * 60 + parseInt($("#d6_herramientas_panol_m").val());
						d += parseInt($("#d6_operador_h").val()) * 60 + parseInt($("#d6_operador_m").val());
						d += parseInt($("#d6_infraestructura_h").val()) * 60 + parseInt($("#d6_infraestructura_m").val());
						var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
						var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
						var m = i_t - i_i;
						d = m - d;
						totalDelta6 = d;
						var hours = Math.floor(d / 60);          
						var minutes = d % 60;
						if(hours==0&&minutes==0){ 
							$("#d6_ing").text("Todo el tiempo está asignado");
						}else if (hours>=0&&minutes>=0){
							if(hours==0){
								$("#d6_ing").text("Faltan "+minutes+"m por ingresar");
							}else if(minutes==0){
								$("#d6_ing").text("Faltan "+hours+"h por ingresar");	
							}else{
								$("#d6_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
							}
						}else if(hours<=0&&minutes<=0){
							if(minutes!=0){
								hours = hours + 1;
							}
							hours = hours * -1;
							minutes = minutes * -1;
							if(hours==0){
								$("#d6_ing").text("Hay "+minutes+"m adicionales ingresado");
							}else if(minutes==0){
								$("#d6_ing").text("Hay "+hours+"h adicional ingresada");
							}else{
								$("#d6_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
							}
						}
					}
				}
			}
		}
		
	});
	
	$("select").change();
	$("input").change();
	
	<?php if($intervencion['Planificacion']['estado']!=7&&$intervencion['Planificacion']['estado']!=6){ ?>
		$(".agregar_tec_extra").hide();
		<?php if($this->Session->read('esAdmin')!='1'){ ?>
		$("input[type=text]").attr("readonly","readonly");
		$("input[type=date]").attr("readonly","readonly");
		$("input[type=number]").attr("readonly","readonly");
		$("textarea").attr("readonly","readonly");
		$('select option:not(:selected)').attr('disabled',true);
		<?php } ?>
	<?php 
	}
	$d=@$_GET["d"];
?>

	$(".desaprobar_intervencion").click(function(){
		if(confirm("¿Está seguro que desea desaprobar esta intervención?")){
			var folio = $("#id").val();
			window.location = '/Trabajo/desaprobar/'+folio;
			return false;
		}
	});
	
	<?php
	if($intervencion["Planificacion"]["tipointervencion"]=='MP'){
	?>
		//$("#tipo_intervencion").attr("disabled","disabled");
	<?php
	}
	?>
});
</script>
  <div class="titleArea" style="padding-top: 0;">
      <div class="wrapper">
            <div class="pageTitle">
                <h5>Detalle de la Intervención <?php echo $intervencion["Planificacion"]["tipointervencion"];?></h5>
				<span>Información ingresada por el Técnico</span>
            </div>
          <div class="clear"></div>
        </div>
    </div>
    <div class="line"></div>
<div class="wrapper">
<form action="/Trabajo/index/<?php echo $intervencion["Planificacion"]["id"];?>" method="POST">
	<input type="hidden" name="id" id="id" value="<?php echo $intervencion["Planificacion"]["id"];?>" />
	<input type="hidden" name="folio" id="folio" value="<?php echo $intervencion["Planificacion"]["folio"];?>" />
	<input type="hidden" name="id_fechas" id="id_fechas" value="<?php echo $fechas["IntervencionFechas"]["id"];?>" />
	<input type="hidden" name="id_comentarios" id="id_comentarios" value="<?php echo $comentarios["id"];?>" />
	<input type="hidden" name="tiempo_trabajo" id="tiempo_trabajo" value="" />
	<input type="hidden" name="fecha_inicio_global" id="fecha_inicio_global" value="" />
	<input type="hidden" name="fecha_termino_global" id="fecha_termino_global" value="" />
	<input type="hidden" name="faena_id" id="faena_id" value="<?php echo $intervencion["Planificacion"]["faena_id"];?>" />
	<div class="widget">
	<div class="title show" show="tecnicos" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Técnicos</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable tecnicos" style="display: none;"> 
		 <tr>
			<td style="width: 300px; overflow: hidden;">Técnico</td>
			<td>
			<select name="UserID" id="UserID" class="tecnico_soporte" style="width: 300px; overflow: hidden;">
				<?php foreach ($usuarios as $usuario) { ?>
					<option value="<?php echo $usuario["Usuario"]["id"]; ?>" <?php echo $json["UserID"] == $usuario["Usuario"]["id"] ? 'selected="selected"':'';?>>
						<?php echo $usuario["Usuario"]["apellidos"] . ' ' . $usuario["Usuario"]["nombres"]; ?>
					</option>
				<?php } ?>
			</select>
			<?php /*if($intervencion['Planificacion']['estado']==7||$intervencion['Planificacion']['estado']==6){ ?>
			<div style="float: right; width: 16px; height: 16px; margin-top: 6px;margin-right: 6px; cursor: pointer;" class="agregar_tec_extra">
				<img src="/images/icons/color/plus.png" title="Agregar técnico" />
			</div>
			<?php }*/ ?>
			</td>
			<td>
				<select disabled="disabled">
					<option value="0"<?php if($json["TecnicoApoyoTipo01"]=='0'){echo ' selected="selected"';} ?>>Lider</option>
					<option value="1"<?php if($json["TecnicoApoyoTipo01"]=='1'){echo ' selected="selected"';} ?>>Apoyo</option>
				</select>
			</td>
		  </tr>
        <?php
		  for ($i = 2; $i < 11; $i++) {
			if (isset($json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))]) && is_numeric($json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))])) {
		?>
		<tr class="tecnico_<?php echo $i;?>">
			<td>Técnico</td>
			<td>
			<select name="TecnicoApoyo<?php echo (str_pad($i,2,"0",STR_PAD_LEFT));?>" id="TecnicoApoyo<?php echo (str_pad($i,2,"0",STR_PAD_LEFT));?>" num="<?php echo $i;?>" class="tecnico_soporte" style="width: 300px; overflow: hidden;">
				<option value="">N/A</option>
				<?php foreach ($usuarios as $usuario) { ?>
				<option value="<?php echo $usuario["Usuario"]["id"]; ?>" <?php echo $json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))] == $usuario["Usuario"]["id"] ? 'selected="selected"':'';?>>
					<?php echo $usuario["Usuario"]["apellidos"] . ' ' . $usuario["Usuario"]["nombres"]; ?>
				</option>
				<?php } ?>
			</select>
				<?php /*if($intervencion['Planificacion']['estado']==7||$intervencion['Planificacion']['estado']==6){ ?>
				<div style="float: right; width: 16px; height: 16px; margin-top: 6px;margin-right: 6px; cursor: pointer;" class="quitar_tec_extra quitar_tec_<?php echo $i;?>" num="<?php echo $i;?>">
				<img src="/images/icons/color/cross.png" title="Quitar técnico" />
				</div>
				<?php } */?>
			</td>
			<td>
				<select disabled="disabled">
					<option value="0"<?php if($json["TipoTecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))]=='0'){echo ' selected="selected"';} ?>>Lider</option>
					<option value="1"<?php if($json["TipoTecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))]=='1'){echo ' selected="selected"';} ?>>Apoyo</option>
				</select>
			</td>
		</tr>
		<?php
			}
		}
		?>
		<?php
		  for ($j = $i; $j < 7; $j++) {
		?>
		<tr style="display: none;" class="tecnico_<?php echo $j;?>">
			<td>Técnico</td>
			<td>
			<select name="TecnicoApoyo<?php echo (str_pad($i,2,"0",STR_PAD_LEFT));?>" class="tecnico_soporte" num="<?php echo $j;?>" id="TecnicoApoyo<?php echo (str_pad($i,2,"0",STR_PAD_LEFT));?>" style="width: 300px; overflow: hidden;">
				<option value="">N/A</option>
				<?php foreach ($usuarios as $usuario) { ?>
				<option value="<?php echo $usuario["Usuario"]["id"]; ?>"><?php echo $usuario["Usuario"]["apellidos"] . ' ' . $usuario["Usuario"]["nombres"]; ?></option>
				<?php } ?>
			</select>
			<?php if($intervencion['Planificacion']['estado']==7||$intervencion['Planificacion']['estado']==6){ ?>			
			<div style="float: right; width: 16px; height: 16px; margin-top: 6px;margin-right: 6px; cursor: pointer;" class="quitar_tec_extra quitar_tec_<?php echo $j;?>" num="<?php echo $j;?>">
				<img src="/images/icons/color/cross.png" title="Quitar técnico" />
			</div>
			<?php } ?>
			</td>
		</tr>
		<?php
		}
		?>
		</table>
		<input type="hidden" class="num_tecnicos" value="<?php echo --$i;?>" />
	</div>
<div class="widget">
<div class="title show" show="informacion" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Información Base</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable informacion" style="display: none;">
<tr>
    <td style="width: 300px; overflow: hidden;">Folio</td>
    <td>
		<input type="text" readonly="readonly" value="<?php echo $intervencion["Planificacion"]["id"];?>" />
	</td>
  </tr>
<tr>
    <td style="width: 300px; overflow: hidden;">Tipo Intervención</td>
    <td>
		<select name="tipointervencion" id="tipo_intervencion" style="width: 300px; overflow: hidden;">
			<?php
				if($intervencion["Planificacion"]["tipointervencion"] == 'MP'){
			?>
			<option value="MP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'MP' ? ' selected="selected"':'';?>>MP</option>
			<?php
				} else {
			?>
				<option value="EX"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'EX' ? ' selected="selected"':'';?>>EX</option>
				<option value="RI"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'RI' ? ' selected="selected"':'';?>>RI</option>
				<option value="RP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'RP' ? ' selected="selected"':'';?>>RP</option>
				<option value="OP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'OP' || $intervencion["Planificacion"]["tipointervencion"] == 'BL' ? ' selected="selected"':'';?>>OP</option>
			<?php
				}
			?>
		</select>
	</td>
  </tr>
 <tr>
    <td style="width: 300px; overflow: hidden;">Flota</td>
    <td>
		<select style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="<?php echo $intervencion["Planificacion"]["flota_id"];?>" selected="selected"><?php echo $intervencion["Flota"]["nombre"];?></option>
		</select>
	</td>
  </tr>
  <tr>
    <td>Equipo</td>
    <td>
		<select style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="<?php echo $intervencion["Planificacion"]["unidad_id"];?>" selected="selected"><?php echo $intervencion["Unidad"]["unidad"];?></option>
		</select>
	</td>
  </tr>
  <tr>
    <td>ESN</td>
    <td>
		<input type="text" value="<?php 
		$esn_ = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
		echo $esn_;
		$esn = "";
		if($esn_==''){
			if (@$json["esn_conexion"] != "") {
				$esn =  @$json["esn_conexion"];
			} elseif (@$json["esn_nuevo"] != "") {
				$esn = @$json["esn_nuevo"];
			} elseif (@$json["esn"] != "") {
				$esn =  @$json["esn"];
			} else {
				$esn =  $intervencion['Planificacion']['esn'];
			}
			
			if (is_numeric($esn)) {
				echo $esn;
			} else {
				echo $util->esnPadre($intervencion['Planificacion']['padre']);
			}
		}
		?>" readonly="readonly" />
	</td>
  </tr>
  <?php if($intervencion["Planificacion"]["tipointervencion"] != 'MP'){ ?>
   <tr>
    <td>Motivo de llamado</td>
    <td>
		<select name="MotivoID" id="MotivoID" style="width: 300px; overflow: hidden;">
			<option value="" disabled="disabled"></option>
			<option <?php echo @$json["MotivoID"] == 'OP' ? ' selected="selected"':'';?> value="OP">Operador</option>
			<option <?php echo @$json["MotivoID"] == 'FC' ? ' selected="selected"':'';?> value="FC">FC</option>
			<option <?php echo @$json["MotivoID"] == 'OT' ? ' selected="selected"':'';?> value="OT">Oportunidad</option>
		</select>
	</td>
  </tr>
   <tr>
    <td>Categoría</td>
    <td>
		<select name="CategoriaID" id="CategoriaID" style="width: 300px; overflow: hidden;" CategoriaID_id="<?php echo @$json["CategoriaID"];?>">			
			<option value="" disabled="disabled"></option>
			<option <?php echo @$json["CategoriaID"] == '19' ? ' selected="selected"':'';?> value="19">Aceite</option>
			<option <?php echo @$json["CategoriaID"] == '22' ? ' selected="selected"':'';?> value="22">Adminisión</option>
			<option <?php echo @$json["CategoriaID"] == '21' ? ' selected="selected"':'';?> value="21">Combustible</option>
			<option <?php echo @$json["CategoriaID"] == '23' ? ' selected="selected"':'';?> value="23">FC</option>
			<option <?php echo @$json["CategoriaID"] == '16' ? ' selected="selected"':'';?> value="16">Motor</option>
			<option <?php echo @$json["CategoriaID"] == '18' ? ' selected="selected"':'';?> value="18">Otro</option>
			<option <?php echo @$json["CategoriaID"] == '20' ? ' selected="selected"':'';?> value="20">RPM</option>
			<option <?php echo @$json["CategoriaID"] == '15' ? ' selected="selected"':'';?> value="15">Refrigerante</option>
			<option <?php echo @$json["CategoriaID"] == '17' ? ' selected="selected"':'';?> value="17">UF</option>
		</select>
	</td>
  </tr>
   <tr>
    <td>Síntoma</td>
    <td>
		<?php echo $intervencion['Sintoma']["nombre"]; ?>
		<!--<select name="sintoma_id" id="sintoma_id" style="width: 300px; overflow: hidden;" sintoma_id="<?php echo $intervencion['Planificacion']["sintoma_id"]; ?>">
			<option value="" disabled="disabled"></option>
		</select>-->
	</td>
  </tr>
  <?php } ?>
  <?php if($intervencion["Planificacion"]["tipointervencion"] == 'MP'){ ?>
   <tr>
    <td>Tipo Mantención</td>
    <td>
		<select name="TipoMAntención" id="TipoMAntención" style="width: 300px; overflow: hidden;" disabled="disabled"> 
			<option value="" disabled="disabled"></option>
			<option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '250' ? ' selected="selected"':'';?> value="250">250</option>
			<option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '500' ? ' selected="selected"':'';?> value="500">500</option>
			<option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '1000' ? ' selected="selected"':'';?> value="1000">1000</option>
			<option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '1500' ? ' selected="selected"':'';?> value="1500">Overhaul</option>
		</select>
	</td>
  </tr>
  <?php } ?>
   <?php if (isset($json["horometro_cabina"])) { ?>
  <tr>
    <td>Horómetro Inicial</td>
    <td><input type="text" name="horometro_cabina" id="horometro_cabina" class="horometro" value="<?php echo number_format($json['horometro_cabina'],2,".","");?>"></td>
  </tr>
  <?php } ?>
   <?php if (isset($json["horometro_final"])) { ?>
  <tr>
    <td>Horómetro Final</td>
    <td><input type="text" name="horometro_final" id="horometro_final" class="horometro"></td>
  </tr>
  <?php } ?>
  <tr>
    <td>Lugar Reparación</td>
    <td><select name="LugarReparacion" id="LugarReparacion" style="width: 300px; overflow: hidden;" disabled="disabled">
		<option value="" disabled="disabled"></option>
		<option value="TE"<?php echo strtoupper($json["LugarReparacion"]) == 'TE' ? ' selected="selected"':'';?>>Terreno</option>
		<option value="TA"<?php echo strtoupper($json["LugarReparacion"]) == 'TA' ? ' selected="selected"':'';?>>Taller</option>
    </select></td>
  </tr>
</table>
</div>

<!-- Elementos -->
<?php if (count($elementos) > 0) { ?>
<div class="widget">
<div class="title show" show="elementos" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Elementos</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable elementos" style="display: none;">
  <thead>
	  <tr>
		<td width="20">#</td>
		<td>Sistema</td>
		<td>Subsistema</td>
		<td>Posición</td>
		<!--<td>Imagen</td>-->
		<td><div style="width: 50px">ID</div></td>
		<td>Elemento</td>
		<td>Posición</td>
		<td>Diagnóstico</td>
		<td style="width:100px">Solución</td>
		<td style="width:140px">Tipo</td>
		<td style="width:140px">NP Sale</td> 
		<td style="width:140px">NP Entra</td>
        <td style="width:50px">Duración</td>
		<td style="width:20px"></td>
	  </tr>
  </thead>
  <tbody>
  <?php
	$cantidad_elementos = count($elementos);
	$total_tiempo = 0;
	for ($i = 0; $i < $cantidad_elementos; $i++) {
		$elemento = $elementos[$i]["IntervencionElementos"];
  ?>
	<tr>
		<td width="20"><?php echo $i+1;?></td>
		<td><?php echo $elementos[$i]["Sistema"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Subsistema"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Posiciones_Subsistema"]["nombre"];?></td>
		<!--<td><img src="/images/icons/control/32/illustration.png" class="ele_img" id="ele_img_" alt="" title="Ver imagen elemento" style="width:16px;cursor:pointer;margin-top:7px;" /></td>-->
		<td><?php echo $elemento["id_elemento"];?></td>
		<td><?php echo $elementos[$i]["Elemento"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Posiciones_Elemento"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Diagnostico"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Solucion"]["nombre"];?></td>
		<td><?php echo $elementos[$i]["Tipo_Elemento"]["nombre"];?></td>
		<td><?php echo $elemento["pn_saliente"];?></td>
		<td><?php echo $elemento["pn_entrante"];?></td>
		<td style="text-align: center;"><?php 
			$total_tiempo += $elemento["tiempo"];
			$hours  = floor($elemento["tiempo"] / 60); 
			$minutes = $elemento["tiempo"] % 60;
			echo str_pad($hours, 2, "0", STR_PAD_LEFT);
			echo ":";
			echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
		
		?></td>
		<td width="20"></td>
	  </tr>
	<?php } ?>
	<tr>
		<td colspan="12"></td>
        <td style="text-align: center;"><?php
			$hours  = floor($total_tiempo / 60); 
			$minutes = $total_tiempo % 60;
			echo str_pad($hours, 2, "0", STR_PAD_LEFT);
			echo ":";
			echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
		?></td>
		<td></td>
	  </tr>
  </tbody>
  </table>
  </div>
  <input type="hidden" name="existe_elemento" id="existe_elemento" value="1">
<?php }else{ ?>
<input type="hidden" name="existe_elemento" id="existe_elemento" value="0">
<?php } ?>

<?php if (count($elementos_reproceso) > 0) { ?>
<div class="widget">
<div class="title show" show="elementos_reproceso" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Reproceso de Elementos</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable elementos_reproceso" style="display: none;">
  <thead>
	  <tr>
		<td width="20">#</td>
		<td>Sistema</td>
		<td>Subsistema</td>
		<td>Posición</td>
		<!--<td>Imagen</td>-->
		<td><div style="width: 50px">ID</div></td>
		<td>Elemento</td>
		<td>Posición</td>
		<td>Diagnóstico</td>
		<td style="width:100px">Solución</td>
		<td style="width:140px">Tipo</td>
		<td style="width:140px">NP Sale</td> 
		<td style="width:140px">NP Entra</td>
        <td style="width:50px">Duración</td>
		<td style="width:20px"></td>
	  </tr>
  </thead>
  <tbody>
  <?php
	$cantidad_elementos = count($elementos_reproceso);
	$total_tiempo = 0;
	for ($i = 0; $i < $cantidad_elementos; $i++) {
		$elemento = $elementos_reproceso[$i]["IntervencionElementos"];
  ?>
	<tr>
		<td width="20"><?php echo $i+1;?></td>
		<td><?php echo $elementos_reproceso[$i]["Sistema"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Subsistema"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Posiciones_Subsistema"]["nombre"];?></td>
		<!--<td><img src="/images/icons/control/32/illustration.png" class="ele_img" id="ele_img_" alt="" title="Ver imagen elemento" style="width:16px;cursor:pointer;margin-top:7px;" /></td>-->
		<td><?php echo $elemento["id_elemento"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Elemento"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Posiciones_Elemento"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Diagnostico"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Solucion"]["nombre"];?></td>
		<td><?php echo $elementos_reproceso[$i]["Tipo_Elemento"]["nombre"];?></td>
		<td><?php echo $elemento["pn_saliente"];?></td>
		<td><?php echo $elemento["pn_entrante"];?></td>
		<td style="text-align: center;"><?php 
			$total_tiempo += $elemento["tiempo"];
			$hours  = floor($elemento["tiempo"] / 60); 
			$minutes = $elemento["tiempo"] % 60;
			echo str_pad($hours, 2, "0", STR_PAD_LEFT);
			echo ":";
			echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
		
		?></td>
		<td width="20"></td>
	  </tr>
	<?php } ?>
	<tr>
		<td colspan="12"></td>
        <td style="text-align: center;"><?php
			$hours  = floor($total_tiempo / 60); 
			$minutes = $total_tiempo % 60;
			echo str_pad($hours, 2, "0", STR_PAD_LEFT);
			echo ":";
			echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
		?></td>
		<td></td>
	  </tr>
  </tbody>
  </table>
  </div>
<?php } ?>

<div class="widget">
<div class="title show" show="informacion_flujo" style="cursor: pointer;"><img src="/images/icons/dark/clock.png" alt="" class="titleIcon"><h6>Información de Flujo</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable informacion_flujo" style="display: none;">
  <?php if (isset($decisiones["cambio_modulo"]) && $decisiones["cambio_modulo"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Cambio módulo</td>
    <td>
		<select name="cambio_modulo" id="cambio_modulo" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["cambio_modulo"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["cambio_modulo"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
    <?php if (isset($decisiones["intervencion_terminada"]) && $decisiones["intervencion_terminada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Intervención terminada</td>
    <td>
		<select name="intervencion_terminada" id="intervencion_terminada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["intervencion_terminada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["intervencion_terminada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["desconexion_realizada"]) && $decisiones["desconexion_realizada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Desconexión realizada</td>
    <td>
		<select name="desconexion_realizada" id="desconexion_realizada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["desconexion_realizada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["desconexion_realizada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["desconexion_terminada"]) && $decisiones["desconexion_terminada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Desconexión terminada</td>
    <td>
		<select name="desconexion_terminada" id="desconexion_terminada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["desconexion_terminada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["desconexion_terminada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["conexion_realizada"]) && $decisiones["conexion_realizada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Conexión realizada</td>
    <td>
		<select name="conexion_realizada" id="conexion_realizada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["conexion_realizada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["conexion_realizada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["conexion_terminada"]) && $decisiones["conexion_terminada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Conexión terminada</td>
    <td>
		<select name="conexion_terminada" id="conexion_terminada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["conexion_terminada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["conexion_terminada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["puesta_marcha_realizada"]) && $decisiones["puesta_marcha_realizada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Puesta en marcha realizada</td>
    <td>
		<select name="puesta_marcha_realizada" id="puesta_marcha_realizada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["puesta_marcha_realizada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["puesta_marcha_realizada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["prueba_potencia_relalizada"]) && $decisiones["prueba_potencia_relalizada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Prueba de potencia realizada</td>
    <td>
		<select name="prueba_potencia_relalizada" id="prueba_potencia_relalizada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["prueba_potencia_relalizada"] == 'S' ? ' selected="selected"':'';?>>REALIZADA</option>
			<option value="P"<?php echo $decisiones["prueba_potencia_relalizada"] == 'P' ? ' selected="selected"':'';?>>QUEDA PENDIENTE</option>
			<option value="N"<?php echo $decisiones["prueba_potencia_relalizada"] == 'N' ? ' selected="selected"':'';?>>NO APLICA</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["prueba_potencia_exitosa"]) && $decisiones["prueba_potencia_exitosa"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Prueba de potencia exitosa</td>
    <td>
		<select name="prueba_potencia_exitosa" id="prueba_potencia_exitosa" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["prueba_potencia_exitosa"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["prueba_potencia_exitosa"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["siguiente_actividad"]) && $decisiones["siguiente_actividad"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Siguiente actividad</td>
    <td>
		<select name="siguiente_actividad" id="siguiente_actividad" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="A"<?php echo $decisiones["siguiente_actividad"] == 'A' ? ' selected="selected"':'';?>>AGREGAR NUEVO ELEMENTO</option>
			<option value="P"<?php echo $decisiones["siguiente_actividad"] == 'P' ? ' selected="selected"':'';?>>DEJAR PENDIENTE</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["reproceso_potencia"]) && $decisiones["reproceso_potencia"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Resultado prueba potencia</td>
    <td>
		<select name="reproceso_potencia" id="reproceso_potencia" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="E"<?php echo $decisiones["reproceso_potencia"] == 'E' ? ' selected="selected"':'';?>>EXITOSA</option>
			<option value="F"<?php echo $decisiones["reproceso_potencia"] == 'F' ? ' selected="selected"':'';?>>EXITOSA</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["reproceso_modulo"]) && $decisiones["reproceso_modulo"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Cambio de módulo</td>
    <td>
		<select name="reproceso_modulo" id="reproceso_modulo" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["reproceso_modulo"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["reproceso_modulo"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["reproceso_evento"]) && $decisiones["reproceso_evento"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Estado evento</td>
    <td>
		<select name="reproceso_evento" id="reproceso_evento" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="F"<?php echo $decisiones["reproceso_evento"] == 'F' ? ' selected="selected"':'';?>>FINALIZADO</option>
			<option value="P"<?php echo $decisiones["reproceso_evento"] == 'P' ? ' selected="selected"':'';?>>PENDIENTE</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["trabajo_finalizado"]) && $decisiones["trabajo_finalizado"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Trabajo finalizado</td>
    <td>
		<select name="trabajo_finalizado" id="trabajo_finalizado" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["trabajo_finalizado"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["trabajo_finalizado"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  <?php if (isset($decisiones["mantencion_terminada"]) && $decisiones["mantencion_terminada"] != '') { ?>
  <tr>
    <td style="width: 300px; overflow: hidden;">Mantención terminada</td>
    <td>
		<select name="mantencion_terminada" id="mantencion_terminada" style="width: 300px; overflow: hidden;" disabled="disabled">
			<option value="" disabled="disabled"></option>
			<option value="S"<?php echo $decisiones["mantencion_terminada"] == 'S' ? ' selected="selected"':'';?>>SI</option>
			<option value="N"<?php echo $decisiones["mantencion_terminada"] == 'N' ? ' selected="selected"':'';?>>NO</option>
		</select>
	</td>
  </tr>
  <?php } ?>
  </table>
  </div>

<div class="widget">
<div class="title show" show="fechas" style="cursor: pointer;"><img src="/images/icons/dark/clock.png" alt="" class="titleIcon"><h6>Detalle Fechas</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable fechas" style="display: none;">
	 <tr style="font-weight: bold;">
		<td>Descripción</td>
		<td align="center">Fecha</td>
		<td align="center">Hora</td>
		<td align="center">Minuto</td>
		<td align="center">Período</td>
	 </tr>
	<?php
	if(isset($fechas['IntervencionFechas']['llamado']) && $fechas['IntervencionFechas']['llamado'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['llamado']);
	?>
  <tr class="tr_llamado_fecha">
    <td style="width: 300px; overflow: hidden; font-weight: bold;">Llamado</td>
    <td align="center">
		<input type="date" size="10" name="llamado[]" id="llamado_fecha" value="<?php echo $fecha->format('Y-m-d');?>" class="f_1 delta1_data" max="<?php echo date("Y-m-d");?>" />
	</td>
	<td align="center">
		<select name="llamado[]" id="llamado_hora" class="h_1 delta1_data">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="llamado[]" id="llamado_min" class="m_1 delta1_data">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="llamado[]" id="llamado_periodo" class="p_1 delta1_data">
			<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
		</select>
	</td>
  </tr>
	<?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['llegada']);
	?>
<tr class="tr_llegada_fecha">
    <td style="font-weight: bold;">Llegada</td>
    <td align="center"><input type="date" size="10" name="llegada[]" value="<?php echo $fecha->format('Y-m-d');?>" id="llegada_fecha" class="f_2 delta1_data delta2_data" max="<?php echo date("Y-m-d");?>" /></td>
	<td align="center">
		<select name="llegada[]" id="llegada_hora" class="h_2 delta1_data delta2_data">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="llegada[]" id="llegada_min" class="m_2 delta1_data delta2_data">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="llegada[]" id="llegada_periodo" class="p_2 delta1_data delta2_data">
			<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
		</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['inicio_intervencion']);
	?>
<tr class="tr_i_i_f">
    <td style="width: 300px; overflow: hidden; font-weight: bold;">Inicio intervención</td>
    <td align="center"><input type="date" size="10" name="inicio_intervencion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="i_i_f" class="f_3 delta2_data" max="<?php echo date("Y-m-d");?>" /></td>
	<td align="center">
		<select name="inicio_intervencion[]" id="i_i_h"  class="h_3 delta2_data">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_intervencion[]" id="i_i_m" class="m_3 delta2_data">
		<option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
	</select>
	</td>
	<td align="center">
		<select name="inicio_intervencion[]" id="i_i_p" class="p_3 delta2_data">
			<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
		</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
		$termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_intervencion']);
	?>
<tr class="tr_i_t_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término intervención</td>
    <td align="center"><input type="date" size="10" name="termino_intervencion[]" value="<?php echo $termino_intervencion->format('Y-m-d');?>" id="i_t_f" class="f_4" max="<?php echo date("Y-m-d");?>" /></td>
	<td align="center">
		<select name="termino_intervencion[]" id="i_t_h" class="h_4">
			<option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_intervencion[]" id="i_t_m" class="m_4">
		  <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_intervencion[]" id="i_t_p" class="p_4">
			<option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
		</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['inicio_desconexion']) && $fechas['IntervencionFechas']['inicio_desconexion'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['inicio_desconexion']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Inicio desconexión</td>
    <td align="center"><input type="date" size="10" name="inicio_desconexion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="desc_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="inicio_desconexion[]" id="desc_h" class="h_<?php echo $i;?>">
			<option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_desconexion[]" id="desc_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_desconexion[]" id="desc_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_desconexion']) && $fechas['IntervencionFechas']['termino_desconexion'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['termino_desconexion']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término desctonexión</td>
    <td align="center"><input type="date" size="10" name="termino_desconexion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="desct_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="termino_desconexion[]" id="desct_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_desconexion[]" id="desct_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_desconexion[]" id="desct_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['inicio_conexion']) && $fechas['IntervencionFechas']['inicio_conexion'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['inicio_conexion']);
	?>
	<tr class="tr_con_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Inicio conexión</td>
    <td align="center"><input type="date" size="10" name="inicio_conexion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="con_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="inicio_conexion[]" id="con_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_conexion[]" id="con_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_conexion[]" id="con_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_conexion']) && $fechas['IntervencionFechas']['termino_conexion'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['termino_conexion']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término conexión</td>
    <td align="center"><input type="date" size="10" name="termino_conexion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="cont_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="termino_conexion[]" id="cont_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_conexion[]" id="cont_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_conexion[]" id="cont_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['inicio_puesta_marcha']) && $fechas['IntervencionFechas']['inicio_puesta_marcha'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['inicio_puesta_marcha']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Inicio puesta en marcha</td>
    <td align="center"><input type="date" size="10" name="inicio_puesta_marcha[]" value="<?php echo $fecha->format('Y-m-d');?>" id="pm_i_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="inicio_puesta_marcha[]" id="pm_i_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_puesta_marcha[]" id="pm_i_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_puesta_marcha[]" id="pm_i_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_puesta_marcha']) && $fechas['IntervencionFechas']['termino_puesta_marcha'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['termino_puesta_marcha']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término puesta en marcha</td>
    <td align="center"><input type="date" size="10" name="termino_puesta_marcha[]" value="<?php echo $fecha->format('Y-m-d');?>" id="pm_t_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="termino_puesta_marcha[]" id="pm_t_h" class="h_<?php echo $i;?>">
		 <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_puesta_marcha[]" id="pm_t_m" class="m_<?php echo $i;?>">
		 <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_puesta_marcha[]" id="pm_t_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['inicio_prueba_potencia']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Inicio prueba de potencia</td>
    <td align="center"><input type="date" size="10" name="inicio_prueba_potencia[]" value="<?php echo $fecha->format('Y-m-d');?>" id="pp_i_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="inicio_prueba_potencia[]" id="pp_i_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_prueba_potencia[]" id="pp_i_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="inicio_prueba_potencia[]" id="pp_i_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_prueba_potencia']) && $fechas['IntervencionFechas']['termino_prueba_potencia'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['termino_prueba_potencia']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término prueba de potencia</td>
    <td align="center"><input type="date" size="10" name="termino_prueba_potencia[]" value="<?php echo $fecha->format('Y-m-d');?>" id="pp_t_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="termino_prueba_potencia[]" id="pp_t_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_prueba_potencia[]" id="pp_t_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_prueba_potencia[]" id="pp_t_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($fechas['IntervencionFechas']['termino_reproceso']) && $fechas['IntervencionFechas']['termino_reproceso'] != '') {
		$fecha = new DateTime($fechas['IntervencionFechas']['termino_reproceso']);
	?>
	<tr class="tr_desc_f">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Término reproceso</td>
    <td align="center"><input type="date" size="10" name="termino_reproceso[]" value="<?php echo $fecha->format('Y-m-d');?>" id="repro_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="termino_reproceso[]" id="repro_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_reproceso[]" id="repro_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="termino_reproceso[]" id="repro_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($intervencion["Planificacion"]['fecha_operacion']) && $intervencion["Planificacion"]['fecha_operacion'] != null && $intervencion["Planificacion"]['fecha_operacion'] != '') {
		$fecha = new DateTime($intervencion["Planificacion"]['fecha_operacion']);
	?>
	<tr class="tr_fecha_operacion">
    <td style="width: 300px; overflow: hidden;font-weight: bold; ">Fecha operación</td>
    <td align="center"><input type="date" size="10" name="fecha_operacion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="operacion_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
	<td align="center">
		<select name="fecha_operacion[]" id="operacion_h" class="h_<?php echo $i;?>">
		  <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
			<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
			<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
			<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
			<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
			<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
			<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
			<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
			<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
			<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
			<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
			<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
		</select>
	</td>
	<td align="center">
		<select name="fecha_operacion[]" id="operacion_m" class="m_<?php echo $i;?>">
		  <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"':'';?>>00</option>
		  <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"':'';?>>15</option>
		  <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"':'';?>>30</option>
		  <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"':'';?>>45</option>
		</select>
	</td>
	<td align="center">
		<select name="fecha_operacion[]" id="operacion_p" class="p_<?php echo $i;?>">
		<option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"':'';?>>AM</option>
			<option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"':'';?>>PM</option>
	</select>
	</td>
  </tr>
  <?php
	} 
	?>
	<?php
	if(isset($intervencion["Planificacion"]['fecha_aprobacion']) && $intervencion["Planificacion"]['fecha_aprobacion'] != null && $intervencion["Planificacion"]['fecha_aprobacion'] != '') {
		$fecha = new DateTime($intervencion["Planificacion"]['fecha_aprobacion']);
	?>
	<tr class="tr_fecha_aprobacion">
		<td style="width: 300px; overflow: hidden;font-weight: bold; ">Fecha aprobación</td>
		<td align="center"><input type="date" size="10" name="fecha_aprobacion[]" value="<?php echo $fecha->format('Y-m-d');?>" id="aprobacion_f" max="<?php echo date("Y-m-d");?>" class="f_<?php echo ++$i;?>" /></td>
		<td align="center">
			<select name="fecha_aprobacion[]" id="aprobacion_h" class="h_<?php echo $i;?>">
				<option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"':'';?>>01</option>
				<option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"':'';?>>02</option>
				<option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"':'';?>>03</option>
				<option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"':'';?>>04</option>
				<option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"':'';?>>05</option>
				<option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"':'';?>>06</option>
				<option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"':'';?>>07</option>
				<option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"':'';?>>08</option>
				<option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"':'';?>>09</option>
				<option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"':'';?>>10</option>
				<option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"':'';?>>11</option>
				<option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"':'';?>>12</option>
			</select>
		</td>
		<td align="center">
			<select name="fecha_aprobacion[]" id="aprobacion_m" class="m_<?php echo $i;?>">
				<option value="<?php echo $fecha->format('i');?>" selected="selected"><?php echo $fecha->format('i');?></option>
			</select>
		</td>
		<td align="center">
			<select name="fecha_aprobacion[]" id="aprobacion_p" class="p_<?php echo $i;?>">
				<option value="<?php echo $fecha->format('A');?>" selected="selected"><?php echo $fecha->format('A');?></option>
			</select>
		</td>
	</tr>
  <?php
	} 
	?>
</table>
</div>
<input type="hidden" id="cantidad_fechas" value="4" />
<div class="widget despliegue_deltas">
	<div class="title show" show="deltas" style="cursor: pointer;"><img src="/images/icons/dark/clock.png" alt="" class="titleIcon"><h6>Detalle Delta</h6></div>
	<table cellpadding="0" cellspacing="0" width="100%" class="sTable deltas" style="display: none;">
		 <tr>
          <td rowspan="2" width="25%" valign="middle"><strong>Descripción</strong></td>
          <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_1"><strong>Delta1 (<span class="delta1_duracion"></span>) <br /> <span style="color: gray;">(<span id="d1_ing">0h 0m</span>)</span></strong></td>
          <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_2"><strong>Delta2 (<span class="delta2_duracion"></span>)  <br /><span style="color: gray;">(<span id="d2_ing">0h 0m</span>)</span></strong></td>
		  <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_3"><strong>Delta3 (<span class="delta3_duracion"></span>)  <br /><span style="color: gray;">(<span id="d3_ing">0h 0m</span>)</span></strong></td>
		  <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_4"><strong>Delta4 (<span class="delta4_duracion"></span>)  <br /><span style="color: gray;">(<span id="d4_ing">0h 0m</span>)</span></strong></td>
		  <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_5"><strong>Delta5 (<span class="delta5_duracion"></span>)  <br /><span style="color: gray;">(<span id="d5_ing">0h 0m</span>)</span></strong></td>
		  <td width="220" colspan="4" align="center" valign="middle" class="columna_delta_6"><strong>Delta6 (<span class="delta6_duracion"></span>)  <br /><span style="color: gray;">(<span id="d6_ing">0h 0m</span>)</span></strong></td>
		  
        </tr>
		<tr >
          <td width="" align="center" valign="middle" class="columna_delta_1"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_1"><strong>Minutos</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_1"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_1"><strong>Observación</strong></td>
		  <td width="" align="center" valign="middle" class="columna_delta_2"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_2"><strong>Minuto</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_2"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_2"><strong>Observación</strong></td>
		  <td width="" align="center" valign="middle" class="columna_delta_3"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_3"><strong>Minuto</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_3"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_3"><strong>Observación</strong></td>
		  <td width="" align="center" valign="middle" class="columna_delta_4"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_4"><strong>Minuto</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_4"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_4"><strong>Observación</strong></td>
		  <td width="" align="center" valign="middle" class="columna_delta_5"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_5"><strong>Minuto</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_5"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_5"><strong>Observación</strong></td>
		  <td width="" align="center" valign="middle" class="columna_delta_6"><strong>Hora</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_6"><strong>Minuto</strong></td>
          <td width="" align="center" valign="middle" class="columna_delta_6"><strong>Responsable</strong></td>
		  <td width="10px" align="center" valign="middle" class="columna_delta_6"><strong>Observación</strong></td>
        </tr>
		  <?php
		//if (intval(@$json["d_traslado_dcc_h"]) > 0 || intval(@$json["d_traslado_dcc_m"]) > 0) {
		  ?>
        <tr class="d_traslado_dcc" >
          <td width="25%"><strong>Traslado DCC</strong></td>
          <td align="center" class="columna_delta_1">
		  <?php
			$hours = floor($deltas[1]["tiempo"] / 60);
			$minutes = ($deltas[1]["tiempo"] % 60);
		  ?>
			<input name="delta[1]" type="text" id="d_traslado_dcc_h"  size="4" value="<?php echo $hours;?>" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			</td>
			<td align="center" class="columna_delta_1">
            <select name="delta_m[1]" id="d_traslado_dcc_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
			<td align="center" class="columna_delta_1">
            <select name="delta_r[1]" id="d_traslado_dcc_r">
              <option value="0"></option>
              <option value="1"<?php if($deltas[1]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
              <option value="2"<?php if($deltas[1]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[1]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		  <td align="center" class="columna_delta_1">
			<input name="delta_o[1]" type="text" id="d_traslado_dcc_o" value="<?php echo $deltas[1]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
          <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
          <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
          <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
          <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_traslado_oem_h"]) > 0 || intval(@$json["d_traslado_oem_m"]) > 0) {
		  ?>
         <tr class="d_traslado_oem" >
          <td ><strong>Traslado OEM</strong></td>
          <td align="center" class="d_traslado_oem_b columna_delta_1">
		  <?php
			$hours = floor($deltas[2]["tiempo"] / 60);
			$minutes = ($deltas[2]["tiempo"] % 60);
		  ?>
			<input name="delta[2]" type="text" id="d_traslado_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[2]" id="d_traslado_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
           <select name="delta_r[2]" id="d_traslado_oem_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[2]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			</select>
		  </td>
		  <td align="center" class="columna_delta_1">
		  <input name="delta_o[2]" type="text" id="d_traslado_oem_o" value="<?php echo $deltas[2]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_tronadura_h"]) > 0 || intval(@$json["d_tronadura_m"]) > 0 || intval(@$json["d_tronadura_inter_h"]) > 0 || intval(@$json["d_tronadura_inter_m"]) > 0 || intval(@$json["d4_tronadura_h"]) > 0 || intval(@$json["d4_tronadura_m"]) > 0) {
		  ?>
        <tr class="d_tronadura d_tronadura_inter" >
          <td width="25%" ><strong>Tronadura</strong></td>
          <td align="center" class="d_tronadura_b columna_delta_1" >
			<?php
			$hours = floor($deltas[3]["tiempo"] / 60);
			$minutes = ($deltas[3]["tiempo"] % 60);
		  ?>
			<input name="delta[3]" type="text" id="d_tronadura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[3]" id="d_tronadura_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_r[3]" id="d_tronadura_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[3]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_1">
		  <input name="delta_o[3]" type="text" id="d_tronadura_o" value="<?php echo $deltas[3]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td align="center" class="d_tronadura_b columna_delta_2" >
			<?php
			$hours = floor($deltas[13]["tiempo"] / 60);
			$minutes = ($deltas[13]["tiempo"] % 60);
		  ?>
			<input name="delta[13]" type="text" id="d2_tronadura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[13]" id="d2_tronadura_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_r[13]" id="d2_tronadura_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[13]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		   <td align="center" class="columna_delta_2">
		  <input name="delta_o[13]" type="text" id="d2_tronadura_o" value="<?php echo $deltas[13]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td align="center" class="columna_delta_4">
			<?php
			$hours = floor($deltas[25]["tiempo"] / 60);
			$minutes = ($deltas[25]["tiempo"] % 60);
		  ?>
			<input name="delta[25]" type="text" id="d4_tronadura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_4">
            <select name="delta_m[25]" id="d4_tronadura_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_4">
             <select name="delta_r[25]" id="d4_tronadura_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[25]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[25]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		   <td align="center" class="columna_delta_4">
		 <input name="delta_o[25]" type="text" id="d4_tronadura_o" value="<?php echo $deltas[25]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_clima_h"]) > 0 || intval(@$json["d_clima_m"]) > 0 || intval(@$json["d_clima_inter_h"]) > 0 || intval(@$json["d_clima_inter_m"]) > 0 || intval(@$json["d4_clima_h"]) > 0 || intval(@$json["d4_clima_m"]) > 0) {
		  ?>
         <tr class="d_clima d_clima_inter" >
          <td ><strong>Clima</strong></td>
          <td align="center" class="d_clima_b columna_delta_1"  >
			<?php
			$hours = floor($deltas[4]["tiempo"] / 60);
			$minutes = ($deltas[4]["tiempo"] % 60);
		  ?>
			<input name="delta[4]" type="text" id="d_clima_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[4]" id="d_clima_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_r[4]" id="d_clima_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[4]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		   <td align="center" class="columna_delta_1">
		  <input name="delta_o[4]" type="text" id="d_clima_o" value="<?php echo $deltas[4]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td align="center" class="d_clima_b columna_delta_2">
			<?php
			$hours = floor($deltas[11]["tiempo"] / 60);
			$minutes = ($deltas[11]["tiempo"] % 60);
		  ?>
			<input name="delta[11]" type="text" id="d2_clima_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[11]" id="d2_clima_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_r[11]" id="d2_clima_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[11]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_2">
		  <input name="delta_o[11]" type="text" id="d2_clima_o" value="<?php echo $deltas[11]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td align="center" class="columna_delta_4">
			<?php
			$hours = floor($deltas[49]["tiempo"] / 60);
			$minutes = ($deltas[49]["tiempo"] % 60);
		  ?>
			<input name="delta[49]" type="text" id="d4_clima_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_4">
            <select name="delta_m[49]" id="d4_clima_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_4">
             <select name="delta_r[49]" id="d4_clima_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[49]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[49]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		   <td align="center" class="columna_delta_4">
		 <input name="delta_o[49]" type="text" id="d4_clima_o" value="<?php echo $deltas[49]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		   <?php
		//}
		//if (intval(@$json["d_logistica_dcc_h"]) > 0 || intval(@$json["d_logistica_dcc_m"]) > 0) {
		  ?>
        <tr class="d_logistica_dcc" >
          <td width="25%" ><strong>Logistica DCC</strong></td>
          <td align="center" class="columna_delta_1">
			<?php
			$hours = floor($deltas[5]["tiempo"] / 60);
			$minutes = ($deltas[5]["tiempo"] % 60);
		  ?>
			<input name="delta[5]" type="text" id="d_logistica_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[5]" id="d_logistica_dcc_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
             <select name="delta_r[5]" id="d_logistica_dcc_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[5]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
			  <option value="2"<?php if($deltas[5]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[5]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		   <td align="center" class="columna_delta_1">
		 <input name="delta_o[5]" type="text" id="d_logistica_dcc_o" value="<?php echo $deltas[5]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td align="center" class="columna_delta_2">
			<?php
			$hours = floor($deltas[27]["tiempo"] / 60);
			$minutes = ($deltas[27]["tiempo"] % 60);
		  ?>
			<input name="delta[27]" type="text" id="d2_logistica_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[27]" id="d2_logistica_dcc_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
             <select name="delta_r[27]" id="d2_logistica_dcc_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[27]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
			  <option value="2"<?php if($deltas[27]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[27]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		   <td align="center" class="columna_delta_2">
		 <input name="delta_o[27]" type="text" id="d_logistica_dcc_o" value="<?php echo $deltas[27]["observacion"];?>" style="width: 100px;" />
		  </td>
		 <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td align="center" class="columna_delta_4">
			<?php
			$hours = floor($deltas[28]["tiempo"] / 60);
			$minutes = ($deltas[28]["tiempo"] % 60);
		  ?>
			<input name="delta[28]" type="text" id="d4_logistica_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_4">
            <select name="delta_m[28]" id="d4_logistica_dcc_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_4">
             <select name="delta_r[28]" id="d4_logistica_dcc_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[28]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[28]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
            </select>
		  </td>
		   <td align="center" class="columna_delta_4">
		 <input name="delta_o[28]" type="text" id="d4_logistica_dcc_o" value="<?php echo $deltas[28]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_logistica_oem_h"]) > 0 || intval(@$json["d_logistica_oem_m"]) > 0) {
		  ?>
         <tr class="d_logistica_oem" >
          <td ><strong>Logistica OEM</strong></td>
          <td align="center" class="d_logistica_oem_b columna_delta_1">
			<?php
			$hours = floor($deltas[6]["tiempo"] / 60);
			$minutes = ($deltas[6]["tiempo"] % 60);
		  ?>
			<input name="delta[6]" type="text" id="d_logistica_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[6]" id="d_logistica_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
           <select name="delta_r[6]" id="d_logistica_oem_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[6]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_1">
		  <input name="delta_o[6]" type="text" id="d_logistica_oem_o" value="<?php echo $deltas[6]["observacion"];?>" style="width: 100px;" />
		  </td>
           <td align="center" class="d_logistica_oem_b columna_delta_2">
			<?php
			$hours = floor($deltas[48]["tiempo"] / 60);
			$minutes = ($deltas[48]["tiempo"] % 60);
		  ?>
			<input name="delta[48]" type="text" id="d2_logistica_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[48]" id="d2_logistica_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
           <select name="delta_r[48]" id="d2_logistica_oem_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[48]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_2">
		  <input name="delta_o[48]" type="text" id="d2_logistica_oem_o" value="<?php echo $deltas[48]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		 <?php
		//}
		//if (intval(@$json["d_personal_h"]) > 0 || intval(@$json["d_personal_m"])) {
		  ?>
        <tr class="d_personal" >
          <td width="25%" ><strong>Personal</strong></td>
          <td align="center" class="d_personal_b columna_delta_1"  >
			<?php
			$hours = floor($deltas[7]["tiempo"] / 60);
			$minutes = ($deltas[7]["tiempo"] % 60);
		  ?>
			<input name="delta[7]" type="text" id="d_personal_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_1">
            <select name="delta_m[7]" id="d_personal_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_1">
           <select name="delta_r[7]" id="d_personal_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[7]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
      <option value="3"<?php if($deltas[7]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		   <td align="center" class="columna_delta_1">
		  <input name="delta_o[7]" type="text" id="d_personal_o" value="<?php echo $deltas[7]["observacion"];?>" style="width: 100px;" />
		  </td>
          <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d2_cliente_h"]) > 0 || intval(@$json["d2_cliente_m"]) || intval(@$json["d3_cliente_h"]) > 0 || intval(@$json["d3_cliente_m"])) {
		  ?>
        <tr class="d2_cliente" >
          <td width="25%" ><strong>Cliente</strong></td>
          <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
          <td align="center" class="d_personal_b columna_delta_2"  >
			<?php
			$hours = floor($deltas[8]["tiempo"] / 60);
			$minutes = ($deltas[8]["tiempo"] % 60);
		  ?>
			<input name="delta[8]" type="text" id="d2_cliente_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[8]" id="d2_cliente_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
           <select name="delta_r[8]" id="d2_cliente_r">
      <option value="0"></option>
      <option value="1"<?php if($deltas[8]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[8]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[8]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_2">
		  <input name="delta_o[8]" type="text" id="d2_cliente_o" value="<?php echo $deltas[8]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 3 -->
		  <td align="center" class="columna_delta_3"  >
			<?php
			$hours = floor($deltas[21]["tiempo"] / 60);
			$minutes = ($deltas[21]["tiempo"] % 60);
		  ?>
			<input name="delta[21]" type="text" id="d3_cliente_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_3">
            <select name="delta_m[21]" id="d3_cliente_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_3">
           <select name="delta_r[21]" id="d3_cliente_r">
      <option value="0"></option>
      <option value="1"<?php if($deltas[21]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[21]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[21]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_3">
		  <input name="delta_o[21]" type="text" id="d3_cliente_o" value="<?php echo $deltas[21]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 4 -->
		  <td align="center" class="columna_delta_4"  >
			<?php
			$hours = floor($deltas[29]["tiempo"] / 60);
			$minutes = ($deltas[29]["tiempo"] % 60);
		  ?>
			<input name="delta[29]" type="text" id="d4_cliente_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_4">
            <select name="delta_m[29]" id="d4_cliente_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_4">
           <select name="delta_r[29]" id="d4_cliente_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[29]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_4">
		  <input name="delta_o[29]" type="text" id="d4_cliente_o" value="<?php echo $deltas[29]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 5 -->
		  <td align="center" class="columna_delta_5"  >
			<?php
			$hours = floor($deltas[37]["tiempo"] / 60);
			$minutes = ($deltas[37]["tiempo"] % 60);
		  ?>
			<input name="delta[37]" type="text" id="d5_cliente_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_5">
            <select name="delta_m[37]" id="d5_cliente_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_5">
           <select name="delta_r[37]" id="d5_cliente_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[37]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_5">
		  <input name="delta_o[37]" type="text" id="d5_cliente_o" value="<?php echo $deltas[37]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 6 -->
		  <td align="center" class="columna_delta_6"  >
			<?php
			$hours = floor($deltas[39]["tiempo"] / 60);
			$minutes = ($deltas[39]["tiempo"] % 60);
		  ?>
			<input name="delta[39]" type="text" id="d6_cliente_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[39]" id="d6_cliente_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[39]" id="d6_cliente_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[39]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_6">
		  <input name="delta_o[39]" type="text" id="d6_cliente_o" value="<?php echo $deltas[39]["observacion"];?>" style="width: 100px;" />
		  </td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_oem_h"]) > 0 || intval(@$json["d_oem_m"]) || intval(@$json["d3_oem_h"]) > 0 || intval(@$json["d3_oem_m"]) || intval(@$json["d4_oem_h"]) > 0 || intval(@$json["d4_oem_m"])) {
		  ?>
         <tr class="d_oem" >
          <td ><strong>OEM</strong></td>
          <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
          <td align="center" class="d_personal_b columna_delta_2" >
			<?php
			$hours = floor($deltas[9]["tiempo"] / 60);
			$minutes = ($deltas[9]["tiempo"] % 60);
		  ?>
			<input name="delta[9]" type="text" id="d2_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[9]" id="d2_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
           <select name="delta_r[9]" id="d2_oem_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[9]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_2">
		  <input name="delta_o[9]" type="text" id="d2_oem_o" value="<?php echo $deltas[9]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 3 -->
		  <td align="center" class="columna_delta_3"  >
			<?php
			$hours = floor($deltas[20]["tiempo"] / 60);
			$minutes = ($deltas[20]["tiempo"] % 60);
		  ?>
			<input name="delta[20]" type="text" id="d3_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_3">
            <select name="delta_m[20]" id="d3_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_3">
           <select name="delta_r[20]" id="d3_oem_r">
      <option value="0"></option>
     <option value="1"<?php if($deltas[20]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[20]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[20]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_3">
		  <input name="delta_o[20]" type="text" id="d3_oem_o" value="<?php echo $deltas[20]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 4 -->
		  <td align="center" class="columna_delta_4"  >
			<?php
			$hours = floor($deltas[24]["tiempo"] / 60);
			$minutes = ($deltas[24]["tiempo"] % 60);
		  ?>
			<input name="delta[24]" type="text" id="d4_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_4">
            <select name="delta_m[24]" id="d4_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_4">
           <select name="delta_r[24]" id="d4_oem_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[24]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_4">
		  <input name="delta_o[24]" type="text" id="d4_oem_o" value="<?php echo $deltas[24]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 5 -->
		  <td align="center" class="columna_delta_5"  >
			<?php
			$hours = floor($deltas[36]["tiempo"] / 60);
			$minutes = ($deltas[36]["tiempo"] % 60);
		  ?>
			<input name="delta[36]" type="text" id="d5_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_5">
            <select name="delta_m[36]" id="d5_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_5">
           <select name="delta_r[36]" id="d5_oem_r">
      <option value="0"></option>
      <option value="3"<?php if($deltas[36]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_5">
		  <input name="delta_o[36]" type="text" id="d5_oem_o" value="<?php echo $deltas[36]["observacion"];?>" style="width: 100px;" />
		  </td>
		  <!-- Delta 6 -->
		  <td align="center" class="columna_delta_6"  >
			<?php
			$hours = floor($deltas[40]["tiempo"] / 60);
			$minutes = ($deltas[40]["tiempo"] % 60);
		  ?>
			<input name="delta[40]" type="text" id="d6_oem_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[40]" id="d6_oem_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[40]" id="d6_oem_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[40]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
      <option value="3"<?php if($deltas[40]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		  <td align="center" class="columna_delta_6">
		  <input name="delta_o[40]" type="text" id="d6_oem_o" value="<?php echo $deltas[40]["observacion"];?>" style="width: 100px;" />
		  </td>
          </tr>
		  <?php
		///}
		//if (intval(@$json["d_zona_segura_h"]) > 0 || intval(@$json["d_zona_segura_m"])) {
		  ?>
        <tr  class="d_zona_segura" > 
          <td width="25%" ><strong>Zona Segura</strong></td>
          <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
          <td align="center" class="d_personal_b columna_delta_2" >
			<?php
			$hours = floor($deltas[10]["tiempo"] / 60);
			$minutes = ($deltas[10]["tiempo"] % 60);
		  ?>
			<input name="delta[10]" type="text" id="d2_zona_segura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[10]" id="d2_zona_segura_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
           <select name="delta_r[10]" id="d2_zona_segura_r">
      <option value="0"></option>
      <option value="2"<?php if($deltas[10]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
      <option value="3"<?php if($deltas[10]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		   <td align="center" class="columna_delta_2">
		  <input name="delta_o[10]" type="text" id="d2_zona_segura_o" value="<?php echo $deltas[10]["observacion"];?>" style="width: 100px;" />
		  </td>
			<td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
			<td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			<td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
			<td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
          </tr>
		  <?php
		//}
		//if (intval(@$json["d_charla_h"]) > 0 || intval(@$json["d_charla_m"])) {
		  ?>
        <tr class="d_charla" >
          <td width="25%" ><strong>Charla</strong></td>
          <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
         <td align="center" class="d_personal_b columna_delta_2"  >
			<?php
			$hours = floor($deltas[12]["tiempo"] / 60);
			$minutes = ($deltas[12]["tiempo"] % 60);
		  ?>
			<input name="delta[12]" type="text" id="d2_charla_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_2">
            <select name="delta_m[12]" id="d2_charla_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_2">
           <select name="delta_r[12]" id="d2_charla_r">
      <option value="0"></option>
      <option value="1"<?php if($deltas[12]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
      <option value="2"<?php if($deltas[12]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
      <option value="3"<?php if($deltas[12]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
    </select>
		  </td>
		   <td align="center" class="columna_delta_2">
		  <input name="delta_o[12]" type="text" id="d2_charla_o" value="<?php echo $deltas[12]["observacion"];?>" style="width: 100px;" />
		  </td>
		  
		  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
		  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
		  
		   <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[41]["tiempo"] / 60);
			$minutes = ($deltas[41]["tiempo"] % 60);
		  ?>
			<input name="delta[41]" type="text" id="d6_charla_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[41]" id="d6_charla_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[41]" id="d6_charla_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[41]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
			  <option value="2"<?php if($deltas[41]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[41]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input name="delta_o[41]" type="text" id="d6_charla_o" value="<?php echo $deltas[41]["observacion"];?>" style="width: 100px;" />
		  </td>
          </tr>
		 <?php //} ?>
		  <tr class="trPersonaDCC">
		   <td><strong>Personal DCC</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 3 -->
			  <td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[14]["tiempo"] / 60);
				$minutes = ($deltas[14]["tiempo"] % 60);
			  ?>
				<input name="delta[14]" type="text" id="d3_personal_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[14]" id="d3_personal_dcc_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[14]" id="d3_personal_dcc_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[14]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[14]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[14]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[14]" type="text" id="d3_personal_dcc_o" value="<?php echo $deltas[14]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
			  <td class="columna_delta_5">
			  <?php
				$hours = floor($deltas[30]["tiempo"] / 60);
				$minutes = ($deltas[30]["tiempo"] % 60);
			  ?>
				<input name="delta[30]" type="text" id="d5_personal_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_m[30]" id="d5_personal_dcc_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_r[30]" id="d5_personal_dcc_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[30]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[30]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[30]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[30]" type="text" id="d5_personal_dcc_o" value="<?php echo $deltas[30]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <!-- Delta 6 -->
			  <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[38]["tiempo"] / 60);
			$minutes = ($deltas[38]["tiempo"] % 60);
		  ?>
			<input name="delta[38]" type="text" id="d6_personal_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[38]" id="d6_personal_dcc_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[38]" id="d6_personal_dcc_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[38]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[38]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[38]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input type="text" name="delta_o[38]" id="d6_personal_dcc_o" value="<?php echo $deltas[38]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>
		  <tr class="trFluidos">
		   <td><strong>Fluídos</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 3 -->
			  <td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[15]["tiempo"] / 60);
				$minutes = ($deltas[15]["tiempo"] % 60);
			  ?>
				<input name="delta[15]" type="text" id="d3_fluidos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[15]" id="d3_fluidos_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[15]" id="d3_fluidos_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[15]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[15]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[15]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[15]" type="text" id="d3_fluidos_o" value="<?php echo $deltas[15]["observacion"];?>" style="width: 100px;" />
			  </td>

			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
			  <td class="columna_delta_5">
			  <?php
				$hours = floor($deltas[31]["tiempo"] / 60);
				$minutes = ($deltas[31]["tiempo"] % 60);
			  ?>
				<input name="delta[31]" type="text" id="d5_fluidos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_m[31]" id="d5_fluidos_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			   <select name="delta_r[31]" id="d5_fluidos_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[31]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[31]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[31]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[31]" type="text" id="d5_fluidos_o" value="<?php echo $deltas[31]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <!-- Delta 6 -->
			 <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[42]["tiempo"] / 60);
			$minutes = ($deltas[42]["tiempo"] % 60);
		  ?>
			<input name="delta[42]" type="text" id="d6_fluidos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[42]" id="d6_fluidos_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[42]" id="d6_fluidos_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[42]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[42]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[42]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input name="delta_o[42]" type="text" id="d6_fluidos_o" value="<?php echo $deltas[42]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>
		  <tr class="trReparacionDiagnostico">
		   <td><strong>Reparación & Diagnóstico</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[16]["tiempo"] / 60);
				$minutes = ($deltas[16]["tiempo"] % 60);
			  ?>
				<input name="delta[16]" type="text" id="d3_reparacion_diagnostico_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[16]" id="d3_reparacion_diagnostico_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[16]" id="d3_reparacion_diagnostico_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[16]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[16]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[16]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[16]" type="text" id="d3_reparacion_diagnostico_o" value="<?php echo $deltas[16]["observacion"];?>" style="width: 100px;" />
			  </td>

			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
			  <td class="columna_delta_5">
			  <?php
				$hours = floor($deltas[32]["tiempo"] / 60);
				$minutes = ($deltas[32]["tiempo"] % 60);
			  ?>
				<input name="delta[32]" type="text" id="d5_reparacion_diagnostico_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_m[32]" id="d5_reparacion_diagnostico_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_r[32]" id="d5_reparacion_diagnostico_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[32]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[32]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[32]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[32]" type="text" id="d5_reparacion_diagnostico_o" value="<?php echo $deltas[32]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
		  </tr>
		  <tr class="trMantencion">
		   <td><strong>Mantención</strong></td>
			  <!-- Delta 4 t 6			  -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 6 -->
			  <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[43]["tiempo"] / 60);
			$minutes = ($deltas[43]["tiempo"] % 60);
		  ?>
			<input name="delta[43]" type="text" id="d6_mantencion_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[43]" id="d6_mantencion_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[43]" id="d6_mantencion_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[43]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[43]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[43]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input type="text" name="delta_o[43]" id="d6_mantencion_o" value="<?php echo $deltas[43]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>
		  <tr class="trRepuestos">
		   <td><strong>Repuestos</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[17]["tiempo"] / 60);
				$minutes = ($deltas[17]["tiempo"] % 60);
			  ?>
				<input name="delta[17]" type="text" id="d3_repuestos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 17px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[17]" id="d3_repuestos_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[17]" id="d3_repuestos_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[17]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[17]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[17]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[17]" type="text" id="d3_repuestos_o" value="<?php echo $deltas[17]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
				<td class="columna_delta_5">
				<?php
				$hours = floor($deltas[33]["tiempo"] / 60);
				$minutes = ($deltas[33]["tiempo"] % 60);
			  ?>
				<input name="delta[33]" type="text" id="d5_repuestos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 17px; width: 35px;" />
				</td>
			  <td class="columna_delta_5">
			  <select name="delta_m[33]" id="d5_repuestos_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_r[32]" id="d5_repuestos_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[32]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[32]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[32]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[32]" type="text" id="d5_repuestos_o" value="<?php echo $deltas[32]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <!-- Delta 6 -->
			  <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[44]["tiempo"] / 60);
			$minutes = ($deltas[44]["tiempo"] % 60);
		  ?>
			<input name="delta[44]" type="text" id="d6_repuestos_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[44]" id="d6_repuestos_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[44]" id="d6_repuestos_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[44]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[44]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input type="text" name="delta_o[44]" id="d6_repuestos_o" value="<?php echo $deltas[44]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>	
		  <tr class="trHerramientasDCC">
		   <td><strong>Herramientas DCC</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 3 -->
				<td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[18]["tiempo"] / 60);
				$minutes = ($deltas[18]["tiempo"] % 60);
			  ?>
				<input name="delta[18]" type="text" id="d3_herramientas_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 18px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[18]" id="d3_herramientas_dcc_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[18]" id="d3_herramientas_dcc_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[18]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[18]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[18]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[18]" type="text" id="d3_herramientas_dcc_o" value="<?php echo $deltas[18]["observacion"];?>" style="width: 100px;" />
			  </td>
			 <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
			  <td class="columna_delta_5">
			  <?php
				$hours = floor($deltas[34]["tiempo"] / 60);
				$minutes = ($deltas[34]["tiempo"] % 60);
			  ?>
				<input name="delta[34]" type="text" id="d5_herramientas_dcc_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 18px; width: 35px;" />
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_m[34]" id="d5_herramientas_dcc_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_r[34]" id="d5_herramientas_dcc_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[34]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[34]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[34]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[34]" type="text" id="d5_herramientas_dcc_o" value="<?php echo $deltas[34]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_6" align="center" bgcolor="#CCCCCC" ></td>
		  </tr>
		  <tr class="trHerramientasPanol">
		   <td><strong>Herramientas Pañol</strong></td>
			  <!-- Delta Personal DCC, 3, 5 y 6 -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 3 -->
			  <td align="center" class="columna_delta_3"  >
				<?php
				$hours = floor($deltas[19]["tiempo"] / 60);
				$minutes = ($deltas[19]["tiempo"] % 60);
			  ?>
				<input name="delta[19]" type="text" id="d3_herramientas_panol_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 19px; width: 35px;" />
			  </td>
			  <td align="center" class="columna_delta_3">
				<select name="delta_m[19]" id="d3_herramientas_panol_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
				</td>
			  <td align="center" class="columna_delta_3">
			   <select name="delta_r[19]" id="d3_herramientas_panol_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[19]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[19]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[19]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td align="center" class="columna_delta_3">
				<input name="delta_o[19]" type="text" id="d3_herramientas_panol_o" value="<?php echo $deltas[19]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_4" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 5 -->
			  <td class="columna_delta_5">
			  <?php
				$hours = floor($deltas[35]["tiempo"] / 60);
				$minutes = ($deltas[35]["tiempo"] % 60);
			  ?>
				<input name="delta[35]" type="text" id="d5_herramientas_panol_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 19px; width: 35px;" />
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_m[35]" id="d5_herramientas_panol_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <select name="delta_r[35]" id="d5_herramientas_panol_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[35]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[35]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[35]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_5">
			  <input name="delta_o[35]" type="text" id="d5_herramientas_panol_o" value="<?php echo $deltas[35]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <!-- Delta 6 -->
			<td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[47]["tiempo"] / 60);
			$minutes = ($deltas[47]["tiempo"] % 60);
		  ?>
			<input name="delta[47]" type="text" id="d6_herramientas_panol_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[47]" id="d6_herramientas_panol_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[47]" id="d6_herramientas_panol_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[47]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
			  <option value="2"<?php if($deltas[47]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[47]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input type="text" name="delta_o[47]" id="d6_herramientas_panol_o" value="<?php echo $deltas[47]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>
		  <tr class="trOperador">
		   <td><strong>Operador</strong></td>
			  <!-- Delta 4			  -->
			  <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			  <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 4 -->
			  <td class="columna_delta_4">
			  <?php
				$hours = floor($deltas[23]["tiempo"] / 60);
				$minutes = ($deltas[23]["tiempo"] % 60);
			  ?>
				<input name="delta[23]" type="text" id="d4_operador_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td class="columna_delta_4">
				<select name="delta_m[23]" id="d4_operador_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_4">
				<select name="delta_r[23]" id="d4_operador_r">
				  <option value="0"></option>
				  <option value="2"<?php if($deltas[23]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[23]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_4">
				<input type="text" name="delta_o[23]" id="d4_operador_o" value="<?php echo $deltas[23]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
			  
			  <td align="center" class=" columna_delta_6">
			<?php
			$hours = floor($deltas[46]["tiempo"] / 60);
			$minutes = ($deltas[46]["tiempo"] % 60);
		  ?>
			<input name="delta[46]" type="text" id="d6_operador_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[46]" id="d6_operador_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[46]" id="d6_operador_r">
			  <option value="0"></option>
			  <option value="2"<?php if($deltas[46]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
			  <option value="3"<?php if($deltas[46]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
			<input type="text" name="delta_o[46]" id="d6_operador_o" value="<?php echo $deltas[46]["observacion"];?>" style="width: 100px;" />
		  </td>
			  
		  </tr>
		  <tr class="trInfraestructura">
		  <td><strong>Infraestructura</strong></td>
			  <!-- Delta 4 t 6			  -->
			 <td colspan="4" class="columna_delta_1" align="center" bgcolor="#CCCCCC" ></td>
			 <td colspan="4" class="columna_delta_2" align="center" bgcolor="#CCCCCC" ></td>
			 <td colspan="4" class="columna_delta_3" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 4 -->
			  <td class="columna_delta_4">
			  <?php
				$hours = floor($deltas[26]["tiempo"] / 60);
				$minutes = ($deltas[26]["tiempo"] % 60);
			  ?>
				<input name="delta[26]" type="text" id="d4_infraestructura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
			  </td>
			  <td class="columna_delta_4">
			  <select name="delta_m[26]" id="d4_infraestructura_m">
				 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
				  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
				  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
				  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
				</select>
			  </td>
			  <td class="columna_delta_4">
				<select name="delta_r[26]" id="d4_infraestructura_r">
				  <option value="0"></option>
				  <option value="1"<?php if($deltas[26]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[26]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[26]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
				</select>
			  </td>
			  <td class="columna_delta_4">
				<input type="text" name="delta_o[26]" id="d4_infraestructura_o" value="<?php echo $deltas[26]["observacion"];?>" style="width: 100px;" />
			  </td>
			  <td colspan="4" class="columna_delta_5" align="center" bgcolor="#CCCCCC" ></td>
			  <!-- Delta 6 -->
			  <td align="center" class=" columna_delta_6"  >
			<?php
			$hours = floor($deltas[45]["tiempo"] / 60);
			$minutes = ($deltas[45]["tiempo"] % 60);
		  ?>
			<input name="delta[45]" type="text" id="d6_infraestructura_h" value="<?php echo $hours;?>" size="4" pattern="[0-9]*" min="0" style="height: 16px; width: 35px;" />
		  </td>
		  <td align="center" class="columna_delta_6">
            <select name="delta_m[45]" id="d6_infraestructura_m">
             <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
			  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
			  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
			  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
            </select>
            </td>
		  <td align="center" class="columna_delta_6">
           <select name="delta_r[45]" id="d6_infraestructura_r">
			  <option value="0"></option>
			  <option value="1"<?php if($deltas[45]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
				  <option value="2"<?php if($deltas[45]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
				  <option value="3"<?php if($deltas[45]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
			</select>
		  </td>
		   <td align="center" class="columna_delta_6">
		  <input type="text" name="delta_o[45]" id="d6_infraestructura_o" value="<?php echo $deltas[45]["observacion"];?>" style="width: 100px;" />
		  </td>
		  </tr>
		  
     </table>
	 </div> 
<?php //$hayAvanceHoras = false; ?>
<div class="widget">
<div class="title show" show="avance_horas" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Avance Horas</h6></div>
	<table cellpadding="0" cellspacing="0" width="100%" class="sTable avance_horas" style="display: none;">
		<tr>
			<td width="50%" style="font-weight: bold;">Detalle</td>
			<td style="font-weight: bold;">Horas</td>
			<td style="font-weight: bold;">Observación</td>	
		</tr>
		<?php if (@$intervencion["Planificacion"]["tipointervencion"]!='EX') { 
			if (@$json["AvanceHoraPotenciaTotal"]=='')
			{
				@$json["AvanceHoraPotenciaTotal"] = '0';
			}
		?>
		<tr>
			<td width="50%">Avance horas potencia</td>
			<td><input type="text" id="AvanceHoraPotenciaTotal" name="AvanceHoraPotenciaTotal" size="10" value="<?php echo @$json["AvanceHoraPotenciaTotal"];?>" style="text-align: right;" /></td>
			<td><input type="text" id="AvanceHoraPotenciaTotalObservacion" name="AvanceHoraPotenciaTotalObservacion" style="width:80%;" value="<?php echo @$json["AvanceHoraPotenciaTotalObservacion"];?>" /></td>	
		</tr>
		<?php } ?>
		<?php //if (@$json["AvanceHoraPotenciaOEM"] != "" && @$json["AvanceHoraPotenciaOEM"] != "0") { 
			//$hayAvanceHoras = true;
		?>
		<tr>
			<td width="50%">Avance horas OEM</td>
			<td><input type="text" id="AvanceHoraPotenciaOEM" name="AvanceHoraPotenciaOEM" size="10" value="<?php echo @$json["AvanceHoraPotenciaOEM"];?>" style="text-align: right;" /></td>
			<td><input type="text" id="AvanceHoraPotenciaOEMObservacion" name="AvanceHoraPotenciaOEMObservacion" style="width:80%;"  value="<?php echo @$json["AvanceHoraPotenciaOEMObservacion"];?>" /></td>	
		</tr>
		<?php //} ?>
		<?php //if (@$json["AvanceHoraPotenciaMINA"] != "" && @$json["AvanceHoraPotenciaMINA"] != "0") {
			//$hayAvanceHoras = true;
		?>
		<tr>
			<td width="50%">Avance horas MINA</td>
			<td><input type="text" id="AvanceHoraPotenciaMINA" name="AvanceHoraPotenciaMINA" size="10" value="<?php echo @$json["AvanceHoraPotenciaMINA"];?>" style="text-align: right;" /></td>
			<td><input type="text" id="AvanceHoraPotenciaMINAObservacion" name="AvanceHoraPotenciaMINAObservacion" style="width:80%;"  value="<?php echo @$json["AvanceHoraPotenciaMINAObservacion"];?>" /></td>	
		</tr>
		<?php //} ?>
		<?php if (@$intervencion["Planificacion"]["tipointervencion"]!='EX') { 
		?>
		<tr>
			<td width="50%">Avance horas DCC</td>
			<td><input type="text" id="AvanceHoraPotenciaDCC" name="AvanceHoraPotenciaDCC" size="10" value="<?php echo @$json["AvanceHoraPotenciaDCC"];?>" style="text-align: right;" /></td>
			<td><input type="text"  id="AvanceHoraPotenciaDCCObservacion" name="AvanceHoraPotenciaDCCObservacion" style="width:80%;"  value="<?php echo @$json["AvanceHoraPotenciaDCCObservacion"];?>" /></td>	
		</tr>
		<?php } ?>
		<?php// if(!$hayAvanceHoras) { ?>
		<!--<tr>
			<td colspan="3">No hay información ingresada</td>
		</tr>-->
		<?php //} ?>
	</table>
</div>
<?php $hayFluidos = false; ?>
<div class="widget">
<div class="title show" show="fluidos" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Fluídos</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable fluidos" style="display: none;">
	 <?php //if (@$json["AceiteMotor"] != "" && @$json["AceiteMotor"] != "0") { 
		//$hayFluidos = true;
	 ?>
	 <tr>
    <td width="55%">Aceite Motor</td>
    <td>
        <input type="text" id="AceiteMotor" name="AceiteMotor" size="5" value="<?php echo @$json["AceiteMotor"];?>" />
    </td>
    <td>litros</td>
    <td width="210px"><select id="AceiteMotorTipo" name="AceiteMotorTipo">
		<option value="0"></option>
		<option value="C"<?php echo @$json["AceiteMotorTipo"] == 'C' ? ' selected="selected"':'';?>>CAMBIO</option>
		<option value="R"<?php echo @$json["AceiteMotorTipo"] == 'R' ? ' selected="selected"':'';?>>RELLENO</option>
    </select></td>
  </tr>
  <?php //} ?>
  <?php //if (@$json["AvanceHoraPotenciaMINA"] != "" && @$json["AvanceHoraPotenciaMINA"] != "0") { 
		//$hayFluidos = true;
  ?>
  <tr>
    <td>Aceite Reserva</td>
    <td><input type="text" id="AceiteReserva" name="AceiteReserva" size="5" value="<?php echo @$json["AceiteReserva"];?>" /></td>
    <td>litros</td>
    <td><select id="AceiteReservaTipo" name="AceiteReservaTipo">
	<option value="0"></option>
		<option value="C"<?php echo @$json["AceiteReservaTipo"] == 'C' ? ' selected="selected"':'';?>>CAMBIO</option>
		<option value="R"<?php echo @$json["AceiteReservaTipo"] == 'R' ? ' selected="selected"':'';?>>RELLENO</option>
    </select></td>
  </tr>
  <?php //} ?>
  <?php //if (@$json["Refrigerante"] != "" && @$json["Refrigerante"] != "0") { 
		//$hayFluidos = true;
  ?>
  <tr>
    <td>Refrigerante</td>
    <td><input type="text" id="Refrigerante" name="Refrigerante" size="5" value="<?php echo @$json["Refrigerante"];?>" /></td>
    <td>litros</td>
    <td><select id="RefrigeranteTipo" name="RefrigeranteTipo">
	<option value="0"></option>
		<option value="C"<?php echo @$json["RefrigeranteTipo"] == 'C' ? ' selected="selected"':'';?>>CAMBIO</option>
		<option value="R"<?php echo @$json["RefrigeranteTipo"] == 'R' ? ' selected="selected"':'';?>>RELLENO</option>
    </select></td>
  </tr>
  <?php //} ?>
  <?php //if (@$json["Combustible"] != "" && @$json["Combustible"] != "0") { 
		//$hayFluidos = true;
  ?>
  <tr>
    <td>Combustible</td>
    <td><input type="text" id="Combustible" name="Combustible" size="5" value="<?php echo @$json["Combustible"];?>" /></td>
    <td>litros</td>
    <td><select id="CombustibleTipo" name="CombustibleTipo">
	<option value="0"></option>
		<option value="C"<?php echo @$json["CombustibleTipo"] == 'C' ? ' selected="selected"':'';?>>CAMBIO</option>
		<option value="R"<?php echo @$json["CombustibleTipo"] == 'R' ? ' selected="selected"':'';?>>RELLENO</option>
    </select></td>
  </tr>
  <?php // } ?>
  <?php //if (@$json["Zerex"] != "" && @$json["Zerex"] != "0") { 
		//$hayFluidos = true;
  ?>
  <tr>
    <td>Zerex</td>
    <td><input type="text" id="Zerex" name="Zerex" size="5" value="<?php echo @$json["Zerex"];?>" /></td>
    <td>litros</td>
    <td>&nbsp;</td>
  </tr>
  <?php //} ?>
  <?php //if (@$json["Resurs"] != "" && @$json["Resurs"] != "0") { 
		//$hayFluidos = true;
  ?>
  <tr>
    <td>Resurs</td>
    <td><input type="text" id="Resurs" name="Resurs" size="5" value="<?php echo @$json["Resurs"];?>" /></td>
    <td>tarros</td>
    <td>&nbsp;</td>
  </tr>
  <?php //} ?>
  <?php //if(!$hayAvanceHoras) { ?>
	<!--<tr>
		<td colspan="4">No hay información ingresada</td>
	</tr>-->
	<?php //} ?>
 </table>
 </div>
	 
<div class="widget">
<div class="title show" show="comentarios" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Comentarios</h6></div>
<p class="comentarios" style="display: none;">
  <textarea name="comentario" rows="2" id="comentario" style="width: 99%;"><?php echo @$comentarios["comentario"];?></textarea>
</p>
</div>

<div class="widget">
<div class="title show" show="codigokch" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Códigos KCH</h6></div>
<p class="codigokch" style="display: none;">
  <textarea name="codigokch" rows="2" id="codigokch" style="width: 99%;"><?php echo @$comentarios["codigo_kch"];?></textarea>
</p>
</div>

<?php if(count($backlogs)>0){ ?>
<div class="widget">
<div class="title show" show="backlog" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Backlogs</h6></div>
<input type="hidden" name="backlogs" id="backlogs" value="1" />
<table cellpadding="0" cellspacing="0" width="100%" class="sTable backlog" style="display: none;">
	<thead>
		<tr>
			<td style="width: 110px;text-align:center;">Folio</td>
			<td style="width: 110px;text-align:center;">Criticidad</td>
			<td style="width: 300px;text-align:center;">Responsable</td>
			<td>Part Number</td>
			<td>Comentario</td>
		</tr>
	</thead>
	<tbody>
	<?php
		/*
	[Elemento] => Array
		(
			[id] => 85
			[folio] => RI2031008805201705280100
			[sistema_id] => 3
			[subsistema_id] => 11
			[subsistema_posicion_id] => 12
			[elemento_id] => 698
			[id_elemento] => 20
			[elemento_posicion_id] => 12
			[diagnostico_id] => 5
			[solucion_id] => 
			[tipo_id] => 
			[pn_saliente] => IGFzZGFzZA==
			[pn_entrante] => 
			[tiempo] => 
			[tipo_registro] => 2
		)
		*/
		foreach($backlogs as $backlog){
			//print_r($backlog);
	?>
		<tr>
			<td style="text-align:center;"><?php echo $backlog["Backlog"]["id"];?></td>
			<td style="text-align:center;">
				<select disabled="disabled">
					<option value="1"<?php echo($backlog["Backlog"]["criticidad"]=='1'?' selected="selected" ':''); ?>>Alto</option>
					<option value="2"<?php echo($backlog["Backlog"]["criticidad"]=='2'?' selected="selected" ':''); ?>>Medio</option>
					<option value="3"<?php echo($backlog["Backlog"]["criticidad"]=='3'?' selected="selected" ':''); ?>>Bajo</option>
				</select>
			</td>
			<td style="text-align:center;">
				<select disabled="disabled">
					<option value="1"<?php echo($backlog["Backlog"]["responsable_id"]=='1'?' selected="selected" ':''); ?>>DCC</option>
					<option value="2"<?php echo($backlog["Backlog"]["responsable_id"]=='2'?' selected="selected" ':''); ?>>OEM</option>
					<option value="3"<?php echo($backlog["Backlog"][""]=='3'?' selected="selected" ':''); ?>>MINA</option>
				</select>
			</td>
			<td>
				<input type="text" readonly="readonly" value="<?php echo $backlog["IntervencionElementos"]["pn_saliente"];?>" style="width:90%;" />
			</td>
			<td>
				<input type="text" readonly="readonly" value="<?php echo $backlog["Backlog"]["comentario"];?>" style="width:90%;" />
			</td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>
<input type="hidden" value="" id="bkdel" name="bkdel" />
</div>
<?php }else{ ?>
	<input type="hidden" name="backlogs" id="backlogs" value="0" />
<?php } ?>
<div class="widget">
<div class="title show" show="turno" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Turno</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable turno" style="display: none;">
	<tbody>
		<tr>
			<td style="width: 300px;text-align:left;">Supervisor Responsable</td>
			<td style="text-align:left;"><?php echo $util->getUsuarioInfo($intervencion['Planificacion']["supervisor_responsable"]);?></td>
		</tr>
		<tr>
			<td style="text-align:left;">Turno</td>
			<td style="text-align:left;"><?php echo $intervencion['Planificacion']["turno"];?></td>
		</tr>
		<tr>
			<td style="text-align:left;">Período</td>
			<td style="text-align:left;"><?php echo $intervencion['Planificacion']["periodo"] == 'D' ? 'DÍA':'NOCHE';?></td>
		</tr>
	</tbody>
</table>
</div>
<?php
	if(count($modificaciones)>0){
?>
	<div class="widget">
<div class="title show" show="modificaciones" style="cursor: pointer;"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Registro de Modificaciones</h6></div>
<table cellpadding="0" cellspacing="0" width="100%" class="sTable modificaciones" style="display: none;">
	<thead>
		<tr>
			<td style="width: 50px;text-align:center;">N°</td>
			<td style="width: 200px;text-align:center;">Fecha</td>
			<td style="text-align:center;">Usuario</td>
		</tr>
	</thead>
	<tbody>
	<?php
		$i=1;
		foreach($modificaciones as $v){
	?>
		<tr>
			<td style="text-align:center;"><?php echo $i++;?></td>
			<td style="text-align:center;"><?php echo $v["LogIntervencion"]["fecha"];?></td>
			<td style="text-align:left;"><?php echo $v["Usuario"]["nombres"];?> <?php echo $v["Usuario"]["apellidos"];?></td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>
</div>
<?php
	}
?>
<p style="color: #e0e0e0; text-align: right;">
<?php
	if (preg_match('/iphone/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/android/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/ipad/i', $intervencion["Planificacion"]["log"])) {
        echo "Móvil";
    } elseif (preg_match('/linux/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } elseif (preg_match('/macintosh|mac os x/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } elseif (preg_match('/windows|win32/i', $intervencion["Planificacion"]["log"])) {
        echo "Computador";
    } else {
		echo "Computador";
	} 
?>
</p>
<p>
	<?php
		if($intervencion['Planificacion']['estado']==4&&$this->Session->read('esAdmin')=='1'){
	?>
		<input type="button" name="desaprobar_intervencion" value="Desaprobar" style="float: right; margin-right: 3px;margin-left:3px;" class="greenB desaprobar_intervencion" />
	<?php
	}
	?>
	<?php
		if($intervencion['Planificacion']['estado']!=7&&$intervencion['Planificacion']['estado']!=6){
	?>
		<input type="button" name="volver" value="Volver" class="basic" onclick="window.location='/Planificacion/historial';" style="float: left;" />
		<input type="button" name="imprimir" value="Imprimir" class="blueB imprimir" f="<?php echo $intervencion['Planificacion']['id'];?>" style="float: right;" />
		
	<?php
		}else{
	?>
	<input type="button" name="volver" value="Volver" class="basic" onclick="window.location='/Planificacion/historial';" style="float: left;" />
	<?php 
		if ($intervencion['Planificacion']['padre'] == null || $intervencion['Planificacion']['padre'] == '') {
	?>
		<input type="submit" name="guardar_detalle" value="Siguiente" class="blueB v_d" style="float: right;" />
	<?php } else { ?>
		<?php
			$folio_pendiente = $util->getEstadoPadre($intervencion['Planificacion']['padre']);
			if ($folio_pendiente == '') {
		?>
		<input type="submit" name="guardar_detalle" value="Siguiente" class="blueB v_d" style="float: right;" />
		<?php } else { ?>
		<input type="submit" name="guardar_detalle" value="Siguiente" disabled="disabled"  class="blueB v_d" style="float: right;" title="Debe aprobar intervención con Folio <?php echo $folio_pendiente;?> para poder revisar esta intervención." />
		<?php } ?>
	<?php } ?>
	<input type="submit" name="guardar_detalle" value="Guardar" style="float: right; margin-right: 3px;" class="greenB v_d" />
	<?php } ?>
	<?php if($this->Session->read('esAdmin')=='1'){ ?>
		<!--
			<input type="button" name="eliminar" value="Eliminar" class="redB elint" h="<?php echo $util->folioHijos($intervencion['Planificacion']['hijo']);?>" e="<?php echo $intervencion['Planificacion']['estado'];?>" p="<?php echo @$intervencion['Planificacion']['padre'];?>" f="<?php echo $intervencion['Planificacion']['id'];?>" style="float: right;margin-right: 3px;" />
		-->
	<?php } ?>
</p>
</form>
</div> 