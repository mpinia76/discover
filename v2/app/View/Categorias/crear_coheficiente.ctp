<?php


//formulario
echo $this->Form->create(null, array('url' => '/categorias/crear_coheficiente','inputDefaults' => (array('div' => 'ym-gbox'))));

?>



<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default'=>$selectedCategoriasID));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.dia',array('empty' => 'Seleccionar...','label' => 'Dia', 'options' => $dias)); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.coheficiente',array('type'=>'number','value'=>$coheficiente));?></div>
</div>




<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar_coheficiente.json', true);?>',$('form').serialize(),{id:'w_coheficientes_categorias',url:'v2/categorias/index_coheficientes'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonGuardarError" class="boton guardar" style="display:none">Procesando...</span>
<?php echo $this->Form->end(); ?>

<script>

</script>