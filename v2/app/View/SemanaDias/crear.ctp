<?php
//agregar el calendario
$this->Js->buffer('
var dtA = new Date(\'8/24/2009 9:00:00\');
$("#DiaHorarioHoraInicio").wickedpicker(
{now: dtA.getHours() + \':\' + dtA.getMinutes(),
twentyFour: true}
);');

$this->Js->buffer('
var dtA = new Date(\'8/24/2009 20:00:00\');
$("#DiaHorarioHoraFin").wickedpicker(
{now: dtA.getHours() + \':\' + dtA.getMinutes(),
twentyFour: true}
);');

//formulario
echo $this->Form->create(null, array('url' => '/semana_dias/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>



<div class="ym-grid">
	<div class="ym-g50 ym-gl"><?php echo $this->Form->input('SemanaDia.dia',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $dias));?></div>
    
</div>

<!-- horarios -->
<div class="sectionSubtitle">Horarios</div>
<div class="ym-grid">
    
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DiaHorario.hora_inicio',array('label'=>'Inicio (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DiaHorario.hora_fin',array('label'=>'Fin (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px;"><span onclick="addExtra();" class="boton agregar">+ agregar</span></div></div>
</div>
<table width="100%" id="reserva_extras"></table>

<!-- fin horarios -->


<span onclick="guardar('guardar.json',$('form').serialize(),{id:'w_semana_dias',url:'v2/semana_dias/index_horarios'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
function addExtra(){
    	
        $.ajax({
          url: '<?php echo $this->Html->url('/dia_horarios/getRow', true);?>',
          data: {'hora_inicio' : $('#DiaHorarioHoraInicio').val(), 'hora_fin' : $('#DiaHorarioHoraFin').val()},
          success: function(data){
              $('#reserva_extras').append(data);
              
          },
          dataType: 'html'
        });
   
}
</script>