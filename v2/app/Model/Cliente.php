<?php
class Cliente extends AppModel {
    public $displayField = 'nombre_apellido';
    public $validate = array(
        'nombre_apellido' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nombre y apellido valido'
        ),
         /*'dni' => array(
            'rule'     => 'numeric',
            //'required' => true,
            'allowEmpty' => true,
            'message' => 'Ingrese solo numeros'
        ),
       'sexo' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Seleccione un sexo'
        ),
        
        'telefono' => array(
            'rule' => 'telefono_o_celular',
            'message' => 'Ingrese un telefono o celular valido'
        ),
        'celular' => array(
            'rule' => 'telefono_o_celular',
            'message' => 'Ingrese un telefono o celular valido'
        ),*/
        'email' => array(
            'rule'     => 'email',
            //'required' => true,
            'allowEmpty' => true,
            'message' => 'Ingrese un email valido'
        ),
        'email2' => array(
            'rule'     => 'email',
            //'required' => true,
            'allowEmpty' => true,
            'message' => 'Ingrese un email valido'
        ),
        /*'nro_licencia_de_conducir' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un nro licencia de conducir'
        ),*/
        'vencimiento' => array(
            'rule'     => array('date','dmy'),
            'required' => false,
            'allowEmpty' => true,
            'message' => 'Ingrese una fecha valida'
        )
    );
    public function telefono_o_celular(){
        if((isset($this->data['Cliente']['telefono']) and $this->data['Cliente']['telefono'] != '') or (isset($this->data['Cliente']['celular']) and $this->data['Cliente']['celular'] != '')) return true;
    }
    
	
    
 	public function beforeSave($options = Array()) {
    		
    	if(($this->data['Cliente']['nacimiento']!='')){
            $this->data['Cliente']['nacimiento'] = $this->dateFormatBeforeSave($this->data['Cliente']['nacimiento']);
        }
        
 		if(($this->data['Cliente']['vencimiento']!='')){
            $this->data['Cliente']['vencimiento'] = $this->dateFormatBeforeSave($this->data['Cliente']['vencimiento']);
        }
 		if((($this->data['Cliente']['dni']!=''))&&(($this->data['Cliente']['sexo']!=''))&&(($this->data['Cliente']['nacionalidad']=='Argentina'))){
            $this->data['Cliente']['cuit'] = $this->Format_toCuil($this->data['Cliente']['dni'],$this->data['Cliente']['sexo']);
        }
        else{
        	$this->data['Cliente']['cuit'] ='';
        }
        return true;
    }
   
    
	public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['Cliente']['nacimiento'])) {
                $results[$key]['Cliente']['nacimiento']= $this->dateFormatAfterFind($val['Cliente']['nacimiento']);
            }
            if (!empty($val) and isset($val['Cliente']['vencimiento'])) {
                $results[$key]['Cliente']['vencimiento']= $this->dateFormatAfterFind($val['Cliente']['vencimiento']);
            }
           
        }
        return $results;
    }
}
?>
