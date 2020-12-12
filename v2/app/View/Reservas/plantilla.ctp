<?php
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_reservas").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
<div class="content">

	<table width="100%">
		 <tr>
            <td width="80%"></td>
            <td width="20%"><?php echo $this->Html->image('logo_s.jpg'); ?></td>
        </tr>
    </table>
    <h1> Planilla de reserva</h1>
    <hr/>
    <table width="100%">
        <tr>
            <td width="150"><strong>Creada: </strong> <?php echo $reserva['Reserva']['creado']; ?></td>
            <td align="center"><strong>Reservada por: </strong> <?php echo $reserva['Usuario']['nombre']." ".$reserva['Usuario']['apellido']?></td>
            <td width="100" align="right"><strong>Numero: </strong> <?php echo $reserva['Reserva']['numero']; ?></td>
        </tr>
    </table>
    <h2>Datos Titular de la Reserva</h2>
    <table width="100%">
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>Nombre y Apellido: </strong> <?php echo $reserva['Cliente']['nombre_apellido']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>DNI/Pasaporte: </strong> <?php echo $reserva['Cliente']['dni']; ?></p></td>
        </tr>
        <tr>
            <td><p style="font-size:16px;"><strong>Telefono: </strong> <?php echo $reserva['Cliente']['telefono']; ?></p></td>
            <td><p style="font-size:16px;"><strong>Celular: </strong> <?php echo $reserva['Cliente']['celular']; ?></p></td>
        </tr>
        <tr>
            <td><p style="font-size:16px;"><strong>Direccion: </strong> <?php echo $reserva['Cliente']['direccion']; ?></p></td>
            <td><p style="font-size:16px;"><strong>Localidad: </strong> <?php echo $reserva['Cliente']['localidad']; ?></p></td>
        </tr>
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>IVA: </strong> <?php echo $reserva['Cliente']['iva']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>CUIT: </strong> <?php echo $reserva['Cliente']['cuit']; ?></p></td>
        </tr>
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>Nacimiento: </strong> <?php echo $reserva['Cliente']['nacimiento']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Profesion: </strong> <?php echo $reserva['Cliente']['profesion']; ?></p></td>
        </tr>
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>Email: </strong>  <?php echo $reserva['Cliente']['email']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Domicilio en Ushuaia: </strong> <?php echo $reserva['Cliente']['domicilio_local']; ?></p></td>
        </tr>
        
        <tr>
            <td colspan="2" height="30"><p style="font-size:16px;"><em>Datos Conductor</em></p></td>
        </tr>
        
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>Nombre y Apellido: </strong> <?php echo $reserva['Cliente']['ad_nombre_apellido']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>DNI/Pasaporte: </strong> <?php echo $reserva['Cliente']['ad_dni']; ?></p></td>
        </tr>
        
        <tr>
            <td width="50%"><p style="font-size:16px;"><strong>Telefono: </strong> <?php echo $reserva['Cliente']['ad_telefono']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Email: </strong> <?php echo $reserva['Cliente']['ad_email']; ?></p></td>
        </tr>
        <tr>
            <td width="33%"><p style="font-size:16px;"><strong>Nro. Licencia de conducir: </strong> <?php echo $reserva['Cliente']['nro_licencia_de_conducir']; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Vencimiento: </strong> <?php echo $reserva['Cliente']['vencimiento']; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Lugar de emision: </strong> <?php echo $reserva['Cliente']['lugar_emision']; ?></p></td>
        </tr>
        <tr>
            <td><p style="font-size:16px;"><strong>Direccion: </strong> <?php echo $reserva['Cliente']['ad_direccion']; ?></p></td>
            <td><p style="font-size:16px;"><strong>Localidad: </strong> <?php echo $reserva['Cliente']['ad_localidad']; ?></p></td>
        </tr>
    </table>
    <h2>Datos de la reserva</h2>
    <table width="100%">
    	<tr> 
            <td width="50%"><p style="font-size:16px;"><strong>Categoria: </strong><?php echo $categoria['Categoria']['categoria']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Unidad: </strong><?php echo $reserva['Unidad']['unidad']; ?></p></td>
           
        </tr>
        <tr> 
            
            <td width="50%"><p style="font-size:16px;"><strong>Lugar Retiro: </strong><?php echo $reserva['Lugar_Retiro']['lugar']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Retiro: </strong><?php echo $reserva['Reserva']['retiro'].' '. $reserva['Reserva']['hora_retiro']; ?></p></td>
            
        </tr>
        <tr> 
            <td width="50%"><p style="font-size:16px;"><strong>Lugar Devolucion: </strong><?php echo $reserva['Lugar_Devolucion']['lugar']; ?></p></td>
            <td width="50%"><p style="font-size:16px;"><strong>Devolucion: </strong><?php echo $reserva['Reserva']['devolucion'].' '.$reserva['Reserva']['hora_devolucion']; ?></p></td>
            
        </tr>
        <tr>
            <td colspan="3"><p style="font-size:16px;"><strong>Total Pasajeros: </strong><?php echo $reserva['Reserva']['pax_adultos'] + $reserva['Reserva']['pax_menores'] ; ?> </p></td>
        </tr>
        <tr>
            <td width="33%"><p style="font-size:16px;"><strong>Mayores: </strong><?php echo $reserva['Reserva']['pax_adultos']; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Menores: </strong><?php echo $reserva['Reserva']['pax_menores']; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Bebes: </strong><?php echo $reserva['Reserva']['pax_bebes']; ?></p></td>
        </tr>
        <tr>
            <td colspan="3"><p style="font-size:16px;"><strong>Seguro contratado</strong></td>
        </tr>
        <tr>
            <td width="33%"><p style="font-size:16px;"><strong>Discover: </strong><?php echo $reserva['Reserva']['discover'] ? 'Si' : 'No'; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Discover Plus: </strong><?php echo $reserva['Reserva']['discover_plus'] ? 'Si' : 'No'; ?></p></td>
            <td width="33%"><p style="font-size:16px;"><strong>Discover Advance: </strong><?php echo $reserva['Reserva']['discover_advance'] ? 'Si' : 'No'; ?></p></td>
        </tr>
        <?php if(count($reserva['Extras']) > 0){ ?>
        <tr>
            <td colspan="3" height="30"><em>Detalle de extras contratados</em></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Detalle</strong></td>
            <td><strong>Cantidad</strong></td>
        </tr>
        <?php foreach($reserva['Extras'] as $extra){ ?>
            <tr>
                <td class="border" colspan="2"> <?php echo $extra_rubros[$extra['extra_rubro_id']]?> <?php echo $extra_subrubros[$extra['extra_subrubro_id']]?> <?php echo $extra['detalle'];?></td>
                <td class="border"><?php echo $extra['ReservaExtra']['cantidad'];?></td>
            </tr>
        <?php }} ?>
    </table>
    <?php if($reserva['Reserva']['comentarios'] != ''){ ?>
    <h2>Comentarios</h2>
    <p style="padding-left:5px;"><?php echo nl2br($reserva['Reserva']['comentarios']); ?></p>
    <?php } ?>
    <p style="font-size:16px;" align="right"><strong>Saldo Pendiente: $<?php echo ($pendiente==-0)?0:$pendiente; ?></strong></p>
</div>
<?php if(!$pdf){ ?>
<span id="botonDescargar" onclick="descargar()" class="boton guardar">Descargar</span>
<span id="botonEnviar" onclick="enviarVoucher()" class="boton guardar">Enviar</span>

<script>

function descargar(){
	document.location = "<?php echo $this->Html->url('/reservas/plantilla/'.$reserva['Reserva']['id'].'/1', true);?>";
    
}

function enviarVoucher(){
	
		
	
			
	createWindow('w_enviar_palnilla','Enviar planilla','<?php echo $this->Html->url('/reservas/formMailPlanilla/'.$reserva['Reserva']['id'], true);?>','430','400');		
			
			
		
	
}

</script>

<?php }?>