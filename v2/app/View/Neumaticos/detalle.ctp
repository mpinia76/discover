<?php
//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_neumaticos_detalle").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>

        <td>
            <p>
                <b>Información de alta</b>
            <p>

            <p>
                <b>Neumático:</b> <?php echo $neumatico['Neumatico']['marca'].' - '.$neumatico['Neumatico']['modelo'].' - '.$neumatico['Neumatico']['medida'].' - '.$unidad['Unidad']['unidadPatente']?> <br>
                <b>Temporada:</b> <?php echo $neumatico['Neumatico']['temporada']?> <br>
                <b>Identificador:</b> <?php echo $neumatico['Neumatico']['identificador']?> <br>
                <b>Fecha:</b> <?php echo $neumatico['Neumatico']['fecha']?> <br>
                <b>km Unidad Asignada:</b> <?php echo $unidad['Unidad']['km']?> <br>
            </p>
        </td>
    </tr>
</table>

<p>
    <b>Historial de estado</b>
<p>

<table width="100%" cellpadding="0" cellspacing="0">


    <tr style="font-weight:bold;">
        <td>Unidad</td>
        <td>Estado</td>
        <td>Desde</td>
        
        <td>Hasta</td>
        <td>KM Recorrido</td>
        <td>Dibujo MM</td>
        <td>Comentarios</td>
    </tr>
    <?php
    //print_r($neumatico['NeumaticoEstado']);
    foreach($neumatico['NeumaticoEstado'] as $neumaticoEstado){
        $comentarios = ($neumaticoEstado['estado']!='Baja')?$neumaticoEstado['descripcion']:'';?>
        <tr>
            <td ><?php echo $neumaticoEstado['descripcion_unidad']['unidadPatente'];?></td>
            <td ><?php echo $neumaticoEstado['estado'];?></td>
            <td ><?php echo $neumaticoEstado['desde'];?></td>
            <td ><?php echo $neumaticoEstado['hasta'];?></td>
            <td ><?php echo $neumaticoEstado['km'];?></td>
            <td ><?php echo $neumaticoEstado['dibujo'];?></td>
            <td ><?php echo $comentarios;?></td>
        </tr>

        <?php //print_r($reserva);
    } ?>
    
</table>
<p>
    <b>Detalle de baja</b>
<p>
<table width="100%" cellpadding="0" cellspacing="0">


    <tr style="font-weight:bold;">
        <td>Fecha</td>
        <td>Motivo</td>
        <td>Fotos</td>



    </tr>
    <?php
    //print_r($neumatico['NeumaticoEstado']);
    foreach($neumatico['NeumaticoEstado'] as $neumaticoEstado){
        if ($neumaticoEstado['estado']=='Baja'){?>
        <tr>

            <td ><?php echo $neumaticoEstado['fecha'];?></td>
            <td ><?php echo $neumaticoEstado['motivo'];?></td>

            <td><a onclick="verFotos();">Ver</a></td>
        </tr>

        <?php } //print_r($reserva);
    } ?>

</table>

<script>
    function verFotos(){

            //document.location = "<?php echo $this->Html->url('/informes/ventas_diario', true);?>";
        createWindow('w_neumaticos_fotos','Fotos','<?php echo $this->Html->url('/neumaticos/fotos', true);?>/<?php  echo $neumatico['Neumatico']['id'];?>','800','350');


    }

</script>