<?php
class Neumatico extends AppModel {

    //public $belongsTo = array('Unidad');

    public $hasMany = array('NeumaticoEstado');

    public $validate = array(
        'fecha' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'message' => 'Ingrese una fecha valida'
        ),
        'condicion' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione una condicion'
        ),
        'km' => array(
            'rule'    => array('range', -1,9999999),
            'required'   => true,
            'message' => 'Ingrese un numero'
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
        'identificador' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe generar un identificador'
        ),
        'estado' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione un estado'
        ),
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
