<?php
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Componente</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/MedicionComponente/grabar" class="horizontal-form" method="post" role="form" enctype="multipart/form-data">
                    <input name="e" class="form-control" type="hidden" id="e" value="2">
                    <input name="id" class="form-control" type="hidden" id="id" value="<?php echo $medicion['id']; ?>">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="control-label bold">Nombre</label>
                                <input type="text" name="nombre" id="nombre" value="<?php echo $medicion['nombre']; ?>"class="form-control" required="required">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold">Unidad de medida</label>
                                <input type="text" name="unidad_medida" id="unidad_medida" value="<?php echo $medicion['unidad_medida']; ?>" class="form-control" required="required">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label bold">medida inicial</label>
                                <input type="text" name="medicion_inicial" id="medicion_inicial" value="<?php echo $medicion['medicion_inicial']; ?>" class="form-control" required="required">
                                 <p class="caption-helper">Completar en caso de que la medida inicial sea la misma para todas las unidades, si no, dejar en 0</p>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="control-label bold">Imagen de referencia</label>
                                <input type="file" name="imagen" id="imagen" value="<?php echo $medicion['imagen']; ?>" class="form-control">
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label bold">Compara medidas?</label>
                                <?php 
                                    if($medicion['compara_medidas']){
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"compara_medidas\" id=\"compara_medidas\" value=\"1\" checked=\"checked\" />";
                                    }else{
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"compara_medidas\" id=\"compara_medidas\" value=\"1\" />";
                                    }
                                ?>
                                <p class="caption-helper">Si necesita comparar la medicion SELECCIONAR, si no, dejar SIN Seleccionar(solo garabar medición)</p>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label bold">Selecciona Fecha?</label>
                                <?php 
                                    if($medicion['selecciona_fecha']){
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"selecciona_fecha\" id=\"selecciona_fecha\" value=\"1\" checked=\"checked\" />";
                                    }else{
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"selecciona_fecha\" id=\"selecciona_fecha\" value=\"1\" />";
                                    }
                                ?>
                                <p class="caption-helper">Si necesita que el usuario ingrese la fecha, seleccionar, si no, esta toma por defecto la actual</p>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label bold">Ingresa Medición?</label>
                                <?php 
                                    if($medicion['ingresa_medicion']){
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"ingresa_medicion\" id=\"ingresa_medicion\" value=\"1\" checked=\"checked\" />";
                                    }else{
                                        echo "<input type=\"checkbox\" class=\"form-control\" name=\"ingresa_medicion\" id=\"ingresa_medicion\" value=\"1\" />";
                                    }
                                ?>
                                <p class="caption-helper">Si Necesita que el usuario ingrese una medicion SELECCIONAR, si no, se saca el campo del formulario y guarda por defecto 0.</p>
                            </div>
                        </div>
                    </div>	
                    <hr>
                    <div class="form-group">
                        <div class="row">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase">Propiedades especiales por motor</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="control-label bold">Motor</label>
                                                <select id="motor" name="motor" class="form-control">
                                                <?php

                                                    foreach($motores as $mc) { ?>
                                                    <option value="<?php echo $mc['Motor']["id"];?>"><?php echo $mc['Motor']["nombre"]. ' ' . $mc['TipoEmision']['nombre'];?></option>

                                                    <?php  } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label bold">Posiciones</label>
                                                <input type="text" name="posiciones_e" id="posiciones_e" value="" class="form-control">
                                                <p class="caption-helper">Separa las posiciones por coma (,)</p>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="control-label bold">Necesita Cambio?</label>
                                                <?php 
                                                    echo "<input type=\"checkbox\" class=\"form-control\" name=\"cambio\" id=\"cambio\" value=\"1\" />";
                                                ?>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <label class="control-label bold">Opciones cambio</label>
                                                <input type="text" name="opciones_cambio" id="opciones_cambio" value="" class="form-control">
                                                <p class="caption-helper">Separa las opciones por coma (,) (Si, No, Uno, Mas de uno)</p>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <label class="control-label bold">Motivo cambio</label>
                                                <input type="text" name="motivo_cambio" id="motivo_cambio" value="" class="form-control">
                                                <p class="caption-helper">Separa los motivos por coma (,)</p>
                                            </div>
                                            <div class="col-md-1">
                                                <br>
                                                <button id="btnGuardarespeciales" type="button" class="btn blue" onclick="addEspecial()">
                                                                    <i class="fa fa-save"></i> Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="especiales">
                                                    <tr>
                                                        <th>motor</th>
                                                        <th>posiciones</th>
                                                        <th>cambio</th>
                                                        <th>opciones</th>
                                                        <th>motivo cambio</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                    <?php
                                                        foreach( $especiales as $registro ) {

                                                        echo "<tr>";
                                                        echo "<td>";
                                                        echo $registro[0]['strmotor']. ' ' . $registro[0]['emision'];
                                                        ?>
                                                            <input type="hidden" class="form-group" value="<?php echo $registro[0]['posiciones']; ?>" id="especial[<?php echo $registro[0]['motor_id']; ?>][posicion]" name="especial[<?php echo $registro[0]['motor_id']; ?>][posicion]">
                                                            <input type="hidden" class="form-group" value="<?php echo $registro[0]['cambio']; ?>" id="especial[<?php echo $registro[0]['motor_id']; ?>][cambio]" name="especial[<?php echo $registro[0]['motor_id']; ?>][cambio]">
                                                            <input type="hidden" class="form-group" value="<?php echo $registro[0]['motor_id']; ?>" id="especial[<?php echo $registro[0]['motor_id']; ?>][motor]" name="especial[<?php echo $registro[0]['motor_id']; ?>][motor]">
                                                            <input type="hidden" class="form-group" value="<?php echo $registro[0]['opciones_cambio']; ?>" id="especial[<?php echo $registro[0]['motor_id']; ?>][motor]" name="especial[<?php echo $registro[0]['motor_id']; ?>][opciones_cambio]">
                                                            <input type="hidden" class="form-group" value="<?php echo $registro[0]['motivo_cambio']; ?>" id="especial[<?php echo $registro[0]['motor_id']; ?>][motivo_cambio]" name="especial[<?php echo $registro[0]['motor_id']; ?>][motivo_cambio]">
                                                        <?php
                                                        echo "</td>";
                                                        echo "<td>{$registro[0]['posiciones']}</td>";
                                                        echo "<td>" . ($registro[0]['cambio'] ? "Si": "No")."</td>";
                                                        echo "<td>{$registro[0]['opciones_cambio']}</td>";
                                                        echo "<td>{$registro[0]['motivo_cambio']}</td>";
                                                        echo "<td align=\"center\" style=\"width: 80px\">";
                                                        echo "<button class='btn' onclick='eliminarFila()'><i class='fa fa-trash'></i></button>";
                                                        echo "</td>";
                                                    echo "</tr>";
                                                    }?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-blue-hoki"></i>
                                        <span class="caption-subject font-blue-hoki bold uppercase">Campos especiales</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="bold control-label">Formulario HTML de condiciones especiales.</label>
                                                <p><pre><u>CONSIDERACIONES</u> <br><u>Fromato:</u> 
        &lt;div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            &lt;div class="form-group">
                &lt;label>Cambio</label>
                &lt;select class="form-control" name="especiales[cambio_aceite]" id="cambio_aceite" required=""> 
                    &lt;option value="NO">NO</option>
                    &lt;option value="SI">SI</option>
                &lt;/select>
            &lt;/div>
        &lt;/div><br>1)INPUT - name: todos los names de elementos deben ser "especiales[nombre_propiedad]". ej: name="especiales[tipo_aceite]" <br>2)SELECT- puede ser dinamico o estatico, para los "value": si son mas de una palabra deben ser separadas po (_). ej: &lt;option value="tipo_aceite_mantencion">Tipo Aceite Mantención&lt;/option><br>3)Ejemplo Script tipo aceite DINAMICO por faena
            &lt;script type="text/javascript">
               $(document).ready(function(){
                const Aceites = [
                {idfaena:7,idaceite: 'Lubrax_Top_Turbo_15w40', Aceite : 'Lubrax Top Turbo 15w40'}, 
                {idfaena:66,idaceite: 'Shell_Rimula_R4L_15w40', Aceite : 'Shell Rimula R4L 15w40'}];
                f = parseInt($("#faena_seleccionada").val());
                ac = Aceites.filter(Aceite => Aceite.idfaena=== f);
                var select = 'especiales[tipo_aceite]';
                poblaselects(select, ac, 'idaceite', 'Aceite');
                **poblaselects -> parametros 1)nombre select, 
                                             2)arreglo filtrado, 
                                             3)nombre Key, 
                                             4)nombee texto a mostrar
                });
                &lt;/script>
                                                </pre></p>
                                                <textarea rows="8" name="especiales" class="form-control" type="text" id="especiales"> <?php echo $medicion['especiales']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <h3 class="form-section"></h3>
                    <div class="alerta alert-success">
                        <br>
                        <h4><span class="font-blue-hoki bold">Rango bajo (óptimo)</span></h4>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div id="alerta_min" class="alert alert-danger" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <strong><i class="fa fa-times"></i> Error!</strong> El valor Max. no puede ser menor.
                                </div>

                                <div class="col-md-2">
                                    <label class="bold control-label">Min.</label>
                                    <input name="optimo_min" class="form-control" required="required" type="text" id="optimo_min" value="<?php echo $medicion['optimo_min']; ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="bold control-label">Max</label>
                                    <input name="optimo_max" class="form-control" required="required" type="text" id="optimo_max" value="<?php echo $medicion['optimo_max']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje email.</label>
                                    <textarea rows="5" name="optimo_mensaje" class="form-control" required="" type="text" id="optimo_mensaje"> <?php echo $medicion['optimo_mensaje']; ?></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje alerta.</label>
                                    <textarea rows="2" name="optimo_alerta" class="form-control" required="" type="text" id="optimo_alerta"> <?php echo $medicion['optimo_alerta']; ?></textarea>
                                </div>
                            </div>
                        </div>	
                    <br>
                    </div>
                    <h3 class="form-section"></h3>
                    <div class="alerta alert-warning">
                        <br>
                        <h4><span class="font-blue-hoki bold">Rango Medio</span></h4>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div id="alerta_medio" class="alert alert-danger" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <strong><i class="fa fa-times"></i> Error!</strong> El valor Max. no puede ser menor.
                                </div>
                                <div id="alerta_medio2" class="alert alert-danger" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <strong><i class="fa fa-times"></i> Error!</strong> El valor min. no puede ser menor que el max del rango bajo (optimo).
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label bold">Min.</label>
                                    <input name="medio_min" class="form-control" required="required" type="text" id="medio_min" value="<?php echo $medicion['medio_min']; ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="bold control-label">Max</label>
                                    <input name="medio_max" class="form-control" required="required" type="text" id="medio_max" value="<?php echo $medicion['medio_max']; ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje.</label>
                                    <textarea rows="5" name="medio_mensaje" class="form-control" required="" type="text" id="medio_mensaje"> <?php echo $medicion['medio_mensaje']; ?></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje alerta.</label>
                                    <textarea rows="2" name="medio_alerta" class="form-control" required="" type="text" id="medio_alerta"> <?php echo $medicion['medio_alerta']; ?></textarea>
                                </div>
                            </div>
                        </div>	
                    <br>
                    </div>
                    <h3 class="form-section"></h3>
                    <div class="alerta alert-danger">
                        <br>
                        <h4><span class="font-blue-hoki bold">Rango Máximo</span></h4>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div id="alerta_alto" class="alert alert-danger" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <strong><i class="fa fa-times"></i> Error!</strong> El valor Max. no puede ser menor.
                                </div>
                                <div id="alerta_alto2" class="alert alert-danger" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <strong><i class="fa fa-times"></i> Error!</strong> El valor min. no puede ser menor que el max del rango medio.
                                </div>
                                <div class="col-md-2">
                                    <label class="bold control-label">Min.</label>
                                    <input name="alto_min" class="form-control" required="required" type="text" id="alto_min" value="<?php echo $medicion['alto_min']; ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="bold control-label">Max</label>
                                    <input name="alto_max" class="form-control" required="required" type="text" id="alto_max" value="<?php echo $medicion['alto_max']; ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje.</label>
                                    <textarea rows="5" name="alto_mensaje" class="form-control" required="" type="text" id="alto_mensaje"> <?php echo $medicion['alto_mensaje']; ?></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="bold control-label">Mensaje alerta.</label>
                                    <textarea rows="2" name="alto_alerta" class="form-control" required="" type="text" id="alto_alerta"> <?php echo $medicion['alto_alerta']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <br>	
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                                <div class="form-actions">
                                        <button id="btnGuardar" type="submit" class="btn blue">
                                                <i class="fa fa-save"></i> Guardar</button>
                                        <button type="button" class="btn default" onclick="window.location='/MedicionComponente';">Cancelar</button>
                                </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>

        </div>

    </div>
</div>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    function addEspecial(){
        var cambio = 'No';
        if($("#posiciones_e").val() == ""){
            alert("Debe ingresar posiciones para el componente, si no, dejar 'unica'");
            return false;
        }

        if(document.getElementById("cambio").checked == true){
            if($("#motivo_cambio").val() == ""){
                alert("Debe ingresar al menos un motivo de cambio para el componente.");
                return false;
            }
            cambio = 'Si';
            cam = true;
        }else{
            cam = false;
        }

        var combo = document.getElementById("motor");
        var motor = $("#motor").val();
        var strmotor = combo.options[combo.selectedIndex].text;
        var p = $("#posiciones_e").val();
        var mc = $("#motivo_cambio").val();
        var opc = $("#opciones_cambio").val();
        
        hiddens = "<input type='hidden' class='form-group' value='"+ motor +"' id='especial["+ motor +"][motor]' name='especial["+ motor +"][motor]'>" +
                    "<input type='hidden' class='form-group' value='"+ p +"' id='especial["+ motor +"][posicion]' name='especial["+ motor +"][posicion]'>" +
                    "<input type='hidden' class='form-group' value='"+ cam +"' id='especial["+ motor +"][cambio]' name='especial["+ motor +"][cambio]'>" +
                    "<input type='hidden' class='form-group' value='"+ mc +"' id='especial["+ motor +"][motivo_cambio]' name='especial["+ motor +"][motivo_cambio]'>" +
                    "<input type='hidden' class='form-group' value='"+ opc +"' id='especial["+ motor +"][opciones_cambio]' name='especial["+ motor +"][opciones_cambio]'>";
        document.getElementById("especiales").insertRow(-1).innerHTML = '<td>'+strmotor+ hiddens +'</td><td>'+p+'</td><td>'+cambio+'</td><td>'+ opc +'</td><td>' + mc + '</td><td><button class="btn"onclick="eliminarFila()"><i class="fa fa-trash"></i></button></td>';

    }

    function eliminarFila(){
        var table = document.getElementById("especiales");
        var rowCount = table.rows.length;
        //console.log(rowCount);

        if(rowCount <= 1)
          alert('No se puede eliminar el encabezado');
        else
          table.deleteRow(rowCount -1);
    }


    $(document).ready(function(){
        $("#optimo_min").focusout(function(){
            if(parseFloat($("#optimo_max").val()) != 0){
                if(parseFloat($("#optimo_max").val()) < parseFloat($("#optimo_min").val())){
                    $("#optimo_max").css('border-color','red');
                    $("#optimo_min").css('border-color','red');
                    $("#alerta_min").show();
                    document.getElementById("btnGuardar").disabled = true;
                }else{
                    $("#optimo_max").css('border-color','');
                    $("#optimo_min").css('border-color','');
                    $("#alerta_min").hide();
                    document.getElementById("btnGuardar").disabled = false;
                }
            }
        });

        $("#optimo_max").focusout(function(){
            if(parseFloat($("#optimo_max").val()) != 0){
                if(parseFloat($("#optimo_max").val()) < parseFloat($("#optimo_min").val())){
                    $("#optimo_max").css('border-color','red');
                    $("#optimo_min").css('border-color','red');
                    $("#alerta_min").show();
                }else{
                    $("#optimo_max").css('border-color','');
                    $("#optimo_min").css('border-color','');
                    $("#alerta_min").hide();
                }
            }
        });


        $("#medio_min").focusout(function(){
            if(parseFloat($("#medio_max").val()) != 0){
                if(parseFloat($("#medio_max").val()) < parseFloat($("#medio_min").val())){
                    $("#medio_max").css('border-color','red');
                    $("#medio_min").css('border-color','red');
                    $("#alerta_medio").show();
                    document.getElementById("btnGuardar").disabled = true;
                }else{
                    $("#medio_max").css('border-color','');
                    $("#medio_min").css('border-color','');
                    $("#alerta_medio").hide();
                    document.getElementById("btnGuardar").disabled = false;
                }
            }

            if(parseFloat($("#medio_min").val()) < parseFloat($("#optimo_max").val())){
                $("#medio_min").css('border-color','red');
                $("#alerta_medio2").show();
                document.getElementById("btnGuardar").disabled = true;
            }else{
                $("#medio_min").css('border-color','');
                $("#alerta_medio2").hide();
                document.getElementById("btnGuardar").disabled = false;
            }
            
        });

        $("#medio_max").focusout(function(){
            if(parseFloat($("#medio_max").val()) != 0){
                if(parseFloat($("#medio_max").val()) < parseFloat($("#medio_min").val())){
                    $("#medio_max").css('border-color','red');
                    $("#medio_min").css('border-color','red');
                    $("#alerta_medio").show();
                }else{
                    $("#medio_max").css('border-color','');
                    $("#medio_min").css('border-color','');
                    $("#alerta_medio").hide();
                }
            }
        });


        $("#alto_min").focusout(function(){
            if(parseFloat($("#alto_max").val()) != 0){
                if(parseFloat($("#alto_max").val()) < parseFloat($("#alto_min").val())){
                    $("#alto_max").css('border-color','red');
                    $("#alto_min").css('border-color','red');
                    $("#alerta_alto").show();
                    document.getElementById("btnGuardar").disabled = true;
                }else{
                    $("#alto_max").css('border-color','');
                    $("#alto_min").css('border-color','');
                    $("#alerta_alto").hide();
                    document.getElementById("btnGuardar").disabled = false;
                }
            }

            if(parseFloat($("#alto_min").val()) < parseFloat($("#medio_max").val())){
                $("#alto_min").css('border-color','red');
                $("#alerta_alto2").show();
                document.getElementById("btnGuardar").disabled = true;
            }else{
                $("#alto_min").css('border-color','');
                $("#alerta_alto2").hide();
                document.getElementById("btnGuardar").disabled = false;
            }
        });

        $("#alto_max").focusout(function(){
            if(parseFloat($("#alto_max").val()) != 0){
                if(parseFloat($("#alto_max").val()) < parseFloat($("#alto_min").val())){
                    $("#alto_max").css('border-color','red');
                    $("#alto_min").css('border-color','red');
                    $("#alerta_alto").show();
                }else{
                    $("#alto_max").css('border-color','');
                    $("#alto_min").css('border-color','');
                    $("#alerta_alto").hide();
                }
            }
        });
    });
</script>

