<div class="content">
    <table width="100%">
        <tr>
            <td width="28%"><?php echo $this->Html->image('logo_s.jpg', array('width' => '150')); ?></td>
            <td width="36%" class="locacion">
                CABA, Argentina (1107) Tel. (54) 11 52547702/ 0810-345-5002<br> <?php echo $solo_desde;?> CABA
            </td>
            <td width="36%"  class="locacion">
                25 de Mayo 260 2do. 9 Ushuaia, Tierra del Fuego, Argentina (9410) Tel. (54) 2901 425337<br/>

            </td>
        </tr>
    </table>
    <hr/>
    <h1><?php echo $confirmacion_reserva;?></h1>
    <table width="680" align="center" cellpadding="3" cellspacing="3" border="0">
        <tr>
            <td width="200"><strong><?php echo $titular;?>:</strong></td>
            <td><?php echo $reserva['Cliente']['nombre_apellido'];?></td>
        </tr>
        <tr>
            <td width="200"><strong><?php echo $total_pasajeros;?>:</strong></td>
            <td><?php echo $reserva['Reserva']['pax_adultos'] + $reserva['Reserva']['pax_menores'];?></td>
        </tr>
        <tr>
            <td width="200"><?php echo $adultos;?>:</td>
            <td><?php echo $reserva['Reserva']['pax_adultos'];?></td>
        </tr>
        <tr>
            <td width="200"><?php echo $menores;?>:</td>
            <td><?php echo $reserva['Reserva']['pax_menores'];?></td>
        </tr>
        <tr>
            <td width="200"><?php echo $bebes;?>:</td>
            <td><?php echo $reserva['Reserva']['pax_bebes'];?></td>
        </tr>
        <tr>
            <td width="200"><strong><?php echo $categoria_label;?>:</strong></td>
            <td><?php echo $categoria['Categoria']['categoria'];?></td>
        </tr>
        <tr>
            <td width="200"><strong><?php echo $lugar_retiro;?>:</strong></td>
            <td><?php echo $reserva['Lugar_Retiro']['lugar'];?></td>
        </tr>
        <tr>
            <td width="200"><strong><?php echo $retiro;?>:</strong></td>
            <td><?php echo $reserva['Reserva']['retiro'].' '.$reserva['Reserva']['hora_retiro'];?></td>
        </tr>
         <tr>
            <td width="200"><strong><?php echo $lugar_devolucion;?>:</strong></td>
            <td><?php echo $reserva['Lugar_Devolucion']['lugar'];?></td>
        </tr>
        <tr>
            <td width="200"><strong><?php echo $devolucion;?>:</strong></td>
            <td><?php echo $reserva['Reserva']['devolucion'].' '.$reserva['Reserva']['hora_devolucion'];?></td>
        </tr>

        <tr>
            <td width="200"><strong><?php echo $seguro;?>:</strong></td>
            <td><?php
	    if($reserva['Reserva']['discover']){
		$seguro = 'Discover';
	    }
	    if($reserva['Reserva']['discover_plus']){
		$seguro = 'Discover Plus';
	    }
	    if($reserva['Reserva']['discover_advance']){
		$seguro = 'Discover Advance';
	    }

	    //echo ($reserva['Reserva']['discover'])?'Discover':(($reserva['Reserva']['discover_plus'])?'Discover Plus':($reserva['Reserva']['discover_advance'])?'Discover Advance':'');
	    echo $seguro;
	    ?></td>
        </tr>
        <?php

        if(count($extras) > 0){ ?>
            <tr>
                <td colspan="2"><strong>Extras incluidos</strong></td>

            </tr>







            <?php foreach($extras as $extra){
                // print_r($extra);
                switch ($idioma) {
                    case 1:
                        $subRubro=$extra['Extra']['ExtraSubrubro']['subrubro'];
                        break;

                    case 2:
                        $subRubro=$extra['Extra']['ExtraSubrubro']['subrubro_ingles'];
                        break;

                    case 3:
                        $subRubro=$extra['Extra']['ExtraSubrubro']['subrubro_portugues'];
                        break;
                }

                ?>
                <tr>
                    <td class="border" width="400"> <?php echo utf8_encode($extra['Extra']['ExtraRubro']['rubro']);?> <?php echo utf8_encode($subRubro);?> <?php echo utf8_encode($extra['Extra']['detalle']); ?></td>
                    <td class="border"><?php echo $extra['ReservaExtra']['cantidad'];?></td>
                </tr>
            <?php } ?>





        <?php } ?>
        <?php if($pendiente > 0){ ?>
        <tr>
            <td width="200"><strong><?php echo $saldo;?>:</strong></td>
            <td>$<?php echo $pendiente; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td width="200"><strong><?php echo $numero;?>:</strong></td>
            <td><?php echo $reserva['Reserva']['numero'];?></td>
        </tr>
    </table>
    <p><strong><?php echo nl2br($restricciones); ?></strong></p>

    <h1><?php echo $politica_cancelacion_label;?></h1>
    <p><?php echo nl2br($politica_cancelacion); ?></p>
    <p>&nbsp;</p>
    <p align="center"><em><?php echo $gracias;?></em></p>
</div>
