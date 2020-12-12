<?php
function validar($tabla,$campos,$conn=''){
	switch ($tabla) {
		case 'empleado':
			$sql="SELECT id, CONCAT(nombre,' ',apellido) as empleado, CASE WHEN estado ='0' THEN 'Inactivo' ELSE 'Activo' END as estado FROM $tabla WHERE dni = '".$campos['dni']."'";
			$rs = mysqli_fetch_array(mysqli_query($conn,$sql));
			//echo $sql;
			if ($rs['id']) {
				$msg=" El DNI ya se encuentra registrado para ".$rs['empleado']." - ".$rs['estado'];
				return $msg;
			}
		break;

		default:

		break;
	}
	return 0;
}
function mysql_insert($tabla,$campos,$conn=''){
$msg=validar($tabla, $campos,$conn);
if (!$msg) {
	$query='INSERT INTO `'.$tabla.'` (';
	$count=0;
	foreach($campos as $campo => $valor){
		$campo = str_replace("\'","`",$campo);
			if($count==0)
			{
			$query.=$campo;
			}else{
			$query.=','.$campo;

			}
	$count++;
	}
	$query.=') VALUES (';
		$count=0;
	foreach($campos as $campo => $valor){
	if (strcmp ($valor,'NOW()') == 0){
	$valor="".$valor."";
	}else{
	$valor="'".$valor."'";}
			if($count==0)
			{
			$query.=$valor;
			}else{
			$query.=",".$valor;

			}
	$count++;
	}
	$query.=')';
	//echo $query;
	mysqli_query($conn,$query);
	$result=mysqli_affected_rows($conn);
	//print_r($result);
	if($result=="-1"){
	//$msg="No se pudieron cargar los datos: ".mysqli_error($conn);
		//$msg="No se pudieron cargar los datos: ";
	}

	else{
		if ($tabla=='empleado') {
			$sql = "INSERT INTO empleado_historico (empleado_id, alta) VALUES (".mysqli_insert_id($conn).", '".fechasql($_POST['fecha_alta'])."')";
			//echo $sql;
			mysqli_query($conn,$sql);
		}
		$msg=1;
	}
}

return $msg;
}

function mysql_update($tabla,$campos,$id,$conn='') {
$query='UPDATE `'.$tabla.'` SET ';
$count=0;
foreach($campos as $campo => $valor)
{
	$campo = str_replace("\'","`",$campo);
	if($count==0)
		{
	$query.=''.$campo.'=\''.$valor.'\'';
		}else{
	$query.=','.$campo.'=\''.$valor.'\'';
		}
$count++;
}
$query.=' WHERE id = \''.$id.'\'';


mysqli_query($conn,$query);
$result=mysqli_affected_rows($conn);
if($result=="-1"){
	$msg="No se pudieron actualizar los datos: ".mysqli_error($conn);
	//$msg="No se pudieron actualizar los datos: ";
}
else{
$msg=2;}
return $msg;
}

?>
