<?php
//DATOS PARA LA BASE DE DATOS MYSQL



define('DB_HOST', 'localhost');
define('DB_NAME', 'discoverbsas');
define('DB_USER', 'root');
define('DB_PASS', '');

//CONEXION A LA BASE DE DATOS

/*$conn=mysql_connect($dbhost, $dbuser, $dbpassword);
//print_r($conn);
mysql_select_db($dbname);*/

$connBsAs = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>
