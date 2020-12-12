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
   	
    <?php 
    for ($i = 0; $i < (count($diasSemana)); $i++) {
		echo '<col width="60px" />';
	}
    ?>
    <tr bgcolor= "#989797" class="titulo">
        <td bgcolor= "#989797" rowspan="3" style="border: 1px solid black;">Categoria</td>
        <?php foreach($meses as $mes=>$diasCant){	
        	
        	echo "<td colspan='".($diasCant)."' style='border: 1px solid black;'>".$mes."</td>";
        }?>
    </tr>
    <tr class="titulo">
    	
        
        <?php foreach($diasSemana as $dia){
        	$diaSemana = explode("-", $dia);
        	echo '<td bgcolor= "#0066cc" style="border: 1px solid black;"><font color="#fff">'.$diaSemana[0].'</font></td>';
        }?>
    </tr>
    <tr class="titulo">
        
        <?php foreach($diasSemana as $dia){
        	$diaSemana = explode("-", $dia);
        	$bgcolor = (($diaSemana[0]=='Sabado')||($diaSemana[0]=='Domingo'))?'#0066cc':'#989797';
        	$color = (($diaSemana[0]=='Sabado')||($diaSemana[0]=='Domingo'))?'#fff':'#000';
        	echo '<td bgcolor= "'.$bgcolor.'" style="border: 1px solid black;"><font color="'.$color.'">'.$diaSemana[1].'</font></td>';
        }?>
    </tr>	
    <?php foreach($reservas as $reserva){?> 
    	<tr>
    	<td id="<?php echo $reserva['id'];?>" bgcolor= "#989797" style="border: 1px solid black;"><?php echo $reserva['categoria'];?></td>
    	
        <?php 
        	
        	
        	foreach($reserva['diasMostrar'][$reserva['id']] as $key => $dd){
        		
        ?> 
	         				
	         				<td id="<?php echo $reserva['id'].' '.$key.' '.$dd['fecha'];?>" style="border: 1px solid black;"><?php echo $dd['fecha'];?></td>
	         	<?php 	
	         				
	         			
	    	
	    	
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
    	document.location = "<?php echo $this->Html->url('/categorias/tarifas', true);?>/"+desde+"/"+hasta+"/1";
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
    position = dhxWins.window("w_tarifas_categorias").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
$('#EditTable td').dblclick(function () {
     var id = $(this).attr('id');
     varIdArray = id.split(" "); 
     var strDesde = $('#desde').val().split("/"); 
	 var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	
	 var strHasta = $('#hasta').val().split("/"); 
	 var hasta = strHasta[2]+'-'+strHasta[1]+'-'+strHasta[0];
	 var importe = '0';
     if(varIdArray[1]){
     	desde = varIdArray[1];
     	hasta = varIdArray[1];
     	if(varIdArray[2]!='Sin'){
     		importe = varIdArray[2];
     	}
     }
     
     if(id){
     	createWindow("w_categorias_tarifas_view","Tarifas","<?php echo $this->Html->url('/categorias/editar_tarifas', true);?>/"+varIdArray[0]+"/"+desde+"/"+hasta+"/"+importe,"450","200");
     }
});



</script>