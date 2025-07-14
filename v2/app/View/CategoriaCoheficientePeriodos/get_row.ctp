<?php
$i = rand(100,10000);
if(isset($categoria_coheficiente_periodo_id)){ ?>
    <tr class="border_bottom" id="CategoriaCoheficientePeriodo<?php echo $categoria_coheficiente_periodo_id?>">
<?php }else{ ?>
    <tr class="border_bottom" id="Extra<?php echo $i?>">
<?php } ?>
    <td width="25%">
        <input type="hidden" name="data[CategoriaCoheficientePeriodoCounter][]" value="<?php echo $i?>"/>
        
        <input type="hidden" name="data[CategoriaCoheficientePeriodoDesde][]" value="<?php echo $desde;?>"/>
        <input type="hidden" name="data[CategoriaCoheficientePeriodoHasta][]" value="<?php echo $hasta;?>"/>
        <?php echo $desde;?>
    </td>
    <td><?php echo $hasta;?></td>
    
    
<?php if(isset($categoria_coheficiente_periodo_id)){ ?>
    <td align="right" width="50"><a onclick=" quitarExtra('<?php echo $categoria_coheficiente_periodo_id;?>');">quitar</a></td>
<?php }else{ ?>
    <td align="right" width="50"><a onclick="$('#Extra<?php echo $i?>').remove();">quitar</a></td>
<?php } ?>
</tr>
