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

            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/gestion_alertas/dataTable', true).'",
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

     $("#filter_patente").keyup(function(){
        oTable.fnFilter($(this).val(),5);
    });

    $("#filter_marca").keyup(function(){
        oTable.fnFilter($(this).val(),6);
    });

    $("#filter_modelo").keyup(function(){
        oTable.fnFilter($(this).val(),7);
    });

     $("#filter_unidad").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),8);
        }else{
            oTable.fnFilter($("#filter_unidad option:selected").text(),8);
         }
     });

     $("#filter_magnitud").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),9);
        }else{
            oTable.fnFilter($("#filter_magnitud option:selected").text(),9);
         }
     });

     $("#filter_segmento").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),10);
        }else{
            oTable.fnFilter($("#filter_segmento option:selected").text(),10);
         }
     });

    $("#filter_control").keyup(function(){
        oTable.fnFilter($(this).val(),11);
    });

	$("#filter_actual").keyup(function(){
        oTable.fnFilter($(this).val(),12);
    });

    $("#filter_limite").keyup(function(){
        oTable.fnFilter($(this).val(),13);
    });

     $("#filter_proxima").keyup(function(){
        oTable.fnFilter($(this).val(),14);
    });

    $("#filter_estado").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),15);
        }else{
            oTable.fnFilter($("#filter_estado option:selected").text(),15);
         }
     });

     $("#filter_gestion").keyup(function(){
        oTable.fnFilter($(this).val(),16);
    });

     $("#filter_usuario").keyup(function(){
        oTable.fnFilter($(this).val(),17);
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

function resolver(){
    var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        var data = oTable.fnGetData(row[0]);

        if(data[15]!='<span style="color:green">Resuelta</span>'){
            createWindow("w_resolver_view","Resolver Alerta","<?php echo $this->Html->url('/gestion_alertas/resolver', true);?>/"+data[0],"450","350");
        }
        else {
            alert('Alerta en estado Resuelta');
        }


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
    <!--<li class="boton agregar"><a onclick="createWindow('w_alertas_add','Alta y parametrización de alertas','<?php echo $this->Html->url('/alertas/crear', true);?>','450','350');">Crear</a></li>-->
    <li class="boton editar"><a onclick="resolver();">Resolver</a></li>
    <!--<li class="boton anular"> <a onclick="eliminar();">Eliminar</a></li>
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>-->
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
            <input type="text" style="width: 90%;" id="filter_patente" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_marca" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_modelo" />
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
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_actual" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_limite" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_proxima" />
        </th>
        <th width="30">
            <select id="filter_estado">
                <option value="">Estado</option>
                <option>Pendiente</option>
                <option>Resuelta</option>
                <option>Vencida</option>
            </select>
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_gestion" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_usuario" />
        </th>
    </tr>
        <tr>
            <th width="50">Id</th>
            <th width="100">Alerta</th>
            <th width="50">Fecha</th>
            <th width="30">Tipo</th>
            <th width="30">Nivel</th>
            <th width="30">Patente</th>
            <th width="30">Marca</th>
            <th width="30">Modelo</th>
            <th width="30">Unidad</th>
            <th width="30">Magnitud</th>
            <th width="30">Segmento</th>
            <th width="30">Controla</th>
            <th width="30">Actual</th>
            <th width="30">Limite</th>
            <th width="30">Proxima</th>
            <th width="30">Estado</th>
            <th width="30">Gestion</th>
            <th width="30">Usuario</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
