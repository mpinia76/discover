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
                createWindow("w_neumaticos_view","Gestion de neumaticos","'.$this->Html->url('/neumaticos/editar', true).'/"+data[0],"6","350");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/neumaticos/dataTable', true).'",
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
    

    $("#filter_fecha").keyup(function(){
        oTable.fnFilter($(this).val(),1);
    });
    $("#filter_marca").keyup(function(){
        oTable.fnFilter($(this).val(),2);
    });
    $("#filter_modelo").keyup(function(){
        oTable.fnFilter($(this).val(),3);
    });
    $("#filter_fabricacion").keyup(function(){
        oTable.fnFilter($(this).val(),4);
    });
    $("#filter_dibujo").keyup(function(){
        oTable.fnFilter($(this).val(),5);
    });
     $("#filter_temporada").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),6);
        }else{
            oTable.fnFilter($("#filter_temporada option:selected").text(),6);
         }
     });
    $("#filter_identificaro").keyup(function(){
        oTable.fnFilter($(this).val(),7);
    });
    $("#filter_categoria").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),8);
        }else{
            oTable.fnFilter($("#filter_categoria option:selected").text(),8);
         }
     });

     $("#filter_unidad").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),9);
        }else{
            oTable.fnFilter($("#filter_unidad option:selected").text(),9);
         }
     });

     $("#filter_posicion").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),10);
        }else{
            oTable.fnFilter($("#filter_posicion option:selected").text(),10);
         }
     });

     $("#filter_estado").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),11);
        }else{
            oTable.fnFilter($("#filter_estado option:selected").text(),11);
         }
     });

    $("#filter_antiguedad").keyup(function(){
        oTable.fnFilter($(this).val(),12);
    });

	$("#filter_km").keyup(function(){
        oTable.fnFilter($(this).val(),13);
    });





');

//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_neumaticos").getPosition();
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

        createWindow("w_neumaticos_view","Gestion de neumaticos","<?php echo $this->Html->url('/neumaticos/editar', true);?>/"+data[0],"450","350");

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
    <li class="boton agregar"><a onclick="createWindow('w_neumaticos_add','Gestion de neumaticos','<?php echo $this->Html->url('/neumaticos/crear', true);?>','450','350');">Crear</a></li>
    <li class="boton editar"><a onclick="editar();">Editar</a></li>
    <!--<li class="boton anular"> <a onclick="eliminar();">Eliminar</a></li>-->
    <li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>
</ul>







<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
    <tr>
        <th width="50">Id</th>




        <th width="50">
            <input type="text" style="width: 90%;" id="filter_fecha" />
        </th>
        <th width="70">
            <input type="text" style="width: 90%;" id="filter_marca" />
        </th>
        <th width="70">
            <input type="text" style="width: 90%;" id="filter_modelo" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_fabricacion" />
        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_dibujo" />
        </th>
        <th width="50">

            <select id="filter_temporada">
                <option value="">Temporada</option>
                <option>Verano</option>
                <option>Invierno Clavos</option>
                <option>Invierno Silice</option>
                <option>Mixto</option>
            </select>

        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_identificador" />
        </th>
        <th width="30">

            <select id="filter_categoria">
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

            <select id="filter_posicion">
                <option value="">Posicion</option>
                <option>DC</option>
                <option>DA</option>
                <option>TC</option>
                <option>TA</option>
                <option>Auxilio</option>
            </select>

        </th>
        <th width="30">

            <select id="filter_estado">
                <option value="">Estado</option>
                <option>En uso</option>
                <option>En deposito</option>
                <option>Baja</option>
            </select>

        </th>
        <th width="30">
            <input type="text" style="width: 90%;" id="filter_antiguedad" />
        </th>
        <th width="20">
            <input type="text" style="width: 90%;" id="filter_km" />
        </th>
    </tr>
        <tr>
            <th width="50">Id</th>
            <th width="50">Fecha</th>
            <th width="70">Marca</th>
            <th width="70">Modelo</th>
            <th width="30">Fabricacion</th>
            <th width="30">Medida</th>
            <th width="50">Temporada</th>
            <th width="30">Identificador</th>
            <th width="30">Categoria</th>
            <th width="30">Unidad</th>
            <th width="30">Posicion</th>
            <th width="30">Estado</th>
            <th width="30">Antigüedad</th>
            <th width="30">Km</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
