<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
?>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Usuarios Gestión</h5>
		</div>
	  <div class="clear"></div>
	</div>
</div>
<div class="line"></div>
<?php echo $this->Session->flash();?>
<div class="wrapper">
	<div class="widget">
	<?php
		if (count($resultado)>0) { 
	?>
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Usuarios perfil GESTIÓN existentes en el sistema</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <thead>
                  <tr>
					  <?php echo "<td>" . $paginator->sort('Usuario.id', 'Código') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Usuario.usuario', 'Usuario') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Usuario.nombres', 'Nombres') . "</td>";?>
					  <?php echo "<td>" . $paginator->sort('Usuario.apellidos', 'Apellidos') . "</td>";?>
					  <td>Faena</td>
					  <td>Cargo</td>
					  <?php echo "<td width=\"30\">" . $paginator->sort('UsuarioNivel.e', 'Estado') . "</td>";?>
                  </tr>
              </thead>
              <tbody>
				<?php 
				$i = 1;
				foreach ($resultado as $usuario) {
					//$usuario=$usuario["UsuarioFaena"];
				?>
					<tr>
						<td><?php echo $usuario["Usuario"]["id"];?></td>
						<td><?php echo $usuario["Usuario"]["usuario"];?></td>
						<td><?php echo $usuario["Usuario"]["nombres"];?></td>
						<td><?php echo $usuario["Usuario"]["apellidos"];?></td>
						<td><?php
						$resultados=$util->listarFaenasUsuario($usuario["Usuario"]["id"]);
						foreach ($resultados as $r) {
							echo $r["nombre"];
							if(count($resultados)>1){
								echo '<a href="/Usuario/desasignar/'.$usuario["Usuario"]["id"].'/'.$r["id"].'">';
								echo '<img src="/images/icons/color/cross.png" alt="" title="Sacar usuario de '.$r["nombre"].'" style="margin-left:5px; margin-right:5px; width: 10px;height:10px;cursor:pointer;"></a>';
							}
						}
						
						?></td>
						<td>Gestión</td>
						<td align="center">
							<?php if($usuario["UsuarioNivel"]["e"]=='1'){?>
							<a href="/Usuario/gestion/<?php echo $usuario["UsuarioNivel"]["id"];?>/0">
								<img src="/images/icons/control/32/user.png" width="20" title="Click para desactivar" />
							</a>
							<?php }?>
							<?php if($usuario["UsuarioNivel"]["e"]=='0'){?>
							<a href="/Usuario/gestion/<?php echo $usuario["UsuarioNivel"]["id"];?>/1">
								<img src="/images/icons/control/32/user.png" width="20" style="opacity:0.3;" title="Click para activar" />
							</a>
							<?php }?>
						</td>
					</tr>
				<?php
				}
				?>
			  </tbody>
		  </table>
	<?php
		}		
	?>
</div>
</div>
<?php
if (count($resultado)>0) { 
	echo "<div class='paging'>";
	//echo $paginator->first("Primera");
	if($paginator->hasPrev()){
		echo $paginator->prev("Anterior");
	}
	echo $paginator->numbers(array('modulus' => 5));
	if($paginator->hasNext()){
		echo $paginator->next("Siguiente");
	}
	//echo $paginator->last("Última");
	echo "</div>";
}
?>