<?php
echo $this->Form->create(null, array('url' => '/cobro_tarjeta_tipos/add','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('CobroTarjetaTipo.id');
echo $this->Form->hidden('CobroTarjetaTipo.id_decidir');
echo $this->Form->hidden('CobroTarjetaTipo.activa');
echo $this->Form->input('CobroTarjetaTipo.cobro_tarjeta_posnet_id',array('empty' => 'Seleccionar...','label' => 'Locacion', 'options' => $posnets));
echo $this->Form->input('CobroTarjetaTipo.cuenta_id',array('empty' => 'Seleccionar...','label' => 'Cuenta de acreditacion', 'options' => $cuentas));
echo $this->Form->input('CobroTarjetaTipo.marca',array('empty' => 'Seleccionar...','label' => 'Marca', 'options' => $tarjetas));
echo $this->Form->input('CobroTarjetaTipo.nro_comercio');
echo $this->Form->input('CobroTarjetaTipo.mostrar',array('label' => 'Mostrar al cobrar'));
echo $this->Form->end();
?>
<span onclick="guardar('<?php echo $this->Html->url('/cobro_tarjeta_tipos/guardar.json', true);?>',$('form').serialize(),{id:'w_cobro_tarjeta_tipos',url:'v2/cobro_tarjeta_tipos/index'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
