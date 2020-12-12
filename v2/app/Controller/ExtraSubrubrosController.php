<?php
class ExtraSubrubrosController extends AppController {
    public $scaffold;
    public function json(){
        $this->response->type('application/json');
        $this->layout = 'json';
        $this->set('subrubros',$this->Subrubro->find('all'));
    }
    
    public function create(){
        $this->layout = 'json';
        if(empty($this->request->data)) {
            
        }else{
            $this->loadModel('Rubro');
            $rubro = $this->request->data['Rubro'];
            $this->Rubro->create();
            $this->Rubro->set($rubro);
            if($this->Rubro->validates()){
                $this->Rubro->save();
                
                $subrubro = $this->request->data['Subrubro'];
                $this->Subrubro->create();
                $this->Subrubro->set('rubro_id',$this->Rubro->id);
                $this->Subrubro->set($subrubro);
                
                if($this->Subrubro->validates()){
                    $this->Subrubro->save();
                }
            }
            

        }
        
    }
    
	public function index(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Subrubros de Extras');
    }
    
	public function index2(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Parametrizacion Extras web');
    }
    
	public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('ExtraSubrubro');
        if($limit == "todos"){
            $subrubroes = $this->ExtraSubrubro->find('all'); 
        }else{
            $subrubroes = $this->ExtraSubrubro->find('all',array('limit' => $limit)); 
        }
        foreach ($subrubroes as $subrubro) {
        	
        	$rows[] = array(
                $subrubro['ExtraSubrubro']['id'],
                $subrubro['ExtraRubro']['rubro'],
                $subrubro['ExtraSubrubro']['subrubro'],
                $subrubro['ExtraSubrubro']['subrubro_ingles'],
                $subrubro['ExtraSubrubro']['subrubro_portugues']
            );
            
        }
       
        
        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }
    
	public function dataTable2($limit = ""){
        $rows = array();
        $this->loadModel('ExtraSubrubro');
        if($limit == "todos"){
            $subrubroes = $this->ExtraSubrubro->find('all'); 
        }else{
            $subrubroes = $this->ExtraSubrubro->find('all',array('limit' => $limit)); 
        }
        foreach ($subrubroes as $subrubro) {
        	$impacto = ($subrubro['ExtraSubrubro']['impacto'])?'X dia':'Total';
        	$descuento = ($subrubro['ExtraSubrubro']['descuento'])?'Si':'No';
        	$activo = ($subrubro['ExtraSubrubro']['activo'])?'Si':'No';
        	$rows[] = array(
                $subrubro['ExtraSubrubro']['id'],
                $subrubro['ExtraRubro']['rubro'],
                $subrubro['ExtraSubrubro']['subrubro'],
                $descuento,
                $impacto,
                $activo
            );
            
        }
       
        
        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }
    
	public function crear(){
        $this->layout = 'form';

        $this->set('extras_rubros', $this->ExtraSubrubro->ExtraRubro->find('list'));
        
       

    }
    
 	public function editar($id = null){
        $this->layout = 'form';
        $this->set('extras_rubros', $this->ExtraSubrubro->ExtraRubro->find('list'));
       
		$this->ExtraSubrubro->id = $id;
        $this->request->data = $this->ExtraSubrubro->read();
        
		
        $this->set('extra_subrubro', $this->ExtraSubrubro->read());
    }
    
	public function editar2($id = null){
        $this->layout = 'form';
        $this->set('extras_rubros', $this->ExtraSubrubro->ExtraRubro->find('list'));
        //impacto
        $this->set('impacto_ops', array('1' => 'X dia', '0' => 'Total'));
		$this->ExtraSubrubro->id = $id;
        $this->request->data = $this->ExtraSubrubro->read();
        
		
        $this->set('extra_subrubro', $this->ExtraSubrubro->read());
    }
    
    
	public function guardar(){

       
        
        //print_r($this->request->data);
        if(!empty($this->request->data)) {

         	//vaildo reserva
            $extraSubrubro = $this->request->data['ExtraSubrubro'];
            $this->ExtraSubrubro->set($extraSubrubro);
            if(!$this->ExtraSubrubro->validates()){
                $errores['ExtraSubrubro'] = $this->ExtraSubrubro->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                
                $this->ExtraSubrubro->save();

                

                $this->set('resultado','OK');
                $this->set('mensaje','Datos guardados');
                $this->set('detalle','');
            }
            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }
    }
}
?>
