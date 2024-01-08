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
                    <span class="caption-subject font-blue-hoki bold uppercase">Crear Plan de acción</span>
                    <span class="caption-helper">(planes de acción)</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="horizontal-form" method="post">
                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo @$data["id"]; ?>"  />
                    <input name="data[motor_id]" class="form-control" type="hidden" id="motor_id"  />
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input name="data[nombre]" class="form-control" required="required" id="nombre" value="<?php echo @$data["nombre"]; ?>" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="data[descripcion]" class="form-control" required="required" id="descripcion"><?php echo @$data["descripcion"]; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Modelo motor</label>
                                    <select class="form-control" name="data[motor_id_]" required="required" id="motor_id_">
                                        <option value="">Seleccione una opción</option>
                                        <?php foreach ($motores as $key => $value) { ?>
                                            <option value="<?php echo $value["Motor"]["id"]; ?>" <?php echo @$data["motor_id"] == $value["Motor"]["id"] ? 'selected="selected"':''; ?> ><?php echo $value["Motor"]["nombre"]; ?> <?php echo $value["TipoAdmision"]["nombre"]; ?> <?php echo $value["TipoEmision"]["nombre"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sistema</label>
                                    <select class="form-control" name="data[sistema_id]" required="required" id="sistema_id">
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
                                    <select class="form-control" name="data[subsistema_id]" required="required" id="subsistema_id">
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pos. Subsistema</label>
                                    <select class="form-control" name="data[subsistema_posicion_id]" required="required" id="subsistema_posicion_id">
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
                                    <input name="data[id_elemento]" class="form-control" type="text" id="id_elemento" value="<?php echo $elemento['IntervencionElementos']["id_elemento"]; ?>"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Elemento</label>
                                    <select class="form-control" name="data[elemento_id]" required="required" id="elemento_id" />
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pos. Elemento</label>
                                    <select class="form-control" name="data[elemento_posicion_id]" required="required" id="elemento_posicion_id">
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Diagnóstico</label>
                                    <select class="form-control" name="data[diagnostico_id]" required="required" id="diagnostico_id">
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Part number</label>
                                    <input name="data[pn_saliente]" class="form-control" type="text" id="pn_saliente" value="<?php echo $elemento['IntervencionElementos']["pn_saliente"]; ?>"  />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Categoría síntoma</label>
                                    <select class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id">
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
                                    <select class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id">
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

                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/PlanAccion/';">Volver</button>
                        <button type="submit" class="btn blue"><i class="fa fa-filter"></i> Guardar</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

