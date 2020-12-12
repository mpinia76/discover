<?php 
ini_set('memory_limit', '-1');
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
class FeriadosController extends AppController {

	public $scaffold;
   

	/*function index_horarios(){
        $this->layout = 'index';
        if (!isset($_SESSION['paginaHorarios'])) {
        
        	$_SESSION['paginaHorarios']=2;
        }
         
    }*/
	
	
    public function index(){
	    $this->layout = 'indexDefault';
	    $_SESSION['paginaHorarios']=2;
		$this->setLogUsuario('Carga de Restricciones');
		
    }



    


      

    public function dataTable($limit = ""){

        $rows = array();
        $this->loadModel('Feriado');
        if($limit == "todos"){
            $feriados = $this->Feriado->find('all',array('order' => 'Feriado.fecha DESC')); 
        }else{
            $feriados = $this->Feriado->find('all',array('limit' => $limit,'order' => 'Feriado.fecha DESC')); 
        }
        
        foreach ($feriados as $feriado) {
        	$feriadoHorarios = '';
	        if(count($feriado['FeriadoHorario']>0)){
                foreach($feriado['FeriadoHorario'] as $feriadoHorario){
                    $feriadoHorarios .= $feriadoHorario['hora_inicio'].'-'.$feriadoHorario['hora_fin'].'<br>';
                }
            }
        	//print_r($feriado);
        	$abre = ($feriado['Feriado']['abre'])?'SI':'NO';
        	$rows[] = array(
                $feriado['Feriado']['id'],
                $feriado['Feriado']['fecha'],
                $abre,
               	$feriadoHorarios
            );
            
        }
       
        
        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));



       
    }

    

    public function crear(){
        $this->layout = 'form';

       

    }

	public function editar($id = null){
        $this->layout = 'form';

        

        $feriadoHorarios = $this->Feriado->FeriadoHorario->find('all',array('conditions' => array('feriado_id' => $id),'recursive' => 2));
        $this->set('feriadoHorarios',$feriadoHorarios);

       //$this->set('hora',date ("h:i"));
       
        /*$this->set('hora_retiro',date('H:i', strtotime("2000-01-01 ".$this->Reserva->horaRetiro)));
        $this->set('hora_devolucion',date('H:i', strtotime("2000-01-01 ".$this->Reserva->horaDevolucion)));*/

       
        $this->Feriado->id = $id;
        $this->request->data = $this->Feriado->read();
        $feriado = $this->request->data;
        

        
            
       
        $this->set('feriado', $this->Feriado->read());
    }

    public function guardar(){

     //load modules
        $this->loadModel('Feriado');
        
		$this->loadModel('FeriadoHorario');
        //print_r($this->request->data);
        if(!empty($this->request->data)) {

        	//print_r($this->request->data);
           
            $feriado = $this->request->data['Feriado'];
            $this->Feriado->set($feriado);
            if(!$this->Feriado->validates()){
                 $errores['Feriado'] = $this->Feriado->validationErrors;
            }
	        
            
            if ($feriado['abre']) {
            	//print_r($this->request->data);
	            if(array_key_exists('FeriadoHorarioHoraInicio',$this->request->data)){
		            $feriadoHorarios = $this->request->data['FeriadoHorarioHoraInicio'];
	            }
	            if($feriadoHorarios and count($feriadoHorarios)>0){
	            	$array_dias['Sunday'] = "Domingo";
					$array_dias['Monday'] = "Lunes";
					$array_dias['Tuesday'] = "Martes";
					$array_dias['Wednesday'] = "Miercoles";
					$array_dias['Thursday'] = "Jueves";
					$array_dias['Friday'] = "Viernes";
					$array_dias['Saturday'] = "Sabado";
					
					$date_parts = explode("/",$feriado['fecha']);
					$fecha = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
					
					
					
					$diaDesde = $array_dias[date('l', strtotime($fecha))];
			        
					$this->loadModel('SemanaDia');
					
					$diaHorarioDesde = $this->SemanaDia->find('all',array('joins' => array(
			        array(
			            'table' => 'dia_horarios',
			            'alias' => 'DiaHorario',
			            'type' => 'LEFT',
			            'conditions' =>array(
			                'SemanaDia.id = DiaHorario.semana_dia_id'
			            )
			        )),'fields' => array('DiaHorario.hora_inicio',
			'DiaHorario.hora_fin'),'conditions'=>array('SemanaDia.dia = '=>$diaDesde),'recursive' => -1));
					//print_r($diaHorarioDesde);
			        /*App::uses('ConnectionManager', 'Model');
		        	$dbo = ConnectionManager::getDatasource('default');
				    $logs = $dbo->getLog();
				    $lastLog = end($logs['log']);
				    
				    echo $lastLog['query'];*/
	            	if (count($feriadoHorarios)>2) {
	            		$errores[FeriadoHorario][hora_inicio]='No se pueden cargar mas de 2 franjas horarias';
	            	}
	            		
	            	
	            	elseif (count($feriadoHorarios)==2){
	            		foreach ($diaHorarioDesde as $horarios) {
		            		if ($this->request->data['FeriadoHorarioHoraInicio'][0].':00'<$horarios['DiaHorario']['hora_inicio']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
	            			if ($this->request->data['FeriadoHorarioHoraInicio'][1].':00'<$horarios['DiaHorario']['hora_inicio']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
		            		if ($this->request->data['FeriadoHorarioHoraFin'][0].':00'>$horarios['DiaHorario']['hora_fin']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
	            			if ($this->request->data['FeriadoHorarioHoraFin'][1].':00'>$horarios['DiaHorario']['hora_fin']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
	            		}
	            		
	            		if ($this->request->data['FeriadoHorarioHoraFin'][0]<$this->request->data['FeriadoHorarioHoraInicio'][0]) {
	            			$errores[FeriadoHorario][hora_inicio]='La fecha de apertura de la 1er franja debe ser menor a la de cierre';
	            		}
	            		if ($this->request->data['FeriadoHorarioHoraFin'][0]>$this->request->data['FeriadoHorarioHoraInicio'][1]) {
	            			$errores[FeriadoHorario][hora_inicio]='La fecha de apertura de la 2da franja debe ser mayor a la de cierre de la primera';
	            		}
	            		if ($this->request->data['FeriadoHorarioHoraFin'][1]<$this->request->data['FeriadoHorarioHoraInicio'][1]) {
	            			$errores[FeriadoHorario][hora_inicio]='La fecha de apertura de la 2da franja debe ser menor a la de cierre';
	            		}
	            	}
	            	else{
	            		foreach ($diaHorarioDesde as $horarios) {
	            			
		            		if ($this->request->data['FeriadoHorarioHoraInicio'][0].':00'<$horarios['DiaHorario']['hora_inicio']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
	            			
		            		if ($this->request->data['FeriadoHorarioHoraFin'][0].':00'>$horarios['DiaHorario']['hora_fin']) {
		            			$errores[FeriadoHorario][hora_inicio]='La restriccion se encuentra fuera de los segmentos horarios vigentes';
		            		}
	            			
	            		}
	            		if ($this->request->data['FeriadoHorarioHoraFin'][0]<$this->request->data['FeriadoHorarioHoraInicio'][0]) {
	            			$errores[FeriadoHorario][hora_inicio]='La fecha de apertura de la 1er franja debe ser menor a la de cierre';
	            		}
	            		
	            	}
	            }
	            else {
	            	$errores[FeriadoHorario][hora_inicio]='Debe cargar al menos una franja horaria';
	            }
            }
            
            
            //print_r($errores);
            
            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
	            try {
				    $this->Feriado->save();
			
	                //guardo reserva extras
	                $this->FeriadoHorario->deleteAll(array('feriado_id' => $this->Feriado->id), false);
	                if ($feriado['abre']) {
	                    if($feriadoHorarios and count($feriadoHorarios)>0){
	                    	
	                        
	                        $i=0;
	                        foreach($feriadoHorarios as $feriadoHorario){
	                            $this->FeriadoHorario->create();
	                            $this->FeriadoHorario->set('hora_inicio',$this->request->data['FeriadoHorarioHoraInicio'][$i]);
	                            $this->FeriadoHorario->set('hora_fin',$this->request->data['FeriadoHorarioHoraFin'][$i]);
	                            $this->FeriadoHorario->set('feriado_id',$this->Feriado->id);
	                            $this->FeriadoHorario->save();
	                            $i++;
	                        }
	                    }
	                }
	                
	
	                $this->set('resultado','OK');
	                $this->set('mensaje','Datos guardados');
	                $this->set('detalle','');
				} catch (PDOException $e) {
				  if ($e->errorInfo[1]=='1062') {
				  	$errores[Feriado][fecha]='El dia ya fue cargado';
				  }
				  else {
				  	$errores[Feriado][fecha]=$e->errorInfo[1];
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

         	$this->loadModel('Feriados');
         	$this->loadModel('FeriadoHorario');
         	$this->FeriadoHorario->deleteAll(array('feriado_id' => $this->request->data['id']), false);
            
            $this->Feriados->delete($this->request->data['id'],true);   
		
        
	        $this->set('resultado','OK');
	        $this->set('mensaje','Feriado eliminado');
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
