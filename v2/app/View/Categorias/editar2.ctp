<?php

//formulario
echo $this->Form->create(null, array('url' => '/categorias/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('Categoria.id'); ?>
<?php echo $this->Form->hidden('Categoria.categoria'); ?>
<?php echo $this->Form->hidden('Categoria.vehiculos'); ?>

<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.categoria',array('disabled' => 'disabled')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.vehiculos',array('disabled' => 'disabled'));?></div>
    
</div>

<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('Categoria.orden'); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Categoria.activa',array('default' => '1', 'label' => 'Disponible en venta on line')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Categoria.descuento',array('default' => '0', 'label' => 'Descuento')); ?></div>
</div>





<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar.json', true);?>',$('form').serialize(),{id:'w_habilitar_categorias',url:'v2/categorias/index2'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

</script>