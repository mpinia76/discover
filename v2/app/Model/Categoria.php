<?php
class Categoria extends AppModel {
    public $displayField = 'categoria';
    public $belongsTo = array(
        'ConceptoFacturacion' => array(
            'className'    => 'ConceptoFacturacion',
            'foreignKey'   => 'concepto_facturacion_id'
        )
    );
    public $validate = array(
        'categoria' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre valido'
        )
    );
}
?>
