<?php 
ini_set('memory_limit', '-1');
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
class SemanaDiasController extends AppController {

	public $scaffold;
   

	function index_horarios(){
        $this->layout = 'index';
        if (!isset($_SESSION['paginaHorarios'])) {
        
        	$_SESSION['paginaHorarios']=1;
        }
         
    }
	
	
    public function index(){
	    $this->layout = 'indexDefault';
	    $_SESSION['paginaHorarios']=1;
		$this->setLogUsuario('Carga de Horarios');
		
    }



    


      

    public function dataTable($limit = ""){

        $rows = array();
        $this->loadModel('SemanaDia');
        if($limit == "todos"){
            $semanaDias = $this->SemanaDia->find('all',array('order' => 'SemanaDia.id ASC')); 
        }else{
            $semanaDias = $this->SemanaDia->find('all',array('limit' => $limit,'order' => 'SemanaDia.id ASC')); 
        }
        
        foreach ($semanaDias as $semanaDia) {
        	$diaHorarios = '';
	        if(count($semanaDia['DiaHorario']>0)){
                foreach($semanaDia['DiaHorario'] as $diaHorario){
                    $diaHorarios .= $diaHorario['hora_inicio'].'-'.$diaHorario['hora_fin'].'<br>';
                }
            }
        	//print_r($semanaDia);
        	$rows[] = array(
                $semanaDia['SemanaDia']['id'],
                $semanaDia['SemanaDia']['dia'],
               	$diaHorarios
            );
            
        }
       
        
        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));



       
    }

    

    public function crear(){
        $this->layout = 'form';

        $dias = array ('Domingo'=>'Domingo','Lunes'=>'Lunes','Martes'=>'Martes','Miercoles'=>'Miercoles','Jueves'=>'Jueves','Viernes'=>'Viernes','Sabado'=>'Sabado');
       
        
        //print_r($categorias);
        $this->set('dias',$dias);

    }

	public function editar($id = null){
        $this->layout = 'form';

        

        $diaHorarios = $this->SemanaDia->DiaHorario->find('all',array('conditions' => array('semana_dia_id' => $id),'recursive' => 2));
        $this->set('diaHorarios',$diaHorarios);

       //$this->set('hora',date ("h:i"));
       
        /*$this->set('hora_retiro',date('H:i', strtotime("2000-01-01 ".$this->Reserva->horaRetiro)));
        $this->set('hora_devolucion',date('H:i', strtotime("2000-01-01 ".$this->Reserva->horaDevolucion)));*/

       
        $this->SemanaDia->id = $id;
        $this->request->data = $this->SemanaDia->read();
        $semanaDia = $this->request->data;
        

        $dias = array ('Domingo'=>'Domingo','Lunes'=>'Lunes','Martes'=>'Martes','Miercoles'=>'Miercoles','Jueves'=>'Jueves','Viernes'=>'Viernes','Sabado'=>'Sabado');
       
        
        
       	$this->set('defaultDia',$semanaDia['dia']);  
       	
        $this->set('dias',$dias);   
            
       
        $this->set('semanaDia', $this->SemanaDia->read());
    }

    public function guardar(){

     //load modules
        $this->loadModel('SemanaDia');
        
		$this->loadModel('DiaHorario');
        //print_r($this->request->data);
        if(!empty($this->request->data)) {

        	//print_r($this->request->data);
           
            $semanaDia = $this->request->data['SemanaDia'];
            $this->SemanaDia->set($semanaDia);
            if(!$this->SemanaDia->validates()){
                 $errores['SemanaDia'] = $this->SemanaDia->validationErrors;
            }
	        //print_r($this->request->data);
            if(array_key_exists('DiaHorarioHoraInicio',$this->request->data)){
	            $diaHorarios = $this->request->data['DiaHorarioHoraInicio'];
            }
            if($diaHorarios and count($diaHorarios)>0){
            	if (count($diaHorarios)>2) {
            		$errores[DiaHorario][hora_inicio]='No se pueden cargar mas de 2 franjas horarias';
            	}
            	elseif (count($diaHorarios)==2){
            		if ($this->request->data['DiaHorarioHoraFin'][0]<$this->request->data['DiaHorarioHoraInicio'][0]) {
            			$errores[DiaHorario][hora_inicio]='La fecha de inicio de la 1er franja debe ser menor a la de fin';
            		}
            		if ($this->request->data['DiaHorarioHoraFin'][0]>$this->request->data['DiaHorarioHoraInicio'][1]) {
            			$errores[DiaHorario][hora_inicio]='La fecha de inicio de la 2da franja debe ser mayor a la de fin de la primera';
            		}
            	}
            	else{
            		if ($this->request->data['DiaHorarioHoraFin'][0]<$this->request->data['DiaHorarioHoraInicio'][0]) {
            			$errores[DiaHorario][hora_inicio]='La fecha de inicio de la 1er franja debe ser menor a la de fin';
            		}
            		if ($this->request->data['DiaHorarioHoraFin'][1]<$this->request->data['DiaHorarioHoraInicio'][1]) {
            			$errores[DiaHorario][hora_inicio]='La fecha de inicio de la 2da franja debe ser menor a la de fin';
            		}
            	}
            }
            else {
            	$errores[DiaHorario][hora_inicio]='Debe cargar al menos una franja horaria';
            }
            
            //print_r($errores);
            
            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
	            try {
				    $this->SemanaDia->save();
			
	                //guardo reserva extras
	                $this->DiaHorario->deleteAll(array('semana_dia_id' => $this->SemanaDia->id), false);
                    if($diaHorarios and count($diaHorarios)>0){
                    	
                        
                        $i=0;
                        foreach($diaHorarios as $diaHorario){
                            $this->DiaHorario->create();
                            $this->DiaHorario->set('hora_inicio',$this->request->data['DiaHorarioHoraInicio'][$i]);
                            $this->DiaHorario->set('hora_fin',$this->request->data['DiaHorarioHoraFin'][$i]);
                            $this->DiaHorario->set('semana_dia_id',$this->SemanaDia->id);
                            $this->DiaHorario->save();
                            $i++;
                        }
                    }
	                
	
	                $this->set('resultado','OK');
	                $this->set('mensaje','Datos guardados');
	                $this->set('detalle','');
				} catch (PDOException $e) {
				  if ($e->errorInfo[1]=='1062') {
				  	$errores[SemanaDia][dia]='El dia ya fue cargado';
				  }
				  else {
				  	$errores[SemanaDia][dia]=$e->errorInfo[1];
				  }
				   $this->set('resultado','ERROR');
                	$this->set('mensaje','No se pudo guardar');
                	$this->set('detalle',$errores);
				}
               
            }
            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }

        
       
    }

	public function eliminar($id = null){
        if(!empty($this->request->data)) {

         	
         	$this->loadModel('DiaHorario');
         	$this->DiaHorario->deleteAll(array('semana_dia_id' => $this->request->data['id']), false);
            
          
		
        
	        $this->set('resultado','OK');
	        $this->set('mensaje','Horario eliminado');
	        $this->set('detalle','');
	        
	        $this->set('_serialize', array(
	            'resultado',
	            'mensaje' ,
	            'detalle' 
	        ));
        }
    }
    
	
    
}
?>
