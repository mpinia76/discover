<?php
$this->Js->buffer('
    dhxWins = parent.dhxWins;
    position = dhxWins.window("w_extras").getPosition();
    xpos = position[0];
    ypos = position[1];
');

//formulario
echo $this->Form->create(null, array('url' => '/extras/crear','inputDefaults' => (array('div' => 'ym-gbox'))));

$this->Js->get('#ExtraExtraRubroId');
$this->Js->event(
    'change',
    $this->Js->request(array(
      'action' => 'getSubrubros'), array(
      'async' => true,
      'update' => '#subrubros',
      'data' => '{rubro_id:$(this).val()}',
      'dataExpression' => true
    ))
);
echo $this->Form->input('Extra.extra_rubro_id',array('options' => $extra_rubros, 'empty' => 'Seleccionar', 'type'=>'select'));
?> 
<span id="subrubros"></span>
<?php 

echo $this->Form->input('Extra.detalle',array('label' => 'Detalle'));
echo $this->Form->input('Extra.tarifa',array('label' => 'Precio $','type' => 'text', 'class' => 'number'));
echo $this->Form->input('Extra.activo',array('checked'=>'checked'));
echo $this->Form->end();
?>
<span onclick="guardar('guardar.json',$('form').serialize(),{id:'w_extras',url:'v2/extras/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>