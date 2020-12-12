<div class="content">
    <table width="100%">
        <tr>
            <td width="28%"><?php echo $this->Html->image('logo_s.jpg', array('width' => '150')); ?></td>
            <td width="36%" class="locacion">
                <span class="ciudad">Buenos Aires</span> <br/>
                Oficina de Reservas Puerto Madero <br/>
                Tel: 0810 345 5002
            </td>
            <td width="36%"  class="locacion">
                <span class="ciudad">Ushuaia – Tierra del Fuego</span> <br/>
                25 de Mayo 260 – 2do piso oficina 9 – Edificio Finisterre<br/>
                
            </td>
        </tr>
    </table>
    <hr/>
    <h1>Confirmaci&oacute;n de la reserva</h1>
    <table width="680" align="center" cellpadding="3" cellspacing="3" border="0">
        <tr>
            <td width="200"v><strong>Titular de la reserva</strong></td>
            <td><?php echo $reserva['Cliente']['nombre_apellido'];?></td>
        </tr>
        <tr>
            <td width="200"><strong>Cantidad de pasajeros</strong></td>
            <td><?php echo $reserva['Reserva']['pax_adultos'] + $reserva['Reserva']['pax_menores'];?></td>
        </tr>
        <tr>
            <td width="200">Mayores</td>
            <td><?php echo $reserva['Reserva']['pax_adultos'];?></td>
        </tr>
        <tr>
            <td width="200">Menores</td>
            <td><?php echo $reserva['Reserva']['pax_menores'];?></td>
        </tr>
        <tr>
            <td width="200">Beb&eacute;s</td>
            <td><?php echo $reserva['Reserva']['pax_bebes'];?></td>
        </tr>
        <tr>
            <td width="200"><strong>Unidad</strong></td>
            <td><?php echo $categoria['Categoria']['categoria'].' '.$reserva['Unidad']['unidad'];?></td>
        </tr>
        <tr>
            <td width="200"><strong>Lugar Retiro</strong></td>
            <td><?php echo $reserva['Lugar_Retiro']['lugar'];?></td>
        </tr>
        <tr>
            <td width="200"><strong>Retiro</strong></td>
            <td><?php echo $reserva['Reserva']['retiro'].' '.$reserva['Reserva']['hora_retiro'];?></td>
        </tr>
         <tr>
            <td width="200"><strong>Lugar Devoluci&oacute;n</strong></td>
            <td><?php echo $reserva['Lugar_Devolucion']['lugar'];?></td>
        </tr>
        <tr>
            <td width="200"><strong>Devoluci&oacute;n</strong></td>
            <td><?php echo $reserva['Reserva']['devolucion '].' '.$reserva['Reserva']['hora_devolucion'];?></td>
        </tr>
       
        <tr>
            <td width="200"><strong>Seguro contratado</strong></td>
            <td><?php echo ($reserva['Reserva']['discover'])?'Discover':(($reserva['Reserva']['discover_plus'])?'Discover Plus':($reserva['Reserva']['discover_advance'])?'Discover Advance':'');?></td>
        </tr>
        <tr>
            <td width="200"><strong>Tarifa acordada</strong></td>
            <td>$<?php echo $total; ?></td>
        </tr>
        <tr>
            <td width="200"><strong>Pago recibido</strong></td>
            <td>$<?php echo $pagado; ?></td>
        </tr>
        <tr>
            <td width="200"><strong>Saldo a pagar</strong></td>
            <td>$<?php echo $pendiente; ?></td>
        </tr>
        <tr>
            <td width="200"><strong>N&uacute;mero de la reserva</strong></td>
            <td><?php echo $reserva['Reserva']['id'];?></td>
        </tr>
    </table>
    
    <p>&nbsp;</p>
    <p align="center"><em>Gracias por habernos elegido!</em></p>
</div>