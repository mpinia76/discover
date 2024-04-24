<?php
class NeumaticosController extends AppController {
    public $scaffold;




    public function index(){
        $this->layout = 'index';
        $this->setLogUsuario('Gestion de neumaticos');
    }



    public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('Neumatico');
        if($limit == "todos"){
            $neumaticos = $this->Neumatico->find('all', [

                'contain' => ['Unidad'], // Para cargar los datos relacionados de Unidad
                'fields' => ['Neumatico.*', 'Unidad.marca AS unidad_marca'], // Prefijar 'marca' con el alias de la tabla Unidad
            ]);
        }else{
            //$neumaticos = $this->Neumatico->find('all',array('limit' => $limit));
            $neumaticos = $this->Neumatico->find('all', [
                'limit' => $limit,
                'contain' => ['Unidad'], // Para cargar los datos relacionados de Unidad
                'fields' => ['Neumatico.*', 'Unidad.marca AS unidad_marca'], // Prefijar 'marca' con el alias de la tabla Unidad
            ]);
        }
        foreach ($neumaticos as $neumatico) {

            /*switch($alerta['Alerta']['nivel']) {
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
            $magnitud = ($alerta['Alerta']['magnitud'])?$alerta['Alerta']['magnitud']:'No aplica';*/
            $rows[] = array(
                $neumatico['Neumatico']['id'],

                date('d/m/Y',strtotime($neumatico['Neumatico']['fecha'])),
                $neumatico['Neumatico']['marca'],
                $neumatico['Neumatico']['modelo'],
                $neumatico['Neumatico']['fabricacion'],
                $neumatico['Neumatico']['dibujo'],
                $neumatico['Neumatico']['temporada'],
                $neumatico['Neumatico']['identificador'],
                '',
                '',
                $neumatico['Neumatico']['posicion'],
                $neumatico['Neumatico']['estado'],
                '',
                ''
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
        $this->set('posiciones',array('DC' => 'DC','DA' => 'DA','TC' => 'TC','TA' => 'TA','Auxilio' => 'Auxilio'));
        $this->set('temporadas',array('Verano'=> 'Verano', 'Invierno Clavos'=> 'Invierno Clavos', 'Invierno Silice'=> 'Invierno Silice', 'Mixto'=> 'Mixto'));
        $this->set('estados',array('En uso' => 'En uso','En deposito' => 'En deposito'));

    }

    public function editar($id = null){
        $this->layout = 'form';



        $this->Neumatico->id = $id;
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;

        $this->set('posiciones',array('DC' => 'DC','DA' => 'DA','TC' => 'TC','TA' => 'TA','Auxilio' => 'Auxilio'));
        $this->set('temporadas',array('Verano'=> 'Verano', 'Invierno Clavos'=> 'Invierno Clavos', 'Invierno Silice'=> 'Invierno Silice', 'Mixto'=> 'Mixto'));
        $this->set('estados',array('En uso' => 'En uso','En deposito' => 'En deposito'));

        $this->set('neumatico', $this->Neumatico->read());
    }


    public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {


            $this->Neumatico->begin();




            //vaildo reserva
            $neumatico = $this->request->data['Neumatico'];
            $this->Neumatico->set($neumatico);
            if(!$this->Neumatico->validates()){
                $errores['Neumatico'] = $this->Neumatico->validationErrors;
            }

            if(($neumatico['estado']=='En uso')&&($neumatico['unidad']=='')){
                $errores['Neumatico']['unidad'] = 'Debe seleccionar una unidad';
            }


            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                $grabar=1;
                try {
                    $this->Neumatico->save();

                } catch (PDOException $e) {
                    if ($e->errorInfo[1] == '1062') {
                        $errores['Neumatico']['identificador'] = 'Identificador repetido';
                        $grabar = 0;
                    } else {
                        $errores['Neumatico']['identificador'] = $e->errorInfo[1];
                        $grabar = 0;
                    }
                }



                if($grabar) {
                    $this->Neumatico->commit();

                    $this->set('resultado','OK');
                    $this->set('mensaje','Datos guardados');
                    $this->set('detalle','');
                }
                else
                {
                    $this->Neumatico->rollback();

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

