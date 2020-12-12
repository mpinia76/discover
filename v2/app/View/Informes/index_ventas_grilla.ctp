

<strong>&nbsp;&nbsp;Entre fechas&nbsp;<input type="text" name="desde" id="desde" class="datepicker" value="<?php echo date('d/m/Y');?>">&nbsp;&nbsp;y&nbsp;<input type="text" name="hasta" id="hasta" class="datepicker"></strong>
        
    
 <input type="button" onclick="ver_economico();" value="Ver" /> <span id="cargando" style="display:none;">Cargando ...</span>
<div id="informe_economico"></div>
<script>
sumaFecha = function(d, fecha)
{
 var Fecha = new Date();
 var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
 var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
 var aFecha = sFecha.split(sep);
 var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
 fecha= new Date(fecha);
 fecha.setDate(fecha.getDate()+parseInt(d));
 var anno=fecha.getFullYear();
 var mes= fecha.getMonth()+1;
 var dia= fecha.getDate();
 mes = (mes < 10) ? ("0" + mes) : mes;
 dia = (dia < 10) ? ("0" + dia) : dia;
 var fechaFinal = dia+sep+mes+sep+anno;
 return (fechaFinal);
 }
 
 $('#hasta').val(sumaFecha(15,$('#desde').val()));
 
function ver_economico(){
	var strDesde = $('#desde').val().split("/"); 
	var Fecha1 = new Date(parseInt(strDesde[2]),parseInt(strDesde[1]-1),parseInt(strDesde[0]));
	var strHasta = $('#hasta').val().split("/"); 
	var Fecha2 = new Date(parseInt(strHasta[2]),parseInt(strHasta[1]-1),parseInt(strHasta[0]));
	if(!isNaN(strHasta[2])){
		
		
		if(Fecha1>Fecha2){
			alert('La fecha Hasta tiene que ser posterior a la fecha Desde');
			return false;
		}
	}
	
	
	
	var desde = strDesde[2]+'-'+strDesde[1]+'-'+strDesde[0];	
	
	var hasta = strHasta[2]+'-'+strHasta[1]+'-'+strHasta[0];	
	
	if ((desde!='undefined-undefined-')) {
		$('#cargando').show();
	    $.ajax({
	        url: '<?php echo $this->Html->url('/informes/ventas_grilla', true);?>/'+desde+'/'+hasta,
	        dataType: 'html',
	        success: function(data){
	            $('#cargando').hide();
	            $('#informe_economico').html(data);
	        }
	    })
	}
    
}


$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });
ver_economico(); 
</script>
