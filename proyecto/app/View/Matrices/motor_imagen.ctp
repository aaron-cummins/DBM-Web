<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Imagen <?php echo $motor; ?> - <?php echo $sistema; ?> - <?php echo $subsistema; ?></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/Matrices/MotorAgregar" class="horizontal-form" method="post">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<img src="/images/motor/<?php echo $image_source;?>.png" />
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="history.back();">Aceptar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>