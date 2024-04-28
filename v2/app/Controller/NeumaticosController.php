<?php
class NeumaticosController extends AppController {
    public $scaffold;

    public function dateFormatSQL($dateString) {
        //echo $dateString."<br>";
        $date_parts = explode("/",$dateString);
        switch (count($date_parts)) {
            case 1:
                $result = $date_parts[0];
                break;
            case 2:
                $result = $date_parts[1]."-".$date_parts[0];
                break;
            default:
                $result = $date_parts[2]."-".$date_parts[1]."-".$date_parts[0];
                break;
        }
        return $result;
    }


    public function index(){
        $this->layout = 'index';
        $this->setLogUsuario('Gestion de neumaticos');
    }



    public function dataTable($limit = ""){

        $orderType= ($_GET['sSortDir_0'])? $_GET['sSortDir_0']:'desc';
        switch ($_GET['iSortCol_0']) {
            case 1:
                $order='Neumatico.fecha '.$orderType;
                break;
            case 2:
                $order='Neumatico.marca '.$orderType;
                break;
            case 3:
                $order='Neumatico.modelo '.$orderType;
                break;
            case 4:
                $order='Neumatico.fabricacion '.$orderType;
                break;
            case 5:
                $order='Neumatico.medida '.$orderType;
                break;
            case 6:
                $order='Neumatico.temporada '.$orderType;
                break;
            case 7:
                $order='Neumatico.identificador '.$orderType;
                break;
            case 9:
                $order='Unidad.patente '.$orderType;
                break;
            case 10:
                $order='Neumatico.posicion '.$orderType;
                break;
            case 11:
                $order='NeumaticoEstado.estado '.$orderType;
                break;
            default:
                $order='Neumatico.fecha '.$orderType;
                break;
        }


        $condicionSearch1 = ($_GET['sSearch_1'])?array('Neumatico.fecha LIKE '=> '%'.$this->dateFormatSQL($_GET['sSearch_1']).'%'):array();
        $condicionSearch2 = ($_GET['sSearch_2'])?array('Neumatico.marca LIKE '=>'%'.$_GET['sSearch_2'].'%'):array();
        $condicionSearch3 = ($_GET['sSearch_3'])?array('Neumatico.modelo LIKE '=>'%'.$_GET['sSearch_3'].'%'):array();
        $condicionSearch4 = ($_GET['sSearch_4'])?array('Neumatico.fabricacion LIKE '=>'%'.$_GET['sSearch_4'].'%'):array();
        $condicionSearch5 = ($_GET['sSearch_5'])?array('Neumatico.medida LIKE '=>'%'.$_GET['sSearch_5'].'%'):array();
        $condicionSearch6 = ($_GET['sSearch_6'])?array('Neumatico.temporada = '=>$_GET['sSearch_6']):array();
        $condicionSearch7 = ($_GET['sSearch_7'])?array('Neumatico.identificador LIKE '=>'%'.$_GET['sSearch_7'].'%'):array();
        $condicionSearch9 = ($_GET['sSearch_9'])?array('Unidad.patente LIKE '=>'%'.$_GET['sSearch_9'].'%'):array();
        $condicionSearch10 = ($_GET['sSearch_10'])?array('Neumatico.posicion = '=>$_GET['sSearch_10']):array();
        //$condicionSearch11 = ($_GET['sSearch_11'])?($_GET['sSearch_11']==1)?array('NeumaticoEstado.estado = '=>$_GET['sSearch_11']):array();
        $condicionSearch11 = array();
        //echo 'filtro'.$_GET['sSearch_11'];
        if ($_GET['sSearch_11']){
            if ($_GET['sSearch_11']=='Activas'){
                $condicionSearch11 = array(
                    'or' => array(
                        array('NeumaticoEstado.estado' => 'En uso'),
                        array('NeumaticoEstado.estado' => 'En deposito')
                    )
                );
            }
            else{
                $condicionSearch11 = array('NeumaticoEstado.estado = '=>$_GET['sSearch_11']);
            }
        }
        //print_r($condicionSearch11);
        $condicion=array($condicionSearch1,$condicionSearch2,$condicionSearch3,$condicionSearch4,$condicionSearch5,$condicionSearch6,$condicionSearch7,$condicionSearch9,$condicionSearch10,$condicionSearch11);

        $rows = array();
        $this->loadModel('Neumatico');

        /*$neumaticos = $this->Neumatico->find('all', [
            'conditions' => $condicion,
            'order' => $order,
            'limit' => $_GET['iDisplayLength'],
            'contain' => ['Unidad'], // Para cargar los datos relacionados de Unidad
            'fields' => ['Neumatico.*', 'Unidad.marca AS unidad_marca', 'Unidad.patente AS patente'], // Prefijar 'marca' con el alias de la tabla Unidad
            'offset' => $_GET['iDisplayStart'],
            'joins' => [
                [
                    'table' => '(SELECT neumatico_id FROM neumatico_estados WHERE hasta IS NULL) AS NeumaticoEstadoFiltered',
                    'alias' => 'NeumaticoEstadoFiltered',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Neumatico.id = NeumaticoEstadoFiltered.neumatico_id'
                    ]
                ]
            ],
        ]);*/
        /*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = end($logs['log']);

			    echo $lastLog['query'];*/
        $neumaticos = $this->Neumatico->find('all',array('joins' => array(
            array(
                'table' => 'unidads',
                'alias' => 'Unidad',
                'type' => 'LEFT',
                'conditions' => array(
                    'Unidad.id = Neumatico.unidad_id'
                )
            ),

            array(
                'table' => 'neumatico_estados',
                'alias' => 'NeumaticoEstado',
                'type' => 'LEFT',
                'conditions' => array(
                    'Neumatico.id = NeumaticoEstado.neumatico_id','NeumaticoEstado.hasta is null'
                )
            )


        ),'fields'=>array('Neumatico.*','NeumaticoEstado.*', 'Unidad.marca AS unidad_marca', 'Unidad.patente AS patente'), 'conditions' => $condicion,'order' => $order, 'offset'=>$_GET['iDisplayStart'], 'limit'=>$_GET['iDisplayLength'], 'recursive' => -1));

        /*if($limit == "todos"){
            $neumaticos = $this->Neumatico->find('all', [

                'contain' => ['Unidad'], // Para cargar los datos relacionados de Unidad
                'fields' => ['Neumatico.*', 'Unidad.marca AS unidad_marca', 'Unidad.patente AS patente'], // Prefijar 'marca' con el alias de la tabla Unidad
            ]);
        }else{
            //$neumaticos = $this->Neumatico->find('all',array('limit' => $limit));
            $neumaticos = $this->Neumatico->find('all', [
                'limit' => $limit,
                'contain' => ['Unidad'], // Para cargar los datos relacionados de Unidad
                'fields' => ['Neumatico.*', 'Unidad.marca AS unidad_marca', 'Unidad.patente AS patente'], // Prefijar 'marca' con el alias de la tabla Unidad
            ]);
        }*/
        //$iTotal = $this->Neumatico->find('count',array('conditions'=> $condicion));

        $iTotal = $this->Neumatico->find('count',array('joins' => array(


            array(
                'table' => 'neumatico_estados',
                'alias' => 'NeumaticoEstado',
                'type' => 'LEFT',
                'conditions' => array(
                    'Neumatico.id = NeumaticoEstado.neumatico_id','NeumaticoEstado.hasta is null'
                )
            )


        ), 'conditions' => $condicion));

        $this->loadModel('Unidad');
        foreach ($neumaticos as $neumatico) {
            //print_r($neumatico);
            $this->Unidad->id = $neumatico['Neumatico']['unidad_id'];
            $this->request->data = $this->Unidad->read();
            $unidad = $this->request->data;
            // Extraer el año y la semana del valor
            $year = substr($neumatico['Neumatico']['fabricacion'], 2) + 2000; // Extraer los últimos dos dígitos
            $semana = substr($neumatico['Neumatico']['fabricacion'], 0, 2); // Extraer los primeros dos dígitos

            // Construir la fecha desde la semana y el año
            $fecha = new DateTime();
            $fecha->setISODate($year, $semana); // Establecer la fecha usando la semana y el año

// Calcular la diferencia en años
            $hoy = new DateTime();
            $diferencia = $hoy->diff($fecha);
            $years = $diferencia->y + $diferencia->m / 12 + $diferencia->d / 365.25;

// Formatear el resultado con coma como separador decimal
            $years = number_format($years, 2, ',', '');


            $rows[] = array(
                $neumatico['Neumatico']['id'],

                //date('d/m/Y',strtotime($neumatico['Neumatico']['fecha'])),
                $neumatico['Neumatico']['fecha'],
                $neumatico['Neumatico']['marca'],
                $neumatico['Neumatico']['modelo'],
                $neumatico['Neumatico']['fabricacion'],
                $neumatico['Neumatico']['medida'],
                $neumatico['Neumatico']['temporada'],
                $neumatico['Neumatico']['identificador'],
                $unidad['Categoria']['categoria'],
                $neumatico['Unidad']['patente'],
                $neumatico['Neumatico']['posicion'],
                $neumatico['NeumaticoEstado']['estado'],
                $years,
                $neumatico['NeumaticoEstado']['km']
            );

        }


        /*$this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));*/

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => count($rows),
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => array()
        );

        $output['aaData'] = $rows;
        $this->set('aoData',$output);
        //print_r($output);
        $this->set('_serialize',
            'aoData'
        );
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
        $this->set('posiciones',array('DI' => 'DI','DD' => 'DD','TI' => 'TI','TD' => 'TD','Auxilio' => 'Auxilio'));
        $this->set('temporadas',array('Verano'=> 'Verano', 'Invierno Clavos'=> 'Invierno Clavos', 'Invierno Silice'=> 'Invierno Silice', 'Mixto'=> 'Mixto'));
        $this->set('estados',array('En uso' => 'En uso','En deposito' => 'En deposito'));

        $this->loadModel('Categoria');
        $this->set('categorias', $this->Categoria->find('list',array('order' => array('Categoria.categoria ASC'))));
    }

    public function editar($id = null){
        $this->layout = 'form';



        $this->Neumatico->id = $id;
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;

        $this->set('posiciones',array('DI' => 'DI','DD' => 'DD','TI' => 'TI','TD' => 'TD','Auxilio' => 'Auxilio'));
        $this->set('temporadas',array('Verano'=> 'Verano', 'Invierno Clavos'=> 'Invierno Clavos', 'Invierno Silice'=> 'Invierno Silice', 'Mixto'=> 'Mixto'));
        $this->set('estados',array('En uso' => 'En uso','En deposito' => 'En deposito'));

        $this->set('neumatico', $this->Neumatico->read());
    }


    public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {


            $this->Neumatico->begin();
            $this->loadModel('NeumaticoEstado');
            $this->NeumaticoEstado->begin();


            //vaildo reserva
            $neumatico = $this->request->data['Neumatico'];
            $this->Neumatico->set($neumatico);
            if(!$this->Neumatico->validates()){
                $errores['Neumatico'] = $this->Neumatico->validationErrors;
            }

            if(($this->request->data['NeumaticoEstado']['estado']=='En uso')&&($neumatico['unidad_id']=='')){
                $errores['Neumatico']['unidad_id'] = 'Debe seleccionar una unidad';
            }

            if($this->request->data['NeumaticoEstado']['km_unidad']<$this->request->data['NeumaticoEstado']['km_unidad_aux']){
                $errores['NeumaticoEstado']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }

            // Si se ha especificado una unidad en la solicitud, verifica cuántos neumáticos están asignados a esa unidad
            if (!empty($this->request->data['Neumatico']['unidad_id'])) {
                $neumaticosAsignados = $this->Neumatico->find('count', array(
                    'conditions' => array(
                        'Neumatico.unidad_id' => $this->request->data['Neumatico']['unidad_id']
                    )
                ));
            }

            // Si ya hay 5 neumáticos asignados a la unidad, agrega un error
            if ($neumaticosAsignados >= 5) {
                $errores['Neumatico']['unidad_id'] = 'La unidad ya tiene asignados 5 neumaticos';
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

                    try {
                        $this->NeumaticoEstado->create();
                        $this->NeumaticoEstado->set('neumatico_id',$this->Neumatico->id);
                        $this->NeumaticoEstado->set('fecha',$neumatico['fecha']);
                        $this->NeumaticoEstado->set('estado',$this->request->data['NeumaticoEstado']['estado']);
                        $this->NeumaticoEstado->set('desde',$neumatico['fecha']);
                        $this->NeumaticoEstado->set('km_unidad',$this->request->data['NeumaticoEstado']['km_unidad']);
                        $this->NeumaticoEstado->save();

                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == '1062') {
                            $errores['NeumaticoEstado']['estado'] = 'Estado repetido';
                            $grabar = 0;
                        } else {
                            $errores['Neumatico']['identificador'] = $e->errorInfo[1];
                            $grabar = 0;
                        }
                    }

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
                    $this->NeumaticoEstado->commit();
                    $this->set('resultado','OK');
                    $this->set('mensaje','Datos guardados');
                    $this->set('detalle','');
                }
                else
                {
                    $this->Neumatico->rollback();
                    $this->NeumaticoEstado->rollback();
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

    public function cambiar($ids){
        $this->layout = 'form';

        $idsArray = explode(",",$ids);
        //$this->loadModel('PuntoVenta');
        $neumatico = $this->Neumatico->find('first', array(
            'conditions' => array('Neumatico.id' => $idsArray[0]),
            'joins' => array(
                array(
                    'table' => 'neumatico_estados',
                    'alias' => 'NeumaticoEstado',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'NeumaticoEstado.neumatico_id = Neumatico.id',
                        'NeumaticoEstado.hasta IS NULL' // Condición para obtener el estado actual
                    )
                )
            )
        ));

        //print_r($neumatico);
        $this->loadModel('Categoria');
        $this->set('categorias', $this->Categoria->find('list',array('order' => array('Categoria.categoria ASC'))));
        $unidad_id = $neumatico['Neumatico']['unidad_id'];
        if ($unidad_id) {
            $this->loadModel('Unidad');
            $unidad = $this->Unidad->find('first',array('conditions'=>array('Unidad.id'=>$unidad_id)));

            $this->set('defaultCategoria',$unidad['Categoria']['id']);
            //lista de unidades
            $this->set('unidads', $this->Unidad->find('list',array('order' => array('Unidad.marca, Unidad.modelo ASC'),'conditions' => array('Unidad.estado' => 1, 'Unidad.categoria_id' => $unidad['Categoria']['id']))));
            $this->set('defaultUnidad',$unidad_id);


            //print_r($unidad);
            $this->set('km',$unidad['Unidad']['km']);
        }

        //$this->set('neumatico', $neumatico);
        //$this->set('km',$neumatico['NeumaticoEstado'][0]['km_unidad']);
        $this->set('fecha',$neumatico['NeumaticoEstado'][0]['fecha']);
        $this->set('dibujo',$neumatico['Neumatico']['dibujo']);
        $this->set('ids', $ids);


    }

    public function guardarCambio(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {


            $this->Neumatico->begin();
            $this->loadModel('NeumaticoEstado');
            $this->NeumaticoEstado->begin();




            if($this->request->data['NeumaticoEstado']['km_unidad']<$this->request->data['NeumaticoEstado']['km_unidad_aux']){
                $errores['NeumaticoEstado']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }

            if (empty($this->request->data['Neumatico']['fecha'])) {
                $errores['Neumatico']['fecha'][] = 'Ingrese una fecha valida';
            }
            //print_r($this->request->data);
            if ($this->dateFormatSQL($this->request->data['Neumatico']['fecha'])<$this->dateFormatSQL($this->request->data['Neumatico']['fecha_aux'])) {
                $errores['Neumatico']['fecha'][] = 'La fecha no puede ser menor a la del estado actual';
            }

            if($this->request->data['NeumaticoEstado']['km_unidad']<$this->request->data['NeumaticoEstado']['km_unidad_aux']){
                $errores['NeumaticoEstado']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }


            // Si se ha especificado una unidad en la solicitud, verifica cuántos neumáticos están asignados a esa unidad
            if (!empty($this->request->data['Neumatico']['unidad_id'])) {
                $neumaticosAsignados = $this->Neumatico->find('count', array(
                    'conditions' => array(
                        'Neumatico.unidad_id' => $this->request->data['Neumatico']['unidad_id']
                    )
                ));
            }

            // Si ya hay 5 neumáticos asignados a la unidad, agrega un error
            if ($neumaticosAsignados >= 5) {
                $errores['Neumatico']['unidad_id'] = 'La unidad ya tiene asignados 5 neumaticos';
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

                    try {
                        $this->NeumaticoEstado->create();
                        $this->NeumaticoEstado->set('neumatico_id',$this->Neumatico->id);
                        $this->NeumaticoEstado->set('fecha',$neumatico['fecha']);
                        $this->NeumaticoEstado->set('estado',$this->request->data['NeumaticoEstado']['estado']);
                        $this->NeumaticoEstado->set('desde',$neumatico['fecha']);
                        $this->NeumaticoEstado->set('km_unidad',$this->request->data['NeumaticoEstado']['km_unidad']);
                        $this->NeumaticoEstado->save();

                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == '1062') {
                            $errores['NeumaticoEstado']['estado'] = 'Estado repetido';
                            $grabar = 0;
                        } else {
                            $errores['Neumatico']['identificador'] = $e->errorInfo[1];
                            $grabar = 0;
                        }
                    }

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
                    $this->NeumaticoEstado->commit();
                    $this->set('resultado','OK');
                    $this->set('mensaje','Datos guardados');
                    $this->set('detalle','');
                }
                else
                {
                    $this->Neumatico->rollback();
                    $this->NeumaticoEstado->rollback();
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

