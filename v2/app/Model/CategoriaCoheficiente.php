<?php
class CategoriaCoheficiente extends AppModel {
    public $useTable = 'categoria_coheficiente';
    public $belongsTo = array('Categoria');
    
    public $validate = array(
    	'categoria_id' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar una categoría'
        ),
        'dia' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un dia'
        ),
        'coheficiente' => array(
            'rule'    => array('range', -0.9,1.1),
            'required'   => true,
            'message' => 'Ingrese un numero entre 0 y 1'
        )
    );
    
	
}
?>
