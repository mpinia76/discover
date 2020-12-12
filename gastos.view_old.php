<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
//print_r($_POST);
$data 	= explode(",",$_GET['dataid']);

if(is_array($data) and count($data)>1 and ($_GET['action'] == 'consultar' or $_GET['action'] == 'editar' or $_GET['action'] == 'autorizar') ){ ?>

	<p align="center">Seleccione un s&oacute;lo registro</p>

<?php }elseif(is_array($data) and count($data)>1 and ($_GET['action'] == 'abonar') or isset($_POST['datos']) ){

	$dataid = $_GET['dataid'];
	include("gastos.pagar.php");

}elseif(is_array($data) and count($data)==1){

	$dataid = $data[0];


	include_once("functions/fechasql.php");
	include_once("functions/date.php");
	include_once("functions/getProveedor.php");
	include_once("config/db.php");
	include_once("config/user.php");

	if(($_POST['aprobar'])){ //gasto aprobado
		$sql = "SELECT nro_orden FROM gasto ORDER BY nro_orden DESC LIMIT 1";
		$rs = mysqli_fetch_array(mysqli_query($conn,$sql));
		$nro_orden = $rs['nro_orden'] + 1; //obtengo el numero de orden

		$sql = "UPDATE gasto SET nro_orden=$nro_orden WHERE id=".$_POST['gasto_id'];
		mysqli_query($conn,$sql); //guardo el numero de orden

		$dataid = $_POST['gasto_id'];
		$result = 1;
		$_GET['action'] = 'abonar';

	}elseif(($_POST['desaprobar'])){ //gasto desaprobado
		$sql = "UPDATE gasto SET estado=2 WHERE id=".$_POST['gasto_id'];
		mysqli_query($conn,$sql);
		$dataid = $_POST['gasto_id'];
		$result = 1;
		$_GET['action'] = 'consultar';
	}elseif(($_POST['actualizar'])){ //actualizo algunos datos
		$iva_27 = (ISSET($_POST['iva_27'])&&($_POST['iva_27']!=''))?$_POST['iva_27']:0;
		$iva_21 = (ISSET($_POST['iva_21'])&&($_POST['iva_21']!=''))?$_POST['iva_21']:0;
		$iva_10_5 = (ISSET($_POST['iva_10_5'])&&($_POST['iva_10_5']!=''))?$_POST['iva_10_5']:0;
		$otra_alicuota = (ISSET($_POST['otra_alicuota'])&&($_POST['otra_alicuota']!=''))?$_POST['otra_alicuota']:0;
		$result = 1;
		$sql = "UPDATE gasto SET
					fecha='".fechasql($_POST['fecha'])."',
					fecha_vencimiento='".fechasql($_POST['fecha_vencimiento'])."',
					rubro_id=".$_POST['rubro'].",
					subrubro_id=".$_POST['subrubro_id'].",
					proveedor='".$_POST['proveedor']."',
					descripcion='".$_POST['descripcion']."',
					factura_nro='".$_POST['factura_nro']."',
					factura_tipo='".$_POST['factura_tipo']."',
					factura_orden='".$_POST['factura_orden']."',
					remito_nro='".$_POST['remito_nro']."',
					recibo_nro='".$_POST['recibo_nro']."',
					monto='".$_POST['monto']."',
					iva_27='".$iva_27."',
					iva_21='".$iva_21."',
					iva_10_5='".$iva_10_5."',
					otra_alicuota='".$otra_alicuota."'
				WHERE id=".$_POST['gasto_id'];

		mysqli_query($conn,$sql);
		$result = '';
		$fecha_cheque 			= $_POST['fecha_cheque'];
		$fecha_cheque_id 		= $_POST['fecha_cheque_id'];
		if ($fecha_cheque) {
			foreach($fecha_cheque as $key=>$valor){
				if((date("Y-m-d")) >= fechasql($valor)){
					$sql = "UPDATE cheque_consumo SET fecha = '".fechasql($valor)."' WHERE id = ".$fecha_cheque_id[$key];
			        mysqli_query($conn,$sql);
				}
				else $result .= 'La fecha del/los cheque/s debe ser inferior o igual a la fecha de hoy<br>';
			}
		}


		$fecha_transferencia 			= $_POST['fecha_transferencia'];
		$fecha_transferencia_id 		= $_POST['fecha_transferencia_id'];
		if ($fecha_transferencia) {
			foreach($fecha_transferencia as $key=>$valor){
				if((date("Y-m-d")) >= fechasql($valor)){
					$sql = "UPDATE transferencia_consumo SET fecha = '".fechasql($valor)."' WHERE id = ".$fecha_transferencia_id[$key];
			        mysqli_query($conn,$sql);
			    }
				else $result .= 'La fecha de la/s transferencia/s debe ser inferior o igual a la fecha de hoy<br>';
			}
		}


		$fecha_debito 			= $_POST['fecha_debito'];
		$fecha_debito_id 		= $_POST['fecha_debito_id'];
		if ($fecha_debito) {
			foreach($fecha_debito as $key=>$valor){
				if((date("Y-m-d")) >= fechasql($valor)){
					$sql = "UPDATE cuenta_movimiento SET fecha = '".fechasql($valor)."' WHERE id = ".$fecha_debito_id[$key];
			        mysqli_query($conn,$sql);
			    }
				else $result .= 'La fecha del/los debito/s debe ser inferior o igual a la fecha de hoy<br>';
			}
		}


		$fecha_efectivo 			= $_POST['fecha_efectivo'];
		$fecha_efectivo_id 		= $_POST['fecha_efectivo_id'];

		if ($fecha_efectivo) {
			$ok=1;
			foreach($fecha_efectivo as $key=>$valor){
				if(!ACCION_139){
					$sql = "SELECT caja_id
					FROM caja_sincronizada
					WHERE registro_id = ".$fecha_efectivo_id[$key];

					$rsCaja = mysqli_fetch_array(mysqli_query($conn,$sql));
					$id_caja = $rsCaja['caja_id'];


					$sql = "SELECT MAX(fecha) AS fecha
					FROM caja_movimiento
					WHERE caja_id = ".$id_caja;

					$rsCajaSincronizada = mysqli_fetch_array(mysqli_query($conn,$sql));
					$fecha_sincronizacion = $rsCajaSincronizada['fecha'];

					if ($fecha_sincronizacion.'>='.fechasql($valor)) {
						$ok=0;
						$result .="La caja se encuentra conciliada y sincronizada para la fecha del movimiento que intenta realizar. Contactar administrador";

					}
				}

				if ($ok) {
					if((date("Y-m-d")) >= fechasql($valor)){
						$sql = "UPDATE efectivo_consumo SET fecha = '".fechasql($valor)."' WHERE id = ".$fecha_efectivo_id[$key];
				        mysqli_query($conn,$sql);
				        $time = time();

						$hora = date("H:i:s", $time);

						$fecha =fechasql($valor).' '.$hora;
				        $sql = "UPDATE caja_movimiento SET fecha = '".$fecha."' WHERE registro_id = ".$fecha_efectivo_id[$key];
				        mysqli_query($conn,$sql);
				    }
					else $result .= 'La fecha del/los pago/s en efectivo debe ser inferior o igual a la fecha de hoy<br>';
				}


			}
		}


		$fecha_tarjeta 			= $_POST['fecha_tarjeta'];
		$fecha_tarjeta_id 		= $_POST['fecha_tarjeta_id'];
		if ($fecha_tarjeta) {
			foreach($fecha_tarjeta as $key=>$valor){
				if((date("Y-m-d")) >= fechasql($valor)){
					$sql = "UPDATE tarjeta_consumo SET fecha = '".fechasql($valor)."' WHERE id = ".$fecha_tarjeta_id[$key];
			        mysqli_query($conn,$sql);
			    }
				else $result .= 'La fecha del/los pago/s con tarjeta debe ser inferior o igual a la fecha de hoy<br>';
			}
		}

		$result = ($result=='')?1:$result;
		$dataid = $_POST['gasto_id'];
		$_GET['action'] = 'editar';

	}elseif(($_POST['guardar'])){ //guardo los datos extras del gasto

		$dataid = $_POST['gasto_id'];
		$_GET['action'] = 'abonar';

		if($_POST['forma_pago']=='n'){

			$result = 'Debe seleccionar al menos una forma de pago';
			$dataid = $_POST['gasto_id'];

		}elseif( ($_POST['factura_nro'] != '' and $_POST['factura_tipo'] != 'n') or $_POST['remito_nro'] != '' or $_POST['recibo_nro'] != '' ){

			$operacion_monto = $_POST['gasto_monto'];
			include("functions/comprueba_pagos.php");

			if($procesa){


				//echo $sql."<br>";
				$operacion_id[] = $dataid;
				$operacion_tipo = 'gasto';

				include("functions/procesa_pagos.php");
				if (!$error) {
					$iva_27 = (ISSET($_POST['iva_27'])&&($_POST['iva_27']!=''))?$_POST['iva_27']:0;
					$iva_21 = (ISSET($_POST['iva_21'])&&($_POST['iva_21']!=''))?$_POST['iva_21']:0;
					$iva_10_5 = (ISSET($_POST['iva_10_5'])&&($_POST['iva_10_5']!=''))?$_POST['iva_10_5']:0;
					$otra_alicuota = (ISSET($_POST['otra_alicuota'])&&($_POST['otra_alicuota']!=''))?$_POST['otra_alicuota']:0;
					$result = 1;
					$sql = "UPDATE gasto SET
								estado=1,
								factura_nro='".$_POST['factura_nro']."',
								factura_tipo='".$_POST['factura_tipo']."',
								factura_orden='".$_POST['factura_orden']."',
								remito_nro='".$_POST['remito_nro']."',
								recibo_nro='".$_POST['recibo_nro']."',
								iva_27='".$iva_27."',
								iva_21='".$iva_21."',
								iva_10_5='".$iva_10_5."',
								otra_alicuota='".$otra_alicuota."'
							WHERE id=".$_POST['gasto_id'];
					mysqli_query($conn,$sql);
				}

			}else{
				if(($operacion_monto+$monto_interes-$monto_descuento) != $monto_pagado){
					$result = 'Verifique que monto original ('.$operacion_monto.') mas los intereses ('.$monto_interes.') menos los descuentos ('.$monto_descuento.') sea igual al valor que intenta pagar ('.$monto_pagado.')';
				}elseif($fecha_error != 0){
					$result = 'La fecha ingresada no es correcta en alguna de las formas de pago';
				}elseif($error_cheque == true){
					$result = 'Debe completar el titular del cheque';
				}elseif($error_cheque_numero == true){
					$result = 'Ya existe un cheque del banco seleccionado y el numero ingresado';
				}elseif($fecha_hoy == false){
					$result = 'Le fecha de pago no puede ser posterior a hoy';
				}else{
					$result = 'No se pudo procesar la operacion';
				}
			}

		}else{
			$result = 'No se guardo, debe completar con un n&uacute;mero de recibo, remito o factura';
		}
	}


	if(isset($dataid)){
		$sql = "SELECT usuario.nombre,usuario.apellido,gasto.*,subrubro.subrubro,subrubro.id as subrubro_id,rubro.rubro,rubro.id as rubro_id FROM gasto LEFT JOIN subrubro ON gasto.subrubro_id=subrubro.id INNER JOIN usuario ON gasto.user_id=usuario.id INNER JOIN rubro ON gasto.rubro_id=rubro.id WHERE gasto.id=$dataid";
		//echo $sql;
		$rs = mysqli_fetch_array(mysqli_query($conn,$sql));

		$estado = $rs['estado'];
		$operacion_id = $dataid;
		$operacion_tipo = 'gasto';
	}
	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="STYLESHEET" type="text/css" href="styles/toolbar.css"/>
	<link href="styles/form.css" rel="stylesheet" type="text/css" />
	<script src="library/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>
	<!--JQuery UI-->
	<script src="library/jquery/ui/jquery-ui-1.8.4.custom.min.js" type="text/javascript"></script>
	<link href="library/jquery/ui/css/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
	<script src="js/combobox-autosuggest.js" type="text/javascript"></script>
	<!--/JQuery UI-->

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

	.ui-autocomplete {
		max-height: 100px;
		overflow-y: auto;
	}
	select{
		height: 21px;
	}

	</style>
	<?php
	$pro_sql = "SELECT nombre FROM proveedor ORDER BY nombre ASC";
	$pro_rsTemp = mysqli_query($conn,$pro_sql);
	while($pro_rs = mysqli_fetch_array($pro_rsTemp)){
		$proveedores[] = $pro_rs['nombre'];
	}
	if ($proveedores) {
		$proveedores = implode('", "',$proveedores);
	}

	?>
	<script>

	$(function()
	{
		$('.date-pick').datePicker().trigger('change');
        $('.date-pick.date-edit').datePicker({startDate:'01/01/2010'}).trigger('change');

		$("#proveedores").combobox();
		mostrarAlicuota("<?php echo $rs['factura_tipo']; ?>");

	});
	function mostrarAlicuota(tipo){

		if((tipo == 'A')||(tipo == 'M')){
			$('#alicuota').show();
		}
		else{
			$('#alicuota').hide();
			$('#iva_27').val('');
			$('#iva_21').val('');
			$('#iva_10_5').val('');
			$('#otra_alicuota').val('');
		}
	}
	function inicializarCalendario() {

		var ToEndDate = new Date();

		var fullDate = new Date()
		var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);

		var currentDate = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear();

		$('.dp-applied').datePicker({

		    weekStart: 1,
		    startDate: '01/01/2010',
		    endDate: currentDate,
		    autoclose: true
		})
	}

	function createCombo(tabla,campo_id,campo,value){

        var datos = ({
            'tabla' : tabla,
            'campo_id' : campo_id,
            'campo' : campo,
            'value' : value
         });

        $.ajax({
            beforeSend: function(){
                $('#combo_loading').show();
            },
            data: datos,
            url: 'functions/createcombo.php',
            success: function(data) {
                $('#combo_loading').hide();
                $('#subrubro_combo').html(data);
                $('#subrubro').show();
            }
        });
    }
	</script>
	<script type="text/javascript">
	function addFormaDePago(forma_pago_id){

		var datos = ({
			'forma_pago' : forma_pago_id,
			'pago' : 1
		});

		$.ajax({
			beforeSend: function(){
				$('#forma_pago_loading').show();
			},
			data: datos,
			url: 'functions/formadepago.php',
			success: function(data) {
				$('#forma_pago_loading').hide();
				$('#forma_de_pago').append(data);
				$('.date-pick').datePicker().trigger('change');
			}
		});
	}
	</script>
	<script language="javascript" type="text/javascript">

	function vacio(q) {
		//funcion que chequea que los campos no sean espacios en blanco
		for ( i = 0; i < q.length; i++ ) {
				if ( q.charAt(i) != " " ) {
						return true
				}
		}
	return false
	}
	$(document).ready( function() {   // Esta parte del c�digo se ejecutar� autom�ticamente cuando la p�gina est� lista.
	    $("#guardarSubmit").click( function() {     // Con esto establecemos la acci�n por defecto de nuestro bot�n de enviar.
	    	$('#guardarSubmit').val('Procesando...');
			$('#guardarSubmit').attr('disabled','disabled');
	        if(validaForm()){

	        }
	    });
	    $("#actualizarSubmit").click( function() {     // Con esto establecemos la acci�n por defecto de nuestro bot�n de enviar.
	        if(validaForm()){

	        }
	    });
	    $("#desaprobarSubmit").click( function() {     // Con esto establecemos la acci�n por defecto de nuestro bot�n de enviar.
	        if(validaForm()){

	        }
	    });
	    $("#aprobarSubmit").click( function() {     // Con esto establecemos la acci�n por defecto de nuestro bot�n de enviar.
	        if(validaForm()){

	        }
	    });
	});

	function validaForm() {
		if($("#rubro").val() == ""){
	        alert("Rubro es obligatorio");
	        $("#rubro").focus();       // Esta funci�n coloca el foco de escritura del usuario en el campo Nombre directamente.
	        return false;
	    }
		if($("#fecha").val() == ""){
	        alert("Fecha es obligatorio");
	        $("#fecha").focus();       // Esta funci�n coloca el foco de escritura del usuario en el campo Nombre directamente.
	        return false;
	    }
		if($("#monto").val() == ""){
	        alert("El monto es obligatorio");
	        $("#monto").focus();       // Esta funci�n coloca el foco de escritura del usuario en el campo Nombre directamente.
	        return false;
	    }
	     // Si todo est� correcto
				/*if(F.rubro.value == 'null') {
				alert("Rubro es obligatorio")
				F.rubro.focus();
				return false
				}
				if(vacio(F.fecha.value) == false) {
				alert("Fecha es obligatorio")
				F.fecha.focus();
				return false
				}
				if(vacio(F.monto.value) == false) {
				alert("El monto es obligatorio")
				F.monto.focus();
				return false
				}*/
				if(($("#remito_nro").val()!="")&&($("#remito_nro").val().length != 6)) {
					alert("Ingrese 6 digitos en el nro de remito")
					F.factura_nro.focus();
					return false
					}
				if(($("#recibo_nro").val()!="")&&($("#recibo_nro").val().length != 6)) {
					alert("Ingrese 6 digitos en el nro de recibo")
					F.factura_nro.focus();
					return false
					}
				if(($("#factura_tipo").val()!="n")&&($("#factura_nro").val().length != 6)) {
					alert("Ingrese 6 digitos en el nro de factura")
					F.factura_nro.focus();
					return false
				}
				if($("#monto").val()>0){
					if((($("#factura_tipo").val()=="A")||($("#factura_tipo").val()=="M"))&&($("#iva_27").val() == '')&&($("#iva_21").val() == '')&&($("#iva_10_5").val() == '')&&($("#otra_alicuota").val() == '')) {
						alert("Incluya los montos de IVA Segun la alicuota que corresponda")
						$("#iva_27").focus();
						return false
						}
					var mitadMonto = parseFloat($("#monto").val()/2);
					var totalAlicuota = parseFloat($("#iva_27").val())+parseFloat($("#iva_21").val())+parseFloat($("#iva_10_5").val())+parseFloat($("#otra_alicuota").val());
					if((totalAlicuota>mitadMonto)||($("#iva_27").val()>mitadMonto)||($("#iva_21").val()>mitadMonto)||($("#iva_10_5").val()>mitadMonto)||($("#otra_alicuota").val()>mitadMonto)){
						alert("El total de IVA no puede ser mayor al 50% del monto bruto")
						$("#iva_27").focus();
						return false
						}
				}
		<?php if($rs['nro_orden']==0 and $rs['estado']==0 and $_GET['action'] == 'autorizar'){ ?>
				$('#aprobarSubmit').val('Procesando...');
				$('#aprobarSubmit').attr('disabled','disabled');
				$('#aprobar').val('1');
				$('#desaprobarSubmit').val('Procesando...');
				$('#desaprobarSubmit').attr('disabled','disabled');
				$('#desaprobar').val('1');
				$("#idForm").submit();
		<?php }elseif($_GET['action'] == 'editar'){

			?>
			var datos = ({

				'fecha' : $("#fecha").val(),
				'fecha_vencimiento' : $("#fecha_vencimiento").val(),
			});
			$.ajax({

				data: datos,
				url: 'functions/checkFecha.php',
				dataType:"json",
				success: function(data) {

					if(data["fecha"] == 'no'){
						alert('Error en alguna de las fechas');
					}
					else{
						$('#actualizarSubmit').val('Procesando...');
						$('#actualizarSubmit').attr('disabled','disabled');
						$('#actualizar').val('1');
						$("#idForm").submit();
					}
				}
			});
		<?php }elseif($rs['nro_orden']!=0 and $rs['estado']==0 and $_GET['action'] == 'abonar'){ ?>
					$('#mensaje').html('');
					$('#mensaje').hide();

					$.ajax({

						type : 'POST',
						data: $("#idForm").serialize(),
						url: 'controlar_abono_gasto.php',
						success: function(data){

							if(data.logs){
								for(var x = 0; x < data.logs.length; x++){
									$('#mensaje').append(data.logs[x]+'<br />');
								}
								$('#mensaje').show();
								$('html,body').animate({
								    scrollTop: $("#mensaje").offset().top
								}, 200);
								$('#guardarSubmit').val('Abonar');

								$('#guardarSubmit').removeAttr( "disabled" );
								return false;
							}else{
								$('#guardar').val('1');
								$('#guardarSubmit').val('Procesando...');
								$('#guardarSubmit').attr('disabled','disabled');
								$("#idForm").submit();
							}

						}
					});



		<?php } ?>

	}

	</script>

	</head>

	<body onload="inicializarCalendario()">
	<?php if( isset($_POST['guardar']) or isset($_POST['actualizar']) or isset($_POST['aprobar']) or isset($_POST['desaprobar']) ) { ?>
	<script>
	var dhxWins = parent.dhxWins;
	dhxWins.window('w_gasto').attachURL('v2/gastos/index');
	</script>
	<?php } ?>

	<?php include_once("config/messages.php"); ?>
     <div id="mensaje" class="error" style="display:none"></div>
	<div class="formContainer">

	<form method="post" id="idForm" name="form" action="gastos.view.php">
	<input name="aprobar" id="aprobar" type="hidden" value="0">
	<input name="desaprobar" id="desaprobar" type="hidden" value="0">
	<input name="actualizar" id="actualizar" type="hidden" value="0">
	<input name="guardar" id="guardar" type="hidden" value="0">
		<input type="hidden" name="gasto_id" value="<?php echo $operacion_id?>" />
		<input type="hidden" name="gasto_monto" value="<?php echo $rs['monto']?>" />
		<fieldset>
			<legend>Detalle de gasto</legend>
			<ul class="form">
				<li><label>Estado:</label>
				<span style="background:#FFFF99;">
				<?php if($rs['estado'] == 0 and $rs['nro_orden'] == 0){ ?>
					<?php $subestado = 1; ?>
					Pendiente de autorizaci&oacute;n
				<?php }elseif($rs['estado'] == 0 and $rs['nro_orden'] != 0){ ?>
					<?php $subestado = 2; ?>
					Gasto autorizado, pendiente de pago
				<?php }elseif($rs['estado'] == 1 and $rs['nro_orden'] != 0 and $rs['factura_nro'] == ''){ ?>
					<?php $subestado = 3; ?>
					Gasto autorizado, abonado, falta numero de factura
				<?php }elseif($rs['estado'] == 1 and $rs['nro_orden'] != 0 and $rs['factura_nro'] != ''){ ?>
					<?php $subestado = 4; ?>
					Gasto autorizado, abonado, con numero de factura
				<?php }elseif($rs['estado'] == 2){ ?>
					<?php $subestado = 0; ?>
					Gasto no autorizado
				<?php } ?>
				</span>
				</li>
				<li><label>Responsable:</label><?php echo $rs['nombre']?> <?php echo $rs['apellido']?></li>
				<?php if($_GET['action'] == 'editar'  and $subestado < 3){ ?>
					<li><label>Fecha devengado:</label><input class="date-pick dp-applied" name="fecha" id="fecha" value="<?php echo fechavista($rs['fecha'])?>" /></li>
					<li><label>Fecha factura:</label><input class="date-pick dp-applied" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo fechavista($rs['fecha_vencimiento'])?>" /></li>
					<li><label>Rubro:</label>
					<select name="rubro" id="rubro" onChange="createCombo('subrubro','rubro_id','subrubro',form.rubro.options[form.rubro.selectedIndex].value);">
					<?php
					$sql2 = "SELECT id,rubro FROM rubro WHERE gastos=1 and activo=1 ORDER BY rubro";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['rubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['rubro']?></option>
					<?php } ?>
					</select> <img id="combo_loading" src="images/loading.gif" style="display:none" />
					<li id="subrubro"><label>Subrubro:</label>
						<div id="subrubro_combo">
							<select name="subrubro_id" size="1">
							<?php
							$sql2 = "SELECT id,subrubro FROM subrubro WHERE rubro_id = ".$rs['rubro_id']." AND activo=1 ORDER BY subrubro ";
							$rsTemp2 = mysqli_query($conn,$sql2);
							while($rs2 = mysqli_fetch_array($rsTemp2)){?>
							<option <?php if($rs2['id']==$rs['subrubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['subrubro']?></option>
							<?php } ?>
							</select>
						</div>
					</li>
					<li><label>Proveedor:</label>
					<select id="proveedores" name="proveedor" size="1">
					<option value="">Seleccione uno...</option>
					<?php
					$sql2 = "SELECT id,nombre FROM proveedor ORDER BY nombre ASC";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['proveedor']){ $selected = true; ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['nombre']?></option>
					<?php } ?>
					<?php if(!$selected){ ?>
					<option value="<?php echo $rs['proveedor']?>" selected="selected"><?php echo $rs['proveedor']?></option>
					<?php } ?>
					</select>
					</li>
					<li><label>Descripcion:</label><textarea name="descripcion"><?php echo $rs['descripcion']?></textarea></li>
					<li><label>Remito:</label><input type="text" name="remito_nro" id="remito_nro" value="<?php echo $rs['remito_nro']?>" /></li>
					<li><label>Recibo:</label><input type="text" name="recibo_nro" id="recibo_nro" value="<?php echo $rs['recibo_nro']?>" /></li>
					<?php if(($subestado == 2)||($subestado == 1)){ ?>
						<li><label>Factura:</label>
						<select size="1" name="factura_tipo" id="factura_tipo" onchange="mostrarAlicuota($(this).val())">
						<option value="n" <?php if($rs['factura_tipo']=='n'){ ?> selected="selected" <?php } ?>>Tipo</option>
						<option value="A" <?php if($rs['factura_tipo']=='A'){ ?> selected="selected" <?php } ?>>A</option>
						<option value="B" <?php if($rs['factura_tipo']=='B'){ ?> selected="selected" <?php } ?>>B</option>
						<option value="C" <?php if($rs['factura_tipo']=='C'){ ?> selected="selected" <?php } ?>>C</option>
						<option value="E" <?php if($rs['factura_tipo']=='E'){ ?> selected="selected" <?php } ?>>E</option>
						<option value="M" <?php if($rs['factura_tipo']=='M'){ ?> selected="selected" <?php } ?>>M</option>
					</select>
					<select size="1" name="factura_orden">
						<option value="B" <?php if($rs['factura_orden']=='B'){ ?> selected="selected" <?php } ?>>0001</option>
						<option value="N" <?php if($rs['factura_orden']=='N'){ ?> selected="selected" <?php } ?>>0002</option>
					</select>
					<input type="text" name="factura_nro" id="factura_nro" value="<?php echo $rs['factura_nro']?>"/></li>
					<?php } ?>

				<?php }elseif( ($_GET['action'] == 'editar' and $subestado == 3)) { ?>
					<li><label>Fecha devengado:</label><input class="date-pick date-edit dp-applied" name="fecha" id="fecha" value="<?php echo fechavista($rs['fecha'])?>" /></li>
					<li><label>Fecha factura:</label><input class="date-pick date-edit dp-applied" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo fechavista($rs['fecha_vencimiento'])?>" /></li>
					<li><label>Rubro:</label>
					<select name="rubro" onChange="createCombo('subrubro','rubro_id','subrubro',form.rubro.options[form.rubro.selectedIndex].value);;">
					<?php
					$sql2 = "SELECT id,rubro FROM rubro WHERE gastos=1 and activo=1 ORDER BY rubro";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['rubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['rubro']?></option>
					<?php } ?>
					</select> <img id="combo_loading" src="images/loading.gif" style="display:none" />
					<li id="subrubro"><label>Subrubro:</label>
						<div id="subrubro_combo">
							<select name="subrubro_id" size="1">
							<?php
							$sql2 = "SELECT id,subrubro FROM subrubro WHERE rubro_id = ".$rs['rubro_id']." AND activo=1 ORDER BY subrubro ";
							$rsTemp2 = mysqli_query($conn,$sql2);
							while($rs2 = mysqli_fetch_array($rsTemp2)){?>
							<option <?php if($rs2['id']==$rs['subrubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['subrubro']?></option>
							<?php } ?>
							</select>
						</div>
					</li>
					<li><label>Proveedor:</label>
					<select id="proveedores" name="proveedor" size="1">
					<option value="">Seleccione uno...</option>
					<?php
					$sql2 = "SELECT id,nombre FROM proveedor ORDER BY nombre ASC";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['proveedor']){ $selected = true; ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['nombre']?></option>
					<?php } ?>
					<?php if(!$selected){ ?>
					<option value="<?php echo $rs['proveedor']?>" selected="selected"><?php echo $rs['proveedor']?></option>
					<?php } ?>
					</select>
					</li>
					<li><label>Descripcion:</label><textarea name="descripcion"><?php echo $rs['descripcion']?></textarea></li>
					<li><label>Remito:</label><input type="text" name="remito_nro" id="remito_nro" value="<?php echo $rs['remito_nro']?>" maxlength="6"/></li>
					<li><label>Recibo:</label><input type="text" name="recibo_nro" id="recibo_nro" value="<?php echo $rs['recibo_nro']?>" maxlength="6"/></li>
					<li><label>Factura:</label>
					<select size="1" name="factura_tipo" id="factura_tipo" onchange="mostrarAlicuota($(this).val())">
						<option value="n" <?php if($rs['factura_tipo']=='n'){ ?> selected="selected" <?php } ?>>Tipo</option>
						<option value="A" <?php if($rs['factura_tipo']=='A'){ ?> selected="selected" <?php } ?>>A</option>
						<option value="B" <?php if($rs['factura_tipo']=='B'){ ?> selected="selected" <?php } ?>>B</option>
						<option value="C" <?php if($rs['factura_tipo']=='C'){ ?> selected="selected" <?php } ?>>C</option>
						<option value="E" <?php if($rs['factura_tipo']=='E'){ ?> selected="selected" <?php } ?>>E</option>
						<option value="M" <?php if($rs['factura_tipo']=='M'){ ?> selected="selected" <?php } ?>>M</option>
					</select>
					<select size="1" name="factura_orden">
						<option value="B" <?php if($rs['factura_orden']=='B'){ ?> selected="selected" <?php } ?>>0001</option>
						<option value="N" <?php if($rs['factura_orden']=='N'){ ?> selected="selected" <?php } ?>>0002</option>
					</select>
					<input type="text" name="factura_nro" id="factura_nro" value="<?php echo $rs['factura_nro']?>"/></li>
				<?php }elseif($_GET['action'] == 'abonar' and $subestado == 2){ ?>
					<li><label>Fecha devengado:</label><?php echo fechavista($rs['fecha'])?></li>
					<input type="hidden" name="fecha" value="<?php echo fechavista($rs['fecha'])?>" />
					<li><label>Fecha factura:</label><?php echo fechavista($rs['fecha'])?></li>
					<input type="hidden" name="fecha_vencimiento" value="<?php echo fechavista($rs['fecha_vencimiento'])?>" />
					<li><label>Rubro:</label><?php echo $rs['rubro']?></li>
					<input type="hidden" name="rubro" value="<?php echo $rs['rubro_id']?>" />
					<li><label>Sububro:</label><?php echo $rs['subrubro']?></li>
					<input type="hidden" name="subrubro_id" value="<?php echo $rs['subrubro_id']?>" />
					<li><label>Proveedor:</label><?php echo getProveedor($rs['proveedor'])?></li>
					<input type="hidden" name="proveedor" value="<?php echo getProveedor($rs['proveedor'])?>" />
					<li><label>Descripcion:</label><?php echo $rs['descripcion']?></li>
					<input type="hidden" name="descripcion" value="<?php echo $rs['descripcion']?>" />
					<li><label>Remito:</label><input type="text" name="remito_nro" id="remito_nro" value="<?php echo $rs['remito_nro']?>" maxlength="6"/></li>
					<li><label>Recibo:</label><input type="text" name="recibo_nro" id="recibo_nro" value="<?php echo $rs['recibo_nro']?>" maxlength="6"/></li>
					<li><label>Factura:</label>
					<select size="1" name="factura_tipo" id="factura_tipo" onchange="mostrarAlicuota($(this).val())">
						<option value="n" <?php if($rs['factura_tipo']=='n'){ ?> selected="selected" <?php } ?>>Tipo</option>
						<option value="A" <?php if($rs['factura_tipo']=='A'){ ?> selected="selected" <?php } ?>>A</option>
						<option value="B" <?php if($rs['factura_tipo']=='B'){ ?> selected="selected" <?php } ?>>B</option>
						<option value="C" <?php if($rs['factura_tipo']=='C'){ ?> selected="selected" <?php } ?>>C</option>
						<option value="E" <?php if($rs['factura_tipo']=='E'){ ?> selected="selected" <?php } ?>>E</option>
						<option value="M" <?php if($rs['factura_tipo']=='M'){ ?> selected="selected" <?php } ?>>M</option>
					</select>
					<select size="1" name="factura_orden">
						<option value="B" <?php if($rs['factura_orden']=='B'){ ?> selected="selected" <?php } ?>>0001</option>
						<option value="N" <?php if($rs['factura_orden']=='N'){ ?> selected="selected" <?php } ?>>0002</option>
					</select>
					<input type="text" name="factura_nro" id="factura_nro" value="<?php echo $rs['factura_nro']?>"/></li>
				<?php }elseif($_GET['action'] == 'editar' and $subestado == 4){ ?>
					<li><label>Fecha devengado:</label><input class="date-pick date-edit dp-applied" name="fecha" id="fecha" value="<?php echo fechavista($rs['fecha'])?>" /></li>
					<li><label>Fecha factura:</label><input class="date-pick date-edit dp-applied" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo fechavista($rs['fecha_vencimiento'])?>" /></li>
					<li><label>Rubro:</label>
					<select name="rubro" onChange="createCombo('subrubro','rubro_id','subrubro',form.rubro.options[form.rubro.selectedIndex].value);;">
					<?php
					$sql2 = "SELECT id,rubro FROM rubro WHERE gastos=1 and activo=1 ORDER BY rubro";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['rubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['rubro']?></option>
					<?php } ?>
					</select> <img id="combo_loading" src="images/loading.gif" style="display:none" />
					<li id="subrubro"><label>Subrubro:</label>
						<div id="subrubro_combo">
							<select name="subrubro_id" size="1">
							<?php
							$sql2 = "SELECT id,subrubro FROM subrubro WHERE rubro_id = ".$rs['rubro_id']." AND activo=1 ORDER BY subrubro ";
							$rsTemp2 = mysqli_query($conn,$sql2);
							while($rs2 = mysqli_fetch_array($rsTemp2)){?>
							<option <?php if($rs2['id']==$rs['subrubro_id']){ ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['subrubro']?></option>
							<?php } ?>
							</select>
						</div>
					</li>
					<li><label>Proveedor:</label>
					<select id="proveedores" name="proveedor" size="1">
					<option value="">Seleccione uno...</option>
					<?php
					$sql2 = "SELECT id,nombre FROM proveedor ORDER BY nombre ASC";
					$rsTemp2 = mysqli_query($conn,$sql2);
					while($rs2 = mysqli_fetch_array($rsTemp2)){?>
					<option <?php if($rs2['id']==$rs['proveedor']){ $selected = true; ?> selected="selected" <?php } ?> value="<?php echo $rs2['id']?>"><?php echo $rs2['nombre']?></option>
					<?php } ?>
					<?php if(!$selected){ ?>
					<option value="<?php echo $rs['proveedor']?>" selected="selected"><?php echo $rs['proveedor']?></option>
					<?php } ?>
					</select>
					</li>
					<li><label>Descripcion:</label><textarea name="descripcion"><?php echo $rs['descripcion']?></textarea></li>
					<li><label>Remito:</label><input type="text" name="remito_nro" id="remito_nro" value="<?php echo $rs['remito_nro']?>" maxlength="6"/></li>
					<li><label>Recibo:</label><input type="text" name="recibo_nro" id="recibo_nro" value="<?php echo $rs['recibo_nro']?>" maxlength="6"/></li>
					<li><label>Factura:</label>

					<select size="1" name="factura_tipo" id="factura_tipo" onchange="mostrarAlicuota($(this).val())">
						<option value="n"  <?php if($rs['factura_tipo'] == "n") echo"selected";?> >Tipo</option>
						<option value="A" <?php if($rs['factura_tipo'] == "A") echo"selected";?> >A</option>
						<option value="B" <?php if($rs['factura_tipo'] == "B") echo"selected";?> >B</option>
						<option value="C" <?php if($rs['factura_tipo'] == "C") echo"selected";?> >C</option>
						<option value="E" <?php if($rs['factura_tipo']=='E'){ ?> selected="selected" <?php } ?>>E</option>
						<option value="M" <?php if($rs['factura_tipo']=='M'){ ?> selected="selected" <?php } ?>>M</option>
					</select>
					<select size="1" name="factura_orden">
						<option value="B" <?php if($rs['factura_orden'] == "B") echo"selected";?> >0001</option>
						<option value="N" <?php if($rs['factura_orden'] == "N") echo"selected";?> >0002</option>
					</select>
					<input type="text" name="factura_nro" id="factura_nro" value="<?php echo $rs['factura_nro']?>" /></li>
				<?php }elseif($subestado > 0){ ?>
					<li><label>Fecha devengado:</label><?php echo fechavista($rs['fecha'])?></li>
					<input type="hidden" name="fecha" value="<?php echo fechavista($rs['fecha'])?>" />
					<li><label>Fecha factura:</label><?php echo fechavista($rs['fecha_vencimiento'])?></li>
					<input type="hidden" name="fecha" value="<?php echo fechavista($rs['fecha'])?>" />
					<li><label>Rubro:</label><?php echo $rs['rubro']?></li>
					<input type="hidden" name="rubro" value="<?php echo $rs['rubro_id']?>" />
					<li><label>Sububro:</label><?php echo $rs['subrubro']?></li>
					<input type="hidden" name="subrubro_id" value="<?php echo $rs['subrubro_id']?>" />
					<li><label>Proveedor:</label><?php echo getProveedor($rs['proveedor'])?></li>
					<input type="hidden" name="proveedor" value="<?php echo getProveedor($rs['proveedor'])?>" />
					<li><label>Descripcion:</label><?php echo $rs['descripcion']?></li>
					<input type="hidden" name="descripcion" value="<?php echo $rs['descripcion']?>" />
					<li><label>Remito:</label><?php echo $rs['remito_nro']?></li>
					<input type="hidden" name="remito_nro" value="<?php echo $rs['remito_nro']?>" />
					<li><label>Recibo::</label><?php echo $rs['recibo_nro']?></li>
					<input type="hidden" name="recibo_nro" value="<?php echo $rs['recibo_nro']?>" />
					<?php if($subestado > 0){ ?>
					<li><label>Factura:</label>Tipo: <?php echo $rs['factura_tipo']?> Numero: <?php echo $rs['factura_nro']?></li>
					<input type="hidden" name="factura_orden" value="<?php echo $rs['factura_orden']?>" />
					<input type="hidden" name="factura_tipo" value="<?php echo $rs['factura_tipo']?>" />
					<input type="hidden" name="factura_nro" value="<?php echo $rs['factura_nro']?>" />
					<?php } ?>
				<?php } ?>
				<?php if($_GET['action'] == 'editar' and $subestado < 3 and ACCION_39){ ?>
					<li><label>Monto bruto:</label><input type="text" name="monto" id="monto" value="<?php echo $rs['monto']?>" /></li>
				<?php }else{ ?>
					<li><label>Monto bruto:</label>$<?php echo $rs['monto']?></li>
                    <input type="hidden" name="monto" value="<?php echo $rs['monto']?>" />
				<?php } ?>
				<span id="alicuota" style="display:none">
				<li><label>IVA 27%:</label><input name="iva_27" id="iva_27" value="<?php echo ($rs['iva_27']!=0)?$rs['iva_27']:"";?>" size="3"/></li>
				<li><label>IVA 21%:</label><input name="iva_21" id="iva_21" value="<?php echo ($rs['iva_21']!=0)?$rs['iva_21']:"";?>" size="3"/></li>
				<li><label>IVA 10.5%:</label><input name="iva_10_5" id="iva_10_5" value="<?php echo ($rs['iva_10_5']!=0)?$rs['iva_10_5']:"";?>" size="3"/></li>
				<li><label>Otra al&iacute;cuota:</label><input name="otra_alicuota" id="otra_alicuota" value="<?php echo ($rs['otra_alicuota']!=0)?$rs['otra_alicuota']:"";?>" size="3"/></li>
				</span>

	<?php if($rs['nro_orden']==0 and $rs['estado']==0 and $_GET['action'] == 'autorizar'){ ?>

			</ul>
		</fieldset>
		<p align="center"><input type="button" value="Aprobar gasto" name="aprobarSubmit" id="aprobarSubmit" /> <input type="button" value="Desaprobar gasto" name="desaprobarSubmit" id="desaprobarSubmit" /></p>
	</form>

	<?php }elseif($rs['estado']==0 and $_GET['action'] == 'autorizar' and $rs['nro_orden']!=0){ ?>
			</ul>
		</fieldset>
		<input type="hidden" name="gasto_id" value="<?php echo $operacion_id?>" />
	</form>

	<?php }elseif($_GET['action'] == 'editar'){ ?>
			<?php if($subestado >= 3) { include("pagos.view.php"); } ?>
			</ul>
		</fieldset>
		<p align="center"><input type="button" value="Actualizar datos" name="actualizarSubmit" id="actualizarSubmit" /></p>
	</form>

	<?php }elseif($rs['nro_orden']!=0 and $rs['estado']==0 and $_GET['action'] == 'abonar'){ ?>

				<li><label>Forma de pago:</label>
				<select name="forma_pago">
				<option value="n">Seleccionar...</option>
				<?php
				$sql = "SELECT id,forma_pago FROM forma_pago ORDER BY forma_pago";
				$rsTemp = mysqli_query($conn,$sql);
				while($rs = mysqli_fetch_array($rsTemp)){?>
				<option value="<?php echo $rs['id']?>"><?php echo $rs['forma_pago']?></option>
				<?php } ?>
				</select> &nbsp; <a style="cursor:pointer; color:#0000FF; text-decoration:underline;" onClick="addFormaDePago(form.forma_pago.options[form.forma_pago.selectedIndex].value)">agregar</a> <img id="forma_pago_loading" src="images/loading.gif" style="display:none" /></li>
				<div id="forma_de_pago"></div>
			</ul>
		</fieldset>
		<p align="center"><input type="button" value="Abonar" name="guardarSubmit" id="guardarSubmit" /></p>
	</form>

	<?php }elseif($rs['nro_orden']!=0 and $rs['estado']==1 and ($_GET['action'] == 'consultar' or $_GET['action'] == 'autorizar' or $_GET['action'] == 'abonar') ){ ?>
				<?php include("pagos.view.php") ?>
			</ul>
		</fieldset>
	</form>

	<?php }elseif($rs['estado']==2){ ?>
			</ul>
		</fieldset>
		<input type="hidden" name="gasto_id" value="<?php echo $operacion_id?>" />
		<p align="center">Este gasto fue desaprobado por administraci&oacute;n</p>
	</form>

	<?php }elseif(($rs['plan_id'])and ($_GET['action'] == 'consultar')){

		$sql = "SELECT * FROM plans WHERE id=".$rs['plan_id'];
		//echo $sql;
		$rsTemp = mysqli_query($conn,$sql);
		if(mysqli_num_rows($rsTemp)>0){
		if($rsPlan = mysqli_fetch_array($rsTemp)){
		?>
		<li><h3>Plan de pago</h3></li>
		<li><label>Plan:</label><?php echo $rsPlan['plan']?> </li>


		<li><label>Deuda original:</label>$<?php echo $rsPlan['monto']?></li>
		<li><label>Interes:</label>$<?php echo $rsPlan['intereses']?></li>

		<li><label>Cantidad de cuotas:</label><?php echo $rsPlan['cuotas']?></li>
		<li><label>Cuota mensual:</label>$<?php echo number_format(($rsPlan['monto']+$rsPlan['intereses'])/$rsPlan['cuotas'], 2, '.', '');?></li>

		<?php }} ?>





			</ul>
		</fieldset>

	</form>

	<?php } ?>
	</div>
	</body>
	</html>
<?php } ?>
