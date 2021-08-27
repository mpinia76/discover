<?php
class UnidadsController extends AppController {
    public $scaffold;
    public $components = array('Mpdf');

    public function index(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Administracion de flota');
    }

 	public function index2(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Habilitar Unidades');
    }

	public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('Unidad');
        if($limit == "todos"){
            $unidades = $this->Unidad->find('all');
        }else{
            $unidades = $this->Unidad->find('all',array('limit' => $limit));
        }
        foreach ($unidades as $unidad) {
        	$estado = ($unidad['Unidad']['estado'])?'Activa':'Inactiva';
        	$excluir = ($unidad['Unidad']['excluir'])?'Si':'No';

        	$rows[] = array(
                $unidad['Unidad']['id'],
                $unidad['Unidad']['orden'],
                $unidad['Categoria']['categoria'],
                $unidad['Unidad']['marca'],
                $unidad['Unidad']['modelo'],
                $unidad['Unidad']['patente'],
                $unidad['Unidad']['km_ini'],
                $unidad['Unidad']['km'],
                $unidad['Unidad']['habilitacion'],
                $unidad['Unidad']['periodo'],
                $unidad['Unidad']['baja'],
                $estado,
                $excluir
            );

        }


        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }

	public function dataTable2($limit = ""){
        $rows = array();
        $this->loadModel('Unidad');
        if($limit == "todos"){
            $unidades = $this->Unidad->find('all',array('conditions' => (array('Categoria.activa' => 1)), 'recursive' => 2));
        }else{
            $unidades = $this->Unidad->find('all',array('limit' => $limit,'conditions' => (array('Categoria.activa' => 1)), 'recursive' => 2));
        }
        foreach ($unidades as $unidad) {

        	$activa = ($unidad['Unidad']['activa'])?'Si':'No';
        	$rows[] = array(
                $unidad['Unidad']['id'],
                $unidad['Categoria']['categoria'],
                $unidad['Unidad']['marca'],
                $unidad['Unidad']['modelo'],
                $unidad['Unidad']['patente'],
                $activa
            );

        }


        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }

	public function crear(){
        $this->layout = 'form';
		//lista de unidades
        $this->set('categorias', $this->Unidad->Categoria->find('list'));

        $this->loadModel('Alerta');
        $alertas= $this->Alerta->find('all',array("conditions" => "tipo = 'Flota'"));
        //print_r($alertas);
        $this->set('alertas', $alertas);

        $this->set('cantunidadalertas', 0);

        $this->loadModel('Reserva');
        $ultima_reserva = $this->Reserva->find('first',array('order' => array('Reserva.id' => 'desc')));

        $this->set('ultimaReserva',$ultima_reserva['Reserva']['numero']);

    }

    public function editar($id = null){
        $this->layout = 'form';
        $this->set('categorias', $this->Unidad->Categoria->find('list'));
		$this->Unidad->id = $id;
        $this->request->data = $this->Unidad->read();
        $unidad = $this->request->data;

        $this->set('unidad', $this->Unidad->read());


        $this->loadModel('Alerta');
        $alertas= $this->Alerta->find('all',array("conditions" => "tipo = 'Flota'"));
        //print_r($alertas);
        $this->set('alertas', $alertas);

        $this->loadModel('UnidadAlerta');
        $this->loadModel('GestionAlerta');
        $unidadalertas= $this->UnidadAlerta->find('all',array("conditions" => "unidad_id = '$id'"));
        $arrayUnidadAlerta = array();
        $arrayUnidadAlertas = array();
        foreach ($unidadalertas as $unidadalerta){
            //print_r($unidadalerta);
            $arrayUnidadAlerta['id'] = $unidadalerta['UnidadAlerta']['id'];
            $arrayUnidadAlerta['alerta_id'] = $unidadalerta['UnidadAlerta']['alerta_id'];
            $arrayUnidadAlerta['inicio_num'] = $unidadalerta['UnidadAlerta']['inicio_num'];
            $arrayUnidadAlerta['inicio_fecha'] = $unidadalerta['UnidadAlerta']['inicio_fecha'];
            $arrayUnidadAlerta['fin_num'] = $unidadalerta['UnidadAlerta']['fin_num'];
            $arrayUnidadAlerta['inactiva'] = $unidadalerta['UnidadAlerta']['inactiva'];
            $gestionAlerta = $this->GestionAlerta->find('first',array('conditions' => array('unidad_alerta_id =' =>$unidadalerta['UnidadAlerta']['id'],'estado <>'=>'Pendiente')));
            if (!empty($gestionAlerta)){
                $arrayUnidadAlerta['gestionNoPendiente']=1;
            }
            else{
                $arrayUnidadAlerta['gestionNoPendiente']=0;
            }
            $arrayUnidadAlertas[]=$arrayUnidadAlerta;
        }

        //print_r($arrayUnidadAlertas);
        $this->set('unidadalertas', $arrayUnidadAlertas);


        $this->set('cantunidadalertas', count($unidadalertas));

        $this->loadModel('Reserva');
        //$ultima_reserva = $this->Reserva->find('first',array('order' => array('Reserva.id' => 'desc')));

        $filtroUnidad=array('Reserva.unidad_id' => $id);
        $condicion=array($filtroUnidad);

        $ultima_reserva = $this->Reserva->find('first', array(
            'conditions' => $condicion
        ,'order' => array('Reserva.id' => 'desc')));


        $this->set('ultimaReserva',$ultima_reserva['Reserva']['numero']);

    }

	public function editar2($id = null){
        $this->layout = 'form';
        $this->set('categorias', $this->Unidad->Categoria->find('list'));
		$this->Unidad->id = $id;
        $this->request->data = $this->Unidad->read();
        $unidad = $this->request->data;

        $this->set('unidad', $this->Unidad->read());
    }

	public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

            $this->loadModel('UnidadAlerta');
            $this->loadModel('GestionAlerta');
            $this->Unidad->begin();

            $this->UnidadAlerta->begin();
            $this->GestionAlerta->begin();

         	//vaildo reserva
            $unidad = $this->request->data['Unidad'];
            $this->Unidad->set($unidad);
            if(!$this->Unidad->validates()){
                $errores['Unidad'] = $this->Unidad->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                $grabar=1;
                $this->Unidad->save();
                $alertas = $this->request->data['alerta'];
                $unidadAlertas_id = $this->request->data['unidadAlerta_id'];
                $inicio_nums = $this->request->data['inicio_num'];
                $inicio_fechas = $this->request->data['inicio_fecha'];
                $fin_nums = $this->request->data['fin_num'];
                $inactivas = $this->request->data['inactiva'];
                $noBorrar='';
                foreach ($unidadAlertas_id as $unidadAlerta_id){
                    $noBorrar .=$unidadAlerta_id.',';
                }
                foreach($alertas as $item=>$v){

                    if ($alertas[$item]==0){
                        $grabar=0;
                        $errores['Unidad']['errorAlerta'] = 'Falta seleccionar un alerta';
                    }
                    elseif ((($inicio_nums[$item]==''))&&(!($inicio_fechas[$item]))){
                        $grabar=0;
                        $errores['Unidad']['errorAlerta'] = 'Falta cargar un inicio';
                    }
                    elseif ($fin_nums[$item]==''){
                        $grabar=0;
                        $errores['Unidad']['errorAlerta'] = 'Falta cargar un fin';
                    }
                    else{
                        $gestionAlerta=array();
                        if (!empty($unidadAlertas_id[$item])){
                            $unidadAlerta_id=$unidadAlertas_id[$item];
                            $this->UnidadAlerta->id = $unidadAlerta_id;
                            $this->UnidadAlerta->read();
                            $gestionAlerta = $this->GestionAlerta->find('first',array('conditions' => array('GestionAlerta.unidad_alerta_id =' =>$unidadAlerta_id)));
                            if (!empty($gestionAlerta)){

                                $this->GestionAlerta->id = $gestionAlerta['GestionAlerta']['id'];
                                $this->GestionAlerta->read();
                            }
                            else{
                                $this->GestionAlerta->create();
                            }
                        }
                        else{
                            $this->UnidadAlerta->create();
                            $this->GestionAlerta->create();
                        }

                        try {

                            if ((empty($gestionAlerta))||($gestionAlerta['GestionAlerta']['estado']=='Pendiente')){
                                $this->UnidadAlerta->set('unidad_id',$this->Unidad->id);
                                $this->UnidadAlerta->set('alerta_id',$alertas[$item]);
                                $this->UnidadAlerta->set('inicio_num',$inicio_nums[$item]);
                                $this->UnidadAlerta->set('inicio_fecha',$inicio_fechas[$item]);
                                $this->UnidadAlerta->set('fin_num',$fin_nums[$item]);
                                $this->UnidadAlerta->set('inactiva',($inactivas[$item])?1:0);
                                $this->UnidadAlerta->save();

                                $user_id = $_COOKIE['useridushuaia'];
                                $this->GestionAlerta->set('unidad_alerta_id',$this->UnidadAlerta->id);
                                $this->GestionAlerta->set('inicio_num',$inicio_nums[$item]);
                                $this->GestionAlerta->set('inicio_fecha',$inicio_fechas[$item]);

                                $this->GestionAlerta->set('usuario_id',$user_id);
                                $this->GestionAlerta->set('estado','Pendiente');
                                $this->GestionAlerta->save();

                            }
                            else{
                                $errores['Unidad']['errorAlerta'] = 'Hay alertas que NO se modifican porque tienen gestiones NO PEDIENTES';
                                //$grabar = 0;
                            }

                            } catch (PDOException $e) {
                            if ($e->errorInfo[1] == '1062') {
                                $errores['Unidad']['errorAlerta'] = 'Hay alertas duplicadas';
                                $grabar = 0;
                            } else {
                                $errores['Unidad']['errorAlerta'] = $e->errorInfo[1];
                                $grabar = 0;
                            }
                        }





                        $noBorrar .=$this->UnidadAlerta->id.',';
                    }


                }
                if($grabar) {
                    if($noBorrar){
                        $conditions = [
                            'UnidadAlerta.unidad_id ' => $this->Unidad->id,
                            'UnidadAlerta.id NOT ' => explode(',', $noBorrar),
                        ];
                    }
                    else{
                        $conditions = [
                            'UnidadAlerta.unidad_id ' => $this->Unidad->id,

                        ];
                    }


                    $results = $this->UnidadAlerta->deleteAll($conditions);


                    $this->Unidad->commit();
                    $this->UnidadAlerta->commit();
                    $this->GestionAlerta->commit();
                    $this->set('resultado','OK');
                    $this->set('mensaje','Datos guardados');
                    $this->set('detalle','');
                }
                else
                {
                    $this->Unidad->rollback();
                    $this->UnidadAlerta->rollback();
                    $this->GestionAlerta->rollback();
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

}
?>
