<?php
class GestionAlertasController extends AppController {
    public $scaffold;




    public function index(){
        $this->layout = 'index';
        $this->setLogUsuario('Gestion de Alertas');
    }


    public function dataTable($limit = ""){
        $rows = array();
        //$this->loadModel('GestionAlerta');

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
            $nombre = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['alerta']:$alerta['Alerta']['alerta'];
            $fecha = ($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Alerta']['fecha']:$alerta['Alerta']['fecha'];
            $estado = $alerta['GestionAlerta']['estado'];
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
                    $actual = ($estado=='Resuelta')?$alerta['GestionAlerta']['km_resolucion']:($alerta['GestionAlerta']['unidad_alerta_id'])?$alerta['UnidadAlerta']['Unidad']['km']-$alerta['GestionAlerta']['inicio_num']:'';
                    $limite = ($alerta['GestionAlerta']['unidad_alerta_id'])?($segmento=='Intervalo')?$controla+$alerta['GestionAlerta']['inicio_num']:$controla:'';
                    $proxima = ($alerta['GestionAlerta']['unidad_alerta_id'])?($segmento=='Intervalo')?$controla*2+$alerta['GestionAlerta']['inicio_num']:'':'';
                    $limiteNum =$limite;
                    break;
                case 'Tiempo':
                    $hoy = New Datetime("now");

                    $date_parts = explode("/",$alerta['GestionAlerta']['inicio_fecha']);
                    $yy=$date_parts[2];
                    $mm=$date_parts[1];
                    $dd=$date_parts[0];
                    $inicio = $yy.'-'.$mm.'-'.$dd;
                    $inicioT = new DateTime($inicio);
                    $interval = $inicioT->diff($hoy);

                    switch($magnitud) {
                        case 'Dia':
                            $actual = $interval->days;
                            $limite = strtotime($inicio."+ ".$controla." days");
                            $proxima = strtotime($inicio."+ ".($controla*2)." days");
                            $intervalLimite = $inicioT->diff(new DateTime($limite));
                            $limiteNum = $intervalLimite->days;
                            break;
                        case 'Mes':
                            $actual = $interval->m;
                            $limite = strtotime($inicio."+ ".$controla." months");
                            $proxima = strtotime($inicio."+ ".($controla*2)." months");
                            $intervalLimite = $inicioT->diff(new DateTime($limite));
                            $limiteNum = $intervalLimite->m;
                            break;
                        case 'Año':
                            $actual = $interval->y;
                            $limite = strtotime($inicio."+ ".$controla." years");
                            $proxima = strtotime($inicio."+ ".($controla*2)." years");
                            $intervalLimite = $inicioT->diff(new DateTime($limite));
                            $limiteNum = $intervalLimite->y;
                            break;
                    }
                    //echo $actual.' - '.$limiteNum.' / ';
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
                        /*$limite = ($segmento=='Intervalo')?$controla+$actual:$controla;
                        $proxima = ($segmento=='Intervalo')?$controla*2+$actual:'';*/
                        $limite=$controla;
                        $proxima =$controla*2;
                        $limiteNum =$limite;
                    }

                    break;
            }

            //echo $alerta['UnidadAlerta']['Unidad']['patente'].'-'.$alerta['UnidadAlerta']['Unidad']['km'].' =>'.$actual.'>='.$recordatorio.' - ';
            if ($actual>=$recordatorio){
                if ($estado=='Pendiente'){
                    if ($actual>$limiteNum){
                        $this->GestionAlerta->id = $alerta['GestionAlerta']['id'];
                        $this->GestionAlerta->read();
                        $usuario_id = $_COOKIE['useridushuaia'];
                        $this->GestionAlerta->set('usuario_id',$usuario_id);
                        $this->GestionAlerta->set('estado','Vencida');
                        $this->GestionAlerta->save();
                        $estado = 'Vencida';
                    }
                }


                switch($estado) {
                    case 'Resuelta':
                        $estado = '<span style="color:green">' . $estado . '</span>';
                        break;
                    case 'Pendiente':
                        $estado = '<span style="color:yellow">' . $estado . '</span>';
                        break;
                    case 'Vencida':
                        $estado = '<span style="color:red">' . $estado . '</span>';
                        break;
                }
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
                    $estado,
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

    public function resolver($id = null){
        $this->layout = 'form';



        $this->GestionAlerta->id = $id;
        $this->request->data = $this->GestionAlerta->read();
        $gestionAlerta = $this->request->data;
        //print_r($gestionAlerta);
        if ($gestionAlerta['GestionAlerta']['unidad_alerta_id']){
            $this->loadModel('Alerta');
            $this->Alerta->id = $gestionAlerta['UnidadAlerta']['alerta_id'];
            $alerta = $this->Alerta->read();
            $unidad = $alerta['Alerta']['unidad'];
            $segmento = $alerta['Alerta']['segmento'];
            $controla = $alerta['Alerta']['controla'];
            $magnitud = $alerta['Alerta']['magnitud'];
            $this->loadModel('Unidad');
            $this->Unidad->id = $gestionAlerta['UnidadAlerta']['unidad_id'];
            $flota = $this->Unidad->read();
        }
        else{
            $unidad = $gestionAlerta['Alerta']['unidad'];
            $segmento = $gestionAlerta['Alerta']['segmento'];
            $controla = $gestionAlerta['Alerta']['controla'];
            $magnitud = $gestionAlerta['Alerta']['magnitud'];
        }


        $this->set('unidad', $unidad);

        switch($unidad) {
            case 'KM':
                $actual = ($gestionAlerta['GestionAlerta']['unidad_alerta_id'])?$flota['Unidad']['km']-$gestionAlerta['GestionAlerta']['inicio_num']:'';
                $limite = ($gestionAlerta['GestionAlerta']['unidad_alerta_id'])?($segmento=='Intervalo')?$controla+$gestionAlerta['GestionAlerta']['inicio_num']:$controla:'';
                $mostrarIniNum ='block';
                $mostrarIniFecha ='none';
                $mostrarResFecha ='none';
                $mostrarResReserva ='none';
                $mostrarResNum ='block';
                break;
            case 'Tiempo':
                $hoy = New Datetime("now");

                $date_parts = explode("/",$gestionAlerta['GestionAlerta']['inicio_fecha']);
                $yy=$date_parts[2];
                $mm=$date_parts[1];
                $dd=$date_parts[0];
                $inicio = $yy.'-'.$mm.'-'.$dd;
                /*$inicioT = new DateTime($inicio);
                $interval = $inicioT->diff($hoy);*/

                switch($magnitud) {
                    case 'Dia':
                        //$actual = $interval->days;
                        $limite = strtotime($inicio."+ ".$controla." days");
                        /*$intervalLimite = $inicioT->diff(new DateTime($limite));
                        $limiteNum = $intervalLimite->days;*/
                        break;
                    case 'Mes':
                        //echo $inicio."+ ".$controla;
                        //$actual = $interval->m;
                        $limite = strtotime($inicio."+ ".$controla." months");
                        /*$intervalLimite = $inicioT->diff(new DateTime($limite));
                        $limiteNum = $intervalLimite->m;*/
                        break;
                    case 'Año':
                        //$actual = $interval->y;
                        $limite = strtotime($inicio."+ ".$controla." years");
                        /*$intervalLimite = $inicioT->diff(new DateTime($limite));
                        $limiteNum = $intervalLimite->y;*/
                        break;
                }
                //echo $actual.' - '.$limiteNum.' / ';

                $limite=date("d/m/Y",$limite);


                $mostrarIniNum ='none';
                $mostrarIniFecha ='block';
                $mostrarResFecha ='block';
                $mostrarResReserva ='none';
                $mostrarResNum ='none';
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

        $this->set('mostrarIniNum', $mostrarIniNum);
        $this->set('mostrarIniFecha', $mostrarIniFecha);
        $this->set('mostrarResReserva', $mostrarResReserva);
        $this->set('mostrarResFecha', $mostrarResFecha);
        $this->set('mostrarResNum', $mostrarResNum);
        $this->set('actual', $actual);
        $this->set('limite', $limite);


        $this->set('gestion_alerta', $this->GestionAlerta->read());
    }

    public function guardarR(){

        //print_r($this->request->data);
        if(!empty($this->request->data)) {

            //$this->loadModel('GestionAlerta');


            $this->GestionAlerta->begin();


            //vaildo reserva
            $gestionAlerta = $this->request->data['GestionAlerta'];
            $this->GestionAlerta->set($gestionAlerta);

            if(!$this->GestionAlerta->validates()){
                $errores['GestionAlerta'] = $this->GestionAlerta->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                $grabar=1;


                    try {
                        $usuario_id = $_COOKIE['useridushuaia'];
                        $this->GestionAlerta->set('usuario_id',$usuario_id);
                        $this->GestionAlerta->set('km_resolucion',$gestionAlerta['km_resolucion']);
                        $this->GestionAlerta->set('fecha_resolucion',$gestionAlerta['fecha_resolucion']);
                        $this->GestionAlerta->set('km_resolucion',$gestionAlerta['km_resolucion']);
                        $this->GestionAlerta->set('resuelta',$gestionAlerta['resuelta']);
                        $this->GestionAlerta->set('estado','Resuelta');
                        $this->GestionAlerta->set('fecha_gestion',date('Y-m-d'));
                        $this->GestionAlerta->set('gestion','Resolver');
                        $this->GestionAlerta->save();
                        $gestionAlertaAnt = $this->GestionAlerta->findById($gestionAlerta['id']);
                        $inactiva=0;
                        if ($gestionAlertaAnt['GestionAlerta']['unidad_alerta_id']){
                            $alerta_id = $gestionAlertaAnt['UnidadAlerta']['alerta_id'];
                            $inactiva=$gestionAlertaAnt['UnidadAlerta']['inactiva'];
                        }
                        else{
                            $alerta_id = $gestionAlertaAnt['alerta_id'];
                        }
                        if(!$inactiva){
                            $this->loadModel('Alerta');
                            $this->Alerta->id = $alerta_id;
                            $alerta = $this->Alerta->read();
                            $segmento = $alerta['Alerta']['segmento'];
                            $magnitud = $alerta['Alerta']['magnitud'];
                            $fin = $alerta['Alerta']['fin_num'];
                            if ($gestionAlerta['km_resolucion']){
                                $actual=$gestionAlerta['km_resolucion'];
                            }
                            elseif ($gestionAlerta['fecha_resolucion']){
                                $date_parts = explode("/",$gestionAlertaAnt['GestionAlerta']['inicio_fecha']);
                                $yy=$date_parts[2];
                                $mm=$date_parts[1];
                                $dd=$date_parts[0];

                                $inicio = $yy.'-'.$mm.'-'.$dd;
                                $inicioT = new DateTime($inicio);

                                $part=explode("/",$gestionAlerta['fecha_resolucion']);
                                $hoy=$part[2]."-".$part[1]."-".$part[0];

                                $interval = $inicioT->diff($hoy);

                                switch($magnitud) {
                                    case 'Dia':
                                        $actual = $interval->days;

                                        break;
                                    case 'Mes':
                                        //echo $inicio."+ ".$controla;
                                        $actual = $interval->m;

                                        break;
                                    case 'Año':
                                        $actual = $interval->y;

                                        break;
                                }
                            }


                            if (($actual<$fin)&&($segmento=='Intervalo')){
                                $this->GestionAlerta->create();
                                $this->GestionAlerta->set('unidad_alerta_id',$gestionAlertaAnt['GestionAlerta']['unidad_alerta_id']);
                                $this->GestionAlerta->set('alerta_id',$gestionAlertaAnt['GestionAlerta']['alerta_id']);
                                $this->GestionAlerta->set('inicio_num',$gestionAlerta['km_resolucion']);
                                $this->GestionAlerta->set('inicio_fecha',$gestionAlerta['fecha_resolucion']);
                                $this->GestionAlerta->set('usuario_id',$usuario_id);
                                $this->GestionAlerta->set('estado','Pendiente');
                                $this->GestionAlerta->save();
                            }
                        }






                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == '1062') {
                            $errores['Alerta']['errorAlerta'] = 'Hay alertas duplicadas';
                            $grabar = 0;
                        } else {
                            $errores['Alerta']['errorAlerta'] = $e->errorInfo[1];
                            $grabar = 0;
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

}
?>

