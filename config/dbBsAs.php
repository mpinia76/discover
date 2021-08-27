<?php
//DATOS PARA LA BASE DE DATOS MYSQL


/*$dbhost = "163.10.35.37";
$dbname = "discoverbsas";
$dbuser = "root";
$dbpassword = "secyt";*/

$dbhost = "localhost";
$dbname = "discoverbsas";
$dbuser = "root";
$dbpassword = "";

//CONEXION A LA BASE DE DATOS

/*$conn=mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbname);*/

$connBsAs = mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname)


?>
