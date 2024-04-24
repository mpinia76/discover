<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');


//formulario
echo $this->Form->create(null, array('url' => '/alertas/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>



<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Alerta.alerta');?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Alerta.corta',array('label'=>'Descripcion'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Alerta.tipo',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $tipos));?></div>


</div>

<div class="ym-grid">
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.nivel',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $niveles));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.unidad',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $unidades));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.magnitud',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $magnitudes));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.segmento',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $segmentos));?></div>

</div>

<div class="ym-grid">
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.controla',array('type'=>'number', 'style'=>'width:60px;'));?></div>
    <div class="ym-g25 ym-gl" id="divIniNum"><?php echo $this->Form->input('Alerta.inicio_num',array('label'=>'Inicio','type'=>'number', 'style'=>'width:60px;'));?></div>
    <div class="ym-g25 ym-gl" id="divIniFecha" style="display:none"><?php echo $this->Form->input('Alerta.inicio_fecha',array('label'=>'Inicio','class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.fin_num',array('label'=>'Fin', 'type'=>'number', 'style'=>'width:60px;'));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Alerta.recordatorio',array('type'=>'number', 'style'=>'width:60px;'));?></div>
</div>

<div class="ym-grid">
    <?php echo $this->Form->input('Alerta.descripcion',array('label' => false, 'type' => 'textarea')); ?>
</div>



<span onclick="guardar('guardar.json',$('form').serialize(),{id:'w_alertas',url:'v2/alertas/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
    $('#AlertaUnidad').change(function(){


        switch ($(this).val()) {
            case 'KM':
                $('#AlertaMagnitud').val('');
                $('#AlertaMagnitud').prop('disabled', true);
                $('#divIniNum').show();
                $('#divIniFecha').hide();
                break;
            case 'Tiempo':
                $('#AlertaMagnitud').prop('disabled', false);
                $('#divIniNum').hide();
                $('#divIniFecha').show();
                break;
            case 'Reservas':
                $('#AlertaMagnitud').val('');
                $('#AlertaMagnitud').prop('disabled', true);
                $('#divIniNum').show();
                $('#divIniFecha').hide();
                break;

        }

    })

    $('#AlertaTipo').change(function(){

        switch ($(this).val()) {
            case 'Flota':
                $('#AlertaInicioNum').val('');
                $('#AlertaInicioNum').prop('disabled', true);
                $('#AlertaInicioFecha').val('');
                $('#AlertaInicioFecha').prop('disabled', true);
                $('#AlertaFinNum').val('');
                $('#AlertaFinNum').prop('disabled', true);
                break;
            case 'General':
                $('#AlertaInicioNum').prop('disabled', false);
                $('#AlertaInicioFecha').prop('disabled', false);
                $('#AlertaFinNum').prop('disabled', false);
                break;


        }

    })
</script>
