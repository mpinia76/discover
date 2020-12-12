<?php

//require("favoritas.common.php");

include("preguntas.inc.php");




echo '<?xml version="1.0" encoding="UTF-8"?>';



?>

<?php



include_once("config/db.php");






//funciones utiles

function fechasql($fecha){

	$part=explode("/",$fecha);

	$mysql=$part[2]."-".$part[1]."-".$part[0];

	return $mysql;

}

function fechavista($fecha){
	$part=explode("-",$fecha);
	$mysql=$part[2]."/".$part[1]."/".$part[0];
	return $mysql;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Page-Enter" content="revealTrans(Duration=1, Transition=5)" >

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Discover - Rent a car - Encuesta de satisfacci&oacute;n</title>

<?php //$xajax->printJavascript(); ?>



<link rel="stylesheet" type="text/css" media="all" href="../admin/css/style.css" />


<link rel="stylesheet" type="text/css" media="all" href="../admin/css/niceforms-default.css" />



<style type="text/css">
button::-moz-focus-inner, input::-moz-focus-inner {

    border: 0;
    padding: 0;

}
button::-moz-focus-inner, input::-moz-focus-inner {

    padding: 0;
    border: 0;

}
::before, ::after {

    -webkit-box-sizing: border-box;
    box-sizing: border-box;

}
.cat-agregar {
            width: 180px !important;
            padding: 15px !important;
            border: 0;
            background: #359cdf !important;
            color: #fff !important;
            float: center !important;
            text-align: center !important;
            font-size: 14px !important;
            margin-top: 20px !important;
            font-weight: 400 !important;
            letter-spacing: 1px !important;
            height: auto !important;
            line-height: 10px !important;
            text-transform: none !important;
            cursor: pointer;
        }
.titulo_formulario {

	font-family: Arial, Helvetica, sans-serif;

	font-size: x-large;

	color: #AAA;

	}

.titulo_principal {

	font-family: Arial, Helvetica, sans-serif;

	border: 1px solid #CCC;

	font-size:1.8em;

	font-weight:bold;

	color:#2a9ed9;

	background-color:#FFF;

	line-height: 1.8em;

	}

.titulo_secundario {

	font-family: Arial, Helvetica, sans-serif;

	font-size:small;

	}

.comentario {

	font-family: Arial, Helvetica, sans-serif;

	font-size:medium;

	font-style:italic;

	color:#fff;

	font-weight: bold;

	}

table {

	border: 1px solid #CCCCCC;

	}

.itemdetabla-1{

	font-family: Arial, Helvetica, sans-serif;

	font-size:small;

	line-height: 1.8em;

	padding-left: .5em;

	background-color:#FFF;

	color:#333333;

	font-weight:normal;

	}

.itemdetabla-2{

	font-family: Arial, Helvetica, sans-serif;

	font-size:small;

	line-height: 1.8em;

	padding-left: .5em;

	background-color:#EEE;

	color:#333333;

	font-weight:normal;

	}

.controldetabla-1{

	line-height: 1.8em;

	background-color:#FFF;

	}

.controldetabla-2{

	line-height: 1.8em;

	background-color:#EEE;

	}

#contenedor{

	width:70%;

	margin-left:auto;

	margin-right:auto;

	padding:15px;

	border: 2px solid #CCCCCC;

	background-color:#FFF;

	overflow-y:scroll;

	overflow-x:hidden;

	height: 50%;

	/*position:relative;*/

	position:absolute;

	top:25%;

	right:15%;

	z-index:10;

	}

.mensaje {

	position:absolute;

	top:100px;

	left:90px;

	padding:15px;

	width:320px;

	background-color:#009091;

	border:solid 4px #FFFFFF;

	color:#FFFFFF;

	z-index:10;

	font:normal 12px arial;

	text-align:center;

}

.mensaje .cerrar {

	font:bold 13px arial;

	color: #FFFF33;

	cursor:pointer;

	text-decoration:none;

}

</style>

<!--JQuery Date Picker-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>



<!--[if IE]><script type="text/javascript" src="../js/jquery.bgiframe.js"></script><![endif]-->





<script language="javascript" type="text/javascript">



function valida(F) {


	 	if(F.unidad.value=="n") {
	        alert("Por favor seleccione el unidad")
			window.document.form.unidad.focus();
	        return false
	        }




}

</script>

<style type="text/css">



a.dp-choose-date {

	float: left;

	width: 16px;

	height: 16px;

	padding: 0;

	margin: 5px 3px 0;

	display: block;

	text-indent: -2000px;

	overflow: hidden;

	background: url(../js/calendar.png) no-repeat;

}

a.dp-choose-date.dp-disabled {

	background-position: 0 -20px;

	cursor: default;

}

input.dp-applied {

	width: 70px;

	float: left;

}





.page-heading {

              background-color: #2a9ed9;

			  background-image: url(https://www.discoverushuaia.com.ar/tienda/wp-content/uploads/2016/09/bg-home-v3-b-1.jpg);

			  background-size: cover;

			  background-repeat: no-repeat;

			  color:#fff;

			  }

</style>

<!--/JQuery Date Picker-->

</head>



<body class="page-heading">



<img src="../img/logo_blanco.png" alt="Rent a Car" width="200" height="61" class="logo-fixed" /></a> </div></div>

<?php if(isset($_POST['enviar'])){

	$sql="SELECT id FROM encuesta WHERE id='".$_POST['id']."'";

	$rsTemp = mysqli_query($conn,$sql);

	if(mysqli_affected_rows($conn)==1){


		//cargo el cliente en la bd

		/*$sql="SELECT id FROM clientes WHERE email='".$_POST['email']."'";

		$rsTemp = mysqli_query($conn,$sql);

		if(mysqli_affected_rows($conn)==1){

			$rs = mysqli_fetch_array($rsTemp);

			$cliente_id = $rs['id'];

			$sql="UPDATE clientes SET

					nombre = '".$_POST['nombre']."',

					apellido = '".$_POST['apellido']."',

					email = '".$_POST['email']."',

					telefono = '".$_POST['cod_area']." ".$_POST['telefono']."',

					tipodoc = '".$_POST['tipodoc']."',

					nrodoc = '".$_POST['nrodoc']."',

					pais = '".$_POST['pais']."',

					provincia = '".$_POST['provincia']."',

					ciudad = '".$_POST['ciudad']."',

					calle = '".$_POST['calle']."',

					nropuerta = '".$_POST['nropuerta']."'

				WHERE email = '".$_POST['email']."'";

			mysqli_query($conn,$sql);







		}*/
		//$persona = $_POST['nombre_apellido']." ha completado una encuesta de satisfaccion.  \r\n \r\n";


		//guardo la encuesta

		$sql="UPDATE encuesta SET
					respondida = '1',
					comentarios_buenos = '".$_POST['comentarios_buenos']."',

					comentarios_malos = '".$_POST['comentarios_malos']."'



				WHERE id = '".$_POST['id']."'";

			mysqli_query($conn,$sql);


		//echo $sql;

		//$estadia = "Los datos ingresados son de su alquiler desde el ".$_POST['retiro']." hasta el ".$_POST['devolucion']." a la unidad ".$_POST['unidad']." \r\n \r\n";







		$encuesta_id=$_POST['id'];



		$letras="abcdefghijklmnopq";
		$total=0;
		$contestadas=0;
		for($i=1; $i<=10; $i++){

			/*if($i==7){

				for($j=0; $j<strlen($letras); $j++){

					if(isset($_POST[$i.$letras[$j]])){

						$sql = "INSERT INTO `encuesta_respuestas`

							(`encuesta_id`,`pregunta_id`,`valor`,`extra`)

							VALUES

							('".$encuesta_id."', '".$i.$letras[$j]."', '".$_POST[$i.$letras[$j]]."','')";

						$resultado.=$pregunta[$i.$letras[$j]].": ".$_POST[$i.$letras[$j]]." \r\n";



						mysqli_query($conn,$sql);

					}

				}

			}else{*/

				if(isset($_POST[$i])){
					$sql = "DELETE  FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$encuesta_id."' AND `pregunta_id` = '".$i."'";


						//echo $sql."<br>";

						mysqli_query($conn,$sql);

					$sql = "INSERT INTO `encuesta_respuestas`

							(`encuesta_id`,`pregunta_id`,`valor`,`extra`)

							VALUES

							('".$encuesta_id."', '".$i."', '".$_POST[$i]."','".$_POST[$i.$_POST[$i]."_extra"]."')";





					mysqli_query($conn,$sql);

				}

			//}

		}




	}





	?>

	<div id="gracias">

	<p align="center" class="titulo_principal"><?php echo "Encuesta modificada con &Eacute;xito"?></p>

	</div>



<?php }else{
	$sql = "SELECT encuesta.id AS idEncuesta,reservas.id, encuesta.password, clientes.nombre_apellido, clientes.dni, clientes.email, reservas.retiro, reservas.devolucion,
	unidads.marca, unidads.id as id_unidad, unidads.modelo, clientes.nacionalidad, clientes.localidad, clientes.direccion, clientes.telefono, clientes.celular, encuesta.comentarios_buenos, encuesta.comentarios_malos
	FROM encuesta INNER JOIN reservas ON encuesta.reserva_id = reservas.id
	INNER JOIN clientes ON reservas.cliente_id = clientes.id
	INNER JOIN unidads ON reservas.unidad_id = unidads.id
	WHERE encuesta.reserva_id = ".$_GET['id'];
	//echo $sql;
	$rsTemp1 = mysqli_query($conn,$sql);
	//print_r($rsTemp1);
	if ($rs = mysqli_fetch_array($rsTemp1)){
			//echo $_POST['username']." == ".$rs['email'] ." and ". md5($_POST['password'])." == ".$rs['password'];
			//if ($_POST['username']==$rs['email'] and md5($_POST['password'])==$rs['password']){


				?>

	<div id="formulario">

	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="form" onSubmit="return valida(this);">

	<input type="hidden" id="id" name="id" value="<?php echo $rs['idEncuesta']?>"/>
	<input type="hidden" id="retiro" name="retiro" value="<?php echo fechavista($rs['retiro']);?>"/>
	<input type="hidden" id="devolucion" name="devolucion" value="<?php echo fechavista($rs['devolucion']);?>"/>
	<!--  <input type="hidden" id="unidad" name="unidad" value="<?php echo trim($rs['marca']).' '.trim($rs['modelo']);?>"/>-->
	<input type="hidden" id="email" name="email" value="<?php echo trim($rs['email']);?>"/>
	<input type="hidden" id="nombre_apellido" name="nombre_apellido" value="<?php echo trim($rs['nombre_apellido']);?>"/>

	<div id="datos_personales">



		<p class="titulo_secundario">

		<div class="titulo_secundario" style="float:left; margin-top:3px;">Fecha de retiro del veh&iacute;culo: &nbsp;<strong><?php echo fechavista($rs['retiro'])?></strong></div>

		<div class="titulo_secundario" style="float:left; margin-top:3px;"> &nbsp; devoluci&oacute;n: &nbsp; <strong><?php echo fechavista($rs['devolucion'])?></strong></div>

		<div class="titulo_secundario" style="float:left; margin-top:3px;"> &nbsp; Unidad elegida: </div>
		<select size="1" name="unidad">
		<option value="n">Seleccionar...</option>
		<?php
		$sql = "SELECT DISTINCT marca, modelo FROM unidads";
		$rsTempUnidad = mysqli_query($conn,$sql);
		while($rsUnidad = mysqli_fetch_array($rsTempUnidad)){
			$unidadSeleccionada = ($rsUnidad['marca'].$rsUnidad['modelo']==$rs['marca'].$rs['modelo'])?' selected=\'selected\' ':'';
		?>
		<option value="<?php echo $rsUnidad['marca'].' '.$rsUnidad['modelo']?>" <?php echo $unidadSeleccionada?>><?php echo $rsUnidad['marca'].' '.$rsUnidad['modelo']?></option>
		<?php } ?>
		</select>


		</p>

		<p class="titulo_secundario">

		Nombre y Apellido: <strong><?php echo trim($rs['nombre_apellido']);?></strong>



	</div>

	<p class="comentario">Seleccione una opci&oacute;n para cada respuesta (1=insatisfecho, 5=Muy satisfecho)</p>

	<div id="encuesta">

	<p class="titulo_secundario"><strong>1. Sobre su reserva</strong></p>

	  <p class="titulo_secundario"><strong>&iquest;Quienes lo acompa&ntilde;aron en su viaje?</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=1";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="1" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>Solo<br />

		<input name="1" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>Pareja<br />

		<input name="1" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>Familia<br />



	  </p>

	  <p class="titulo_secundario"><strong>Relaci&oacute;n Calidad/Precio de su auto.(Califique de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=2";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="2" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="2" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="2" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="2" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="2" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  </p>

	  <p class="titulo_secundario"><strong>Amabilidad y tiempo de respuesta  del personal comercial al realizar su reserva (Califique de 1 a 5)</strong><br>
		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=3";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>


		<input name="3" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="3" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="3" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="3" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="3" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  </p>

	  <p class="titulo_secundario"><strong>2. Sobre el coche</strong></p>

		<p class="titulo_secundario"><strong>Limpieza  del coche al momento de la entrega (Califique de 1 a 5)</strong><br>
		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=4";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>


		<input name="4" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="4" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="4" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="4" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="4" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  	<p class="titulo_secundario"><strong>Estado General del coche. (califique de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=5";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="5" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="5" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="5" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="5" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="5" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  <p class="titulo_secundario"><strong>3. Sobre nuestra  empresa. </strong></p>



		<p class="titulo_secundario"><strong>Facilidad para encontrarnos al momento de la entrega (Califique de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=6";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="6" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="6" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="6" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="6" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="6" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  	<p class="titulo_secundario"><strong>Tiempo de espera para retirar su coche (Califique de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=7";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="7" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="7" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="7" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="7" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="7" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  	<p class="titulo_secundario"><strong>Amabilidad del personal al momento de la entrega del veh&iacute;culo (califique  de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=8";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>

		<input name="8" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="8" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="8" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="8" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="8" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  	<p class="titulo_secundario"><strong>Dificultades al momento de la devoluci&oacute;n de su veh&iacute;culo (califique de 1 a 5)</strong><br>

		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=9";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>


		<input name="9" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/>1<br />

		<input name="9" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/>2<br />

		<input name="9" type="radio" value="3" <?php echo ($rsRespuestas['valor']=='3')?' checked="checked"':''?>/>3<br />

		<input name="9" type="radio" value="4" <?php echo ($rsRespuestas['valor']=='4')?' checked="checked"':''?>/>4<br />

		<input name="9" type="radio" value="5" <?php echo ($rsRespuestas['valor']=='5')?' checked="checked"':''?>/>5<br />



	  	</p>

	  <p class="titulo_secundario"><strong>4. Si tuviera que volver a reservar un veh&iacute;culo para visitar Ushuaia. Volver&iacute;a a hacerlo con Discover Ushuaia Rent a Car.  </strong>
		<?php
		$sql = "SELECT * FROM `encuesta_respuestas`

							WHERE `encuesta_id` = '".$rs['idEncuesta']."' AND pregunta_id=10";




					$rsTemp2 =	mysqli_query($conn,$sql);
					$rsRespuestas = mysqli_fetch_array($rsTemp2);


		?>
		<br />

		<input name="10" type="radio" value="1" <?php echo ($rsRespuestas['valor']=='1')?' checked="checked"':''?>/> Si<br />

		<input name="10" type="radio" value="2" <?php echo ($rsRespuestas['valor']=='2')?' checked="checked"':''?>/> No<br />



	  </p>

	<p class="titulo_secundario"><strong>Lo que le ha gustado</strong><br /><br />

	  <textarea name="comentarios_buenos" cols="100" rows="5" style="border-color: #06fb4e; border-width: 4px;"><?php echo utf8_encode($rs['comentarios_buenos']);?></textarea>

	</p>

          <p class="titulo_secundario"><strong>Lo que no le ha gustado</strong><br /><br />

	  <textarea name="comentarios_malos" cols="100" rows="5" style="border-color: #900303; border-width: 4px;"><?php echo utf8_encode($rs['comentarios_malos']);?></textarea>

	</p>

	<p align="center"><input type="submit" class="cat-agregar" value="Enviar" name="enviar"></p>





	</form>

	</div>

	<br/>

	</div>

<?php /*}
		else{?>
			<div id="gracias">

		<p align="center" class="titulo_principal">Los datos ingresados son erroneos.</p>
		<p align="center" class="titulo_principal"><a href="javascript:history.back(-1);" title="Ir la pÃ¡gina anterior">Volver</a></p>
		</div>
		<?php }
	}*/
	}else{

		?>
		<div id="gracias">

		<p align="center" class="titulo_principal">La encuesta ya ha sido completada.</p>

		</div>
	<?php }
}
?>
