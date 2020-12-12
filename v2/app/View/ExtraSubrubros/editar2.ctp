<?php


//formulario
echo $this->Form->create(null, array('url' => '/extra_subrubros/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('ExtraSubrubro.id'); ?>
<?php echo $this->Form->hidden('ExtraSubrubro.extra_rubro_id'); ?>
<?php echo $this->Form->hidden('ExtraSubrubro.subrubro'); ?>


<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('ExtraSubrubro.extra_rubro_id',array('empty' => 'Seleccionar', 'options' => $extras_rubros, 'type'=>'select', 'disabled'=>'disabled'));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('ExtraSubrubro.subrubro', array('disabled'=>'disabled'));?></div>
</div>

<div class="ym-grid">
    
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('ExtraSubrubro.descuento'); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('ExtraSubrubro.impacto',array('type' => 'select', 'options' => $impacto_ops, 'empty' => 'Seleccionar ...')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('ExtraSubrubro.activo',array('default' => '0', 'label' => 'Disponible venta on line')); ?></div>
</div>





<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/extra_subrubros/guardar.json', true);?>',$('form').serialize(),{id:'w_extra_subrubros',url:'v2/extra_subrubros/index2'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

</script>