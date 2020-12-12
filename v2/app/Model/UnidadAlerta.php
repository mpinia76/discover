<?php
class UnidadAlerta extends AppModel
{
    public $belongsTo = array('Unidad', 'Alerta');


    public $hasMany = array('GestionAlerta'=>array('className'=>'GestionAlerta',
        'foreignKey'=>'unidad_alerta_id',
        'dependent'=>true, // true without single quote
        'exclusive'=>true
    ));




    public function beforeSave($options = Array()) {
        if (!empty($this->data['UnidadAlerta']['inicio_fecha'])) {
            $this->data['UnidadAlerta']['inicio_fecha'] = $this->dateFormatBeforeSave($this->data['UnidadAlerta']['inicio_fecha']);

        }


        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['UnidadAlerta']['inicio_fecha'])) {
                $results[$key]['UnidadAlerta']['inicio_fecha']= $this->dateFormatAfterFind($val['UnidadAlerta']['inicio_fecha']);
            }


        }
        return $results;
    }
}
