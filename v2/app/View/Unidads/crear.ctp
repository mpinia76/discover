<?php
//agregar el calendario
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');


//formulario
echo $this->Form->create(null, array('url' => '/unidads/crear','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('Unidad.ultimaReserva',array('value' => $ultimaReserva));
echo $this->Form->hidden('Unidad.cantAlertas',array('value' => $cantunidadalertas));
?>



<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.marca');?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.modelo');?></div>
</div>
<div class="ym-grid">
	<div class="ym-g25 ym-gl"><?php echo $this->Form->input('Unidad.patente'); ?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Unidad.orden',array( 'style' => 'width:60px;')); ?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Unidad.capacidad',array('style' => 'width:60px;')); ?></div>
    <div class="ym-g25 ym-gl"><?php echo $this->Form->input('Unidad.km',array('style' => 'width:80px;')); ?></div>
</div>
<div class="ym-grid">
	<div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.habilitacion',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.baja',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.periodo'); ?></div>


</div>
<div class="ym-grid">

    <div class="ym-g50 ym-gl">

    </div>
    <div class="ym-g50 ym-gl" style="margin-top: 15px; font-weight: bold">
        Alertas relacionadas a la unidad.
        <?php echo $this->Form->hidden('Unidad.errorAlerta');?>
    </div>
    <div class="ym-g8 ym-gl">
        <span id="btn_agregar_alerta" style="" class="boton agregar" onclick="addAlerta();">+ agregar</span>

    </div>
</div>

<table class="table" style="width: 100%">


    <tbody id="cuerpoalertas">

    </tbody>




</table>
<div class="ym-grid">

    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.estado',array('default' => '1', 'label' => 'Activa')); ?></div>
    <div class="ym-g50 ym-gl"><?php echo $this->Form->input('Unidad.excluir',array('default' => '0', 'label' => 'Excluir de estadisticas')); ?></div>
</div>



<span id="botonGuardar" onclick="guardar('<?php echo $this->Html->url('/unidads/guardar.json', true);?>',$('form').serialize(),{id:'w_unidads',url:'v2/unidads/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
    function cambiarAlerta(a)
    {
        var $id = $("#alerta_"+a).val()
        var $unidad= $( "#alerta_"+a+" option:selected" ).attr("uni");

        switch ($unidad) {
            case 'KM':

                $('#inicio_num_'+a).val($('#UnidadKm').val());
                $('#divIniNum_'+a).show();
                $('#divIniFecha_'+a).hide();
                break;
            case 'Tiempo':
                $('#divIniNum_'+a).hide();
                $('#divIniFecha_'+a).show();
                break;
            case 'Reservas':
                $('#inicio_num_'+a).val($('#UnidadUltimaReserva').val());
                $('#divIniNum_'+a).show();
                $('#divIniFecha_'+a).hide();
                break;

        }
    }

    function addAlerta()
    {

        $cantAlertas = parseInt($('#UnidadCantAlertas').val());
        $cantAlertas = $cantAlertas+1;
        $('#UnidadCantAlertas').val($cantAlertas);
        var tr='<tr>'+
            '<td></td><td>'+'<select name="alerta[]" id="alerta_'+$cantAlertas+'" style="width: 200px;" onChange="cambiarAlerta('+$cantAlertas+')"><option value="">Seleccionar...</option>'+
            '<?php foreach ($alertas as $alerta){
                $magnitud = ($alerta['Alerta']['magnitud'])?' '.$alerta['Alerta']['magnitud']:' ';
                $alertaMostrar = $alerta['Alerta']['alerta'].' '.$alerta['Alerta']['unidad'].$magnitud.' '.$alerta['Alerta']['segmento'];
                echo '<option uni="'.$alerta['Alerta']['unidad'].'" value="'.$alerta['Alerta']['id'].'">'.$alertaMostrar.'</option>';
            }?>'+

            '</select></td>'+'<td>'+'<span style="font-weight:bold">Inicio: </span><span id="divIniNum_'+$cantAlertas+'"><input name="inicio_num[]" id="inicio_num_'+$cantAlertas+'" type="number"/></span><span id="divIniFecha_'+$cantAlertas+'" style="display:none"><input name="inicio_fecha[]" id="inicio_fecha_'+$cantAlertas+'" class="datepicker" type="text" style="width: 100px;"/></span>' +'</td><td><a href="#" class="removealerta"><img src="../img/bt_anular.png" align="absmiddle"></a></td>'+
            '</tr>';
        $('#cuerpoalertas').append(tr);

        $(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });
    };
    $('body').on('click', '.removealerta', function(e){

        e.preventDefault();
        var last=$('tbody tr').length;

        $(this).parent().parent().remove();


    });



</script>
