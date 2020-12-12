<?php


//dataTables
$this->Js->buffer('
    oTable = $("#dataTable").dataTable( {
	    "sDom": "<\"dataTables_top\"i>t<\"dataTables_bottom\"lp>r",
        "bProcessing": true,
    	"bAutoWidth": false,
        "oLanguage": {
            "sUrl": "/dataTables.spanish.txt"
        },
        "fnDrawCallback": function( oSettings ) {
            $("#dataTable tr").unbind("dblclick").dblclick(function(){
                var data = oTable.fnGetData( this );
                createWindow("w_descuentos_view","Ver Opcion de Cobro","'.$this->Html->url('/descuentos/editar', true).'/"+data[0],"450","300");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/descuentos/dataTable', true).'",
        "bDeferRender": true,
        "aoColumns": [
            {"bVisible": false },
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
           	null,
           	null,
            null,
           	null,
            null
        ]
    });
    $(".date_filter").change(function(){ oTable.fnDraw(); })
    $("#data_search").keyup( function () { oTable.fnFilter(this.value); });

');


//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_descuentos").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
<script>

function editar(){
    var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        var data = oTable.fnGetData(row[0]);

        createWindow("w_descuentos_view","Ver opcion de Cobro","<?php echo $this->Html->url('/descuentos/editar', true);?>/"+data[0],"450","300");

    }
}

function eliminar(){

	var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        if(confirm('Seguro desea eliminar la opcion de pago?')){
	    	var id = oTable.fnGetData(row[0]);

	        $.ajax({
	            url : '<?php echo $this->Html->url('/descuentos/eliminar', true);?>',
	            type : 'POST',
	            dataType: 'json',
	            data: {'id' : id},
	            success : function(data){

	               window.parent.dhxWins.window('w_descuentos').attachURL('<?php echo $this->Html->url('/descuentos/index', true);?>');

	            }
	        });
	    }
    }




}

</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_descuentos_add','Crear Opcion de Cobro','<?php echo $this->Html->url('/descuentos/crear', true);?>','450','300');">Crear</a></li>
    <li class="boton editar"><a onclick="editar();">Editar</a></li>
    <li class="boton anular"> <a onclick="eliminar();">Eliminar</a></li>
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>
</ul>







<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
        <tr>
            <th width="50">Id</th>
            <th width="200">Opcion de cobro</th>
            <th width="100">Disponible venta on line Espa&ntilde;ol</th>
            <th width="100">Disponible venta on line Ingl&eacute;s</th>
            <th width="100">Disponible venta on line Portugu&eacute;s</th>
            <th width="30">Permite Tarjeta Espa&ntilde;ol</th>
            <th width="30">Permite Tarjeta Ingl&eacute;s</th>
            <th width="30">Permite Tarjeta Portugu&eacute;s</th>
            <th width="30">MercadoPago Espa&ntilde;ol</th>
            <th width="30">MercadoPago Ingl&eacute;s</th>
            <th width="30">MercadoPago Portugu&eacute;s</th>
            <th width="30">Descuento</th>
            <th width="30">Pago parcial</th>
     		<th width="30">Orden</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
