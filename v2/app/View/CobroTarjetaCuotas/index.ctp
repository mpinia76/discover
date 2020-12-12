<?php
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
                createWindow("w_'.$this->params['controller'].'_view","Editar","'.$this->Html->url('/'.$this->params['controller'].'/edit', true).'/"+data[0],"430","350");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/'.$this->params['controller'].'/dataTable', true).'",
        "bDeferRender": true,
        "aoColumns" : [
          {"bVisible" : false },
          null,
          null,
          null,
          null,
          null,
          {"bVisible" : false }
        ]
    });
    $(".date_filter").change(function(){ oTable.fnDraw(); })
    $("#data_search").keyup( function () { oTable.fnFilter(this.value); });
');
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_'.$this->params['controller'].'").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>
<script>
function edit(){
    var row = $('tr.row_selected');
    var data = oTable.fnGetData(row[0]);
    createWindow('w_<?php echo $this->params['controller'];?>_edit','Editar','<?php echo $this->Html->url('/'.$this->params['controller'].'/edit', true);?>/'+data,'430','350');
}
</script>
<ul class="action_bar">
    <li class="boton agregar"><a onclick="createWindow('w_<?php echo $this->params['controller'];?>_add','Crear','<?php echo $this->Html->url('/'.$this->params['controller'].'/add', true);?>','430','350');">Crear</a></li>
    <li class="boton editar"><a onclick="edit();">Editar</a></li>

</ul>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
        <tr>
            <th>id</th>
            <th>Terminal</th>
            <th>Marca</th>
            <th>Cuota</th>
            <th>Mascara Web</th>
            <th>Interes</th>
            <th>Disponible venta on line</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
