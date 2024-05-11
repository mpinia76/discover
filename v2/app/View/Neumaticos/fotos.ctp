

<table width="100%" cellpadding="0" cellspacing="0">


    <tr style="font-weight:bold;">
        <td>Foto 1</td>
        <td>Foto 2</td>
        <td>Foto 3</td>



    </tr>
    <?php
    //print_r($neumatico['NeumaticoEstado']);
    foreach($neumatico['NeumaticoEstado'] as $neumaticoEstado){
        if ($neumaticoEstado['estado']=='Baja'){?>
            <tr>

                <td >
                    <?php if ($neumaticoEstado['foto1']) { ?>
                        <img src="<?php echo $imageUrl.$neumaticoEstado['foto1'];?>" width="100%" style="margin-top:10px;" />

                     <?php } ?>
                </td>
                <td >
                    <?php if ($neumaticoEstado['foto2']) { ?>
                        <img src="<?php echo $imageUrl.$neumaticoEstado['foto2'];?>" width="100%" style="margin-top:10px;" />

                    <?php } ?>
                </td>
                <td >
                    <?php if ($neumaticoEstado['foto2']) { ?>
                        <img src="<?php echo $imageUrl.$neumaticoEstado['foto2'];?>" width="100%" style="margin-top:10px;" />

                    <?php } ?>
                </td>
            </tr>

        <?php } //print_r($reserva);
    } ?>

</table>