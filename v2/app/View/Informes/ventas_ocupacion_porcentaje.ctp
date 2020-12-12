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
        <td width="90">Total</td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades activas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $unidad_total[$categoria['Categoria']['id']][$i];
        //$unidad_totales[$i]+=$unidad_total[$categoria['Categoria']['id']][$i];
       
        ?></td>
        <?php } ?>
        
    </tr>
    
    <tr class="contenido">
        <td class="mes">d&iacute;as con 100%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado[$categoria['Categoria']['id']][$i]['100'];
        //$porcentaje_ocupado_totales[$i]['100']+=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['100'];
        $total +=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['100']
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 75%-100%</td>
        <?php 
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado[$categoria['Categoria']['id']][$i]['75'];
        //$porcentaje_ocupado_totales[$i]['75']+=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['75'];
        $total +=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['75']
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 50%-75%</td>
        <?php 
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado[$categoria['Categoria']['id']][$i]['50'];
        //$porcentaje_ocupado_totales[$i]['50']+=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['50'];
        $total +=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['50']
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
     <tr class="contenido">
        <td class="mes">d&iacute;as entre 25%-50%</td>
        <?php 
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado[$categoria['Categoria']['id']][$i]['25'];
        //$porcentaje_ocupado_totales[$i]['25']+=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['25'];
        $total +=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['25']
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 0%-25%</td>
        <?php 
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado[$categoria['Categoria']['id']][$i]['0'];
        //$porcentaje_ocupado_totales[$i]['0']+=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['0'];
        $total +=$porcentaje_ocupado[$categoria['Categoria']['id']][$i]['0']
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <?php } ?>
    <tr class="titulo">
        <td colspan="14" style="background-color:green">Porcentaje de Ocupaci&oacute;n total</td>
       
    </tr>
    <tr class="titulo">
        <td width="150"><?php echo $ano?></td>
        <?php for($i=1; $i<=12; $i++){ ?>
        <td  width="90""><?php echo $meses[$i]?></td>
        <?php } ?>
        <td width="90">Total</td>
    </tr>
    <tr class="contenido">
        <td class="mes">Unidades activas</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $unidad_tot[$i];
        
        ?></td>
        <?php } ?>
        
    </tr>
    
    <tr class="contenido">
        <td class="mes">d&iacute;as con 100%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado_unidad[$i]['100'];
        
        $total +=$porcentaje_ocupado_unidad[$i]['100'];
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 75%-100%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado_unidad[$i]['75'];
        $total +=$porcentaje_ocupado_unidad[$i]['75'];
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 50%-75%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado_unidad[$i]['50'];
        $total +=$porcentaje_ocupado_unidad[$i]['50'];
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 25%-50%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado_unidad[$i]['25'];
        $total +=$porcentaje_ocupado_unidad[$i]['25'];
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
    <tr class="contenido">
        <td class="mes">d&iacute;as entre 0%-25%</td>
        <?php 
        $cant = 0;
        $total = 0;
        for($i=1; $i<=12; $i++){ ?>
        <td><?php echo $porcentaje_ocupado_unidad[$i]['0'];
        $total +=$porcentaje_ocupado_unidad[$i]['0'];
        ?></td>
        <?php } ?>
       <td><?php echo $total;?></td>
        
    </tr>
</table>



