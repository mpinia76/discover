<?php
include_once("config/db.php");
include_once("functions/date.php");
include_once("functions/fechasql.php");
include_once("functions/util.php");
header("Pragma: public");
header("Expires: 0");

$filename = "formulario_DJ230_".$_POST["ano"]."_".$_POST["mes"].".xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
$meses = array('01'=>'ENERO', '02'=> 'FEBRERO', '03'=> 'MARZO', '04'=> 'ABRIL', '05'=> 'MAYO', '06'=> 'JUNIO', '07'=> 'JULIO', '08'=> 'AGOSTO', '09'=> 'SEPTIEMBRE', '10'=>'OCTUBRE', '11'=> 'NOVIEMBRE', '12'=>'DICIEMBRE');
?>
<table style="border-collapse: collapse;">
<tbody>
<tr>
<th>
</th>
</tr>
<tr>
<td></td>
<td style="width:130;border: 1px solid black;text-align:center;vertical-align: middle;" rowspan="5" ><img src="http://localhost/discover/images/cabedj320.png" alt="" align="middle"/></td>
<td colspan="4" style="width: 500;font-family: Arial; font-size: 8.5pt; font-weight: bold;border-top: 0.1pt solid black;text-align:center;">DIRECCION GENERAL DE RENTAS PROVINCIA DE TIERRA DEL FUEGO, A.e I. del A. S.</td>
<td rowspan="5" style="width: 95; font-family: Arial; font-size: 8.5pt; font-weight: bold;font-style: italic;border: 0.1pt solid black; vertical-align: middle; text-align:center;">FORMULARIO DJ230</td>
<td rowspan="8" style="width: 95; font-family: Arial; font-size: 8.5pt; border: 0.1pt solid black; vertical-align: middle; text-align:center;">SELLO Y FIRMA RECEPCION</td>
</tr>
<tr>
<td></td>

<td colspan="4" style="font-family: Arial; font-size: 8.5pt; text-align:center;"></td>
</tr>
<tr>
<td></td>

<td colspan="4" style="font-family: Arial; font-size: 8.5pt; text-align:center;">DECLARACION JURADA - IMPUESTO DE SELLOS AGENTE DE RECAUDACION - REGIMEN GENERAL </td>
</tr>
<tr>
<td></td>

<td colspan="4" style="font-family: Arial; font-size: 8.5pt; text-align:center;text-decoration: underline;">-RESOLUCION D.G.R. N&deg;_________.- ANEXO I</td>
</tr>
<tr>
<td></td>

<td colspan="4" style="font-family: Arial; font-size: 8.5pt; text-align:center;"></td>
</tr>

<tr>
<td></td>
<td style="font-family: Arial; font-size: 8.5pt; font-weight: bold;border: 0.1pt solid black;">DENOMINACION SOCIAL</td>
<td colspan="4" style="font-family: Arial;  font-size: 8.5pt; text-align:center;border: 0.1pt solid black;">VILLAGE DE LAS PAMPAS S.A.</td>
<td style="font-family: Arial; font-size: 8.5pt; border: 0.1pt solid black;text-align:center;">MES/A&Ntilde;O:</td>
</tr>
<tr>
<td></td>
<td style="font-family: Arial; font-size: 8.5pt; font-weight: bold;border: 0.1pt solid black;">CUIT N&deg;</td>
<td colspan="4" style="font-family: Arial;  font-size: 8.5pt; text-align:center;border: 0.1pt solid black;">30-70840526-0</td>
<td style="font-family: Arial; font-size: 8.5pt; border: 0.1pt solid black;text-align:center; mso-number-format:\@;" rowspan="2"><?php echo $_POST["mes"]."/".$_POST["ano"] ?></td>
</tr>
<tr>
<td></td>
<td style="font-family: Arial; font-size: 8.5pt; font-weight: bold;border: 0.1pt solid black;">N&deg; AGTE RECAUDACION</td>
<td style="font-family: Arial;  font-size: 8.5pt; text-align:center;border: 0.1pt solid black;">1070</td>
<td style="font-family: Arial; font-size: 8.5pt; font-weight: bold;border: 0.1pt solid black;">DOMICILIO :</td>
<td style="font-family: Arial;  font-size: 8.5pt; text-align:center;border: 0.1pt solid black;" colspan="2">19 N&deg;3049 (MANUEL B GONNET)</td>

</tr>

<tr>

<td></td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">FECHA DE OPERACI&Oacute;N</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">NOMBRE Y APELLIDO / RAZON SOCIAL</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">SUCURSAL-FILIAL- AGENCIA O REPRES</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">TIPO DE INSTRUMENTO</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">CUIT/DNI</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">BASE IMPONIBLE / VALOR ECONOMICO DEL INSTRUMENTO</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; font-weight: bold;border: 0.1pt solid black;vertical-align: middle;">IMPUESTO RECAUDADO</td>

</tr>
<?php $sql = "SELECT R.id, R.devolucion, R.total, C.nombre_apellido, C.cuit, C.dni, C.sexo, C.nacionalidad FROM reservas R INNER JOIN clientes C ON R.cliente_id = C.id WHERE devolucion LIKE '".$_POST["ano"]."-".$_POST["mes"]."%' ORDER BY devolucion ASC";

$rsTemp = mysqli_query($conn,$sql); echo mysqli_error($conn);
$totalGral=0;
while($rs = mysqli_fetch_array($rsTemp)){
	if($rs['estado']!='2'){
		$sql = "SELECT * FROM reserva_extras WHERE reserva_id = ".$rs['id'];

		$rsTempExtras = mysqli_query($conn,$sql); echo mysqli_error($conn);
		$no_adelantadas=0;
		while($rsExtras = mysqli_fetch_array($rsTempExtras)){
			if($rsExtras['adelantada'] != 1){
	        	$no_adelantadas = $no_adelantadas + $rsExtras['cantidad'] * $rsExtras['precio'];
	        }
		}
		$sql = "SELECT * FROM reserva_cobros WHERE reserva_id = ".$rs['id'];

		$rsTempDescuentos = mysqli_query($conn,$sql); echo mysqli_error($conn);
		$descontado=0;
		while($rsDescuentos = mysqli_fetch_array($rsTempDescuentos)){
			if($rsDescuentos['tipo'] == "DESCUENTO"){

	        	$descontado += $rsDescuentos['monto_neto'];
	        }
		}
		$total =$rs['total']+ $no_adelantadas-$descontado;
		$impuesto = $total*(1/100);
		$totalGral += $impuesto;
		/*$buscar = 'https://soa.afip.gob.ar/sr-padron/v2/personas/'.trim($rs['dni']);
		$respuesta = file_get_contents($buscar);

		$respuesta = json_decode($respuesta);

		if(($respuesta->success)&&(!$respuesta->data[1])){
			$cuit= $respuesta->data[0];
			$pre_cuit = substr ( $cuit , 0 , 2);
			$dni= substr ( $cuit , 2 , 8);
			$post_cuit = substr($cuit, -1);
			$cuit = $pre_cuit."-".$dni."-".$post_cuit;
		}
		else{
			$cuit=$rs['dni'];
		}*/
		if (($rs['sexo'])&&($rs['nacionalidad']=='Argentina')) {
			$dni = str_pad($rs['dni'], 8, "0", STR_PAD_LEFT);
			$cuit=Format_toCuil( $dni, $rs['sexo']);
		}
		else{
			$cuit=$rs['dni'];
		}


?>
<tr>
<td></td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; border: 0.1pt solid black;vertical-align: middle;"><?php echo $rs['devolucion'];?></td>
<td style="font-family: Arial; font-size: 8.5pt;  border: 0.1pt solid black;vertical-align: middle;"><?php echo utf8_decode($rs['nombre_apellido']);?></td>
<td style="font-family: Arial; font-size: 8.5pt; border: 0.1pt solid black;vertical-align: middle;">Ushuaia</td>
<td style="font-family: Arial; font-size: 8.5pt;  border: 0.1pt solid black;vertical-align: middle;">Contrato</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:center; border: 0.1pt solid black;vertical-align: middle;"><?php echo $cuit;?></td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:right; border: 0.1pt solid black;vertical-align: middle;"><?php echo trim( number_format($total, 2, ',', '') );?></td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:right; border: 0.1pt solid black;vertical-align: middle;"><?php echo trim( number_format($impuesto, 2, ',', '') );?></td>

</tr>
<?php }}?>
<tr>
<td></td>
<td colspan="6" style="font-family: Arial; font-size: 8.5pt; text-align:right; border: 0.1pt solid black;vertical-align: middle;">TOTAL</td>
<td style="font-family: Arial; font-size: 8.5pt; text-align:right; border: 0.1pt solid black;vertical-align: middle;"><?php echo trim( number_format($totalGral, 2, ',', '') );?></td>
</tr>
<tr>
<td></td>
<td colspan="7" style="font-family: Arial; font-size: 8.5pt; text-align:left; border-top: 0.1pt solid black;border-right: 0.1pt solid black;border-left: 0.1pt solid black;vertical-align: middle;">SON PESOS: ............................................................................................................................................................................................................................</td>

</tr>
<tr>
<td></td>
<td colspan="7" style="font-family: Arial; font-size: 8.5pt; text-align:left; border-right: 0.1pt solid black;border-left: 0.1pt solid black;vertical-align: middle;"></td>

</tr>
<tr>
<td></td>
<td colspan="7" style="font-family: Arial; font-size: 8.5pt; text-align:left; border-right: 0.1pt solid black;border-left: 0.1pt solid black;vertical-align: middle;">DECLARAMOS BAJO JURAMENTO QUE LAS OPERACIONES QUE SE DETALLAN EN ESTE FORMULARIO, SON LA TOTALIDAD DE LAS CORRESPONDIENTES AL MES DE <?php echo $meses[$_POST['mes']];?> DE <?php echo $_POST['ano'];?></td>

</tr>
<tr>
<td></td>
<td colspan="7" style="font-family: Arial; font-size: 8.5pt; text-align:left; border-bottom: 0.1pt solid black;border-right: 0.1pt solid black;border-left: 0.1pt solid black;vertical-align: middle;"></td>

</tr>
<tr>
<td></td>
<td style="font-family: Arial; font-size: 10pt;">FIRMA:</td>
<td style="font-family: Arial; font-size: 10pt;">............................</td>
<td style="font-family: Arial; font-size: 10pt;">ACLARACION:</td>
<td style="font-family: Arial; 10pt;" colspan="2">........................................................</td>
<td style="font-family: Arial; font-size: 10pt;">CAR&Aacute;CTER::</td>
<td style="font-family: Arial; font-size: 10pt;">.....................</td>
</tr>
</tbody>
</table>
