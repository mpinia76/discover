<style>
    .fina-tabla {
        border-collapse: collapse;
    }
    .fina-tabla, .fina-tabla td, .fina-tabla th {
        border: 1px solid #000; /* Cambia #000 a cualquier color de borde que prefieras */
        padding: 8px; /* Ajusta el padding según tus necesidades */
    }
    .fina-tabla th {
        background-color: #f2f2f2; /* Opcional: color de fondo para el encabezado */
    }
</style>

<table class="fina-tabla" width="100%" cellspacing="0">
        <tr class="titulo">
            <td rowspan="3">Unidad</td>
            <td rowspan="3">Patente</td>
            <td rowspan="3">Medida</td>
            <td colspan="2" rowspan="2"><?php echo $estado; ?></td>
            <td colspan="8">MEDIDAS DIBUJO MM</td>
        </tr>
        <tr class="titulo">


            <td>Del. Izq.</td>
            <td>Del. Der.</td>
            <td>Tras. Izq.</td>
            <td>Tras. Der.</td>
            <td>Del. Izq.</td>
            <td>Del. Der.</td>
            <td>Tras. Izq.</td>
            <td>Tras. Der.</td>
        </tr>
    <tr class="titulo">

        <td >Verano</td>
        <td >Invierno</td>
        <td colspan="4">Verano</td>
        <td colspan="4">Invierno</td>
    </tr>
    
    <?php //print_r($unidads); ?>
        <?php foreach ($unidads as $unidad) {
            //print_r($unidad['Unidad']['neumaticos'])."<br>";?>
    <tr class="contenido">
        <td class="mes"><?php echo $unidad['Unidad']['marca'].' '.$unidad['Unidad']['modelo']; ?></td>

        <td><?php echo $unidad['Unidad']['patente']; ?></td>
        <td><?php
            $medida='';
            $discrepancia=0;
            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {
                if ($medida){
                    if ($medida!=$neumatico['Neumatico']['medida']){
                        $discrepancia=1;
                    }
                }
                $medida=$neumatico['Neumatico']['medida'];
            }
            $strDiscrepancia = ($discrepancia)?' Discrepancia!!!':'';
            echo $medida.$strDiscrepancia; ?></td>
        <td><?php
            $totaVerano=0;
            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if (strpos($neumatico['Neumatico']['temporada'], 'Verano') !== false) {
                    $totaVerano++;
                    }


            }

            echo ($totaVerano)?$totaVerano:''; ?>
        </td>
        <td><?php
            $totaInvierno=0;
            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if (strpos($neumatico['Neumatico']['temporada'], 'Invierno') !== false) {
                    $totaInvierno++;
                }


            }

            echo ($totaInvierno)?$totaInvierno:''; ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Verano') !== false)&&($neumatico['Neumatico']['posicion']=='DI')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

            ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Verano') !== false)&&($neumatico['Neumatico']['posicion']=='DD')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

             ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Verano') !== false)&&($neumatico['Neumatico']['posicion']=='TI')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

            ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Verano') !== false)&&($neumatico['Neumatico']['posicion']=='TD')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

            ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Invierno') !== false)&&($neumatico['Neumatico']['posicion']=='DI')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

             ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Invierno') !== false)&&($neumatico['Neumatico']['posicion']=='DD')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

            ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Invierno') !== false)&&($neumatico['Neumatico']['posicion']=='TI')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

             ?>
        </td>
        <td><?php

            foreach ($unidad['Unidad']['neumaticos'] as $neumatico) {

                if ((strpos($neumatico['Neumatico']['temporada'], 'Invierno') !== false)&&($neumatico['Neumatico']['posicion']=='TD')) {
                    echo $neumatico['Neumatico']['dibujo'];
                }


            }

             ?>
        </td>
    </tr>

        <?php } ?>
</table>



