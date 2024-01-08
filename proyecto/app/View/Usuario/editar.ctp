<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Editar registro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="nombres" class="col-md-2 control-label">Nombres</label>
							<div class="col-md-4">
								<input name="data[nombres]" class="form-control" required="required" type="text" id="nombres" value="<?php echo $data["Usuario"]["nombres"]; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="apellidos" class="col-md-2 control-label">Apellidos</label>
							<div class="col-md-4">
								<input name="data[apellidos]" class="form-control" required="required" type="text" id="apellidos" value="<?php echo $data["Usuario"]["apellidos"]; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="usuario" class="col-md-2 control-label">Rut</label>
							<div class="col-md-4">
								<input name="data[usuario]" class="form-control" required="required" type="number" max="99999999" maxlength="8" min="1" id="usuario" value="<?php echo $data["Usuario"]["usuario"]; ?>" />
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="ucode" class="col-md-2 control-label">U</label>
							<div class="col-md-4">
								<input name="data[u]" class="form-control" required="required" type="text" id="u" value="<?php echo $data["Usuario"]["u"]; ?>" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="correo_electronico" class="col-md-2 control-label">Correo electrónico</label>
							<div class="col-md-4">
								<input name="data[correo_electronico]" class="form-control" required="required" type="email" id="correo_electronico" value="<?php echo $data["Usuario"]["correo_electronico"]; ?>">
							</div>
						</div>
					</div>
					<!--
					<div class="form-group">
						<div class="row">
							<label for="pin" class="col-md-2 control-label">Contraseña</label>
							<div class="col-md-4">
								<input name="data[pin]" class="form-control" minlength="8" maxlength="16" type="password" id="pin" value="" placeholder="Ingresar solo para cambiar" />
								<small>
									<ul>
										<li>Debe poseer un mínimo de 8 caracteres</li>
										<li>Debe incluir al menos una letra</li>
										<li>Debe incluir al menos un número</li>
										<li>La contraseña debe poseer solo letras y numeros</li>
									</ul>
								</small>
							</div>
							
						</div>
					</div>
					-->
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="pin" class="col-md-2 control-label">Contraseña<br>
                                                    <small class="text-info ">Ingresar contraseña en caso de requerir modificación de esta</small>
                                                </label>
                                                <div class="col-md-4">
                                                    <input name="data[pin]" class="form-control" minlength="8" maxlength="16" type="password" id="pin" value="" placeholder="Ingresar contraseña en caso de requerir modificación de esta" />
                                                    <small>
                                                        <ul>
                                                            <li>Debe poseer un mínimo de 8 caracteres</li>
                                                            <li>Debe incluir al menos una letra</li>
                                                            <li>Debe incluir al menos un número</li>
                                                            <li>La contraseña debe poseer solo letras y numeros</li>
                                                        </ul>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-4">
							<div class="form-actions">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/Usuario';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>