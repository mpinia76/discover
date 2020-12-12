<?php
echo $this->Form->input('HoraRetiro',array('options' => $horarios, 'type'=>'select', 'label' => '(HH:MM)', 'onChange' => 'igualarDevolucion()'));
?>