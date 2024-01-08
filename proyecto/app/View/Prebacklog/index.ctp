<?php
App::import('Controller', 'Utilidades');
$usuario = $this->Session->read('Usuario');
$util = new UtilidadesController();

$paginator = $this->Paginator;
$inicio = $limit * ($paginator->current() - 1) + 1;
$termino = $inicio + $limit - 1;
if ($termino > $paginator->params()['count']) {
    $termino = $paginator->params()['count'];
}

$faena_session = $this->Session->read("faena_id");
$usuario_id = $this->Session->read('usuario_id');
$cargos = $this->Session->read("PermisosCargos")

?>
<div class="row widget-row">
     <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-default icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=7"><span class="widget-thumb-subtitle">SIN REVISAR</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $sinrevisar ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-yellow-lemon icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=3"><span class="widget-thumb-subtitle">SIN PLANIFICAR</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $revisado ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-primary icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=2"><span class="widget-thumb-subtitle">SIN EJECUTAR</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $planificado ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green-jungle icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=11"><span class="widget-thumb-subtitle">SIN CIERRE</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $realizado ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <!--<h4 class="widget-thumb-heading">TOTAL</h4>-->
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-yellow-gold icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=13"><span class="widget-thumb-subtitle">Cerrados</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $cerrado ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    
    <div class="col-md-2">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-blue icon-layers"></i>
                <div class="widget-thumb-body">
                    <a href="/Prebacklog/index?estado_id=9"><span class="widget-thumb-subtitle">DESACTIVADO</span></a>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="1,293"><?php echo $desactivado ?></span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Filtros y Descarga</span>
                    <span class="caption-helper">Aplique uno o más filtros</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body form" style="display: none;">
                <!-- BEGIN FORM-->
                <form action="/Prebacklog" class="horizontal-form" method="get">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Faena</label>
                                    <select class="form-control" name="faena_id" id="faena_id"> 
                                        <option value="">Todos</option>
                                        <?php foreach ($faenas as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Flota</label>
                                    <select class="form-control" name="flota_id" id="flota_id" > 
                                        <option value="">Todos</option>
                                        <?php
                                        print_r($this->Session->read("FaenasFiltro"));
                                        foreach ($flotas as $key => $value) {
                                            ?>
                                            <option value="<?php echo $value["FaenaFlota"]["faena_id"]; ?>_<?php echo $value["FaenaFlota"]["flota_id"]; ?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"]; ?>"><?php echo $value["Flota"]["nombre"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unidad</label>
                                    <select class="form-control" name="unidad_id" id="unidad_id" > 
                                        <option value="">Todos</option>
                                        <?php foreach ($unidades as $key => $value) { ?>
                                            <option value="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>_<?php echo $value["Unidad"]["id"]; ?>" faena_flota="<?php echo $value["Unidad"]["faena_id"]; ?>_<?php echo $value["Unidad"]["flota_id"]; ?>"><?php echo $value["Unidad"]["unidad"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Criticidad</label>
                                    <select class="form-control" name="criticidad_id" id="criticidad_id">
                                        <option value="" selected="selected">Todos</option>
                                        <option value="1"<?php echo @$criticidad_id == '1' ? "selected=\"selected\"" : ""; ?>>Alto</option>
                                        <option value="2"<?php echo @$criticidad_id == '2' ? "selected=\"selected\"" : ""; ?>>Medio</option>
                                        <option value="3"<?php echo @$criticidad_id == '3' ? "selected=\"selected\"" : ""; ?>>Bajo</option>
                                    </select> 
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label>Fecha inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>" max="<?php echo date("Y-m-d"); ?>" /> </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label>Fecha término</label>
                                    <input type="date" name="fecha_termino" class="form-control" value="<?php echo $fecha_termino; ?>" max="<?php echo date("Y-m-d"); ?>" /> </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select class="form-control" name="estado_id" id="estado_id">
                                        <option value="" selected="selected">Todos</option>
                                        <option value="7" <?php echo @$estado_id == 7 ? "selected=\"selected\"" : ""; ?>>Sin Revisar</option>
                                        <option value="3" <?php echo @$estado_id == 3 ? "selected=\"selected\"" : ""; ?>>Sin Planificar</option>
                                        <option value="2" <?php echo @$estado_id == 2 ? "selected=\"selected\"" : ""; ?>>Sin Ejecutar</option>
                                        <option value="11" <?php echo @$estado_id == 11 ? "selected=\"selected\"" : ""; ?>>Sin Cierre</option>
                                        <option value="9" <?php echo @$estado_id == 9 ? "selected=\"selected\"" : ""; ?>>Desactivado</option>
                                        <option value="13" <?php echo @$estado_id == 13 ? "selected=\"selected\"" : ""; ?>>Cerrado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Resultados por página</label>
                                    <select class="form-control" name="limit">
                                        <option value="10" <?php echo @$limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
                                        <option value="25" <?php echo @$limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
                                        <option value="50" <?php echo @$limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
                                        <option value="100" <?php echo @$limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
                                    </select> 
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label>Folio</label>
                                    <input type="text" name="folio" class="form-control" value="<?php echo @$folio; ?>"> </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/Preacklog';">Limpiar</button>
                        <button type="submit" class="dt-button buttons-print btn blue-soft btn-outline" name="btn-descargar">
                            <i class="fa fa-file-excel-o"></i> Descargar Excel</button>
                        <button type="submit" class="dt-button buttons-print btn blue-soft">
                            <i class="fa fa-filter"></i> Aplicar</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<div class="portlet light">
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase">Prbacklogs</span>
            <span class="caption-helper">Prebacklog parámetros en total <?php echo $total ?></span>
        </div>
        <div class="tools"> </div>
    </div>
    <div class="portlet-body form">
        <form action="" class="horizontal-form" method="post" id="form-prebacklogs">
            <input type="hidden" name="id_prebacklog" class="form-control" id="id_prebacklog" value="" />
            <input type="hidden" name="motivo_cierre" class="form-control" id="motivo_cierre" value=""/>
            <input type="hidden" name="comentario_cierre" class="form-control" id="comentario_cierre" value="" />
            <div id="table_1_wrapper" class="dataTables_wrapper">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dt-buttons">
                                <a class="dt-button buttons-print btn blue-soft" tabindex="0" aria-controls="table_1" href="/Prebacklog/index"><i class="fa fa-eraser"></i> <span>Limpiar filtros</span></a>
                                <!--<a class="dt-button buttons-print btn blue-soft btn-outline" tabindex="0" aria-controls="table_1" href="/Prebacklog/descarga_parametros"><i class="fa fa-file-excel-o"></i> <span>Descargar</span></a>-->
                                <a class="dt-button buttons-print btn blue-soft" tabindex="0" aria-controls="table_1" href="/Prebacklog/prebacklog_parametro"><i class="fa fa-plus"></i> <span>Prebacklog Parámetros</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog.id', 'Folio') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Faena.nombre', 'Faena') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Flota.nombre', 'Flota') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Unidad.unidad', 'Unidad') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('SintomaCategoria.nombre', 'Cat. Sintoma') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Sintoma.nombre', 'Sintoma') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog.fecha_creacion', 'Fecha') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Criticidad.nombre', 'Criticidad') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Estado.nombre', 'Estado') . "</th>"; ?>
                                        <th align="center">Acciones</th>
                                    </tr>
                                    <?php
                                    $i = 1;
                                    foreach ($registros as $registro) {
                                        $registro['Prebacklog']['fecha_creacion'] = strtotime($registro['Prebacklog']['fecha_creacion']);
                                        $registro['Prebacklog']['fecha_creacion'] = date("d-m-Y H:i:s", $registro['Prebacklog']['fecha_creacion']);


                                        echo "<tr>";
                                        echo "<td>{$registro['Prebacklog']['id']}</td>";
                                        echo "<td>{$registro['Faena']['nombre']}</td>";
                                        echo "<td>{$registro['Flota']['nombre']}</td>";
                                        echo "<td>{$registro['Unidad']['unidad']}</td>";
                                        echo "<td>{$registro['SintomaCategoria']['nombre']}</td>";
                                        echo "<td>{$registro['Sintoma']['nombre']}</td>";
                                        echo "<td>{$registro['Prebacklog']['fecha_creacion']}</td>";
                                        echo "<td>";
                                            if($registro['Criticidad']['nombre'] == 'Alto'){
                                                echo    '<span class="label label-sm label-danger">Alto</span>';
                                            }else if($registro['Criticidad']['nombre'] == 'Medio'){
                                                echo    '<span class="label label-sm label-warning">Medio</span>';
                                            }else{
                                                echo    '<span class="label label-sm label-success">Bajo</span>';
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            if($registro['Estado']['id'] == 7){
                                                echo    '<span class="label label-sm label-default">'. $registro['Estado']['nombre_prebacklog'].'</span>';
                                            } else if($registro['Estado']['id'] == 3){
                                                echo    '<span class="label label-sm label-warning">'. $registro['Estado']['nombre_prebacklog'].' </span>';
                                            } else if($registro['Estado']['id'] == 11){
                                                echo    '<span class="label label-sm label-success">'. $registro['Estado']['nombre_prebacklog'].'</span>';
                                            } else if($registro['Estado']['id'] == 9){
                                                echo    '<span class="label label-sm label-info">'. $registro['Estado']['nombre_prebacklog'].'</span>';
                                            } else if($registro['Estado']['id'] == 2){
                                                echo    '<span class="label label-sm label-primary">'. $registro['Estado']['nombre_prebacklog'].'</span>';
                                            } else if($registro['Estado']['id'] == 13){
                                                echo    '<span class="label label-sm bg-yellow-gold">'. $registro['Estado']['nombre_prebacklog'].'</span>';
                                            }
                                            
                                            
                                        echo "</td>";
                                        echo "<td>";
                                        echo '<a class="btn btn-sm yellow tooltips" data-toggle="modal" href="#modal_' . $registro['Prebacklog']['id'] . '" onclick="getComentarios(' . $registro['Prebacklog']['id'] . ');" data-container="body" data-placement="top" data-original-title="Comentarios"><i class="fa fa-comments"></i>  </a>';
                                        echo '<div class="modal fade" id="modal_' . $registro['Prebacklog']['id'] . '" tabindex="-1" role="basic" aria-hidden="true" style="display: none;background-color:  transparent;;border:  0;box-shadow: none;">
                                                <div class="modal-dialog" style="width: 500px !important">
                                                        <div class="modal-content">
                                                                <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                        <h4 class="modal-title">Comentario prebacklog</h4>
                                                                </div>
                                                                <div class="modal-body"> 
                                                                    <div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                                                        <ul class="chats" id="body' . $registro['Prebacklog']['id'] . '">
                                                                            <img src="/images/loaders/loader12.gif">
                                                                        </ul>
                                                                    </div>';
                                        echo '                  </div>
                                                                <div class="modal-footer">
                                                                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>';
                                        #echo "</td>";


                                       //echo "</td>";

                                        //VISTA DE PREBACKLOG
                                        echo '<a class="btn btn-sm blue tooltips" href="/Prebacklog/vista_parametros/' . $registro['Prebacklog']['id'] . '" data-container="body" data-placement="top" data-original-title="ver prebacklog"><i class="fa fa-eye"></i>  </a>';
                                        #echo "</td>";
                                        
                                        
                                        //CERRAR PREBACKLOG
                                        if($registro['Prebacklog']['estado_id'] == 7){
                                            #echo "<td>";
                                            echo '<a class="btn btn-sm red-haze tooltips" data-toggle="modal" href="#modal_cierre' . $registro['Prebacklog']['id'] . '" data-container="body" data-placement="top" data-original-title="Cerrar"><i class="fa fa-times-circle"></i>  </a>';
                                            echo '<div class="modal fade" id="modal_cierre' . $registro['Prebacklog']['id'] . '" tabindex="-1" role="basic" aria-hidden="true" style="display: none;background-color:  transparent;;border:  0;box-shadow: none;">
                                                    <div class="modal-dialog" style="width: 500px !important">
                                                            <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                        <h4 class="modal-title">Cierre de prebacklog</h4>
                                                                    </div>
                                                                    <div class="modal-body"> 
                                                                        <div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                                                            <input type="hidden" name="id' . $registro['Prebacklog']['id'] . '" id="id' . $registro['Prebacklog']['id'] . '">
                                                                            <div class="alert alert-danger" id="alerta_cierra' . $registro['Prebacklog']['id'] . '" style="display: none;">
                                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                                <strong><i class="fa fa-exclamation"></i> ALERTA! </strong>
                                                                                Debe ingresar un motivo por el cual cerrará el prebacklog.
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Motivo Cierre</label>
                                                                                <select class="form-control" name="motivo_cierre_' . $registro['Prebacklog']['id'] . '" id="motivo_cierre_' . $registro['Prebacklog']['id'] . '" > 
                                                                                    <option value="">Todos</option>';
                                                                                    foreach ($motivocierre as $key => $value) {
                                            echo '                                      <option value="' . $value["Prebacklog_motivoCierre"]["id"] . '">' . $value["Prebacklog_motivoCierre"]["motivo"] . '</option>';
                                                                                    }
                                            echo '                              </select>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label>Comentario</label>
                                                                                <textarea id="comentario_cierre_' . $registro['Prebacklog']['id'] . '" name="cometario_cierre_' . $registro['Prebacklog']['id'] . '" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                 </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                                        <button type="button" class="btn green btn-submit-cierre" onclick="cierra_prebacklog(' . $registro['Prebacklog']['id'] . ');">Aceptar</button>
                                                                    </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>';
                                            #echo "</td>";
                                        }


                                        //CREAR BACKLOG
                                        if ($registro['Prebacklog']['estado_id'] == 7) {
                                            //echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Previsualizar', $registro['Planificacion']['id']), array('class' => 'btn btn-sm blue'));
                                            echo '<a class="btn btn-sm green tooltips" href="#" onclick="crea_backlog(' . $registro['Prebacklog']['id'] . ', '. $registro['Prebacklog']['tipo'] .')" data-container="body" data-placement="top" data-original-title="Crear backlog"><i class="fa fa-th"></i>  </a>';
                                            //echo $this->Html->link("Planificar", array('controller' => 'Trabajo', 'action' => 'Planificar' ,"Backlog", $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
                                           
                                        }
                                        
                                        
                                        //VALIDAR PREBACKLOG REALIZADO
                                        if ($registro['Prebacklog']['estado_id'] == 11) {
                                            
                                            echo '<a class="btn btn-sm blue-ebonyclay tooltips" data-toggle="modal" href="#valida' . $registro['Prebacklog']['id'] . '" data-container="body" data-placement="top" data-container="body" data-placement="top" data-original-title="Validar"><i class="fa fa-check-square-o"></i>  </a>';
                                            echo '<div id="valida' . $registro['Prebacklog']['id'] . '" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false" style="display: none; margin-top: -77.5px;" aria-hidden="true">
                                                    <div class="modal-header">
                                                        <button type="button" id="close' . $registro['Prebacklog']['id'] . '" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h5 class="modal-title">¿Se resolvió el problema? o ¿Necesita crear otro backlog?</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-body">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>comentario</label>
                                                                    <textarea name="comentario_' . $registro['Prebacklog']['id'] . '" id="comentario_' . $registro['Prebacklog']['id'] . '" class="form-control"></textarea>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label><b>Esta alerta:</b> </label>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="md-checkbox-list">
                                                                                <div class="md-checkbox">
                                                                                    <input type="checkbox" id="e_falla_mayor_' . $registro['Prebacklog']['id'] . '" name="e_falla_mayor_' . $registro['Prebacklog']['id'] . '" value="1" class="md-check">
                                                                                    <label for="e_falla_mayor_' . $registro['Prebacklog']['id'] . '">
                                                                                        <span></span>
                                                                                        <span class="check"></span>
                                                                                        <span class="box"></span> Evitó falla mayor </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                         <div class="col-md-6">
                                                                            <div class="md-checkbox-list">
                                                                                <div class="md-checkbox">
                                                                                    <input type="checkbox" id="e_falla_acumulativa_' . $registro['Prebacklog']['id'] . '" name="e_falla_acumulativa_' . $registro['Prebacklog']['id'] . '" value="1" class="md-check">
                                                                                    <label for="e_falla_acumulativa_' . $registro['Prebacklog']['id'] . '">
                                                                                        <span></span>
                                                                                        <span class="check"></span>
                                                                                        <span class="box"></span> Evitó falla acumulativa </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="md-checkbox-list">
                                                                                <div class="md-checkbox">
                                                                                    <input type="checkbox" id="e_falla_electrica_' . $registro['Prebacklog']['id'] . '" name="e_falla_electrica_' . $registro['Prebacklog']['id'] . '" value="1" class="md-check">
                                                                                    <label for="e_falla_electrica_' . $registro['Prebacklog']['id'] . '">
                                                                                        <span></span>
                                                                                        <span class="check"></span>
                                                                                        <span class="box"></span> Resuelve falla eléctrica </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="md-checkbox-list">
                                                                                <div class="md-checkbox">
                                                                                    <input type="checkbox" id="no_existe_falla_' . $registro['Prebacklog']['id'] . '" name="no_existe_falla_' . $registro['Prebacklog']['id'] . '" value="1" class="md-check">
                                                                                    <label for="no_existe_falla_' . $registro['Prebacklog']['id'] . '">
                                                                                        <span></span>
                                                                                        <span class="check"></span>
                                                                                        <span class="box"></span> Falla no existe </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <p>&nbsp;</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" onclick="validar(' . $registro['Prebacklog']['id'] . ', '. $registro['Prebacklog']['tipo'] .', 1)" class="btn btn-outline dark">Crear Backlog</button>
                                                        <button type="button" onclick="validar(' . $registro['Prebacklog']['id'] . ', '. $registro['Prebacklog']['tipo'] .', 0)" class="btn green">Cerrar Prebacklog</button>
                                                    </div>
                                                </div>'; 
                                           
                                        }

                                        if ($util->check_permissions_menu_item("Prebacklog", "Administracion", $faena_session, $usuario_id, $cargos) == TRUE && ($registro['Prebacklog']['estado_id'] == 9 || $registro['Prebacklog']['estado_id'] == 13 )) {
                                            echo '<a class="btn btn-sm green-jungle tooltips" data-toggle="modal" href="#reabre' . $registro['Prebacklog']['id'] . '" data-container="body" data-placement="top" data-container="body" data-placement="top" data-original-title="Reabrir"><i class="fa fa-reply"></i>  </a>';
                                            echo '<div id="reabre' . $registro['Prebacklog']['id'] . '" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false" style="display: none; margin-top: -77.5px;" aria-hidden="true">
                                                    <div class="modal-header">
                                                        <button type="button" id="close' . $registro['Prebacklog']['id'] . '" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h5 class="modal-title">Reabrir prebacklog, asignar un nuevo backlog</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-body">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>comentario</label>
                                                                    <textarea name="comentario_' . $registro['Prebacklog']['id'] . '" id="comentario_' . $registro['Prebacklog']['id'] . '" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p>&nbsp;</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" onclick="reabre(' . $registro['Prebacklog']['id'] . ', '. $registro['Prebacklog']['tipo'] .', 1)" class="btn btn-outline dark">Crear Backlog</button>
                                                    </div>
                                                </div>';
                                        }

                                        //Eliminar prebacklog
                                        if ($util->check_permissions_menu_item("Prebacklog", "Administracion", $faena_session, $usuario_id, $cargos) == TRUE && $registro['Prebacklog']['estado_id'] == 7) {
                                            //echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Previsualizar', $registro['Planificacion']['id']), array('class' => 'btn btn-sm blue'));
                                            echo '<a class="btn btn-sm red-intense tooltips" href="#" onclick="eliminar_prebacklog(' . $registro['Prebacklog']['id'] . ', '. $registro['Prebacklog']['tipo'] .')" data-container="body" data-placement="top" data-original-title="Elinminar prebacklog"><i class="fa fa-trash"></i>  </a>';
                                            //echo $this->Html->link("Planificar", array('controller' => 'Trabajo', 'action' => 'Planificar' ,"Backlog", $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
                                           
                                        }
                                        echo "</td>";

                                        $i++;
                                        echo "</tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-sm-12">
                            <input type="hidden" class="trabajos" value="<?php echo ($i - 1); ?>" />
                            <div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio; ?> a <?php echo $termino; ?> de <?php echo $paginator->params()['count']; ?> registros</div>
                        </div>
                        <div class="col-md-7 col-sm-12">
                            <?php
                            if (count($registros) > 0) {
                                echo '<div class="dataTables_paginate paging_bootstrap_number" id="table_1_paginate">
								<ul class="pagination pull-right" style="visibility: visible;">';
                                echo $paginator->first("<i class=\"fa fa-angle-double-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
                                if ($paginator->hasPrev()) {
                                    echo $paginator->prev("<i class=\"fa fa-angle-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
                                }
                                echo $paginator->numbers(array('modulus' => 5, "separator" => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
                                if ($paginator->hasNext()) {
                                    echo $paginator->next("<i class=\"fa fa-angle-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
                                }
                                echo $paginator->last("<i class=\"fa fa-angle-double-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
                                echo "</ul></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>





<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    function crea_backlog(id, tipo){
        if(confirm("Va a crear un Backlog, ¿esta seguro?")){
            window.location.href  = "/Backlog/web?idPrebacklog="+id+"&tipo="+tipo+"&validationes=S4lV4DoR%C4m1l4YA4r0N-ANZR6hkai1DCC";
        }else{
            return false;
        }
    }
    
    
    function eliminar_prebacklog(id, tipo){
        if(confirm("Va a Eliminar este Preacklog, ¿esta seguro?")){
            window.location.href  = "/Prebacklog/eliminar/"+id+"/"+tipo+"&validationes=DEL-S4lV4DoR%C4m1l4YA4r0N-ANZR6hkai1DCC";
        }else{
            return false;
        }
    }
    
    function validar(id, tipo, option){
        var unoOpcion = 0;

        if($("#comentario_"+id).val() == ""){
            alert("Debe ingresar un comentario.")
            return false;
        }

        $efm = document.getElementById('e_falla_mayor_'+id).checked ? 1 : 0;
        $efa = document.getElementById('e_falla_acumulativa_'+id).checked ? 1 : 0;
        $efe = document.getElementById('e_falla_electrica_'+id).checked ? 1 : 0;
        $nef = document.getElementById('no_existe_falla_'+id).checked ? 1 : 0;

        if($efm == 1) unoOpcion += 1;
        if($efa == 1) unoOpcion += 1;
        if($efe == 1) unoOpcion += 1;
        if($nef == 1) unoOpcion += 1;

        if(unoOpcion > 1 && option == 0) {
            alert("Solo debe seleccionar solo una opción.")
            return false;
        }else if(unoOpcion == 0 && option == 0){
            alert("Debe seleccionar al menos una opción.")
            return false;
        }else if(unoOpcion > 0 && option == 1 ){
            alert("Al crear un nuevo backlog no debe seleccionar ninguna opción.")
            return false;
        }

        $("#close"+id).click();



        if(option == 1){
            if (confirm('el comentario se guardará y va a generar un nuevo backlog, ¿está seguro?')){
                window.location.href  = "/Prebacklog/validar?idPrebacklog="+id+"&tipo="+tipo+"&option="+option+"&comentario="+$("#comentario_"+id).val()+
                                        "&efm="+$efm+
                                        "&efa="+$efa+
                                        "&efe="+$efe+
                                        "&nef="+$nef;
            }else{
                $("#comentario_"+id).val("");
            }
        }else{
            if (confirm('el comentario se guardará y se cerrará el prebacklog, ¿está seguro?')){
                window.location.href  = "/Prebacklog/validar?idPrebacklog="+id+"&tipo="+tipo+"&option="+option+"&comentario="+$("#comentario_"+id).val()+
                                        "&efm="+$efm+
                                        "&efa="+$efa+
                                        "&efe="+$efe+
                                        "&nef="+$nef;
            }else{
                $("#comentario_"+id).val("");
            }
        }
    }

    function reabre(id, tipo, option){
        var unoOpcion = 0;

        if($("#comentario_"+id).val() == ""){
            alert("Debe ingresar un comentario.")
            return false;
        }
        $("#close"+id).click();

        if(option == 1){
            if (confirm('el comentario se guardará y va a generar un nuevo backlog, ¿está seguro?')){
                window.location.href  = "/Prebacklog/validar?idPrebacklog="+id+"&tipo="+tipo+"&option="+option+"&comentario="+$("#comentario_"+id).val()+"&efm=0&efa=0&efe=0&nef=0";
            }else{
                $("#comentario_"+id).val("");
            }
        }else{
            if (confirm('el comentario se guardará y se cerrará el prebacklog, ¿está seguro?')){
                window.location.href  = "/Prebacklog/validar?idPrebacklog="+id+"&tipo="+tipo+"&option="+option+"&comentario="+$("#comentario_"+id).val()+"&efm=0&efa=0&efe=0&nef=0"
            }else{
                $("#comentario_"+id).val("");
            }
        }
    }
    
    function cierra_prebacklog(id){
        if ($("#motivo_cierre_"+id).val() != '') {
            
            if (confirm('¿Esta seguro de cerrar este prebacklog?')) {
                $("#alerta_cierra" + id).hide();
                $("#id_prebacklog").val(id);
                $("#motivo_cierre").val($("#motivo_cierre_" + id).val());
                $("#comentario_cierre").val($("#comentario_cierre_"+id).val());
                $("#form-prebacklogs").submit();
            }
        } else {
            $("#alerta_cierra"+id).show();
        }
    }
    
    function getComentarios(id) {
        html = '';
        $.get("/Prebacklog/comentarios/" + id, function (data) {
            var obj = $.parseJSON(data);
            $.each(obj, function (i, item) {

                if (i % 2 == 0) {
                    html += '<li class="in">'
                            + '    <img class="avatar" alt="" src="/images/user.png">'
                            + '    <div class="message">'
                            + '        <span class="arrow"> </span>'
                            + '        <a href="javascript:;" class="name">' + item.Usuario.nombres + ' ' + item.Usuario.apellidos + '</a>'
                            + '        <span class="datetime"> (' + item.Prebacklog_comentario.fecha + ') </span>'
                            + '        <span class="body">' + item.Prebacklog_comentario.comentario + '</span>'
                            + '    </div>'
                            + '</li>';
                } else {
                    html += '<li class="out">'
                            + '    <img class="avatar" alt="" src="/images/user.png">'
                            + '    <div class="message">'
                            + '        <span class="arrow"> </span>'
                            + '        <a href="javascript:;" class="name">' + item.Usuario.nombres + ' ' + item.Usuario.apellidos + '</a>'
                            + '        <span class="datetime"> (' + item.Prebacklog_comentario.fecha + ') </span>'
                            + '        <span class="body">' + item.Prebacklog_comentario.comentario + '</span>'
                            + '    </div>'
                            + '</li>';
                }

            });

            if (html == '') {
                html += '<li class="">'
                        + ' No hay mensajes para mostrar'
                        + '</li>';
            }

            $('#body' + id).html();
            $('#body' + id).html(html);
        });
    }
</script>
