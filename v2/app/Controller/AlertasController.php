<?php
class AlertasController extends AppController {
    public $scaffold;




    public function index(){
        $this->layout = 'index';
        //$this->setLogUsuario('Opciones de Cobro/alertas');
    }



    public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('Alerta');
        if($limit == "todos"){
            $alertas = $this->Alerta->find('all');
        }else{
            $alertas = $this->Alerta->find('all',array('limit' => $limit));
        }
        foreach ($alertas as $alerta) {

            switch($alerta['Alerta']['nivel']) {
                case 'Nivel 1':
                    $nivel = '<span style="color:green">' . $alerta['Alerta']['nivel'] . '</span>';
                    break;
                case 'Nivel 2':
                    $nivel = '<span style="color:orange">' . $alerta['Alerta']['nivel'] . '</span>';
                    break;
                case 'Nivel 3':
                    $nivel = '<span style="color:red">' . $alerta['Alerta']['nivel'] . '</span>';
                    break;
            }
            $magnitud = ($alerta['Alerta']['magnitud'])?$alerta['Alerta']['magnitud']:'No aplica';
            $rows[] = array(
                $alerta['Alerta']['id'],
                $alerta['Alerta']['alertaCompleta'],
                date('d/m/Y',strtotime($alerta['Alerta']['fecha'])),
                $alerta['Alerta']['tipo'],
                $nivel,
                $alerta['Alerta']['unidad'],
                $magnitud,
                $alerta['Alerta']['segmento'],
                $alerta['Alerta']['controla'],
                $alerta['Alerta']['fin_num']
            );

        }


        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }

    public function index2(){
        $this->layout = 'index';
        //$this->setLogUsuario('Opciones de Cobro/alertas');
    }



    public function dataTable2($limit = ""){
        $rows = array();
        $this->loadModel('GestionAlerta');

        if($limit == "todos"){
            $alertas = $this->GestionAlerta->find('all',array('recursive' => 2));
        }else{
            $alertas = $this->GestionAlerta->find('all',array('limit' => $limit,'recursive' => 2));


        }
        //print_r($alertas);
        foreach ($alertas as $alerta) {
            $recordatorio = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['recordatorio']:$alerta['Alerta']['recordatorio'];
            $tipo = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['tipo']:$alerta['Alerta']['tipo'];
            $nivel = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['nivel']:$alerta['Alerta']['nivel'];
            $unidad = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['unidad']:$alerta['Alerta']['unidad'];
            $magnitud = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['magnitud']:$alerta['Alerta']['magnitud'];
            $segmento = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['segmento']:$alerta['Alerta']['segmento'];
            $controla = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['controla']:$alerta['Alerta']['controla'];
            $nombre = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['alertaCompleta']:$alerta['Alerta']['alertaCompleta'];
            $fecha = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['fecha']:$alerta['Alerta']['fecha'];
            switch($nivel) {
                case 'Nivel 1':
                    $nivel = '<span style="color:green">' . $nivel . '</span>';
                    break;
                case 'Nivel 2':
                    $nivel = '<span style="color:yellow">' . $nivel . '</span>';
                    break;
                case 'Nivel 3':
                    $nivel = '<span style="color:red">' . $nivel . '</span>';
                    break;
            }
            switch($unidad) {
                case 'KM':
                    $actual = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Unidad']['km']-$alerta['GestionAlerta']['inicio_num']:'';
                    $limite = ($alerta['GestionAlerta']['unidad_alerta_id'])?($segmento=='Intervalo')?$controla+$alerta['GestionAlerta']['inicio_num']:$controla:'';
                    $proxima = ($alerta['GestionAlerta']['unidad_alerta_id'])?($segmento=='Intervalo')?$controla*2+$alerta['GestionAlerta']['inicio_num']:'':'';
                    break;
                case 'Tiempo':
                    $hoy = New Datetime("now");

                    $date_parts = explode("/",$alerta['GestionAlerta']['inicio_fecha']);
                    $yy=$date_parts[2];
                    $mm=$date_parts[1];
                    $dd=$date_parts[0];

                    $inicio = new DateTime($yy.'-'.$mm.'-'.$dd);
                    $interval = $inicio->diff($hoy);

                    switch($magnitud) {
                        case 'Dia':
                            $actual = $interval->days;
                            $limite = strtotime($inicio."+ ".$controla." days");
                            $proxima = strtotime($inicio."+ ".($controla*2)." days");
                            break;
                        case 'Mes':
                            $actual = $interval->m;
                            $limite = strtotime($inicio."+ ".$controla." months");
                            $proxima = strtotime($inicio."+ ".($controla*2)." months");
                            break;
                        case 'Año':
                            $actual = $interval->y;
                            $limite = strtotime($inicio."+ ".$controla." years");
                            $proxima = strtotime($inicio."+ ".($controla*2)." years");
                            break;
                    }
                    $limite=date("d/m/Y",$limite);
                    $proxima=($segmento=='Intervalo')?date("d/m/Y",$proxima):'';
                    break;
                case 'Reservas':
                    if ($alerta['GestionAlerta']['alerta_id']){
                        $actual = '';
                        $limite = '';
                        $proxima = '';
                    }
                    else{
                        $this->loadModel('Reserva');
                        $filtroNro=array('Reserva.numero >=' => $alerta['GestionAlerta']['inicio_num']);
                        $filtroUnidad=array('Reserva.unidad_id' => $alerta['UnidadAlerta']['Unidad']['id']);
                        //$filtroDevolucion=array('Reserva.devolucion <' => $devolucion);

                        $filtroEstado=array('or' => array('AND' =>array(array(
                            'Reserva.estado <>' => 2),
                            array('Reserva.estado <>' => 3)),
                            array('Reserva.estado ' => null)));
                        /* $filtroKm=array('or' => array(array(
                             'Reserva.km_fin ' => 0),
                             array('Reserva.km_fin ' => null)));*/

                        $condicion=array($filtroNro,$filtroUnidad,$filtroEstado);

                        $cantReservas = $this->Reserva->find('count', array(
                            'conditions' => $condicion
                        ));
                        $actual = $cantReservas;
                        $limite = ($segmento=='Intervalo')?$controla+$actual:$controla;
                        $proxima = ($segmento=='Intervalo')?$controla*2+$actual:'';
                    }

                    break;
            }
            if ($actual>=$recordatorio){
                $this->loadModel('Usuario');
                $user = $this->Usuario->find('first',array('conditions'=>array('Usuario.id'=>$alerta['GestionAlerta']['usuario_id'])));
                //print_r($user);
                $rows[] = array(
                    $alerta['GestionAlerta']['id'],
                    $nombre,
                    date('d/m/Y',strtotime($fecha)),
                    $tipo,
                    $nivel,
                    ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Unidad']['patente']:'',
                    ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Unidad']['marca']:'',
                    ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Unidad']['modelo']:'',
                    $unidad,
                    $magnitud,
                    $segmento,
                    $controla,
                    $actual,
                    $limite,
                    $proxima,
                    $alerta['GestionAlerta']['estado'],
                    ($alerta['GestionAlerta']['fecha_gestion'])?date('d/m/Y',strtotime($alerta['GestionAlerta']['fecha_gestion'])):'',
                    $user['Usuario']['nombre'].' '.$user['Usuario']['apellido']
                );
            }


        }

        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }

    public function crear(){
        $this->layout = 'form';
        $this->set('tipos',array('Flota' => 'Flota','General' => 'General'));
        $this->set('niveles',array('Nivel 1' => 'Nivel 1','Nivel 2' => 'Nivel 2','Nivel 3' => 'Nivel 3'));
        $this->set('unidades',array('KM' => 'KM','Tiempo' => 'Tiempo','Reservas' => 'Reservas'));
        $this->set('magnitudes',array('Dia' => 'Dia','Mes' => 'Mes','Año' => 'Año'));
        $this->set('segmentos',array('Intervalo' => 'Intervalo','Umbral' => 'Umbral'));
    }

    public function editar($id = null){
        $this->layout = 'form';



        $this->Alerta->id = $id;
        $this->request->data = $this->Alerta->read();
        $alerta = $this->request->data;

        $this->set('tipos',array('Flota' => 'Flota','General' => 'General'));
        $this->set('niveles',array('Nivel 1' => 'Nivel 1','Nivel 2' => 'Nivel 2','Nivel 3' => 'Nivel 3'));
        $this->set('unidades',array('KM' => 'KM','Tiempo' => 'Tiempo','Reservas' => 'Reservas'));
        $this->set('magnitudes',array('Dia' => 'Dia','Mes' => 'Mes','Año' => 'Año'));
        $this->set('segmentos',array('Intervalo' => 'Intervalo','Umbral' => 'Umbral'));

        $this->set('alerta', $this->Alerta->read());
    }


    public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

            $this->loadModel('GestionAlerta');
            $this->Alerta->begin();

            $this->GestionAlerta->begin();


            //vaildo reserva
            $alerta = $this->request->data['Alerta'];
            $this->Alerta->set($alerta);
            if(!$this->Alerta->validates()){
                $errores['Alerta'] = $this->Alerta->validationErrors;
            }

            if(($alerta['unidad']=='Tiempo')&&($alerta['magnitud']=='')){
                $errores['Alerta']['magnitud'] = 'Debe seleccionar una magnitud';
            }


            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                $grabar=1;
                $this->Alerta->set('fecha',date('Y-m-d'));
                $this->Alerta->save();
                //print_r($alerta);
                if ($alerta['tipo']=='General'){
                    $gestionAlerta = $this->GestionAlerta->find('first',array('conditions' => array('GestionAlerta.alerta_id =' =>$this->Alerta->id)));

                    if (!empty($gestionAlerta)){

                        $this->GestionAlerta->id = $gestionAlerta['GestionAlerta']['id'];
                        $this->GestionAlerta->read();
                    }
                    else{
                        $this->GestionAlerta->create();
                    }

                    try {
                        $usuario_id = $_COOKIE['useridushuaia'];
                        $this->GestionAlerta->set('alerta_id',$this->Alerta->id);
                        $this->GestionAlerta->set('inicio_num',$alerta['inicio_num']);
                        $this->GestionAlerta->set('inicio_fecha',$alerta['inicio_fecha']);
                        $this->GestionAlerta->set('usuario_id',$usuario_id);
                        $this->GestionAlerta->set('estado','Pendiente');
                        $this->GestionAlerta->save();

                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == '1062') {
                            $errores['Alerta']['errorAlerta'] = 'Hay alertas duplicadas';
                            $grabar = 0;
                        } else {
                            $errores['Alerta']['errorAlerta'] = $e->errorInfo[1];
                            $grabar = 0;
                        }
                    }
                }



                if($grabar) {
                    $this->Alerta->commit();
                    $this->GestionAlerta->commit();
                    $this->set('resultado','OK');
                    $this->set('mensaje','Datos guardados');
                    $this->set('detalle','');
                }
                else
                {
                    $this->Alerta->rollback();
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

    public function eliminar($id = null){
        if(!empty($this->request->data)) {

            $this->loadModel('Alerta');


            $this->Alerta->delete($this->request->data['id'],true);


            $this->set('resultado','OK');
            $this->set('mensaje','Alerta eliminada');
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

