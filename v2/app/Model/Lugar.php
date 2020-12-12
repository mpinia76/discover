<?php
class Lugar extends AppModel {
    public $displayField = 'lugar';
    public $validate = array(
        'lugar' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe completar con un nombre'
        )
    );
    
    public $hasMany = array(
		'Lugar_Retiro' => array(
		'className' => 'Reserva',
		'foreignKey' => 'lugar_retiro_id'
		),
		'Lugar_Devolucion' => array(
		'className' => 'Reserva',
		'foreignKey' => 'lugar_devolucion_id'
		)
	);
}
?>
