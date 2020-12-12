 <?php
 if(!$pdf){
 	echo '<ul class="action_bar">
   
    <li class="boton pdf"><a onclick="imprimir();">Imprimir</a></li>
    
	';?>
	<li class="boton agregar"><a onclick="createWindow('w_categoria_coheficiente_add','Crear Coheficiente','<?php echo $this->Html->url('/categorias/crear_coheficiente', true);?>','450','150');">Crear Asociacion</a></li>
</ul>
<?php
 }
 ?>
 
    <table width="100%" cellspacing="0" id="EditTable" style="table-layout:fixed;"> 
     <tbody>
     <col width="200px" />
   	
   
   
    <tr class="titulo">
    	
        <td bgcolor= "#989797" style="border: 1px solid black;">Categoria</td>
        <?php 
        if(count($diasMostrados) > 0){
	        foreach($diasMostrados as $dia => $coheficiente){
	        	
	        	echo '<td bgcolor= "#0066cc" style="border: 1px solid black;"><font color="#fff">'.$dia.'</font></td>';
	        }
        }
        ?>
    </tr>
    
    <?php foreach($reservas as $reserva){?> 
    	<tr>
    	<td bgcolor= "#989797" style="border: 1px solid black;"><?php echo $reserva['categoria'];?></td>
    	
        <?php 
        	
        	
        	foreach($reserva['diasMostrar'][$reserva['id']] as $key => $dd){
        			$diaID = explode("-", $dd['dia']);
        		
        ?> 
	         				
	         				<td id="<?php echo $diaID[1];?>" style="border: 1px solid black;"><?php echo $diaID[0];?></td>
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
	
    	document.location = "<?php echo $this->Html->url('/categorias/index_coheficientes', true);?>/1";
    
    
}
$('tr').mouseover(function(){
    $('tr').removeClass('hover');
    $(this).addClass('hover');
});
<?php
//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_coheficientes_categorias").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
$('#EditTable td').dblclick(function () {
     var id = $(this).attr('id');
     
     
     if(id){
     	createWindow("w_categoria_coheficiente_update","Editar Coheficiente","<?php echo $this->Html->url('/categorias/editar_coheficiente', true);?>/"+id,"450","200");
     }
});



</script>