<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Intervención </span>
                    <span class="caption-helper"></span>
                </div>
            </div>
            <div class="portlet-body form">
                <?php if(isset($error_rp_ri)) { ?>
                <div class="alert alert-danger">
                    <strong>Alerta: </strong> <span class=""><?php echo $error_rp_ri; ?></span>
                </div>
                <?php } ?>
                <form action="" class="horizontal-form" method="post" id="form-intervencion">
                    <input type="hidden" name="id" id="id" value="<?php echo $intervencion["Planificacion"]["id"]; ?>" />
                    <input type="hidden" name="folio_inicial" id="folio_inicial" value="<?php echo $intervencion["Planificacion"]["folio"]; ?>" />
                    <input type="hidden" name="motor_id" id="motor_id" value="<?php echo $intervencion["Unidad"]["motor_id"]; ?>" />
                    <input type="hidden" name="faena_id" id="faena_id" value="<?php echo $intervencion["Planificacion"]["faena_id"]; ?>" />
                    <input type="hidden" name="flota_id" id="flota_id" value="<?php echo $intervencion["Planificacion"]["flota_id"]; ?>" />
                    <input type="hidden" name="equipo_id" id="equipo_id" value="<?php echo $intervencion["Planificacion"]["unidad_id"]; ?>" />
                    <input type="hidden" name="correlativo" id="correlativo" value="<?php echo $intervencion["Planificacion"]["correlativo_final"]; ?>" />
                    <input type="hidden" name="estado" id="estado" value="<?php echo $intervencion["Planificacion"]["estado"]; ?>" />
                    <input type="hidden" name="id_fechas" id="id_fechas" value="<?php echo $fechas["IntervencionFechas"]["id"]; ?>" />
                    <input type="hidden" name="id_comentarios" id="id_comentarios" value="<?php echo $comentarios["id"]; ?>" />
                    <input type="hidden" name="tiempo_trabajo" id="tiempo_trabajo" value="" />

                    <input type="hidden" name="elementos" id="elementos" value="<?php echo count($elementos); ?>" />
                    <input type="hidden" name="elementos_reproceso" id="elementos_reproceso" value="<?php echo count($elementos_reproceso); ?>" />
                    <input type="hidden" name="fecha" id="fecha" value="<?php echo date("Y-m-d", strtotime(str_replace("/", "-", $json["FechaInicioGlobal"]))); ?>" />
                    <input type="hidden" name="hora" id="hora" value="<?php echo date("H:i:s", strtotime(str_replace("/", "-", $json["FechaInicioGlobal"]))); ?>" />
                    <input type="hidden" name="fecha_termino" id="fecha_termino" value="<?php echo date("Y-m-d", strtotime(str_replace("/", "-", $json["FechaTerminoGlobal"]))); ?>" />
                    <input type="hidden" name="hora_termino" id="hora_termino" value="<?php echo date("H:i:s", strtotime(str_replace("/", "-", $json["FechaTerminoGlobal"]))); ?>" />
                    <input type="hidden" name="fecha_inicio_global" id="fecha_inicio_global" value="<?php //echo isset($json["FechaInicioGlobal"]) ? $json["FechaInicioGlobal"] : $intervencion["Planificacion"]["fecha"]."T".$intervencion["Planificacion"]["hora"]; ?>" />
                    <input type="hidden" name="fecha_termino_global" id="fecha_termino_global" value="<?php //echo isset($json["FechaTerminoGlobal"]) ? $json["FechaTerminoGlobal"] : $intervencion["Planificacion"]["fecha_termino"]."T".$intervencion["Planificacion"]["hora_termino"]; ?>" />
                    <div class="form-body">
                        <!-- Box tecnicos -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box cummins">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-user"></i>Técnicos participantes</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
                                        <!-- Tecnico que registra la bitácora, no se puede modificar, ni eliminar -->
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-9"> 
                                                    <select name="tecnico-id[]" id="tecnico-id-<?php echo rand(10000, 99999); ?>" class="form-control tecnico-id" required="required">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($usuarios as $usuario) { ?>
                                                            <option value="<?php echo $usuario["Usuario"]["id"]; ?>" <?php echo $json["UserID"] == $usuario["Usuario"]["id"] ? 'selected="selected"' : ''; ?>>
                                                                <?php echo $usuario["Usuario"]["apellidos"] . ' ' . $usuario["Usuario"]["nombres"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2">
                                                    <select class="form-control tipo-tecnico tipo-tecnico-registra" id="tipo-tecnico-<?php echo rand(10000, 99999); ?>" name="tipo-tecnico[]" required="required">
                                                        <option value="">Seleccione una opción</option>
                                                        <?php foreach ($tipos_tecnicos as $tipo) {
                                                            ?>
                                                            <option value="<?php echo $tipo["TipoTecnico"]["id"]; ?>"<?php if (intval($json["TipoTecnicoApoyo01"]) == $tipo["TipoTecnico"]["id"]) {
                                                            echo ' selected="selected"';
                                                        } ?> unico="<?php echo $tipo["TipoTecnico"]["unico"]; ?>" requerido="<?php echo $tipo["TipoTecnico"]["requerido"]; ?>"><?php echo $tipo["TipoTecnico"]["nombre"]; ?></option>
<?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-1">
                                                    <a class="btn btn-sm green tooltips agregar-tecnico" data-container="body" data-placement="top" data-original-title="Agregar técnico"><i class="fa fa-plus"></i>  </a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        for ($i = 2; $i < 11; $i++) {
                                            if (isset($json["TecnicoApoyo" . (str_pad($i, 2, "0", STR_PAD_LEFT))]) && is_numeric($json["TecnicoApoyo" . (str_pad($i, 2, "0", STR_PAD_LEFT))])) {
                                                ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-9">
                                                            <select name="tecnico-id[]" id="tecnico-id-<?php echo rand(10000, 99999); ?>" num="<?php echo $i; ?>" class="form-control tecnico-id" required="required">
                                                                <option value="">Seleccione una opción</option>
                                                                    <?php foreach ($usuarios as $usuario) { ?>
                                                                    <option value="<?php echo $usuario["Usuario"]["id"]; ?>" <?php echo $json["TecnicoApoyo" . (str_pad($i, 2, "0", STR_PAD_LEFT))] == $usuario["Usuario"]["id"] ? 'selected="selected"' : ''; ?>>
                                                                    <?php echo $usuario["Usuario"]["apellidos"] . ' ' . $usuario["Usuario"]["nombres"]; ?>
                                                                    </option>
        <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <select class="form-control tipo-tecnico" id="tipo-tecnico-<?php echo rand(10000, 99999); ?>" name="tipo-tecnico[]" required="required">
                                                                <option value="">Seleccione una opción</option>
                                                                <?php foreach ($tipos_tecnicos as $tipo) { ?>
                                                                    <option value="<?php echo $tipo["TipoTecnico"]["id"]; ?>"<?php if (intval($json["TipoTecnicoApoyo" . (str_pad($i, 2, "0", STR_PAD_LEFT))]) == $tipo["TipoTecnico"]["id"]) {
                                                            echo ' selected="selected"';
                                                        } ?> unico="<?php echo $tipo["TipoTecnico"]["unico"]; ?>" requerido="<?php echo $tipo["TipoTecnico"]["requerido"]; ?>"><?php echo $tipo["TipoTecnico"]["nombre"]; ?></option>
        <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <a class="btn btn-sm red tooltips quitar-tecnico" data-container="body" data-placement="top" data-original-title="Quitar técnico"><i class="fa fa-trash"></i>  </a>
                                                        </div>
                                                    </div>
                                                </div>
        <?php
    }
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Box información base -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box cummins">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-file-text-o"></i>Información base</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Folio</div>
                                                <div class="col-lg-10"><input type="text" id="folio" class="form-control" readonly="readonly" name="folio" value="<?php echo $intervencion["Planificacion"]["id"]; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Correlativo</div>
                                                <div class="col-lg-10"><input type="text" id="correlativo_final" class="form-control" readonly="readonly" name="correlativo_final" value="<?php echo $intervencion["Planificacion"]["correlativo_final"]; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Faena</div>
                                                <div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Faena"]["nombre"]; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Flota</div>
                                                <div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Flota"]["nombre"]; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Equipo</div>
                                                <div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Unidad"]["unidad"]; ?>" /></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Tipo intervención</div>
                                                <div class="col-lg-10">
                                                    <select name="tipointervencion" id="tipointervencion" class="form-control">
                                                        <?php
                                                        if ($intervencion["Planificacion"]["tipointervencion"] == 'MP') {
                                                            ?>
                                                            <option value="MP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'MP' ? ' selected="selected"' : ''; ?>>MP</option>
                                                            <?php
                                                        } else {
                                                            ?>
    <?php if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') { ?>
                                                                <option value="<?php echo $intervencion["Planificacion"]["tipointervencion"]; ?>" selected="selected"><?php echo $intervencion["Planificacion"]["tipointervencion"]; ?></option>
                                                            <?php } else { ?>
                                                                <option value="EX"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'EX' ? ' selected="selected"' : ''; ?>>EX</option>
                                                                <option value="RI"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'RI' ? ' selected="selected"' : ''; ?>>RI</option>
                                                                <option value="RP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'RP' ? ' selected="selected"' : ''; ?>>RP</option>
                                                                <option value="OP"<?php echo $intervencion["Planificacion"]["tipointervencion"] == 'OP' || $intervencion["Planificacion"]["tipointervencion"] == 'BL' ? ' selected="selected"' : ''; ?>>OP</option>
    <?php } ?>
                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
<?php if ($intervencion["Planificacion"]["tipointervencion"] == 'MP') { ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Tipo mantención</div>
                                                    <div class="col-lg-10">
                                                        <select name="tipomantencion" id="tipomantencion" class="form-control">
                                                            <option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '250' ? ' selected="selected"' : ''; ?> value="250">250</option>
                                                            <option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '500' ? ' selected="selected"' : ''; ?> value="500">500</option>
                                                            <option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '1000' ? ' selected="selected"' : ''; ?> value="1000">1000</option>
                                                            <option <?php echo $intervencion["Planificacion"]["tipomantencion"] == '1500' ? ' selected="selected"' : ''; ?> value="1500">Overhaul</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
<?php } ?>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Lugar reparación</div>
                                                <div class="col-lg-10"> 
                                                    <select name="LugarReparacion" id="LugarReparacion" class="form-control">
<?php foreach ($lugares_reparacion as $key => $value) { ?>
                                                            <option value="<?php echo $key; ?>"<?php echo $intervencion['Planificacion']["lugar_reparacion_id"] == $key ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">ESN</div>
                                                <div class="col-lg-10"> 
                                                    <input type="text" value="<?php
                                                    $esn_ = $util->getESN($intervencion['Planificacion']['faena_id'], $intervencion['Planificacion']['flota_id'], $util->getMotor($intervencion['Planificacion']['unidad_id']), $util->getUnidad($intervencion['Planificacion']['unidad_id']), $intervencion['Planificacion']['fecha']);
                                                    echo $esn_;
                                                    $esn = "";
                                                    if ($esn_ == '') {
                                                        if (@$json["esn_conexion"] != "") {
                                                            $esn = @$json["esn_conexion"];
                                                        } elseif (@$json["esn_nuevo"] != "") {
                                                            $esn = @$json["esn_nuevo"];
                                                        } elseif (@$json["esn"] != "") {
                                                            $esn = @$json["esn"];
                                                        } else {
                                                            $esn = $intervencion['Planificacion']['esn'];
                                                        }

                                                        if (is_numeric($esn)) {
                                                            echo $esn;
                                                        } else {
                                                            echo $util->esnPadre($intervencion['Planificacion']['padre']);
                                                        }
                                                    }
                                                    ?>" readonly="readonly" class="form-control" />
                                                </div>
                                            </div>
                                        </div>


<?php if ($intervencion["Planificacion"]["tipointervencion"] != 'MP') { ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Motivo de llamado</div>
                                                    <div class="col-lg-10"> 
                                                        <select name="motivo_llamado_id" id="motivo_id" class="form-control">
    <?php foreach ($motivos as $key => $value) { ?>
                                                                <option value="<?php echo $key; ?>" <?php echo $intervencion['Planificacion']['motivo_llamado_id'] == $key ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
    <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Categoría Síntoma</div>
                                                    <div class="col-lg-10"> 
                                                        <select name="categoria_sintoma_id" id="categoria_id" required="required" class="form-control">			
                                                            <option value="" disabled="disabled"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Síntoma</div>
                                                    <div class="col-lg-10"> 
                                                        <select name="sintoma_id" id="sintoma_id" required="required" class="form-control">			
                                                            <option value="" disabled="disabled"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
<?php } ?>

<?php #if (isset($json["horometro_cabina"])) {  ?>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Horómetro Cabina</div>
                                                <div class="col-lg-10"> 
                                                    <input type="text" name="horometro_cabina"  id="horometro_cabina" class="form-control" value="<?php echo number_format($intervencion["Planificacion"]["horometro_cabina"], 2, ".", ""); ?>">
                                                </div>
                                            </div>
                                        </div>
<?php #}  ?>
<?php #if (isset($json["horometro_final"])) {  ?>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-2">Horómetro Motor</div>
                                                <div class="col-lg-10"><input type="text" name="horometro_motor" id="horometro_motor" class="form-control" value="<?php echo number_format($intervencion["Planificacion"]["horometro_motor"], 2, ".", ""); ?>">
                                                </div>
                                            </div>
                                        </div>
<?php #}  ?>

<?php if ($this->Session->read("PermisosCargos")[$this->Session->read('faena_id')][0] == 4) { ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Fecha inicio</div>
                                                    <div class="col-lg-10"> 
                                                        <input type="datetime-local" name="fecha_i_g"  id="fecha_i_g" class="form-control" value="<?php echo ($intervencion["Planificacion"]["fecha"] != '') ? $intervencion["Planificacion"]["fecha"] . "T" . $intervencion["Planificacion"]["hora"] : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-2">Fecha término</div>
                                                    <div class="col-lg-10"> 
                                                        <input type="datetime-local" name="fecha_t_g"  id="fecha_t_g" class="form-control" value="<?php echo ($intervencion["Planificacion"]["fecha_termino"] != '') ? $intervencion["Planificacion"]["fecha_termino"] . "T" . $intervencion["Planificacion"]["hora_termino"] : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
<?php } else { ?>
                                            <input type="hidden" name="fecha_i_g"  id="fecha_i_g" class="form-control" value="<?php echo ($intervencion["Planificacion"]["fecha"] != '') ? $intervencion["Planificacion"]["fecha"] . "T" . $intervencion["Planificacion"]["hora"] : ''; ?>">
                                            <input type="hidden" name="fecha_t_g"  id="fecha_t_g" class="form-control" value="<?php echo ($intervencion["Planificacion"]["fecha_termino"] != '') ? $intervencion["Planificacion"]["fecha_termino"] . "T" . $intervencion["Planificacion"]["hora_termino"] : ""; ?>">
<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet box cummins">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-file-text-o"></i>Información de flujo</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body" style="display: none;">
<?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>
    <?php if (isset($decisiones["cambio_modulo"]) && $decisiones["cambio_modulo"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Cambio módulo</div>
                                                        <div class="col-lg-3">
                                                            <select name="cambio_modulo" id="cambio_modulo" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["cambio_modulo"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["cambio_modulo"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
<?php } ?>
<?php if (isset($decisiones["intervencion_terminada"]) && $decisiones["intervencion_terminada"] != '') { ?>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-3">Intervención terminada</div>
                                                    <div class="col-lg-3">
                                                        <select name="intervencion_terminada" id="intervencion_terminada" class="form-control">
                                                            <option value="" disabled="disabled"></option>
                                                            <option value="S"<?php echo $decisiones["intervencion_terminada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                            <option value="N"<?php echo $decisiones["intervencion_terminada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
<?php } ?>
<?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>
    <?php if (isset($decisiones["desconexion_realizada"]) && $decisiones["desconexion_realizada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Desconexión realizada</div>
                                                        <div class="col-lg-3">
                                                            <select name="desconexion_realizada" id="desconexion_realizada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["desconexion_realizada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["desconexion_realizada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["desconexion_terminada"]) && $decisiones["desconexion_terminada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Desconexión terminada</div>
                                                        <div class="col-lg-3">
                                                            <select name="desconexion_terminada" id="desconexion_terminada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["desconexion_terminada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["desconexion_terminada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["conexion_realizada"]) && $decisiones["conexion_realizada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Conexión realizada</div>
                                                        <div class="col-lg-3">
                                                            <select name="conexion_realizada" id="conexion_realizada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["conexion_realizada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["conexion_realizada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["conexion_terminada"]) && $decisiones["conexion_terminada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">>Conexión terminada</div>
                                                        <div class="col-lg-3">
                                                            <select name="conexion_terminada" id="conexion_terminada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["conexion_terminada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["conexion_terminada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["puesta_marcha_realizada"]) && $decisiones["puesta_marcha_realizada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Puesta en marcha realizada</div>
                                                        <div class="col-lg-3">
                                                            <select name="puesta_marcha_realizada" id="puesta_marcha_realizada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["puesta_marcha_realizada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["puesta_marcha_realizada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["prueba_potencia_realizada"]) && $decisiones["prueba_potencia_realizada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Prueba de potencia realizada</div>
                                                        <div class="col-lg-3">
                                                            <select name="prueba_potencia_realizada" id="prueba_potencia_realizada" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["prueba_potencia_realizada"] == 'S' ? ' selected="selected"' : ''; ?>>REALIZADA</option>
                                                                <option value="P"<?php echo $decisiones["prueba_potencia_realizada"] == 'P' ? ' selected="selected"' : ''; ?>>QUEDA PENDIENTE</option>
                                                                <option value="N"<?php echo $decisiones["prueba_potencia_realizada"] == 'N' ? ' selected="selected"' : ''; ?>>NO APLICA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["prueba_potencia_exitosa"]) && $decisiones["prueba_potencia_exitosa"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Prueba de potencia exitosa</div>
                                                        <div class="col-lg-3">
                                                            <select name="prueba_potencia_exitosa" id="prueba_potencia_exitosa" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["prueba_potencia_exitosa"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["prueba_potencia_exitosa"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["siguiente_actividad"]) && $decisiones["siguiente_actividad"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Siguiente actividad</div>
                                                        <div class="col-lg-3">
                                                            <select name="siguiente_actividad" id="siguiente_actividad" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="A"<?php echo $decisiones["siguiente_actividad"] == 'A' ? ' selected="selected"' : ''; ?>>AGREGAR NUEVO ELEMENTO</option>
                                                                <option value="P"<?php echo $decisiones["siguiente_actividad"] == 'P' ? ' selected="selected"' : ''; ?>>DEJAR PENDIENTE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["reproceso_potencia"]) && $decisiones["reproceso_potencia"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Resultado prueba potencia</div>
                                                        <div class="col-lg-3">
                                                            <select name="reproceso_potencia" id="reproceso_potencia" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="E"<?php echo $decisiones["reproceso_potencia"] == 'E' ? ' selected="selected"' : ''; ?>>EXITOSA</option>
                                                                <option value="F"<?php echo $decisiones["reproceso_potencia"] == 'F' ? ' selected="selected"' : ''; ?>>EXITOSA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["reproceso_modulo"]) && $decisiones["reproceso_modulo"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Cambio de módulo</div>
                                                        <div class="col-lg-3">
                                                            <select name="reproceso_modulo" id="reproceso_modulo" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["reproceso_modulo"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["reproceso_modulo"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["reproceso_evento"]) && $decisiones["reproceso_evento"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Estado evento</div>
                                                        <div class="col-lg-3">
                                                            <select name="reproceso_evento" id="reproceso_evento" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="F"<?php echo $decisiones["reproceso_evento"] == 'F' ? ' selected="selected"' : ''; ?>>FINALIZADO</option>
                                                                <option value="P"<?php echo $decisiones["reproceso_evento"] == 'P' ? ' selected="selected"' : ''; ?>>PENDIENTE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["trabajo_finalizado"]) && $decisiones["trabajo_finalizado"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Trabajo finalizado</div>
                                                        <div class="col-lg-3">
                                                            <select name="trabajo_finalizado" id="trabajo_finalizado" class="form-control">
                                                                <option value="" disabled="disabled"></option>
                                                                <option value="S"<?php echo $decisiones["trabajo_finalizado"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["trabajo_finalizado"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if (isset($decisiones["mantencion_terminada"]) && $decisiones["mantencion_terminada"] != '') { ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3">Mantención terminada</div>
                                                        <div class="col-lg-3">
                                                            <select name="mantencion_terminada" id="mantencion_terminada" class="form-control">
                                                                <option value="S"<?php echo $decisiones["mantencion_terminada"] == 'S' ? ' selected="selected"' : ''; ?>>SI</option>
                                                                <option value="N"<?php echo $decisiones["mantencion_terminada"] == 'N' ? ' selected="selected"' : ''; ?>>NO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
<?php }  ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        if ((strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "RI") ||
                                (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "OP" && $intervencion["Planificacion"]["backlog_id"] == NULL) ||
                                (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "EX")) {

                            if (isset($fechas['IntervencionFechas']['llamado']) && $fechas['IntervencionFechas']['llamado'] != '') {
                                if (isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') {
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="portlet box cummins">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-calendar"></i>Detalle llamado y llegada</div>
                                                    <div class="tools">
                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body" style="display: none;">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2">Detalle de Fechas</div>
                                                            <div class="col-lg-4">Fecha</div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Período</div>
                                                        </div>
                                                    </div>
                                            <?php
                                            if (isset($fechas['IntervencionFechas']['llamado']) && $fechas['IntervencionFechas']['llamado'] != '') {
                                                $fecha = new DateTime($fechas['IntervencionFechas']['llamado']);
                                                ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Llamado</div>
                                                                <div class="col-lg-4"><input type="date" size="10" name="llamado[]" id="llamado_fecha" value="<?php echo $fecha->format('Y-m-d'); ?>" class="f_1 delta1_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                <div class="col-lg-2"><select name="llamado[]" id="llamado_hora" class="h_1 delta1_data form-control">
                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                    </select></div>
                                                                <div class="col-lg-2"><select name="llamado[]" id="llamado_min" class="m_1 delta1_data form-control">
                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                    </select></div>
                                                                <div class="col-lg-2"><select name="llamado[]" id="llamado_periodo" class="p_1 delta1_data form-control">
                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                    </select></div>
                                                            </div></div>
                                                        <?php
                                                    }
                                                    ?>


                                                <?php
                                                if (isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') {
                                                    $fecha = new DateTime($fechas['IntervencionFechas']['llegada']);
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Llegada</div>
                                                                <div class="col-lg-4"><input type="date" size="10" name="llegada[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="llegada_fecha" class="f_2 delta1_data delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                <div class="col-lg-2">
                                                                    <select name="llegada[]" id="llegada_hora" class="h_2 delta1_data delta2_data form-control">
                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="llegada[]" id="llegada_min" class="m_2 delta1_data delta2_data form-control">
                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="llegada[]" id="llegada_periodo" class="p_2 delta1_data delta2_data form-control">
                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-success">
                                                                <strong>Delta Disponible</strong> <span class="delta1_duracion"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-warning">
                                                                <strong>Delta Ingresado</strong> <span id="d1_ing"></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2">Detalle Deltas</div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Responsable</div>
                                                            <div class="col-lg-2">Observación</div>
                                                        </div>
                                                    </div>
                                            <?php
                                            foreach ($deltas_ as $key => $value) {
                                                if ($value["DeltaItem"]["grupo"] != "1") {
                                                    continue;
                                                }
                                                ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                <div class="col-lg-2">
                                                <?php
                                                $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
                                                $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
                                                ?>
                                                                    <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                        <option value="00"<?php if ($minutes == "0") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>00</option>
                                                                        <option value="15"<?php if ($minutes == "15") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>15</option>
                                                                        <option value="30"<?php if ($minutes == "30") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>30</option>
                                                                        <option value="45"<?php if ($minutes == "45") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                        <option value="0"></option>
                                                                        <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>DCC</option>
                                                                        <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>OEM</option>
                                                                        <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                                                                                echo " selected=\"selected\"";
                                                                            }; ?>>MINA</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            if (isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') {
                                if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="portlet box cummins">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-calendar"></i>Detalle llegada e inicio de la intervención</div>
                                                    <div class="tools">
                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-lg-12">Detalle de fechas</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-4">Fecha</div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Período</div>
                                                        </div>
                                                    </div>
                                    <?php
                                    if (isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') {
                                        $fecha = new DateTime($fechas['IntervencionFechas']['llegada']);
                                        ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Llegada</div>
                                                    <?php if (isset($fechas['IntervencionFechas']['llamado']) && $fechas['IntervencionFechas']['llamado'] != '') { ?>
                                                                    <div class="col-lg-4">
                                                                        <input type="date" size="10" value="<?php echo $fecha->format('Y-m-d'); ?>" id="llegada_fecha_2" class="form-control tooltips" max="<?php echo date("Y-m-d"); ?>" readonly="readonly" data-container="body" data-placement="top" data-original-title="Esta fecha se debe modificar en la sección 'Detalle llamado y llegada'" /> 
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_hora_2" class="form-control" disabled="disabled">
                                                                            <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                            <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                            <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                            <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                            <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                            <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                            <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                            <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                            <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                            <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                            <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                            <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_min_2" class="form-control" disabled="disabled">
                                                                            <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                            <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                            <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                            <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_periodo_2" class="form-control" disabled="disabled">
                                                                            <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                            <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                        </select>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="col-lg-4">
                                                                        <input type="date" size="10" name="llegada[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="llegada_fecha" class="form-control" max="<?php echo date("Y-m-d"); ?>" />
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_hora" class="form-control">
                                                                            <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                            <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                            <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                            <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                            <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                            <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                            <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                            <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                            <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                            <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                            <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                            <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_min" class="form-control">
                                                                            <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                            <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                            <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                            <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-2">
                                                                        <select name="llegada[]" id="llegada_periodo" class="form-control">
                                                                            <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                            <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                        </select>
                                                                    </div>
                                                                    <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
                                                    $fecha = new DateTime($fechas['IntervencionFechas']['inicio_intervencion']);
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Inicio intervención</div>
                                                                <div class="col-lg-4"><input type="date" size="10" name="inicio_intervencion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_i_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_h"  class="h_3 delta2_data form-control">
                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_m" class="m_3 delta2_data form-control">
                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_p" class="p_3 delta2_data form-control">
                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                <?php
            }
            ?>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-success">
                                                                <strong>Delta Disponible</strong> <span class="delta2_duracion"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-warning">
                                                                <strong>Delta Ingresado</strong> <span id="d2_ing"></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Responsable</div>
                                                            <div class="col-lg-2">Observación</div>
                                                        </div>
                                                    </div>
            <?php
            foreach ($deltas_ as $key => $value) {
                if ($value["DeltaItem"]["grupo"] != "2") {
                    continue;
                }
                ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                <div class="col-lg-2">
                <?php
                $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
                $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
                ?>
                                                                    <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                        <option value="00"<?php if ($minutes == "0") {
                    echo " selected=\"selected\"";
                }; ?>>00</option>
                                                                        <option value="15"<?php if ($minutes == "15") {
                            echo " selected=\"selected\"";
                        }; ?>>15</option>
                                                                        <option value="30"<?php if ($minutes == "30") {
                            echo " selected=\"selected\"";
                        }; ?>>30</option>
                                                                        <option value="45"<?php if ($minutes == "45") {
                            echo " selected=\"selected\"";
                        }; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                        <option value="0"></option>
                                                                        <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                            echo " selected=\"selected\"";
                        }; ?>>DCC</option>
                                                                        <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                            echo " selected=\"selected\"";
                        }; ?>>OEM</option>
                                                                        <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                            echo " selected=\"selected\"";
                        }; ?>>MINA</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == "MP" && strtoupper($intervencion["Planificacion"]["tipomantencion"]) != "1500") { ?>
    <?php
    if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
        if (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
            ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="portlet box cummins">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-calendar"></i>Detalle inicio y término mantención</div>
                                                    <div class="tools">
                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                    </div>
                                                </div>
                                                <div class="portlet-body" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-lg-12">Detalle de fechas</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-4">Fecha</div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Período</div>
                                                        </div>
                                                    </div>
            <?php
            if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['inicio_intervencion']);
                ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Inicio mantención</div>
                                                                <div class="col-lg-4"><input type="date" size="10" name="inicio_intervencion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_i_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>"  /></div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_h"  class="h_3 delta2_data form-control"  >
                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_m" class="m_3 delta2_data form-control"  >
                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="inicio_intervencion[]" id="i_i_p" class="p_3 delta2_data form-control"  >
                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                <?php
            }
            ?>
            <?php
            if (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
                $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_intervencion']);
                ?>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-2">Término mantención</div>
                                                                <div class="col-lg-4"><input type="date" size="10" name="termino_intervencion[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="i_t_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                <div class="col-lg-2">
                                                                    <select name="termino_intervencion[]" id="i_t_h" class="h_4 form-control">
                                                                        <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                        <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                        <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                        <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                        <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                        <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                        <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                        <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                        <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                        <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                        <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                        <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="termino_intervencion[]" id="i_t_m" class="m_4 form-control">
                                                                        <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                        <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                        <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                        <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <select name="termino_intervencion[]" id="i_t_p" class="p_4 form-control">
                                                                        <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                        <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-success">
                                                                <strong>Delta Disponible</strong> <span class="delta6_duracion"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="alert alert-warning">
                                                                <strong>Delta Ingresado</strong> <span id="d6_ing"></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-2"></div>
                                                            <div class="col-lg-2">Hora</div>
                                                            <div class="col-lg-2">Minuto</div>
                                                            <div class="col-lg-2">Responsable</div>
                                                            <div class="col-lg-2">Observación</div>
                                                        </div>
                                                    </div>
            <?php
            foreach ($deltas_ as $key => $value) {
                if ($value["DeltaItem"]["grupo"] != "6") {
                    continue;
                }
                ?>
                                                        <div class="form-group">
                <?php if ($value["DeltaItem"]["id"] == 43) { ?>
                                                                <div class="row backgound-cummins">
                                                                <?php } else { ?>
                                                                    <div class="row">
                <?php } ?>
                <?php if ($value["DeltaItem"]["id"] == 43) { ?>
                                                                        <div class="col-lg-2" style="padding-top: 5px;">
                                                <?php } else { ?>
                                                                            <div class="col-lg-2">
                                                <?php } ?>
                                                <?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                        <div class="col-lg-2">
                                                <?php
                                                $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
                                                $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
                                                ?>
                                                                            <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                <option value="00"<?php if ($minutes == "0") {
                                    echo " selected=\"selected\"";
                                }; ?>>00</option>
                                                                                <option value="15"<?php if ($minutes == "15") {
                                    echo " selected=\"selected\"";
                                }; ?>>15</option>
                                                                                <option value="30"<?php if ($minutes == "30") {
                                    echo " selected=\"selected\"";
                                }; ?>>30</option>
                                                                                <option value="45"<?php if ($minutes == "45") {
                                    echo " selected=\"selected\"";
                                }; ?>>45</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                <option value="0"></option>
                                                                                <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                                    echo " selected=\"selected\"";
                                }; ?>>DCC</option>
                                                                                <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                                                    echo " selected=\"selected\"";
                                                }; ?>>OEM</option>
                                                                                <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                                                    echo " selected=\"selected\"";
                                                }; ?>>MINA</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
        <?php } ?>
    <?php } ?>
<?php } ?>	

<?php
if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) != "MP") {
    if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
        if (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
            ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="portlet box cummins">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="fa fa-calendar"></i>Detalle inicio y término de la intervención</div>
                                                            <div class="tools">
                                                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body" style="display: none;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-lg-2">Detalle de fechas</div>
                                                                    <div class="col-lg-4">Fecha</div>
                                                                    <div class="col-lg-2">Hora</div>
                                                                    <div class="col-lg-2">Minuto</div>
                                                                    <div class="col-lg-2">Período</div>
                                                                </div>
                                                            </div>
            <?php
            if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['inicio_intervencion']);
                ?>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Inicio intervención</div>
                <?php if (isset($fechas['IntervencionFechas']['llegada']) && $fechas['IntervencionFechas']['llegada'] != '') { ?>
                                                                            <div class="col-lg-4">
                                                                                <input type="date" size="10" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_i_f_2" class="form-control tooltips" max="<?php echo date("Y-m-d"); ?>" readonly="readonly"  data-container="body" data-placement="top" data-original-title="Esta fecha se debe modificar en la sección 'Detalle llegada e inicio de la intervención'" />
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select id="i_i_h_2" class="form-control" disabled="disabled">
                                                                                    <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                    <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                    <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                    <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                    <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                    <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                    <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                    <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                    <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                    <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                    <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                    <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select id="i_i_m_2" class="form-control" disabled="disabled">
                                                                                    <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                    <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                    <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                    <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select id="i_i_p_2" class="form-control" disabled="disabled">
                                                                                    <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                    <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                </select>
                                                                            </div>
                <?php } else { ?>
                                                                            <div class="col-lg-4">
                                                                                <input type="date" size="10" name="inicio_intervencion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_i_f" class="form-control" max="<?php echo date("Y-m-d"); ?>" />
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_intervencion[]" id="i_i_h"  class="form-control">
                                                                                    <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                    <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                    <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                    <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                    <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                    <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                    <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                    <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                    <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                    <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                    <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                    <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_intervencion[]" id="i_i_m" class="form-control">
                                                                                    <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                    <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                    <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                    <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_intervencion[]" id="i_i_p" class="form-control">
                                                                                    <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                    <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                </select>
                                                                            </div>
                                                                    <?php }
                                                                ?>
                                                                    </div>
                                                                </div>
                <?php
            }

            if (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['termino_intervencion']);
                ?>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Término intervención</div>
                                                                        <div class="col-lg-4"><input type="date" size="10" name="termino_intervencion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_t_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                        <div class="col-lg-2">
                                                                            <select name="termino_intervencion[]" id="i_t_h" class="h_4 form-control">
                                                                                <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <select name="termino_intervencion[]" id="i_t_m" class="m_4 form-control">
                                                                                <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <select name="termino_intervencion[]" id="i_t_p" class="p_4 form-control">
                                                                                <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                                    <?php
                                                                                }
                                                                                if (!empty($elementos)) {
                                                                                    ?>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="alert alert-success">
                                                                            <strong>Delta Disponible</strong> <span class="delta3_duracion"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="alert alert-warning">
                                                                            <strong>Delta Ingresado</strong> <span id="d3_ing"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12" id="d3_diagnostico" style="display: block">
                                                                        <div class="alert alert-info">
                                                                            <strong>Diagnóstico falla: </strong><span class="d3_diag"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2"></div>
                                                                        <div class="col-lg-2">Hora</div>
                                                                        <div class="col-lg-2">Minuto</div>
                                                                        <div class="col-lg-2">Responsable</div>
                                                                        <div class="col-lg-2">Observación</div>
                                                                    </div>
                                                                </div>
                                                                        <?php
                                                                        foreach ($deltas_ as $key => $value) {
                                                                            if ($value["DeltaItem"]["grupo"] != "3") {
                                                                                continue;
                                                                            }
                                                                            ?>
                                                                    <div class="form-group">
                                                            <?php if ($value["DeltaItem"]["id"] == 16) { ?>
                                                                            <div class="row backgound-cummins">
                                                            <?php } else { ?>
                                                                                <div class="row">
                                                            <?php } ?>
                                                            <?php if ($value["DeltaItem"]["id"] == 16) { ?>
                                                                                    <div class="col-lg-2" style="padding-top: 5px;">
                    <?php } else { ?>
                                                                                        <div class="col-lg-2">
                    <?php } ?>


                    <?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                                    <div class="col-lg-2">
                    <?php
                    $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
                    $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
                    ?>
                                                                                        <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                            <option value="00"<?php if ($minutes == "0") {
                        echo " selected=\"selected\"";
                    }; ?>>00</option>
                                                                                            <option value="15"<?php if ($minutes == "15") {
                        echo " selected=\"selected\"";
                    }; ?>>15</option>
                                                                                            <option value="30"<?php if ($minutes == "30") {
                        echo " selected=\"selected\"";
                    }; ?>>30</option>
                                                                                            <option value="45"<?php if ($minutes == "45") {
                        echo " selected=\"selected\"";
                    }; ?>>45</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                            <option value="0"></option>
                                                                                            <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                        echo " selected=\"selected\"";
                    }; ?>>DCC</option>
                                                                                            <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                        echo " selected=\"selected\"";
                    }; ?>>OEM</option>
                                                                                            <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                        echo " selected=\"selected\"";
                    }; ?>>MINA</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-4">
                                                                                        <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                                                    <?php } ?>
                                                                                                <?php }
                                                                                                ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                                                                </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <?php if (!empty($elementos)) { ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="portlet box cummins">
                                                        <div class="portlet-title">
                                                            <div class="caption"> 
                                                                <i class="fa fa-wrench"></i>Elementos (Tiempo de Reparación & Diagnóstico)</div>
                                                            <div class="tools">
                                                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body"  style="display: none;">
                                                            <div id="table_wrapper" class="dataTables_wrapper">
                                                                <div class="form-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="alert alert-success">
                                                                                <strong>Tiempo Disponible</strong> <span class="delta_disponible_elementos"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="alert alert-warning">
                                                                                <strong>Tiempo Ingresado</strong>  <span class="delta_faltante_elementos"></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6" id="alerta_elemento_falla" style="display: none">
                                                                            <div class="alert alert-danger">
                                                                                <strong>Elementos: </strong>  <span class="elemento_falla"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-striped table-bordered table-hover tabla-elementos">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="20">#</th>
                                                                                            <th>Sistema</th>
                                                                                            <th>Subsistema</th>
                                                                                            <th style="width:50px">Posición</th>
                                                                                            <th>Imagen</th>
                                                                                            <th><div style="width: 35px">ID</div></th>
                                                                                            <th>Elemento</th>
                                                                                            <th style="width:50px">Posición</th>
                                                                                            <th>Diagnóstico</th>
                                                                                            <th style="width:100px">Solución</th>
                                                                                            <th style="width:100px">Tipo</th>
                                                                                            <th style="width:100px">NP Sale</th> 
                                                                                            <th style="width:100px">NP Entra</th>
                                                                                            <th colspan="2" width="107">Duración</th>
                                                                                            <th style="width: 50px;"></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $cantidad_elementos = count($elementos);
                                                                                        $total_tiempo = 0;
                                                                                        for ($i = 0; $i < $cantidad_elementos; $i++) {
                                                                                            $elemento = $elementos[$i]["IntervencionElementos"];
                                                                                            $id = rand(10000, 99999);
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td width="20"><?php echo $i + 1; ?></td>
                                                                                                <td>
                                                                                                    <select name="sistema-id[]" id="sistema-id-<?php echo $id; ?>" class="form-control sistema-id" required="required" sistema_id="<?php echo $elemento["sistema_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="subsistema-id[]" id="subsistema-id-<?php echo $id; ?>" class="form-control subsistema-id" required="required" subsistema_id="<?php echo $elemento["subsistema_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="posicion-subsistema-id[]" id="posicion-subsistema-id-<?php echo $id; ?>" class="form-control posicion-subsistema-id" required="required" posicion_id="<?php echo $elemento["subsistema_posicion_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>	
                                                                                                </td>

                                                                                                <td align="center">
                                                                                                    <a class="btn btn-sm blue tooltips ver-imagen" id="ver-imagen-<?php echo $id; ?>" data-container="body" data-placement="top" data-original-title="Ver imagen">
                                                                                                        <i class="fa fa-image"></i>
                                                                                                    </a>
                                                                                                </td>
                                                                                                <!--<td><img src="/images/icons/control/32/illustration.png" class="ele_img" id="ele_img_" alt="" title="Ver imagen elemento" style="width:16px;cursor:pointer;margin-top:7px;" /></td>-->
                                                                                                <td>
                                                                                                    <input type="text" id="id-elemento-<?php echo $id; ?>" class="form-control" name="id-elemento[]" value="<?php echo $elemento["id_elemento"]; ?>" style="width: 34px;padding-left: 8px;padding-right:  7px;" />
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="elemento-id[]" id="elemento-id-<?php echo $id; ?>" class="form-control elemento-id" required="required" elemento_id="<?php echo $elemento["elemento_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="posicion-elemento-id[]" id="posicion-elemento-id-<?php echo $id; ?>" class="form-control posicion-elemento-id" required="required" posicion_id="<?php echo $elemento["elemento_posicion_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>	
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="diagnostico-id[]" id="diagnostico-id-<?php echo $id; ?>" class="form-control diagnostico-id" required="required" diagnostico_id="<?php echo $elemento["diagnostico_id"]; ?>">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="solucion-id[]" id="solucion-id-<?php echo $id; ?>" class="form-control solucion-id" required="required">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                        <?php
                                                                                                        foreach ($soluciones as $solucion) {
                                                                                                            echo "<option value=\"{$solucion["Solucion"]["id"]}\" " . ($elemento["solucion_id"] == $solucion["Solucion"]["id"] ? "selected=\"selected\"" : "") . ">{$solucion["Solucion"]["nombre"]}</option>";
                                                                                                        }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <select name="tipo-id[]" id="tipo-id-<?php echo $id; ?>" class="form-control tipo-id" required="required">
                                                                                                        <option value="">Seleccione una opción</option>
                                                                                                        <?php
                                                                                                        foreach ($tipos_registros as $tipo) {
                                                                                                            echo "<option value=\"{$tipo["TipoElemento"]["id"]}\" " . ($elemento["tipo_id"] == $tipo["TipoElemento"]["id"] ? "selected=\"selected\"" : "") . ">{$tipo["TipoElemento"]["nombre"]}</option>";
                                                                                                        }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="text" id="pn-saliente-<?php echo $id; ?>" class="form-control" name="pn_saliente[]" value="<?php echo $elemento["pn_saliente"]; ?>" maxlength="29" />
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="text" id="pn-entrante-<?php echo $id; ?>" class="form-control" name="pn_entrante[]" value="<?php echo $elemento["pn_entrante"]; ?>" maxlength="29"/>
                                                                                                </td>
                                                                                                <td style="width: 50px;" >
                                                                                                    <?php
                                                                                                    $total_tiempo += $elemento["tiempo"];
                                                                                                    $hours = floor($elemento["tiempo"] / 60);
                                                                                                    $minutes = $elemento["tiempo"] % 60;
                                                                                                    ?>
                                                                                                    <input name="hora_elemento[]" id="hora-elemento-<?php echo $id; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control hora_elemento" style="width: 42px;padding-right: 1px;padding-left: 8px;" />
                                                                                                </td>
                                                                                                <td style="width: 50px;" >
                                                                                                    <select name="minuto_elemento[]" id="minuto-elemento-<?php echo $id; ?>" class="form-control minuto_elemento" style="width: 43px;" >
                                                                                                        <?php for ($j = 0; $j < 60; $j++) { ?>
                                                                                                            <option value="<?php echo $j; ?>"<?php if ($minutes == $j) {
                                                                                                            echo " selected=\"selected\"";
                                                                                                        }; ?>><?php echo str_pad($j, 2, "0", STR_PAD_LEFT);
                                                                                                        ; ?></option>
                                                                                                    <?php } ?>
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <?php if ($i == 0) { ?>
                                                                                                        <a class="btn btn-sm green tooltips agregar-elemento" data-container="body" data-placement="top" data-original-title="Agregar elemento">
                                                                                                            <i class="fa fa-plus"></i>
                                                                                                        </a>
                                                                                                    <?php } else { ?>
                                                                                                        <a class="btn btn-sm red tooltips quitar-elemento" data-container="body" data-placement="top" data-original-title="Quitar elemento">
                                                                                                            <i class="fa fa-trash"></i>
                                                                                                        </a>	
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            </tr>	
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="table table-striped table-bordered table-hover">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                        <?php
                                                                                        $hours = floor($total_tiempo / 60);
                                                                                        $minutes = $total_tiempo % 60;
                                                                                        ?>
                                                                                            <td colspan="12"></td>
                                                                                            <td style="width: 50px;" >
                                                                                                <input disabled="disabled" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control total_delta_hora_elemento" style="width: 42px;padding-right: 1px;padding-left: 8px;" />
                                                                                            </td>
                                                                                            <td style="width: 50px;" >
                                                                                                <select class="form-control total_delta_minutos_elemento" disabled="disabled" style="width: 43px;" >
                                                                                                    <?php for ($i = 0; $i < 60; $i++) { ?>
                                                                                                        <option value="<?php echo $i; ?>"<?php if ($minutes == $i) {
                                                                                                            echo " selected=\"selected\"";
                                                                                                        }; ?>><?php echo str_pad($i, 2, "0", STR_PAD_LEFT);
                                                                                                        ; ?></option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td style="width: 50px;">
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
<?php }  ?>

<?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>

                                                                    <?php
                                                                    if ((isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') &&
                                                                            (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '')) {
                                                                        ?>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-calendar"></i>Detalle término de la intervención e inicio prueba de potencia</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-lg-12">Detalle de fechas</div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2"></div>
                                                                        <div class="col-lg-4">Fecha</div>
                                                                        <div class="col-lg-2">Hora</div>
                                                                        <div class="col-lg-2">Minuto</div>
                                                                        <div class="col-lg-2">Período</div>
                                                                    </div>
                                                                </div>
        <?php
        if (isset($fechas['IntervencionFechas']['termino_intervencion']) && $fechas['IntervencionFechas']['termino_intervencion'] != '') {
            $fecha = new DateTime($fechas['IntervencionFechas']['termino_intervencion']);
            ?>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2">Término intervención</div>
                                                                            <?php if (isset($fechas['IntervencionFechas']['inicio_intervencion']) && $fechas['IntervencionFechas']['inicio_intervencion'] != '') { ?>
                                                                                <div class="col-lg-4">
                                                                                    <input type="date" size="10" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_t_f_2" class="form-control" max="<?php echo date("Y-m-d"); ?>" readonly="readonly"  data-container="body" data-placement="top" data-original-title="Esta fecha se debe modificar en la sección 'Detalle inicio de la intervención y término de la intervención'" />
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select id="i_t_h_2"  class="form-control" disabled="disabled">
                                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select id="i_t_m_2" class="form-control" disabled="disabled">
                                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select id="i_t_p_2" class="form-control" disabled="disabled">
                                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                    </select>
                                                                                </div>	
            <?php } else { ?>
                                                                                <div class="col-lg-4">
                                                                                    <input type="date" size="10" name="termino_intervencion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="i_t_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" />
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_intervencion[]" id="i_t_h"  class="h_3 delta2_data form-control">
                                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_intervencion[]" id="i_t_p" class="m_3 delta2_data form-control">
                                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_intervencion[]" id="i_t_p" class="p_3 delta2_data form-control">
                                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                    </select>
                                                                                </div>
            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                if (isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') {
                                                                    $termino_intervencion = new DateTime($fechas['IntervencionFechas']['inicio_prueba_potencia']);
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2">Inicio prueba de potencia</div>
                                                                            <div class="col-lg-4"><input type="date" size="10" name="inicio_prueba_potencia[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="pp_i_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_prueba_potencia[]" id="pp_i_h" class="h_4 form-control">
                                                                                    <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                    <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                    <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                    <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                    <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                    <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                    <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                    <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                    <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                    <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                    <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                    <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_prueba_potencia[]" id="pp_i_m" class="m_4 form-control">
                                                                                    <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                    <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                    <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                    <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="inicio_prueba_potencia[]" id="pp_i_p" class="p_4 form-control">
                                                                                    <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                    <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="alert alert-success">
                                                                            <strong>Delta Disponible</strong> <span class="delta4_duracion"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="alert alert-warning">
                                                                            <strong>Delta Ingresado</strong> <span id="d4_ing"></span>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2"></div>
                                                                        <div class="col-lg-2">Hora</div>
                                                                        <div class="col-lg-2">Minuto</div>
                                                                        <div class="col-lg-2">Responsable</div>
                                                                        <div class="col-lg-2">Observación</div>
                                                                    </div>
                                                                </div>
        <?php
        foreach ($deltas_ as $key => $value) {
            if ($value["DeltaItem"]["grupo"] != "4") {
                continue;
            }
            ?>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                            <div class="col-lg-2">
            <?php
            $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
            $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
            ?>
                                                                                <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                    <option value="00"<?php if ($minutes == "0") {
                echo " selected=\"selected\"";
            }; ?>>00</option>
                                                                                    <option value="15"<?php if ($minutes == "15") {
                echo " selected=\"selected\"";
            }; ?>>15</option>
                                                                                    <option value="30"<?php if ($minutes == "30") {
                echo " selected=\"selected\"";
            }; ?>>30</option>
                                                                                    <option value="45"<?php if ($minutes == "45") {
                echo " selected=\"selected\"";
            }; ?>>45</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-2">
                                                                                <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                    <option value="0"></option>
                                                                                    <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                echo " selected=\"selected\"";
            }; ?>>DCC</option>
                                                                                    <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                echo " selected=\"selected\"";
            }; ?>>OEM</option>
                                                                                    <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                echo " selected=\"selected\"";
            }; ?>>MINA</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
        <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>
    <?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>
        <?php
        if ((isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') &&
                (isset($fechas['IntervencionFechas']['termino_prueba_potencia']) && $fechas['IntervencionFechas']['termino_prueba_potencia'] != '')) {
            ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="portlet box cummins">
                                                                <div class="portlet-title">
                                                                    <div class="caption">
                                                                        <i class="fa fa-calendar"></i>Detalle inicio y término de prueba de potencia</div>
                                                                    <div class="tools">
                                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                    </div>
                                                                </div>
                                                                <div class="portlet-body" style="display: none;">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">Detalle de fechas</div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2"></div>
                                                                            <div class="col-lg-4">Fecha</div>
                                                                            <div class="col-lg-2">Hora</div>
                                                                            <div class="col-lg-2">Minuto</div>
                                                                            <div class="col-lg-2">Período</div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if (isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') {
                                                                        $fecha = new DateTime($fechas['IntervencionFechas']['inicio_prueba_potencia']);
                                                                        ?>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-lg-2">Inicio prueba de potencia</div>
                                                                                <div class="col-lg-4"><input type="date" size="10" name="inicio_prueba_potencia[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="pp_i_f_2" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" readonly="readonly"  data-container="body" data-placement="top" data-original-title="Esta fecha se debe modificar en la sección 'Detalle término de la intervención e inicio prueba de potencia'" /></div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="inicio_prueba_potencia[]" id="pp_i_h_2"  class="h_3 delta2_data form-control" disabled="disabled">
                                                                                        <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                        <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                        <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                        <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                        <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                        <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                        <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                        <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                        <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                        <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                        <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                        <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="inicio_prueba_potencia[]" id="pp_i_m_2" class="m_3 delta2_data form-control" disabled="disabled">
                                                                                        <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                        <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                        <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                        <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="inicio_prueba_potencia[]" id="pp_i_p_2" class="p_3 delta2_data form-control" disabled="disabled">
                                                                                        <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                        <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                    if (isset($fechas['IntervencionFechas']['termino_prueba_potencia']) && $fechas['IntervencionFechas']['termino_prueba_potencia'] != '') {
                                                                        $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_prueba_potencia']);
                                                                        ?>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-lg-2">Término prueba de potencia</div>
                                                                                <div class="col-lg-4"><input type="date" size="10" name="termino_prueba_potencia[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="pp_t_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_prueba_potencia[]" id="pp_t_h" class="h_4 form-control">
                                                                                        <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                        <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                        <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                        <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                        <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                        <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                        <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                        <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                        <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                        <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                        <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                        <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_prueba_potencia[]" id="pp_t_m" class="m_4 form-control">
                                                                                        <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                        <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                        <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                        <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_prueba_potencia[]" id="pp_t_p" class="p_4 form-control">
                                                                                        <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                        <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                <?php
            }
            ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
        <?php } ?>	
    <?php } ?>

                                            <!-- Detalle Reproceso -->
    <?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>
        <?php
        if ((isset($fechas['IntervencionFechas']['termino_prueba_potencia']) && $fechas['IntervencionFechas']['termino_prueba_potencia'] != '') &&
                (isset($fechas['IntervencionFechas']['termino_reproceso']) && $fechas['IntervencionFechas']['termino_reproceso'] != '')) {
            ?>

                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="portlet box cummins">
                                                                <div class="portlet-title">
                                                                    <div class="caption">
                                                                        <i class="fa fa-calendar"></i>Detalle término prueba de potencia y reproceso de elementos</div>
                                                                    <div class="tools">
                                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                    </div>
                                                                </div>
                                                                <div class="portlet-body" style="display: none;">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">Detalle de fechas</div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2"></div>
                                                                            <div class="col-lg-4">Fecha</div>
                                                                            <div class="col-lg-2">Hora</div>
                                                                            <div class="col-lg-2">Minuto</div>
                                                                            <div class="col-lg-2">Período</div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if (isset($fechas['IntervencionFechas']['termino_prueba_potencia']) && $fechas['IntervencionFechas']['termino_prueba_potencia'] != '') {
                                                                        $fecha = new DateTime($fechas['IntervencionFechas']['termino_prueba_potencia']);
                                                                        ?>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-lg-2">Término prueba de potencia</div>
                <?php if (isset($fechas['IntervencionFechas']['inicio_prueba_potencia']) && $fechas['IntervencionFechas']['inicio_prueba_potencia'] != '') { ?>
                                                                                    <div class="col-lg-4">
                                                                                        <input type="date" size="10" name="termino_prueba_potencia[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="pp_t_f_2" class="form-control" max="<?php echo date("Y-m-d"); ?>" readonly="readonly"  data-container="body" data-placement="top" data-original-title="Esta fecha se debe modificar en la sección 'Detalle inicio y término de prueba de potencia'" />
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_h_2"  class="form-control" disabled="disabled">
                                                                                            <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                            <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                            <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                            <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                            <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                            <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                            <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                            <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                            <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                            <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                            <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                            <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_m_2" class="form-control" disabled="disabled">
                                                                                            <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                            <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                            <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                            <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_p_2" class="form-control" disabled="disabled">
                                                                                            <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                            <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                        </select>
                                                                                    </div>
                <?php } else { ?>
                                                                                    <div class="col-lg-4">
                                                                                        <input type="date" size="10" name="termino_prueba_potencia[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="pp_t_f" class="form-control" max="<?php echo date("Y-m-d"); ?>" />
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_h"  class="form-control">
                                                                                            <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                            <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                            <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                            <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                            <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                            <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                            <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                            <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                            <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                            <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                            <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                            <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_m" class="form-control">
                                                                                            <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                            <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                            <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                            <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <select name="termino_prueba_potencia[]" id="pp_t_p" class="form-control">
                                                                                            <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                            <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        <?php
                                                                                        if (isset($fechas['IntervencionFechas']['termino_reproceso']) && $fechas['IntervencionFechas']['termino_reproceso'] != '') {
                                                                                            $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_reproceso']);
                                                                                            ?>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-lg-2">Término reproceso de elementos</div>
                                                                                <div class="col-lg-4"><input type="date" size="10" name="termino_reproceso[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="repro_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_reproceso[]" id="repro_h" class="h_4 form-control">
                                                                                        <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                        <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                        <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                        <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                        <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                        <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                        <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                        <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                        <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                        <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                        <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                        <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_reproceso[]" id="repro_m" class="m_4 form-control">
                                                                                        <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                        <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                        <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                        <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <select name="termino_reproceso[]" id="repro_p" class="p_4 form-control">
                                                                                        <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                        <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                <?php
            }
            ?>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="alert alert-success">
                                                                                <strong>Delta Disponible</strong> <span class="delta5_duracion"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="alert alert-warning">
                                                                                <strong>Delta Ingresado</strong> <span id="d5_ing"></span>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-2"></div>
                                                                            <div class="col-lg-2">Hora</div>
                                                                            <div class="col-lg-2">Minuto</div>
                                                                            <div class="col-lg-2">Responsable</div>
                                                                            <div class="col-lg-2">Observación</div>
                                                                        </div>
                                                                    </div>
            <?php
            foreach ($deltas_ as $key => $value) {
                if ($value["DeltaItem"]["grupo"] != "5") {
                    continue;
                }
                ?>
                                                                        <div class="form-group">
                <?php if ($value["DeltaItem"]["id"] == 32) { ?>
                                                                                <div class="row backgound-cummins">
                <?php } else { ?>
                                                                                    <div class="row">
                <?php } ?>
                <?php if ($value["DeltaItem"]["id"] == 32) { ?>
                                                                                        <div class="col-lg-2" style="padding-top: 5px;">
                <?php } else { ?>
                                                                                            <div class="col-lg-2">
                <?php } ?>
                                                                                                            <?php echo $value["DeltaItem"]["nombre"]; ?></div>
                                                                                        <div class="col-lg-2">
                                                                                                            <?php
                                                                                                            $hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
                                                                                                            $minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
                                                                                                            ?>
                                                                                            <input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                                <option value="00"<?php if ($minutes == "0") {
                                                                                                echo " selected=\"selected\"";
                                                                                            }; ?>>00</option>
                                                                                                <option value="15"<?php if ($minutes == "15") {
                                                                                                echo " selected=\"selected\"";
                                                                                            }; ?>>15</option>
                                                                                                <option value="30"<?php if ($minutes == "30") {
                                                                                                echo " selected=\"selected\"";
                                                                                            }; ?>>30</option>
                                                                                                <option value="45"<?php if ($minutes == "45") {
                                                                                                echo " selected=\"selected\"";
                                                                                            }; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
                                                                                                <option value="0"></option>
                                                                                                <option value="1"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "1") {
                                                                                                        echo " selected=\"selected\"";
                                                                                                    }; ?>>DCC</option>
                                                                                                <option value="2"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "2") {
                                                                                                        echo " selected=\"selected\"";
                                                                                                    }; ?>>OEM</option>
                                                                                                <option value="3"<?php if ($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"] == "3") {
                                                                                                            echo " selected=\"selected\"";
                                                                                                        }; ?>>MINA</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-4">
                                                                                            <input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"]; ?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
        <?php } ?>
                                                                                                            <?php } ?>
                                                                                                            <?php if (!empty($elementos_reproceso)) { ?>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="portlet box cummins">
                                                                    <div class="portlet-title">
                                                                        <div class="caption"> 
                                                                            <i class="fa fa-wrench"></i>Reproceso de Elementos (Tiempo de Reparación & Diagnóstico)</div>
                                                                        <div class="tools">
                                                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body" style="display: none;">
                                                                        <div id="table_wrapper" class="dataTables_wrapper">
                                                                            <div class="form-body">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="alert alert-success">
                                                                                            <strong>Tiempo Disponible</strong> <span class="delta_disponible_elementos_reproceso"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6">
                                                                                        <div class="alert alert-warning">
                                                                                            <strong>Tiempo Ingresado</strong>  <span class="delta_faltante_elementos_reproceso"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-striped table-bordered table-hover">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th width="20">#</th>
                                                                                                        <th>Sistema</th>
                                                                                                        <th>Subsistema</th>
                                                                                                        <th style="width:50px">Posición</th>
                                                                                                        <!--<th>Imagen</th>-->
                                                                                                        <th><div style="width: 35px">ID</div></th>
                                                                                                        <th>Elemento</th>
                                                                                                        <th style="width:50px">Posición</th>
                                                                                                        <th>Diagnóstico</th>
                                                                                                        <th style="width:100px">Solución</th>
                                                                                                        <th style="width:100px">Tipo</th>
                                                                                                        <th style="width:100px">NP Sale</th> 
                                                                                                        <th style="width:100px">NP Entra</th>
                                                                                                        <th style="width:107px" colspan="2">Duración</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                        <?php
                                                                        $cantidad_elementos = count($elementos_reproceso);
                                                                        $total_tiempo = 0;
                                                                        $elementos = $elementos_reproceso;
                                                                        for ($i = 0; $i < $cantidad_elementos; $i++) {
                                                                            $elemento = $elementos[$i]["IntervencionElementos"];
                                                                            ?>
                                                                                                        <tr>
                                                                                                            <td width="20"><?php echo $i + 1; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Sistema"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Subsistema"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Posiciones_Subsistema"]["nombre"]; ?></td>
                                                                                                            <!--<td><img src="/images/icons/control/32/illustration.png" class="ele_img" id="ele_img_" alt="" title="Ver imagen elemento" style="width:16px;cursor:pointer;margin-top:7px;" /></td>-->
                                                                                                            <td><?php echo $elemento["id_elemento"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Elemento"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Posiciones_Elemento"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Diagnostico"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["Solucion"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elementos[$i]["TipoElemento"]["nombre"]; ?></td>
                                                                                                            <td><?php echo $elemento["pn_saliente"]; ?></td>
                                                                                                            <td><?php echo $elemento["pn_entrante"]; ?></td>
                                                                                                            <td style="width: 50px;" >
            <?php
            $total_tiempo += $elemento["tiempo"];
            $hours = floor($elemento["tiempo"] / 60);
            $minutes = $elemento["tiempo"] % 60;
            ?>
                                                                                                                <input name="hora_elemento_reproceso[<?php echo $elemento["id_elemento"]; ?>]" id="hora_elemento_reproceso<?php echo $elemento["id"]; ?>" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control hora_elemento_reproceso" style="width: 42px;padding-right: 1px;padding-left: 8px;" />
                                                                                                            </td>
                                                                                                            <td style="width: 50px;" >
                                                                                                                <select name="minuto_elemento_reproceso[<?php echo $elemento["id_elemento"]; ?>]" id="minuto_elemento_reproceso<?php echo $elemento["id"]; ?>" class="form-control minuto_elemento_reproceso" style="width: 43px;" >
            <?php for ($i = 0; $i < 60; $i++) { ?>
                                                                                                                        <option value="<?php echo $i; ?>"<?php if ($minutes == $i) {
                    echo " selected=\"selected\"";
                }; ?>><?php echo str_pad($i, 2, "0", STR_PAD_LEFT);
                ; ?></option>
            <?php } ?>
                                                                                                                </select>
                                                                                                            </td>
                                                                                                        </tr>
                                                                        <?php } ?>
                                                                                                    <tr>
                                                                        <?php
                                                                        $hours = floor($total_tiempo / 60);
                                                                        $minutes = $total_tiempo % 60;
                                                                        ?>
                                                                                                        <td colspan="12"></td>
                                                                                                        <td style="width: 50px;" >
                                                                                                            <input disabled="disabled" type="number" size="4" value="<?php echo $hours; ?>" pattern="[0-9]*" min="0" class="form-control total_delta_hora_elemento_reproceso" style="width: 42px;padding-right: 1px;padding-left: 8px;" />
                                                                                                        </td>
                                                                                                        <td style="width: 50px;" >
                                                                                                            <select class="form-control total_delta_minutos_elemento_reproceso" disabled="disabled" style="width: 43px;" >
        <?php for ($i = 0; $i < 60; $i++) { ?>
                                                                                                                    <option value="<?php echo $i; ?>"<?php if ($minutes == $i) {
                echo " selected=\"selected\"";
            }; ?>><?php echo str_pad($i, 2, "0", STR_PAD_LEFT);
            ; ?></option>
        <?php } ?>
                                                                                                            </select>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    <?php }  ?>
    <?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>
        <?php
        if ((isset($fechas['IntervencionFechas']['inicio_desconexion']) && $fechas['IntervencionFechas']['inicio_desconexion'] != '') &&
                (isset($fechas['IntervencionFechas']['termino_desconexion']) && $fechas['IntervencionFechas']['termino_desconexion'] != '')) {
            ?>

                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="portlet box cummins">
                                                                        <div class="portlet-title">
                                                                            <div class="caption">
                                                                                <i class="fa fa-calendar"></i>Detalle inicio y término desconexión</div>
                                                                            <div class="tools">
                                                                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body" style="display: none;">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">Detalle de fechas</div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-lg-2"></div>
                                                                                    <div class="col-lg-4">Fecha</div>
                                                                                    <div class="col-lg-2">Hora</div>
                                                                                    <div class="col-lg-2">Minuto</div>
                                                                                    <div class="col-lg-2">Período</div>
                                                                                </div>
                                                                            </div>
            <?php
            if (isset($fechas['IntervencionFechas']['inicio_desconexion']) && $fechas['IntervencionFechas']['inicio_desconexion'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['inicio_desconexion']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Inicio desconexión</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="inicio_desconexion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="desc_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_desconexion[]" id="desc_h"  class="h_3 delta2_data form-control">
                                                                                                <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_desconexion[]" id="desc_m" class="m_3 delta2_data form-control">
                                                                                                <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_desconexion[]" id="desc_p" class="p_3 delta2_data form-control">
                                                                                                <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
            <?php
            if (isset($fechas['IntervencionFechas']['termino_desconexion']) && $fechas['IntervencionFechas']['termino_desconexion'] != '') {
                $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_desconexion']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Término desconexión</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="termino_desconexion[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="desct_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_desconexion[]" id="desct_h" class="h_4 form-control">
                                                                                                <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_desconexion[]" id="desct_m" class="m_4 form-control">
                                                                                                <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_desconexion[]" id="desct_p" class="p_4 form-control">
                                                                                                <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($intervencion["Planificacion"]["tipointervencion"] != 'EX') { ?>	
        <?php
        if ((isset($fechas['IntervencionFechas']['inicio_conexion']) && $fechas['IntervencionFechas']['inicio_conexion'] != '') &&
                (isset($fechas['IntervencionFechas']['termino_conexion']) && $fechas['IntervencionFechas']['termino_conexion'] != '')) {
            ?>
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="portlet box cummins">
                                                                        <div class="portlet-title">
                                                                            <div class="caption">
                                                                                <i class="fa fa-calendar"></i>Detalle inicio y término conexión</div>
                                                                            <div class="tools">
                                                                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body" style="display: none;">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">Detalle de fechas</div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-lg-2"></div>
                                                                                    <div class="col-lg-4">Fecha</div>
                                                                                    <div class="col-lg-2">Hora</div>
                                                                                    <div class="col-lg-2">Minuto</div>
                                                                                    <div class="col-lg-2">Período</div>
                                                                                </div>
                                                                            </div>
            <?php
            if (isset($fechas['IntervencionFechas']['inicio_conexion']) && $fechas['IntervencionFechas']['inicio_conexion'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['inicio_conexion']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Inicio conexión</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="inicio_conexion[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="con_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_conexion[]" id="con_h"  class="h_3 delta2_data form-control">
                                                                                                <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_conexion[]" id="con_m" class="m_3 delta2_data form-control">
                                                                                                <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_conexion[]" id="con_p" class="p_3 delta2_data form-control">
                                                                                                <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
            <?php
            if (isset($fechas['IntervencionFechas']['termino_conexion']) && $fechas['IntervencionFechas']['termino_conexion'] != '') {
                $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_conexion']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Término conexión</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="termino_conexion[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="cont_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_conexion[]" id="cont_h" class="h_4 form-control">
                                                                                                <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_conexion[]" id="cont_m" class="m_4 form-control">
                                                                                                <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_conexion[]" id="cont_p" class="p_4 form-control">
                                                                                                <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
        <?php } ?>

        <?php
        if ((isset($fechas['IntervencionFechas']['inicio_puesta_marcha']) && $fechas['IntervencionFechas']['inicio_puesta_marcha'] != '') &&
                (isset($fechas['IntervencionFechas']['termino_puesta_marcha']) && $fechas['IntervencionFechas']['termino_puesta_marcha'] != '')) {
            ?>

                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="portlet box cummins">
                                                                        <div class="portlet-title">
                                                                            <div class="caption">
                                                                                <i class="fa fa-calendar"></i>Detalle inicio y término puesta en marcha</div>
                                                                            <div class="tools">
                                                                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="portlet-body" style="display: none;">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">Detalle de fechas</div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="row">
                                                                                    <div class="col-lg-2"></div>
                                                                                    <div class="col-lg-4">Fecha</div>
                                                                                    <div class="col-lg-2">Hora</div>
                                                                                    <div class="col-lg-2">Minuto</div>
                                                                                    <div class="col-lg-2">Período</div>
                                                                                </div>
                                                                            </div>
            <?php
            if (isset($fechas['IntervencionFechas']['inicio_puesta_marcha']) && $fechas['IntervencionFechas']['inicio_puesta_marcha'] != '') {
                $fecha = new DateTime($fechas['IntervencionFechas']['inicio_puesta_marcha']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Inicio puesta en marcha</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="inicio_puesta_marcha[]" value="<?php echo $fecha->format('Y-m-d'); ?>" id="pm_i_f" class="f_3 delta2_data form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_puesta_marcha[]" id="pm_i_h"  class="h_3 delta2_data form-control">
                                                                                                <option value="01"<?php echo $fecha->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $fecha->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $fecha->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $fecha->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $fecha->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $fecha->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $fecha->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $fecha->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $fecha->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $fecha->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $fecha->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $fecha->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_puesta_marcha[]" id="pm_i_m" class="m_3 delta2_data form-control" >
                                                                                                <option value="00"<?php echo $fecha->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $fecha->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $fecha->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $fecha->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="inicio_puesta_marcha[]" id="pm_i_p" class="p_3 delta2_data form-control">
                                                                                                <option value="AM"<?php echo $fecha->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $fecha->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
            <?php
            if (isset($fechas['IntervencionFechas']['termino_puesta_marcha']) && $fechas['IntervencionFechas']['termino_puesta_marcha'] != '') {
                $termino_intervencion = new DateTime($fechas['IntervencionFechas']['termino_puesta_marcha']);
                ?>
                                                                                <div class="form-group">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2">Término puesta en marcha</div>
                                                                                        <div class="col-lg-4"><input type="date" size="10" name="termino_puesta_marcha[]" value="<?php echo $termino_intervencion->format('Y-m-d'); ?>" id="pm_t_f" class="f_4 form-control" max="<?php echo date("Y-m-d"); ?>" /></div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_puesta_marcha[]" id="pm_t_h" class="h_4 form-control">
                                                                                                <option value="01"<?php echo $termino_intervencion->format('h') == '01' ? ' selected="selected"' : ''; ?>>01</option>
                                                                                                <option value="02"<?php echo $termino_intervencion->format('h') == '02' ? ' selected="selected"' : ''; ?>>02</option>
                                                                                                <option value="03"<?php echo $termino_intervencion->format('h') == '03' ? ' selected="selected"' : ''; ?>>03</option>
                                                                                                <option value="04"<?php echo $termino_intervencion->format('h') == '04' ? ' selected="selected"' : ''; ?>>04</option>
                                                                                                <option value="05"<?php echo $termino_intervencion->format('h') == '05' ? ' selected="selected"' : ''; ?>>05</option>
                                                                                                <option value="06"<?php echo $termino_intervencion->format('h') == '06' ? ' selected="selected"' : ''; ?>>06</option>
                                                                                                <option value="07"<?php echo $termino_intervencion->format('h') == '07' ? ' selected="selected"' : ''; ?>>07</option>
                                                                                                <option value="08"<?php echo $termino_intervencion->format('h') == '08' ? ' selected="selected"' : ''; ?>>08</option>
                                                                                                <option value="09"<?php echo $termino_intervencion->format('h') == '09' ? ' selected="selected"' : ''; ?>>09</option>
                                                                                                <option value="10"<?php echo $termino_intervencion->format('h') == '10' ? ' selected="selected"' : ''; ?>>10</option>
                                                                                                <option value="11"<?php echo $termino_intervencion->format('h') == '11' ? ' selected="selected"' : ''; ?>>11</option>
                                                                                                <option value="12"<?php echo $termino_intervencion->format('h') == '12' ? ' selected="selected"' : ''; ?>>12</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_puesta_marcha[]" id="pm_t_m" class="m_4 form-control">
                                                                                                <option value="00"<?php echo $termino_intervencion->format('i') == '00' ? ' selected="selected"' : ''; ?>>00</option>
                                                                                                <option value="15"<?php echo $termino_intervencion->format('i') == '15' ? ' selected="selected"' : ''; ?>>15</option>
                                                                                                <option value="30"<?php echo $termino_intervencion->format('i') == '30' ? ' selected="selected"' : ''; ?>>30</option>
                                                                                                <option value="45"<?php echo $termino_intervencion->format('i') == '45' ? ' selected="selected"' : ''; ?>>45</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <select name="termino_puesta_marcha[]" id="pm_t_p" class="p_4 form-control">
                                                                                                <option value="AM"<?php echo $termino_intervencion->format('A') == 'AM' ? ' selected="selected"' : ''; ?>>AM</option>
                                                                                                <option value="PM"<?php echo $termino_intervencion->format('A') == 'PM' ? ' selected="selected"' : ''; ?>>PM</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                <?php
            }
            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
        <?php } ?>			
    <?php } ?>
<?php } ?>				
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-clock-o"></i>Avance de horas</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Avance horas potencia</div>
                                                                        <div class="col-lg-2">
                                                                            <input type="text" id="AvanceHoraPotenciaTotal" class="form-control" placeholder="0" name="AvanceHoraPotenciaTotal" value="<?php echo @$json["AvanceHoraPotenciaTotal"]; ?>" />
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" id="AvanceHoraPotenciaTotalObservacion" class="form-control" name="AvanceHoraPotenciaTotalObservacion" placeholder="Sin observaciones" value="<?php echo @$json["AvanceHoraPotenciaTotalObservacion"]; ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Avance horas OEM</div>
                                                                        <div class="col-lg-2">
                                                                            <input type="text" id="AvanceHoraPotenciaOEM" class="form-control" placeholder="0" name="AvanceHoraPotenciaOEM" value="<?php echo @$json["AvanceHoraPotenciaOEM"]; ?>" />
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" id="AvanceHoraPotenciaOEMObservacion" class="form-control" name="AvanceHoraPotenciaOEMObservacion" placeholder="Sin observaciones" value="<?php echo @$json["AvanceHoraPotenciaOEMObservacion"]; ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Avance horas MINA</div>
                                                                        <div class="col-lg-2">
                                                                            <input type="text" id="AvanceHoraPotenciaMINA" class="form-control" placeholder="0" name="AvanceHoraPotenciaMINA" value="<?php echo @$json["AvanceHoraPotenciaMINA"]; ?>" />
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" id="AvanceHoraPotenciaMINAObservacion" class="form-control" name="AvanceHoraPotenciaMINAObservacion" placeholder="Sin observaciones" value="<?php echo @$json["AvanceHoraPotenciaMINAObservacion"]; ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Avance horas DCC</div>
                                                                        <div class="col-lg-2">
                                                                            <input type="text" id="AvanceHoraPotenciaDCC" class="form-control" placeholder="0" name="AvanceHoraPotenciaDCC" value="<?php echo @$json["AvanceHoraPotenciaDCC"]; ?>" />
                                                                        </div>
                                                                        <div class="col-lg-8">
                                                                            <input type="text" id="AvanceHoraPotenciaDCCObservacion" class="form-control" name="AvanceHoraPotenciaDCCObservacion" placeholder="Sin observaciones" value="<?php echo @$json["AvanceHoraPotenciaDCCObservacion"]; ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-eyedropper"></i>Fluidos</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                                <?php foreach ($fluidos as $key => $value) { ?>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-lg-3"><?php echo $value[0]; ?></div>
                                                                            <div class="col-lg-3"><input type="text" id="fluido<?php echo $key; ?>" class="form-control" placeholder="0" name="fluido[<?php echo $key; ?>]" size="5" value="<?php echo $value[3]; ?>" /></div>
                                                                            <div class="col-lg-3"><?php echo $value[1]; ?></div>
                                                                            <div class="col-lg-3">
    <?php if (!empty($value[2])) { ?>
                                                                                    <select id="tipo_fluido<?php echo $key; ?>" name="tipo_fluido[<?php echo $key; ?>]" class="form-control">
                                                                                        <option value=""></option>
        <?php foreach ($value[2] as $key2 => $value2) { ?>
                                                                                            <option value="<?php echo $key2; ?>"<?php echo $value[4] == $key2 ? ' selected="selected"' : ''; ?>><?php echo $value2; ?></option>

        <?php } ?>
                                                                                    </select>
    <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
<?php }  ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-commenting-o"></i>Comentarios</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <textarea name="comentario" class="form-control" rows="2" id="comentario"><?php echo @$comentarios["comentario"]; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-industry"></i>Códigos KCH</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <textarea name="codigokch" class="form-control" rows="2" id="codigokch"><?php echo @$comentarios["codigo_kch"]; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="portlet box cummins">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <i class="fa fa-info-circle"></i>Turno</div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body" style="display: none;">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Supervisor responsable</div>
                                                                        <div class="col-lg-10">
                                                                            <input type="text" id="supervisor_responsable" class="form-control" readonly="readonly" name="supervisor_responsable" value="<?php echo $util->getUsuarioInfo($intervencion['Planificacion']["supervisor_responsable"]); ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Turno</div> 
                                                                        <div class="col-lg-10">
                                                                            <select name="Turno" id="Turno" class="form-control">
<?php foreach ($turnos as $key => $value) { ?>
                                                                                    <option value="<?php echo $key; ?>"<?php echo $intervencion['Planificacion']["turno_id"] == $key ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-2">Período</div>
                                                                        <div class="col-lg-10">
                                                                            <select name="Periodo" id="Periodo" class="form-control">
<?php foreach ($periodos as $key => $value) { ?>
                                                                                    <option value="<?php echo $key; ?>"<?php echo $intervencion['Planificacion']["periodo_id"] == $key ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--
                                                <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="portlet box cummins">
                                                                        <div class="portlet-title">
                                                                                <div class="caption">
                                                                                <i class="fa fa-industry"></i>Códigos KCH</div>
                                                                                <div class="tools">
                                                                                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                                </div>
                                                                        </div>
                                                                        <div class="portlet-body" style="display: none;">
                                                                                <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                                <textarea name="codigokch" class="form-control" rows="2" id="codigokch"><?php echo @$comentarios["codigo_kch"]; ?></textarea>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                -->

<?php if (isset($despliegue) && $despliegue == "Folio") { ?>
                                                    <div class="form-actions right">
                                                        <button type="button" class="btn default" onclick="window.history.back();">Volver a Administración de Folios</button>
                                                    </div>
<?php } else { ?>
                                                    <div class="form-actions right">
                                                        <button type="button" class="btn default" onclick="window.location = '/Trabajo/Historial';">Cancelar</button>

    <?php if ($intervencion["Planificacion"]["tipointervencion"] == "MP" && ($intervencion["Planificacion"]["tipomantencion"] == "250" || $intervencion["Planificacion"]["tipomantencion"] == "500" || $intervencion["Planificacion"]["tipomantencion"] == "1000")) { ?>
                                                            <button type="button" class="btn default" onclick="window.location = '/PautaMantencion/Ver/<?php echo $intervencion["Planificacion"]["id"]; ?>';">Pauta de Mantención</button>
    <?php } ?>


    <?php if (isset($fechas['IntervencionFechas']['inicio_puesta_marcha']) && $fechas['IntervencionFechas']['inicio_puesta_marcha'] != '') { ?>
                                                            <button type="button" class="btn default" onclick="window.location = '/PautaPuestaMarcha/Ver/1/<?php echo $intervencion["Planificacion"]["id"]; ?>';">Pauta de Puesta en Marcha</button>
    <?php } ?>

                                                        <!-- ***** Verifico el estado de la itervencion si es aprobado solo dejo visualizar -->
    <?php if (($intervencion["Planificacion"]["estado"] == 4 || $intervencion["Planificacion"]["estado"] == 5) && $this->request->query['edt'] == "0TxR056HBVhnli12LLGnhnANZR") { ?>
                                                            <button type="button" class="btn blue" onclick="window.location = '/Trabajo/detalle/<?php echo $intervencion["Planificacion"]["id"]; ?>?edt=0TxR056HBVhnli12LLGnhnANZR';">Ver detalle</button>
    <?php } else { ?>
                                                            <button type="button" class="btn blue submit-guardar" value="Guardar">
                                                                <i class="fa fa-filter"></i> Guardar</button>
    <?php } ?>
                                                        <!-- ***** FIN -->

                                                        <input type="hidden" name="acepta-aprobar" id="acepta-aprobar" value="" />
                                                    </div>
<?php } ?>
                                            </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="confirma_guardado" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
                                <div class="modal-body">
                                    <p> ¿Realmente desea guardar los cambios ingresados? </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-outline red">Cancelar</button>
                                    <button type="button" class="btn green btn-submit-modal">Guardar</button>
                                    <button type="button" class="btn green btn-submit-siguiente-modal">Guardar y Aprobar</button>
                                </div>
                            </div>

                            <div id="modal_mensaje_error" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
                                <div class="modal-body">
                                    <p class="mensaje_modal"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-outline red">Aceptar</button>
                                </div>
                            </div>

                            <div id="modal_imagen_elemento" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false" style="width: 800px;">
                                <div class="modal-body">
                                    <img id="imagen_elemento_url" src="" style="width: 100%;" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-dismiss="modal" class="btn btn-outline red">Cerrar</button>
                                </div>
                            </div>
            </div>
