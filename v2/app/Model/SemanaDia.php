<?php
class SemanaDia extends AppModel {
    
    public $hasMany = array('DiaHorario');
    
    public $validate = array(
        'dia' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un dia'
        )
    );
    
   
    
    
    
}
?>
