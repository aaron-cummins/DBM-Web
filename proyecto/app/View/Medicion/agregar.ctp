<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Medición de puesta en servicio</span>
					<span class="caption-helper">Mediciones puesta en servicio o incial</span>
				</div>
			</div>
                    <div class="portlet-body form">
                       <form action="" class="horizontal-form" method="post">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Faena</label>
                                                    <select class="form-control" name="faena_id" id="faena_id" required=""> 
                                                        <option value="">Seleccione una Faena</option>
                                                            <?php foreach($faenas as $key => $value) { ?>
                                                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                            <?php }?>
                                                    </select>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Flota</label>
                                                    <select class="form-control" name="flota_id" id="flota_id" required="" > 
                                                            <option value="">Seleccione una Flota</option>
                                                            <?php
                                                            foreach($flotas as $key => $value) { ?>
                                                                    <option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
                                                            <?php } ?>
                                                    </select>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Unidad</label>
                                                    <select class="form-control" name="unidad_id" id="unidad_id" required=""> 
                                                            <option value="">Seleccione un Equipo</option>
                                                            <?php
                                                            foreach($unidades as $key => $value) { ?>
                                                                    <option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
                                                            <?php } ?>
                                                    </select>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="form-group">
                                                    <label>Componente</label>
                                                    <select class="form-control" name="componente_id" id="componente_id" required=""> 
                                                            <?php foreach($componentes as $key => $value) { ?>
                                                                    <option um="<?php echo $value["MedicionComponente"]["unidad_medida"];?>" value="<?php echo $value["MedicionComponente"]["id"];?>" ><?php echo $value["MedicionComponente"]["nombre"];?></option>
                                                            <?php } ?>
                                                    </select>
                                            </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha Medición</label>
                                                <input type="date" name="fecha" id="fecha" required="" class="form-control">
                                            </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Posición</label>
                                            <input type="text" name="posicion" id="posicion" required="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 col-md-4 col-sm-9 col-xs-9">
                                        <div class="form-group">
                                            <label>Medición</label>
                                            <input type="text" id="medicion" name="medicion" required="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-md-2 col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label>u. med.</label>
                                            <input type="text" id="uni_med" name="uni_med" required="" class="form-control" value="<?php echo $uni_med; ?>" readonly="">
                                        </div>
                                    </div>
                                     
                                    <?php if(isset($alert)){ ?>
                                        <div class="col-md-12 col-md-12 col-sm-12 col-xs-12" id="alerta">
                                            <div id="chart_div"></div>
                                            <?php echo $alert; ?>
                                        </div>
                                    <?php }?>
                                            
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                            <div class="form-actions">
                                                    <button type="submit" class="btn blue">
                                                            <i class="fa fa-save"></i> Guardar</button>
                                                    <button type="button" class="btn default" onclick="window.location='/Medicion/mediciones';">Cancelar</button>
                                            </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
</div>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

    $("#componente_id").change(() => {
        let select = document.querySelector("#componente_id");
        let um = select.options[select.selectedIndex].getAttribute('um');
        $("#uni_med").val(um);

    });

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

