<?php
class DiaHorariosController extends AppController {
    public $scaffold;
    public function index(){
        $this->set('rows',$this->DiaHorario->find('all'));
        $this->set('_serialize', array(
            'rows'
        ));
    }
    
    
	
    
    public function getRow(){
        $this->layout = 'ajax';
		//print_r($this->request->query);
        if($this->request->data){
           
            
            //guardo la relacion automaticamente
            $this->DiaHorario->set(array(
                'semana_dia_id' => $this->request->data['semana_dia_id'],
               
                'hora_inicio' => $this->request->data['hora_inicio'],
            	'hora_fin' => $this->request->data['hora_fin']
               
            ));
            $this->DiaHorario->save();
            $this->set('semana_dia_horario_id',$this->DiaHorario->id);
        }
    	else{
            $this->set('hora_inicio',str_replace(' ', '', $this->request->query['hora_inicio']));
            $this->set('hora_fin',str_replace(' ', '', $this->request->query['hora_fin']));
            //$this->set('extra',$this->ReservaExtra->Extra->findById($this->request->query['extra_id']));
        }
    }
    
}
?>
