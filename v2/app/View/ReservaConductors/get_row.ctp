<?php
$i = rand(100,10000);
if(isset($reserva_conductor_id)){ ?>
    <tr class="border_bottom" id="ReservaConductor<?php echo $reserva_conductor_id?>">
<?php }else{ ?>
    <tr class="border_bottom" id="Conductor<?php echo $i?>">
<?php } ?>
    <td width="30%">
        <input type="hidden" name="data[ReservaConductorCounter][]" value="<?php echo $i?>"/>
        <input type="hidden" class="itemConductor" name="data[ReservaConductorNombreApellido][]" value="<?php echo $nombre_apellido;?>"/>

        <?php echo $nombre_apellido;?>
    </td>
    <td width="20%">

        <input type="hidden" name="data[ReservaConductorDni][]" value="<?php echo $dni;?>"/>

        <?php echo $dni;?>
    </td>
    <td width="10%">

        <input type="hidden" name="data[ReservaConductorTelefono][]" value="<?php echo $telefono;?>"/>
        <input type="hidden" name="data[ReservaConductorEmail][]" value="<?php echo $email;?>"/>
        <input type="hidden" name="data[ReservaConductorNroLicencia][]" value="<?php echo $nroLicencia;?>"/>

        <?php echo $nroLicencia;?>
    </td>
    <td width="10%">

        <input type="hidden" name="data[ReservaConductorVencimiento][]" value="<?php echo $vencimiento;?>"/>
        <input type="hidden" name="data[ReservaConductorLugarEmision][]" value="<?php echo $lugarEmision;?>"/>
        <input type="hidden" name="data[ReservaConductorDireccion][]" value="<?php echo $direccion;?>"/>
        <input type="hidden" name="data[ReservaConductorLocalidad][]" value="<?php echo $localidad;?>"/>

        <?php echo $vencimiento;?>
    </td>
<?php if(isset($reserva_conductor_id)){ ?>
    <td align="right" width="50"><a onclick=" quitarConductor('<?php echo $reserva_conductor_id;?>');">quitar</a></td>
<?php }else{ ?>
    <td align="right" width="50"><a onclick="$('#Conductor<?php echo $i?>').remove();">quitar</a></td>
<?php } ?>
</tr>
