<?php
echo $this->Form->create(null, array('url' => '/cobro_tarjeta_cuotas/edit','inputDefaults' => (array('div' => 'ym-gbox'))));
echo $this->Form->hidden('CobroTarjetaCuota.id');
echo $this->Form->hidden('CobroTarjetaCuota.posnet_id', array('value' => $cobro_tarjeta_cuota['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']));
echo $this->Form->hidden('CobroTarjetaCuota.cobro_tarjeta_tipo_id');
echo $this->Form->hidden('CobroTarjetaCuota.cuota');
echo $this->Form->hidden('CobroTarjetaCuota.mascara_web');
echo $this->Form->hidden('CobroTarjetaCuota.interes');
echo $this->Form->input('CobroTarjetaCuota.posnet_id',array('options' => $posnets, 'empty' => 'Seleccionar', 'type'=>'select', 'label' => 'Terminal', 'value' => $cobro_tarjeta_cuota['CobroTarjetaTipo']['cobro_tarjeta_posnet_id'],'disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaCuota.cobro_tarjeta_tipo_id',array('options' => $marcas, 'empty' => 'Seleccionar', 'type'=>'select', 'div' => 'ym-gbox', 'label' => 'Marca','disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaCuota.cuota',array('label' => 'Cuota','type'=>'text', 'class'=>'number','disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaCuota.mascara_web',array('label' => 'Mascara WEB','type'=>'text', 'class'=>'number','disabled'=>'disabled'));
echo $this->Form->input('CobroTarjetaCuota.interes',array('label' => 'Coeficiente','type'=>'text', 'class'=>'number','disabled'=>'disabled'));

echo $this->Form->input('CobroTarjetaCuota.activa',array('default' => '0', 'label' => 'Disponible venta on line'));
echo $this->Form->end();
?>
<span onclick="guardar('<?php echo $this->Html->url('/cobro_tarjeta_cuotas/guardar.json', true);?>',$('form').serialize(),{id:'w_habilitar_tarjetas_cuotas',url:'v2/cobro_tarjeta_cuotas/index2'});" class="boton guardar">Guardar <img src="<?php echo $this->webroot; ?>img/loading_save.gif" class="loading" id="loading_save" /></span>
