<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase"><?php echo $componente_str;?></span>
					<span class="caption-helper">Seleccione Flota</span>
				</div>
			</div>
                    <div class="portlet-body form">
                        <div class="row">
                        <?php foreach( $flotas as $flota ) { ?>
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <?php echo $this->Html->link($flota['Flota']['nombre'], array('action' => 'medir', $flota['FaenaFlota']['flota_id'], $componente, $flota['FaenaFlota']['motor_id']), array('class' => 'btn btn-block dark')); ?>
                                    <br>
				</div>
                        <?php } ?>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="text-align: center;">
                                <?php echo $this->Html->link("Volver", array('action' => 'medicion_normal'), array('class' => 'center btn btn-outline red btn-block')); ?>
                            </div>
                         </div>
                        
                    </div>
                </div>
        </div>
</div>