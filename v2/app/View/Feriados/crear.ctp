<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');
//agregar el calendario
$this->Js->buffer('
var dtA = new Date(\'8/24/2009 9:00:00\');
$("#FeriadoHorarioHoraInicio").wickedpicker(
{now: dtA.getHours() + \':\' + dtA.getMinutes(),
twentyFour: true}
);');

$this->Js->buffer('
var dtA = new Date(\'8/24/2009 20:00:00\');
$("#FeriadoHorarioHoraFin").wickedpicker(
{now: dtA.getHours() + \':\' + dtA.getMinutes(),
twentyFour: true}
);');

//formulario
echo $this->Form->create(null, array('url' => '/feriados/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>



<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('Feriado.fecha',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g50 ym-gl" style="margin-top:20px;"><?php echo $this->Form->input('Feriado.abre');?></div>
</div>

<!-- horarios -->
<div id="divHorarios" style="display:none">
<div class="sectionSubtitle">Horarios</div>
<div class="ym-grid">
    
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('FeriadoHorario.hora_inicio',array('label'=>'Apertura (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('FeriadoHorario.hora_fin',array('label'=>'Cierre (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px;"><span onclick="addExtra();" class="boton agregar">+ agregar</span></div></div>
</div>
<table width="100%" id="reserva_extras"></table>
</div>
<!-- fin horarios -->


<span onclick="guardar('guardar.json',$('form').serialize(),{id:'w_semana_dias',url:'v2/semana_dias/index_horarios'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
$(function(){
	$('#FeriadoAbre').change(function(){
  	if(!$(this).prop('checked')){
    	$('#divHorarios').hide();
    }else{
    	$('#divHorarios').show();
    }
  
  })

})
function addExtra(){
    	
        $.ajax({
          url: '<?php echo $this->Html->url('/feriado_horarios/getRow', true);?>',
          data: {'hora_inicio' : $('#FeriadoHorarioHoraInicio').val(), 'hora_fin' : $('#FeriadoHorarioHoraFin').val()},
          success: function(data){
              $('#reserva_extras').append(data);
              
          },
          dataType: 'html'
        });
   
}
</script>