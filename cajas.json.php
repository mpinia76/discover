<?php
session_start();
$user_id = $_SESSION['useridushuaia'];

include_once("config/db.php");

if($user_id != 1){
	$sql = "SELECT caja.* FROM caja INNER JOIN usuario_caja ON usuario_caja.caja_id = caja.id WHERE usuario_caja.usuario_id = $user_id";
}else{
	$sql = "SELECT * FROM caja";
}

$rsTemp = mysqli_query($conn,$sql);
$rows = array();
while($rs = mysqli_fetch_array($rsTemp)){

	// Saldo total en pesos (sin moneda específica)
	$saldo_sql = "SELECT SUM(monto) as saldo FROM caja_movimiento WHERE caja_id = ".$rs['id'];
	$saldo_rs = mysqli_fetch_array(mysqli_query($conn, $saldo_sql));
	$saldo = $saldo_rs['saldo'];

// Saldo USD descontando usados
	$saldo_sql = "
    SELECT 
        SUM(CASE WHEN monto_moneda > usados THEN monto_moneda - usados ELSE 0 END) AS saldo, 
        SUM(CASE WHEN monto_moneda > usados THEN (monto_moneda - usados) * cambio ELSE 0 END) AS saldo_pesos 
    FROM caja_movimiento 
    WHERE caja_id = ".$rs['id']." AND moneda_id = 2
";
	$saldo_rs = mysqli_fetch_array(mysqli_query($conn, $saldo_sql));
	$saldo_usd = $saldo_rs['saldo'];
	$saldo_usd_restar = $saldo_rs['saldo_pesos'];

// Saldo EUR descontando usados
	$saldo_sql = "
    SELECT 
        SUM(CASE WHEN monto_moneda > usados THEN monto_moneda - usados ELSE 0 END) AS saldo, 
        SUM(CASE WHEN monto_moneda > usados THEN (monto_moneda - usados) * cambio ELSE 0 END) AS saldo_pesos 
    FROM caja_movimiento 
    WHERE caja_id = ".$rs['id']." AND moneda_id = 3
";
	$saldo_rs = mysqli_fetch_array(mysqli_query($conn, $saldo_sql));
	$saldo_euros = $saldo_rs['saldo'];
	$saldo_euros_restar = $saldo_rs['saldo_pesos'];

// Saldo en pesos neto
	$saldo_pesos = $saldo - $saldo_usd_restar - $saldo_euros_restar;

// Última sincronización
	$sinc_sql = "
    SELECT caja_sincronizada.usuario_id, caja_sincronizada.fecha, caja_sincronizada.monto, usuario.nombre, usuario.apellido 
    FROM caja_sincronizada 
    INNER JOIN usuario ON caja_sincronizada.usuario_id = usuario.id 
    WHERE caja_sincronizada.caja_id = ".$rs['id']." 
    ORDER BY caja_sincronizada.fecha DESC 
    LIMIT 1
";
	$sinc_rs = mysqli_fetch_array(mysqli_query($conn, $sinc_sql));

	$usuario = $sinc_rs['nombre'] . " " . $sinc_rs['apellido'];
	$fecha = $sinc_rs['fecha'] ? date("d/m/Y H:i:s", strtotime($sinc_rs['fecha'])) : '';
	$monto = $sinc_rs['monto'] ?? '';

	$data = array(
		"id" => $rs['id'],
		"data" => array(
			$rs['caja'],
			$usuario,
			$fecha,
			round($monto, 2),
			round($saldo_euros, 2),
			round($saldo_usd, 2),
			round($saldo_pesos, 2),
			round($saldo, 2)
		)
	);

	array_push($rows,$data);
}

$array = array("rows" => $rows);

$json = json_encode($array);

echo $json;

?>
