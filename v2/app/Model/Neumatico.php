<?php
class Neumatico extends AppModel {

    public $belongsTo = array('Unidad');



    public $validate = array(
        'fecha' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'message' => 'Ingrese una fecha valida'
        ),
        'marca' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una marca'
        ),
        'modelo' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un modelo'
        ),
        'medida' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una medida'
        ),
        'fabricacion' => array(
            'rule'    => array('between', 4, 4),
            'required'   => true,
            'message' => 'Ingrese 2 digitos para la semana y 2 para el año'
        ),
        'posicion' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione una posicion'
        ),
        'temporada' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione una temporada'
        ),
        'dibujo' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una medida'
        ),
        'estado' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione un estado'
        ),
        'identificador' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe generar un identificador'
        )
        /*'unidad' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una unidad'
        ),*/


    );





 	public function beforeSave($options = Array()) {

    	if(($this->data['Neumatico']['fecha']!='')){
            $this->data['Neumatico']['fecha'] = $this->dateFormatBeforeSave($this->data['Neumatico']['fecha']);
        }



        return true;
    }


	public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['Neumatico']['fecha'])) {
                $results[$key]['Neumatico']['fecha']= $this->dateFormatAfterFind($val['Neumatico']['fecha']);
            }


        }
        return $results;
    }
}
?>
