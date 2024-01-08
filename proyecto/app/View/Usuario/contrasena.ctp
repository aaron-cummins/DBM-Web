<h2>Cambiar contraseña</h2>
 
<div class='upper-right-opt'>
    <?php echo $this->Html->link( 'Listado de registros', array( 'action' => 'index' ) ); ?>
</div>
 
<?php 
echo $this->Form->create('Usuario');
 
    echo $this->Form->input('pin', array('label' => 'Contraseña:', 'type' => 'password', 'required' => true));
     
echo $this->Form->end('Aceptar');
?>