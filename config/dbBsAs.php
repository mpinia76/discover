<?php
//DATOS PARA LA BASE DE DATOS MYSQL


$dbhost = "163.10.35.34";
$dbname = "discoverbsas";
$dbuser = "root";
$dbpassword = "secyt";

/*$dbhost = "localhost";
$dbname = "discover";
$dbuser = "root";
$dbpassword = "";*/

//CONEXION A LA BASE DE DATOS

/*$conn=mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbname);*/

$connBsAs = mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname)


?>
