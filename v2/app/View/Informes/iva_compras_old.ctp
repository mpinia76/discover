
<ul class="action_bar">
    <li class="boton excel"><a onclick="descargar();">Excel</a></li>
</ul>
<table width="100%" cellspacing="0">
    <tr class="titulo">
        <td>Fecha comprobante</td>
        <td>Factura</td>
        <td>Proveedor</td>
        <td>IVA 27%</td>
        <td>IVA 21%</td>
        <td>IVA 10.5%</td>
        <td>Otra al&iacute;cuota</td>
        <td>Monto bruto</td>
       
       
    </tr>
    <?php 
    $total27=0;
    $total21=0;
    $total10_5=0;
    $totalOtraAlicuota=0;
    $totalMonto=0;
    $creditoFiscal = 0;
    foreach($gastos as $gasto){
             $total27 +=$gasto['Gasto']['iva_27'];
             $total21 +=$gasto['Gasto']['iva_21'];
             $total10_5 +=$gasto['Gasto']['iva_10_5'];
             $totalOtraAlicuota +=$gasto['Gasto']['otra_alicuota'];
             $totalMonto +=$gasto['Gasto']['monto'];
             $facturaOrden = ($gasto['Gasto']['factura_orden']=='B')?'0001':'0002';
             $factura = $gasto['Gasto']['factura_tipo'].'-'.$facturaOrden.'-'.$gasto['Gasto']['factura_nro'];
             $creditoFiscal +=$gasto['Gasto']['iva_27']+$gasto['Gasto']['iva_21']+$gasto['Gasto']['iva_10_5']+$gasto['Gasto']['otra_alicuota'];
             ?>
    <tr class="contenido">
       
        
            <td><?php echo $gasto['Gasto']['fecha']; ?></td>
            <td><?php echo $factura; ?></td>
            <td align="left"><?php 
            if(isset($gasto['Proveedor']['id'])){
                $proveedor = $gasto['Proveedor']['nombre'];
            }else{
                $proveedor = $gasto['Gasto']['proveedor'];
            }
            
            
            echo $proveedor; ?></td>
            <td align="right"><?php echo number_format($gasto['Gasto']['iva_27'],2); ?></td>
            <td align="right"><?php echo number_format($gasto['Gasto']['iva_21'],2); ?></td>
            <td align="right"><?php echo number_format($gasto['Gasto']['iva_10_5'],2); ?></td>
            <td align="right"><?php echo number_format($gasto['Gasto']['otra_alicuota'],2); ?></td>
            <td align="right"><?php echo number_format($gasto['Gasto']['monto'],2); ?></td>
        
            
    </tr>
    <?php } ?>
    <tr class="contenido">
       
        
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        
            
    </tr>
    <tr class="contenido">
       
        
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        
            
    </tr>
    <tr class="titulo">
       
        
            <td></td>
            <td align="right">Total Cr&eacute;dito fiscal</td>
            <td align="right"><?php echo number_format($creditoFiscal,2); ?></td>
            <td align="right"><?php echo number_format($total27,2); ?></td>
            <td align="right"><?php echo number_format($total21,2); ?></td>
            <td align="right"><?php echo number_format($total10_5,2); ?></td>
            <td align="right"><?php echo number_format($totalOtraAlicuota,2); ?></td>
            <td align="right"><?php echo number_format($totalMonto,2); ?></td>
        
            
    </tr>
</table>
<script>
$('tr').mouseover(function(){
    $('tr').removeClass('hover');
    $(this).addClass('hover');
});
</script>