<?php

//formulario
echo $this->Form->create(null, array('url' => '/categorias/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('Categoria.id'); ?>
<?php echo $this->Form->hidden('Categoria.activa'); ?>
<?php echo $this->Form->hidden('Categoria.descuento'); ?>
<?php echo $this->Form->hidden('Categoria.orden'); ?>
<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.categoria');?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.vehiculos');?></div>
    
</div>
<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.vehiculos_ingles');?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Categoria.vehiculos_portugues');?></div>
    
</div>


<div class="sectionSubtitle">Descripcion</div>
<div class="ym-grid">
    <?php echo $this->Form->input('Categoria.descripcion',array('label' => false, 'type' => 'textarea')); ?>
</div>
<div class="sectionSubtitle">Descripcion ingles</div>
<div class="ym-grid">
    <?php echo $this->Form->input('Categoria.descripcion_ingles',array('label' => false, 'type' => 'textarea')); ?>
</div>
<div class="sectionSubtitle">Descripcion portugues</div>
<div class="ym-grid">
    <?php echo $this->Form->input('Categoria.descripcion_portugues',array('label' => false, 'type' => 'textarea')); ?>
</div>



<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar.json', true);?>',$('form').serialize(),{id:'w_categorias',url:'v2/categorias/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

</script>