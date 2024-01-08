<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase"><?php echo $componente_str;?></span>
					<span class="caption-helper">Mediciones</span>
				</div>
			</div>
                    <div class="portlet-body form">
                        <form action="" id="formulario" onsubmit="javascript:document.getElementById('valida_').disabled = true" class="horizontal-form" method="post">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Equipo</label>
                                                <select class="form-control" name="unidad_ids" id="unidad_ids" <?php echo (isset($_GET['unidad']) ? "readonly='true'" : "")?> required=""> 
                                                    <option value="">Seleccione</option>
                                                    <?php
                                                    foreach($unidades as $unidad) { ?>
                                                    <option value="<?php echo $unidad["Unidad"]["id"];?>" <?php echo ($unidad["Unidad"]["id"] == $_GET['unidad'] ? "selected=\"selected\"" : ""); ?>><?php echo $unidad["Unidad"]["unidad"];?></option>

                                                    <?php  } ?>
                                                </select> 
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($_GET['corr'])){ ?>
                                            <div class="col-md-3 col-md-3 col-sm-12 col-xs-12">
                                                <input type="hidden" name="" id="" value="0">
                                                <div class="form-group">
                                                    <label>Intervención</label>
                                                    <div class="input-group date">
                                                        <input type="text" id="intervencion" name="intervencion" required="" value="<?php echo  $_GET['corr'] ?>" class="form-control" readOnly="true">
                                                        <span class="input-group-addon">
                                                            MP
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            
                                        
                                            <div class="col-md-3 col-md-3 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <?php if($selecciona_fecha){?>
                                                        <input type="date" id="fecha" name="fecha" required="required" value="<?php echo $fecha; ?>" class="form-control">
                                                    <?php }else { ?>
                                                        <input type="date" id="fecha" name="fecha" required="required" value="<?php echo $fecha; ?>" class="form-control" readOnly="true">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }else{?>
                                            <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                <input type="hidden" name="intervencion" id="intervencion" value="0">
                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <?php if($selecciona_fecha){?>
                                                        <input type="date" id="fecha" name="fecha" required="required" value="<?php echo $fecha; ?>" class="form-control">
                                                    <?php }else { ?>
                                                        <input type="date" id="fecha" name="fecha" required="required" value="<?php echo $fecha; ?>" class="form-control" readOnly="true">
                                                    <?php } ?>
                                                    
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if(isset($imagen) && $imagen != "") { ?>
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Imagen de Referencia</label>
                                            <br>
                                            <img src="/images/mediciones/<?php echo $imagen ?>" height="300" width="300">
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <!--<div class="col-md-5 col-md-5 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Posición <?php echo $componente_str;?></label>
                                                <select class="form-control" name="posiciones_ids" id="posiciones_ids" required=""> 

                                                <?php
                                                    foreach($posiciones as $posicion) { ?>
                                                    <option value="<?php echo $posicion["numero"];?>" <?php echo ($posicion["numero"] == $posicion_id ? "selected": ""); ?>><?php echo $posicion["nombre"];?></option>

                                                    <?php  } ?>
                                                </select> 
                                            </div>
                                        </div>-->
                                        
                                        <?php 
                                            if(count($posiciones) > 1){
                                                foreach($posiciones as $posicion) { ?>
                                                    <div class="clearfix"></div>
                                                    <div class="form-group">
                                                        <div class="col-md-3 col-md-3 col-sm-3 col-xs-3">
                                                            <div class="form-group">
                                                                <label>Pos.</label>
                                                                <input type="text" id="posiciones_ids[<?php echo $posicion["numero"] ?>]" name="posiciones_ids[]" required="required" value="<?php echo $posicion["nombre"]; ?>" class="form-control" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-md-6 col-sm-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label>Medición <?php echo $componente_str . " (".$uni_med.")"; ?></label>
                                                                <?php if($ingresa_medicion){?>
                                                                    <input type="number" id="medicion[<?php echo $posicion["numero"] ?>]" name="medicion[]" required="required" step="0.01" min="0" value="<?php //echo $val_medicion; ?>" class="form-control">
                                                                <?php }else { ?>
                                                                    <input type="number" id="medicion[<?php echo $posicion["numero"] ?>]" name="medicion[]" required="required" step="0.01" min="0" value="0" class="form-control" disabled="disabled">
                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="uni_med" name="uni_med" required="" value="<?php echo $uni_med; ?>" class="form-control" readonly="">
                                                        <div class="col-md-3 col-md-3 col-sm-3 col-xs-3">
                                                            <div class="form-group">
                                                                <label>Cambio</label>
                                                                <!--<input type="checkbox" id="cambios[<?php echo $posicion["numero"] ?>]" name="cambios[]" value="1" class="form-control">-->
                                                                <select id="cambios[<?php echo $posicion["numero"] ?>]" name="cambios[]" class="form-control">
                                                                <?php
                                                                    foreach($opciones_cambio as $op) { ?>
                                                                    <option value="<?php echo $op["numero"];?>"><?php echo $op["nombre"];?></option>

                                                                    <?php  } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php  } 
                                            } else {?>
                                                <div class="col-md-3 col-md-3 col-sm-3 col-xs-3">
                                                    <div class="form-group">
                                                        <label>Pos.</label>
                                                        <input type="text" id="posiciones_ids[<?php echo $posicion["numero"] ?>]" name="posiciones_ids[]" required="" value="<?php echo $posicion["nombre"]; ?>" class="form-control" readonly="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Medición</label>
                                                        <?php if($ingresa_medicion){?>
                                                            <input type="number" id="medicion" name="medicion[]" required="required" step="0.01" min="0" value="<?php echo $val_medicion; ?>" class="form-control">
                                                        <?php }else { ?>
                                                            <input type="number" id="medicion" name="medicion[]" required="required" step="0.01" min="0" value="0" class="form-control" disabled="disabled">
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-md-3 col-sm-3 col-xs-3">
                                                    <div class="form-group">
                                                        <label>u.m.</label>
                                                        <input type="text" id="uni_med" name="uni_med" required="" value="<?php echo $uni_med; ?>" class="form-control" readonly="">
                                                    </div>
                                                </div>
                                             <?php  } ?>
                                    </div>
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php if(isset($especiales)){ ?>
                                            <?php echo $especiales; ?>
                                        <?php }?>
                                    </div>
                                    
                                    <input type="hidden" name="flota" id="flota" value="<?php echo $flota;?>" class="form-control">
                                    <input type="hidden" name="componente" id="componente" value="<?php echo $componente;?>" class="form-control">
                                    <input type="hidden" name="medi_inicial" id="medi_inicial" value="<?php echo $medi_inicial;?>" class="form-control">
                                    
                                    <input type="hidden" name="optimo_max" id="optimo_max" value="<?php echo $optimo_max ?>" class="form-control">
                                    <input type="hidden" name="optimo_alerta" id="optimo_alerta" value="<?php echo $optimo_alerta ?>" class="form-control">
                                    
                                    <input type="hidden" name="medio_max" id="medio_max" value="<?php echo $medio_max ?>" class="form-control">
                                    <input type="hidden" name="medio_alerta" id="medio_alerta" value="<?php echo $medio_alerta ?>" class="form-control">

                                    <input type="hidden" name="alto_max" id="alto_max" value="<?php echo $alto_max ?>" class="form-control">
                                    <input type="hidden" name="alto_alerta" id="alto_alerta" value="<?php echo $alto_alerta ?>" class="form-control">

                                    <input type="hidden" name="motivo_cambio" id="motivo_cambio" value="">
                                    <input type="hidden" name="cambios[]" id="cambios" value="">

                                    <input type="hidden" name="faena_seleccionada" id="faena_seleccionada" value="<?php  echo $this->Session->read("faena_id");?>">
                                     <?php if(isset($alert)){ ?>
                                        <div class="col-md-12 col-md-12 col-sm-12 col-xs-12" id="alerta">
                                            <div id="chart_div"></div>
                                            <?php echo $alert; ?>
                                        </div>
                                    <?php }?>
                                </div>
                                
                            </div>
                            <div class="form-actions right">
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 right">
                                    <?php 
                                        if(isset($block) && $block == true){ ?>
                                            <button type="button" class="btn dark btn-block" disabled="disabled"><i class="fa fa-arrow-right"></i> Validar</button>
                                        
                                        <?php } else {
                                            if($compara_medicion){
                                                if($cambios){?>
                                                    <button type="button" id="valida_" class="btn dark btn-block" onclick="validar()"><i class="fa fa-arrow-right"></i> Validar y guardar</button>
                                                <?php } else {?>
                                                    <button type="button" id="valida_" class="btn dark btn-block" onclick="submitform()"><i class="fa fa-arrow-right"></i> Validar y guardar</button>
                                                <?php }
                                            } else {?>
                                                    <button type="submit" id="valida_" class="btn dark btn-block"><i class="fa fa-aenviarrrow-right"></i> Validar y guardar</button>
                                            <?php }?>

                                            
                                        <?php }?> 
                                    <br>
                                    <div class="form-group" style="text-align: center;">
                                        <?php if (isset($_GET['corr'])){ ?>
                                            <?php #echo $this->Html->link("Volver", array('action' => 'medicion_mantencion', $_GET['unidad']), array('class' => 'center btn btn-outline red btn-block')); ?>
                                            <a href="/Medicion/medicion_mantencion<?php echo "?flota=".$flota."&unidad=".$_GET['unidad']."&corr=".$_GET['corr'] ?>" class="center btn btn-outline red btn-block">Volver</a>
                                        <?php }else{ ?>
                                            <?php echo $this->Html->link("Volver", array('action' => 'selectflota', $componente), array('class' => 'center btn btn-outline red btn-block')); ?>
                                        <?php } ?>
                                     </div>
                                </div>
                            </div>
                            <div id="static_desactivar" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false" aria-hidden="false">
                                <div class="modal-body">
                                    <div id="alerta2">
                                        
                                    </div>
                                    <hr>
                                    <div>
                                        <label>¿Se realizará cambio?</label>
                                        <div class="form-group">
                                            <select id="cambio2" name="cambio2" class="form-control" onchange="oculta();">
                                                <option value="0">No</option>
                                                <option value="1">SI</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="mc" style="display:none;">
                                        <label>Motivo por el que se realiza el cambio</label>
                                        <div class="form-group">
                                            <select id="motivo_cambio2" name="motivo_cambio2" class="form-control">
                                            <?php
                                                foreach($motivos_cambios as $mc) { ?>
                                                <option value="<?php echo $mc["numero"];?>"><?php echo $mc["nombre"];?></option>

                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="valida_2" class="btn dark btn-block" onclick="submitform();">Aceptar</button>
                                    <button type="button" data-dismiss="modal" onclick="$('#static_desactivar').hide();" class="btn btn-outline red btn-block" id="closed">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
</div>


<script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    if($("#intervencion").val() != 0){
        $('#unidad_ids option:not(:selected)').attr('disabled',true);
    }
    
    /*$("#fecha").datepicker({
        loseText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        format: 'yyyy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });*/
        
    //$(".mask").inputmask('Regex', {regex: "^[0-9]{1,6}(\\.\\d{1,2})?$"});
    $(document).on("input", ".mask", function (e) {
        this.value = this.value.replace(",", ".");
    });
    
    function poblaselects(select, arreglo, idkey, idvalue){
        var domselect =  document.getElementsByName(select)[0];
        for (value in arreglo) {
            var option = document.createElement("option");
            option.text = arreglo[value][idvalue];
            option.value = arreglo[value][idkey];
            domselect.add(option);
        }
    }

    function oculta(){
        if($("#cambio2").val() == 1){
            document.getElementById("mc").style.display = 'block';
        }else{
            document.getElementById("mc").style.display = 'none';
        }
    };

    function validar(){
        
        
        var med = $("#medicion").val().replace(",", ".");
        var med_ini = $("#medi_inicial").val().replace(",", ".");
        
        var optimo = $("#optimo_max").val().replace(",", ".");
        var medio = $("#medio_max").val().replace(",", ".");
        var alto = $("#alto_max").val().replace(",", ".");
        
        if($("#unidad_ids").val() == "" || $("#unidad_ids").val() == 0){
            alert("Debe selecionar un equipo");
            $('#closed').click();
            return false;
        }
        
        if($("#medicion").val() == "" || $("#medicion").val() == 0){
            alert("Debe ingresar una medicion valida");
            $('#closed').click();
            return false;
        }

        var delta = parseFloat(med - med_ini)
        
        if(delta <= optimo){
            $("#alerta2").html('<img src="/img/ok.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i> ÉXITO!</strong>' +
                    $('#optimo_alerta').val() +
                '</div>');
        }else if(delta <= medio){
            $("#alerta2").html('<img src="/img/medio.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-exclamation"></i> ALERTA! </strong>  -'+
                    $('#medio_alerta').val() +
                '</div>');
        }else if(delta <= alto){
            $("#alerta2").html('<img src="/img/alto.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-times"></i> ERROR!</strong>  -' +
                    $('#alto_alerta').val() +
                '</div>');
        }else{
            $("#alerta2").html('<img src="/img/alto.png" style="display:block; margin-left: auto; margin-right: auto;" alt="ok" ><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-times"></i> ERROR!</strong>  -' +
                    $('#alto_alerta').val() +
                '</div>');
        }
        
        $('#static_desactivar').modal('show');
    }

    function submitform(){
        document.getElementById("valida_").disabled = true;
        
        if($("#unidad_ids").val() == "" || $("#unidad_ids").val() == 0){
            alert("Debe selecionar un equipo");
            $('#closed').click();
            document.getElementById("valida_").disabled = false;
            return false;
        }
        
        if($("#medicion").val() == "" || $("#medicion").val() == 0){
            alert("Debe ingresar una medicion valida");
            $('#closed').click();
            document.getElementById("valida_").disabled = false;
            exit;
        }
        
        /** vaida filtros **/
        var pos = <?php echo count($posiciones) ?>;
        if( Number.isInteger(pos) && pos > 1){
            for(i = 1; i <= pos ; i++ ){
                var valor = document.getElementById("medicion[" + i + "]").value;
                
                if(valor == "" || valor == 0){
                    alert("Debe ingresar una medicion valida");
                    $('#closed').click();
                    document.getElementById("valida_").disabled = false;
                    return false;
                }
            }
        }
        
        
        $("#cambio").val($("#cambio2").val());
        if($("#cambio2").val() == 1){
            $("#motivo_cambio").val($("#motivo_cambio2").val());
            $("#cambios").val("SI");
        }else{
            $("#motivo_cambio").val();
            $("#cambios").val("NO");
        }
        

        $("#formulario").submit();
    }
    

    google.charts.load('current', {packages: ['corechart', 'line']});
    
    <?php if(count($grafico) > 0) {?>
        google.charts.setOnLoadCallback(grafico);
    <?php } ?>

    function grafico() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'X');
      data.addColumn('number', 'P.S.');
      data.addColumn('number', 'Medición');

      data.addRows([
            <?php
                
                foreach($grafico as $value) {
                    echo "[new Date('".$value[0]['fechastr']."'), ". $grafico[0][0]['medicion'].", ".$value[0]['medicion']."],";
                }
            ?>
      ]);

      var options = {
        hAxis: {
            title: 'Medición',
            format: 'dd/MM/yyyy',
            gridlines: {count: 5}

        },
        vAxis: {
          title: 'Medición'
        },
        backgroundColor: '#FFFFFF'
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>