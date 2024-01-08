<?php

?>
 <div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Medición</span>
					<span class="caption-helper">Seleccione</span>
				</div>
			</div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Html->link("Medición por componente", array('action' => 'medicion_normal'), array('class' => 'btn btn-block dark')); ?>
                            <br>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Html->link("Medición por mantención", array('action' => 'medicion_mantencion'), array('class' => 'btn btn-block green')); ?>
                            <br>
                        </div>
                        <!--<div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="text-align: center;">
                            <?php echo $this->Html->link("Volver", array('action' => 'index'), array('class' => 'center btn btn-outline red btn-block')); ?>
                        </div>-->
                     </div>

                </div>
            </div>
        </div>
</div>