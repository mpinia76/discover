<?php


include_once("config/db.php");
include_once("functions/util.php");

/*$dbhost = "localhost";
$dbname = "sistemad_produccion";
$dbuser = "sistemad_usrdb";
$dbpassword = "sidb0149";*/

/*$dbhost = "localhost";
$dbname = "discover";
$dbuser = "root";
$dbpassword = "";*/

//CONEXION A LA BASE DE DATOS

//$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);


$hoy = date("Y-m-d");
$ayer = date('Y-m-d', strtotime('-1 day'));
//$fecha = '2018-05-06';
$condicion = (isset($_GET['id']))?" reservas.id='".$_GET['id']."' ":" (reservas.devolucion='".$hoy."' OR reservas.devolucion='".$ayer."')";
$sql = "SELECT reservas.id, clientes.nombre_apellido, clientes.email FROM reservas INNER JOIN clientes ON reservas.cliente_id = clientes.id
WHERE ".$condicion." AND ((reservas.estado != 2 AND reservas.estado != 3) OR reservas.estado is null)";
//echo $sql."<br>";
$rsTemp1 = mysqli_query($conn,$sql);
$id=0;
while ($rs = mysqli_fetch_array($rsTemp1)){
	$newPassword =  textoRadom(8) ;
	$newPasswordMD5 =  md5($newPassword) ;
	//echo $newPassword."<br>";
	$sql = "SELECT id, enviada,respondida FROM encuesta WHERE reserva_id = ".$rs['id'];
	//echo $sql."<br>";
	$rsTemp = mysqli_query($conn,$sql);
	if(mysqli_num_rows($rsTemp)==0){
		$sql = "INSERT INTO encuesta (reserva_id, password, enviada) VALUES ('".$rs['id']."','".$newPasswordMD5."', 1)";
		//echo $sql."<br>";
		mysqli_query($conn,$sql);
		$id=mysqli_insert_id($conn);
	}
	else{
		$rsEncuesta = mysqli_fetch_array($rsTemp);
		if ($rsEncuesta['respondida']==0) {
			$enviada = $rsEncuesta['enviada'] + 1;
			$id = $rsEncuesta['id'];
			$sql = "UPDATE encuesta SET enviada = ".$enviada." WHERE id = ".$id;
			//echo $sql."<br>";
			mysqli_query($conn,$sql);
		}
	}
	if ($id) {


		$textMessage = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY><div class=""><div class="aHl"></div><div id=":od" tabindex="-1"></div><div id=":o2" class="ii gt">
		<div id=":o1" class="a3s aXjCH "><div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:#666666">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)"><div dir="ltr">
		<div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif">
		<span class="m_5146862768849835395m_-4273096359447565817gmail-m_2461132594155676157gmail-m_-1279388942409998069gmail-im" style="font-family:tahoma,sans-serif">
		<font color="#444444"><p class="MsoNormal" style="margin-bottom:0.0001pt;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif">Estimado/a '.$rs['nombre_apellido'];
		$textMessage .= '</span><span style="font-family:arial,sans-serif"></span></p>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif">&nbsp;</span>
		<span style="font-family:arial,sans-serif"></span></p>


		<p class="MsoNormal" style="margin-bottom:0.0001pt;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif">Nos comunicamos para agradecerles el habernos elegido y confiado en nosotros durante su estad&iacute;a en Ushuaia. Queremos invitarlo a participar de nuestra encuesta de satisfacci&oacute;n haciendo click <a href="https://www.discoverushuaia.com.ar/encuestas/encuesta.php?id='.$id.'" target="_blank">aqu&iacute;</a>. No le llevar&aacute; m&aacute;s de 2 minutos y para nosotros es de gran ayuda e importancia contar con su opini&oacute;n.</span></p>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif">&nbsp;</span></p>

		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444">Lo saludamos afectuosamente y esperamos contarlo entre nuestros clientes en su pr&oacute;xima visita al fin del mundo.</font></span></p>
		<p class="MsoNormal" style="color:rgb(68,68,68);margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><br></span></p>
		<p class="MsoNormal" style="color:rgb(68,68,68);margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"></span></p>
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default">
		<div class="gmail_default" style="text-align:left;font-family:verdana,sans-serif">
		<i style="font-size:x-small">
		<img src="http://104.238.101.101/ushuaia/images/Banner_USH.png" style="margin-right:0px" data-image-whitelisted="" class="CToWUd" width="350"></i>
		<br></div>
		</div></div>
<br></div></div>
<br></div></div>
<br></div></div>
<br></div></div>
<br><p></p><p class="MsoNormal" style="color:rgb(68,68,68);margin-bottom:0.0001pt;font-family:tahoma,sans-serif;font-size:12.8px;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
<span style="font-size:8.5pt;font-family:verdana,sans-serif"><br></span></p></div></div><div class="yj6qo"></div><div class="adL">
<br></div></div></div><div class="adL">
<br></div></div></div><div class="adL">
<br></div></div></div><div class="adL">
</div></div></div><div id=":og" class="ii gt" style="display:none"><div id=":oh" class="a3s aXjCH undefined"></div></div>
<div class="hi"></div></div></BODY>
</HTML>';

		$mailheaders = "MIME-Version: 1.0 \r\n";
	    $mailheaders .= "Content-type: text/html; charset=utf8 \r\n";
	    $mailheaders .= "From: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> \r\n";
	    $mailheaders .= "Return-path: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> \r\n";
	    $mailheaders .= "Cco: pacec@discoverushuaia.com.ar \r\n";
        $mailheaders .= "Cco: minervinim@discoverushuaia.com.ar \r\n";
        $mailheaders .= "X-Priority: 1 \r\n";
	    $mailheaders .= "X-MSMail-Priority: High \r\n";
	    $mailheaders .= "X-Mailer: PHP/".phpversion()." \n";



		mail($rs['email'], "Regreso a Casa - Discover Rent a Car", $textMessage, $mailheaders, "-finfo@discoverushuaia.com.ar");
		//mail("pacec@discoverushuaia.com.ar", "Regreso a Casa - Discover Rent a Car", $textMessage, $mailheaders, "-finfo@discoverushuaia.com.ar");

	}
}


?>
