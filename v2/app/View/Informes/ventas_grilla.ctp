 <?php
 if(!$pdf){
 	echo '<ul class="action_bar">
   
    <li class="boton pdf"><a onclick="imprimir();">Imprimir</a></li>

</ul>';
 }
 ?>
 
    <table width="100%" cellspacing="0" id="EditTable" style="table-layout:fixed;"> 
     <tbody>
     <col width="70px" />
    <col width="80px" />
    <col width="80px" />
    <col width="60px" />
    <?php 
    for ($i = 0; $i < (count($diasSemana)*2); $i++) {
		echo '<col width="60px" />';
	}
    ?>
    <tr bgcolor= "#989797" class="titulo">
        <td colspan='4' style='border: 1px solid black;'>Unidad</td>
        <?php foreach($meses as $mes=>$diasCant){	
        	
        	echo "<td colspan='".($diasCant*2)."' style='border: 1px solid black;'>".$mes."</td>";
        }?>
    </tr>
    <tr class="titulo">
    	<td bgcolor= "#989797" rowspan="2" style="border: 1px solid black;">Categoria</td>
        <td bgcolor= "#989797" rowspan="2" style="border: 1px solid black;">Marca</td>
        <td bgcolor= "#989797" rowspan="2" style="border: 1px solid black;">Modelo</td>
        <td bgcolor= "#989797" rowspan="2" style="border: 1px solid black;">Patente</td>
        <?php foreach($diasSemana as $dia){
        	$diaSemana = explode("-", $dia);
        	echo '<td bgcolor= "#0066cc" colspan="2" style="border: 1px solid black;"><font color="#fff">'.$diaSemana[0].'</font></td>';
        }?>
    </tr>
    <tr class="titulo">
        
        <?php foreach($diasSemana as $dia){
        	$diaSemana = explode("-", $dia);
        	$bgcolor = (($diaSemana[0]=='Sabado')||($diaSemana[0]=='Domingo'))?'#0066cc':'#989797';
        	$color = (($diaSemana[0]=='Sabado')||($diaSemana[0]=='Domingo'))?'#fff':'#000';
        	echo '<td bgcolor= "'.$bgcolor.'" colspan="2" style="border: 1px solid black;"><font color="'.$color.'">'.$diaSemana[1].'</font></td>';
        }?>
    </tr>	
    <?php foreach($reservas as $reserva){
    	$patentesEspeciales = array('AC488VR','AB425OY','AA727XE','AA727XF','AA727XG','AC488VQ');
		if (in_array($reserva['patente'], $patentesEspeciales)) {
			$bold="font-weight:bold;";
		}else{
			$bold="";
		}
    ?> 
    	<tr>
    	<td bgcolor= "#989797" style="border: 1px solid black;<?php echo $bold;?>"><?php echo $reserva['categoria'];?></td>
    	<td bgcolor= "#989797" style="border: 1px solid black;<?php echo $bold;?>"><?php echo $reserva['marca'];?></td>
        <td bgcolor= "#989797" style="border: 1px solid black;<?php echo $bold;?>"><?php echo $reserva['modelo'];?></td>
        <td bgcolor= "#989797" style="border: 1px solid black;<?php echo $bold;?>"><?php echo $reserva['patente'];?></td>
        <?php 
        	
        	$colSpamAnt = 0;
        	$celdas = 0;
        	$agregarUna = 0;
        	foreach($reserva['diasMostrar'][$reserva['unidad']] as $dd){
        		$agregoOtra =0;
        		
        		$colSpamArr = explode("-", $dd['retiro']);
        		$bgcolor = ($colSpamArr[2])?'bgcolor= "'.$colSpamArr[2].'"':'';
        		if(($colSpamAnt == 0)||($colSpamArr[1]!=0)){
        			
        			if ($colSpamAnt != 2){
        				if($agregarUna){ 
	         				
	         				echo '<td style="border: 1px solid black;"></td>';
	         			}
        				$pri=1;
        				$celdas++;
        				
		    			if(($colSpamArr[1]*2)!=0){
		    				if(($celdas+($colSpamArr[1]*2))>(count($reserva['diasMostrar'][$reserva['unidad']]))*2){
		    					$colSpam = (count($reserva['diasMostrar'][$reserva['unidad']])*2)-$celdas;
		    				}
		    				else{
		    					$colSpam = ($colSpamArr[1]*2);
		    				}
		    			}
		    			else{
		    				if($colSpamArr[4]!='dev'){
		    					$colSpam=1;
		    				}
		    				else{
		    					
		    					$agregoOtra =1;
		    					
		    					
		    					$colSpam=0;
		    				}
		    			} 
	        			$celdas += $colSpam;
	        			
	        			if($colSpamArr[4]!='dev'){
        ?> 
	         				
	         				<td id="<?php echo $colSpamArr[3];?>" style="border: 1px solid black;"><?php echo $dd['devolucion'];?></td>
	         	<?php 	
	         				
	         			}
	         			else {
	         				
	         				$colSpam++;
	         			}
	         			 ?>
	         			<td id="<?php echo $colSpamArr[3];?>" colspan="<?php echo $colSpam;?>" <?php echo $bgcolor;?> style="border: 1px solid black;"><?php echo $colSpamArr[0];?></td>
	         	<?php   
	         			if($agregoOtra){ 
	         				
	         					echo '<td style="border: 1px solid black;"></td>';
	         				}
	         			$agregarUna = 0;
	         
	    			}
	    			else{
	    				$agregarUna = 1;
	    				//echo $colSpamAnt." => ".$dd['retiro']."<br>";
		    			$pri=0;
		    			//$celdas++;
		    			
		    			if(($colSpamArr[1]*2)!=0){
		    				if(($celdas+($colSpamArr[1]*2))>(count($reserva['diasMostrar'][$reserva['unidad']]))*2){
		    					$colSpam = (count($reserva['diasMostrar'][$reserva['unidad']])*2)-$celdas;
		    				}
		    				else{
		    					$colSpam = ($colSpamArr[1]*2);
		    				}
		    			}
		    			else{
		    				$colSpam=1;
		    			} 
	        			$celdas += $colSpam;
		    			?>
	    		
	         			<td id="<?php echo $colSpamArr[3];?>" colspan="<?php echo $colSpam;?>" <?php echo $bgcolor;?> style="border: 1px solid black;"><?php echo $colSpamArr[0];?></td>
	         			<?php 
	         			///if($celdas<(count($reserva['diasMostrar'][$reserva['unidad']]))*2){ ?>
	         				<!--<td id="<?php echo $colSpamArr[3];?>" style="border: 1px solid black;"><?php echo $dd['devolucion'];?></td>-->
	    <?php
	    				//}
	    			}
	    		}
	    		
		    	if(($colSpamArr[1]==0)){
		    		if($colSpamAnt != 0){
		    			$colSpamAnt -= 2;
		    			if (($pri)&&($colSpamAnt == 0)&&($colSpamArr[1]==0)){
		    				
		    				$celdas++;	
		    				 echo '<td style="border: 1px solid black;"></td>';
		    			}
		    		}
		    	}
		    	else{
		    		$colSpamAnt = ($colSpamArr[1]*2);
		    	}
	    	
	    	
	    	} 
	    ?>
    	</tr>
    	
    <?php //print_r($reserva);
    } ?>
    <tbody>
</table>

<script>
function imprimir(){
	var strDesde = $('#desde').val().split("/"); 
	var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	
	var strHasta = $('#hasta').val().split("/"); 
	var hasta = strHasta[2]+'-'+strHasta[1]+'-'+strHasta[0];	
	
	if ((desde!='undefined-undefined-')) {
    	document.location = "<?php echo $this->Html->url('/informes/ventas_grilla', true);?>/"+desde+"/"+hasta+"/1";
    	}
    
}
$('tr').mouseover(function(){
    $('tr').removeClass('hover');
    $(this).addClass('hover');
});
<?php
//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_ventas_grilla").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
$('#EditTable td').dblclick(function () {
     var id = $(this).attr('id');
     if(id){
     	createWindow("w_reservas_view","Ver reserva","<?php echo $this->Html->url('/reservas/editar', true);?>/"+id,"630","600");
     }
});



</script>