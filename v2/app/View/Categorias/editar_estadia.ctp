<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

//formulario
echo $this->Form->create(null, array('url' => '/categorias/editar_estadia','inputDefaults' => (array('div' => 'ym-gbox'))));

?>

<div class="ym-grid">
    
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaEstadia.fechaDesde',array('label'=>'Desde','class'=>'datepicker','type'=>'text','default'=>$desde));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaEstadia.fechaHasta',array('label'=>'Hasta','class'=>'datepicker','type'=>'text','default'=>$hasta));?></div>
</div>


<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaEstadia.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default'=>$selectedCategoriasID));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaEstadia.dias',array('type'=>'integer','value'=>$dias));?></div>
</div>







<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar_estadia.json', true);?>',$('form').serialize(),{id:'w_estadia_categorias',url:'v2/categorias/index_estadia'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonGuardarError" class="boton guardar" style="display:none">Procesando...</span>
<!--<span id="botonEliminar" onclick="eliminar();" class="boton guardar">Eliminar</span>-->
<?php echo $this->Form->end(); ?>

<script>
function eliminar(){
	
    if(confirm('Seguro desea eliminar la estadia?')){
    	
    	
        $.ajax({
            url : '<?php echo $this->Html->url('/categorias/eliminar_estadia', true);?>',
            type : 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success : function(data){
            	
               window.parent.dhxWins.window('w_estadia_categorias').attachURL('<?php echo $this->Html->url('/categorias/index_estadia', true);?>');
	    		window.parent.dhxWins.window('w_categorias_estadias_view').close();
            }
        });
    }
    
}


</script>