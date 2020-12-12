<?php
class MarcaTarjeta extends AppModel {
    public $displayField = 'marca';
    public $validate = array(
        'marca' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre valido'
        )
    );
}
?>
