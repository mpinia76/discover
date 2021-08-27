<?php
class ReservaConductor extends AppModel {

    public $validate = array(

        /*'email' => array(
            'rule'     => 'email',
            //'required' => true,
            'allowEmpty' => true,
            'message' => 'Ingrese un email valido'
        ),*/

        'vencimiento' => array(
            'rule'     => array('date','dmy'),
            'required' => false,
            'allowEmpty' => true,
            'message' => 'Hay un error en la fecha de vencimiento de uno de los conductores'
        )
    );


    public function beforeSave($options = Array()) {



        if(($this->data['ReservaConductor']['vencimiento']!='')){
            $this->data['ReservaConductor']['vencimiento'] = $this->dateFormatBeforeSave($this->data['ReservaConductor']['vencimiento']);
        }

        return true;
    }


    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {

            if (!empty($val) and isset($val['ReservaConductor']['vencimiento'])) {
                $results[$key]['ReservaConductor']['vencimiento']= $this->dateFormatAfterFind($val['ReservaConductor']['vencimiento']);
            }

        }
        return $results;
    }

}
?>
