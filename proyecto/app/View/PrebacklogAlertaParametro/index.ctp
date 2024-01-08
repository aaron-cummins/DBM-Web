<?php
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
                <form action="/PrebacklogalertaParametro" class="horizontal-form" method="get">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Alerta</label>
                                    <select class="form-control" name="alerta_id" id="alerta_id">
                                        <option value="">Todos</option>
                                        <?php foreach ($alertas as $key => $value) { ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
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
                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="button" class="btn default" onclick="window.location = '/PrebacklogalertaParametro';">Limpiar</button>
                        <button type="submit" class="btn blue">
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
            <span class="caption-subject bold uppercase">Tipo Alertas</span>
        </div>
        <div class="tools"> </div>
    </div>
    <div class="portlet-body form">
        <form action="" class="horizontal-form" method="post">
             <div id="table_1_wrapper" class="dataTables_wrapper">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="dt-buttons">
                                <a class="dt-button buttons-print btn dark" tabindex="0" aria-controls="table_1" href="/PrebacklogAlertaParametro/alerta_parametro"><i class="fa fa-plus"></i> <span>Nuevo parámetro</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_alertaParametro.id', 'ID') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_alertaParametro.nombre', 'Nombre') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_alerta.nombre', 'Alerta') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Criticidad.nombre', 'Criticidad') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_alerta.unidad_medida', 'U. medida') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_alertaParametro.fecha_creacion', 'Fecha Creacion') . "</th>"; ?>
                                        <th align="center">
                                        <?php
                                                echo '<div class="form-group" style="margin-bottom: 0;">';
                                                echo '<div class="mt-checkbox-inline">';
                                                echo '<label class="mt-checkbox mt-checkbox-outline">';
                                                echo "<input type=\"checkbox\" class=\"seleccion-masiva\" />";
                                                echo "<span></span>";
                                                echo "</label>";
                                                echo '</div>';
                                                echo '</div>';
                                        ?>
                                        </th>
                                        <th colspan="3" align="center">Acciones</th>
                                    </tr>
                                    <?php
                                    $i = 1;
                                    foreach ($registros as $registro) {
                                        $registro['Prebacklog_alertaParametro']['fecha_creacion'] = strtotime($registro['Prebacklog_alertaParametro']['fecha_creacion']);
                                        $registro['Prebacklog_alertaParametro']['fecha_creacion'] = date("d-m-Y", $registro['Prebacklog_alertaParametro']['fecha_creacion']);


                                        echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['Prebacklog_alertaParametro']['id']}]\" value=\"{$registro['Prebacklog_alertaParametro']['e']}\">";
                                        echo "{$registro['Prebacklog_alertaParametro']['id']}</td>";
                                        echo "<td>{$registro['Prebacklog_alertaParametro']['nombre']}</td>";
                                        echo "<td>{$registro['Prebacklog_alerta']['nombre']}</td>";
                                        echo "<td>{$registro['Criticidad']['nombre']}</td>";
                                        echo "<td>{$registro['Prebacklog_alertaParametro']['unidad_medida']}</td>";
                                        echo "<td>{$registro['Prebacklog_alertaParametro']['fecha_creacion']}</td>";
                                        echo "<td align=\"center\" style=\"width: 60px;\">";
                                        echo '<div class="form-group" style="margin-bottom: 0;">';
                                        echo '<div class="mt-checkbox-inline">';
                                        echo '<label class="mt-checkbox mt-checkbox-outline">';
                                        if($registro['Prebacklog_alertaParametro']['e'] == '1') {
                                                echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Prebacklog_alertaParametro']['id']}]\" value=\"1\" checked=\"checked\" />";
                                        } else {
                                                echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Prebacklog_alertaParametro']['id']}]\" value=\"1\" />";
                                        }
                                        echo "<span></span>";
                                        echo "</label>";
                                        echo '</div>';
                                        echo '</div>';
                                        echo "</td>";
                                        echo "<td align=\"center\" style=\"width: 80px\">";
                                                echo $this->Html->link("Editar", array('action' => 'alerta_parametro', $registro['Prebacklog_alertaParametro']['id']), array('class' => 'btn btn-sm blue'));
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
                 <div class="form-actions right">
                    <button type="submit" class="btn blue"><i class="fa fa-save"></i> Guardar </button>
                </div>
            </div>
        </form>
    </div>
</div>