 <?php
 if(!$pdf){
 	echo '<ul class="action_bar">
   
    <li class="boton pdf"><a onclick="imprimir();">Imprimir</a></li>

</ul>';
 }
 ?>
 
    <table width="100%" cellspacing="0">
     <tr style="font-weight: bold;">
        <td colspan='10' style='border: 1px solid black;'><?php echo $desde;?></td>
        
    </tr>
    <tr class="titulo">
        <td colspan='3' style='border: 1px solid black;'>Unidad</td>
        <td rowspan='2' style='border: 1px solid black;'>Estado</td>
        <td colspan='3' style='border: 1px solid black;'>Devolucion</td>
        <td colspan='3' style='border: 1px solid black;'>Entrega</td>
    </tr>
    <tr class="titulo">
        <td style='border: 1px solid black;'>Marca</td>
        <td style='border: 1px solid black;'>Modelo</td>
        <td style='border: 1px solid black;'>Patente</td>
        
        <td style='border: 1px solid black;'>Nombre</td>
        <td style='border: 1px solid black;'>Lugar</td>
        <td style='border: 1px solid black;'>Hora</td>
        <td style='border: 1px solid black;'>Nombre</td>
        <td style='border: 1px solid black;'>Lugar</td>
        <td style='border: 1px solid black;'>Hora</td>
    </tr>
    <?php foreach($reservas as $reserva){?> 
    	<tr>
    	<td style='border: 1px solid black;'><?php echo $reserva['marca'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['modelo'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['patente'];?></td>
        <td style='border: 1px solid black;'></td>
        
        <td style='border: 1px solid black;'><?php echo $reserva['nombre_apellido_devolucion'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['lugar_devolucion'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['hora_devolucion'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['nombre_apellido_retiro'];?></td>
        <td style='border: 1px solid black;'><?php echo $reserva['lugar_retiro'];?></td></td>
        <td style='border: 1px solid black;'><?php echo $reserva['hora_retiro'];?></td>
    	</tr>
    	
    <?php //print_r($reserva);
    } ?>
    
</table>

<script>
function imprimir(){
	var strDesde = $('#desde').val().split("/"); 
	var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	
	if ((desde!='undefined-undefined-')) {
    	document.location = "<?php echo $this->Html->url('/informes/ventas_diario', true);?>/"+desde+"/1";
    	}
    
}
$('tr').mouseover(function(){
    $('tr').removeClass('hover');
    $(this).addClass('hover');
});
$('.extras_totales').click(function(){
    $('.extras').toggle()
})
$('.adelantados_totales').click(function(){
    $('.extras_adelantados').toggle()
})
$('.no_adelantados_totales').click(function(){
    $('.extras_no_adelantados').toggle()
})
</script>