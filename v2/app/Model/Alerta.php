<?php
class Alerta extends AppModel {

    public $hasMany = array('UnidadAlerta','UnidadAlerta');

    public $virtualFields = array(
        'alertaCompleta' => "CONCAT(alerta,' - ', corta)"
    );

    public $validate = array(
        'tipo' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un tipo'
        ),
        'nivel' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nivel'
        ),
        'unidad' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una unidad'
        ),
        'alerta' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre'
        ),
        'inicio_fecha' => array(
            'rule'     => array('date','dmy'),
            'required' => false,
            'allowEmpty' => true,
            'message' => 'Ingrese una fecha valida'
        )
    );




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
