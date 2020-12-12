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
                createWindow("w_subrubros_view","Ver extra subrubro","'.$this->Html->url('/extra_subrubros/editar', true).'/"+data[0],"450","300");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/extra_subrubros/dataTable', true).'",
        "bDeferRender": true,
        "aoColumns": [
            {"bVisible": false },
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
    position = dhxWins.window("w_extra_subrubros").getPosition();
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
        
        createWindow("w_extra_subrubros_view","Ver extra subrubro","<?php echo $this->Html->url('/extra_subrubros/editar', true);?>/"+data[0],"450","300");
        
    }
}

</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_extra_subrubros_add','Crear Parametrizacion Extra','<?php echo $this->Html->url('/extra_subrubros/crear', true);?>','450','300');">Crear</a></li>
    <li class="boton editar"><a onclick="editar();">Editar</a></li>
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>
</ul>
  
  
 



 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
        <tr>
            <th width="50">Id</th>
            <th width="150">Extra Rubro</th>
            <th width="150">Subrubro</th>
           	<th width="150">Subrubro Ingles</th>
           	<th width="150">Subrubro Portugues</th>
            
            
        </tr>
    </thead>
    <tbody></tbody>
</table>
