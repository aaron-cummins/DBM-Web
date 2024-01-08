
<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Agregar categoría de prebacklog</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form action="" class="horizontal-form" method="post" role="form">
                    <input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo $data["Prebacklog_categoria"]["id"]; ?>">
                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Nombre Categoría</label>
                            <div class="col-md-4">
                                <input name="data[nombre]" class="form-control" required="required" type="text" id="nombre" value="<?php echo $data["Prebacklog_categoria"]["nombre"]; ?>">
                                <input name="data[fecha_creacion]" class="form-control" type="hidden" id="fecha_creacion" value="<?php echo $data["Prebacklog_categoria"]["fecha_creacion"]; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Estado</label>
                            <div class="col-md-4">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" id="e" name="data[e]" value="1" class="md-check" <?php echo ($data["Prebacklog_categoria"]["e"] == '1' ? 'checked' : ''); ?>>
                                        <label for="e">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Activa </label>
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
                                <button type="button" class="btn default" onclick="window.location = '/Prebacklog_categoria';">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>