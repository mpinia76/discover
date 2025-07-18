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
                $order='Neumatico.estado '.$orderType;
                break;
            case 12:
                $order='Neumatico.condicion '.$orderType;
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
        /*switch ($_GET['sSearch_11']) {
            case 'Estado':
                $condicionSearch11 = array();
                break;
            case 'Activas':
                $condicionSearch11 = array(
                    'or' => array(
                        array('Neumatico.estado' => 'En uso'),
                        array('Neumatico.estado' => 'En deposito')
                    )
                );
                break;
            case 'En uso':
                $condicionSearch11 = array('Neumatico.estado = '=>$_GET['sSearch_11']);
                break;
            case 'En deposito':
                $condicionSearch11 = array('Neumatico.estado = '=>$_GET['sSearch_11']);
                break;
            case 'Baja':
                $condicionSearch11 = array('Neumatico.estado = '=>$_GET['sSearch_11']);
                break;
            default:
                $condicionSearch11 = array(
                    'or' => array(
                        array('Neumatico.estado' => 'En uso'),
                        array('Neumatico.estado' => 'En deposito')
                    )
                );
                break;

        }*/
        $condicionSearch11 = ($_GET['sSearch_11'])?array('Neumatico.estado = '=>$_GET['sSearch_11']):array();
        $condicionSearch12 = ($_GET['sSearch_12'])?array('Neumatico.condicion = '=>$_GET['sSearch_12']):array();
        /*if ($_GET['sSearch_11']){
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
        }*/
        //print_r($condicionSearch11);
        $condicion=array($condicionSearch1,$condicionSearch2,$condicionSearch3,$condicionSearch4,$condicionSearch5,$condicionSearch6,$condicionSearch7,$condicionSearch9,$condicionSearch10,$condicionSearch11,$condicionSearch12);

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
            )


        ),'fields'=>array('Neumatico.*', 'Unidad.marca AS unidad_marca', 'Unidad.patente AS patente'), 'conditions' => $condicion,'order' => $order, 'offset'=>$_GET['iDisplayStart'], 'limit'=>$_GET['iDisplayLength'], 'recursive' => -1));

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
                'table' => 'unidads',
                'alias' => 'Unidad',
                'type' => 'LEFT',
                'conditions' => array(
                    'Unidad.id = Neumatico.unidad_id'
                )
            )


        ),array( 'conditions' => $condicion)));

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
                $neumatico['Neumatico']['estado'],
                $neumatico['Neumatico']['condicion'],
                $years,
                $neumatico['Neumatico']['km']
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
        $this->set('condiciones',array('Nueva' => 'Nueva','Usada' => 'Usada'));
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


        //print_r($neumatico);
        /*$this->set('defaultEstado', $neumaticoEstado['NeumaticoEstado']['estado']);
        $this->set('defaultDibujo', $neumaticoEstado['NeumaticoEstado']['dibujo']);*/
        $this->set('defaultKm', $neumatico['Neumatico']['km_unidad']);
        $this->set('condiciones',array('Nueva' => 'Nueva','Usada' => 'Usada'));
        $this->set('posiciones',array('DI' => 'DI','DD' => 'DD','TI' => 'TI','TD' => 'TD','Auxilio' => 'Auxilio'));
        $this->set('temporadas',array('Verano'=> 'Verano', 'Invierno Clavos'=> 'Invierno Clavos', 'Invierno Silice'=> 'Invierno Silice', 'Mixto'=> 'Mixto'));
        $this->set('estados',array('En uso' => 'En uso','En deposito' => 'En deposito'));

        $this->set('neumatico', $this->Neumatico->read());
        $this->loadModel('Categoria');
        $this->set('categorias', $this->Categoria->find('list',array('order' => array('Categoria.categoria ASC'))));
        $unidad_id = $neumatico['Neumatico']['unidad_id'];
        if ($unidad_id) {
            $this->loadModel('Unidad');
            $unidad = $this->Unidad->find('first', array('conditions' => array('Unidad.id' => $unidad_id)));

            $this->set('defaultCategoria', $unidad['Categoria']['id']);
            //lista de unidades
            $this->set('unidads', $this->Unidad->find('list', array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' => array('Unidad.estado' => 1, 'Unidad.categoria_id' => $unidad['Categoria']['id']))));
            $this->set('defaultUnidad', $unidad_id);
        }
    }


    public function guardar() {
        if(!empty($this->request->data)) {
            $this->Neumatico->begin();
            $this->loadModel('NeumaticoEstado');
            $this->NeumaticoEstado->begin();

            $neumatico = $this->request->data['Neumatico'];
            $this->Neumatico->set($neumatico);

            if(!$this->Neumatico->validates()) {
                $errores['Neumatico'] = $this->Neumatico->validationErrors;
            }

            if($neumatico['estado'] == 'En uso' && empty($neumatico['unidad_id'])) {
                $errores['Neumatico']['unidad_id'] = 'Debe seleccionar una unidad';
            }

            if($neumatico['condicion'] == 'Nueva' && $neumatico['km'] != '0') {
                $errores['Neumatico']['km'] = 'Si la condicion es Nueva los KM deben ser 0 (cero)';
            }

            if($neumatico['condicion'] == 'Usada' && $neumatico['km'] == '0') {
                $errores['Neumatico']['km'] = 'Si la condicion es Usada los KM no pueden ser 0 (cero)';
            }

            if($neumatico['km_unidad'] < $neumatico['km_unidad_aux']) {
                $errores['Neumatico']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }

            // ❗️Validación personalizada por estado
            if (!empty($neumatico['unidad_id']) && !empty($neumatico['posicion']) && !empty($neumatico['estado'])) {
                $unidadId = $neumatico['unidad_id'];
                $posicion = $neumatico['posicion'];
                $estado = $neumatico['estado'];
                $neumaticoId = !empty($this->Neumatico->id) ? $this->Neumatico->id : 0;

                $neumaticosExistentes = $this->Neumatico->find('count', [
                    'conditions' => [
                        'Neumatico.unidad_id' => $unidadId,
                        'Neumatico.posicion' => $posicion,
                        'Neumatico.estado' => $estado,
                        'NOT' => ['Neumatico.id' => $neumaticoId]
                    ]
                ]);

                if ($estado === 'En uso' && $neumaticosExistentes >= 1) {
                    $errores['Neumatico']['posicion'] = 'Ya existe un neumático en uso para esta posición en la unidad.';
                }

                if ($estado === 'En deposito' && $neumaticosExistentes >= 3) {
                    $errores['Neumatico']['posicion'] = 'Ya hay 2 neumáticos en depósito para esta posición en la unidad.';
                }
            }

            // Validar máximo 5 neumáticos asignados a una unidad
            if (!empty($neumatico['unidad_id'])) {
                $neumaticosAsignados = $this->Neumatico->find('count', [
                    'conditions' => [
                        'Neumatico.unidad_id' => $neumatico['unidad_id'],
                        'Neumatico.estado' => 'En uso'
                    ]
                ]);

                if ($neumaticosAsignados >= 5) {
                    $errores['Neumatico']['unidad_id'] = 'La unidad ya tiene asignados 5 neumáticos';
                }
            }

            if (isset($errores) && count($errores) > 0) {
                $this->set('resultado', 'ERROR');
                $this->set('mensaje', 'No se pudo guardar');
                $this->set('detalle', $errores);
            } else {
                $grabar = 1;
                try {
                    $this->Neumatico->save();

                    try {
                        $neumaticoEstado = $this->Neumatico->NeumaticoEstado->find('first', [
                            'conditions' => [
                                'NeumaticoEstado.neumatico_id' => $this->Neumatico->id,
                                'NeumaticoEstado.hasta IS NULL'
                            ]
                        ]);

                        if (!empty($neumaticoEstado)) {
                            $this->NeumaticoEstado->id = $neumaticoEstado['NeumaticoEstado']['id'];
                            $neumaticoEstado = $this->NeumaticoEstado->read();

                            $this->NeumaticoEstado->set('unidad_id', $neumatico['unidad_id']);
                            $this->NeumaticoEstado->set('fecha', $neumatico['fecha']);
                            $this->NeumaticoEstado->set('dibujo', $neumatico['dibujo']);
                            $this->NeumaticoEstado->set('estado', $neumatico['estado']);
                            $this->NeumaticoEstado->set('desde', $neumatico['fecha']);
                            $this->NeumaticoEstado->set('km_unidad', $neumatico['km_unidad']);
                            $this->NeumaticoEstado->set('km', $neumatico['km']);
                            $this->NeumaticoEstado->save();
                        } else {
                            $this->NeumaticoEstado->create();
                            $this->NeumaticoEstado->set('neumatico_id', $this->Neumatico->id);
                            $this->NeumaticoEstado->set('unidad_id', $neumatico['unidad_id']);
                            $this->NeumaticoEstado->set('fecha', $neumatico['fecha']);
                            $this->NeumaticoEstado->set('dibujo', $neumatico['dibujo']);
                            $this->NeumaticoEstado->set('estado', $neumatico['estado']);
                            $this->NeumaticoEstado->set('desde', $neumatico['fecha']);
                            $this->NeumaticoEstado->set('km_unidad', $neumatico['km_unidad']);
                            $this->NeumaticoEstado->set('km', $neumatico['km']);
                            $this->NeumaticoEstado->save();
                        }

                    } catch (PDOException $e) {
                        if ($e->errorInfo[1] == '1062') {
                            $errores['Neumatico']['estado'] = 'Estado repetido';
                            $grabar = 0;
                        } else {
                            $errores['Neumatico']['identificador'] = $e->errorInfo[1];
                            $grabar = 0;
                        }
                    }

                } catch (PDOException $e) {
                    if ($e->errorInfo[1] == '1062') {
                        if (strpos($e->getMessage(), 'identificador') !== false) {
                            $errores['Neumatico']['identificador'] = 'Identificador repetido';
                        } elseif (strpos($e->getMessage(), 'unidad_id_estado_posicion') !== false) {
                            $errores['Neumatico']['posicion'] = 'Ya existe la posicion para esta unidad';
                        } else {
                            $errores['Neumatico']['identificador'] = 'Error desconocido: ' . $e->errorInfo[1];
                        }
                        $grabar = 0;
                    }
                }

                if ($grabar) {
                    $this->Neumatico->commit();
                    $this->NeumaticoEstado->commit();
                    $this->set('resultado', 'OK');
                    $this->set('mensaje', 'Datos guardados');
                    $this->set('detalle', '');
                } else {
                    $this->Neumatico->rollback();
                    $this->NeumaticoEstado->rollback();
                    $this->set('resultado', 'ERROR');
                    $this->set('mensaje', 'No se pudo guardar');
                    $this->set('detalle', $errores);
                }
            }

            $this->set('_serialize', ['resultado', 'mensaje', 'detalle']);
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
            /*$neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
                'conditions' => array(
                    'NeumaticoEstado.neumatico_id' => $idsArray[0],
                    'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
                )
            ));*/
        $this->set('posiciones',array('DI' => 'DI','DD' => 'DD','TI' => 'TI','TD' => 'TD','Auxilio' => 'Auxilio'));
            // Asignar los estados al neumático
            //$neumatico['NeumaticoEstado'] = $neumaticoEstados;

        $this->Neumatico->id = $idsArray[0];
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;
        $this->set('neumatico', $this->Neumatico->read());
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
        $disabled = ($neumatico['Neumatico']['estado']=='En uso')?'disabled':'';
        //echo $neumatico['NeumaticoEstado']['estado'].' - '.$disabled;
        $this->set('fecha',$neumatico['Neumatico']['fecha']);

        $this->set('dibujo',$neumatico['Neumatico']['dibujo']);
        $this->set('ids', $ids);
        $this->set('disabled', $disabled);


    }

    public function guardarCambio(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {


            $this->Neumatico->begin();
            $this->loadModel('NeumaticoEstado');
            $this->NeumaticoEstado->begin();


            if (empty($this->request->data['Neumatico']['fecha'])) {
                $errores['Neumatico']['fecha'][] = 'Ingrese una fecha valida';
            }
            //print_r($this->request->data);
            if ($this->dateFormatSQL($this->request->data['Neumatico']['fecha'])<$this->dateFormatSQL($this->request->data['Neumatico']['fecha_aux'])) {
                $errores['Neumatico']['fecha'][] = 'La fecha no puede ser menor a la del estado actual';
            }

            if($this->request->data['Neumatico']['km_unidad']<$this->request->data['Neumatico']['km_unidad_aux']){
                $errores['Neumatico']['km_unidad'] = 'Los KM no pueden ser menores a los actuales de la unidad';
            }


            // Si se ha especificado una unidad en la solicitud, verifica cuántos neumáticos están asignados a esa unidad
            if (!empty($this->request->data['Neumatico']['unidad_id'])) {
                $neumaticosAsignados = $this->Neumatico->find('count', array(
                    'conditions' => array(
                        'Neumatico.unidad_id' => $this->request->data['Neumatico']['unidad_id'],'Neumatico.estado'=>'En uso'
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

                        $this->Neumatico->id = $id;
                        //$this->request->data = $this->Neumatico->read();
                        $neumatico = $this->Neumatico->read();

                        //print_r($neumatico);
                        $estado = ($neumatico['Neumatico']['estado']=='En uso')?'En deposito':'En uso';
                        $this->Neumatico->id = $id;
                    file_put_contents(APP . 'tmp/logs/debug.log', 'Procesando neumático con estado: ' . $estado . "\n", FILE_APPEND);
                        if ($estado=='En deposito'){
                            $km=$neumatico['Neumatico']['km']+$this->request->data['Neumatico']['km_unidad']-$neumatico['Neumatico']['km_unidad'];

                            //$this->Neumatico->set('unidad_id', null);

                        }
                        else{
                            $km=$neumatico['Neumatico']['km'];
                            //$this->Neumatico->set('unidad_id', $this->request->data['Neumatico']['unidad_id']);
                        }

                    // Obtener los datos de unidad y posición para las validaciones
                    $unidadId = $this->request->data['Neumatico']['unidad_id'];
                    $posicion = $this->request->data['Neumatico']['posicion'];

                    // Validación de solo un neumático "En uso" para la misma unidad y posición
                    if ($estado == 'En uso') {
                        $neumaticoEnUso = $this->Neumatico->find('count', array(
                            'conditions' => array(
                                'Neumatico.unidad_id' => $unidadId,
                                'Neumatico.posicion' => $posicion,
                                'Neumatico.estado' => 'En uso'
                            )
                        ));
                        //file_put_contents(APP . 'tmp/logs/debug.log', 'cant: ' . $neumaticoEnUso . "\n", FILE_APPEND);
                        if ($neumaticoEnUso > 0) {
                            $errores['Neumatico']['posicion'] = 'Ya existe un neumático "En uso" para esta unidad y posición';
                            $grabar = 0;
                        }
                    }

                    // Validación de hasta 2 neumáticos "En depósito" para la misma unidad y posición
                    if ($estado == 'En deposito') {
                        $neumaticosEnDeposito = $this->Neumatico->find('count', array(
                            'conditions' => array(
                                'Neumatico.unidad_id' => $unidadId,
                                'Neumatico.posicion' => $posicion,
                                'Neumatico.estado' => 'En deposito'
                            )
                        ));
                        //file_put_contents(APP . 'tmp/logs/debug.log', 'cant: ' . $neumaticosEnDeposito . "\n", FILE_APPEND);
                        if ($neumaticosEnDeposito >= 3) {
                            $errores['Neumatico']['posicion'] = 'Ya existen 3 neumáticos "En depósito" para esta unidad y posición';
                            $grabar = 0;
                        }
                    }

                    // Si ya se encontró un error, no continuar
                    if ($grabar == 0) {
                        break;
                    }


                        //print_r($this->request->data);
                        $this->Neumatico->set('dibujo', $this->request->data['Neumatico']['dibujo']);
                        $this->Neumatico->set('posicion', $this->request->data['Neumatico']['posicion']);
                        $this->Neumatico->set('estado', $estado);
                        $this->Neumatico->set('km_unidad', $this->request->data['Neumatico']['km_unidad']);
                        $this->Neumatico->set('km', $km);

                        if ($estado == 'En uso') {
                            $this->Neumatico->set('unidad_id', $unidadId);
                        }
                    //file_put_contents(APP . 'tmp/logs/debug.log', 'Estado: ' . $estado .' unidad: '.$this->Neumatico->unidad_id. "\n", FILE_APPEND);
                        //$this->Neumatico->save();

                        try {
// Suponiendo que $estado es un objeto o un array
                            //file_put_contents(APP . 'tmp/logs/debug.log', 'Guardar: ' . print_r($this->Neumatico, true) . "\n", FILE_APPEND);

                            if (!$this->Neumatico->save()) {
                                file_put_contents(APP . 'tmp/logs/debug.log', print_r($this->Neumatico->validationErrors, true), FILE_APPEND);
                            }
                            $neumaticoEstado = $this->Neumatico->NeumaticoEstado->find('first', array(
                                'conditions' => array(
                                    'NeumaticoEstado.neumatico_id' => $id,
                                    'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
                                )
                            ));

                            $this->NeumaticoEstado->id = $neumaticoEstado['NeumaticoEstado']['id'];
                            $neumaticoEstado = $this->NeumaticoEstado->read();

                            $this->NeumaticoEstado->set('hasta', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->save();
                            $this->NeumaticoEstado->create();
                            $this->NeumaticoEstado->set('neumatico_id', $id);
                            if ($estado == 'En uso') {
                                $this->NeumaticoEstado->set('unidad_id', $this->request->data['Neumatico']['unidad_id']);
                            }
                            else{
                                $this->NeumaticoEstado->set('unidad_id', $neumatico['Neumatico']['unidad_id']);
                            }
                            $this->NeumaticoEstado->set('fecha', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->set('estado', $estado);
                            $this->NeumaticoEstado->set('desde', $this->request->data['Neumatico']['fecha']);
                            $this->NeumaticoEstado->set('dibujo', $this->request->data['Neumatico']['dibujo']);
                            $this->NeumaticoEstado->set('km_unidad', $this->request->data['Neumatico']['km_unidad']);
                            $this->NeumaticoEstado->set('km', $km);
                            $this->NeumaticoEstado->set('descripcion', $this->request->data['NeumaticoEstado']['descripcion']);
                            $this->NeumaticoEstado->save();

                        } catch (PDOException $e) {
                            if ($e->errorInfo[1] == '1062') {
                                // Verificar qué índice único causó la excepción
                                if (strpos($e->getMessage(), 'identificador') !== false) {
                                    // Error de duplicación en el índice 'identificador'
                                    $errores['Neumatico']['identificador'] = 'Identificador repetido';
                                } elseif (strpos($e->getMessage(), 'unidad_id_estado_posicion') !== false) {
                                    // Error de duplicación en el índice 'unidad_id_posicion'
                                    $errores['Neumatico']['posicion'] = 'Ya existe la posicion para esta unidad';
                                } else {
                                    // Otro tipo de error, manejar según sea necesario
                                    $errores['Neumatico']['identificador'] = 'Error desconocido: ' . $e->errorInfo[1];
                                }
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


        /*$neumatico = $this->Neumatico->NeumaticoEstado->find('first', array(
            'conditions' => array(
                'NeumaticoEstado.neumatico_id' => $id,
                'NeumaticoEstado.hasta IS NULL' // Filtrar solo el estado actual
            )
        ));*/

        $this->Neumatico->id = $id;
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;
        $this->set('neumatico', $this->Neumatico->read());

        $unidad_id = $neumatico['Neumatico']['unidad_id'];
        if ($unidad_id) {
            $this->loadModel('Unidad');
            $unidad = $this->Unidad->find('first',array('conditions'=>array('Unidad.id'=>$unidad_id)));


            //print_r($unidad);
            $this->set('km',$unidad['Unidad']['km']);
        }


        //$this->set('fecha',$neumatico['NeumaticoEstado']['fecha']);
        $this->set('dibujo',$neumatico['Neumatico']['dibujo']);
        //$this->set('id', $id);
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

            $maxFileSize = 2097152; // 2MB in bytes

            // Verifica y guarda los archivos de imagen con extensiones válidas
            if (!empty($foto1['name'])) {
                $filename = $foto1['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions) && $foto1['size'] <= $maxFileSize) {
                    move_uploaded_file($foto1['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto1'] = 'NO es una imagen o supera el tamaño máximo de 2MB';
                }


            }

            if (!empty($foto2['name'])) {
                $filename = $foto2['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions) && $foto2['size'] <= $maxFileSize) {
                    move_uploaded_file($foto2['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto2'] = 'NO es una imagen o supera el tamaño máximo de 2MB';
                }

            }

            if (!empty($foto3['name'])) {
                $filename = $foto3['name'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $allowedExtensions) && $foto3['size'] <= $maxFileSize) {
                    move_uploaded_file($foto3['tmp_name'], $uploadDir . $filename); // Guarda el archivo en el servidor
                    // Ahora puedes almacenar la ruta de la imagen en la base de datos si es necesario
                }
                else{
                    $errores['NeumaticoEstado']['foto3'] = 'NO es una imagen o supera el tamaño máximo de 2MB';
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


                    $km=$neumatico['Neumatico']['km'];

                }
                else{

                    $km=$neumatico['NeumaticoEstado']['km']+$this->request->data['Neumatico']['km_unidad']-$neumatico['Neumatico']['km_unidad'];
                }
                $this->Neumatico->set('estado', 'Baja');
                $this->Neumatico->set('km', $km);
                $this->Neumatico->set('dibujo', $this->request->data['Neumatico']['dibujo']);
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
                    $this->NeumaticoEstado->set('km_unidad', $this->request->data['Neumatico']['km_unidad']);
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


    public function detalle($id = null){
        $this->layout = 'informe';



        $this->Neumatico->id = $id;
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;

        $unidad_id = $neumatico['NeumaticoEstado'][0]['unidad_id'];
        $this->loadModel('Unidad');
        if ($unidad_id) {

            $unidad = $this->Unidad->find('first',array('conditions'=>array('Unidad.id'=>$unidad_id)));


            //print_r($unidad);
            $this->set('unidad',$unidad);
        }

        // Recorrer los estados del neumático y agregar la descripción de la unidad
        foreach ($neumatico['NeumaticoEstado'] as $k => $neumaticoEstado) {
            $unidadEstado = $this->Unidad->find('first', array('conditions' => array('Unidad.id' => $neumaticoEstado['unidad_id'])));
            // Agregar la descripción de la unidad al estado del neumático
            $neumatico['NeumaticoEstado'][$k]['descripcion_unidad'] = isset($unidadEstado['Unidad']) ? $unidadEstado['Unidad'] : null;
        }

        //print_r($neumatico);

        $this->set('neumatico',$neumatico);
    }

    public function fotos($id = null){
        $this->layout = 'informe';
        $this->Neumatico->id = $id;
        $this->request->data = $this->Neumatico->read();
        $neumatico = $this->request->data;
        //print_r($neumatico);
        $imagePath = 'img' . DS . 'neumaticos' . DS;


        // Obtener la URL relativa para la imagen
        $imageUrl = Router::url('/' . $imagePath, true);
        //echo $imageUrl;
        $this->set('neumatico',$neumatico);
        $this->set('imageUrl',$imageUrl);
    }

}
?>

