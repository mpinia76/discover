


        <strong>Fecha&nbsp;<input type="text" name="desde" id="desde" class="datepicker" value="<?php echo date('d/m/Y');?>"></strong>
        
    
 <input type="button" onclick="ver_economico();" value="Ver" /> <span id="cargando" style="display:none;">Cargando ...</span>
<div id="informe_economico"></div>
<script>
function ver_economico(){
	var strDesde = $('#desde').val().split("/"); 
	var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	
	if ((desde!='undefined-undefined-')) {
		$('#cargando').show();
	    $.ajax({
	        url: '<?php echo $this->Html->url('/informes/ventas_diario', true);?>/'+desde,
	        dataType: 'html',
	        success: function(data){
	            $('#cargando').hide();
	            $('#informe_economico').html(data);
	        }
	    })
	}
    
}


$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });
/*ver_economico(); */
</script>
