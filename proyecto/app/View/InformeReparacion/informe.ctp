<?php

App::import('Controller', 'Utilidades');
App::import('Controller', 'UtilidadesReporte');
$util = new UtilidadesController();
$utilReporte = new UtilidadesReporteController();
$paginator = $this->Paginator;
$inicio = $limit * ($paginator->current() - 1) + 1;
$termino = $inicio + $limit - 1;
if ($termino > $paginator->params()['count']) {
        $termino = $paginator->params()['count'];
}

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-equalizer font-blue-hoki"></i>
                    <span class="caption-subject font-blue-hoki bold uppercase">Filtros</span>
                    <span class="caption-helper">Aplique uno o más filtros</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/InformeReparacion/informe" class="horizontal-form" method="get">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Faena</label>
                                    <select class="form-control" name="faena_id" id="faena_id" required="required"> 
                                        <option value="">Seleccione una opción</option>
					<?php foreach($faenas as $key => $value) { ?>
                                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Flota</label>
                                    <select class="form-control" name="flota_id" id="flota_id"> 
                                        <option value="">Todos</option>
					<?php
					foreach($flotas as $key => $value) { ?>
                                        <option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
					<?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Unidad</label>
                                    <select class="form-control" name="unidad_id" id="unidad_id" > 
                                        <option value="">Todos</option>
										<?php
										foreach($unidades as $key => $value) { ?>
                                        <option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Correlativo</label>
                                    <input type="number" name="correlativo" class="form-control" min="1" value="<?php echo @$correlativo;?>"> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                        <label>Tipo</label>
                                        <select class="form-control" name="tipointervencion" id="tipointervencion"> 
                                                <option value="" selected="selected">Todos</option>
                                                <option value="MP"<?php echo @$tipointervencion == 'MP' ? "selected=\"selected\"" : ""; ?>>MP</option>
                                                <option value="RP"<?php echo @$tipointervencion == 'RP' ? "selected=\"selected\"" : ""; ?>>RP</option>
                                                <option value="OP"<?php echo @$tipointervencion == 'OP' ? "selected=\"selected\"" : ""; ?>>OP</option>
                                                <option value="EX"<?php echo @$tipointervencion == 'EX' ? "selected=\"selected\"" : ""; ?>>EX</option>
                                                <option value="RI"<?php echo @$tipointervencion == 'RI' ? "selected=\"selected\"" : ""; ?>>RI</option>
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio;?>" required="required" /> </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <input type="date" name="fecha_termino" class="form-control" value="<?php echo $fecha_termino;?>" required="required" /> </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Resultados por página</label>
                                    <select class="form-control" name="limit">
                                        <option value="" <?php echo $limit == "" ? "selected=\"selected\"" : ""; ?>>Todos</option>
                                        <option value="10" <?php echo $limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
                                        <option value="25" <?php echo $limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
                                        <option value="50" <?php echo $limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
                                        <option value="100" <?php echo $limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
                                    </select> 
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/InformeReparacion/Informe';">Limpiar</button>
                        <button type="submit" class="btn blue" name="btn-aplicar"><i class="fa fa-filter"></i> Aplicar</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<?php if (count($intervenciones) > 0) {  ?>

<div class="portlet light">
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase">Historial</span>
        </div>
        <div class="tools"> </div>
    </div>
    <div class="portlet-body form">
        <!--<form action="" class="horizontal-form" method="post">-->
        <div id="table_1_wrapper" class="dataTables_wrapper">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Correlativo</th>
                                        <th>Folio</th>
                                        <th>Faena</th>
                                        <th>Flota</th>
                                        <th>Unidad</th>
                                        <th>Esn</th>
                                        <th>Tipo</th>
                                        <th colspan="2">Inicio</th>
                                        <th>Duración</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php 
                            $i = 1;
                            foreach ($intervenciones as $intervencion) {
                                echo '<tr>'; 
                            ?>

                                <td><?php echo $intervencion["ReporteBase"]["correlativo"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["folio"];?></td>
                                <td nowrap><?php echo $intervencion["ReporteBase"]["faena"];?></td>
                                <td nowrap><?php echo $intervencion["ReporteBase"]["flota"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["unidad"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["esn"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["tipo"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["fecha_inicio"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["hora_inicio"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["tiempo"];?></td>
                                <td><?php echo $intervencion["ReporteBase"]["sintoma"];?></td>
                                <td>
                                    <?php
                                    echo '<a class="btn btn-sm red btn-outline tooltips" data-toggle="modal" href="#modal_c'.$intervencion["ReporteBase"]["folio"].'" data-container="body" data-placement="top" data-original-title="Comentarios"><i class="fa fa-comments"></i>  </a>';
                                    echo '<div class="modal fade" id="modal_c'.$intervencion["ReporteBase"]["folio"].'" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Comentario Intervención</h4>
                                                </div>
                                                <div class="modal-body"> ';


                                    echo "<div class=\"row\"><div class=\"col-md-3\">Faena</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['faena']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Flota</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['flota']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Unidad</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['unidad']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Correlativo</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['correlativo']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Folio</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['folio']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Fecha inicio</div> <div class=\"col-md-9\">{$intervencion["ReporteBase"]['fecha_inicio']}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Supervisor</div> <div class=\"col-md-9\">{$util->getUsuarioInfoRut($intervencion["ReporteBase"]["supervisor_responsable"])}</div></div>";
                                    echo "<div class=\"row\"><div class=\"col-md-3\">Técnico</div> <div class=\"col-md-9\">{$util->getUsuarioInfoRut($intervencion["ReporteBase"]["tecnico_1"])}</div></div>";
                                    echo "<hr>";
                                    if (strlen($intervencion["ReporteBase"]['comentario_tecnico']) > 0) {
                                            echo "<div class=\"row\"><div class=\"col-md-3\">Comentarios</div> <div class=\"col-md-9\">";
                                            echo  strtoupper($intervencion["ReporteBase"]['comentario_tecnico']);
                                            echo "</div></div>";
                                            $hay_comentarios = true;
                                    }
                                    if (!$hay_comentarios) {
                                            echo "<div class=\"row\"><div class=\"col-md-12\">No hay comentarios ingresados</div></div>";
                                    }
                                    echo ' </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                                    <!-- /.modal-content -->
                                    </div>';
                                    
                                    ?>
                                    
                                    
                                    <a class="btn btn-sm green tooltips" data-toggle="modal" href="#modal_<?php echo $intervencion["ReporteBase"]["correlativo"] ?>"
                                       data-container="body" data-placement="top" data-original-title="Descargar Informe Reparación">
                                        <i class="fa fa-file-excel-o"></i>  
                                    </a>		

                                    <div class="modal fade" id="modal_<?php echo $intervencion['ReporteBase']['correlativo'] ?>" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
                                        <form action="/InformeReparacion/descargar/<?php echo $intervencion['ReporteBase']['correlativo'] ?>" id="form_<?php echo $intervencion['ReporteBase']['correlativo'] ?>" class="horizontal-form" method="post" enctype="multipart/form-data" target="_blank">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Adjuntar imagenes</h4>
                                                </div>
                                                <div class="modal-body"> 

                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Imagen 1</label>
                                                                    <input type="file" name="img[]" id="img1" class="form-control">
                                                                    <input type="hidden" name="correlativo" id="correlativo" class="form-control" value="<?php echo $intervencion['ReporteBase']['correlativo'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Imagen 2</label>
                                                                    <input type="file" name="img[]" id="img2" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Imagen 3</label>
                                                                    <input type="file" name="img[]" id="img3" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Imagen 4</label>
                                                                    <input type="file" name="img[]" id="img4" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer form-actions right">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" onclick="form_submit(<?php echo $intervencion['ReporteBase']['correlativo'] ?>)" class="btn blue" name="btn-submit" data-dismiss="modal">Descargar Informe</button>
                                                </div>

                                                <script type="text/javascript">
                                                    function form_submit(form) {
                                                        document.getElementById("form_" + form).submit();
                                                        $('#img1').val('');
                                                        $('#img2').val('');
                                                        $('#img3').val('');
                                                        $('#img4').val('');
                                                    }
                                                </script>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
                    </div>
                    <!-- End col -->
                    <div class="col-md-7 col-sm-12">
                    <?php
                    if (count($intervenciones) > 0) { 
                            echo '<div class="dataTables_paginate paging_bootstrap_number" id="table_1_paginate">
                            <ul class="pagination pull-right" style="visibility: visible;">';
                            echo $paginator->first("<i class=\"fa fa-angle-double-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
                            if($paginator->hasPrev()){
                                    echo $paginator->prev("<i class=\"fa fa-angle-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
                            }
                            echo $paginator->numbers(array('modulus' => 5, "separator" => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
                            if($paginator->hasNext()){
                                    echo $paginator->next("<i class=\"fa fa-angle-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
                            }
                            echo $paginator->last("<i class=\"fa fa-angle-double-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
                            echo "</ul></div>";
                    } ?>
                    </div>
                    <!-- End col -->
                </div>
            </div>
        </div>
        
        <!--</form>-->
    </div>
</div>




<?php } ?>
