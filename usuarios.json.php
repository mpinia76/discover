<?php
session_start();

include_once("config/db.php");

//$sql = "SELECT * FROM usuario WHERE admin!=1 AND id!=".$_SESSION['useridushuaia'];
$sql = "SELECT * FROM usuario WHERE id!=".$_SESSION['useridushuaia'];

$rsTemp = mysqli_query($conn,$sql);
$rows = array();
while($rs = mysqli_fetch_array($rsTemp)){

	$data = array(
		"id" => $rs['id'],
		"data" => array(
			$rs['nombre'],
			$rs['apellido']
		)
	);
	array_push($rows,$data);
}

$array = array("rows" => $rows);

$json = json_encode($array);

echo $json;

?>
