<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');


//formulario
echo $this->Form->create(null, array('url' => '/neumaticos/editar','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('Neumatico.km_unidad_aux',array('value'=>$defaultKm));
echo $this->Form->hidden('Neumatico.id');
echo $this->Form->hidden('Neumatico.identificador');
?>



<div class="ym-grid">
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.fecha',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g40 ym-gl"><?php echo $this->Form->input('Neumatico.marca',array('label'=>'Marca'));?></div>
    <div class="ym-g40 ym-gl"><?php echo $this->Form->input('Neumatico.modelo',array('label'=>'Modelo'));?></div>


</div>
<div class="ym-grid">
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.medida',array('label'=>'Medida','maxlength'=>'10'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.fabricacion',array('label'=>'Fabricación (DOT)','maxlength'=>'4','type'=>'number','oninput' => 'this.value = this.value.slice(0, 4)'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.posicion',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $posiciones));?></div>

</div>
<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.temporada',array('label'=>'Temporada','empty' => 'Seleccionar', 'type'=>'select', 'options' => $temporadas));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.dibujo',array('label'=>'Medida MM Dibujo','maxlength'=>'2','type'=>'number','oninput' => 'this.value = this.value.slice(0, 2)'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.estado',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $estados));?></div>

</div>

<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultCategoria));?></div>
    <div class="ym-g40 ym-gl"><?php echo $this->Form->input('Neumatico.unidad_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultUnidad));?></div>
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.km_unidad');?></div>
</div>




<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/neumaticos/guardar.json', true);?>',$('form').serialize(),{id:'w_neumaticos',url:'v2/neumaticos/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>
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
                    $('#NeumaticoKmUnidad').val(response.km);
                    $('#NeumaticoKmUnidadAux').val(response.km);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });




</script>
