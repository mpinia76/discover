<?php
class DescuentoPeriodosController extends AppController {
    public $scaffold;
    public function index(){
        $this->set('rows',$this->DescuentoPeriodo->find('all'));
        $this->set('_serialize', array(
            'rows'
        ));
    }
    
    
	
    
    public function getRow(){
        $this->layout = 'ajax';
		//print_r($this->request->query);
        if($this->request->data){
           
            
            //guardo la relacion automaticamente
            $this->DescuentoPeriodo->set(array(
                'descuento_id' => $this->request->data['descuento_id'],
               
                'desde' => $this->request->data['desde'],
            	'hasta' => $this->request->data['hasta']
               
            ));
            $this->DescuentoPeriodo->save();
            $this->set('descuento_periodo_id',$this->DescuentoPeriodo->id);
        }
    	else{
            $this->set('desde',$this->request->query['desde']);
            $this->set('hasta',$this->request->query['hasta']);
            //$this->set('extra',$this->ReservaExtra->Extra->findById($this->request->query['extra_id']));
        }
    }
    
}
?>
