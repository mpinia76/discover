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
                createWindow("w_alertas_view","Alta y parametrizacion de Alertas","'.$this->Html->url('/alertas/editar', true).'/"+data[0],"450","350");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/alertas/dataTable', true).'",
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
            null
        ]
    });
    $(".date_filter").change(function(){ oTable.fnDraw(); })
    $("#data_search").keyup( function () { oTable.fnFilter(this.value); });

');

//filtrar total de resultados
$this->Js->buffer('
    $("#total_rows").change(function(){
        oTable.fnReloadAjax("dataTable/"+$(this).val());
    });
    $("#filter_alerta").keyup(function(){
        oTable.fnFilter($(this).val(),1);
    });

    $("#filter_fecha").keyup(function(){
        oTable.fnFilter($(this).val(),2);
    });

     $("#filter_tipo").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),3);
        }else{
            oTable.fnFilter($("#filter_tipo option:selected").text(),3);
         }
     });

    $("#filter_nivel").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),4);
        }else{
            oTable.fnFilter($("#filter_nivel option:selected").text(),4);
         }
     });

     $("#filter_unidad").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),5);
        }else{
            oTable.fnFilter($("#filter_unidad option:selected").text(),5);
         }
     });

     $("#filter_magnitud").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),6);
        }else{
            oTable.fnFilter($("#filter_magnitud option:selected").text(),6);
         }
     });

     $("#filter_segmento").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),7);
        }else{
            oTable.fnFilter($("#filter_segmento option:selected").text(),7);
         }
     });

    $("#filter_control").keyup(function(){
        oTable.fnFilter($(this).val(),8);
    });

	$("#filter_recordatorio").keyup(function(){
        oTable.fnFilter($(this).val(),9);
    });





');

//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_alertas").getPosition();
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

        createWindow("w_alertas_view","Ver opcion de Cobro","<?php echo $this->Html->url('/alertas/editar', true);?>/"+data[0],"450","350");

    }
}

function eliminar(){

	var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        if(confirm('Seguro desea eliminar el alerta?')){
	    	var id = oTable.fnGetData(row[0]);

	        $.ajax({
	            url : '<?php echo $this->Html->url('/alertas/eliminar', true);?>',
	            type : 'POST',
	            dataType: 'json',
	            data: {'id' : id},
	            success : function(data){

	               window.parent.dhxWins.window('w_alertas').attachURL('<?php echo $this->Html->url('/alertas/index', true);?>');

	            }
	        });
	    }
    }




}

</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_alertas_add','Alta y parametrización de alertas','<?php echo $this->Html->url('/alertas/crear', true);?>','450','350');">Crear</a></li>
    <li class="boton editar"><a onclick="editar();">Editar</a></li>
    <!--<li class="boton anular"> <a onclick="eliminar();">Eliminar</a></li>-->
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>
</ul>







<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
    <tr>
        <th width="50">Id</th>

        <th width="100">
            <input type="text" style="width: 90%;" id="filter_alerta" />
        </th>


        <th width="50">
            <input type="text" style="width: 90%;" id="filter_fecha" />
        </th>

        <th width="30">

            <select id="filter_tipo">
                <option value="">Tipo</option>
                <option>General</option>
                <option>Flota</option>

            </select>

        </th>

        <th width="30">

            <select id="filter_nivel">
                <option value="">Nivel</option>
                <option>Nivel 1</option>
                <option>Nivel 2</option>
                <option>Nivel 3</option>
            </select>

        </th>
        <th width="30">

            <select id="filter_unidad">
                <option value="">Unidad</option>
                <option>KM</option>
                <option>Tiempo</option>
                <option>Reservas</option>
            </select>

        </th>
        <th width="30">

            <select id="filter_magnitud">
                <option value="">Magnitud</option>
                <option>Dia</option>
                <option>Mes</option>
                <option>Año</option>
            </select>

        </th>
        <th width="30">

            <select id="filter_segmento">
                <option value="">Segmento</option>
                <option>Intervalo</option>
                <option>Umbral</option>

            </select>

        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_controla" />
        </th>
        <th width="20">
            <input type="text" style="width: 90%;" id="filter_recordatorio" />
        </th>
    </tr>
        <tr>
            <th width="50">Id</th>
            <th width="100">Alerta</th>
            <th width="50">Fecha</th>
            <th width="30">Tipo</th>
            <th width="30">Nivel</th>
            <th width="30">Unidad</th>
            <th width="30">Magnitud</th>
            <th width="30">Segmento</th>
            <th width="30">Controla</th>
            <th width="30">Fin</th>

        </tr>
    </thead>
    <tbody></tbody>
</table>
