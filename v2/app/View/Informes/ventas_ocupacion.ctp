    <table width="100%" cellspacing="0">
    <tr class="titulo">
        <td width="150"><?php echo $ano?></td>
        <?php for($i=1; $i<=12; $i++){ ?>
        <td  width="90""><?php echo $meses[$i]?></td>
        <?php } ?>
        <td width="90">Promedio</td>
    </tr>
    
    
    <tr class="contenido">
        <td class="mes">Capacidad maxima</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_total[$i]?></td>
        <?php } ?>
        <td><?php echo $capacidad_total[$i]?></td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades ocupadas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_ocupada[$i]?></td>
        <?php 
        	if($capacidad_ocupada[$i]!=0){
        		$cant++;
        		$total += $capacidad_ocupada[$i];
        	}
        } ?>
        <td><?php echo round(($total/$cant),2);?></td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades disponibles</td>
        <?php 
         $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_total[$i] - $capacidad_ocupada[$i]?></td>
        <?php 
        	if(($capacidad_total[$i] - $capacidad_ocupada[$i])>0){
        		$cant++;
        		$total += ($capacidad_total[$i] - $capacidad_ocupada[$i]);
        	}
        } ?>
        <td><?php echo round(($total/$cant),2);?></td>
    </tr>
     <tr class="contenido">
        <td class="mes">Ocupacion %</td>
        <?php 
         $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo number_format(($capacidad_ocupada[$i] / $capacidad_total[$i])*100,2)?>%</td>
        <?php 
        	if((($capacidad_ocupada[$i] / $capacidad_total[$i])*100)>0){
        		$cant++;
        		$total += (($capacidad_ocupada[$i] / $capacidad_total[$i])*100);
        	}
        } ?>
        <td><?php echo round(($total/$cant),2);?>%</td>
    </tr>
    
</table>



