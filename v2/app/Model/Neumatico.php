<?php
class Neumatico extends AppModel {

    public $belongsTo = array('Unidad');



    public $validate = array(
        'fecha' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'allowEmpty' => true,
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
            'rule'    => array('between', 0, 10),
            'required'   => true,
            'message' => 'Ingrese una medida (no mas de 10 caracteres)'
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
            'rule' => array('numeric'), // Asegura que solo sean números
            'allowEmpty' => true, // Permite que el campo esté vacío
            'message' => 'Ingrese solo números'
        ),
        'validateDibujoLength' => array(
            'rule' => array('validateDibujoLength'), // Función de validación personalizada
            'required' => true, // El campo es requerido
            'message' => 'Ingrese un máximo de 2 dígitos'
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

    public function validateDibujoLength($check) {
        $value = array_values($check)[0];
        return strlen($value) <= 2;
    }



 	public function beforeSave($options = Array()) {

    	if(($this->data['Alerta']['inicio_fecha']!='')){
            $this->data['Alerta']['inicio_fecha'] = $this->dateFormatBeforeSave($this->data['Alerta']['inicio_fecha']);
        }

        if(($this->data['Alerta']['actual_fecha']!='')){
            $this->data['Alerta']['actual_fecha'] = $this->dateFormatBeforeSave($this->data['Alerta']['actual_fecha']);
        }

        return true;
    }


	public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['Alerta']['inicio_fecha'])) {
                $results[$key]['Alerta']['inicio_fecha']= $this->dateFormatAfterFind($val['Alerta']['inicio_fecha']);
            }

            if (!empty($val) and isset($val['Alerta']['actual_fecha'])) {
                $results[$key]['Alerta']['actual_fecha']= $this->dateFormatAfterFind($val['Alerta']['actual_fecha']);
            }
        }
        return $results;
    }
}
?>
