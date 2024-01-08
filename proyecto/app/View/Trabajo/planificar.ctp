<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Planificar trabajo</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/Trabajo/Planificar" class="horizontal-form" method="post" role="form" id="form-planificar">
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
                           
                            <div class="form-group">
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
                        
                            <div class="form-group">
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
                            
                            <div class="form-group">
                                <div class="row">
                                    <label for="informacion_backlog" class="col-md-4 control-label">Información backlog</label>
                                    <div class="col-md-8">
                                        <textarea name="data[informacion_backlog]" class="form-control" required="required" id="informacion_backlog" disabled="disabled" readonly="readonly"></textarea>
                                    </div>
                                </div>
                            </div>	   
                            <div class="form-group">
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
                          
                            <div class="form-group">
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
                                <button type="submit" class="btn blue btn-validar">
                                    <i class="fa fa-save"></i> Guardar</button>
                                <button type="button" class="btn default" data-target="#static2" data-toggle="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
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