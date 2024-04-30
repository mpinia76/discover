<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

//echo $neumatico['Neumatico']['dibujo'];
//formulario
echo $this->Form->create(null, array('url' => '/neumaticos/cambiar','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('ids', array('value' => $ids));
echo $this->Form->hidden('NeumaticoEstado.km_unidad_aux', array('value' => $km));
echo $this->Form->hidden('Neumatico.fecha_aux', array('value' => $fecha));
?>

<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultCategoria, 'disabled' => $disabled));?></div>
    <div class="ym-g40 ym-gl"><?php echo $this->Form->input('Neumatico.unidad_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultUnidad, 'disabled' => $disabled));?></div>
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('NeumaticoEstado.km_unidad',array('label'=>'KM','value' => $km));?></div>
</div>

<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.fecha',array('class'=>'datepicker','type'=>'text'));?></div>

    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.dibujo',array('label'=>'Medida MM Dibujo','value' => $dibujo,'maxlength'=>'2','type'=>'number','oninput' => 'this.value = this.value.slice(0, 2)'));?></div>

</div>






<span id="botonGuardar" onclick="guardarCerrando('<?php echo $this->Html->url('/neumaticos/guardarCambio.json', true);?>',$('form').serialize(),{id:'w_neumaticos',url:'v2/neumaticos/index'},'w_neumaticos_estado');" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
    $('#UnidadCategoriaId').change(function(){
        if($(this).val()!=''){
            $.ajax({
                url: '<?php echo $this->Html->url('/reservas/getUnidads/', true);?>'+$(this).val(),
                dataType: 'html',

                success: function(data){
                    $('#NeumaticoUnidadId').html(data);
                }
            });
        }else{
            $('#NeumaticoUnidadId').html('');
        }
    })
    $('#NeumaticoUnidadId').change(function(){
        var unidad_id = $(this).val();
        if(unidad_id !== '') {
            $.ajax({
                url: '<?php echo $this->Html->url('/unidads/getKm/', true);?>'+unidad_id,

                type: 'GET',

                success: function(response) {
                    $('#NeumaticoEstadoKmUnidad').val(response.km);
                    $('#NeumaticoEstadoKmUnidadAux').val(response.km);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });



</script>
