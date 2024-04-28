<?php
class NeumaticoEstado extends AppModel
{
    public $belongsTo = array('Neumatico');







    public function beforeSave($options = Array()) {
        if (!empty($this->data['NeumaticoEstado']['fecha'])) {
            $this->data['NeumaticoEstado']['fecha'] = $this->dateFormatBeforeSave($this->data['NeumaticoEstado']['fecha']);

        }
        if (!empty($this->data['NeumaticoEstado']['desde'])) {
            $this->data['NeumaticoEstado']['desde'] = $this->dateFormatBeforeSave($this->data['NeumaticoEstado']['desde']);

        }
        if (!empty($this->data['NeumaticoEstado']['hasta'])) {
            $this->data['NeumaticoEstado']['hasta'] = $this->dateFormatBeforeSave($this->data['NeumaticoEstado']['hasta']);

        }


        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['NeumaticoEstado']['fecha'])) {
                $results[$key]['NeumaticoEstado']['fecha']= $this->dateFormatAfterFind($val['NeumaticoEstado']['fecha']);
            }
            if (!empty($val) and isset($val['NeumaticoEstado']['desde'])) {
                $results[$key]['NeumaticoEstado']['desde']= $this->dateFormatAfterFind($val['NeumaticoEstado']['desde']);
            }
            if (!empty($val) and isset($val['NeumaticoEstado']['hasta'])) {
                $results[$key]['NeumaticoEstado']['hasta']= $this->dateFormatAfterFind($val['NeumaticoEstado']['hasta']);
            }

        }
        return $results;
    }
}
