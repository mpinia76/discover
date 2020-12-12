<?php
class GestionAlerta extends AppModel
{
    public $belongsTo = array('UnidadAlerta', 'Alerta');







    public function beforeSave($options = Array()) {
        if (!empty($this->data['GestionAlerta']['inicio_fecha'])) {
            $this->data['GestionAlerta']['inicio_fecha'] = $this->dateFormatBeforeSave($this->data['GestionAlerta']['inicio_fecha']);

        }
        if (!empty($this->data['GestionAlerta']['fecha_resolucion'])) {
            $this->data['GestionAlerta']['fecha_resolucion'] = $this->dateFormatBeforeSave($this->data['GestionAlerta']['fecha_resolucion']);

        }

        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['GestionAlerta']['inicio_fecha'])) {
                $results[$key]['GestionAlerta']['inicio_fecha']= $this->dateFormatAfterFind($val['GestionAlerta']['inicio_fecha']);
            }
            if (!empty($val) and isset($val['GestionAlerta']['fecha_resolucion'])) {
                $results[$key]['GestionAlerta']['fecha_resolucion']= $this->dateFormatAfterFind($val['GestionAlerta']['fecha_resolucion']);
            }

        }
        return $results;
    }
}
