<?php
class Feriado extends AppModel {
   
	public $hasMany = array('FeriadoHorario');
	
    public $validate = array(
        
       
       
       
        'fecha' => array(
            'format' => array(
                'required'   => true,
	            'rule' => array('date','dmy'),
	            'message' => 'Ingrese una fecha valida'
            ),
            'after' => array(
                'rule' => 'actual_futura',
                'message' => 'Seleccione fecha actual o futura'
            )
        )
       
    );
    
   
	public function actual_futura($data){
        $fecha_part = explode("/",$this->data['Feriado']['fecha']);
        
        $fecha = $fecha_part[2]."-".$fecha_part[1]."-".$fecha_part[0];
        $hoy = date("Y-m-d");
       
        if($hoy <= $fecha) return true;
    }
   

    public function beforeSave($options = Array()) {
    
    	if (!empty($this->data['Feriado']['fecha'])) {
            $this->data['Feriado']['fecha'] = $this->dateFormatBeforeSave($this->data['Feriado']['fecha']);
        }  	
    	
        
    	
        return true;
    }
    
	public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
           
            if (!empty($val) and isset($val['Feriado']['fecha'])) {
                $results[$key]['Feriado']['fecha']= $this->dateFormatAfterFind($val['Feriado']['fecha']);
            }
            
        }
        return $results;
    }
    
}
?>
