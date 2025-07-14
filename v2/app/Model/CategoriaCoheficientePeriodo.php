<?php
class CategoriaCoheficientePeriodo extends AppModel {
   
    public $validate = array(

        'desde' => array(
            'format' => array(
                'required'   => true,
                'rule' => array('date','dmy'),
                'message' => 'Ingrese una fecha valida'
            )
        ),



        'hasta' => array(
            'format' => array(
                'required'   => true,
                'rule' => array('date','dmy'),
                'message' => 'Ingrese una fecha valida'
            )
        ),
    );


    public function after_hasta($data){

        $desde = strtotime ($this->data['CategoriaCoheficientePeriodo']['desde']);
        $hasta = strtotime ($this->data['CategoriaCoheficientePeriodo']['hasta']);


        if($desde <= $hasta) return true;
    }

    public function beforeSave() {
        if (!empty($this->data['CategoriaCoheficientePeriodo']['desde'])) {
            $this->data['CategoriaCoheficientePeriodo']['desde'] = $this->dateFormatBeforeSave($this->data['CategoriaCoheficientePeriodo']['desde']);
        }
        if (!empty($this->data['CategoriaCoheficientePeriodo']['hasta'])) {
            $this->data['CategoriaCoheficientePeriodo']['hasta'] = $this->dateFormatBeforeSave($this->data['CategoriaCoheficientePeriodo']['hasta']);
        }
        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {

            if (!empty($val) and isset($val['CategoriaCoheficientePeriodo']['desde'])) {
                $results[$key]['CategoriaCoheficientePeriodo']['desde']= $this->dateFormatAfterFind($val['CategoriaCoheficientePeriodo']['desde']);
            }
            if (!empty($val) and isset($val['CategoriaCoheficientePeriodo']['hasta'])) {
                $results[$key]['CategoriaCoheficientePeriodo']['hasta']= $this->dateFormatAfterFind($val['CategoriaCoheficientePeriodo']['hasta']);
            }

        }
        return $results;
    }
   
    
}
?>
