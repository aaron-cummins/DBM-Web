<?php
$paginator = $this->Paginator;
$inicio = $limit * ($paginator->current() - 1) + 1;
$termino = $inicio + $limit - 1;
if ($termino > $paginator->params()['count']) {
    $termino = $paginator->params()['count'];
}
?>
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase">Motivos Aceite</span>
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
                                <a class="dt-button buttons-print btn dark" tabindex="0" aria-controls="table_1" href="/PrebacklogMotivoAceite/motivo_aceite"><i class="fa fa-plus"></i> <span>Nuevo tipo de Alerta</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_motivoAceite.id', 'ID') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_motivoAceite.nombre', 'Nombre') . "</th>"; ?>
                                        <?php echo "<th>" . $paginator->sort('Prebacklog_motivoAceite.fecha_creacion', 'Fecha Creacion') . "</th>"; ?>
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
                                        $registro['Prebacklog_motivoAceite']['fecha_creacion'] = strtotime($registro['Prebacklog_motivoAceite']['fecha_creacion']);
                                        $registro['Prebacklog_motivoAceite']['fecha_creacion'] = date("d-m-Y", $registro['Prebacklog_motivoAceite']['fecha_creacion']);


                                        echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['Prebacklog_motivoAceite']['id']}]\" value=\"{$registro['Prebacklog_motivoAceite']['e']}\">";
                                        echo "{$registro['Prebacklog_motivoAceite']['id']}</td>";
                                        echo "<td>{$registro['Prebacklog_motivoAceite']['motivo']}</td>";
                                        echo "<td>{$registro['Prebacklog_motivoAceite']['fecha_creacion']}</td>";
                                        echo "<td align=\"center\" style=\"width: 60px;\">";
                                        echo '<div class="form-group" style="margin-bottom: 0;">';
                                        echo '<div class="mt-checkbox-inline">';
                                        echo '<label class="mt-checkbox mt-checkbox-outline">';
                                        if($registro['Prebacklog_motivoAceite']['e'] == '1') {
                                                echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Prebacklog_motivoAceite']['id']}]\" value=\"1\" checked=\"checked\" />";
                                        } else {
                                                echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Prebacklog_motivoAceite']['id']}]\" value=\"1\" />";
                                        }
                                        echo "<span></span>";
                                        echo "</label>";
                                        echo '</div>';
                                        echo '</div>';
                                        echo "</td>";
                                        echo "<td align=\"center\" style=\"width: 80px\">";
                                                echo $this->Html->link("Editar", array('action' => 'motivo_aceite', $registro['Prebacklog_motivoAceite']['id']), array('class' => 'btn btn-sm blue'));
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
