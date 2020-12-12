    <table width="100%" cellspacing="0">
    <?php 
    $unidad_totales=array();
    $capacidad_totales=array();
    $capacidad_ocupadas=array();
    $capacidad_totales=array();
    $ocupaciones=array();
    foreach($categorias as $categoria){ ?>
    <tr class="titulo">
        <td colspan="14" style="background-color:blue"><?php echo $categoria['Categoria']['categoria']?></td>
       
    </tr>
    <tr class="titulo">
        <td width="150"><?php echo $ano?></td>
        <?php for($i=1; $i<=12; $i++){ ?>
        <td  width="90""><?php echo $meses[$i]?></td>
        <?php } ?>
        <td width="90">Promedio</td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades activas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $unidad_total[$categoria['Categoria']['id']][$i];
        $unidad_totales[$i]+=$unidad_total[$categoria['Categoria']['id']][$i];
        ?></td>
        <?php } ?>
        <td><?php echo $unidad_total[$categoria['Categoria']['id']][$i]?></td>
    </tr>
    
    <tr class="contenido">
        <td class="mes">Capacidad maxima</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_total[$categoria['Categoria']['id']][$i];
        $capacidad_totales[$i]+=$capacidad_total[$categoria['Categoria']['id']][$i];
        ?></td>
        <?php } ?>
        <td><?php echo $capacidad_total[$categoria['Categoria']['id']][$i]?></td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades ocupadas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_ocupada[$categoria['Categoria']['id']][$i];
        $capacidad_ocupadas[$i]+=$capacidad_ocupada[$categoria['Categoria']['id']][$i];
        ?></td>
        <?php 
        	if($capacidad_ocupada[$categoria['Categoria']['id']][$i]!=0){
        		$cant++;
        		$total += $capacidad_ocupada[$categoria['Categoria']['id']][$i];
        		
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
        <td><?php echo $capacidad_total[$categoria['Categoria']['id']][$i] - $capacidad_ocupada[$categoria['Categoria']['id']][$i];
        ?></td>
        <?php 
        	if(($capacidad_total[$categoria['Categoria']['id']][$i] - $capacidad_ocupada[$categoria['Categoria']['id']][$i])>0){
        		$cant++;
        		$total += ($capacidad_total[$categoria['Categoria']['id']][$i] - $capacidad_ocupada[$categoria['Categoria']['id']][$i]);
        		
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
        <td><?php echo number_format(($capacidad_ocupada[$categoria['Categoria']['id']][$i] / $capacidad_total[$categoria['Categoria']['id']][$i])*100,2)?>%</td>
        <?php 
        	if((($capacidad_ocupada[$categoria['Categoria']['id']][$i] / $capacidad_total[$categoria['Categoria']['id']][$i])*100)>0){
        		$cant++;
        		$total += (($capacidad_ocupada[$categoria['Categoria']['id']][$i] / $capacidad_total[$categoria['Categoria']['id']][$i])*100);
        		
        	}
        } ?>
        <td><?php echo round(($total/$cant),2);?>%</td>
    </tr>
    <?php } ?>
    <tr class="titulo">
        <td colspan="14" style="background-color:green">Ocupaci&oacute;n total</td>
       
    </tr>
    <tr class="titulo">
        <td width="150"><?php echo $ano?></td>
        <?php for($i=1; $i<=12; $i++){ ?>
        <td  width="90""><?php echo $meses[$i]?></td>
        <?php } ?>
        <td width="90">Promedio</td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades activas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $unidad_totales[$i];
        
        ?></td>
        <?php } ?>
        <td></td>
    </tr>
    
    <tr class="contenido">
        <td class="mes">Capacidad maxima</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_totales[$i];
        
        ?></td>
        <?php } ?>
        <td></td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades ocupadas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $capacidad_ocupadas[$i]?></td>
        <?php 
        	if($capacidad_ocupadas[$i]!=0){
        		$cant++;
        		$total += $capacidad_ocupadas[$i];
        		
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
        <td><?php echo $capacidad_totales[$i] - $capacidad_ocupadas[$i]?></td>
        <?php 
        	if(($capacidad_totales[$i] - $capacidad_ocupadas[$i])>0){
        		$cant++;
        		$total += ($capacidad_totales[$i] - $capacidad_ocupadas[$i]);
        		
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
        <td><?php echo number_format(($capacidad_ocupadas[$i] / $capacidad_totales[$i])*100,2)?>%</td>
        <?php 
        	if((($capacidad_ocupadas[$i] / $capacidad_totales[$i])*100)>0){
        		$cant++;
        		$total += (($capacidad_ocupadas[$i] / $capacidad_totales[$i])*100);
        		
        	}
        } ?>
        <td><?php echo round(($total/$cant),2);?>%</td>
    </tr>
</table>



