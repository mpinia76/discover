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
<?php echo $this->Form->hidden('SemanaDia.id'); ?>


<div class="ym-grid">
	
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('SemanaDia.dia',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $dias, 'default' => $defaultDia));?></div>
</div>

<!-- horarios -->
<div class="sectionSubtitle">Horarios</div>
<div class="ym-grid">
    
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DiaHorario.hora_inicio',array('label'=>'Inicio (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DiaHorario.hora_fin',array('label'=>'Fin (HH:MM)','type' => 'text', 'class' => 'number timepicker')); ?></div>
    <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px;"><span onclick="addExtra();" class="boton agregar">+ agregar</span></div></div>
</div>
<table width="100%" id="reserva_extras">
        <?php $i = 0;
           
            if(count($diaHorarios) > 0){ 
                foreach($diaHorarios as $diaHorario){ 
                	//print_r($diaHorario);
                ?>
                    
                    <tr class="border_bottom" id="Extra<?php echo $i;?>">
                        <td width="25%">
                            
        
					        <input type="hidden" name="data[DiaHorarioHoraInicio][]" value="<?php echo $diaHorario[DiaHorario]['hora_inicio'];?>"/>
					        <input type="hidden" name="data[DiaHorarioHoraFin][]" value="<?php echo $diaHorario[DiaHorario]['hora_fin'];?>"/>
					        <?php echo $diaHorario['DiaHorario']['hora_inicio']?>
                        </td>
                        
                        <td><?php echo $diaHorario['DiaHorario']['hora_fin']?></td>
                        <td align="right" width="50"><a onclick="quitarExtra('<?php echo $extra['ReservaExtra']['id']?>', <?php echo $i?>);">quitar</a></td>
                    </tr>
        <?php  }} ?>
</table>

<!-- fin horarios -->


<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/semana_dias/guardar.json', true);?>',$('form').serialize(),{id:'w_semana_dias',url:'v2/semana_dias/index_horarios'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span><?php echo $this->Form->end(); ?>

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

function quitarExtra(reserva_extra_id, item){
    
    if(confirm('Seguro desea eliminar el horario?')){
       $('#Extra'+item).remove();
    }
}
</script>