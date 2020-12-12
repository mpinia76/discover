<?php
echo $this->Form->create(null, array('url' => '/cobro_tarjeta_tipos/add','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('CobroTarjetaTipo.id');
echo $this->Form->hidden('CobroTarjetaTipo.cobro_tarjeta_posnet_id');
echo $this->Form->hidden('CobroTarjetaTipo.cuenta_id');
echo $this->Form->hidden('CobroTarjetaTipo.marca');
echo $this->Form->hidden('CobroTarjetaTipo.nro_comercio');
echo $this->Form->hidden('CobroTarjetaTipo.mostrar');
echo $this->Form->input('CobroTarjetaTipo.cobro_tarjeta_posnet_id',array('empty' => 'Seleccionar...','label' => 'Locacion', 'options' => $posnets,'disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaTipo.cuenta_id',array('empty' => 'Seleccionar...','label' => 'Cuenta de acreditacion', 'options' => $cuentas,'disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaTipo.marca',array('empty' => 'Seleccionar...','label' => 'Marca', 'options' => $tarjetas, 'disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaTipo.nro_comercio', array('disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaTipo.id_decidir');
echo $this->Form->input('CobroTarjetaTipo.activa',array('default' => '0', 'label' => 'Disponible venta on line'));
echo $this->Form->end();
?>
<span onclick="guardar('<?php echo $this->Html->url('/cobro_tarjeta_tipos/guardar.json', true);?>',$('form').serialize(),{id:'w_habilitar_tarjetas',url:'v2/cobro_tarjeta_tipos/index2'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
