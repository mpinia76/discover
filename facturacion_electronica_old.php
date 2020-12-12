<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="library/jquery/jquery-1.4.2.min.js" type="text/javascript"></script>

<script>
function roundVal(num){
	return Math.round(num*100)/100;
}
</script>
<title>Documento sin t&iacute;tulo</title>
<style>
a{
text-decoration:underline;
color:#0000FF;
cursor:pointer;
}
</style>
</head>

<body>

<?php if(isset($_POST['ano'])) { $ano = $_POST['ano']; }else{ $ano= date('Y'); } 
		if(isset($_POST['mes'])) { $mes = $_POST['mes']; }else{ $mes= date('m'); }	
?>

<form method="post" action="excel_facturacion_electronica.php">
<select size="1" name="ano">
	<option <?php if($ano == '2010'){?> selected="selected" <?php } ?> >2010</option>
	<option <?php if($ano == '2011'){?> selected="selected" <?php } ?> >2011</option>
	<option <?php if($ano == '2012'){?> selected="selected" <?php } ?> >2012</option>
	<option <?php if($ano == '2013'){?> selected="selected" <?php } ?> >2013</option>
	<option <?php if($ano == '2014'){?> selected="selected" <?php } ?> >2014</option>
	<option <?php if($ano == '2015'){?> selected="selected" <?php } ?> >2015</option>
    <option <?php if($ano == '2016'){?> selected="selected" <?php } ?> >2016</option>
    <option <?php if($ano == '2017'){?> selected="selected" <?php } ?> >2017</option>
    <option <?php if($ano == '2018'){?> selected="selected" <?php } ?> >2018</option>
    <option <?php if($ano == '2019'){?> selected="selected" <?php } ?> >2019</option>
    <option <?php if($ano == '2020'){?> selected="selected" <?php } ?> >2020</option>
    <option <?php if($ano == '2021'){?> selected="selected" <?php } ?> >2021</option>
    <option <?php if($ano == '2022'){?> selected="selected" <?php } ?> >2022</option>
    <option <?php if($ano == '2023'){?> selected="selected" <?php } ?> >2023</option>
</select> 


<select id="formulario_mes" name="mes">
    <option <?php if($mes == '1'){?> selected="selected" <?php } ?> value="01">Enero</option>
    <option <?php if($mes == '2'){?> selected="selected" <?php } ?> value="02">Febrero</option>
    <option <?php if($mes == '3'){?> selected="selected" <?php } ?> value="03">Marzo</option>
    <option <?php if($mes == '4'){?> selected="selected" <?php } ?> value="04">Abril</option>
    <option <?php if($mes == '5'){?> selected="selected" <?php } ?> value="05">Mayo</option>
    <option <?php if($mes == '6'){?> selected="selected" <?php } ?> value="06">Junio</option>
    <option <?php if($mes == '7'){?> selected="selected" <?php } ?> value="07">Julio</option>
    <option <?php if($mes == '8'){?> selected="selected" <?php } ?> value="08">Agosto</option>
    <option <?php if($mes == '9'){?> selected="selected" <?php } ?> value="09">Septiembre</option>
    <option <?php if($mes == '10'){?> selected="selected" <?php } ?> value="10">Octubre</option>
    <option <?php if($mes == '11'){?> selected="selected" <?php } ?> value="11">Noviembre</option>
    <option <?php if($mes == '12'){?> selected="selected" <?php } ?> value="12">Diciembre</option>
</select> 
<input type="submit"  value="Descargar" />


</body>
</html>
