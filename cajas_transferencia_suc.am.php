<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
$user_id = $_SESSION['useridushuaia'];
$user_dni = $_SESSION['userdniushuaia'];
include_once("config/dbBsAs.php");
$sql = "SELECT * FROM usuario WHERE dni = '$user_dni'";

$rsTemp = mysqli_query($connBsAs,$sql);
$rs = mysqli_fetch_array($rsTemp);
$user_otra = $rs['id'];

if ($_POST['caja_id']) {
	$sql = "SELECT * FROM caja WHERE id = ".$_POST['caja_id'];

	$rsTemp = mysqli_query($conn,$sql);
	$rs = mysqli_fetch_array($rsTemp);
	$nombreCajaOtra = $rs['caja'];
}



include_once("functions/form.class.php");
include_once("functions/fechasql.php");
include_once("config/db.php");
include_once("functions/abm.php");
include_once("functions/util.php");

if ($_POST['origen']) {
	$sql = "SELECT * FROM caja WHERE id = ".$_POST['origen'];

	$rsTemp = mysqli_query($conn,$sql);
	$rs = mysqli_fetch_array($rsTemp);
	$nombreCaja = $rs['caja'];
}

//indicar tabla a editar
$tabla = 'caja_movimiento';

//indicar campos a editar
$campos['id'] 				= array(
								'type'				=> 'text',
								'input_type'		=> 'hidden'
							);
$campos['fecha'] 			= array(
								'type'		=> 'date',
								'label'		=> 'Fecha',
								'value'		=> date('d/m/Y')
							);
$campos['origen']			= array(

								'type'				=> 'combo',
								'label'				=> 'Caja origen',
								'tabla'				=> 'caja',
								'campo_id'			=> 'id',
								'campo'				=> 'caja',
								'sql'				=> "SELECT caja.id as id, caja.caja as caja FROM caja INNER JOIN usuario_caja ON usuario_caja.caja_id=caja.id AND usuario_caja.usuario_id=$user_id",
								'requerid'			=> true
							);
$campos['caja_id']			= array(
								'type'				=> 'comboOtraSuc',
								'label'				=> 'Caja Buenos Aires',
								'tabla'				=> 'caja',
								'campo_id'			=> 'id',
								'campo'				=> 'caja',
								'sql'				=> "SELECT caja.id as id, caja.caja as caja FROM caja INNER JOIN usuario_caja ON usuario_caja.caja_id=caja.id AND usuario_caja.usuario_id=$user_otra",
								'requerid'			=> true
							);
$campos['moneda_id']			= array(
								'type'				=> 'combo',
								'label'				=> 'Moneda',
								'tabla'				=> 'moneda',
								'campo_id'			=> 'id',
								'campo'				=> 'moneda',
								'sql'				=> "SELECT moneda.id as id, moneda.moneda as moneda FROM moneda",
								'requerid'			=> true
							);
$campos['monto']			= array(
								'type' 				=> 'text',
								'input_type'		=> 'text',
								'label' 			=> 'Monto',
								'size'				=> '5',
								'requerid' 			=> true
							);




if(($_POST['agregar'])){
	//proceso la entrada de plata a la caja
	$monto=$_POST['monto'];

	if ($_POST['moneda_id']!=1) {

		$sql = "SELECT id,monto_moneda,cambio,usados FROM caja_movimiento WHERE monto_moneda>0 AND caja_id=".$_POST['origen']." AND moneda_id = ".$_POST['moneda_id']. " ORDER BY id ASC";
		//echo $sql;
		$rsTemp = mysqli_query($conn,$sql);
		$monto=0;
		$ok=1;
		while($rs = mysqli_fetch_array($rsTemp) AND $ok){
			if ($_POST['monto']<=($rs['monto_moneda']-$rs['usados'])) {
				$monto_moneda +=$_POST['monto'];
				$cambio=$rs['cambio'];
				$monto +=($_POST['monto']*$cambio);
				//echo $monto."<br>";
				//echo $monto_moneda."<br>";
				$usados = $rs['usados']+$_POST['monto'];
				$sql_update = "UPDATE caja_movimiento SET usados = ".$usados." WHERE id = ".$rs['id'] ;
				//echo $sql_update."<br>";
				_log($sql_update);
				mysqli_query($conn,$sql_update);
				$ok=0;
			}
			else{
				$monto_moneda +=($rs['monto_moneda']-$rs['usados']);
				$cambio=$rs['cambio'];
				$monto +=(($rs['monto_moneda']-$rs['usados'])*$cambio);
				$usados_ant = $rs['usados'];//para anular la actualizaci�n en caso de no usarlos
				$usados = $rs['usados']+($rs['monto_moneda']-$rs['usados']);
				$sql_update = "UPDATE caja_movimiento SET usados = ".$usados." WHERE id = ".$rs['id'] ;
				_log($sql_update);
				//echo $monto."<br>";
				//echo $monto_moneda."<br>";
				//echo $sql_update."<br>";
				mysqli_query($conn,$sql_update);
				$_POST['monto'] -=($rs['monto_moneda']-$rs['usados']);
				$ultimo_id=$rs['id'];
			}
			//$monto=$_POST['monto']*$_POST['cambio'];
		}

		$cambio = $monto / $monto_moneda;

	}
	else{
		$monto_moneda=0;
		$cambio=0;
	}
	if ($ok) {
		$sql_update = "UPDATE caja_movimiento SET usados = ".$usados_ant." WHERE id = ".$ultimo_id ;
		_log($sql_update);
		mysqli_query($conn,$sql_update);
		$monto=0;
	}
	if ($monto!=0) {
		$time = time();

		$hora = date("H:i:s", $time);

		$fecha =fechasql($_POST['fecha']).' '.$hora;


		//mysql_select_db('discover', $conn);

		/*$sql_entra = "INSERT INTO $tabla (fecha,origen,caja_id,monto,usuario_id,moneda_id,monto_moneda,cambio)
					VALUES
					('".$fecha."','caja_".$_POST['origen']."','otra_".$_POST['caja_id']."','".$monto."',".$user_id.",'".$_POST['moneda_id']."','".$monto_moneda."','".$cambio."')";
		mysqli_query($conn,$sql_entra, $dbh2);
		_log($sql_entra);*/
		//echo mysqli_error($conn);
		//echo $sql_entra."<br>";
		//proceso la salida de plata
		$sql_sale = "INSERT INTO $tabla (fecha,origen,caja_id,monto,usuario_id,moneda_id,monto_moneda,cambio)
					VALUES
					('".$fecha."','haciaOtra_".$nombreCajaOtra."','".$_POST['origen']."','-".$monto."',".$user_id.",'".$_POST['moneda_id']."','-".$monto_moneda."','".$cambio."')";
		mysqli_query($conn,$sql_sale);
		//echo mysqli_error($conn);
		_log($sql_sale);
		//echo $sql_sale."<br>";

		//mysql_select_db('discoverbsas', $conn);

		$sql_entra = "INSERT INTO $tabla (fecha,origen,caja_id,monto,usuario_id,moneda_id,monto_moneda,cambio)
					VALUES
					('".$fecha."','cajaOtra_".$nombreCaja."','".$_POST['caja_id']."','".$monto."',".$user_otra.",'".$_POST['moneda_id']."','".$monto_moneda."','".$cambio."')";
		mysqli_query($connBsAs,$sql_entra);
		_log($sql_entra);
		//echo mysqli_error($conn);
		//echo $sql_entra."<br>";
		//proceso la salida de plata
		/*$sql_sale = "INSERT INTO $tabla (fecha,origen,caja_id,monto,usuario_id,moneda_id,monto_moneda,cambio)
					VALUES
					('".$fecha."','hacia_".$_POST['caja_id']."','otra_".$_POST['origen']."','-".$monto."',".$user_otra.",'".$_POST['moneda_id']."','-".$monto_moneda."','".$cambio."')";
		mysqli_query($conn,$sql_sale,$dbh2);
		_log($sql_sale);*/


		$result = 1;
	}
	else{
		$result = "La caja de origen no dispone de saldo suficiente para la moneda seleccionada";
	}
}

$form = new Form();
$form->setLegend('Movimientos en efectivo'); //nombre del form
$form->setAction('cajas_transferencia_suc.am.php'); //a donde hacer el post

if(isset($dataid)){

	$sql = "SELECT * FROM $tabla WHERE id=".$dataid; //traer datos
	$rsTemp = mysqli_query($conn,$sql);
	$rs = mysqli_fetch_array($rsTemp);

	foreach($campos as $clave=>$valores){
		$campos[$clave][3] = $rs[$clave];
	}

	$form->setBotonValue('Editar caja'); //leyendo del boton
	$form->setBotonName('editar');

}else{

	$form->setBotonValue('Hacer transferencia'); //leyenda del boton
	$form->setBotonName('agregarSubmit');

}

$form->setCampos($campos);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="library/jquery/jquery-1.4.2.min.js"></script>

<!--JQuery Uploadify-->
<script type="text/javascript" src="library/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript" src="library/uploadify/swfobject.js"></script>
<link href="library/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<!--/JQuery Uploadify-->

<!--JQuery editor-->
<script type="text/javascript" src="library/jwysiwyg/jquery.wysiwyg.js"></script>
<link rel="stylesheet" href="library/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
<!--/JQuery editor-->

<!--JQuery Date Picker-->
<script type="text/javascript" src="library/datepicker/date.js"></script>
<!--[if IE]><script type="text/javascript" src="library/datepicker/jquery.bgiframe.js"></script><![endif]-->
<script type="text/javascript" src="library/datepicker/jquery.datePicker.min-2.1.2.js"></script>
<link href="library/datepicker/datePicker.css" rel="stylesheet" type="text/css" />
<style>
a.dp-choose-date {
	float: left;
	width: 16px;
	height: 16px;
	padding: 0;
	margin: 5px 3px 0;
	display: block;
	text-indent: -2000px;
	overflow: hidden;
	background: url(images/calendar.png) no-repeat;
}
a.dp-choose-date.dp-disabled {
	background-position: 0 -20px;
	cursor: default;
}
/* makes the input field shorter once the date picker code
 * has run (to allow space for the calendar icon
 */
input.dp-applied {
	width: 140px;
	float: left;
}
</style>
<!--/JQuery Date Picker-->
<script>
function consultarSincronismo (caja){

	var datos = ({
		'caja_id' : caja,
		'fecha' : $('#fecha').val()
	});

	$.ajax({
		beforeSend: function(){
			$('#loading').show();
		},
		data: datos,
		url: 'functions/consultarSincronismo.php',
		success: function(data) {

			if(data == 'no'){
				alert('Por favor, realice la conciliacion y sincronice la caja sobre la que desea generar la operacion para continuar');
				$('#origen').val(0);
			}
			$('#loading').hide();

		}
	});

}

function consultarDescubierto (caja){

	var datos = ({
		'caja_id' : caja,
		'monto' :$( "#monto" ).val()
	});

	$.ajax({
		beforeSend: function(){
			$('#loading').show();
		},
		data: datos,
		url: 'functions/consultarDescubierto.php',
		success: function(data) {

			if(data == 'no'){
				alert('La caja de origen no tiene fondos suficientes');
				$('#origen').val(0);
			}
			$('#loading').hide();

		}
	});

}

function consultarSincronismoFecha (caja){

	var datos = ({
		'caja_id' : caja,
		'fecha' : $('#fecha').val()
	});

	$.ajax({
		beforeSend: function(){
			$('#loading').show();
		},
		data: datos,
		url: 'functions/consultarSincronismoFecha.php',
		success: function(data) {

			if(data == 'no'){
				alert('Movimiento no permitido: La caja se encuentra conciliada y sincronizada para la fecha del movimiento que intenta realizar. Contactar administrador');
				$('#efectivo_caja_id').val(0);
			}
			$('#loading').hide();

		}
	});

}

function consultarSincronismoFechaOtra (caja){

	var datos = ({
		'caja_id' : caja,
		'fecha' : $('#fecha').val()
	});

	$.ajax({
		beforeSend: function(){
			$('#loading').show();
		},
		data: datos,
		url: 'functions/consultarSincronismoFechaOtra.php',
		success: function(data) {

			if(data == 'no'){
				alert('Movimiento no permitido: La caja se encuentra conciliada y sincronizada para la fecha del movimiento que intenta realizar. Contactar administrador');
				$('#efectivo_caja_id').val(0);
			}
			$('#loading').hide();

		}
	});

}

$(document).ready(function() {
	$( "#origen" ).change(function() {
		consultarSincronismo ($( "#origen" ).val());
		consultarSincronismoFecha ($( "#origen" ).val());
		consultarDescubierto ($( "#origen" ).val());
    });
	$( "#caja_id" ).change(function() {

		consultarSincronismoFechaOtra ($( "#caja_id" ).val());
    });
	$( "#moneda_id" ).change(function() {
		$( "#monto" ).val(0);
		$( "#cambio" ).val(0);
    });
	$( "#monto" ).change(function() {

		consultarDescubierto ($( "#origen" ).val());
    });
});


</script>
<?php echo $form->printJS()?>

<link href="styles/form2.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include_once("config/messages.php"); ?>

<?php echo $form->printHTML()?>

</body>
</html>
