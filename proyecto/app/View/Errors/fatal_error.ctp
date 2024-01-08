<?php $this->layout = 'metronic_base'; ?>
<div class="row">
	<div class="col-md-12 page-500">
		<div class=" number font-red"> 404 </div>
		<div class=" details">
			<h3>Error</h3>
			<br>
			<p>
				<a href="/Dashboard" class="btn red btn-outline"> Inicio </a>
				<a href="/Logout" class="btn red btn-outline"> Login </a>
				<br> 
			</p>
		</div>
	</div>
</div>
<?php if (false): ?>
	<div class="row">
		<div class="col-md-12">
			<div class="mt-element-ribbon bg-grey-steel">
				<div class="ribbon ribbon-round ribbon-color-default uppercase">Código de error</div>
				<p class="ribbon-content">
					<?php echo $message; ?>
				</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="mt-element-ribbon bg-grey-steel">
				<div class="ribbon ribbon-round ribbon-color-default uppercase">Código de depuración</div>
				<p class="ribbon-content">
					<?php echo $this->element('exception_stack_trace'); ?>
				</p>
			</div>
		</div>
	</div>
<?php endif; ?>