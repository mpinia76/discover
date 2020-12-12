<?php
class Descuento extends AppModel {
    public $displayField = 'descuento';
    public $validate = array(
        'descuento' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre valido'
        ),
         
        'coheficiente' => array(
            'rule'    => array('range', -0.9,2.1),
            'required'   => true,
            'message' => 'Ingrese un numero entre 0 y 2'
        ),
         'parcial' => array(
            'required'   => true,
            'rule' => array('range', -0.9,2.1),
            'message' => 'Ingrese un numero entre 0 y 2'
        )
    );
    
	
}
?>
