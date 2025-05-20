<?php
$i = rand(100,10000);
if(isset($descuento_periodo_id)){ ?>
    <tr class="border_bottom" id="DescuentoPeriodo<?php echo $descuento_periodo_id?>">
<?php }else{ ?>
    <tr class="border_bottom" id="Extra<?php echo $i?>">
<?php } ?>
    <td width="25%">
        <input type="hidden" name="data[DescuentoPeriodoCounter][]" value="<?php echo $i?>"/>
        
        <input type="hidden" name="data[DescuentoPeriodoDesde][]" value="<?php echo $desde;?>"/>
        <input type="hidden" name="data[DescuentoPeriodoHasta][]" value="<?php echo $hasta;?>"/>
        <?php echo $desde;?>
    </td>
    <td><?php echo $hasta;?></td>
    
    
<?php if(isset($descuento_periodo_id)){ ?>
    <td align="right" width="50"><a onclick=" quitarExtra('<?php echo $descuento_periodo_id;?>');">quitar</a></td>
<?php }else{ ?>
    <td align="right" width="50"><a onclick="$('#Extra<?php echo $i?>').remove();">quitar</a></td>
<?php } ?>
</tr>
