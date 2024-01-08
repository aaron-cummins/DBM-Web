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
                            <span class="caption-subject font-blue-hoki bold uppercase">Componentes a medir</span>
                            <span class="caption-helper"></span>
                    </div>
            </div>
            <?php if(isset($alert)){ ?>
                <div class="col-md-12 col-md-12 col-sm-12 col-xs-12" id="alerta">
                    <div id="chart_div"></div>
                    <?php echo $alert; ?>
                </div>
            <?php }?>
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12"> 
                        
                        <div class="dt-buttons right">
                            <a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/MedicionComponente/grabar"><i class="fa fa-plus"></i> <span>Nuevo</span></a>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th style="width: 50px;">id.</th>
                                    <th>Nombre</th>
                                    <th style="width: 100px;">Unidad de medida</th>
                                    <th colspan='2' style="text-align:center;">Rango Bajo</th>
                                    <th colspan='2' style="text-align:center;">Rango Medio</th>
                                    <th colspan='2' style="text-align:center;">Rango Alto</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?php
                                foreach( $componentes as $registro ) {
                                echo "<tr>";
                                    echo "<td>{$registro['MedicionComponente']['id']}</td>";
                                    echo "<td>{$registro['MedicionComponente']['nombre']}</td>";
                                    echo "<td>{$registro['MedicionComponente']['unidad_medida']}</td>";
                                    
                                    echo "<td>{$registro['MedicionComponente']['optimo_min']}</td>";
                                    echo "<td>{$registro['MedicionComponente']['optimo_max']}</td>";

                                    echo "<td>{$registro['MedicionComponente']['medio_min']}</td>";
                                    echo "<td>{$registro['MedicionComponente']['medio_max']}</td>";

                                    echo "<td>{$registro['MedicionComponente']['alto_min']}</td>";
                                    echo "<td>{$registro['MedicionComponente']['alto_max']}</td>";    
                                    
                                    echo "<td align=\"center\" style=\"width: 80px\">";
                                            echo $this->Html->link("Editar", array('action' => 'grabar', $registro['MedicionComponente']['id']), array('class' => 'btn btn-sm blue'));
                                    echo "</td>";
                                echo "</tr>";
                                }?>
                            </table>
                        </div>
                    </div>
                
                </div>
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                            <div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
                    </div>
                    <div class="col-md-7 col-sm-12">
                            <?php
                            if (count($componentes) > 0) { 
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
                            }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>