<?php
class HoraAdicional extends AppModel {
	public $displayField = 'hora';
    public $validate = array(
        'hora' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre valido'
        ),
         'coheficiente' => array(
            'rule'    => array('range', -0.9,1.1),
            'required'   => true,
            'message' => 'Ingrese un numero entre 0 y 1'
        )
    );
    
	
}
?>
