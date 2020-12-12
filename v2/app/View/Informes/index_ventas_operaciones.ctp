
<ul class="action_bar">
	
	<li onclick="ver_diario()" class="boton abonar">Listado diario de operaciones</li>
	<li onclick="ver_semanal()" class="boton abonar">Listado semanal de operaciones</li>
	
</ul>

       
        
    
 
<div id="informe_operaciones"></div>
<script>
function ver_diario(){
		
	    $.ajax({
	        url: '<?php echo $this->Html->url('/informes/index_ventas_diario', true);?>',
	        dataType: 'html',
	        success: function(data){
	            
	            $('#informe_operaciones').html(data);
	        }
	    })
	
    
}

function ver_semanal(){
		
	    $.ajax({
	        url: '<?php echo $this->Html->url('/informes/index_ventas_semanal', true);?>',
	        dataType: 'html',
	        success: function(data){
	            
	            $('#informe_operaciones').html(data);
	        }
	    })
	
    
}
<?php
/*switch ($_SESSION['paginaOperaciones']) {
 	case 1:?>
 		ver_diario(); 
 	<?php break;
 	case 2:?>
 		ver_semanal(); 
 	<?php break;
 	}*/
?>

</script>
