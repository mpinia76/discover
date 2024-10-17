

<select id="economico_mes">

	<option>En uso</option>
    <option>En deposito</option>

</select>




<input type="button" onclick="ver_economico();" value="Ver" />

  <span id="cargando" style="display:none;">Cargando ...</span>
<div id="informe_economico"></div>
<script>
function ver_economico(){

    $('#cargando').show();
    $.ajax({
        url: '<?php echo $this->Html->url('/informes/neumaticos', true);?>/'+$('#economico_mes').val(),
        dataType: 'html',
        success: function(data){
            $('#cargando').hide();
            $('#informe_economico').html(data);
        }
    })
}



</script>
