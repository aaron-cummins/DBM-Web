<?php
$faena_session = $this->Session->read("faena_id");
$usuario_id = $this->Session->read('usuario_id');
$cargos = $this->Session->read("PermisosCargos");

App::import('Controller', 'Utilidades');
$app = new UtilidadesController();
?>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Crear Backlog</span>
                    <span class="caption-helper">(Specto, web, software)</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="horizontal-form" method="post">
                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo @$data["id"]; ?>"  />
                    <input name="data[folio]" class="form-control" type="hidden" id="folio" value="<?php echo @$data["folio"]; ?>"  />
                    <input name="data[intervencion_elemento_id]" class="form-control" type="hidden" id="intervencion_elemento_id" value="<?php echo @$elemento["IntervencionElementos"]["id"]; ?>"  />
                    <input name="data[motor_id]" class="form-control" type="hidden" id="motor_id"  />
                    <div class="form-body">
                        
                        <?php if($_GET['idPrebacklog']){ ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Creación a travez de prebacklog N°<?php echo $_GET['idPrebacklog'] ?></b></label>
                                    <input type="hidden" value="<?php echo $_GET['idPrebacklog'] ?>" name="data[idprebacklog]" id="idprebacklog">
                                </div>
                            </div>
                        </div>
                        <?php } else {?>
                            <input type="hidden" value="0" name="data[idprebacklog]" id="idprebacklog">
                        <?php }?>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Creación</label>
                                    <?php if($_GET['idPrebacklog']){ ?>
                                    <input type="text" class="form-control preback" value="<?php echo $lugarcreacion[$creacion_id]?>">
                                    <input type="hidden" class="form-control" name="data[creacion_id]" required="required" id="creacion_id" value="<?php echo $creacion_id ?>"> 
                                    
                                    <?php } else { ?>
                                    <select class="form-control preback" name="data[creacion_id]" required="required" id="creacion_id"> 
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($lugarcreacion as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>" <?php echo $creacion_id == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Faena</label>
                                    
                                    <?php if($_GET['idPrebacklog']){ ?>
                                    <input type="text" class="form-control preback" value="<?php echo $faena?>">
                                    <input type="hidden" class="form-control" name="data[faena_id]" required="required" id="faena_id" value="<?php echo $data['faena_id'] ?>"> 
                                    <?php } else { ?>
                                    <select class="form-control preback" name="data[faena_id]" required="required" id="faena_id"> 
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($faenas as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Flota</label>
                                    <?php if($_GET['idPrebacklog']){ ?>
                                    <input type="text" class="form-control preback" value="<?php echo $flota ?>">
                                    <input type="hidden" class="form-control" name="data[flota_id]" required="required" id="flota_id" value="<?php echo $data['flota_id'] ?>"> 
                                    <?php } else { ?>
                                        <select class="form-control preback" name="data[flota_id]" required="required" id="flota_id"> 
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($flotas as $key => $value) { ?>
                                                <option value="<?php echo $value["FaenaFlota"]["faena_id"]; ?>_<?php echo $value["FaenaFlota"]["flota_id"]; ?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"]; ?>"><?php echo $value["Flota"]["nombre"]; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Equipo</label>
                                    <span class="multiselect-native-select">
                                        <select style="visibility: hidden;" class="form-control" name="unidad" id="unidad_id" >
                                            <?php foreach ($unidades as $key => $value) { ?>
                                                <option <?php echo $selected; ?> value="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>_<?php echo $value["Unidad"]["id"]; ?>" faena_flota="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>" motor_id="<?php echo $value["Unidad"]["motor_id"]; ?>"><?php echo $value["Unidad"]["unidad"]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <select class="form-control selectpicker" multiple="" name="unidad_ids[]" id="unidad_ids" required="required">
                                            <?php
                                            if (isset($unidad_ids) && $unidad_ids != "") {
                                                foreach ($unidades as $key => $value) {
                                                    if(in_array($value["Unidad"]["id"], $unidad_ids)){
                                                        $selected = ' selected="selected"';
                                                    } else {
                                                        $selected = ' style="display:none;" ';
                                                    }


                                                    /*if ($value["Unidad"]["id"] == $unidad_id) {
                                                        $selected = ' selected="selected"';
                                                    } else {
                                                        $selected = ' style="display:none;" ';
                                                    }*/
                                                    ?>

                                                    <option <?php echo $selected; ?> value="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>_<?php echo $value["Unidad"]["id"]; ?>" faena_flota="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>" motor_id="<?php echo $value["Unidad"]["motor_id"]; ?>"><?php echo $value["Unidad"]["unidad"]; ?></option>
                                                <?php }
                                            }
                                            ?>
                                        </select>
                                    </span>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Plan de acción</label>
                                    <select class="form-control" name="data[plan_accion_id]" required="required" id="plan_accion_id">
                                        <option value="0">No aplica</option>
                                        <?php foreach ($planaccion as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>" <?php echo $plan_accion_id == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Criticidad</label>
                                    <select class="form-control" name="data[criticidad_id]" required="required" id="criticidad_id"> 
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" <?php echo @$data["criticidad_id"] == 1 ? "selected=\"selected\"" : ""; ?>>Alto</option>
                                        <option value="2" <?php echo @$data["criticidad_id"] == 2 ? "selected=\"selected\"" : ""; ?>>Medio</option>
                                        <option value="3" <?php echo @$data["criticidad_id"] == 3 ? "selected=\"selected\"" : ""; ?>>Bajo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Responsable</label>
                                    <select class="form-control" name="data[responsable_id]" required="required" id="responsable_id"> 
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" <?php echo @$data["responsable_id"] == 1 ? "selected=\"selected\"" : ""; ?>>DCC</option>
                                        <option value="2" <?php echo @$data["responsable_id"] == 2 ? "selected=\"selected\"" : ""; ?>>OEM</option>
                                        <option value="3" <?php echo @$data["responsable_id"] == 3 ? "selected=\"selected\"" : ""; ?>>MINA</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sistema</label>
                                    <?php if($_GET['idPlanaccion'] !=0){ ?>
                                        <input type="hidden" class="form-control" name="data[sistema_id]" required="required" id="sistema_id_" value="<?php echo $data["sistema_id"] ?>" />
                                    <?php }?>
                                    <select class="form-control" name="data[sistema_id]" required="required" id="sistema_id"  <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($sistemas as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subsistema</label>
                                    <?php if($_GET['idPlanaccion'] !=0){ ?>
                                        <input type="hidden" class="form-control" name="data[subsistema_id]" required="required" id="subsistema_id_" value="<?php echo $data["subsistema_id"] ?>" />
                                    <?php }?>
                                    <select class="form-control" name="data[subsistema_id]" required="required" id="subsistema_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pos. Subsistema</label>
                                    <?php if($_GET['idPlanaccion'] !=0){ ?>
                                        <input type="hidden" class="form-control" name="data[subsistema_posicion_id]" required="required" id="subsistema_posicion_id_" value="<?php echo $data["subsistema_posicion_id"] ?>" />
                                    <?php }?>
                                    <select class="form-control" name="data[subsistema_posicion_id]" required="required" id="subsistema_posicion_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Imagen</label>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <div>
                                        <label>&nbsp;</label>
                                        <img src="" id="imagen-elemento" style="width: 50%;" />&nbsp;
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID elemento</label>
                                    <input name="data[id_elemento]" class="form-control" type="text" id="id_elemento" value="<?php echo $elemento['IntervencionElementos']["id_elemento"]; ?>" <?php if($_GET['idPlanaccion'] !=0){ echo "readonly"; }?>/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Elemento</label>
                                    <select class="form-control" name="data[elemento_id]" required="required" id="elemento_id" <?php if($_GET['idPlanaccion'] !=0){ echo "readonly"; }?>/>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pos. Elemento</label>
                                    <?php if($_GET['idPlanaccion'] !=0){ ?>
                                        <input type="hidden" class="form-control" name="data[elemento_posicion_id]" required="required" id="elemento_posicion_id_" value="<?php echo $data["elemento_posicion_id"] ?>" />
                                    <?php }?>
                                    <select class="form-control" name="data[elemento_posicion_id]" required="required" id="elemento_posicion_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Diagnóstico</label>
                                    <?php if($_GET['idPlanaccion'] !=0){ ?>
                                        <input type="hidden" class="form-control" name="data[diagnostico_id]" required="required" id="diagnostico_id_" value="<?php echo $data["diagnostico_id"] ?>" />
                                    <?php }?>
                                    <select class="form-control" name="data[diagnostico_id]" required="required" id="diagnostico_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Part number</label>
                                    <input name="data[pn_saliente]" class="form-control" type="text" id="pn_saliente" value="<?php echo $elemento['IntervencionElementos']["pn_saliente"]; ?>"  <?php if($_GET['idPlanaccion'] !=0){ echo "readonly"; }?> />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                </div>
                            </div>

                            <?php if ($data["creacion_id"] != 3) { ?>
                            
                                <?php if($_GET['idPrebacklog'] && $_GET['tipo'] == 0 ){ ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Categoría síntoma</label>
                                        <input type="text" class="form-control preback" value="<?php echo $cat_sintoma ?>">
                                        <input type="hidden" class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id" value="<?php echo (@$data["categoria_sintoma_id"] == "" ? $cat_sintomaID : @$data["categoria_sintoma_id"]) ?>"> 
                                   
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Síntoma</label>
                                        <input type="text" class="form-control preback" value="<?php echo $sintoma ?>">
                                        <input type="hidden" class="form-control" name="data[sintoma_ids]" required="required" id="sintoma_ids" value="<?php echo ($data['sintoma_id'] == "" ? $sintomaID : $data['sintoma_id']) ?>" sintoma_categoria_id="<?php echo @$data["categoria_sintoma_id"]; ?>"> 
                                   
                                    </div>
                                </div>
                                
                                <?php }else{ ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Categoría síntoma</label>
                                        <?php if($_GET['idPlanaccion'] !=0){ ?>
                                            <input type="hidden" class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id_" value="<?php echo $data["categoria_sintoma_id"] ?>" />
                                        <?php }?>
                                        <select class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($categoria_sintoma as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>" <?php echo @$data["categoria_sintoma_id"] == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Síntoma</label>
                                        <?php if($_GET['idPlanaccion'] !=0){ ?>
                                            <input type="hidden" class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id_" value="<?php echo $data["sintoma_id"] ?>" />
                                        <?php }?>
                                        <select class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id" <?php if($_GET['idPlanaccion'] !=0){ echo "disabled"; }?>>
                                            <option value="">Seleccione una opción</option>
                                            <?php foreach ($sintomas as $key => $value) { ?>
                                                <?php if ($value['Sintoma']["codigo"] != "0" && $value['Sintoma']["codigo"] != "") { ?>
                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["codigo"]; ?> - <?php echo $value['Sintoma']["nombre"]; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $value['Sintoma']["id"]; ?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"]; ?>" style="display: none;"><?php echo $value['Sintoma']["nombre"]; ?></option>

                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentario</label>
                                    <textarea name="data[comentario]" class="form-control" required="required" id="comentario"><?php echo @$data["comentario"]; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tiempo estimado</label>
                                    <input required="required" name="data[tiempo_estimado_hora]" class="form-control" placeholder="Ingrese hora" min="0" type="number" id="tiempo_estimado_hora" value="<?php echo @$data["tiempo_estimado_hora"]; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <select required="required" class="form-control" name="data[tiempo_estimado_minuto]" id="tiempo_estimado_minuto"> 
                                        <option value="">Seleccione minuto</option>
                                        <option value="00" <?php echo is_numeric(@$data["tiempo_estimado_minuto"]) && @$data["tiempo_estimado_minuto"] == 0 ? "selected=\"selected\"" : ""; ?>>00</option>
                                        <option value="15" <?php echo @$data["tiempo_estimado_minuto"] == 15 ? "selected=\"selected\"" : ""; ?>>15</option>
                                        <option value="30" <?php echo @$data["tiempo_estimado_minuto"] == 30 ? "selected=\"selected\"" : ""; ?>>30</option>
                                        <option value="45" <?php echo @$data["tiempo_estimado_minuto"] == 45 ? "selected=\"selected\"" : ""; ?>>45</option>
                                    </select>									
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/Backlog/';">Cancelar</button>
                        <button type="submit" class="btn blue"><i class="fa fa-filter"></i> Guardar</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    <?php if (isset($_GET['idPrebacklog']) && $_GET['idPrebacklog'] != "") { ?>
    
        window.onload = $(".preback").attr("disabled", "disabled");

    <?php } else { ?>
        
        window.onload = $(".preback").removeAttr("disabled");
        
    <?php } ?>
    
    
</script>