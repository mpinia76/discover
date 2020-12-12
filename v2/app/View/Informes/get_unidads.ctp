
<strong>&nbsp;&nbsp;Unidades&nbsp;</strong><select name="InformeUnidadId[]" multiple="multiple" style="height:40px; width:200px; margin:2px 0px" id="InformeUnidadId">
	
	
	<?php 
	
	foreach($unidads as $unidad){ ?>
	<option value="<?php echo $unidad['Unidad']['id']?>"><?php echo $unidad['Unidad']['unidad']?></option>
    <?php } ?>
</select>
