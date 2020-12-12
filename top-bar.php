<div id="top_bar">
	<a href="#" onclick="$('#menu').toggle(); $('#menu .item').hide();"><div id="bt_menu"></div></a>
	<div style="float: left;margin-right: 20px;	padding: 5px;	color: #FFFFFF;	height: 50px;	text-align: right;	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;	font-weight: bold;"><img src="images/ico_users.png" align="absmiddle" /> Bienvenido 
	<a style="color: #FFFFFF;" href="#" onClick="createWindow('w_usuario_edit','Editar usuario','usuarios.am.php?comun=1&dataid=<?php echo $_SESSION['useridushuaia']?>&extras=off','600','400');">
	<?php echo $_SESSION['usernombreushuaia']?></a></div>
	<div id="texto">
	Sucursal Activa: Ushuaia
	&nbsp; | &nbsp;<a href="index.php?exit=on">Cambiar Sucursal<img border="0" src="images/bt_exit.png" align="absmiddle" /></a>
	</div>
</div>