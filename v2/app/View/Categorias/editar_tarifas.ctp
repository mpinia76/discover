<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

//formulario
echo $this->Form->create(null, array('url' => '/categorias/editar_tarifas','inputDefaults' => (array('div' => 'ym-gbox'))));

?>

<div class="ym-grid">
    
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaTarifa.fechaDesde',array('label'=>'Desde','class'=>'datepicker','type'=>'text','default'=>$desde));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaTarifa.fechaHasta',array('label'=>'Hasta','class'=>'datepicker','type'=>'text','default'=>$hasta));?></div>
</div>


<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaTarifa.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default'=>$selectedCategoriasID));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('CategoriaTarifa.importe',array('type'=>'number','value'=>$importe));?></div>
</div>







<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/categorias/guardar_tarifas.json', true);?>',$('form').serialize(),{id:'w_tarifas_categorias',url:'v2/categorias/index_tarifas'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonGuardarError" class="boton guardar" style="display:none">Procesando...</span>
<span id="botonEliminar" onclick="eliminar();" class="boton guardar">Eliminar</span>
<?php echo $this->Form->end(); ?>

<script>
function eliminar(){
	
    if(confirm('Seguro desea eliminar la tarifa?')){
    	
    	
        $.ajax({
            url : '<?php echo $this->Html->url('/categorias/eliminar_tarifa', true);?>',
            type : 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success : function(data){
            	
               window.parent.dhxWins.window('w_tarifas_categorias').attachURL('<?php echo $this->Html->url('/categorias/index_tarifas', true);?>');
	    		window.parent.dhxWins.window('w_categoria_tarifa_update').close();
            }
        });
    }
    
}


</script>