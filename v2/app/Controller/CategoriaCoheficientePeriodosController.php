<?php
class CategoriaCoheficientePeriodosController extends AppController {
    public $scaffold;
    public function index(){
        $this->set('rows',$this->CategoriaCoheficientePeriodo->find('all'));
        $this->set('_serialize', array(
            'rows'
        ));
    }
    
    
	
    
    public function getRow(){
        $this->layout = 'ajax';
		//print_r($this->request->query);
        if($this->request->data){
           
            
            //guardo la relacion automaticamente
            $this->CategoriaCoheficientePeriodo->set(array(
                'categoria_coheficiente_id' => $this->request->data['categoria_coheficiente_id'],
               
                'desde' => $this->request->data['desde'],
            	'hasta' => $this->request->data['hasta']
               
            ));
            $this->CategoriaCoheficientePeriodo->save();
            $this->set('categoria_coheficiente_periodo_id',$this->CategoriaCoheficientePeriodo->id);
        }
    	else{
            $this->set('desde',$this->request->query['desde']);
            $this->set('hasta',$this->request->query['hasta']);
            //$this->set('extra',$this->ReservaExtra->Extra->findById($this->request->query['extra_id']));
        }
    }
    
}
?>
