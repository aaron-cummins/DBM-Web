<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Agregar registro</span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form action="" class="horizontal-form" method="post" role="form">

                    <div class="form-group">
                        <div class="row">
                            <label for="nombres" class="col-md-2 control-label">Nombre completo</label>
                            <div class="col-md-4">
                                <input name="data[nombre]" class="form-control" required="required" type="text" id="nombre" value="<?php echo @$data["Prebacklog_envioEmail"]["nombre"]; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="correo_electronico" class="col-md-2 control-label">Correo electrónico</label>
                            <div class="col-md-4"> 
                                <input name="data[email]" class="form-control" required="required" type="email" id="email" value="<?php echo @$data["Prebacklog_envioEmail"]["email"]; ?>">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <label for="cargo" class="col-md-2 control-label">Cargo</label>
                            <div class="col-md-4">
                                <input name="data[cargo]" class="form-control" required="required" type="text" id="cargo" value="<?php echo @$data["Prebacklog_envioEmail"]["cargo"]; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="faenas" class="col-md-2 control-label">Faenas asignadas</label>
                            <div class="col-md-4">
                                <span class="multiselect-native-select">
                                    <select class="form-control selectpicker" multiple="" name="faenas[]" id="faenas" required="required">
                                        <?php
                                        foreach ($faenas as $key => $value) {
                                            if (in_array($key, $faenas_email)) {
                                                $selected = ' selected="selected"';
                                            } else {
                                                $selected = '';
                                            }
                                            ?>
                                            <option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Es Receptor?</label>
                            <div class="col-md-4">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" id="receptor" name="data[receptor]" value="1" class="md-check" <?php echo ($data["Prebacklog_envioEmail"]["receptor"] == '1' ? 'checked' : ''); ?>>
                                        <label for="receptor">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Receptor </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="nombre" class="col-md-2 control-label">Estado</label>
                            <div class="col-md-4">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" id="e" name="data[e]" value="1" class="md-check" <?php echo ($data["Prebacklog_envioEmail"]["e"] == '1' ? 'checked' : ''); ?>>
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
                                <button type="button" class="btn default" onclick="window.location = '/PrebacklogEnvioEmail';">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>