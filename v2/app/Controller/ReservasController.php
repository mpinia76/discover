<?php
ini_set('memory_limit', '-1');
session_start();
class ReservasController extends AppController {

	public $scaffold;
    public $components = array('Mpdf');

	public function dateFormatSQL($dateString) {
        $date_parts = explode("/",$dateString);
        return $date_parts[2]."-".$date_parts[1]."-".$date_parts[0];
    }

	public function index_check(){
	    $this->layout = 'index';


		$_SESSION['retiro'] = '';
		$_SESSION['devolucion'] = '';
		$_SESSION['HoraRetiro'] = '';
		$_SESSION['HoraDevolucion'] = '';

		if (isset($this->data['HoraRetiro'])) {
			$_SESSION['HoraRetiro'] = $this->data['HoraRetiro'];
		}
		if (isset($this->data['retiro'])) {
			$_SESSION['retiro'] = $this->data['retiro'];
		}
    	if (isset($this->data['devolucion'])) {
			$_SESSION['devolucion'] = $this->data['devolucion'];
		}
		if (isset($this->data['HoraDevolucion'])) {
			$_SESSION['HoraDevolucion'] = $this->data['HoraDevolucion'];
		}

		if ($_SESSION['retiro']) {
			list($dia1,$mes1,$ano1) = explode("/",$_SESSION['retiro']);
			$horarios = $this->dameHorarios($dia1, $mes1, $ano1);
			$this->set('horariosRetiro', $horarios);
		}
		if ($_SESSION['devolucion']) {
			list($dia1,$mes1,$ano1) = explode("/",$_SESSION['devolucion']);
			$horarios = $this->dameHorarios($dia1, $mes1, $ano1);
			$this->set('horariosDevolucion', $horarios);
		}
		/*$this->set('horariosRetiro', $this->dameTodasLasHoras());
		$this->set('horariosDevolucion', $this->dameTodasLasHoras());*/

		$this->setLogUsuario('Check de disponibilidad');

    }


    public function index(){
	    $this->layout = 'index';

		$_SESSION['restricted'] = 'false';
		$_SESSION['desde'] = '';
		$_SESSION['hasta'] = '';
	    if((isset($this->data['year']))&&(sizeof($this->data['year']))>0){
          $_SESSION['year'] = array_pop($this->data['year']);
		  $_SESSION['month'] = $this->data['month'];
		}else{
          $_SESSION['year'] = date('Y');
		  $_SESSION['month'] = 'Todos';
		}
		if (isset($this->data['desde'])) {
			$_SESSION['desde'] = $this->data['desde'];
		}
    	if (isset($this->data['hasta'])) {
			$_SESSION['hasta'] = $this->data['hasta'];
		}
		$this->setLogUsuario('Ventas');
    }

    public function index_restringido(){
	   $this->layout = 'index';
	   $_SESSION['restricted'] = 'true';
	   if((isset($this->data['year']))&&(sizeof($this->data['year']))>0){
          $_SESSION['year'] = array_pop($this->data['year']);
          $_SESSION['month'] = $this->data['month'];
		}else{
		  $_SESSION['year'] = date('Y');
		  $_SESSION['month'] = '01';
		}
		$this->setLogUsuario('Carga de extras y facturas');
        $this->loadModel('Usuario');
        $user_id = $_SESSION['useridushuaia'];
        $user = $this->Usuario->find('first',array('conditions'=>array('Usuario.id'=>$user_id)));

        $permisoPlanilla=1;
        $permisoCheckConsumo=1;
        $permisoCarga=1;
        $permisoFinalizar=1;
        $permisoMasiva=1;

        if ($user['Usuario']['admin'] != '1'){
            $this->loadModel('UsuarioPermiso');
            $permisos = $this->UsuarioPermiso->findAllByUsuarioId($user_id);
            $permisoPlanilla=0;
            $permisoCheckConsumo=0;
            $permisoCarga=0;
            $permisoFinalizar=0;
            $permisoMasiva=0;

            foreach($permisos as $permiso){
                if ($permiso['UsuarioPermiso']['permiso_id']==152) {
                    $permisoPlanilla=1;
                    continue;
                }
                if ($permiso['UsuarioPermiso']['permiso_id']==151) {
                    $permisoCheckConsumo=1;
                    continue;
                }
                if ($permiso['UsuarioPermiso']['permiso_id']==153) {
                    $permisoCarga=1;
                    continue;
                }
                if ($permiso['UsuarioPermiso']['permiso_id']==154) {
                    $permisoFinalizar=1;
                    continue;
                }
                if ($permiso['UsuarioPermiso']['permiso_id']==155) {
                    $permisoMasiva=1;
                    continue;
                }


            }
        }
        $this->set('permisoPlanilla',$permisoPlanilla);
        $this->set('permisoCheckConsumo',$permisoCheckConsumo);
        $this->set('permisoCarga',$permisoCarga);
        $this->set('permisoFinalizar',$permisoFinalizar);
        $this->set('permisoMasiva',$permisoMasiva);
    }

     public function get_reservas_restringidas($year, $month) {
        $from = $year .'-'. $month .'-01 00:00:00';
	$to = $year .'-'. $month .'-31 00:00:00';
	$result = Cache::read('get_reservas_restringidas', 'long');
        if (!$result) {
			$result = $this->Reserva->find('all',array('order' => 'Reserva.id desc', 'conditions' => array('Reserva.devolucion between ? and ?' => array($from, $to))));
			Cache::write('get_reservas_restringidas', $result, 'long');
		}
        return $result;
    }


    public function get_reservas($year, $month, $desde, $hasta) {

		if($month == 'Todos'){
		    $from = $year .'-01-01';
	            $to = $year .'-12-31';
		}else{
		    $from = $year .'-'. $month .'-01';
		    $to = $year .'-'. $month .'-31';
		}

		if (($desde!='')&&($hasta!='')) {
			$condicion=array('Reserva.retiro between ? and ?' => array($from, $to),'Reserva.creado between ? and ?' => array($this->dateFormatSQL($desde), $this->dateFormatSQL($hasta)));
		}
		else $condicion=array('Reserva.retiro between ? and ?' => array($from, $to));
		$result = Cache::read('get_reservas', 'long');
                if (!$result) {
	         $result = $this->Reserva->find('all',array('order' => 'Reserva.id desc', 'conditions' => $condicion));
	       Cache::write('get_reservas', $result, 'long');
	       }
          return $result;
    }

    public function dataTable($columnas_extras = 'todas', $order = 'Reserva.id desc'){

            $rows = array();

	    $year = $_SESSION['year'];
	    $month = $_SESSION['month'];
	    $restricted = $_SESSION['restricted'];

	    $desde = $_SESSION['desde'];
	    $hasta = $_SESSION['hasta'];


	    if ($restricted == 'true') {
	       $reservas = $this->get_reservas_restringidas($year, $month);
	    } else {
               $reservas = $this->get_reservas($year, $month, $desde, $hasta);
	    }



        foreach($reservas as $reserva){
			//print_r($reserva);
            $adelantadas = 0;
            $no_adelantadas = 0;
            $pagado = 0;
            $fiscal = 0;
            $descontado = 0;
            if(count($reserva['ReservaCobro'])>0){
                foreach($reserva['ReservaCobro'] as $cobro){
                    if($cobro['tipo'] == 'DESCUENTO'){
                        $descontado += $cobro['monto_neto'];
                    }else{
                        if($cobro['tipo'] == 'TARJETA' or $cobro['tipo'] == 'TRANSFERENCIA'){
                            $fiscal += $cobro['monto_cobrado'];
                        }
                        $pagado += $cobro['monto_neto'];
                    }
                }
            }
            //if(count($reserva['ReservaExtra']>0)){
                foreach($reserva['ReservaExtra'] as $extra){
                    if($extra['adelantada'] == 1){
                        $adelantadas = $adelantadas + $extra['cantidad'] * $extra['precio'];
                    }else{
                        $no_adelantadas = $no_adelantadas + $extra['cantidad'] * $extra['precio'];
                    }
                }
           // }

            $devoluciones = 0;
            if(count($reserva['ReservaDevolucion']) > 0){
                foreach($reserva['ReservaDevolucion'] as $devolucion){
                    $devoluciones += $devolucion['monto'];
                }
            }

            $facturado = 0;
            if(count($reserva['ReservaFactura']) > 0){
                foreach($reserva['ReservaFactura'] as $factura){
                    $facturado += $factura['monto'];
                }
            }
            $pendiente = round(round($reserva['Reserva']['total'],2) + round($no_adelantadas,2) - round($descontado,2) - round($pagado,2) + round($devoluciones,2),2);
            $pendiente = ($pendiente==-0)?0:$pendiente;
            $total = $reserva['Reserva']['total'] + $no_adelantadas - $descontado;

            switch($reserva['Reserva']['estado']){
                case 0:
                    if($pagado == 0){
                        $estado_text = "Cobro pendiente";
                        $estado_num = 0;
                    }elseif($pendiente > 0){
                        $estado_text = "Cobro parcial";
                        $estado_num = 0;
                    }elseif($pendiente == 0){
                        $estado_text = "Cobrado: pendiente de cierre";
                        $estado_num = "FINALIZA";
                    }else{
                        $estado_text = "Revisar";
                        $estado_num = '666';
                    }
                    break;
                case 1:
                    if($facturado >= $fiscal){
                        $estado_text = "Cobrado: facturado";
                        $estado_num = 1;
                    }elseif($facturado < $fiscal){
                        $estado_text = "Cobrado: discrepancia";
                        $estado_num = 1;
                    }else{
                        $estado_text = "Revisar";
                        $estado_num = '666';
                    }
                    break;
                case 2:
                    if($devoluciones > 0){
                    	if ($devoluciones==$pagado) {
                    		$estado_text = "Cancelada";
	                    	if (($estado[1])&&($estado[0]=='EST')) {
	                        	$contar = ($estado[1]==7)?1:0;
	                        }
                    	}
                    	else{
                        	$estado_text = "Cancelada: devolucion parcial";
	                    	if (($estado[1])&&($estado[0]=='EST')) {
	                        	$contar = ($estado[1]==6)?1:0;
	                        }
                    	}
                    }else{
                        $estado_text = "Cancelada";
                    }
                    $estado_num = 2;
                    break;
                case 3:

                    $estado_text = "Unidad Bloqueada";

                    $estado_num = 3;
                    break;
            }
            $row_data = array(
                $reserva['Reserva']['id'],
                $reserva['Reserva']['numero'],
                $reserva['Reserva']['creado'],
                $reserva['Cliente']['nombre_apellido'],
                $reserva['Unidad']['marca']." ".$reserva['Unidad']['modelo']." ".$reserva['Unidad']['patente'],
                $reserva['Reserva']['retiro']." ".$reserva['Reserva']['hora_retiro'],
                $reserva['Reserva']['devolucion']." ".$reserva['Reserva']['hora_devolucion']
            );
            $planillaEnviada = ($reserva['Reserva']['planilla'])?'<img src="../img/ok.gif">('.$reserva['Reserva']['planilla'].')':'<img src="../img/bt_anular.png">';
	            $voucherEnviada = ($reserva['Reserva']['voucher'])?'<img src="../img/ok.gif">('.$reserva['Reserva']['voucher'].')':'<img src="../img/bt_anular.png">';
            if($columnas_extras == 'todas'){
                array_push($row_data,
                '$'.$reserva['Reserva']['total_estadia'],
                '$'.$adelantadas,
                '$'.$no_adelantadas,
                '$'.round($reserva['Reserva']['total']+$no_adelantadas,2),
                '$'.$descontado,
                '$'.$total,
                '$'.$pendiente,
                $estado_text,
                $estado_num,
                $planillaEnviada,
                $voucherEnviada);
            }else{
                array_push($row_data,
                '$'.$no_adelantadas,
                '$'.$descontado,
                '$'.$pendiente,
                $estado_text,
                $estado_num,
                $planillaEnviada,
                $voucherEnviada);
            }
            $rows[] = $row_data;
        }
        $this->set('aaData',$rows);
        //print_r($rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }


	public function get_reservas_check($desde, $hasta) {

		$result = Cache::read('get_reservas_check', 'long');

		if (($desde!='')&&($hasta!='')) {

			$condicion=array(array('or' => array(
	        		'Reserva.estado <> ' => 2,
	        		'Reserva.estado ' => null,
	        	)),'or' => array(
	        	  array('CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) <'=>$desde,
	        	  'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) >'=>$hasta),
			      'CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) between ? and ?' => array($desde, $hasta),
			      'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) between ? and ?' => array($desde, $hasta),
			    ));

			if (!$result) {
	         	$result = $this->Reserva->find('all',array('fields'=>'Reserva.unidad_id','order' => 'Reserva.id desc', 'conditions' => $condicion,'recursive' => -1));
		        /*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = end($logs['log']);

			    echo $lastLog['query'];*/
		       Cache::write('get_reservas_check', $result, 'long');
	       }
		}



          return $result;
    }

	public function dataTable2(){

            $rows = array();
		$this->loadModel('ConfiguracionReserva');
	    $confReserva = $this->ConfiguracionReserva->find('first',array('conditions' => array('id =' =>'hs_reservas')));

	    $hs_reservas = 0;
	    if ($confReserva) {
	    	$hs_reservas = $confReserva['ConfiguracionReserva']['valor'];
	    }

	    $retiro = $_SESSION['retiro'];
	    $devolucion = $_SESSION['devolucion'];


		$HoraRetiro=$_SESSION['HoraRetiro'];
		$HoraDevolucion=$_SESSION['HoraDevolucion'];

		list($dia1,$mes1,$ano1) = explode("/",$retiro);
		list($dia2,$mes2,$ano2) = explode("/",$devolucion);
		$desde = $ano1.'-'.$mes1.'-'.$dia1;
		$hasta = $ano2.'-'.$mes2.'-'.$dia2;

		$desdeH = $desde.' '.$HoraRetiro;
		$horaResta = '-'.$hs_reservas.' hour';
		$nuevafecha = strtotime ( $horaResta , strtotime ( $desdeH ) ) ;
		$desdeH = date ( 'Y-m-d H:i:s' , $nuevafecha );

		$horaSuma = '+'.$hs_reservas.' hour';
		$hastaH = $hasta.' '.$HoraDevolucion;
		$nuevafecha = strtotime ( $horaSuma , strtotime ( $hastaH ) ) ;
		$hastaH = date ( 'Y-m-d H:i:s' , $nuevafecha );



	    if (($desdeH!='')&&($hastaH!='')) {
			$unidadesOcupadas=array();

	        $reservas = $this->get_reservas_check($desdeH, $hastaH);

	        foreach($reservas as $reserva){


	        	$unidadesOcupadas[]=$reserva['Reserva']['unidad_id'];
	        }

	        $this->loadModel('Unidad');
			$unidads = $this->Unidad->find('all',array('conditions' => array('estado =' =>1,'excluir =' =>0,
								'NOT' => array(
									'Unidad.id' => $unidadesOcupadas)
								)));
			foreach($unidads as $unidad){
				/*$date_parts = explode("/",$desde);
				$diaDesde = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
				$date_parts = explode("/",$hasta);
				$diaHasta = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];*/
	        		$date_parts = explode("/",$unidad['Unidad']['habilitacion']);

	        		$habilitacion = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
	        		$date_parts = explode("/",$unidad['Unidad']['baja']);
	        		$baja = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];

	        		if (($baja>=$hasta)&&($habilitacion<=$desde)) {
	        			$row_data = array(
			                $unidad['Unidad']['id'],
			                $unidad['Categoria']['categoria'],
			                $unidad['Unidad']['marca'],
			                $unidad['Unidad']['modelo'],
			                $unidad['Unidad']['patente']
			            );
			            $rows[] = $row_data;
	        		}
			}

	    }

        $output = array(
        	"sEcho" => intval($_GET['sEcho']),
        	"iTotalRecords" => count($rows),
	        "iTotalDisplayRecords" => count($rows),
	        "aaData" => array()
	    );

        $output['aaData'] = $rows;
        $this->set('aoData',$output);
        //print_r($output);
        $this->set('_serialize', aoData);
    }

    public function cancelar(){
        $this->layout = 'ajax';

        $reserva = $this->Reserva->read(null,$this->request->data['reserva_id']);
        $pagado = 0;
        $descontado = 0;
        if(count($reserva['ReservaCobro'])>0){
            foreach($reserva['ReservaCobro'] as $cobro){
                if($cobro['tipo'] == 'DESCUENTO'){
                    $descontado = $descontado + $cobro['monto_cobrado'];
                }else{
                    $pagado = $pagado + $cobro['monto_cobrado'];
                }
            }
        }
        if($pagado > 0 and count($reserva['ReservaDevolucion']) > 0){
            $this->Reserva->set('estado',2);
            $this->Reserva->save();
            $resultado = 'OK';
            $mensaje = '';
        }elseif($pagado == 0){
            $this->Reserva->set('estado',2);
            $this->Reserva->save();
            $resultado = 'OK';
            $mensaje = '';
        }elseif($pagado > 0 and count($reserva['ReservaDevolucion']) == 0){
            $resultado = 'ERROR';
            $mensaje = 'No se puede cancelar sin realizar alguna devolucion, consulte con el administrador';
        }

        $this->set('resultado',$resultado);
        $this->set('mensaje',$mensaje);

        $this->set('_serialize', array(
            'resultado',
            'mensaje'
        ));
    }

    public function finalizar(){
        $this->layout = 'ajax';

        $reserva = $this->Reserva->read(null,$this->request->data['reserva_id']);

        $fiscal = 0;
        if(count($reserva['ReservaCobro'])>0){
            foreach($reserva['ReservaCobro'] as $cobro){
                if($cobro['tipo'] == 'TARJETA' or $cobro['tipo'] == 'TRANSFERENCIA'){
                    $fiscal += $cobro['monto_cobrado'];
                }
            }
        }

        $facturado = 0;
        if(count($reserva['ReservaFactura']) > 0){
            foreach($reseva['ReservaFactura'] as $factura){
                $facturado += $factura['monto'];
            }
        }

        if($facturado >= $fiscal){
            $resultado = 'OK';
            $mensaje = '';
        }else{
            $resultado = 'ERROR';
            $mensaje = 'El monto de la/las facturas realizada es incorrecto, por favor consulte al administrador';
        }
        $this->Reserva->set('estado',1);
        $this->Reserva->save();

        $this->set('resultado',$resultado);
        $this->set('mensaje',$mensaje);

        $this->set('_serialize', array(
            'resultado',
            'mensaje'
        ));
    }

    public function crear($grilla=null, $unidad_id=null, $retiro=null, $devolucion=null, $horaRetiro=null, $horaDevolucion=null){
        $this->layout = 'form';
		$this->set('grilla',$grilla);
        //ultimo numero de reserva
        $ultima_reserva = $this->Reserva->find('first',array('order' => array('Reserva.id' => 'desc')));
        $ultimo_nro = $ultima_reserva['Reserva']['numero'] + 1;
        $this->set('ultimo_nro',$ultimo_nro);



        if ($retiro) {
        	$date = new DateTime($retiro);
        	$retiro = $date->format('d/m/Y');
        }
        else $retiro = date ("d/m/Y");

        $this->set('retiro',$retiro);

         if ($devolucion) {
        	$date = new DateTime($devolucion);
        	$devolucion = $date->format('d/m/Y');
        }
        else $devolucion = date ("d/m/Y");
        $this->set('devolucion',$devolucion);

        //echo 'horas: '.$horaRetio.' '.$horaDevolucion;
    	if ($horaRetiro) {

        	$this->set('horaRetiro',str_replace('-', ':', $horaRetiro));
        }

    	if ($horaDevolucion) {
        	$this->set('horaDevolucion',str_replace('-', ':', $horaDevolucion));
        }

        //lista de empleados de reservas, tengo que ir a buscar por sector de trabajo
        $this->loadModel('EmpleadoTrabajo');
        //$sectores = $this->EmpleadoTrabajo->find('all',array('order' => array('EmpleadoTrabajo.id ASC'),'conditions' => array('EmpleadoTrabajo.sector_1_id' => 1, 'Empleado.estado' => 1)));

        $empleadosTrabajo = $this->EmpleadoTrabajo->find('all',array('fields'=>array('max(EmpleadoTrabajo.id) as id'), 'group' => array('EmpleadoTrabajo.empleado_id'), 'conditions' => array( 'Empleado.estado ' => 1 )));

        foreach($empleadosTrabajo as $empleadoTrabajo){

        	$this->EmpleadoTrabajo->id = $empleadoTrabajo[0]['id'];
        	$sector = $this->EmpleadoTrabajo->read();

        	if ($sector['EmpleadoTrabajo']['sector_1_id']==1) {
        		$empleados[$sector['Empleado']['id']] = $sector['Empleado']['nombre']." ".$sector['Empleado']['apellido'];
        	}

        }
        $this->set('empleados',$empleados);

        $this->loadModel('Canal');

        //lista de lugares
        $this->set('canals', $this->Canal->find('list',array('order' => array('Canal.canal ASC'))));

         $sexos = array ('1'=>'Masculino','2'=>'Femenino');


        $this->set('sexos',$sexos);

        $this->loadModel('Categoria');

        //lista de lugares
        $this->set('categorias', $this->Categoria->find('list',array('order' => array('Categoria.categoria ASC'))));

        if ($unidad_id) {
        	$this->loadModel('Unidad');
        	$unidad = $this->Unidad->find('first',array('conditions'=>array('Unidad.id'=>$unidad_id)));

        	$this->set('defaultCategoria',$unidad['Categoria']['id']);
        	//lista de unidades
        	$this->set('unidads', $this->Reserva->Unidad->find('list',array('order' => array('Unidad.marca, Unidad.modelo ASC'),'conditions' => array('Unidad.estado' => 1, 'Unidad.categoria_id' => $unidad['Categoria']['id']))));
        	$this->set('defaultUnidad',$unidad_id);
        }


        $this->loadModel('Lugar');

        //lista de lugares
        $this->set('lugars', $this->Lugar->find('list',array('order' => array('Lugar.lugar ASC'))));

        //iva
        //$this->set('iva_ops', array('Responsable Inscripto' => 'Responsable Inscripto', 'Excento' => 'Excento', 'Consumidor Final' => 'Consumidor Final', 'Monotributo' => 'Monotributo'));
        $this->set('iva_ops', array('Responsable Inscripto' => 'Responsable Inscripto', 'Exento' => 'Exento', 'Monotributo' => 'Monotributo'));

        $this->set('tipoDocumento_ops', array('DNI' => 'DNI', 'Pasaporte' => 'Pasaporte'));
        $this->set('tipoTelefono_ops', array('Fijo' => 'Fijo', 'Celular' => 'Celular'));
        $this->set('tipoPersona_ops', array('Fisica' => 'Fisica', 'Juridica' => 'Juridica'));

        //lista de extra rubros
        $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list', array('conditions' => array('extra_variables' => 0))));
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));

    }

    public function editar($id = null,$grilla=null){
        $this->layout = 'form';
		$this->set('grilla',$grilla);
        $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list', array('conditions' => array('extra_variables' => 0))));
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));

        $extras = $this->Reserva->ReservaExtra->find('all',array('conditions' => array('reserva_id' => $id, 'adelantada' => 1, 'extra_id !=' => 0),'recursive' => 2));
        $this->set('extras',$extras);

        $reservaConductors = $this->Reserva->ReservaConductor->find('all',array('conditions' => array('reserva_id' => $id),'recursive' => 2));
        $this->set('reservaConductors',$reservaConductors);

       //$this->set('hora',date ("h:i"));




        //lista de empleados de reservas, tengo que ir a buscar por sector de trabajo
        $this->loadModel('EmpleadoTrabajo');
    	//$sectores = $this->EmpleadoTrabajo->find('all',array('order' => array('EmpleadoTrabajo.id ASC'),'conditions' => array('EmpleadoTrabajo.sector_1_id' => 1, 'Empleado.estado' => 1)));

        $empleadosTrabajo = $this->EmpleadoTrabajo->find('all',array('fields'=>array('max(EmpleadoTrabajo.id) as id'), 'group' => array('EmpleadoTrabajo.empleado_id'), 'conditions' => array( 'Empleado.estado ' => 1 )));

        foreach($empleadosTrabajo as $empleadoTrabajo){

        	$this->EmpleadoTrabajo->id = $empleadoTrabajo[0]['id'];
        	$sector = $this->EmpleadoTrabajo->read();

        	if ($sector['EmpleadoTrabajo']['sector_1_id']==1) {
        		$empleados[$sector['Empleado']['id']] = $sector['Empleado']['nombre']." ".$sector['Empleado']['apellido'];
        	}

        }
        $this->set('empleados',$empleados);

        $this->loadModel('Canal');

        //lista de lugares
        $this->set('canals', $this->Canal->find('list',array('order' => array('Canal.canal ASC'))));

        $this->set('subcanals', $this->Reserva->Subcanal->find('list',array('order' => array('Subcanal.subcanal ASC'))));





         $this->loadModel('Categoria');

        //lista de lugares
        $this->set('categorias', $this->Categoria->find('list',array('order' => array('Categoria.categoria ASC'))));

        //lista de unidades
        $this->set('unidads', $this->Reserva->Unidad->find('list',array('order' => array('Unidad.marca, Unidad.modelo ASC'),'conditions' => array('Unidad.estado' => 1))));

        $this->loadModel('Lugar');

        //lista de lugares
        $this->set('lugars', $this->Lugar->find('list',array('order' => array('Lugar.lugar ASC'))));


        //iva
        $this->set('iva_ops', array('Responsable Inscripto' => 'Responsable Inscripto', 'Exento' => 'Exento', 'Monotributo' => 'Monotributo'));

        $this->Reserva->id = $id;
        $this->request->data = $this->Reserva->read();
        $reserva = $this->request->data;
        $this->set('hora_retiro',substr($reserva['Reserva']['hora_retiro'], 0, -3));
        $this->set('hora_devolucion',substr($reserva['Reserva']['hora_devolucion'], 0, -3));
        $adelantadas = 0;
        $no_adelantadas = 0;
            $pagado = 0;
            $fiscal = 0;
            $descontado = 0;
            if(count($reserva['ReservaCobro'])>0){
                foreach($reserva['ReservaCobro'] as $cobro){
                    if($cobro['tipo'] == 'DESCUENTO'){
                        $descontado += $cobro['monto_neto'];
                    }else{
                        if($cobro['tipo'] == 'TARJETA' or $cobro['tipo'] == 'TRANSFERENCIA'){
                            $fiscal += $cobro['monto_cobrado'];
                        }
                        $pagado += $cobro['monto_neto'];
                    }
                }
            }
            //if(count($reserva['ReservaExtra']>0)){
                foreach($reserva['ReservaExtra'] as $extra){
                    if($extra['adelantada'] == 1){
                        $adelantadas = $adelantadas + $extra['cantidad'] * $extra['precio'];
                    }else{
                        $no_adelantadas = $no_adelantadas + $extra['cantidad'] * $extra['precio'];
                    }
                }
            //}

            $devoluciones = 0;
            if(count($reserva['ReservaDevolucion']) > 0){
                foreach($reserva['ReservaDevolucion'] as $devolucion){
                    $devoluciones += $devolucion['monto'];
                }
            }

            $facturado = 0;
            if(count($reserva['ReservaFactura']) > 0){
                foreach($reserva['ReservaFactura'] as $factura){
                    $facturado += $factura['monto'];
                }
            }
            $pendiente = round(round($reserva['Reserva']['total'],2) + round($no_adelantadas,2) - round($descontado,2) - round($pagado,2) + round($devoluciones,2),2);
            $pendiente = ($pendiente==-0)?0:$pendiente;

        $sexos = array ('1'=>'Masculino','2'=>'Femenino');

       	$this->set('defaultSexo',$reserva['Cliente']['sexo']);
        $this->set('sexos',$sexos);

        //tipo documento

        $this->set('tipoDocumento_ops', array('DNI' => 'DNI', 'Pasaporte' => 'Pasaporte'));
        $this->set('tipoTelefono_ops', array('Fijo' => 'Fijo', 'Celular' => 'Celular'));
        $this->set('tipoPersona_ops', array('Fisica' => 'Fisica', 'Juridica' => 'Juridica'));

        $this->set('pendiente', $pendiente);
        $this->set('reserva', $this->Reserva->read());
    }

    public function modificarUnidad(){

	    	$reserva = $this->Reserva->read(null,$this->request->data['reserva_id']);



	    	//

	    	//print_r($reserva);

    		$vencimiento = $reserva['Cliente']['vencimiento'];
    		if ($vencimiento) {
	        	$date_parts = explode("/",$vencimiento);
	        	$vencimiento =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0];
	        }

        	/*$retiro = $reserva['Reserva']['retiro'];
	        $devolucion = $reserva['Reserva']['devolucion'];


        	if ($retiro) {
	        	$date_parts = explode("/",$retiro);
	        	$hora_retiro = str_replace(' ', '',$reserva['hora_retiro']);
	        	$hora_retiro = date('H:i:s', strtotime($hora_retiro) - 3600);
	        	$retiro =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_retiro;

	        }
			if ($devolucion) {
	        	$date_parts = explode("/",$devolucion);
	        	$hora_devolucion = str_replace(' ', '',$reserva['hora_devolucion']);
	        	$hora_devolucion = date('H:i:s', strtotime($hora_devolucion) + 3600);
	        	$devolucion =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_devolucion;
	        }*/
	        $hora_retiro = str_replace(' ', '',$reserva['Reserva']['hora_retiro']);

	       	$hora_retiro = date('H:i:s', strtotime($hora_retiro) - 3600);

	        $retiro = $this->request->data['retiro'].' '.$hora_retiro;

	        $hora_devolucion = str_replace(' ', '',$reserva['Reserva']['hora_devolucion']);
	        $hora_devolucion = date('H:i:s', strtotime($hora_devolucion) + 3600);
	        $devolucion = $this->request->data['devolucion'].' '.$hora_devolucion;

	        //echo $vencimiento.' - '.$retiro.' - '.$devolucion.' - '.$reserva['Reserva']['unidad_id'];
	        if ($reserva['Reserva']['estado']!=3) {
		        if ($vencimiento<$devolucion) {
		        	$errores .='. Su licencia esta vencida para la fecha de la reserva';
		        }
	        }


	        $result = $this->Reserva->find('first', array(
			  'conditions' => array(
			    'Reserva.unidad_id' => $this->request->data['unidad_id'],
	        	'Reserva.id <>' => (isset($reserva['Reserva']['id']))?$reserva['Reserva']['id']:'',
	        	'AND' => array('or' => array(
	        		'Reserva.estado <> ' => 2,
	        		'Reserva.estado ' => null,
	        	)),
			    'or' => array(
	        	  array('CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) <'=>$retiro,
	        	  'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) >'=>$devolucion),
			      'CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) between ? and ?' => array($retiro, $devolucion),
			      'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) between ? and ?' => array($retiro, $devolucion),
			    )
			  )
			));

			/*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    $lastLog = end($logs['log']);

		    echo $lastLog['query'];*/
        	//print_r($result);


	        if ($result) {
	        	$errores .='. La unidad seleccionada se encuentra incluida en otra reserva para la fecha seleccionada: "Numero de reserva '.$result['Reserva']['numero'].'"';

	        }

	       //print_r($errores);
            //muestro resultado
            if(isset($errores) and $errores!=''){
                $this->set('resultado','ERROR');
                $this->set('mensaje',$errores);
                $this->set('detalle','');
            }else{

            	$this->Reserva->set('unidad_id',$this->request->data['unidad_id']);
            	$date = new DateTime($this->request->data['retiro']);
        		$retiro = $date->format('d/m/Y');
            	$this->Reserva->set('retiro',$retiro);
            	$date = new DateTime($this->request->data['devolucion']);
        		$devolucion = $date->format('d/m/Y');
            	$this->Reserva->set('devolucion',$devolucion);
            	//print_r($this->Reserva);
                $this->Reserva->save();





                $this->set('resultado','OK');
                $this->set('mensaje','Reserva modificada');
                $this->set('detalle','');
            }







    	$this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
    }

    public function guardarKM(){
	    if(!empty($this->request->data)) {
            //print_r($this->request->data);
            $reservaCobro = $this->request->data['ReservaCobro'];
            $this->Reserva->id = $reservaCobro['reserva_id'];
            $reserva = $this->Reserva->read();
            $reserva['Reserva']['km_ini']=$this->request->data['Reserva']['km_ini'];
            $reserva['Reserva']['km_fin']=$this->request->data['Reserva']['km_fin'];
            $this->Reserva->set($reserva);
            if(!$this->Reserva->validates()){
                foreach ($this->Reserva->validationErrors as $validationError){
                    //print_r($validationError);
                    $errores['Reserva']['km_ini'][] = $validationError[0];
                }

            }
            if ($reserva['Reserva']['km_fin']<=$reserva['Reserva']['km_ini']){
                $errores['Reserva']['km_fin'][] ='Los KM finales deben ser mayores a los de inicio de contrato';
            }
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else {
                //guardo unidad
                $this->loadModel('Unidad');
                $this->Unidad->id = $reserva['Unidad']['id'];
                $unidad = $this->Unidad->read();
                $unidad['Unidad']['km']=$this->request->data['Reserva']['km_fin'];
                $this->Unidad->set($unidad);
                $this->Unidad->save();

                //guardo reserva

                $this->Reserva->save();

                $this->set('resultado', 'OK');
                $this->set('mensaje', 'Datos guardados');
                $this->set('detalle', '');
            }

            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }

    }


    public function guardar(){

        //load modules
        $this->loadModel('Cliente');
        $this->loadModel('ReservaExtra');
        $this->loadModel('ReservaConductor');


        if(!empty($this->request->data)) {

        	//print_r($this->request->data);
            //valido cliente
            $cliente = $this->request->data['Cliente'];
            //print_r($cliente);
            $this->Cliente->set($cliente);
            if(!$this->Cliente->validates()){
                 $errores['Cliente'] = $this->Cliente->validationErrors;
            }

            if ($cliente['tipoDocumento']=='') {
                $errores['Cliente']['tipoDocumento'][] = 'Seleccione un Tipo de Documento';
            }

        	if ($cliente['dni']=='') {
            	$errores['Cliente']['dni'][] = 'Ingrese un Documento';
            }

            if ($cliente['tipoDocumento']=='DNI') {
                if (!ctype_digit($cliente['dni'])){
                    $errores['Cliente']['dni'][] = 'Ingrese solo numeros';
                }

            }
            if ($cliente['tipoDocumento']=='DNI') {
                if (strlen($cliente['dni'])!=8){
                    $errores['Cliente']['dni'][] = 'El DNI debe contener 8 digitos';
                }

            }
        	if ($cliente['sexo']=='') {
            	$errores['Cliente']['sexo'][] = 'Seleccione un sexo';
            }

            if (!preg_match ("/^[0-9]+$/", $cliente['codPaisAux'])) {


                $errores['Cliente']['codPaisAux'][] = 'Ingrese un codigo valido';
            }

            //if($cliente['codPais'] == '') {
                //$cliente['codPais'] = $cliente['codPaisAux'];
                $this->Cliente->set('codPais',$cliente['codPaisAux']);
            //}

            if ($cliente['tipoTelefono']=='') {
                $errores['Cliente']['TipoTelefono'][] = 'Seleccione un Tipo de Teléfono';
            }


            if(($cliente['telefono'] == '') AND ($cliente['celular'] == '')){
            	$errores['Cliente']['telefono'][] = 'Ingrese un telefono o celular valido';
            	$errores['Cliente']['celular'][] = 'Ingrese un telefono o celular valido';
            }

        	if ($cliente['email']=='') {
            	$errores['Cliente']['email'][] = 'Ingrese un E-mail';
            }

        	if ($cliente['email2']=='') {
            	$errores['Cliente']['email2'][] = 'Ingrese un E-mail';
            }

        	if ($cliente['email']!=$cliente['email2']) {
            	$errores['Cliente']['email2'][] = 'Los E-mails son distintos';
            }

        	if ($cliente['nro_licencia_de_conducir']=='') {
            	$errores['Cliente']['nro_licencia_de_conducir'][] = 'Ingrese un nro licencia de conducir';
            }

        	if ($cliente['vencimiento']=='') {
            	$errores['Cliente']['vencimiento'][] = 'Ingrese un Vencimiento';
            }

            if ($cliente['nacionalidad']=='') {
            	$errores['Cliente']['nacionalidadAux'] = 'Seleccione una nacionalidad';
            }

            if(($cliente['iva'] != '') AND ($cliente['tipoPersona'] == '')){
                $errores['Cliente']['tipoPersona'][] = 'Seleccione un Tipo de Persona';

            }
            if(($cliente['iva'] != '') AND ($cliente['razon_social'] == '')){
                $errores['Cliente']['razon_social'][] = 'Ingrese una Razon Social';

            }
            //echo $cliente['iva'];
            if ($cliente['iva'] == ''){


                $this->Cliente->set('razon_social','');
                $this->Cliente->set('cuit','');
                $this->Cliente->set('tipoPersona',null);
                $this->Cliente->set('iva',null);

            }
	        $vencimiento = $cliente['vencimiento'];




            //vaildo reserva
            $reserva = $this->request->data['Reserva'];
            $this->Reserva->set($reserva);
            if(!$this->Reserva->validates()){
                $errores['Reserva'] = $this->Reserva->validationErrors;
            }

        	if ($reserva['lugar_retiro_id']=='') {
            	$errores['Reserva']['lugar_retiro_id'][] = 'Debe seleccionar un lugar';
            }

        	if ($reserva['lugar_devolucion_id']=='') {
            	$errores['Reserva']['lugar_devolucion_id'][] = 'Debe seleccionar un lugar';
            }

        	if ($reserva['reservado_por']=='') {
            	$errores['Reserva']['reservado_por'][] = 'Debe seleccionar quien realizo la reserva';
            }
            //print_r($reserva);
        	if ($reserva['subcanal_id']=='') {
            	$errores['Reserva']['subcanal_id'][] = 'Debe seleccionar un subcanal';
            }

        	$retiro = $reserva['retiro'];
	        $devolucion = $reserva['devolucion'];

	        if ($vencimiento) {
	        	$date_parts = explode("/",$vencimiento);
	        	$vencimiento =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0];
	        }
        	if ($retiro) {
	        	$date_parts = explode("/",$retiro);
	        	$hora_retiro = str_replace(' ', '',$reserva['hora_retiro']);
	        	$hora_retiro = date('H:i:s', strtotime($hora_retiro) - 3600);
	        	$retiro =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_retiro;

	        }
			if ($devolucion) {
	        	$date_parts = explode("/",$devolucion);
	        	$hora_devolucion = str_replace(' ', '',$reserva['hora_devolucion']);
	        	$hora_devolucion = date('H:i:s', strtotime($hora_devolucion) + 3600);
	        	$devolucion =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_devolucion;
	        }
	        if ($vencimiento<$devolucion) {
	        	$errores['Cliente']['vencimiento'][]='Su licencia esta vencida para la fecha de la reserva';
	        }



            //echo $retiro.' - '.$devolucion;
	        $result = $this->Reserva->find('first', array(
			  'conditions' => array(
			    'Reserva.unidad_id' => $reserva['unidad_id'],
	        	'Reserva.id <>' => (isset($reserva['id']))?$reserva['id']:'',
	        	'AND' => array('or' => array(
	        		'Reserva.estado <> ' => 2,
	        		'Reserva.estado ' => null,
	        	)),
			    'or' => array(
	        	  array('CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) <'=>$retiro,
	        	  'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) >'=>$devolucion),
			      'CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) between ? and ?' => array($retiro, $devolucion),
			      'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) between ? and ?' => array($retiro, $devolucion),
			    )
			  )
			));

			/*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    $lastLog = end($logs['log']);

		    echo $lastLog['query'];*/
        	//print_r($result);


	        if ($result) {
	        	$errores['Reserva']['unidad_id'][]='La unidad seleccionada se encuentra incluida en otra reserva para la fecha seleccionada: "Numero de reserva '.$result['Reserva']['numero'].'"';

	        }

	        $total_pasajeros = $reserva['pax_adultos']+$reserva['pax_menores']+$reserva['pax_bebes'];

	        $this->loadModel('Unidad');
			$this->Unidad->id = $reserva['unidad_id'];
			$unidad = $this->Unidad->read();
			//print_r($unidad);

			$date_parts = explode("/",$unidad['Unidad']['habilitacion']);

	        $habilitacion = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
        		$date_parts = explode("/",$unidad['Unidad']['baja']);
        		$baja = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];

        		if (($baja<$devolucion)) {
        			$errores['Reserva']['unidad_id'][]='La unidad seleccionada tiene fecha de baja '.$unidad['Unidad']['baja'];
        		}

        		if (($habilitacion>$retiro)) {
        			$errores['Reserva']['unidad_id'][]='La unidad seleccionada tiene fecha de habilitacion '.$unidad['Unidad']['habilitacion'];
        		}




			if (intval($total_pasajeros)>intval($unidad['Unidad']['capacidad'])) {
				$errores['Reserva']['unidad_id'][]='La capacidad homologada de la unidad seleccionada es inferior a la cantidad de pasajeros';
			}
			$total=$reserva['total_estadia'];
        	if(array_key_exists('ReservaExtraId',$this->request->data)){
            	$reservaextras = $this->request->data['ReservaExtraId'];
                if($reservaextras and count($reservaextras)>0){

                	$i=0;
                    foreach($reservaextras as $extra){
                        $total += $this->request->data['ReservaExtraPrecio'][$i]*$this->request->data['ReservaExtraCantidad'][$i];

                        $i++;
                     }
                  }
               }
            //echo $total .' '. $reserva['total'];
	        if ($total != $reserva['total']) {
	        	$errores['Reserva']['total_estadia']='No coinciden los montos';
	        }
            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
                //print_r($cliente);
                //guardo cliente
                $this->Cliente->save();
                //print_r($this->Cliente);
                //guardo reserva
                $this->Reserva->set('cliente_id',$this->Cliente->id);
                $this->Reserva->set('estado','0');
                $this->Reserva->save();

                //guardo reserva extras
                $this->ReservaExtra->deleteAll(array('reserva_id' => $this->Reserva->id, 'adelantada' => 1), false);
                if(array_key_exists('ReservaExtraId',$this->request->data)){
                    $reservaextras = $this->request->data['ReservaExtraId'];
                    if($reservaextras and count($reservaextras)>0){

                        $i=0;
                        foreach($reservaextras as $extra){
                            $this->ReservaExtra->create();
                            $this->ReservaExtra->set('extra_id',$extra);
                            $this->ReservaExtra->set('cantidad',$this->request->data['ReservaExtraCantidad'][$i]);
                            $this->ReservaExtra->set('precio',$this->request->data['ReservaExtraPrecio'][$i]);
                            $this->ReservaExtra->set('reserva_id',$this->Reserva->id);
                            $this->ReservaExtra->set('agregada',date('Y-m-d'));
                            $this->ReservaExtra->save();
                            $i++;
                        }
                    }
                }
                //print_r($this->request->data['ReservaConductorNombreApellido']);
                $this->ReservaConductor->deleteAll(array('reserva_id' => $this->Reserva->id), false);
                if(array_key_exists('ReservaConductorNombreApellido',$this->request->data)){

                    $reservaconductors = $this->request->data['ReservaConductorNombreApellido'];

                    if($reservaconductors and count($reservaconductors)>0){

                        $i=0;
                        foreach($reservaconductors as $conductor){
                            //echo $this->request->data['ReservaConductorNombreApellido'][$i];
                            $this->ReservaConductor->create();
                            $this->ReservaConductor->set('reserva_id',$this->Reserva->id);
                            $this->ReservaConductor->set('nombre_apellido',$this->request->data['ReservaConductorNombreApellido'][$i]);
                            $this->ReservaConductor->set('dni',$this->request->data['ReservaConductorDni'][$i]);
                            $this->ReservaConductor->set('telefono',$this->request->data['ReservaConductorTelefono'][$i]);
                            $this->ReservaConductor->set('email',$this->request->data['ReservaConductorEmail'][$i]);
                            $this->ReservaConductor->set('nro_licencia_de_conducir',$this->request->data['ReservaConductorNroLicencia'][$i]);
                            $this->ReservaConductor->set('vencimiento',$this->request->data['ReservaConductorVencimiento'][$i]);
                            $this->ReservaConductor->set('lugar_emision',$this->request->data['ReservaConductorLugarEmision'][$i]);
                            $this->ReservaConductor->set('direccion',$this->request->data['ReservaConductorDireccion'][$i]);
                            $this->ReservaConductor->set('localidad',$this->request->data['ReservaConductorLocalidad'][$i]);

                            if(!$this->ReservaConductor->validates()){
                                foreach ($this->ReservaConductor->validationErrors as $validationError){
                                    //print_r($validationError);
                                    $errores['Cliente']['ad_localidad'][] = $validationError[0];
                                }

                            }

                            $date_parts = explode("/",$this->request->data['ReservaConductorVencimiento'][$i]);
                            $vencimiento =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0];

                            if ($vencimiento<$devolucion) {
                                $errores['Cliente']['ad_localidad'][]='La licencia de uno de los conductores está vencida para la fecha de la reserva';
                            }
                            if(isset($errores) and count($errores) > 0){
                                break;
                            }
                            else{
                                $this->ReservaConductor->save();
                            }


                            $i++;
                        }
                    }
                }
                 if(isset($errores) and count($errores) > 0){
                    $this->set('resultado','ERROR');
                    $this->set('mensaje','No se pudo guardar');
                    $this->set('detalle',$errores);
                }else {
                     $this->set('resultado', 'OK');
                     $this->set('mensaje', 'Datos guardados');
                     $this->set('detalle', '');
                 }
            }
            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }
    }

	public function bloquearUnidad(){

        //load modules

        //$this->loadModel('ReservaExtra');


        if(!empty($this->request->data)) {
        	$this->loadModel('Cliente');

        	//print_r($this->request->data);
            //valido cliente
            $cliente = $this->request->data['Cliente'];
            //print_r($cliente);
            $this->Cliente->set($cliente);

            if (($cliente['dni']!='')or($cliente['sexo']!='')or($cliente['telefono']!='')or($cliente['celular']!='')or($cliente['direccion']!='')or($cliente['localidad']!='')
            or($cliente['iva']!='')or($cliente['nacimiento']!='')or($cliente['profesion']!='')or($cliente['nacionaliadadAux']!='')or($cliente['domicilio_local']!='')or($cliente['email']!='')
            or($cliente['ad_nombre_apellido']!='')or($cliente['ad_dni']!='')or($cliente['ad_telefono']!='')or($cliente['ad_email']!='')or($cliente['nro_licencia_de_conducir']!='')
            or($cliente['vencimiento']!='')or($cliente['lugar_emision']!='')or($cliente['ad_direccion']!='')or($cliente['ad_localidad']!='')) {
            	$this->guardar();
            }
       		else{

	        	if ($cliente['nombre_apellido']=='') {
	            	$errores['Cliente']['nombre_apellido'] = 'Ingrese un nombre y apellido valido';
	            }



	            //vaildo reserva
	            $reserva = $this->request->data['Reserva'];
	            //print_r($reserva);
	            $this->Reserva->set($reserva);
	            /*if (($reserva['reservado_por']!='')or($reserva['subcanal_id']!='')or($reserva['lugar_retiro_id']!='')or($reserva['lugar_devolucion_id']!='')
	            or($reserva['pax_adultos']!='0')or($reserva['pax_menores']!='0')or($reserva['pax_bebes']!='0')or($reserva['discover']!='0')or($reserva['discover_plus']!='0')
	            or($reserva['discover_advance']!='0')or($reserva['total_estadia']!='0') ){*/
                if (($reserva['reservado_por']!='')
                    or($reserva['pax_adultos']!='0')or($reserva['pax_menores']!='0')or($reserva['pax_bebes']!='0')){
	            	$this->guardar();
	            }
	       		else{
       		    	if(!$this->Reserva->validates()){
		                $errores['Reserva'] = $this->Reserva->validationErrors;
		            }


		        	$retiro = $reserva['retiro'];
			        $devolucion = $reserva['devolucion'];


		        	if ($retiro) {
			        	$date_parts = explode("/",$retiro);
			        	$hora_retiro = str_replace(' ', '',$reserva['hora_retiro']);
			        	$hora_retiro = date('H:i:s', strtotime($hora_retiro) - 3600);
			        	$retiro =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_retiro;

			        }
					if ($devolucion) {
			        	$date_parts = explode("/",$devolucion);
			        	$hora_devolucion = str_replace(' ', '',$reserva['hora_devolucion']);
			        	$hora_devolucion = date('H:i:s', strtotime($hora_devolucion) + 3600);
			        	$devolucion =  $date_parts[2]."-".$date_parts[1]."-".$date_parts[0].' '.$hora_devolucion;
			        }



		            //echo $retiro.' - '.$devolucion;
			        $result = $this->Reserva->find('first', array(
					  'conditions' => array(
					    'Reserva.unidad_id' => $reserva['unidad_id'],
			        	'Reserva.id <>' => (isset($reserva['id']))?$reserva['id']:'',
			        	'AND' => array('or' => array(
			        		'Reserva.estado <> ' => 2,
			        		'Reserva.estado ' => null,
			        	)),
					    'or' => array(
			        	  array('CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) <'=>$retiro,
			        	  'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) >'=>$devolucion),
					      'CONCAT(Reserva.retiro,\' \',Reserva.hora_retiro) between ? and ?' => array($retiro, $devolucion),
					      'CONCAT(Reserva.devolucion,\' \',Reserva.hora_devolucion) between ? and ?' => array($retiro, $devolucion),
					    )
					  )
					));

					/*App::uses('ConnectionManager', 'Model');
		        	$dbo = ConnectionManager::getDatasource('default');
				    $logs = $dbo->getLog();
				    $lastLog = end($logs['log']);

				    echo $lastLog['query'];*/
		        	//print_r($result);


			        if ($result) {
			        	$errores['Reserva']['unidad_id'][]='La unidad seleccionada se encuentra incluida en otra reserva para la fecha seleccionada: "Numero de reserva '.$result['Reserva']['numero'].'"';

			        }



			        $this->loadModel('Unidad');
					$this->Unidad->id = $reserva['unidad_id'];
					$unidad = $this->Unidad->read();
					$date_parts = explode("/",$unidad['Unidad']['habilitacion']);

		        	$habilitacion = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
	        		$date_parts = explode("/",$unidad['Unidad']['baja']);
	        		$baja = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];

	        		if (($baja<$devolucion)) {
	        			$errores['Reserva']['unidad_id'][]='La unidad seleccionada tiene fecha de baja '.$unidad['Unidad']['baja'];
	        		}

	        		if (($habilitacion>$retiro)) {
	        			$errores['Reserva']['unidad_id'][]='La unidad seleccionada tiene fecha de habilitacion '.$unidad['Unidad']['habilitacion'];
	        		}
					//print_r($unidad);

		            //muestro resultado
		            if(isset($errores) and count($errores) > 0){
		                $this->set('resultado','ERROR');
		                $this->set('mensaje','No se pudo guardar');
		                $this->set('detalle',$errores);
		            }else{
		                //guardo cliente
		                $this->Cliente->save();

		                //guardo reserva
		                $this->Reserva->set('cliente_id',$this->Cliente->id);
		                $this->Reserva->set('estado','3');
		                $this->Reserva->save();
                        $this->loadModel('ReservaExtra');
                        $this->ReservaExtra->deleteAll(array('reserva_id' => $this->Reserva->id, 'adelantada' => 1), false);
                        if(array_key_exists('ReservaExtraId',$this->request->data)){
                            $reservaextras = $this->request->data['ReservaExtraId'];
                            if($reservaextras and count($reservaextras)>0){

                                $i=0;
                                foreach($reservaextras as $extra){
                                    $this->ReservaExtra->create();
                                    $this->ReservaExtra->set('extra_id',$extra);
                                    $this->ReservaExtra->set('cantidad',$this->request->data['ReservaExtraCantidad'][$i]);
                                    $this->ReservaExtra->set('precio',$this->request->data['ReservaExtraPrecio'][$i]);
                                    $this->ReservaExtra->set('reserva_id',$this->Reserva->id);
                                    $this->ReservaExtra->set('agregada',date('Y-m-d'));
                                    $this->ReservaExtra->save();
                                    $i++;
                                }
                            }
                        }

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
    }

    public function plantilla($reserva_id, $pdf=0, $output='D',$grilla=null){
        $this->layout = 'plantilla';

        $this->set('grilla',$grilla);

        $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));

        $this->set('pdf',$pdf);

        $this->loadModel('ExtraSubrubro');
        $this->set('extra_subrubros',$this->ExtraSubrubro->find('list'));

        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        $telefono ='';
        if ($reserva['Cliente']['tipoTelefono']=='Fijo'){
            $telefono = $reserva['Cliente']['codPais'].' '.$reserva['Cliente']['codArea'].' '.$reserva['Cliente']['telefono'];
        }
        $celular ='';
        if ($reserva['Cliente']['tipoTelefono']=='Celular'){
            $celular = $reserva['Cliente']['codPais'].' '.$reserva['Cliente']['codArea'].' '.$reserva['Cliente']['telefono'];
        }
        $this->set('telefono',$telefono);
        $this->set('celular',$celular);
        $this->set('reserva',$reserva);


		$this->loadModel('Categoria');
		$this->Categoria->id = $reserva['Unidad']['categoria_id'];
		$categoria = $this->Categoria->read();
		$this->set('categoria',$categoria);
		//print_r($categoria);
        $pagado = 0;
        $descontado = 0;
        if(count($reserva['ReservaCobro'])>0){
            foreach($reserva['ReservaCobro'] as $cobro){
                if($cobro['tipo'] == 'DESCUENTO'){
                    $descontado = $descontado + $cobro['monto_neto'];
                }else{
                    $pagado = $pagado + $cobro['monto_neto'];
                }
            }
        }
    $devoluciones = 0;
            if(count($reserva['ReservaDevolucion']) > 0){
                foreach($reserva['ReservaDevolucion'] as $devolucion){
                    $devoluciones += $devolucion['monto'];
                }
            }
        $this->set('pagado',round($pagado,2));
        $this->set('pendiente',round($reserva['Reserva']['total'] - $descontado - $pagado + $devoluciones,2));
        $this->set('total',round($reserva['Reserva']['total'] - $descontado + $devoluciones,2));

    //genero el pdf
        if ($pdf) {


        	$fileName = ($output=='F')?'files/reserva('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_plantilla_'.date('d_m_Y').'.pdf':'reserva('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_plantilla_'.date('d_m_Y').'.pdf';



            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($this->render());
            $mpdf->Output($fileName,$output);

        }
    }

    public function check_consumo($reserva_id, $pdf=0, $output='D',$grilla=null){
        $this->layout = 'plantilla';

        $this->set('grilla',$grilla);

        $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));

        $this->set('pdf',$pdf);

        $this->loadModel('ExtraSubrubro');
        $this->set('extra_subrubros',$this->ExtraSubrubro->find('list'));

        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        $telefono ='';
        if ($reserva['Cliente']['tipoTelefono']=='Fijo'){
            $telefono = $reserva['Cliente']['codPais'].' '.$reserva['Cliente']['codArea'].' '.$reserva['Cliente']['telefono'];
        }
        $celular ='';
        if ($reserva['Cliente']['tipoTelefono']=='Celular'){
            $celular = $reserva['Cliente']['codPais'].' '.$reserva['Cliente']['codArea'].' '.$reserva['Cliente']['telefono'];
        }
        $this->set('telefono',$telefono);
        $this->set('celular',$celular);
        $this->set('reserva',$reserva);


        $this->loadModel('Categoria');
        $this->Categoria->id = $reserva['Unidad']['categoria_id'];
        $categoria = $this->Categoria->read();
        $this->set('categoria',$categoria);
        //print_r($categoria);
        $pagado = 0;
        $descontado = 0;
        if(count($reserva['ReservaCobro'])>0){
            foreach($reserva['ReservaCobro'] as $cobro){
                if($cobro['tipo'] == 'DESCUENTO'){
                    $descontado = $descontado + $cobro['monto_neto'];
                }else{
                    $pagado = $pagado + $cobro['monto_neto'];
                }
            }
        }
        $devoluciones = 0;
        if(count($reserva['ReservaDevolucion']) > 0){
            foreach($reserva['ReservaDevolucion'] as $devolucion){
                $devoluciones += $devolucion['monto'];
            }
        }
        $this->set('pagado',round($pagado,2));
        $this->set('pendiente',round($reserva['Reserva']['total'] - $descontado - $pagado + $devoluciones,2));
        $this->set('total',round($reserva['Reserva']['total'] - $descontado + $devoluciones,2));

        //genero el pdf
        if ($pdf) {


            $fileName = ($output=='F')?'files/check('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_plantilla_'.date('d_m_Y').'.pdf':'reserva('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_plantilla_'.date('d_m_Y').'.pdf';



            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($this->render());
            $mpdf->Output($fileName,$output);

        }
    }

	public function formMailPlanilla($reserva_id){
        $this->layout = 'form';



        $this->loadModel('Reserva');
        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        $this->set('reserva',$reserva);
        //print_r($reserva);

    }




 	public function enviarPlanilla(){
 		$this->layout = 'json';

        if(!empty($this->request->data)) {

        	$errores=array();
        	$mails=$this->request->data['Reserva']['mails'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Reserva']['mails'][] = 'Error en el/los mail/s';
	            }

        	}
        	$mails=$this->request->data['Reserva']['mailsCC'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Reserva']['mailsCC'][] = 'Error en el/los mail/s';
	            }

        	}
        	$mails=$this->request->data['Reserva']['mailsCCO'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Reserva']['mailsCCO'][] = 'Error en el/los mail/s';
	            }

        	}

            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo enviar');
                $this->set('detalle',$errores);
            }else{
            	$this->loadModel('Reserva');
		        $this->Reserva->id = $this->request->data['Reserva']['reserva_id'];
		        $reserva = $this->Reserva->read();
            	$fileName = 'reserva('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_plantilla_'.date('d_m_Y').'.pdf';
            	$file ='files/'.$fileName;

            	if(is_file($file)){

				        $fp =    @fopen($file,"rb");
				        $data =  @fread($fp,filesize($file));

				        @fclose($fp);


						$attachment = chunk_split(base64_encode($data));
            	}

				$mail.=$persona;



		$textMessage = '';
		$asunto = 'IMPORTANTE DAR DE ALTA E IMPRIMIR PLANILLA '.$reserva['Cliente']['nombre_apellido'].' '.$reserva['Unidad']['marca'].' '.$reserva['Unidad']['modelo'].' '.$reserva['Reserva']['retiro'].' '.$reserva['Reserva']['hora_retiro'].' '.$reserva['Reserva']['devolucion'].' '.$reserva['Reserva']['hora_devolucion'];




		$separator = md5(uniqid(time()));
		// carriage return type (we use a PHP end of line constant)
		$eol = PHP_EOL;
		// attachment name


		// main header (multipart mandatory)
		$headers  = "From: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> ".$eol;

	    $headers .= "Return-path: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> ".$eol;
	    //$headers .= "CC: ".$this->request->data['Voucher']['mailsCC']." \r\n";
	    $headers .= "BCC: ".$this->request->data['Reserva']['mailsCCO']." \r\n";
		$headers .= "MIME-Version: 1.0".$eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol;

		// message & attachment
		$nmessage = "--".$separator."\r\n";
		$nmessage .= "Content-type:text/html; charset=utf8\r\n";
		$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$nmessage .= $textMessage."\r\n\r\n";
		$nmessage .= "--".$separator."\r\n";
		$nmessage .= "Content-Type: application/octet-stream; name=\"".$fileName."\"\r\n";
		$nmessage .= "Content-Transfer-Encoding: base64\r\n";
		$nmessage .= "Content-Disposition: attachment; filename=\"".$fileName."\"\r\n\r\n";
		$nmessage .= $attachment."\r\n\r\n";
		$nmessage .= "--".$separator."--";




		if (mail($this->request->data['Reserva']['mails'], $asunto, $nmessage, $headers, "-finfo@discoverushuaia.com.ar")){

			$enviada = $reserva['Reserva']['planilla'] + 1;
			$this->Reserva->set('planilla',$enviada);
		    $this->Reserva->save();

			$this->set('resultado','OK');
                $this->set('mensaje','Planilla enviada');
                $this->set('detalle','');
		}
		else{
		 	$this->set('resultado','ERROR');
                $this->set('mensaje','Error al enviar');
                //$this->set('detalle');
            }


          unlink($file);



            }


            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }
 	}

	public function contrato($reserva_id){
        $this->layout = 'contrato';

       /* $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));

        $this->loadModel('ExtraSubrubro');
        $this->set('extra_subrubros',$this->ExtraSubrubro->find('list'));*/

        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        /*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    print_r($logs);*/
        $this->set('reserva',$reserva);



		$this->loadModel('Categoria');
		$this->Categoria->id = $reserva['Unidad']['categoria_id'];
		$categoria = $this->Categoria->read();
		$this->set('categoria',$categoria);
		//print_r($categoria);
        $pagado = 0;
        $descontado = 0;
        if(count($reserva['ReservaCobro'])>0){
            foreach($reserva['ReservaCobro'] as $cobro){
                if($cobro['tipo'] == 'DESCUENTO'){
                    $descontado = $descontado + $cobro['monto_neto'];
                }else{
                    $pagado = $pagado + $cobro['monto_neto'];
                }
            }
        }
	$devoluciones = 0;
            if(count($reserva['ReservaDevolucion']) > 0){
                foreach($reserva['ReservaDevolucion'] as $devolucion){
                    $devoluciones += $devolucion['monto'];
                }
            }
        /*$this->set('pagado',$pagado);
        $this->set('pendiente',$reserva['Reserva']['total'] - $descontado - $pagado);*/
       $this->set('total',$reserva['Reserva']['total'] - $descontado + $devoluciones);

        //genero el pdf

        require_once '../../vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal','margin_top' => 0,'margin_left' => 0,'margin_right' => 0,'margin_bottom' => 0]);
        $mpdf->WriteHTML($this->render());
        $mpdf->Output('reserva('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_contrato_'.date('d_m_Y').'.pdf','D');


    }


    //protege el controlador solo para usuarios
    public function beforeFilter(){
        if(isset($_COOKIE['useridushuaia'])){
            $this->loadModel('Usuario');
            $this->set('usuario',$this->Usuario->findById($_COOKIE['useridushuaia']));
        }else{
            $this->redirect('/index');
        }
    }

	public function getUnidads($categoria_id){
        $this->layout = 'ajax';
        //$this->loadModel('Unidads');


        $this->set('unidads', $this->Reserva->Unidad->find('list',array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' =>array('Unidad.categoria_id =' => $categoria_id,'Unidad.estado' => 1))));

    }

	public function getSubcanals($canal_id){
        $this->layout = 'ajax';

        $this->set('subcanals', $this->Reserva->Subcanal->find('list',array('order' => array('Subcanal.subcanal ASC'), 'conditions' =>array('Subcanal.canal_id =' => $canal_id))));

    }

    public function dameHorarios($dd,$mm,$yy){


    	$desde = $yy.'-'.$mm.'-'.$dd;
    	$this->loadModel('Feriado');
 		$condicion =array('fecha' => $desde);
 		$feriados = $this->Feriado->find('first',array('conditions' => $condicion));

 		if ($feriados) {
 			if (!$feriados['Feriado']['abre']) {
 				$horarios['CERRADO']='CERRADO';
 			}
 			else{
	 			$horarios = array();
	 			$feriadoHorarios = $feriados['FeriadoHorario'];

				foreach ($feriadoHorarios as $feriadoHorario) {

					$start = $feriadoHorario['hora_inicio'];
					$end = $feriadoHorario['hora_fin'];

					$tStart = strtotime($start);
					$tEnd = strtotime($end);
					$tNow = $tStart;

					while($tNow <= $tEnd){
						$Hora=date("H:i",$tNow);
						if($Hora==$HoraDevolucion){
							$Horselected="selected";
						}
						else{
							$Horselected="";
						}


					  	$horarios[$Hora]=$Hora;
					  	$tNow = strtotime('+30 minutes',$tNow);
					}
				}
 			}
 		}
 		else{
	    	$array_dias['Sunday'] = "Domingo";
			$array_dias['Monday'] = "Lunes";
			$array_dias['Tuesday'] = "Martes";
			$array_dias['Wednesday'] = "Miercoles";
			$array_dias['Thursday'] = "Jueves";
			$array_dias['Friday'] = "Viernes";
			$array_dias['Saturday'] = "Sabado";



			$diaDesde = $array_dias[date('l', strtotime($desde))];

			$this->loadModel('SemanaDia');

			$diaHorario = $this->SemanaDia->find('first',array('joins' => array(
	        array(
	            'table' => 'dia_horarios',
	            'alias' => 'DiaHorario',
	            'type' => 'LEFT',
	            'conditions' =>array(
	                'SemanaDia.id = DiaHorario.semana_dia_id'
	            )
	        )),'fields' => array('DiaHorario.hora_inicio',
	'DiaHorario.hora_fin'),'conditions'=>array('SemanaDia.dia = '=>$diaDesde),'recursive' => -1));
			/*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = end($logs['log']);

			    echo $lastLog['query'];*/
	        //print_r($diaHorario);
			$horarios = array();
			if ($diaHorario) {
				$start = $diaHorario['DiaHorario']['hora_inicio'];
				$end = $diaHorario['DiaHorario']['hora_fin'];

				$tStart = strtotime($start);
				$tEnd = strtotime($end);
				$tNow = $tStart;

				while($tNow <= $tEnd){
					$Hora=date("H:i",$tNow);
					if($Hora==$HoraDevolucion){
						$Horselected="selected";
					}
					else{
						$Horselected="";
					}


				  	$horarios[$Hora]=$Hora;
				  	$tNow = strtotime('+30 minutes',$tNow);
				}
			}
 		}
		return $horarios;
    }

	public function dameTodasLasHoras(){

			$start = '00:00';
			$end = '23:30';

			$tStart = strtotime($start);
			$tEnd = strtotime($end);
			$tNow = $tStart;

			while($tNow <= $tEnd){
				$Hora=date("H:i",$tNow);
				if($Hora==$HoraDevolucion){
					$Horselected="selected";
				}
				else{
					$Horselected="";
				}


			  	$horarios[$Hora]=$Hora;
			  	$tNow = strtotime('+30 minutes',$tNow);
			}

		return $horarios;
    }


	public function getHorarios($dd,$mm,$yy){
        $this->layout = 'ajax';

        $horarios = $this->dameHorarios($dd, $mm, $yy);
        $this->set('horarios', $horarios);

    }

	public function guardar_responsable(){
        $this->layout = 'ajax';


        $reserva = $this->Reserva->read(null,$this->request->data['id_reserva']);

		if($this->request->data['tipo']=='Entrega'){
            $this->Reserva->set('responsableRetiro',$this->request->data['id_responsable']);

        }else{
        	$this->Reserva->set('responsableDevolucion',$this->request->data['id_responsable']);
        }


		if(!$this->Reserva->validates()){
              $errores['Reserva'] = $this->Reserva->validationErrors;
         }
	 	if(isset($errores) and $errores!=''){
                $this->set('resultado','ERROR');
                $this->set('mensaje',$errores);
                $this->set('detalle','');
            }else{
            	$this->Reserva->save();
                $this->set('resultado','OK');
                $this->set('mensaje','Reserva modificada');
                $this->set('detalle','');
            }

        $this->set('_serialize', array(
            'resultado',
            'mensaje'
        ));

    }



}
?>
