<?php
$ano= date('Y');

?>
<strong>Informe Ocupacion</strong>:
<select id="economico_mes">
	<option>Seleccionar...</option>
	<option>2016</option>
    <option>2017</option>
    <option>2018</option>
    <option>2019</option>
    <option>2020</option>
    <option <?php if($ano == '2021'){?> selected="selected" <?php } ?>>2021</option>
    <option <?php if($ano == '2022'){?> selected="selected" <?php } ?>>2022</option>
    <option <?php if($ano == '2023'){?> selected="selected" <?php } ?>>2023</option>
    <option <?php if($ano == '2024'){?> selected="selected" <?php } ?>>2024</option>
    <option <?php if($ano == '2025'){?> selected="selected" <?php } ?>>2025</option>
    <option <?php if($ano == '2026'){?> selected="selected" <?php } ?>>2026</option>
</select>
<strong>&nbsp;&nbsp;Entre fechas&nbsp;<input type="text" name="desde" id="desde" class="datepicker">&nbsp;&nbsp;y&nbsp;<input type="text" name="hasta" id="hasta" class="datepicker"></strong>





 <input type="button" onclick="ver_economico();" value="Ver" /> <span id="cargando" style="display:none;">Cargando ...</span>
<div id="informe_economico"></div>
<script>
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

    $('#cargando').show();
    $.ajax({
        url: '<?php echo $this->Html->url('/informes/ventas_ocupacion_porcentaje', true);?>/'+$('#economico_mes').val()+'/'+desde+'/'+hasta,
        dataType: 'html',
        success: function(data){
            $('#cargando').hide();
            $('#informe_economico').html(data);
        }
    })
}


$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });
 $("#economico_mes").change(function(){
 		if($('#economico_mes').val()!='Seleccionar...'){
        	$('#desde').val('01/01/'+$('#economico_mes').val());
        }
        $('#hasta').val('');
    });
 $("#economico_mes").change();
</script>
