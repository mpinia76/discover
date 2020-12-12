<?php

//dataTables
$this->Js->buffer('
    oTable = $("#dataTableFeriado").dataTable( {
	    "sDom": "<\"dataTables_top\"i>t<\"dataTables_bottom\"lp>r",
        "bProcessing": true,
    	"bAutoWidth": false,
        "oLanguage": {
            "sUrl": "/dataTables.spanish.txt"
        },
        "fnDrawCallback": function( oSettings ) {
            $("#dataTableFeriado tr").unbind("dblclick").dblclick(function(){
                var data = oTable.fnGetData( this );
                createWindow("w_feriados_view","Editar restriccion","'.$this->Html->url('/feriados/editar', true).'/"+data[0],"450","300");
            });
            $("#dataTableFeriado tr").click(function(e){
                $("#dataTableFeriado tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/feriados/dataTable', true).'",
        "bDeferRender": true,
        "aoColumns": [
            {"bVisible": false },
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
    position = dhxWins.window("w_feriados").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
<script>



</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_feriados_add','Crear restriccion','<?php echo $this->Html->url('/feriados/crear', true);?>','420','300');">Crear</a></li>
    <li class="boton editar"><a onclick="editarFeriado();">Editar</a></li>
    <li class="boton anular"> <a onclick="eliminar();">Eliminar</a></li>
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>
</ul>
  
  
 



 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTableFeriado">
    <thead>
        <tr>
            <th width="50">Id</th>
            <th width="100">Fecha</th>
            <th width="20">Abre</th>
            <th width="200">Horarios</th>
            
        </tr>
    </thead>
    <tbody></tbody>
</table>
