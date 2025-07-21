<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

echo $this->Html->script('https://cdn.jsdelivr.net/npm/tinymce@4.9.11/tinymce.min.js', ['block' => 'script']);
$this->Js->buffer("
    tinymce.init({
        selector: '#DescuentoDescuento, #DescuentoDescuentoIngles, #DescuentoDescuentoPortugues',
        menubar: false,
        plugins: 'link lists',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link',
        height: 200,
        branding: false
    });
");
//formulario
echo $this->Form->create(null, array('url' => '/descuentos/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

?>
<?php echo $this->Form->hidden('Descuento.id'); ?>


<div class="ym-grid">
	<div class="ym-g80 ym-gl"><?php echo $this->Form->input('Descuento.descuento'); ?></div>


</div>
<div class="ym-grid">
	<div class="ym-g80 ym-gl"><?php echo $this->Form->input('Descuento.descuento_ingles'); ?></div>


</div>
<div class="ym-grid">
	<div class="ym-g80 ym-gl"><?php echo $this->Form->input('Descuento.descuento_portugues'); ?></div>


</div>

<div class="ym-grid">

	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.activo',array('label' => 'Disp. en venta on line espa&ntilde;ol')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.activo_ingles',array('label' => 'Disp. en venta on line ingl&eacute;s')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.activo_portugues',array('label' => 'Disp. en venta on line portugu&eacute;s')); ?></div>

</div>

<div class="ym-grid">

	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.tarjeta',array('label' => 'Permite tarjeta espa&ntilde;ol')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.tarjeta_ingles',array('label' => 'Permite tarjeta ingl&eacute;s')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.tarjeta_portugues',array('label' => 'Permite tarjeta portugu&eacute;s')); ?></div>

</div>
<div class="ym-grid">

    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.mercadopago',array( 'label' => 'MercadoPago espa&ntilde;ol')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.mercadopago_ingles',array('label' => 'MercadoPago ingl&eacute;s')); ?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.mercadopago_portugues',array('label' => 'MercadoPago portugu&eacute;s')); ?></div>
</div>
<div class="ym-grid">
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.coheficiente',array('label' => 'Coheficiente descuento')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.parcial',array('label' => 'Coheficiente pago parcial')); ?></div>
	 <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Descuento.orden'); ?></div>
</div>


<!-- periodos -->
<div id="divPeriodos">
    <div class="sectionSubtitle">Períodos</div>
    <div class="ym-grid">
        <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DescuentoPeriodo.desde',array('class'=>'datepicker','type'=>'text'));?></div>
        <div class="ym-g33 ym-gl"><?php echo $this->Form->input('DescuentoPeriodo.hasta',array('class'=>'datepicker','type'=>'text'));?></div>
        <div class="ym-g25 ym-gl"><div id="btn_add_extra" class="ym-gbox" style="margin-top:5px;"><span onclick="addPeriodo();" class="boton agregar">+ agregar</span></div></div>
    </div>
    <table width="100%" id="descuento_periodos">

        <?php $i = 0;

        if(count($descuentoPeriodos) > 0){
            foreach($descuentoPeriodos as $descuentoPeriodo){
                //print_r($descuentoPeriodo);
                ?>

                <tr class="border_bottom" id="Extra<?php echo $i;?>">
                    <td width="25%">


                        <input type="hidden" name="data[DescuentoPeriodoDesde][]" value="<?php echo $descuentoPeriodo['DescuentoPeriodo']['desde'];?>"/>
                        <input type="hidden" name="data[DescuentoPeriodoHasta][]" value="<?php echo $descuentoPeriodo['DescuentoPeriodo']['hasta'];?>"/>
                        <?php echo $descuentoPeriodo['DescuentoPeriodo']['desde']?>
                    </td>

                    <td><?php echo $descuentoPeriodo['DescuentoPeriodo']['hasta']?></td>
                    <td align="right" width="50"><a onclick="quitarExtra('<?php echo $descuentoPeriodo['DescuentoPeriodo']['id']?>', <?php echo $i?>);">quitar</a></td>
                </tr>
            <?php  }} ?>
    </table>


<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/descuentos/guardar.json', true);?>',$('form').serialize(),{id:'w_descuentos',url:'v2/descuentos/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
    $('#DescuentoMercadopago').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoTarjeta').prop( "checked", false );
        }

    })
    $('#DescuentoTarjeta').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoMercadopago').prop( "checked", false );
        }

    })
    $('#DescuentoMercadopagoIngles').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoTarjetaIngles').prop( "checked", false );
        }

    })
    $('#DescuentoTarjetaIngles').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoMercadopagoIngles').prop( "checked", false );
        }

    })
    $('#DescuentoMercadopagoPortugues').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoTarjetaPortugues').prop( "checked", false );
        }

    })
    $('#DescuentoTarjetaPortugues').change(function(){
        if ($(this).is(':checked')) {
            $('#DescuentoMercadopagoPortugues').prop( "checked", false );
        }

    })

    function addPeriodo() {
        var desde = $('#DescuentoPeriodoDesde').val();
        var hasta = $('#DescuentoPeriodoHasta').val();

        if (!desde || !hasta) {
            alert("Debes completar ambas fechas: Desde y Hasta.");
            return;
        }

        // Convertir fechas de dd/mm/yyyy a objetos Date
        var partesDesde = desde.split('/');
        var partesHasta = hasta.split('/');

        var fechaDesde = new Date(partesDesde[2], partesDesde[1] - 1, partesDesde[0]); // Año, Mes (0-11), Día
        var fechaHasta = new Date(partesHasta[2], partesHasta[1] - 1, partesHasta[0]);

        if (fechaDesde > fechaHasta) {
            alert("La fecha 'Desde' no puede ser posterior a la fecha 'Hasta'.");
            return;
        }

        $.ajax({
            url: '<?php echo $this->Html->url('/descuento_periodos/getRow', true);?>',
            data: {
                'desde': desde,
                'hasta': hasta
            },
            success: function (data) {
                $('#descuento_periodos').append(data);
            },
            dataType: 'html'
        });
    }

    function quitarExtra(reserva_extra_id, item){

        if(confirm('Seguro desea eliminar el periodo?')){
            $('#Extra'+item).remove();
        }
    }
</script>
