<?php


//formulario
echo $this->Form->create(null, array('url' => '/categorias/crear_coheficiente','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('CategoriaCoheficiente.id'); ?>
<?php echo $this->Form->hidden('CategoriaCoheficiente.categoria_id'); ?>



<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default'=>$selectedCategoriasID, 'disabled' => 'disabled'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.dia',array('empty' => 'Seleccionar...','label' => 'Dia', 'options' => $dias)); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficiente.coheficiente',array('type'=>'number','value'=>$coheficiente));?></div>
</div>




<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar_coheficiente.json', true);?>',$('form').serialize(),{id:'w_coheficientes_categorias',url:'v2/categorias/index_coheficientes'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonGuardarError" class="boton guardar" style="display:none">Procesando...</span>
<span id="botonEliminar" onclick="eliminar();" class="boton guardar">Eliminar</span>
<?php echo $this->Form->end(); ?>

<script>
function eliminar(){
	
    if(confirm('Seguro desea eliminar la asociacion?')){
    	var id = $('#CategoriaCoheficienteId').val();
    	
        $.ajax({
            url : '<?php echo $this->Html->url('/categorias/eliminar_coheficiente', true);?>',
            type : 'POST',
            dataType: 'json',
            data: {'id' : id},
            success : function(data){
            	
               window.parent.dhxWins.window('w_coheficientes_categorias').attachURL('<?php echo $this->Html->url('/categorias/index_coheficientes', true);?>');
	    		window.parent.dhxWins.window('w_categoria_coheficiente_update').close();
            }
        });
    }
    
}


</script>