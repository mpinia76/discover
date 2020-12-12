<?php

//formulario
echo $this->Form->create(null, array('url' => '/unidads/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('Unidad.id'); ?>
<?php echo $this->Form->hidden('Unidad.categoria_id'); ?>
<?php echo $this->Form->hidden('Unidad.marca'); ?>
<?php echo $this->Form->hidden('Unidad.modelo'); ?>
<?php echo $this->Form->hidden('Unidad.patente'); ?>
<?php echo $this->Form->hidden('Unidad.orden'); ?>
<?php echo $this->Form->hidden('Unidad.habilitacion'); ?>
<?php echo $this->Form->hidden('Unidad.periodo'); ?>
<?php echo $this->Form->hidden('Unidad.baja'); ?>
<?php echo $this->Form->hidden('Unidad.capacidad'); ?>
<?php echo $this->Form->hidden('Unidad.estado'); ?>
<?php echo $this->Form->hidden('Unidad.excluir'); ?>


<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'disabled'=>'disabled'));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.marca',array('disabled'=>'disabled'));?></div>
</div>
<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.modelo',array('disabled'=>'disabled'));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.patente',array('disabled'=>'disabled')); ?></div>
    
</div>

<div class="ym-grid">
    
    
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.activa',array('default' => '0', 'label' => 'Disponible venta on line')); ?></div>
</div>




<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/unidads/guardar.json', true);?>',$('form').serialize(),{id:'w_habilitar_unidads',url:'v2/unidads/index2'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

</script>
