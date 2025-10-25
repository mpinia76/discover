<?php
//DATOS PARA LA BASE DE DATOS MYSQL

define('DB_HOST', 'localhost');
define('DB_NAME', 'discover');
define('DB_USER', 'root');
define('DB_PASS', '');


// API tusFacturas
define('API_KEY', '68875');
define('API_TOKEN', '237a174222d68f6069f7f23a03f79de4');
define('API_URL', 'https://www.tusfacturas.app/app/api/v2/facturacion/nuevo_encola');
define('WEBHOOK_TOKEN', '0f7f6a17143f9cefe10f073a27ebb19a21510cc2f2675ee7a0a6d23a4a3218d7');

// User Tokens por punto de venta o CUIT
$tusfacturas_tokens = [
	1 => [ // Punto de venta 1
		'NUMERO' => '00001',
		'CUIT' => '20251748056',
		'USER_TOKEN' => 'a121bac417e52622bb9412e23a0e70ca941c8902cb745033c6705df5f9e51e40'
	],
	2 => [ // Punto de venta 2
		'NUMERO' => '00002',
		'CUIT' => '20251748056',
		'USER_TOKEN' => '6a79fec78166a3b9b4db3b9ee17ff6179aa02931d9af87f561d5f60119d535c4'
	]
];

//CONEXION A LA BASE DE DATOS

/*$conn=mysql_connect($dbhost, $dbuser, $dbpassword);
//print_r($conn);
mysql_select_db($dbname);*/

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


//CONSULTAS COMUNES
function sql_meses($tabla,$ano,$campo = 'fecha'){
	return "
	ROUND(SUM(IF(MONTH($tabla.$campo)=1 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '1',
	ROUND(SUM(IF(MONTH($tabla.$campo)=2 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '2',
	ROUND(SUM(IF(MONTH($tabla.$campo)=3 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '3',
	ROUND(SUM(IF(MONTH($tabla.$campo)=4 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '4',
	ROUND(SUM(IF(MONTH($tabla.$campo)=5 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '5',
	ROUND(SUM(IF(MONTH($tabla.$campo)=6 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '6',
	ROUND(SUM(IF(MONTH($tabla.$campo)=7 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '7',
	ROUND(SUM(IF(MONTH($tabla.$campo)=8 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '8',
	ROUND(SUM(IF(MONTH($tabla.$campo)=9 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '9',
	ROUND(SUM(IF(MONTH($tabla.$campo)=10 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '10',
	ROUND(SUM(IF(MONTH($tabla.$campo)=11 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '11',
	ROUND(SUM(IF(MONTH($tabla.$campo)=12 AND YEAR($tabla.$campo)=$ano,$tabla.monto,0)),2) as '12'";
}

?>
