

<select id="economico_mes">

	<option>En uso</option>
    <option>En deposito</option>

</select>






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
$(document).ready(function() {
    ver_economico();  // Dispara la función automáticamente al cargar la página
    // Llama a la función ver_economico cada vez que cambie la selección
    $('#economico_mes').change(function() {
        ver_economico();
    });
});


</script>
