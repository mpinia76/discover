 <?php
 if(!$pdf){
 	echo '<ul class="action_bar">
   
    <li class="boton pdf"><a onclick="imprimir();">Imprimir</a></li>
	<li class="boton excel"><a onclick="descargar();">Excel</a></li>
</ul>';
 }
 ?>
 
    <table width="100%" cellspacing="0" id="EditTable">
     <?php
 		if($pdf){?>
    		<tr class="titulo">
        		<td colspan='10' align='center' style='border: 1px solid black;'><?php echo utf8_encode('Planificación de Entregas y devoluciones período '.date("d/m/Y",strtotime($_SESSION['primerDia'])).' - '.date("d/m/Y",strtotime($_SESSION['ultimoDia'])).' Fecha Informe: '.date("d/m/Y H:i"));?></td>
        
    		</tr>
    <?php } ?>		
    <tr class="titulo">
        <td style='border: 1px solid black;'>ENT O DEV</td>
        <td style='border: 1px solid black;'>HORARIO</td>
        <td style='border: 1px solid black;'>LUGAR</td>
        
        <td style='border: 1px solid black;'>TITULAR</td>
        <td style='border: 1px solid black;'><?php echo utf8_encode('N° VUELO')?></td>
        <td style='border: 1px solid black;'>CATEGORIA</td>
        <td style='border: 1px solid black;'><?php echo utf8_encode('VEHÍCULO')?></td>
        <td style='border: 1px solid black;'>OBS</td>
        <td style='border: 1px solid black;'>PATENTE</td>
        <td style='border: 1px solid black;'>RESPONSABLE</td>
    </tr>
    <?php 
    
    foreach($reservas as $reservaDia){?>
    	<tr style="font-weight: bold;">
	        <td colspan='10' align='center' style='border: 1px solid black;background-color: #a4a6a6;'><?php echo utf8_encode($reservaDia[0]['dia']);?></td>
	        
	    </tr>
    	<?php foreach($reservaDia as $reserva){?> 
    	
    	
	    	<tr id='<?php echo $reserva['id_reserva'];?>'>
	    	<td style='border: 1px solid black;'><?php echo utf8_encode($reserva['tipo']);?></td>
	        <td style='border: 1px solid black;'><?php echo $reserva['hora'];?></td>
	        <td style='border: 1px solid black;'><?php echo ($reserva['lugar']);?></td>
	       
	        
	        <td style='border: 1px solid black;'><?php echo ($reserva['titular']);?></td>
	        <td style='border: 1px solid black;'><?php echo $reserva['vuelo'];?></td>
	        <td style='border: 1px solid black;'><?php echo ($reserva['categoria']);?></td>
	        <td style='border: 1px solid black;'><?php echo ($reserva['vehiculo']);?></td>
	        <td style='border: 1px solid black;'><?php echo ($reserva['obs']);?></td>
	        <td style='border: 1px solid black;'><?php echo $reserva['patente'];?></td>
	        
	        <?php
 			if(!$pdf){	?> 
	          <td style='border: 1px solid black;'>
	          	<select id="selectResponsable_<?php echo $reserva['id_reserva'];?>" onChange="seleccionarResponsable('<?php echo $reserva['tipo'];?>','<?php echo $reserva['id_reserva'];?>',this)">
				<option value="0">Seleccionar...</option>
	          	<?php foreach($empleados as $key => $value){
	          		$selected = ((($reserva['responsableRetiro']==$key)&&(utf8_encode($reserva['tipo'])=='Entrega'))||(($reserva['responsableDevolucion']==$key)&&(($reserva['tipo'])=='Devolución')))?"selected='selected'":"";
	          	?>
	          		<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
	          	<?php }?>
	          	</select> 
	          </td>
	         <?php } 
	         else{
	         ?>
	         <td style='border: 1px solid black;'>
	         	<?php foreach($empleados as $key => $value){
	          		if((($reserva['responsableRetiro']==$key)&&(utf8_encode($reserva['tipo'])=='Entrega'))||(($reserva['responsableDevolucion']==$key)&&(($reserva['tipo'])=='Devolución'))){
	          			echo $value;
	          		}
	          		}
	          	?>
	         
	         
	         </td>
	         <?php } 
	         
	         ?>
	    	</tr>
    	
	    <?php //print_r($reserva);
	    } 
    }
 		if($pdf){?>
    		<tr class="titulo">
        		<td colspan='10' style='border: 1px solid black;color: #fb061c;'><?php echo utf8_encode('* Tenga presente que este informe es parcial y no incluye reservas cargadas en el sistema posteriormente a la hora en la que se emitió este informe.');?></td>
        
    		</tr>
    <?php } ?>	    
</table>

<script>
function imprimir(){
	var strDesde = $('#desde').val().split("/"); 
	var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	var strHasta = $('#hasta').val().split("/"); 
	var hasta = strHasta[2]+'-'+strHasta[1]+'-'+strHasta[0];	
	
	if ((desde!='undefined-undefined-')&&(hasta!='undefined-undefined-')) {
    	document.location = "<?php echo $this->Html->url('/informes/ventas_semanal', true);?>/"+desde+"/"+hasta+"/1";
    	}
    
}
<?php
//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_ventas_informe_operaciones").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
function descargar(){
		var strDesde = $('#desde').val().split("/"); 
		var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
		var strHasta = $('#hasta').val().split("/"); 
		var hasta = strHasta[2]+'-'+strHasta[1]+'-'+strHasta[0];	
		
		if ((desde!='undefined-undefined-')&&(hasta!='undefined-undefined-')) {
	   
		    createWindow('w_ventas_informe_operaciones_descargar','Descargar','<?php echo $this->Html->url('/informes/exportarOperacionesSemanal', true);?>/'+desde+'/'+hasta,'430','300');
		    setTimeout('dhxWins.window("w_ventas_informe_operaciones_descargar").close()', 2000);
	    }
	
}
$('#EditTable tr').dblclick(function () {
     var id = $(this).attr('id');
     if(id){
     	createWindow("w_reservas_view","Ver reserva","<?php echo $this->Html->url('/reservas/editar', true);?>/"+id+"/2","630","600");
     }
});

function seleccionarResponsable(tipo,id_reserva,select){

	var id_responsable = $('#'+select.id).val(); 
	
		
	
	
		
	    $.ajax({
	        url: '<?php echo $this->Html->url('/reservas/guardar_responsable', true);?>',
	        type : 'POST',
            dataType: 'json',
            data: {'id_reserva' : id_reserva,'tipo' : tipo,'id_responsable' : id_responsable}
	    })
	
    
}

</script>