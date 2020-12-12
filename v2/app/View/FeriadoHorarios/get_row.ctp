<?php
$i = rand(100,10000);
if(isset($feriado_horario_id)){ ?>
    <tr class="border_bottom" id="FeriadoHorario<?php echo $feriado_horario_id?>">
<?php }else{ ?>
    <tr class="border_bottom" id="Extra<?php echo $i?>">
<?php } ?>
    <td width="25%">
        <input type="hidden" name="data[FeriadoHorarioCounter][]" value="<?php echo $i?>"/>
        
        <input type="hidden" name="data[FeriadoHorarioHoraInicio][]" value="<?php echo $hora_inicio;?>"/>
        <input type="hidden" name="data[FeriadoHorarioHoraFin][]" value="<?php echo $hora_fin;?>"/>
        <?php echo $hora_inicio;?>
    </td>
    <td><?php echo $hora_fin;?></td>
    
    
<?php if(isset($feriado_horario_id)){ ?>
    <td align="right" width="50"><a onclick=" quitarExtra('<?php echo $feriado_horario_id;?>');">quitar</a></td>
<?php }else{ ?>
    <td align="right" width="50"><a onclick="$('#Extra<?php echo $i?>').remove();">quitar</a></td>
<?php } ?>
</tr>
