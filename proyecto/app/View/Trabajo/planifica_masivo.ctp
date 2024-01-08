<div class="row">
    <?php if (isset($exitos) && strlen($exitos) > 2 ) { ?>
        <div class="alert alert-success">
            <strong>Exito!</strong> Trabajos planificados con éxito. <b><?php echo $exitos; ?></b>
        </div>
    <?php } 
    
    if (isset($errores) && strlen($errores) > 2) { ?>
	<div class="alert alert-warning">
            <strong>Alerta!</strong> La planificacion ya existe, por favor verifica los datos en el historial. </br> <b><?php echo $errores; ?></b>
	</div>
    <?php } ?>
    
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Planificar trabajos</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label for="faena_id" class="col-md-4 control-label">Faena</label>

                                    <div class="col-md-8">
                                        <select class="form-control" name="data[faena_id]" required="required" id="faena_id"> 
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($faenas as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                        
                            <!--<h3 class="form-section"></h3>-->
                            <div class="form-group">
                                <div class="row">
                                    <label for="flota_id" class="col-md-4 control-label">Flota</label>

                                    <div class="col-md-8">
                                        <select class="form-control" name="data[flota_id]" required="required" id="flota_id"> 
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($flotas as $key => $value) { ?>
                                                <option value="<?php echo $value["FaenaFlota"]["faena_id"]; ?>_<?php echo $value["FaenaFlota"]["flota_id"]; ?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"]; ?>"><?php echo $value["Flota"]["nombre"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                         
                            <div class="form-group">
                                <div class="row">
                                    <label for="flota_id" class="col-md-4 control-label">Equipo</label>

                                    <div class="col-md-8">
                                        <select class="form-control" name="data[unidad_id]" id="unidad_id" required="required"> 
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($unidades as $key => $value) { ?>
                                                <option value="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>_<?php echo $value["Unidad"]["id"]; ?>" faena_flota="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>" motor_id="<?php echo $value["Unidad"]["motor_id"]; ?>"><?php echo $value["Unidad"]["unidad"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <div class="row">
                                    <label for="esn" class="col-md-4 control-label">ESN</label>

                                    <div class="col-md-8">
                                        <input name="data[esn]" class="form-control" required="required" type="text" id="esn" readonly="readonly"  />
                                    </div>
                                </div>
                            </div>	
                        
                            <div class="form-group">
                                <div class="row">
                                    <label for="fecha" class="col-md-4 control-label">Fecha programada</label>

                                    <div class="col-md-8">
                                        <input name="data[fecha]" class="form-control" required="required" type="date" id="fecha" />
                                    </div>
                                </div>
                            </div>	
                        
                            <div class="form-group">
                                <div class="row">
                                    <label for="hora" class="col-md-4 control-label">Hora programada</label>
                                    <div class="col-md-3">
                                        <select class="form-control" name="data[hora]" required="required" id="hora"> 
                                            <option value="">Seleccione hora</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="data[minuto]" required="required" id="minuto"> 
                                            <option value="">Seleccione minuto</option>
                                            <option value="00">00</option>
                                            <option value="15">15</option>
                                            <option value="30">30</option>
                                            <option value="45">45</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="data[periodo]" required="required" id="periodo"> 
                                            <option value="">Seleccione período</option>
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                        
                            <div class="form-group">
                                <div class="row">
                                    <label for="tiempo_estimado_hora" class="col-md-4 control-label">Tiempo estimado de trabajo</label>
                                    <div class="col-md-4">
                                        <input name="data[tiempo_estimado_hora]" class="form-control" placeholder="Ingrese hora" min="0" required="required" type="number" id="tiempo_estimado_hora"  />
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="data[tiempo_estimado_minuto]" required="required" id="tiempo_estimado_minuto"> 
                                            <option value="">Seleccione minuto</option>
                                            <option value="00">00</option>
                                            <option value="15">15</option>
                                            <option value="30">30</option>
                                            <option value="45">45</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <label for="tipointervencion" class="col-md-4 control-label">Tipo intervención</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="data[tipointervencion]" required="required" id="tipointervencion" disabled="disabled"> 
                                            <option value="">Seleccione tipo intervención</option>
                                            <option value="MP">MP</option>
                                            <option value="RP">RP</option>
                                            <option value="OP">OP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                           
                            <div class="form-group tmp">
                                <div class="row">
                                    <label for="tipomantencion" class="col-md-4 control-label">Tipo mantención</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="data[tipomantencion]" required="required" id="tipomantencion" disabled="disabled"> 
                                            <option value="">Seleccione tipo mantención</option>
                                            <option value="250">250</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                            <option value="1500">Overhaul</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	

                            <div class="form-group">
                                <div class="row">
                                    <label for="os_sap" class="col-md-4 control-label">Número OS SAP</label>
                                    <div class="col-md-8">
                                        <input name="data[os_sap]" class="form-control" type="text" id="os_sap"/>
                                    </div>
                                </div>
                            </div>	
                       
                            <div class="form-group nmp">
                                <div class="row">
                                    <label for="existe_backlog" class="col-md-4 control-label">¿Backlog?</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="data[existe_backlog]" required="required" id="existe_backlog" disabled="disabled"> 
                                            <option value="">Seleccione una opción</option>
                                            <option value="S">SI</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                        
                            <div class="form-group nmp">
                                <div class="row">
                                    <label for="backlog_id" class="col-md-4 control-label">Backlog</label>
                                    <div class="col-md-8">
                                            <!--<select class="backlog_id form-control" name="data[backlog_id]" required="required" id="backlog_id" disabled="disabled">
                                                    <option value="">Seleccione backlog</option>
                                            </select>-->

                                        <span class="multiselect-native-select">
                                            <select class="backlog_id form-control selectpicker" multiple="" name="backlog_id[]" id="backlog_id" disabled="disabled">
                                                <option value="">Seleccione backlog</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            </div>					
                            
                            <div class="form-group nmp">
                                <div class="row">
                                    <label for="informacion_backlog" class="col-md-4 control-label">Información backlog</label>
                                    <div class="col-md-8">
                                        <textarea name="data[informacion_backlog]" class="form-control" required="required" id="informacion_backlog" disabled="disabled" readonly="readonly"></textarea>
                                    </div>
                                </div>
                            </div>	   
                            <div class="form-group nmp">
                                <div class="row">
                                    <label for="categoria_sintoma_id" class="col-md-4 control-label">Categoría síntoma</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id" disabled="disabled"> 
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($categoria_sintoma as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                          
                            <div class="form-group nmp">
                                <div class="row">
                                    <label for="sintoma_id" class="col-md-4 control-label">Síntoma</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id" disabled="disabled"> 
                                            <option value="">Seleccione una opción</option>
                                                <?php foreach ($sintomas as $key => $value) { ?>
                                                <?php if ($value['Sintoma']["codigo"] != "0" && $value['Sintoma']["codigo"] != "") { ?>
                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;">FC <?php echo $value['Sintoma']["codigo"]; ?> <?php echo $value['Sintoma']["nombre"]; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>"  style="display: none;"><?php echo $value['Sintoma']["nombre"]; ?></option>

                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>	
                          
                            <div class="form-group">
                                <div class="row">
                                    <label for="observacion" class="col-md-4 control-label">Observación supervisor</label>
                                    <div class="col-md-8">
                                        <textarea name="data[observacion]" class="form-control" required="required" id="observacion"></textarea>
                                    </div>
                                </div>
                            </div>	

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-actions right">
                                <button type="button" onclick="addrow();" class="btn blue"><i class="fa fa-bars"></i> Agregar</button>
                                <button type="button" class="btn default" onclick="limpiar();">Limpiar</button>
                            </div>
                        </div>
                    </div>
                
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Plan de trabajos</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <form action="" class="horizontal-form" method="post" role="form" id="form-planificar">
                    <div id="table_1_wrapper" class="dataTables_wrapper">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="plan" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Faena</th>
                                                    <th>Flota</th>
                                                    <th>Unidad</th>
                                                    <th>Fec. Prog.</th>
                                                    <th>Hr. Prog.</th>
                                                    <th>T. estimado</th>
                                                    <th>Tipo int.</th>
                                                    <th>Tipo mant.</th>
                                                    <th>Os SAP</th>
                                                    <th>Bcklog</th>
                                                    <th>Cat. Sintoma</th>
                                                    <th>Sintoma</th>
                                                    <th>Observacion</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodytabla">
                                                <?php if(strlen($exitos) >= 2){ ?>
                                                <tr>
                                                    <td colspan="12"><?php echo count(split("," ,$exitos)) . " correctamente ingresados"; ?></td>
                                                </tr>
                                                <?php } ?>
                                                
                                                <?php if(strlen($registros_error) >= 2){ ?>
                                                <tr>
                                                    <td colspan="12"><?php echo count(split("," ,$registros_error)) . " Con errores, no ingresados."; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-actions right">
                                <button type="submit" class="btn blue btn-validar">
                                    <i class="fa fa-save"></i> Guardar</button>
                                <button type="button" class="btn default" data-target="#static2" data-toggle="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
    <div class="modal-body">
        <p> Se guardará una planificación en el sistema. ¿Está seguro? </p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">NO</button>
        <button type="button" data-dismiss="modal" class="btn green btn-guardar">SI</button>
    </div>
</div>
<div id="static2" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
    <div class="modal-body">
        <p> Se va a limpiar formulario, se borrará la información y no se guardará la planificación ¿Está seguro? </p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">NO</button>
        <button type="button" data-dismiss="modal" class="btn green btn-cancelar-reload">SI</button>
    </div>
</div>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var cont = 0;
    function addrow(){
        var faena, flota, equipo,feprog, horprog, testimado, tipoint, tipomant, backlog, catsintoma, sintoma, obs, esn;
        var faenaStr, flotaStr, equipoStr, backlogStr, catsintomaStr, sintomaStr, ossap;
        
        if($("#faena_id").val() != ''){
            var combo = document.getElementById("faena_id");
            faenaStr = combo.options[combo.selectedIndex].text;
            faena = combo.options[combo.selectedIndex].value;
        }else{
            alert("Debe seleccionar una Faena");
            return false;
        }
        
        if($("#flota_id").val() != ''){
            var combo = document.getElementById("flota_id");
            flotaStr = combo.options[combo.selectedIndex].text;
            flota = combo.options[combo.selectedIndex].value;
        }else{
            alert("Debe seleccionar una Flota");
            return false;
        }
        
        if($("#unidad_id").val() != ''){
            var combo = document.getElementById("unidad_id");
            equipoStr = combo.options[combo.selectedIndex].text;
            equipo = combo.options[combo.selectedIndex].value;
        }else{
            alert("Debe seleccionar un equipo");
            return false;
        }
        
        if($("#esn").val() != ''){
            esn = $("#esn").val();
        }else{
            esn = "";
        }
        
        if($("#fecha").val() != ''){
            feprog = $("#fecha").val();
        }else{
            alert("Debe seleccionar una fecha");
            return false;
        }
        
        if($("#hora").val() != '' && $("#minuto").val() != '' && $("#periodo").val() != ''){
            horprog = $("#hora").val() + ":" + $("#minuto").val() + ":00 " + $("#periodo").val();
        }else{
            alert("Debe seleccionar una Hora, minuto y periodo");
            return false;
        }
        
        
        if($("#tiempo_estimado_hora").val() != '' && $("#tiempo_estimado_minuto").val() != ''){
            testimado = $("#tiempo_estimado_hora").val().padStart(2, '0') + ":" + $("#tiempo_estimado_minuto").val();
        }else{
            alert("Debe seleccionar el tiempo estimado con horas y minutos");
            return false;
        }
        
        if($("#tipointervencion").val() != ''){
            tipoint = $("#tipointervencion").val();
        }else{
            alert("Debe seleccionar un tipo de intervención");
            return false;
        }
        
        if($("#tipomantencion").val() != ''){
            tipomant = $("#tipomantencion").val();
        }else{
            if(tipoint == 'MP'){
                alert("Debe seleccionar un tipo de mantención");
                return false;
            }else{
                tipomant = "";
            }
        }
        
        if($('#existe_backlog').val() == 'S'){
            if($("#backlog_id").val() != ''){
                //var combo = document.getElementById("backlog_id");
                //backlogStr =[...$("#backlog_id :selected")].map(e => e.id);
                //backlog = combo.options[combo.selectedIndex].value;

                var selectValue = $("#backlog_id").val() || [];
                backlogStr = selectValue.join(", ");
                backlog = selectValue.join(", ");
            }else{
                backlogStr = "";
                backlog = "";
            }
        }else{
            backlogStr = "";
            backlog = "";
        }
        
        if($("#categoria_sintoma_id").val() != ''){
            var combo = document.getElementById("categoria_sintoma_id");
            catsintomaStr = combo.options[combo.selectedIndex].text;
            catsintoma = combo.options[combo.selectedIndex].value;
        }else{
            if(tipoint != 'MP'){
                alert("Debe seleccionar una categoria de sintoma");
                return false;
            }else{
                catsintomaStr = "";
                catsintoma = "";
            }
        }
        
        if($("#sintoma_id").val() != ''){
            var combo = document.getElementById("sintoma_id");
            sintomaStr = combo.options[combo.selectedIndex].text;
            sintoma = combo.options[combo.selectedIndex].value;
        }else{
            if(tipoint != 'MP'){
                alert("Debe seleccionar un sintoma");
                return false;
            }else{
                sintomaStr = "";
                sintoma = "";
            }
        }
        
        if($("#observacion").val() != ''){
            obs = $("#observacion").val();
        }else{
            obs = "";
        }
        

        ossap = $("#os_sap").val() ? $("#os_sap").val() : 0;

        //faena, flota, equipo,feprog, horprog, testimado, tipoint, tipomant, backlog, catsintoma, sintoma, obs;
        //faenaStr, flotaStr, equipoStr, backlogStr;
        
        fila = "<tr id='row-"+ cont +"'><td>" + faenaStr + 
               
               "<input type='hidden' class='form-control' name='faena_id_[]' id='faena_id["+ cont + "]' value='"+ faena + "'>" +
               "<input type='hidden' class='form-control' name='faena_str_[]' id='faena_str["+ cont + "]' value='"+ faenaStr + "'>" +
               "<input type='hidden' class='form-control' name='flota_id_[]' id='flota_id["+ cont + "]' value='"+ flota + "'>" +
               "<input type='hidden' class='form-control' name='flota_str_[]' id='flota_str["+ cont + "]' value='"+ flotaStr + "'>" +
               "<input type='hidden' class='form-control' name='equipo_id_[]' id='equipo_id["+ cont + "]' value='"+ equipo + "'>" +
               "<input type='hidden' class='form-control' name='equipo_str_[]' id='equipo_str["+ cont + "]' value='"+ equipoStr + "'>" +
               "<input type='hidden' class='form-control' name='esn_[]' id='esn["+ cont + "]' value='"+ esn + "'>" +
               "<input type='hidden' class='form-control' name='fec_prog_[]' id='fec_prog["+ cont + "]' value='"+ feprog + "'>" +
               "<input type='hidden' class='form-control' name='hor_prog_[]' id='hor_prog["+ cont + "]' value='"+ horprog + "'>" +
               "<input type='hidden' class='form-control' name='t_estimado_[]' id='t_estimado["+ cont + "]' value='"+ testimado + "'>" +
               "<input type='hidden' class='form-control' name='tipo_int_[]' id='tipo_int["+ cont + "]' value='"+ tipoint + "'>" +
               "<input type='hidden' class='form-control' name='tipo_mant_[]' id='tipo_mant["+ cont + "]' value='"+ tipomant + "'>" +
               "<input type='hidden' class='form-control' name='os_sap_[]' id='os_sap_["+ cont + "]' value='"+ ossap + "'>" +
               "<input type='hidden' class='form-control' name='backlog_id_[]' id='backlog_id["+ cont + "]' value='"+ backlog + "'>" +
               "<input type='hidden' class='form-control' name='cat_sintoma_[]' id='cat_sintoma["+ cont + "]' value='"+ catsintoma + "'>" +
               "<input type='hidden' class='form-control' name='sintoma_id_[]' id='sintoma_id["+ cont + "]' value='"+ sintoma + "'>" +
               "<input type='hidden' class='form-control' name='obs_[]' id='obs["+ cont + "]' value='"+ obs + "'>" +
                
               "</td>" +
               "<td>" + flotaStr + "</td> " +
               "<td>" + equipoStr + "</td> " +
               "<td>" + feprog + "</td> " +
               "<td>" + horprog + "</td> " +
               "<td>" + testimado + "</td> " +
               "<td>" + tipoint + "</td> " +
               "<td>" + tipomant + "</td> " +
               "<td>" + ossap + "</td> " +
               "<td>" + backlogStr + "</td> " +
               "<td>" + catsintomaStr + "</td> " +
               "<td>" + sintomaStr + "</td> " +
               "<td>" + obs + "</td>" +
               "<td><a class='btn btn-sm red-intense tooltips' href='#' onclick='eliminar(" + cont + ")'  data-original-title='Elinminar prebacklog'><i class='fa fa-trash'></i> </a></td></tr>";
       
       
       $("#bodytabla").append(fila);
       
       cont = cont+1;
       
       limpiar();
    }
    
    function limpiar(){
        //$("#fecha").val("");
        $("#hora").val("");
        $("#minuto").val("");
        $("#periodo").val("");
        $("#tiempo_estimado_hora").val("");
        $("#tiempo_estimado_minuto").val("");
        $("#tipointervencion").val("");
        $("#tipomantencion").val("");
        $("#os_sap").val("");
        $("#backlog_id").val("");
        $("#backlog_id").change();
        $("#categoria_sintoma_id").val("");
        $("#sintoma_id").val("");
        $("#observacion").val("");
        $("#existe_backlog").val("");
    }
    
    function eliminar(id){
        if(confirm("va a eliminar este registro, ¿esta seguro?")){
            $("#row-"+ id).remove();
        }
    }
    
    $("#backlog_id").change( ()=> {
        var sintoma_id = $(this).find("option:selected").attr("sintoma_id");
        var categoria_sintoma_id = $(this).find("option:selected").attr("categoria_sintoma_id");
        
        if(categoria_sintoma_id!='undefined' && categoria_sintoma_id != 'null' && categoria_sintoma_id != '') {
            $("#categoria_sintoma_id").val(categoria_sintoma_id);
            $("#categoria_sintoma_id").change();
        }
        if(sintoma_id!='undefined' && sintoma_id != 'null' && sintoma_id != '') {
            $("#sintoma_id").val(sintoma_id);
            $("#sintoma_id").change();
        }
        
    });
    
    $("#tipointervencion").change( ()=> {
        if($("#tipointervencion").val() == 'MP'){
            $(".tmp").show();
            $(".nmp").hide();
        }else{
            $(".tmp").hide();
            $(".nmp").show();
        }
    });
    
  
    
</script>