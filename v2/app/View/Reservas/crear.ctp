<?php
//agregar el calendario
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');
$this->Js->buffer('$("#ReservaTotalEstadia").keyup(updateTotal)');
$this->Js->buffer('
if ($("#ReservaHoraRetiro").val()!=\'\'){
	fecha = $("#ReservaHoraRetiro").val();
	$("#ReservaHoraRetiro").val(fecha);
}
else{
	/*var today = new Date();
	fecha = today.getHours() + \':\' + today.getMinutes();*/
}

');
$this->Js->buffer('
if ($("#ReservaHoraDevolucion").val()!=\'\'){
	fecha = $("#ReservaHoraDevolucion").val();
	$("#ReservaHoraDevolucion").val(fecha);
}
else{
	/*var today = new Date();
	fecha = today.getHours() + \':\' + today.getMinutes();*/
}

');

$this->Js->buffer('  
  $("#ClienteEmail2").on(\'paste\', function(e){
    e.preventDefault();
    alert(\'Introduzca el email manualmente\');
  })
');
//formulario
echo $this->Form->create(null, array('url' => '/reservas/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>

<div class="sectionTitle">Formulario de reserva</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><div class="ym-gbox"><span class="fieldName">Creada:</span> <?php echo date('d/m/Y');?></div></div>
    <div class="ym-g25 ym-gl"><div class="ym-gbox" ><span class="fieldName">Reserva numero:</span> <?php echo $ultimo_nro?></div></div>
    <div class="ym-g25 ym-gl"><div class="ym-gbox" ><span class="fieldName">Cargado por:</span> <?php echo $usuario['Usuario']['nombre']." ".$usuario['Usuario']['apellido']?></div></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.reservado_por',array('label' => 'Reservado por:', 'options' => $empleados, 'empty' => 'Seleccionar ...', 'type'=>'select')); ?></div>
</div>
<?php echo $this->Form->hidden('Reserva.creado',array('value' => date('d/m/Y'))); ?>
<?php echo $this->Form->hidden('Reserva.actualizado',array('value' => date('Y-m-d H:i:s'))); ?>
<?php echo $this->Form->hidden('Reserva.cargado_por',array('value' => $usuario['Usuario']['id'])); ?>
<?php echo $this->Form->hidden('Reserva.numero',array('value' => $ultimo_nro)); ?>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Subcanal.canal_id',array('label' => 'Canal de venta','empty' => 'Seleccionar', 'type'=>'select'));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Reserva.subcanal_id',array('label' => 'Subcanal de venta', 'empty' => 'Seleccionar', 'type'=>'select'));?></div>
</div>
<div class="sectionTitle">Datos Titular de la Reserva</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.nombre_apellido');?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.dni',array('label'=>'DNI/Pasaporte'));?></div>
</div>
<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.sexo',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $sexos));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.telefono'); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.celular'); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.direccion'); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.localidad'); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.iva',array('type' => 'select', 'options' => $iva_ops, 'empty' => 'Seleccionar ...', 'label' => 'IVA')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.cuit', array('label' => 'CUIT','disabled'=>'disabled')); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.nacimiento',array('class'=>'datepicker','type'=>'text')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.profesion'); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.nacionalidadAux',array('label'=>'Nacionalidad')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Reserva.vuelo',array('label'=>'Nro. de vuelo','maxlength'=>'6')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.domicilio_local',array('label'=>'Domicilio en Ushuaia')); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.email');; ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.email2',array('label'=>'Repita el E-mail')); ?></div>
</div>


<div class="sectionSubtitle">Datos Conductor</div>
<div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.titular_conduce',array('label'=>'Titular de reserva conduce','default' => '0')); ?></div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_nombre_apellido',array('label'=>'Nombre Apellido')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_dni',array('label'=>'DNI/Pasaporte'));?></div>
    
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_telefono',array('label'=>'Telefono')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_email',array('label'=>'Email')); ?></div>
</div>
<div class="ym-grid">
    
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.nro_licencia_de_conducir'); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.vencimiento',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Cliente.lugar_emision',array('label'=>'Lugar de emision')); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_direccion',array('label'=>'Direccion')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Cliente.ad_localidad',array('label'=>'Localidad')); ?></div>
</div>

<div class="sectionTitle">Datos Reserva</div>
<div class="ym-grid">
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultCategoria));?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Reserva.unidad_id',array('empty' => 'Seleccionar', 'type'=>'select', 'default' => $defaultUnidad));?></div>
</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.lugar_retiro_id',array('options' => $lugars, 'empty' => 'Seleccionar ...', 'type'=>'select'));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.retiro',array('class'=>'datepicker','type'=>'text','default'=>$retiro));?></div>
    
    <div class="ym-g25 ym-gl"><label for="ReservaHoraRetiro"><div class="ym-gbox required">Retiro (HH:MM)</label><input class="number" type="time" id="ReservaHoraRetiro" name="data[Reserva][hora_retiro]"></div></div>
</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.lugar_devolucion_id',array('options' => $lugars, 'empty' => 'Seleccionar ...', 'type'=>'select'));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.devolucion',array('class'=>'datepicker','type'=>'text','default'=>$devolucion));?></div>
    <div class="ym-g25 ym-gl"><label for="ReservaHoraRetiro"><div class="ym-gbox required">Devolucion (HH:MM)</label><input class="number" type="time" id="ReservaHoraDevolucion" name="data[Reserva][hora_devolucion]"></div></div>
</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><div class="ym-gbox"><strong>Total</strong></div></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.pax_adultos',array('label'=>'Mayores', 'type' => 'text', 'class' => 'number','default' => '0'));?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.pax_menores',array('label'=>'Menores', 'type' => 'text', 'class' => 'number','default' => '0')); ?></div>
    <div class="ym-g25 bebes"><?php echo $this->Form->input('Reserva.pax_bebes',array('label'=>'Bebes', 'type' => 'text', 'class' => 'number','default' => '0', 'div' => false)); ?> 
</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><div class="ym-gbox"><strong>Seguro</strong></div></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.discover',array('label'=>'Discover')); ?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.discover_plus',array('label'=>'Discover Plus')); ?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Reserva.discover_advance',array('label'=>'Discover Advance')); ?></div>
</div>
<div class="ym-grid">
    <div class="ym-g100 ym-gr" class="total_estadia">
        <div class="ym-gbox"><strong>Total Alquiler $</strong> <input style="width: 100px;" type="text" name="data[Reserva][total_estadia]" id="ReservaTotalEstadia" value="0" /></div>
    </div>
</div>


<!-- reservas extras -->
<div class="sectionSubtitle">Extras</div>
<div class="ym-grid">
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Extra.extra_rubro_id',array('label' => 'Seleecione un rubro', 'options' => $extra_rubros, 'empty' => 'Rubro', 'type'=>'select')); ?></div>
    <div class="ym-g50 ym-gl" id="extra_subrubros"></div>
    <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px; display:none;"><span onclick="addExtra();" class="boton agregar">+ agregar</span></div></div>
</div>
<table width="100%" id="reserva_extras"></table>
<table width="100%" id="reserva_extras" class="extras_totales" style="display: none;">
    <tr>
        <td colspan="3" align="right"><strong>Total extras</strong></td>
        <td width="50" align="right">$<span class="extra_total"></span></td>
        <td width="50">&nbsp;</td>
    </tr>
</table>
<!-- fin reservas extras -->

<div class="ym-grid">
    <div class="ym-g100 ym-gr" class="total_estadia">
        <div class="ym-gbox"><strong>Tarifa bruta inicial (total estad&iacute;a+extras adelantados) $</strong> <input style="width: 100px;" type="hidden" name="data[Reserva][total]" id="ReservaTotal" value="0" /><span id="reservaTotalSpan">0</span></div>
    </div>
</div>

<div class="sectionSubtitle">Comentarios</div>
<div class="ym-grid">
    <?php echo $this->Form->input('Reserva.comentarios',array('label' => false, 'type' => 'textarea')); ?>
</div>

<?php if($grilla){ ?>
<span id="botonGuardar" onclick="guardarSinRefrescar('<?php echo $this->Html->url('/reservas/guardar.json', true);?>',$('form').serialize());" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonBloquear" onclick="guardarSinRefrescar('<?php echo $this->Html->url('/reservas/bloquearUnidad.json', true);?>',$('form').serialize());" class="boton bloquear">Bloquear Unidad <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<span id="botonVolver" onclick="volver();" class="boton volver">Volver a la grilla</span>
<?php }else{ ?>
<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/reservas/guardar.json', true);?>',$('form').serialize(),{id:'w_reservas',url:'v2/reservas/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php } ?>
<?php echo $this->Form->end(); ?>

<script>
$(document).ready(function(){
	$("#ClienteNacionalidadAux").autocomplete({
	source: '<?php echo $this->Html->url('/clientes/autoComplete', true);?>',
	minLength: 2
	});
	
	$("#ClienteNacionalidadAux").autocomplete({
	select: function(event, ui) {
	selected_id = ui.item.id;
	$("#ReservaCrearForm").append('<input id="”NacionalidadId”" type="”hidden”" name="data[Cliente][nacionalidad]" value="' + selected_id + '" />');
	
	}
	});
	$("#ClienteNacionalidadAux").autocomplete({
	open: function(event, ui) {
	$("#NacionalidadId").remove();
	}
	});
});
function addExtra(){
    var pattern = /^(([1-9]\d*))$/;
    if(pattern.test($('#ReservaExtraCantidad').val())){
        $.ajax({
          url: '<?php echo $this->Html->url('/reserva_extras/getRow', true);?>',
          data: {'extra_id' : $('#ExtraId').val(), 'cantidad' : $('#ReservaExtraCantidad').val()},
          success: function(data){
              $('#reserva_extras').append(data);
              $('.extras_totales').show();
              updateTotal();
          },
          dataType: 'html'
        });
    }else{
        alert('Ingrese un numero natural mayor a cero');
        $('#ReservaExtraCantidad').focus();
    }
}
$('#ExtraExtraRubroId').change(function(){
    if($(this).val() != ""){
        $.ajax({
          url: '<?php echo $this->Html->url('/extras/getSubrubrosPrecio', true);?>',
          data: {'rubro_id' : $(this).val() },
          success: function(data){
            $('#btn_add_extra').show();
            $('#extra_subrubros').html(data);
            updateTotal();
          },
          dataType: 'html'
        });
    }else{
        $('#btn_add_extra').hide();
        $('#extra_subrubros').html('');
    }
});
function updateTotal(){
    var result = 0;
    var extra_total = 0;
    result += parseFloat($('#ReservaTotalEstadia').val());
    $(".extra_tarifa").each(function(index,obj) { 
        result += parseFloat($('#'+$(obj).parent().parent().parent().attr('id') + ' .extra_cantidad').text()) * parseFloat($(obj).text()); 
        extra_total += parseFloat($('#'+$(obj).parent().parent().parent().attr('id') + ' .extra_cantidad').text()) * parseFloat($(obj).text()); 
    });
    $('#ReservaTotal').val(result);
    $('#reservaTotalSpan').html(result);
    $('.extra_total').html(extra_total);
    if(extra_total == 0){
        $('.extras_totales').hide();
    }
}

$('#SubcanalCanalId').change(function(){
    if($(this).val()!=''){
        $.ajax({
            url: '<?php echo $this->Html->url('/reservas/getSubcanals/', true);?>'+$(this).val(),
            dataType: 'html',
            
            success: function(data){
                $('#ReservaSubcanalId').html(data);
            }
        });
    }else{
         $('#ReservaSubcanalId').html('');
    }
})

$('#UnidadCategoriaId').change(function(){
    if($(this).val()!=''){
        $.ajax({
            url: '<?php echo $this->Html->url('/reservas/getUnidads/', true);?>'+$(this).val(),
            dataType: 'html',
            
            success: function(data){
                $('#ReservaUnidadId').html(data);
            }
        });
    }else{
         $('#ReservaUnidadId').html('');
    }
})

$("#ClienteTitularConduce").click( function(){
   if( $(this).is(':checked') ) {
   		$("#ClienteAdNombreApellido").val($("#ClienteNombreApellido").val());
   		$("#ClienteAdDni").val($("#ClienteDni").val());
   		$("#ClienteAdTelefono").val($("#ClienteTelefono").val());
   		$("#ClienteAdEmail").val($("#ClienteEmail").val());
   		$("#ClienteAdDireccion").val($("#ClienteDireccion").val());
   		$("#ClienteAdLocalidad").val($("#ClienteLocalidad").val());
   }
   else{
   		$("#ClienteAdNombreApellido").val('');
   		$("#ClienteAdDni").val('');
   		$("#ClienteAdTelefono").val('');
   		$("#ClienteAdEmail").val('');
   		$("#ClienteAdDireccion").val('');
   		$("#ClienteAdLocalidad").val('');
   }
   
})

$("#ReservaDiscover").click( function(){
	$("#ReservaDiscoverPlus").prop( "checked", false );
	$("#ReservaDiscoverAdvance").prop( "checked", false );
})

$("#ReservaDiscoverPlus").click( function(){
	$("#ReservaDiscover").prop( "checked", false );
	$("#ReservaDiscoverAdvance").prop( "checked", false );
})

$("#ReservaDiscoverAdvance").click( function(){
	$("#ReservaDiscoverPlus").prop( "checked", false );
	$("#ReservaDiscover").prop( "checked", false );
})

function volver(){
	document.location = "<?php echo $this->Html->url('/informes/index_ventas_grilla2', true);?>";
    
}



</script>