<?php
class FeriadoHorariosController extends AppController {
    public $scaffold;
    public function index(){
        $this->set('rows',$this->FeriadoHorario->find('all'));
        $this->set('_serialize', array(
            'rows'
        ));
    }
    
    
	
    
    public function getRow(){
        $this->layout = 'ajax';
		//print_r($this->request->query);
        if($this->request->data){
           
            
            //guardo la relacion automaticamente
            $this->FeriadoHorario->set(array(
                'feriado_id' => $this->request->data['feriado_id'],
               
                'hora_inicio' => $this->request->data['hora_inicio'],
            	'hora_fin' => $this->request->data['hora_fin']
               
            ));
            $this->FeriadoHorario->save();
            $this->set('feriado_horario_id',$this->FeriadoHorario->id);
        }
    	else{
            $this->set('hora_inicio',str_replace(' ', '', $this->request->query['hora_inicio']));
            $this->set('hora_fin',str_replace(' ', '', $this->request->query['hora_fin']));
            //$this->set('extra',$this->ReservaExtra->Extra->findById($this->request->query['extra_id']));
        }
    }
    
}
?>
