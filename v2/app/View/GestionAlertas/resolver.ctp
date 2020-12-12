<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');


//formulario
echo $this->Form->create(null, array('url' => '/gestion_alertas/resolver','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('GestionAlerta.id');
?>



<div class="ym-grid">

    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('GestionAlerta.unidad',array('type'=>'text', 'value' => $unidad,'disabled'=>true));?></div>

</div>

<div class="ym-grid">
    <div class="ym-g50 ym-gl" id="divIniNum" style="display:<?php echo $mostrarIniNum; ?>"><?php echo $this->Form->input('GestionAlerta.inicio_num',array('label'=>'Limite','type'=>'number','disabled'=>true, 'value'=>$limite));?></div>
    <div class="ym-g50 ym-gl" id="divIniFecha" style="display:<?php echo $mostrarIniFecha; ?>"><?php echo $this->Form->input('GestionAlerta.inicio_fecha',array('label'=>'Limite','type'=>'text','disabled'=>true,'value'=>$limite));?></div>
    <div class="ym-g50 ym-gl" id="divResNum" style="display:<?php echo $mostrarResNum; ?>"><?php echo $this->Form->input('GestionAlerta.km_resolucion',array('label'=>'KM Resolucion','type'=>'number', 'value'=>$actual));?></div>
    <div class="ym-g50 ym-gl" id="divResFecha" style="display:<?php echo $mostrarResFecha; ?>"><?php echo $this->Form->input('GestionAlerta.fecha_resolucion',array('label'=>'Fecha resolucion','class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g50 ym-gl" id="divResReserva" style="display:<?php echo $mostrarResReserva; ?>"><?php echo $this->Form->input('GestionAlerta.resuelta',array('label'=>'Resuelta'));?></div>

</div>

<div class="ym-grid">
    <?php echo $this->Form->input('GestionAlerta.descripcion',array('label' => false, 'type' => 'textarea')); ?>
</div>



<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/gestion_alertas/guardarR.json', true);?>',$('form').serialize(),{id:'w_gestionalertas',url:'v2/gestion_alertas/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>

</script>
