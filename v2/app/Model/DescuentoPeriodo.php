<?php
class DescuentoPeriodo extends AppModel {
   
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

        $desde = strtotime ($this->data['DescuentoPeriodo']['desde']);
        $hasta = strtotime ($this->data['DescuentoPeriodo']['hasta']);


        if($desde <= $hasta) return true;
    }

    public function beforeSave() {
        if (!empty($this->data['DescuentoPeriodo']['desde'])) {
            $this->data['DescuentoPeriodo']['desde'] = $this->dateFormatBeforeSave($this->data['DescuentoPeriodo']['desde']);
        }
        if (!empty($this->data['DescuentoPeriodo']['hasta'])) {
            $this->data['DescuentoPeriodo']['hasta'] = $this->dateFormatBeforeSave($this->data['DescuentoPeriodo']['hasta']);
        }
        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {

            if (!empty($val) and isset($val['DescuentoPeriodo']['desde'])) {
                $results[$key]['DescuentoPeriodo']['desde']= $this->dateFormatAfterFind($val['DescuentoPeriodo']['desde']);
            }
            if (!empty($val) and isset($val['DescuentoPeriodo']['hasta'])) {
                $results[$key]['DescuentoPeriodo']['hasta']= $this->dateFormatAfterFind($val['DescuentoPeriodo']['hasta']);
            }

        }
        return $results;
    }
   
    
}
?>
