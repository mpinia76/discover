<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
include_once("model/form.class.php");
include_once("config/db.php");
include_once("functions/abm.php");
include_once("functions/util.php");
if(isset($_GET['exit']) and $_GET['exit']=="on"){
    auditarUsuarios('logout');

    session_destroy();
    setcookie("useridushuaia","",time()-3600);
    header('Location: http://localhost/discovergral');
}else if($_SESSION['useridushuaia'] != ''){
    header('Location: desktop.php');
}else header('Location: http://localhost/discovergral');


if(isset($_POST['ingresar'])){
	$user = $_POST['email'];
	$pass = $_POST['password'];

	$sql = "SELECT * FROM usuario WHERE email = '$user' AND password='$pass'";

	$rsTemp = mysqli_query($conn,$sql);
	$total = mysqli_num_rows($rsTemp);


	if($total == 1){
		$rs = mysqli_fetch_array($rsTemp);

		$_SESSION['useridushuaia'] = $rs['id'];
		$_SESSION['usernombreushuaia'] = $rs['nombre']." ".$rs['apellido'];
		$_SESSION['userdniushuaia'] = $rs['dni'];
                                    setcookie('useridushuaia',$rs['id'],time()+60*60*24,'/');
        auditarUsuarios('login');
		if($rs['admin'] == 1){ $_SESSION['adminushuaia'] = true; }
		header("Location: desktop.php#");
	}else{
		header('Location: http://localhost/discovergral/index.php?result=2');
	}
}
$campos['email'] 	= array('text','E-mail',1);
$campos['password'] = array('text','Password',0,'','','password');

$form = new Form();
$form->setLegend('Ingresar al sistema'); //nombre del form
$form->setAction('index.php'); //a donde hacer el post
$form->setBotonValue('Ingresar'); //leyenda del boton
$form->setBotonName('ingresar');
$form->setCampos($campos);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Discover Ushuaia Online!</title>
<link href="styles/form.css" rel="stylesheet" type="text/css" />
<?php echo $form->printJS()?>
<style>
body{
margin:0;
}
</style>
</head>

<body>
<div id="wrapper" style="width:250px; margin-left:auto; margin-right:auto; margin-top:150px;">
<img src="images/logo.jpg" /><br /><br />
<?php  if(isset($result) and $result == 2){ ?>
	<div id="mensaje" class="ok"><p><img src="images/error.gif" align="absmiddle" /> Datos incorrectos</p></div>
<?php  } ?>
<?php echo $form->printHTML()?>
</div>

</body>
</html>
