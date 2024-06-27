<?php
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');


//formulario
echo $this->Form->create(null, array('url' => '/neumaticos/crear','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('Neumatico.km_unidad_aux');
?>
<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.condicion',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $condiciones));?></div>
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.km');?></div>


</div>


<div class="ym-grid">
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.fecha',array('class'=>'datepicker','type'=>'text'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.marca',array('label'=>'Marca'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.modelo',array('label'=>'Modelo'));?></div>


</div>
<div class="ym-grid">
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.medida',array('label'=>'Medida','maxlength'=>'10'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.fabricacion',array('label'=>'Fabricación (DOT)','maxlength'=>'4','type'=>'number','style' => 'width:100%','oninput' => 'this.value = this.value.slice(0, 4)'));?></div>

    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.dibujo',array('label'=>'Medida MM Dibujo','maxlength'=>'2','type'=>'number','style' => 'width:100%','oninput' => 'this.value = this.value.slice(0, 2)'));?></div>
</div>
<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.temporada',array('label'=>'Temporada','empty' => 'Seleccionar', 'type'=>'select', 'options' => $temporadas));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.posicion',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $posiciones));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.estado',array('empty' => 'Seleccionar', 'type'=>'select', 'options' => $estados));?></div>

</div>

<div class="ym-grid">
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Unidad.categoria_id',array('empty' => 'Seleccionar', 'type'=>'select'));?></div>
    <div class="ym-g33 ym-gl"><?php echo $this->Form->input('Neumatico.unidad_id',array('empty' => 'Seleccionar', 'type'=>'select'));?></div>
    <div class="ym-g20 ym-gl"><?php echo $this->Form->input('Neumatico.km_unidad');?></div>
</div>
<div class="ym-grid" style="display: flex; align-items: center;">
    <div class="ym-g20 ym-gl">
        <?php echo $this->Form->input('Neumatico.identificador', array('readonly' => 'readonly'));?>
    </div>
    <div class="ym-g10 ym-gl" style="display: flex; align-items: center;margin-top: 15px;">
        <button type="button" id="generarNumero">Generar</button>
    </div>
</div>

<span onclick="guardar('guardar.json',$('form').serialize(),{id:'w_neumaticos',url:'v2/neumaticos/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
<?php echo $this->Form->end(); ?>

<script>
    $('#UnidadCategoriaId').change(function(){
        if($(this).val()!=''){
            $.ajax({
                url: '<?php echo $this->Html->url('/reservas/getUnidads/', true);?>'+$(this).val(),
                dataType: 'html',

                success: function(data){
                    $('#NeumaticoUnidadId').html(data);
                }
            });
        }else{
            $('#NeumaticoUnidadId').html('');
        }
    })
    $('#NeumaticoUnidadId').change(function(){
        var unidad_id = $(this).val();
        if(unidad_id !== '') {
            $.ajax({
                url: '<?php echo $this->Html->url('/unidads/getKm/', true);?>'+unidad_id,

                type: 'GET',

                success: function(response) {
                    $('#NeumaticoKmUnidad').val(response.km);
                    $('#NeumaticoKmUnidadAux').val(response.km);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
    $('#generarNumero').click(function(){
        var numeroAleatorio = generarNumeroAlfanumerico();
        $('#NeumaticoIdentificador').val(numeroAleatorio);
    });

    function generarNumeroAleatorio() {
        // Genera un número aleatorio único de 6 cifras
        var numero = Math.floor(Math.random() * 900000) + 100000;
        return numero.toString();
    }

    function generarNumeroAlfanumerico() {
        var caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var longitud = 6;
        var numeroAlfanumerico = '';
        for (var i = 0; i < longitud; i++) {
            numeroAlfanumerico += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
        return numeroAlfanumerico;
    }

    $(document).ready(function() {
        // Disparar el evento change del campo de selección de categoría al cargar la página
        $('#UnidadCategoriaId').trigger('change');
    });

</script>
