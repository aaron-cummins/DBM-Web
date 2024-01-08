
<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Agregar Parámetro</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form action="" class="horizontal-form" method="post" role="form">
                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo $data["Prebacklog_alertaParametro"]["id"]; ?>">
                    <input name="data[fecha_creacion]" class="form-control" type="hidden" id="fecha_creacion" value="<?php echo $data["Prebacklog_alertaParametro"]["fecha_creacion"]; ?>">

                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 control-label">Tipo Alerta</label>
                            <div class="col-md-4">
                                <select class="form-control" name="data[alerta_id]" id="alerta_id">
                                    <option value="">Todos</option>
                                    <?php foreach ($alertas as $key => $value) { ?>
                                        <option <?php echo ($data["Prebacklog_alertaParametro"]["alerta_id"] == $key ? "selected=\"selected\"" : ''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Parámetro</label>
                            <div class="col-md-4">
                                <input name="data[nombre]" class="form-control" required="required" type="text" id="nombre" value="<?php echo $data["Prebacklog_alertaParametro"]["nombre"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2 control-label">Criticidad</label>
                            <div class="col-md-4">
                                <select class="form-control" name="data[criticidad_id]" id="criticidad_id">
                                    <option value="" selected="selected">Todos</option>
                                    <option value="1"<?php echo $data["Prebacklog_alertaParametro"]["criticidad_id"] == '1' ? "selected=\"selected\"" : ""; ?>>Alto</option>
                                    <option value="2"<?php echo $data["Prebacklog_alertaParametro"]["criticidad_id"] == '2' ? "selected=\"selected\"" : ""; ?>>Medio</option>
                                    <option value="3"<?php echo $data["Prebacklog_alertaParametro"]["criticidad_id"] == '3' ? "selected=\"selected\"" : ""; ?>>Bajo</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="unidad_medida" class="col-md-2 control-label">Unidad de medida</label>
                            <div class="col-md-4">
                                <input name="data[unidad_medida]" class="form-control" required="required" maxlength="50" type="text" id="unidad_medida" value="<?php echo $data["Prebacklog_alertaParametro"]["unidad_medida"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Estado</label>
                            <div class="col-md-4">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" id="e" name="data[e]" value="1" class="md-check" <?php echo ($data["Prebacklog_alertaParametro"]["e"] == '1' ? 'checked' : ''); ?>>
                                        <label for="e">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Estado </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-4">
                            <div class="form-actions">
                                <button type="submit" class="btn blue">
                                    <i class="fa fa-save"></i> Guardar</button>
                                <button type="button" class="btn default" onclick="window.location = '/Prebacklog_alertaParametro';">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>