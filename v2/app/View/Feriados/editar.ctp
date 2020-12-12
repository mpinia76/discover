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
<?php echo $this->Form->hidden('Feriado.id'); ?>


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
<table width="100%" id="reserva_extras">
        <?php $i = 0;
           
            if(count($feriadoHorarios) > 0){ 
                foreach($feriadoHorarios as $feriadoHorario){ 
                	//print_r($feriadoHorario);
                ?>
                    
                    <tr class="border_bottom" id="Extra<?php echo $i;?>">
                        <td width="25%">
                            
        
					        <input type="hidden" name="data[FeriadoHorarioHoraInicio][]" value="<?php echo $feriadoHorario[FeriadoHorario]['hora_inicio'];?>"/>
					        <input type="hidden" name="data[FeriadoHorarioHoraFin][]" value="<?php echo $feriadoHorario[FeriadoHorario]['hora_fin'];?>"/>
					        <?php echo $feriadoHorario['FeriadoHorario']['hora_inicio']?>
                        </td>
                        
                        <td><?php echo $feriadoHorario['FeriadoHorario']['hora_fin']?></td>
                        <td align="right" width="50"><a onclick="quitarExtra('<?php echo $extra['ReservaExtra']['id']?>', <?php echo $i?>);">quitar</a></td>
                    </tr>
        <?php  }} ?>
</table>
</div>
<!-- fin horarios -->


<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/feriados/guardar.json', true);?>',$('form').serialize(),{id:'w_semana_dias',url:'v2/semana_dias/index_horarios'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span><?php echo $this->Form->end(); ?>

<script>
    $(document).ready(function(){  
      
        if($("#FeriadoAbre").is(':checked')) {  
            $('#divHorarios').show();
        } else {  
            $('#divHorarios').hide();
        }  
      
    });  

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

function quitarExtra(reserva_extra_id, item){
    
    if(confirm('Seguro desea eliminar el horario?')){
       $('#Extra'+item).remove();
    }
}
</script>