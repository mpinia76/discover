<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

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

<!-- periodos -->
<div id="divPeriodos">
    <div class="sectionSubtitle">Períodos</div>
    <div class="ym-grid">
        <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficientePeriodo.desde',array('class'=>'datepicker','type'=>'text'));?></div>
        <div class="ym-g33 ym-gl"><?php echo $this->Form->input('CategoriaCoheficientePeriodo.hasta',array('class'=>'datepicker','type'=>'text'));?></div>
        <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px;"><span onclick="addPeriodo();" class="boton agregar">+ agregar</span></div></div>
    </div>
    <table width="100%" id="categoria_coheficiente_periodos">

        <?php $i = 0;

        if(count($categoriaCoheficientePeriodos) > 0){
            foreach($categoriaCoheficientePeriodos as $categoriaCoheficientePeriodo){
                //print_r($categoriaCoheficientePeriodo);
                ?>

                <tr class="border_bottom" id="Extra<?php echo $i;?>">
                    <td width="25%">


                        <input type="hidden" name="data[CategoriaCoheficientePeriodoDesde][]" value="<?php echo $categoriaCoheficientePeriodo['CategoriaCoheficientePeriodo']['desde'];?>"/>
                        <input type="hidden" name="data[CategoriaCoheficientePeriodoHasta][]" value="<?php echo $categoriaCoheficientePeriodo['CategoriaCoheficientePeriodo']['hasta'];?>"/>
                        <?php echo $categoriaCoheficientePeriodo['CategoriaCoheficientePeriodo']['desde']?>
                    </td>

                    <td><?php echo $categoriaCoheficientePeriodo['CategoriaCoheficientePeriodo']['hasta']?></td>
                    <td align="right" width="50"><a onclick="quitarExtra('<?php echo $categoriaCoheficientePeriodo['CategoriaCoheficientePeriodo']['id']?>', <?php echo $i?>);">quitar</a></td>
                </tr>
            <?php  }} ?>
    </table>


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

function addPeriodo() {
    var desde = $('#CategoriaCoheficientePeriodoDesde').val();
    var hasta = $('#CategoriaCoheficientePeriodoHasta').val();

    if (!desde || !hasta) {
        alert("Debes completar ambas fechas: Desde y Hasta.");
        return;
    }

    // Convertir fechas de dd/mm/yyyy a objetos Date
    var partesDesde = desde.split('/');
    var partesHasta = hasta.split('/');

    var fechaDesde = new Date(partesDesde[2], partesDesde[1] - 1, partesDesde[0]); // Año, Mes (0-11), Día
    var fechaHasta = new Date(partesHasta[2], partesHasta[1] - 1, partesHasta[0]);

    if (fechaDesde > fechaHasta) {
        alert("La fecha 'Desde' no puede ser posterior a la fecha 'Hasta'.");
        return;
    }

    $.ajax({
        url: '<?php echo $this->Html->url('/categoria_coheficiente_periodos/getRow', true);?>',
        data: {
            'desde': desde,
            'hasta': hasta
        },
        success: function (data) {
            $('#categoria_coheficiente_periodos').append(data);
        },
        dataType: 'html'
    });
}

function quitarExtra(reserva_extra_id, item){

    if(confirm('Seguro desea eliminar el periodo?')){
        $('#Extra'+item).remove();
    }
}

</script>