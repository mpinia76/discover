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
            elseif ($_GET['sSearch_11']!='Estado'){
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
                $neumatico['NeumaticoEstado']['fecha'],
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

    public function index2($id){

        $this->layout = 'index';
        $this->set('id',$id);
        //$this->setLogUsuario('Opciones de Cobro/alertas');
    }



    public function dataTable2($id){

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
            elseif ($_GET['sSearch_11']!='Estado'){
                $condicionSearch11 = array('NeumaticoEstado.estado = '=>$_GET['sSearch_11']);
            }
        }
        //print_r($condicionSearch11);
        $condicion=array('Neumatico.id'=>$id,$condicionSearch1,$condicionSearch2,$condicionSearch3,$condicionSearch4,$condicionSearch5,$condicionSearch6,$condicionSearch7,$condicionSearch9,$condicionSearch10,$condicionSearch11);

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
                    'Neumatico.id = NeumaticoEstado.neumatico_id'
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
                    'Neumatico.id = NeumaticoEstado.neumatico_id'
                )
            )


        ), 'conditions' => $condicion));

        $this->loadModel('Unidad');
        foreach ($neumaticos as $neumatico) {
            //print_r($neumatico);
            $this->Unidad->id = $neumatico['NeumaticoEstado']['unidad_id'];
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
                $neumatico['NeumaticoEstado']['fecha'],
                $neumatico['Neumatico']['marca'],
                $neumatico['Neumatico']['modelo'],
                $neumatico['Neumatico']['fabricacion'],
                $neumatico['Neumatico']['medida'],
                $neumatico['Neumatico']['temporada'],
                $neumatico['Neumatico']['identificador'],
                $unidad['Categoria']['categoria'],
                $unidad['Unidad']['unidad'],
                $neumatico['Neumatico']['posicion'],
                $neumatico['NeumaticoEstado']['estado'],
                $years,
                $neumatico['NeumaticoEstado']['km'],
                $neumatico['NeumaticoEstado']['desde'],
                $neumatico['NeumaticoEstado']['hasta'],
                $neumatico['NeumaticoEstado']['dibujo'],
                $neumatico['NeumaticoEstado']['motivo']
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
                        $this->NeumaticoEstado->set('unidad_id',$this->request->data['Neumatico']['unidad_id']);
                        $this->NeumaticoEstado->set('fecha',$neumatico['fecha']);
                        $this->NeumaticoEstado->set('dibujo',$this->request->data['NeumaticoEstado']['dibujo']);
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
                        // Verificar qué índice único causó la excepción
                        if (strpos($e->getMessage(), 'identificador') !== false) {
                            // Error de duplicación en el índice 'identificador'
                            $errores['Neumatico']['identificador'] = 'Identificador repetido';
                        } elseif (strpos($e->getMessage(), 'unidad_id_posicion') !== false) {
                            // Error de duplicación en el índice 'unidad_id_posicion'
                            $errores['Neumatico']['posicion'] = 'Ya existe la posicion para esta unidad';
                        } else {
                            // Otro tipo de error, manejar según sea necesario
                            $errores['Neumatico']['identificador'] = 'Error desconocido: ' . $e->errorInfo[1];
                        }
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
        /*$neumatico = $this->Neumatico->find('first', array(
            'conditions' => array('Neumatico.id' => $idsArray[0]),
        ));*/


            // Recuperar los estados asociados con el neumático
            $neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
                'conditions' => array(
                    'NeumaticoEstado.neumatico_id' => $idsArray[0],
                    'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
                )
            ));

            // Asignar los estados al neumático
            //$neumatico['NeumaticoEstado'] = $neumaticoEstados;


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
        //print_r($neumatico);
        $disabled = ($neumatico['NeumaticoEstado']['estado']=='En uso')?'disabled':'';
        //echo $neumatico['NeumaticoEstado']['estado'].' - '.$disabled;
        $this->set('fecha',$neumatico['NeumaticoEstado']['fecha']);
        $this->set('dibujo',$neumatico['NeumaticoEstado']['dibujo']);
        $this->set('ids', $ids);
        $this->set('disabled', $disabled);


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
                $ids =$this->request->data['Neumatico']['ids'];
                $ids = explode(",",$ids);
                $grabar = 1;
                foreach ($ids as $id) {

                        //echo 'id: '.$id;
                        /*$neumatico = $this->Neumatico->find('first', array(
                            'conditions' => array('Neumatico.id' => $id),
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
                        ));*/

                        $neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
                            'conditions' => array(
                                'NeumaticoEstado.neumatico_id' => $id,
                                'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
                            )
                        ));


                        $estado = ($neumatico['NeumaticoEstado']['estado']=='En uso')?'En deposito':'En uso';
                    $this->Neumatico->id = $id;
                    $neumaticoAux = $this->Neumatico->read();
                        if ($estado=='En deposito'){
                            $km=$neumatico['NeumaticoEstado']['km']+$this->request->data['NeumaticoEstado']['km_unidad']-$neumatico['NeumaticoEstado']['km_unidad'];

                            $this->Neumatico->set('unidad_id', null);

                        }
                        else{
                            $km=$neumatico['NeumaticoEstado']['km'];
                            $this->Neumatico->set('unidad_id', $this->request->data['Neumatico']['unidad_id']);
                        }
                        $this->Neumatico->save();
                        //print_r($neumatico);
                        //$this->Neumatico->save();

                        try {
                            $this->NeumaticoEstado->id = $neumatico['NeumaticoEstado']['id'];
                            $neumaticoEstado = $this->NeumaticoEstado->read();

                            $this->NeumaticoEstado->set('hasta', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->save();
                            $this->NeumaticoEstado->create();
                            $this->NeumaticoEstado->set('neumatico_id', $id);
                            $this->NeumaticoEstado->set('unidad_id', $this->request->data['Neumatico']['unidad_id']);
                            $this->NeumaticoEstado->set('fecha', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->set('estado', $estado);
                            $this->NeumaticoEstado->set('desde', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->set('dibujo', $this->request->data['Neumatico']['dibujo']);
                            $this->NeumaticoEstado->set('km_unidad', $this->request->data['NeumaticoEstado']['km_unidad']);
                            $this->NeumaticoEstado->set('km', $km);

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


    public function baja($id){
        $this->layout = 'form';


        $neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
            'conditions' => array(
                'NeumaticoEstado.neumatico_id' => $id,
                'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
            )
        ));

        $unidad_id = $neumatico['Neumatico']['unidad_id'];
        if ($unidad_id) {
            $this->loadModel('Unidad');
            $unidad = $this->Unidad->find('first',array('conditions'=>array('Unidad.id'=>$unidad_id)));


            //print_r($unidad);
            $this->set('km',$unidad['Unidad']['km']);
        }


        $this->set('fecha',$neumatico['NeumaticoEstado']['fecha']);
        $this->set('dibujo',$neumatico['NeumaticoEstado']['dibujo']);
        $this->set('id', $id);
        $this->set('motivos',array('Desgaste'=>'Desgaste','Rotura'=>'Rotura','Robo'=>'Robo','Venta'=>'Venta'));


    }

    public function guardarBaja(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

            $grabar = 1;
            $this->Neumatico->begin();
            $this->loadModel('NeumaticoEstado');
            $this->NeumaticoEstado->begin();




            /*if($this->request->data['NeumaticoEstado']['km_unidad']<$this->request->data['NeumaticoEstado']['km_unidad_aux']){
                $errores['NeumaticoEstado']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }*/

            if (empty($this->request->data['Neumatico']['fecha'])) {
                $errores['Neumatico']['fecha'][] = 'Ingrese una fecha valida';
            }
            //print_r($this->request->data);
            if ($this->dateFormatSQL($this->request->data['Neumatico']['fecha'])<$this->dateFormatSQL($this->request->data['Neumatico']['fecha_aux'])) {
                $errores['Neumatico']['fecha'][] = 'La fecha no puede ser menor a la del estado actual';
            }

            if (empty($this->request->data['NeumaticoEstado']['motivo'])) {
                $errores['NeumaticoEstado']['motivo'][] = 'Seleccione un motivo';
            }

            if (empty($this->request->data['NeumaticoEstado']['descripcion'])) {
                $errores['NeumaticoEstado']['descripcion'][] = 'Ingrese una descripcion';
            }

            $id=$this->request->data['Neumatico']['id'];

            $neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
                'conditions' => array(
                    'NeumaticoEstado.neumatico_id' => $id,
                    'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
                )
            ));


            $estado = $neumatico['NeumaticoEstado']['estado'];

            if ($estado=='Baja') {
                $errores['Neumatico']['fecha'][] = 'Ya fue dado de baja';
            }

            $foto1 = $this->request->data['NeumaticoEstado']['foto1'];
            $foto2 = $this->request->data['NeumaticoEstado']['foto2'];
            $foto3 = $this->request->data['NeumaticoEstado']['foto3'];



            // Directorio donde guardar las imágenes
            $uploadDir = WWW_ROOT . 'img' . DS . 'neumaticos' . DS;

            //echo $uploadDir;
            // Define las extensiones permitidas
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            // Función para validar la extensión del archivo


            // Verifica y guarda los archivos de imagen con extensiones válidas
            if (!empty($foto1['name'])) {
                $filename = $foto1['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions)){
                    move_uploaded_file($foto1['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto1'] = 'NO es una imagen';
                }

            }

            if (!empty($foto2['name'])) {
                $filename = $foto2['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions)){
                    move_uploaded_file($foto2['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto2'] = 'NO es una imagen';
                }

            }

            if (!empty($foto3['name'])) {
                $filename = $foto3['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions)){
                    move_uploaded_file($foto3['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto3'] = 'NO es una imagen';
                }

            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{





                $this->Neumatico->id = $id;
                $neumaticoAux = $this->Neumatico->read();
                if ($estado=='En deposito'){


                    $km=$neumatico['NeumaticoEstado']['km'];

                }
                else{

                    $km=$neumatico['NeumaticoEstado']['km']+$this->request->data['NeumaticoEstado']['km_unidad']-$neumatico['NeumaticoEstado']['km_unidad'];
                }
                $this->Neumatico->set('unidad_id', null);
                $this->Neumatico->save();
                //print_r($neumatico);
                //$this->Neumatico->save();



                try {
                    $this->NeumaticoEstado->id = $neumatico['NeumaticoEstado']['id'];
                    $neumaticoEstado = $this->NeumaticoEstado->read();

                    $this->NeumaticoEstado->set('hasta', $this->request->data['Neumatico']['fecha']);
                    $this->NeumaticoEstado->save();
                    $this->NeumaticoEstado->create();
                    $this->NeumaticoEstado->set('neumatico_id', $id);
                    $this->NeumaticoEstado->set('unidad_id', null);
                    $this->NeumaticoEstado->set('fecha', $this->request->data['Neumatico']['fecha']);
                    $this->NeumaticoEstado->set('estado', 'Baja');
                    $this->NeumaticoEstado->set('desde', $this->request->data['Neumatico']['fecha']);
                    $this->NeumaticoEstado->set('dibujo', $this->request->data['Neumatico']['dibujo']);
                    $this->NeumaticoEstado->set('km_unidad', $this->request->data['NeumaticoEstado']['km_unidad']);
                    $this->NeumaticoEstado->set('km', $km);
                    $this->NeumaticoEstado->set('motivo', $this->request->data['NeumaticoEstado']['motivo']);
                    $this->NeumaticoEstado->set('descripcion', $this->request->data['NeumaticoEstado']['descripcion']);
                    $this->NeumaticoEstado->set('foto1', $foto1['name']);
                    $this->NeumaticoEstado->set('foto2', $foto2['name']);
                    $this->NeumaticoEstado->set('foto3', $foto3['name']);
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

