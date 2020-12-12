<?php
class FeriadoHorario extends AppModel {
   
    public $validate = array(
        
        'hora_inicio' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar una hora'
        ),
        
       
        
        'hora_fin' => array(
            'format' => array(
                'required'   => true,
	            'rule' => 'notEmpty',
	            'message' => 'Debe seleccionar una hora'
            ),
            'after' => array(
                'rule' => 'after_devolucion',
                'message' => 'La hora debe ser posterior al inicio'
            )
        )
    );
    
   
    
    public function after_devolucion($data){
    	
        
        $retiro = strtotime('2017-01-01 '.str_replace(' ', '', $this->data['FeriadoHorario']['hora_inicio']));
        $devolucion = strtotime('2017-01-01 '.str_replace(' ', '', $this->data['FeriadoHorario']['hora_fin']));
        //echo $retiro_part[2]."-".$retiro_part[1]."-".$retiro_part[0].' '.str_replace(' ', '', $this->data[$this->alias]['hora_inicio']).' '.$devolucion_part[2]."-".$devolucion_part[1]."-".$devolucion_part[0].' '.str_replace(' ', '', $data['hora_devolucio']);
        //echo $retiro.'  '.$devolucion;
        if($retiro < $devolucion) return true;
    }

    public function beforeSave($options = Array()) {
    
    	   	
    	
        
    	if(isset($this->data['FeriadoHorario']['hora_inicio'])){
            $this->data['FeriadoHorario']['hora_inicio'] = str_replace(' ', '', ($this->data['FeriadoHorario']['hora_inicio']));
        }
        
    	if(isset($this->data['FeriadoHorario']['hora_fin'])){
            $this->data['FeriadoHorario']['hora_fin'] = str_replace(' ', '', ($this->data['FeriadoHorario']['hora_fin']));
        }
        return true;
    }
    
   
    
}
?>
