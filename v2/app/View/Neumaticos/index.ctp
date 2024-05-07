<?php


//dataTables
$this->Js->buffer('
    oTable = $("#dataTable").dataTable( {
	    "sDom": "<\"dataTables_top\"i>t<\"dataTables_bottom\"lp>r",
        "bProcessing": true,
        "bServerSide": true,
        "bAutoWidth": false,
        "oLanguage": {
            "sUrl": "/dataTables.spanish.txt"
        },
        "fnDrawCallback": function( oSettings ) {
            $("#dataTable tr").unbind("dblclick").dblclick(function(){
                var data = oTable.fnGetData( this );
                createWindow("w_neumaticos_view","Gestion de neumaticos","'.$this->Html->url('/neumaticos/editar', true).'/"+data[0],"450","350");
            });
            $("#dataTable tr").click(function(e){
                if(e.shiftKey){
                    $(this).toggleClass("row_selected");
                }else{
                    $("#dataTable tr").removeClass("row_selected");
                    $(this).toggleClass("row_selected");
                }
             });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/neumaticos/dataTable', true).'",
        "bDeferRender": true,
        "aoColumns": [
            {"bVisible": false },
            {"sType": "date-uk"},
            null,
            null,
            null,
            null,
            null,
            null,
            {"bSortable": false},
            null,
            null,
            null,
            {"bSortable": false},
            {"bSortable": false}
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
    $("#filter_medida").keyup(function(){
        oTable.fnFilter($(this).val(),5);
    });
     $("#filter_temporada").change(function(){
        if($(this).val() == ""){
            oTable.fnFilter($(this).val(),6);
        }else{
            oTable.fnFilter($("#filter_temporada option:selected").text(),6);
         }
     });
    $("#filter_identificador").keyup(function(){
        oTable.fnFilter($(this).val(),7);
    });
    
    $("#filter_unidad").keyup(function(){
        oTable.fnFilter($(this).val(),9);
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

            createWindow("w_neumaticos_view","Gestion de nuematico","<?php echo $this->Html->url('/neumaticos/editar', true);?>/"+data[0],"450","350");

        }
    }
    function cambiarEstado() {
        var ok = 1;
        var estadoAnterior = null; // Variable para almacenar el estado de la primera fila seleccionada
        var unidadAnterior = null; // Variable para almacenar la unidad de la primera fila seleccionada

        var row = $("#dataTable tr.row_selected");
        if (row.length == 0) {
            alert('Debe seleccionar un registro');
        } else {
            var selected = new Array();
            $('.row_selected').each(function (e, i) {

                var data = oTable.fnGetData(i);

                var estadoActual = data[11]; // Estado actual en la columna 11
                var unidadActual = data[9]; // Unidad actual en la columna 9

                if (estadoActual == 'Baja') {
                    alert("No se pueden cambiar los estados de los neumaticos dados de baja");
                    ok = 0;
                    return false; // Detener el bucle each si los estados son diferentes
                }

                // Verificar si es la primera fila seleccionada
                if (e === 0) {
                    estadoAnterior = estadoActual; // Almacenar el estado de la primera fila
                    unidadAnterior = unidadActual; // Almacenar la unidad de la primera fila
                } else {
                    // Comparar el estado actual con el estado almacenado
                    if (estadoActual !== estadoAnterior) {
                        alert("Todas las filas deben tener el mismo estado.");
                        ok = 0;
                        return false; // Detener el bucle each si los estados son diferentes
                    }
                    // Comparar la unidad actual con la unidad almacenada
                    if (unidadActual !== unidadAnterior) {
                        alert("Todas las filas deben pertenecer a la misma unidad.");
                        ok = 0;
                        return false; // Detener el bucle each si las unidades son diferentes
                    }
                }


                // Verificar si ya se han seleccionado 4 filas
                if (selected.length >= 4) {
                    alert("No puedes seleccionar más de 4 filas.");
                    ok = 0;
                    return false; // Detener el bucle each si ya se han seleccionado 4 filas

                }

                selected.push(data[0]); // Agregar el elemento al arreglo de selección





            });
            //console.log(ok);
            if (ok){
                createWindow('w_neumaticos_estado','Cambiar estado de neumaticos','<?php echo $this->Html->url('/neumaticos/cambiar', true);?>/'+selected.join(','),'450','350');
// Hacer algo con el arreglo 'selected'
                //
            }



        }
    }

    function baja(){

        var row = $("#dataTable tr.row_selected");
        if(row.length == 0){
            alert('Debe seleccionar un registro');
        }else{
            var data = oTable.fnGetData(row[0]);
            if (data[11]=='Baja'){
                alert("Neumatico ya dado de baja");

                return false; // Detener el bucle each si ya se han seleccionado 4 filas

            }
            else{
                createWindow('w_neumaticos_baja','Baja de neumatico','<?php echo $this->Html->url('/neumaticos/baja', true);?>/'+data[0],'450','350');
            }
        }




    }

    function detalle(){

        var row = $("#dataTable tr.row_selected");
        if(row.length == 0){
            alert('Debe seleccionar un registro');
        }else{
            var data = oTable.fnGetData(row[0]);


                createWindow('w_neumaticos_detalle','Detalle de neumatico','<?php echo $this->Html->url('/neumaticos/index2', true);?>/'+data[0],'650','350');

        }




    }


</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_neumaticos_add','Gestion de neumaticos','<?php echo $this->Html->url('/neumaticos/crear', true);?>','450','350');">Alta</a></li>
    <li class="boton editar"><a onclick="editar();">Editar</a></li>
    <li class="boton editar"><a onclick="cambiarEstado();">Estado</a></li>
    <li class="boton anular"> <a onclick="baja();">Baja</a></li>
    <li class="boton consultar"> <a onclick="detalle();">Detalle</a></li>

    <!--<li class="filtro">Buscar <input id="data_search" type="text" with="10"/></li>-->
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
            <input type="text" style="width: 90%;" id="filter_medida" />
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



        </th>
        <th width="30">

            <input type="text" style="width: 90%;" id="filter_unidad" />

        </th>
        <th width="30">

            <select id="filter_posicion">
                <option value="">Posicion</option>
                <option>DI</option>
                <option>DD</option>
                <option>TI</option>
                <option>TD</option>
                <option>Auxilio</option>
            </select>

        </th>
        <th width="30">

            <select id="filter_estado">
                <option value="0">Estado</option>
                <option value="1" selected="selected">Activas</option>
                <option>En uso</option>
                <option>En deposito</option>
                <option>Baja</option>
            </select>

        </th>
        <th width="30">
            <!--<input type="text" style="width: 90%;" id="filter_antiguedad" />-->
        </th>
        <th width="20">
            <!--<input type="text" style="width: 90%;" id="filter_km" />-->
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
