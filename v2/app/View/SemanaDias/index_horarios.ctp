
<ul class="action_bar">
	
	<li onclick="ver_horarios()" class="boton abonar">Carga de Horarios</li>
	<li onclick="ver_feriados()" class="boton abonar">Carga de restricciones</li>
	
</ul>

       
        
    
 
<div id="informe_operaciones"></div>
<script>
function ver_horarios(){
		
	    $.ajax({
	        url: '<?php echo $this->Html->url('/semana_dias/index', true);?>',
	        dataType: 'html',
	        success: function(data){
	            
	            $('#informe_operaciones').html(data);
	        }
	    })
	
    
}

function ver_feriados(){
		
	    $.ajax({
	        url: '<?php echo $this->Html->url('/feriados/index', true);?>',
	        dataType: 'html',
	        success: function(data){
	            
	            $('#informe_operaciones').html(data);
	        }
	    })
	
    
}

function editarFeriado(){
    var row = $("#dataTableFeriado tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        var data = oTable.fnGetData(row[0]);
        
        createWindow("w_feriados_view","Editar restriccion","<?php echo $this->Html->url('/feriados/editar', true);?>/"+data[0],"420","300");
        
    }
}

function eliminar(){
	
	var row = $("#dataTableFeriado tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        if(confirm('Seguro desea eliminar la restriccion?')){
	    	var id = oTable.fnGetData(row[0]);
	    	
	        $.ajax({
	            url : '<?php echo $this->Html->url('/feriados/eliminar', true);?>',
	            type : 'POST',
	            dataType: 'json',
	            data: {'id' : id[0]},
	            success : function(data){
	            	
	               window.parent.dhxWins.window('w_semana_dias').attachURL('<?php echo $this->Html->url('/semana_dias/index_horarios', true);?>');
		    		
	            }
	        });
	    }
    }
	
	
    
    
}

function eliminarHorario(){
	
	var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        if(confirm('Seguro desea eliminar el horario?')){
	    	var id = oTable.fnGetData(row[0]);
	    	
	        $.ajax({
	            url : '<?php echo $this->Html->url('/semana_dias/eliminar', true);?>',
	            type : 'POST',
	            dataType: 'json',
	            data: {'id' : id[0]},
	            success : function(data){
	            	
	               window.parent.dhxWins.window('w_semana_dias').attachURL('<?php echo $this->Html->url('/semana_dias/index_horarios', true);?>');
		    		
	            }
	        });
	    }
    }
	
	
    
    
}

<?php
switch ($_SESSION['paginaHorarios']) {
 	case 1:?>
 		ver_horarios(); 
 	<?php break;
 	case 2:?>
 		ver_feriados(); 
 	<?php break;
 	}
?>

</script>
