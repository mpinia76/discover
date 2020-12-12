<?php
include_once("config/db.php");

$sql = "SELECT * FROM unidads WHERE categoria_id=".$_GET['categoria']." AND estado = 1 AND activa = 1 AND excluir = 0";

$rsTemp = mysqli_query($conn,$sql);

?>
<select name="UnidadId[]" multiple="multiple" style="float:left;height:40px; width:200px; margin:2px 0px" id="UnidadId">
	<?php
	while($rs = mysqli_fetch_array($rsTemp)){

	?>

	<option value="<?php echo $rs['id']?>"><?php echo $rs['marca'].' '.$rs['modelo'].' '.$rs['patente']?> </option>

	<?php } ?>

</select>
