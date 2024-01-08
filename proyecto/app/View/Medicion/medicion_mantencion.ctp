<?php

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Mediciones Mantenimiento</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="row">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="faena_ids" id="faena_ids" value="<?php echo $faena_id; ?>">
                        <div class="col-md-4">
                            <label for="flota_id" class="col-md-4 control-label">Flota</label>
                            <select class="form-control" name="flota_ids" required="required" id="flota_ids"> 
                                <option value="">Seleccione una opción</option>
                                <?php
                                foreach($flotas as $flota) { ?>
                                        <option <?php echo ($flota['FaenaFlota']["flota_id"] == $_GET['flota']) ? "selected=\"selected\"" : "";?> value="<?php echo $flota['FaenaFlota']["faena_id"];?>_<?php echo $flota['FaenaFlota']["flota_id"];?>" faena_id="<?php echo $flota['FaenaFlota']["faena_id"];?>" motor_id="<?php echo $flota['FaenaFlota']['motor_id'] ?>"><?php echo $flota['Flota']['nombre'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                   
                        

                        <div class="col-md-4">
                            <label for="flota_id" class="col-md-4 control-label">Equipo</label>
                            <select class="form-control" name="unidad_ids" id="unidad_ids" required="required"> 
                                <option value="">Seleccione una opción</option>
                                <?php
                                foreach($unidades as $unidad) { ?>
                                        <option <?php echo ($unidad["Unidad"]["id"] == $_GET['unidad']) ? "selected=\"selected\"" : "";?> value="<?php echo $unidad["Unidad"]["faena_id"];?>_<?php echo $unidad["Unidad"]["flota_id"];?>_<?php echo $unidad["Unidad"]["id"];?>" faena_flota="<?php echo $unidad["Unidad"]["faena_id"];?>_<?php echo $unidad["Unidad"]["flota_id"];?>"><?php echo $unidad["Unidad"]["unidad"];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-4">
                            <label for="flota_id" class="col-md-4 control-label">Correlativo MP</label>
                            <select class="form-control" name="intervencion" required="required" id="intervencion" disabled="true"> 
                                <option value="">Seleccione una opción</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr>
                <!-- Nav tabs -->
                <div class="row">
                    <div class="caption">
                        <i class="icon-check font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase">Seleccione componente a medir</span>
                        <span class="caption-helper"></span>
                    </div>
                    <br>
                    <?php foreach( $componentes as $componente ) { ?>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <a class="btn btn-block dark" onclick="formulario(<?php echo $componente['MedicionComponente']['id']?>);"><?php echo $componente['MedicionComponente']['nombre']; ?></a>
                            <br>
                        </div>
                    <?php } ?>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-check font-blue-hoki"></i>
                            <span class="caption-subject font-blue-hoki bold uppercase">Mediciones ingresadas</span>
                            <span class="caption-helper"></span>
                        </div>
                    </div>
                    <br>
                    <div id="table_1_wrapper" class="dataTables_wrapper">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="medidas" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Componente</th>
                                                    <th>Medición</th>
                                                    <th>Fecha medición</th>
                                                    <th>Posición</th>
                                                    <th>Cambio</th>
                                                    <th>val. PS</th>
                                                    <th>Intervención</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                    //echo "<pre>";
                                    //print_r($registros);
                                    //echo "<pre>";
                                        foreach( $registros as $registro ) {
                                            echo "<tr>";
                                                    echo "<td>{$registro[0]['nombre']}</td>";
                                                    echo "<td>".round($registro[0]['medicion'], 2).' ('.$registro[0]['unidad_medida'].")</td>";
                                                    echo "<td>{$registro[0]['fecha']}</td>";
                                                    echo "<td>{$registro[0]['posicion']}</td>";
                                                    echo "<td>".($registro[0]['cambio'] != 0 ? "NO" : "SI")."</td>";
                                                    echo "<td>".(isset($registro[0]['val_ps']) ? $registro[0]['val_ps'] : $registro[0]['medicion_inicial'])."</td>";
                                                    echo "<td>{$registro[0]['correlativo_intervencion']}</td>";
                                                    echo "</tr>";
                                                }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                <div class="row">
                    <div class="form-group col-sm-12 col-lg-2" style="text-align: center;">
                        <?php echo $this->Html->link("Volver", array('action' => 'index'), array('class' => 'center btn btn-outline red btn-block')); ?>
                    </div>
                    
                </div>
                
                <hr>
            </div>
            
        </div>
    </div>    
    
</div>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
        //$("#unidad_ids").val("<?php echo $faena_id."_".$_GET['flota']."_".$_GET['unidad'] ?>").trigger('change');
        var element = document.getElementById('unidad_ids');
        var event = new Event('change');
        element.dispatchEvent(event);
    });
    
    $("#flota_ids").change(function(){
        if($("#faena_ids").val() != '' && $("#flota_ids").val() != '') {
                var faena_flota = $("#flota_ids").val();
                $("#unidad_ids option").hide();
                $("#unidad_ids option[value='']").show();
                $("#unidad_ids option[faena_flota='"+faena_flota+"']").show();
                $("#unidad_ids").removeAttr("disabled");
                $("#unidad_ids").val("");
        }else{
                $("#unidad_ids").val("");
                $("#unidad_ids option[value!='']").hide();
        }
    });
    
    $("#unidad_ids").change(function(){
        var equipo_id = $("#unidad_ids").val();
        var data = equipo_id.split("_");
        var faena_id = data[0];
        var flota_id = data[1];
        var unidad_id = data[2];
        var corr = <?php echo (isset($_GET['corr']) && $_GET['corr'] != "" ? $_GET['corr'] : 0); ?>;
        html = "<option value=\"\">Seleccione MP</opcion>\n";
        $.get( "/Trabajo/getmp/" + faena_id + "/" + flota_id+ "/" + unidad_id, function(data) {
            var obj = $.parseJSON(data);
            $.each(obj, function(i, item) {
                
                if(corr == item.Planificacion.correlativo_final){
                    html += "<option selected='selected'  data-content=\""+ item.Planificacion.tipointervencion +" - " + item.Planificacion.tipomantencion +" | C: "+ item.Planificacion.correlativo_final +" | Fecha: "+ item.Planificacion.fecha + "\" value="+item.Planificacion.correlativo_final+"\ fecha="+item.Planificacion.fecha+"\></option>\n";				 
                }else{
                    html += "<option  data-content=\""+ item.Planificacion.tipointervencion +" - " + item.Planificacion.tipomantencion +" | C: "+ item.Planificacion.correlativo_final +" | Fecha: "+ item.Planificacion.fecha + "\" value="+item.Planificacion.correlativo_final+"\ fecha="+item.Planificacion.fecha+"\></option>\n";				 
                }
                
            });
            $('#intervencion').attr('disabled', false);
            $('#intervencion').html(html);
            $('#intervencion').selectpicker('refresh');
        });
        
        
    });
    
    
    $("#intervencion").change(function(){
        formulario(0);
    });
    
    
    function formulario(componente){
        var motor;
        var fecha; 
        
        let $select = $('#flota_ids');
        let selecteds = [];
            // Buscamos los option seleccionados
        $select.children(':selected').each((idx, el) => {
          // Obtenemos los atributos que necesitamos
          selecteds.push({
            id: el.id,
            value: el.value,
            motor_id: el.getAttribute("motor_id")
          });
        });
        for(i = 0; i < selecteds.length; i ++){
            motor = selecteds[i]['motor_id'];
        }
        
        
        let $select2 = $('#intervencion');
        let selecteds2 = [];
            // Buscamos los option seleccionados
        $select2.children(':selected').each((idx, el) => {
          // Obtenemos los atributos que necesitamos
          selecteds2.push({
            id: el.id,
            value: el.value,
            fecha: el.getAttribute("fecha")
          });
        });
        for(i = 0; i < selecteds2.length; i ++){
            fecha = selecteds2[i]['fecha'];
        }
        
        var componente = componente;
        var motor;
        
        var equipo_id = $("#unidad_ids").val();
        var data = equipo_id.split("_");
        var unidad_id = data[2];
        var flota = data[1];
        
        
        var correlativo = $("#intervencion").val();
        
        if(flota == 0 || flota == "" ){
            alert("Debe seleccionar una flota.")
            return false;
        }
        if(unidad_id == 0 || unidad_id == "" ){
            alert("Debe seleccionar una intervencion.")
            return false;
        }
        if(correlativo == 0 || correlativo == "" ){
            alert("Debe seleccionar una intervencion.")
            return false;
        }
        
        if(componente == 0){
            url = '/Medicion/medicion_mantencion/?flota='+flota+'&unidad='+unidad_id+'&corr='+correlativo;
        }else{
            url = '/Medicion/medir/'+flota+'/'+componente+'/'+motor+'?unidad='+unidad_id+'&corr='+correlativo+'&fecha='+fecha;
        }
        location.href = url;
    }
</script>