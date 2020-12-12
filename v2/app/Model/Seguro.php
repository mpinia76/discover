<?php
class Seguro extends AppModel {
	public $belongsTo = array('Categoria');
    public $displayField = 'seguro';
    public $validate = array(
        'seguro' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre valido'
        ),
         'importe' => array(
            'required'   => true,
            'rule' => 'numeric',
            'message' => 'Ingrese un importe valido'
        )
    );
}
?>
