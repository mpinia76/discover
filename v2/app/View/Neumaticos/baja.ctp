<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

//echo $neumatico['Neumatico']['dibujo'];
//formulario
//echo $this->Form->create(null, array('url' => '/neumaticos/baja','inputDefaults' => (array('div' => 'ym-gbox'))));

echo $this->Form->create(null, array(
    'url' => '/neumaticos/baja',
    'inputDefaults' => array('div' => 'ym-gbox'),
    'type' => 'file' // Establece el tipo de formulario a 'file' para permitir el envío de archivos
));
echo $this->Form->hidden('Neumatico.id', array('value' => $id));
echo $this->Form->hidden('Neumatico.fecha_aux', array('value' => $fecha));
echo $this->Form->hidden('NeumaticoEstado.km_unidad', array('value' => $km));
?>



<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.fecha',array('class'=>'datepicker','type'=>'text'));?></div>

    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('NeumaticoEstado.motivo',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $motivos));?></div>

</div>

<div class="ym-grid">
    <?php echo $this->Form->input('NeumaticoEstado.descripcion',array('type' => 'textarea')); ?>
</div>

<div class="ym-grid">
    <?php echo $this->Form->input('NeumaticoEstado.foto1', array('type' => 'file', 'label' => 'Foto 1')); ?>
    <?php echo $this->Form->input('NeumaticoEstado.foto2', array('type' => 'file', 'label' => 'Foto 2')); ?>
    <?php echo $this->Form->input('NeumaticoEstado.foto3', array('type' => 'file', 'label' => 'Foto 3')); ?>
</div>

<span onclick="guardarConFoto('<?php echo $this->Html->url('/neumaticos/guardarBaja.json', true);?>');" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

    // Función para enviar datos del formulario incluyendo archivos
    function guardarConFoto(url) {
        var form = $('form')[0]; // Obtener el formulario DOM
        var formData = new FormData(form); // Crear un objeto FormData y pasar el formulario DOM

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // No establecer contentType
            success: function(response) {
                // Manejar la respuesta del servidor aquí
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
            }
        });
    }



</script>

