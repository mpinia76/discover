<?php
//calendario
$this->Js->buffer('$.datepicker.regional[ "es" ]');
$this->Js->buffer('$(".datepicker").datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" });');

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
                var strDesde = $("#retiro").val().split("/"); 
				var desde = strDesde[2]+"-"+strDesde[1]+"-"+strDesde[0];	
				var strHasta = $("#devolucion").val().split("/"); 
				var hasta = strHasta[2]+"-"+strHasta[1]+"-"+strHasta[0];
				var strHoraDesde = $("#HoraRetiro").val().split(":"); 
				var horaDesde = strHoraDesde[0]+"-"+strHoraDesde[1];
				var strHoraDevolucion = $("#HoraDevolucion").val().split(":"); 
				var horaDevolucion = strHoraDevolucion[0]+"-"+strHoraDevolucion[1];	
                createWindow("w_reservas_add","Crear reserva","'.$this->Html->url('/reservas/crear', true).'/0/"+data[0]+"/"+desde+"/"+hasta+"/"+horaDesde+"/"+horaDevolucion,"630","600");
            });
            $("#dataTable tr").click(function(e){
                $("#dataTable tr").removeClass("row_selected");
                $(this).toggleClass("row_selected");
            });
        },
        "aaSorting": [],
		"sAjaxSource": "'.$this->Html->url('/reservas/dataTable2', true).'",
        
        "aoColumns": [
            {"bVisible": false },
            null,
           	null,
           	null,
            null
            
        ]
    });
    $(".date_filter").change(function(){ oTable.fnDraw(); })
    
	
');
//extra libreria para agregar filtro de fecha
echo $this->Html->script('dataTables.dateSort', array('block' => 'extra_scripts'));

//abrir ventanas
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_ventas_check").getPosition();
    xpos = position[0];
    ypos = position[1];
');
?>

<ul class="action_bar">
   
    <li class="boton editar"><a onclick="editar();">Crear reserva</a></li>
</ul>
  
  

<?php 
   echo $this->Form->create(false, array('class' => 'form-inline')); 
   
   echo $this->Form->input('retiro',array('label' => false,'placeholder' => 'Retiro', 'class' => 'datepicker', 'type' => 'text','style' => 'float: left; display:inline;'));
   echo $this->Form->input('HoraRetiro',array('label'=>false,'options' => $horariosRetiro, 'type'=>'select','style' => 'float: left; display:inline;')); 
   //echo $this->Form->input('HoraRetiro',array('label' => false,'type' => 'text', 'class' => 'number timepicker','style' => 'float: left;width:50px;display:inline;'));
  
   echo $this->Form->input('devolucion',array('label' => false,'placeholder' => 'Devolucion', 'class' => 'datepicker', 'type' => 'text','style' => 'float: left; display:inline;'));
   echo $this->Form->input('HoraDevolucion',array('label' => false,'options' => $horariosDevolucion, 'type'=>'select','style' => 'float: left; display:inline;'));
   //echo $this->Form->input('HoraDevolucion',array('label'=>false,'type' => 'text', 'class' => 'number timepicker','style' => 'float: left;width:50px;display:inline;'));
echo $this->Form->end('Ver');
?>
 



 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable">
    <thead>
        <tr>
            <th width="50">Id</th>
            <th width="100">Categoria</th>
            <th width="100">Marca</th>
            <th width="100">Modelo</th>
            <th width="100">Patente</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<script>


function limpiarFechas(){
	$('#desde').val('');
	$('#hasta').val('');
}
function editar(){
    var row = $("#dataTable tr.row_selected");
    if(row.length == 0){
        alert('Debe seleccionar un registro');
    }else{
        var data = oTable.fnGetData(row[0]);
        var strDesde = $("#retiro").val().split("/"); 
		var desde = strDesde[2]+"-"+strDesde[1]+"-"+strDesde[0];	
		var strHasta = $("#devolucion").val().split("/"); 
		var hasta = strHasta[2]+"-"+strHasta[1]+"-"+strHasta[0];
		var strHoraDesde = $("#HoraRetiro").val().split(":"); 
		var horaDesde = strHoraDesde[0]+"-"+strHoraDesde[1];
		var strHoraDevolucion = $("#HoraDevolucion").val().split(":"); 
		var horaDevolucion = strHoraDevolucion[0]+"-"+strHoraDevolucion[1];	

        createWindow("w_reservas_add","Crear reserva","<?php echo $this->Html->url('/reservas/crear', true);?>/0/"+data[0]+"/"+desde+"/"+hasta+"/"+horaDesde+"/"+horaDevolucion,"630","600");
        
    }
}

$('#retiro').change(function(){
	
    if($(this).val()!=''){
        $.ajax({
            url: '<?php echo $this->Html->url('/reservas/getHorarios/', true);?>'+$(this).val(),
            dataType: 'html',
            
            success: function(data){
                $('#HoraRetiro').html(data);
            }
        });
    }else{
         $('#HoraRetiro').html('');
    }
})

$('#devolucion').change(function(){
	
    if($(this).val()!=''){
        $.ajax({
            url: '<?php echo $this->Html->url('/reservas/getHorarios/', true);?>'+$(this).val(),
            dataType: 'html',
            
            success: function(data){
                $('#HoraDevolucion').html(data);
            }
        });
    }else{
         $('#HoraDevolucion').html('');
    }
})

$("#index_checkForm").on("submit", function(){
    var strDesde = $("#retiro").val().split("/"); 
    if(strDesde==''){
    	alert('Debe seleccionar una fecha de retiro');
    	return false;
    }
    else{
    	var desde = strDesde[2]+"-"+strDesde[1]+"-"+strDesde[0];	
		var strHasta = $("#devolucion").val().split("/"); 
		if(strHasta==''){
	    	alert('Debe seleccionar una fecha de devolucion');
	    	return false;
	    }
	    else{
	    	var hasta = strHasta[2]+"-"+strHasta[1]+"-"+strHasta[0];
			var strHoraDesde = $("#HoraRetiro").val().split(":"); 
			var horaDesde = strHoraDesde[0]+"-"+strHoraDesde[1];
			var strHoraDevolucion = $("#HoraDevolucion").val().split(":"); 
			var horaDevolucion = strHoraDevolucion[0]+"-"+strHoraDevolucion[1];	
			var fechaHoraDesde = desde+horaDesde;
			var fechaHoraHasta = hasta+horaDevolucion;
			
			if(fechaHoraHasta<=fechaHoraDesde){
				alert('La devolucion debe ser posterior al retiro');
		    	return false;
			}	
	    }
		
    }
	
	
});


</script>