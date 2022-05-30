<?php
session_start();
class InformesController extends AppController {



	public $scaffold;
    public $components = array('Mpdf','ExportXls');





	public function inicio_fin_semana($fecha){

	$diaInicio="Sunday";
	$diaFin="Saturday";

	$strFecha = strtotime($fecha);

	$fechaInicio = date('Y-m-d',strtotime('last '.$diaInicio,$strFecha));
	$fechaFin = date('Y-m-d',strtotime('next '.$diaFin,$strFecha));

	if(date("l",$strFecha)==$diaInicio){
	$fechaInicio= date("Y-m-d",$strFecha);
	}
	if(date("l",$strFecha)==$diaFin){
	$fechaFin= date("Y-m-d",$strFecha);
	}

	return Array("fechaInicio"=>$fechaInicio,"fechaFin"=>$fechaFin);

	}

	function array_sort_by(&$arrIni, $col, $order = SORT_ASC)
	{
	    $arrAux = array();
	    foreach ($arrIni as $key=> $row)
	    {
	        $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
	        $arrAux[$key] = strtolower($arrAux[$key]);
	    }
	    array_multisort($arrAux, $order, $arrIni);
	}

    function index_ventas_economico(){
        $this->layout = 'informe';
        $this->loadModel('Categoria');
		$categorias = $this->Categoria->find('all',array('conditions' => array('activa =' =>1), 'order' => 'categoria ASC'));


        $this->set(array(

        	'categorias' => $categorias

        ));
        $this->setLogUsuario('Informe economico - ventas');
    }

	function index_ventas_extras(){
        $this->layout = 'informe';
        $this->setLogUsuario('Informe de extras');
    }

	function index_ventas_extras_economico(){
       $this->layout = 'informeDefault';
        //$_SESSION['paginaOperaciones']=1;

    }
	function index_ventas_extras_listado(){
       $this->layout = 'informeDefault';
       $this->loadModel('ExtraRubro');
        $this->set('extra_rubros',$this->ExtraRubro->find('list'));
         $this->loadModel('Usuario');
		 $user_id = $_SESSION['useridushuaia'];
        $user = $this->Usuario->find('first',array('conditions'=>array('Usuario.id'=>$_SESSION['useridushuaia'])));

		$permisoAdelantada=1;
		$permisoNoAdelantada=1;
		//print_r($user);
        if ($user['Usuario']['admin'] != '1'){
	        $this->loadModel('UsuarioPermiso');
	        $permisos = $this->UsuarioPermiso->findAllByUsuarioId($user_id);
	        $permisoAdelantada=0;
			$permisoNoAdelantada=0;
	    	foreach($permisos as $permiso){
               if ($permiso['UsuarioPermiso']['permiso_id']==123) {
               		$permisoAdelantada=1;
               		continue;
               }
    			if ($permiso['UsuarioPermiso']['permiso_id']==124) {
               		$permisoNoAdelantada=1;
               		continue;
               }

	        }
        }

      $this->set('permisoAdelantada',$permisoAdelantada);
       $this->set('permisoNoAdelantada',$permisoNoAdelantada);
        //$_SESSION['paginaOperaciones']=1;

    }


    function index_base_datos(){
        $this->layout = 'informe';
        $this->setLogUsuario('Base de datos');
    }

 	function index_ventas_ocupacion(){
        $this->layout = 'informe';
        $this->setLogUsuario('Informe de ocupacion');

    }

	function index_ventas_ocupacion_x_categoria(){
        $this->layout = 'informe';
         $this->setLogUsuario('Informe de ocupacion por categoria');
    }

    function index_ventas_financiero(){
        $this->layout = 'informe';
        $this->setLogUsuario('Informe financiero - ventas');
    }

	function index_ventas_operaciones(){
        $this->layout = 'informe';
        if (!isset($_SESSION['paginaOperaciones'])) {

        	$_SESSION['paginaOperaciones']=1;
        }

    }

	function index_ventas_diario(){
        $this->layout = 'informeDefault';
        $_SESSION['paginaOperaciones']=1;
         $this->setLogUsuario('Listado diario de operaciones');
    }

	function index_ventas_semanal(){
        $this->layout = 'informeDefault';
        if ((isset($_SESSION['primerDia']))&&(isset($_SESSION['ultimoDia']))) {
        	$primerDia=$_SESSION['primerDia'];
        	$ultimoDia=$_SESSION['ultimoDia'];
        }
        else{
        	$arraySemana=$this->inicio_fin_semana(date('Y-m-d'));
        	$primerDia=$arraySemana['fechaInicio'];
        	$ultimoDia=$arraySemana['fechaFin'];
        	$_SESSION['primerDia']=$primerDia;
        	$_SESSION['ultimoDia']=$ultimoDia;
        }

        $_SESSION['paginaOperaciones']=2;
        $this->set('primerDia',date('d/m/Y',strtotime($primerDia)));
        $this->set('ultimoDia',date('d/m/Y',strtotime($ultimoDia)));
         $this->setLogUsuario('Listado semanal de operaciones');
    }

	function index_ventas_grilla(){
        $this->layout = 'informe';
         $this->setLogUsuario('Grilla de reservas');


    }

	public function getUnidadesGrilla($desde){
        $this->layout = 'ajax';
        $this->loadModel('Unidad');

        $condicion=array('Unidad.habilitacion <=' =>$desde,'Unidad.baja >=' =>$desde, 'Unidad.estado = 1');
		$unidades = $this->Unidad->find('all',array('order' => array('Unidad.orden ASC'), 'conditions' => $condicion));
		/*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = end($logs['log']);

			    echo $lastLog['query'];*/
		$this->set(array(
            'unidads' => $unidades
        ));

        $this->set('_serialize', array(
            'unidads'
        ));

    }


	function index_ventas_grilla2(){
        $this->layout = 'informeGrilla';
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");



         $this->setLogUsuario('Grilla de reservas');

		$this->loadModel('GrillaFeriado');

		$feriados = $this->GrillaFeriado->find('all', array('order' => 'desde ASC'));



		$this->set(array(
			'feriados' => $feriados
		));


    }



	function ventas_grilla2($desde){
		$this->layout = false;
    	$this->autoRender = false;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");

        $hasta= date('Y-m-d', strtotime($desde. ' + 32 days'));


        /*$desde='2018-07-18';
        $hasta='2018-08-19';*/

        $this->loadModel('Reserva');

        //$reservas = $this->Reserva->find('all',array( 'recursive' => 1));

        $reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array(array('retiro >=' => $desde,'retiro <=' => $hasta),array('devolucion >=' => $desde,'devolucion <=' => $hasta),array('retiro <' => $desde,'devolucion >' => $hasta))), 'recursive' => 1));
        /*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    $lastLog = $logs['log'][0];
		    echo $lastLog['query'];*/
        //print_r($reservas);
        $reservasMostrar = array();
        if(count($reservas) > 0){

            foreach($reservas as $reserva){

                //verifico que la reserva no este cancelada
                if($reserva['Reserva']['estado'] != 2){
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

	                foreach($reserva['ReservaExtra'] as $extra){
	                    if($extra['adelantada'] == 1){
	                        $adelantadas = $adelantadas + $extra['cantidad'] * $extra['precio'];
	                    }else{
	                        $no_adelantadas = $no_adelantadas + $extra['cantidad'] * $extra['precio'];
	                    }
	                }



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
		            $total = round(round($reserva['Reserva']['total'],2) + round($no_adelantadas,2) - round($descontado,2),2);
		            $pago = round(round($pagado,2) + round($devoluciones,2),2);
		            $pendiente = round(round($reserva['Reserva']['total'],2) + round($no_adelantadas,2) - round($descontado,2) - round($pagado,2) + round($devoluciones,2),2);
		            $faltaPagar = $pendiente;
		            $pendiente = ($pendiente==-0)?0:$pendiente;


		            switch($reserva['Reserva']['estado']){
		                case 0:
		                    if($pagado == 0){
		                        $color='#ff0033';

				            }
				            elseif($pendiente > 0){
		                        $color='#f44611';
	                    	}
	                    	else{
		                        $color='#579d1c';
		                    }
		                    break;
		                case 1:
		                    $color='#579d1c';
		                    break;
		                case 3:
		                    $color='#f1fa52';
		                    break;

		            }

					$date_parts = explode("/",$reserva['Reserva']['retiro']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$dd=$date_parts[0];
        			$dateRetiroStr = $yy.'-'.$mm.'-'.$dd;

					$date_parts = explode("/",$reserva['Reserva']['devolucion']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$dd=$date_parts[0];
        			$dateDevolucionStr = $yy.'-'.$mm.'-'.$dd;
					$hr = explode(":", $reserva['Reserva']['hora_retiro']);
					$hd = explode(":", $reserva['Reserva']['hora_devolucion']);

                    /*$reservasMostrar[]=array('unidad_id'=>$reserva['Unidad']['id'],'reserva_id'=>$reserva['Reserva']['id'],'cliente_id'=>$reserva['Cliente']['id'],'numero'=>$reserva['Reserva']['numero'],'marca'=>$reserva['Unidad']['marca'],'modelo'=>$reserva['Unidad']['modelo'],'patente'=>$reserva['Unidad']['patente'],'nombre'=>$reserva['Cliente']['nombre_apellido'],'lugar_retiro'=>$reserva['Lugar_Retiro']['lugar'],'lugar_devolucion'=>$reserva['Lugar_Devolucion']['lugar'],'retiro'=>$dateRetiroStr.' '.$reserva['Reserva']['hora_retiro'],'devolucion'=>$dateDevolucionStr.' '.$reserva['Reserva']['hora_devolucion'],'fecha_retiro'=>$reserva['Reserva']['retiro'],'fecha_devolucion'=>$reserva['Reserva']['devolucion'],'color'=>$color,'hora_retiro'=>$hr[0].':'.$hr[1],'hora_devolucion'=>$hd[0].':'.$hd[1],'comentarios'=>str_replace("\r\n", "<br />",htmlentities($reserva['Reserva']['comentarios'])),'total'=>number_format($total,2),'pagado'=>number_format($pago,2),'pendiente'=>number_format($faltaPagar,2));*/





                    $reservasMostrar[] = array('start_date'=>$dateRetiroStr.' '.$reserva['Reserva']['hora_retiro'], 'end_date'=>$dateDevolucionStr.' '.$reserva['Reserva']['hora_devolucion'], 'text' =>'<b>Titular:</b>'.$reserva['Cliente']['nombre_apellido'].'<br /> <b>Reserva Nro.:</b> '.$reserva['Reserva']['numero'].'<br /><b>Retiro:</b> '.$reserva['Reserva']['retiro'].' '.$hr[0].':'.$hr[1].'/'.$reserva['Lugar_Retiro']['lugar'].'<br /><b>Devolucion:</b>'.$reserva['Reserva']['devolucion'].' '.$hd[0].':'.$hd[1].'/'.$reserva['Lugar_Devolucion']['lugar'].'<br />  <b>Total:</b>  '.number_format($total,2).'<b> Cobrado:</b>  '.number_format($pago,2).'<b> A Cobrar:</b>  '.number_format($faltaPagar,2).'<br /><b> Comentarios:</b>'.utf8_encode(str_replace("\r\n", "<br />",$reserva['Reserva']['comentarios'])), 'idReserva'=>$reserva['Reserva']['id'], 'section_id'=>$reserva['Unidad']['id'], 'color'=>$color, 'idCliente'=>$reserva['Cliente']['id'], 'readonly'=>false);
                }

	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0



		//print_r($reservasMostrar);



        /*$this->set(array(
           'unidads' => $unidads,
        	'reservas' => $reservasMostrar
        ));*/
         return json_encode(array(
		        'data' => $reservasMostrar
		    ));
    }

	function index_ventas_ocupacion_porcentaje(){
        $this->layout = 'informe';
        $this->setLogUsuario('Informe de Ocupacion/Dias x Mes');
    }

	function index_iva_compras(){
        $this->layout = 'informe';
        $this->setLogUsuario('Libro IVA Compras');
    }

	function index_iva_ventas(){
        $this->layout = 'informe';
        $this->setLogUsuario('Libro IVA Ventas');

        $this->loadModel('PuntoVenta');


        $puntos_venta = $this->PuntoVenta->find('all',array('conditions' => array('ivaVentas =' =>1), 'order' => 'numero ASC'));


        $this->set(array(

        	'puntos_venta' => $puntos_venta

        ));



    }

     function ventas_diario($fecha,$pdf=0){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'informeDefault';

        $this->loadModel('Reserva');



        $reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array('retiro =' => $fecha,'devolucion =' => $fecha))));


        	/*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    $lastLog = $logs['log'][0];
		    echo $lastLog['query'];*/
        $date = new DateTime($fecha);
       $reservasMostrar = array();
        if(count($reservas) > 0){

            foreach($reservas as $reserva){

                //verifico que la reserva no este cancelada
                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
                   	$reservaMostrar = array();
					$reservaMostrar['marca']=$reserva['Unidad']['marca'];
					$reservaMostrar['modelo']=$reserva['Unidad']['modelo'];
					$reservaMostrar['patente']=$reserva['Unidad']['patente'];

					if ($reserva['Reserva']['retiro']==$date->format('d/m/Y')) {
						$reservaMostrar['nombre_apellido_retiro']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['nombre_apellido_devolucion']='';
						$reservaMostrar['lugar_retiro']=$reserva['Lugar_Retiro']['lugar'];
						$reservaMostrar['lugar_devolucion']='';
						$reservaMostrar['hora_retiro']=$reserva['Reserva']['hora_retiro'];
						$reservaMostrar['hora_devolucion']='';
						$reservaMostrar['hora']=$reserva['Reserva']['hora_retiro'];
					}
                	if ($reserva['Reserva']['devolucion']==$date->format('d/m/Y')) {
						$reservaMostrar['nombre_apellido_retiro']='';
						$reservaMostrar['nombre_apellido_devolucion']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['lugar_retiro']='';
						$reservaMostrar['lugar_devolucion']=$reserva['Lugar_Devolucion']['lugar'];
						$reservaMostrar['hora_retiro']='';
						$reservaMostrar['hora_devolucion']=$reserva['Reserva']['hora_devolucion'];
						$reservaMostrar['hora']=$reserva['Reserva']['hora_devolucion'];
					}
					if (($reserva['Reserva']['retiro']==$date->format('d/m/Y'))&&($reserva['Reserva']['devolucion']==$date->format('d/m/Y')) ){
						$reservaMostrar['nombre_apellido_retiro']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['nombre_apellido_devolucion']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['lugar_retiro']=$reserva['Lugar_Retiro']['lugar'];
						$reservaMostrar['lugar_devolucion']=$reserva['Lugar_Devolucion']['lugar'];
						$reservaMostrar['hora_retiro']=$reserva['Reserva']['hora_retiro'];
						$reservaMostrar['hora_devolucion']=$reserva['Reserva']['hora_devolucion'];
						$reservaMostrar['hora']=$reserva['Reserva']['hora_retiro'];
					}
                    $reservasMostrar[]=array('marca'=>$reservaMostrar['marca'],'modelo'=>$reservaMostrar['modelo'],'patente'=>$reservaMostrar['patente'],'nombre_apellido_retiro'=>$reservaMostrar['nombre_apellido_retiro'],'nombre_apellido_devolucion'=>$reservaMostrar['nombre_apellido_devolucion'],'lugar_retiro'=>$reservaMostrar['lugar_retiro'],'lugar_devolucion'=>$reservaMostrar['lugar_devolucion'],'hora_retiro'=>$reservaMostrar['hora_retiro'],'hora_devolucion'=>$reservaMostrar['hora_devolucion'],'hora'=>$reservaMostrar['hora']);
                }

	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0

		$this->array_sort_by($reservasMostrar, 'hora');

		$array_dias['Sunday'] = "Domingo";
		$array_dias['Monday'] = "Lunes";
		$array_dias['Tuesday'] = "Martes";
		$array_dias['Wednesday'] = "Miercoles";
		$array_dias['Thursday'] = "Jueves";
		$array_dias['Friday'] = "Viernes";
		$array_dias['Saturday'] = "Sabado";



		//echo "El dia es ".$array_dias[date('l', strtotime($fecha))];

        $this->set(array(
            'desde' => $array_dias[date('l', strtotime($fecha))].' '.$date->format('d/m/Y'),
         	'pdf' => $pdf,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Diario_operaciones_'.$date->format('d_m_Y').'.pdf','D');


        }
        /**/

     }

	function ventas_semanal($desde, $hasta,$pdf=0){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);
        $_SESSION['primerDia']=$desde;
        $_SESSION['ultimoDia']=$hasta;
        $this->layout = 'informe';

        $this->loadModel('Reserva');
      	$this->loadModel('Categoria');
        $array_dias['Sunday'] = "Domingo";
		$array_dias['Monday'] = "Lunes";
		$array_dias['Tuesday'] = "Martes";
		$array_dias['Wednesday'] = "Mi�rcoles";
		$array_dias['Thursday'] = "Jueves";
		$array_dias['Friday'] = "Viernes";
		$array_dias['Saturday'] = "S�bado";


		//lista de empleados de operaciones, tengo que ir a buscar por sector de trabajo
        $this->loadModel('EmpleadoTrabajo');


        $empleadosTrabajo = $this->EmpleadoTrabajo->find('all',array('fields'=>array('max(EmpleadoTrabajo.id) as id'), 'group' => array('EmpleadoTrabajo.empleado_id'), 'conditions' => array( 'Empleado.estado ' => 1 )));

        foreach($empleadosTrabajo as $empleadoTrabajo){

        	$this->EmpleadoTrabajo->id = $empleadoTrabajo[0]['id'];
        	$sector = $this->EmpleadoTrabajo->read();

        	if (($sector['EmpleadoTrabajo']['sector_1_id']==2)||($sector['EmpleadoTrabajo']['sector_2_id']==2)) {
        		$empleados[$sector['Empleado']['id']] = $sector['Empleado']['nombre']." ".$sector['Empleado']['apellido'];
        	}

        }
        $this->set('empleados',$empleados);

        $reservasMostrar = array();
        for($fecha=$desde;$fecha<=$hasta;$fecha = date("Y-m-d", strtotime($fecha ."+ 1 days"))){

	        $reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array('retiro =' => $fecha,'devolucion =' => $fecha))));


	        	/*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = $logs['log'][0];
			    echo $lastLog['query'];*/
	        $date = new DateTime($fecha);

	       	$reservasMostrarDia = array();
	        if(count($reservas) > 0){

	            foreach($reservas as $reserva){
	                //print_r($reserva);
	                //verifico que la reserva no este cancelada
	                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
	                   	$reservaMostrar = array();
	                   	$this->Categoria->id = $reserva['Unidad']['categoria_id'];
        				$categoria = $this->Categoria->read();
						$reservaMostrar['categoria']=$categoria['Categoria']['categoria'];
						$reservaMostrar['vehiculo']=$categoria['Categoria']['vehiculos'];
						$reservaMostrar['patente']=$reserva['Unidad']['patente'];
						$reservaMostrar['titular']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['vuelo']=($reserva['Reserva']['vuelo']==0)?'':$reserva['Reserva']['vuelo'];
						$reservaMostrar['obs']=$reserva['Reserva']['comentarios'];
						$reservaMostrar['id_reserva']=$reserva['Reserva']['id'];
						$reservaMostrar['responsableRetiro']=$reserva['Reserva']['responsableRetiro'];
						$reservaMostrar['responsableDevolucion']=$reserva['Reserva']['responsableDevolucion'];
						if ($reserva['Reserva']['retiro']==$date->format('d/m/Y')) {
							$reservaMostrar['tipo']='Entrega';
							$reservaMostrar['lugar']=$reserva['Lugar_Retiro']['lugar'];
							$reservaMostrar['hora']=$reserva['Reserva']['hora_retiro'];
						}
	                	if ($reserva['Reserva']['devolucion']==$date->format('d/m/Y')) {
	                		$reservaMostrar['tipo']='Devoluci�n';
							$reservaMostrar['lugar']=$reserva['Lugar_Devolucion']['lugar'];
							$reservaMostrar['hora']=$reserva['Reserva']['hora_devolucion'];
						}

	                    $reservasMostrarDia[]=array('dia'=>$array_dias[date('l', strtotime($fecha))].' '.$date->format('d/m/Y'),'categoria'=>$reservaMostrar['categoria'],'vehiculo'=>$reservaMostrar['vehiculo'],'patente'=>$reservaMostrar['patente'],'titular'=>$reservaMostrar['titular'],'lugar'=>$reservaMostrar['lugar'],'tipo'=>$reservaMostrar['tipo'],'hora'=>$reservaMostrar['hora'],'vuelo'=>$reservaMostrar['vuelo'],'obs'=>$reservaMostrar['obs'],'id_reserva'=>$reservaMostrar['id_reserva'],'responsableRetiro'=>$reservaMostrar['responsableRetiro'],'responsableDevolucion'=>$reservaMostrar['responsableDevolucion']);
	                    $this->array_sort_by($reservasMostrarDia, 'hora');
	                    //print_r($reservasMostrarDia);
	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0
	        if (!empty($reservasMostrarDia)) {
	        	$reservasMostrar[]=$reservasMostrarDia;
	        }

        }

		//





		//echo "El dia es ".$array_dias[date('l', strtotime($fecha))];

        $this->set(array(

         	'pdf' => $pdf,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L']);
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Semanal_operaciones_'.$desde.'_'.$hasta.'.pdf','D');



        }
        /**/

     }



	function exportarOperacionesSemanal($desde, $hasta){
         //error_reporting(0);
        $this->layout = 'ajax';




    	$this->autoRender = false;
  		$this->layout = false;


		$fileName = "Semanal_operaciones_".$desde.'_'.$hasta.".xls";
		//$fileName = "bookreport_".date("d-m-y:h:s").".csv";
		//$headerRow = array("ENT O DEV","HORARIO","LUGAR","TITULAR","N� VUELO","CATEGORIA","VEH�CULO","OBS","PATENTE","RESPONSABLE");

		$data = array();

		$this->loadModel('Reserva');
      	$this->loadModel('Categoria');
      	$this->loadModel('Empleado');
        $array_dias['Sunday'] = "Domingo";
		$array_dias['Monday'] = "Lunes";
		$array_dias['Tuesday'] = "Martes";
		$array_dias['Wednesday'] = "Mi�rcoles";
		$array_dias['Thursday'] = "Jueves";
		$array_dias['Friday'] = "Viernes";
		$array_dias['Saturday'] = "S�bado";

        $reservasMostrar = array();

        $table="<table width='100%' cellspacing='0'>
     	<tr class='titulo'>
        		<td colspan='10' align='center' style='border: 1px solid black;'>Planificaci�n de Entregas y devoluciones per�odo ".date("d/m/Y",strtotime($desde)).' - '.date("d/m/Y",strtotime($hasta))." Fecha Informe: ".date('d/m/Y H:i')."</td>

    		</tr>

    <tr>
        <td align='center' style='border: 1px solid black;'>ENT O DEV</td>
        <td align='center' style='border: 1px solid black;'>HORARIO</td>
        <td align='center' style='border: 1px solid black;'>LUGAR</td>

        <td align='center' style='border: 1px solid black;'>TITULAR</td>
        <td align='center' style='border: 1px solid black;'>N� VUELO</td>
        <td align='center' style='border: 1px solid black;'>CATEGORIA</td>
        <td align='center' style='border: 1px solid black;'>VEH�CULO</td>
        <td align='center' style='border: 1px solid black;'>OBS</td>
        <td align='center' style='border: 1px solid black;'>PATENTE</td>
        <td align='center' style='border: 1px solid black;'>RESPONSABLE</td>
    </tr>";
        for($fecha=$desde;$fecha<=$hasta;$fecha = date("Y-m-d", strtotime($fecha ."+ 1 days"))){

	        $reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array('retiro =' => $fecha,'devolucion =' => $fecha))));


	        	/*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = $logs['log'][0];
			    echo $lastLog['query'];*/
	        $date = new DateTime($fecha);

	       	$reservasMostrarDia = array();
	        if(count($reservas) > 0){

	            foreach($reservas as $reserva){
	                //print_r($reserva);
	                //verifico que la reserva no este cancelada
	                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
	                   	$reservaMostrar = array();
	                   	$this->Categoria->id = $reserva['Unidad']['categoria_id'];
        				$categoria = $this->Categoria->read();
						$reservaMostrar['categoria']=$categoria['Categoria']['categoria'];
						$reservaMostrar['vehiculo']=$categoria['Categoria']['vehiculos'];
						$reservaMostrar['patente']=$reserva['Unidad']['patente'];
						$reservaMostrar['titular']=$reserva['Cliente']['nombre_apellido'];
						$reservaMostrar['vuelo']=($reserva['Reserva']['vuelo']==0)?'':$reserva['Reserva']['vuelo'];
						$reservaMostrar['obs']=$reserva['Reserva']['comentarios'];
						$reservaMostrar['id_reserva']=$reserva['Reserva']['id'];
						if ($reserva['Reserva']['retiro']==$date->format('d/m/Y')) {
							$reservaMostrar['tipo']='Entrega';
							$reservaMostrar['lugar']=$reserva['Lugar_Retiro']['lugar'];
							$reservaMostrar['hora']=$reserva['Reserva']['hora_retiro'];
	        				$this->Empleado->id = $reserva['Reserva']['responsableRetiro'];
							$empleado = $this->Empleado->read();

	        				$reservaMostrar['responsable']=$empleado['Empleado']['nombre']." ".$empleado['Empleado']['apellido'];
						}
	                	if ($reserva['Reserva']['devolucion']==$date->format('d/m/Y')) {
	                		$reservaMostrar['tipo']='Devoluci�n';
							$reservaMostrar['lugar']=$reserva['Lugar_Devolucion']['lugar'];
							$reservaMostrar['hora']=$reserva['Reserva']['hora_devolucion'];
							$this->Empleado->id = $reserva['Reserva']['responsableDevolucion'];
	        				$empleado = $this->Empleado->read();

	        				$reservaMostrar['responsable']=$empleado['Empleado']['nombre']." ".$empleado['Empleado']['apellido'];
						}

	                    $reservasMostrarDia[]=array('dia'=>$array_dias[date('l', strtotime($fecha))].' '.$date->format('d/m/Y'),'categoria'=>$reservaMostrar['categoria'],'vehiculo'=>$reservaMostrar['vehiculo'],'patente'=>$reservaMostrar['patente'],'titular'=>$reservaMostrar['titular'],'lugar'=>$reservaMostrar['lugar'],'tipo'=>$reservaMostrar['tipo'],'hora'=>$reservaMostrar['hora'],'vuelo'=>$reservaMostrar['vuelo'],'obs'=>$reservaMostrar['obs'],'responsable'=>$reservaMostrar['responsable'],'id_reserva'=>$reservaMostrar['id_reserva']);
	                    $this->array_sort_by($reservasMostrarDia, 'hora');
	                    //print_r($reservasMostrarDia);
	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0
	        if (!empty($reservasMostrarDia)) {
	        	$reservasMostrar[]=$reservasMostrarDia;
	        }

        }

        foreach($reservasMostrar as $reservaDia){
			$data[] = array($reservaDia[0]['dia']);
			$table.="<tr style='font-weight: bold;'>
	        <td colspan='10' align='center' style='border: 1px solid black;background-color: #a4a6a6;'>".($reservaDia[0]['dia'])."</td>

	    </tr>";
			foreach($reservaDia as $reserva){

				$table.="<tr>";
	    		$table.="<td align='center' style='border: 1px solid black;'>".($reserva['tipo'])."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".$reserva['hora']."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".($reserva['lugar'])."</td>";


	        	$table.="<td align='center' style='border: 1px solid black;'>".($reserva['titular'])."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".$reserva['vuelo']."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".($reserva['categoria'])."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".($reserva['vehiculo'])."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".($reserva['obs'])."</td>";
	        	$table.="<td align='center' style='border: 1px solid black;'>".$reserva['patente']."</td>";


	          	$table.="<td align='center' style='border: 1px solid black;'>".$reserva['responsable']."</td>
	    	</tr>";
			}
        }
        $table.="
        <tr class='titulo'>
        		<td colspan='10' style='border: 1px solid black;color: #fb061c;'>* Tenga presente que este informe es parcial y no incluye reservas cargadas en el sistema posteriormente a la hora en la que se emiti� este informe</td>

    		</tr>
        </table>";
        //echo $table;
		$this->ExportXls->exportTable($fileName, $table);
    }


	function ventas_grilla($desde,$hasta,$pdf=0){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'informe';

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');


        $mesesMostrar = array();
        $diasMostrar = array();
        $diasMostrados = array();
        $diasSemanaMostrar = array();
        $mesAnt = 0;
        $array_dias['Sunday'] = "Domingo";
		$array_dias['Monday'] = "Lunes";
		$array_dias['Tuesday'] = "Martes";
		$array_dias['Wednesday'] = "Miercoles";
		$array_dias['Thursday'] = "Jueves";
		$array_dias['Friday'] = "Viernes";
		$array_dias['Saturday'] = "Sabado";
        for($i=$desde;$i<=$hasta;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
        	$mes = explode("-", $i);
        	//echo $mes[1].' - '.$mesAnt."<br>";
        	if ($mes[1]!=$mesAnt) {
        		$dias = 1;
        	}
        	else{
        		$dias++;

        	}
        	$mesAnt=$mes[1];
        	$mesesMostrar[$meses[intval($mes[1])].'-'.$mes[0]]=$dias;
        	$diasSemanaMostrar[]=$array_dias[date('l', strtotime($i))].'-'.$mes[2];
        	//$diasMostrar[]=$mes[2];
        	$diasMostrados[$i]=array('retiro'=>'','devolucion'=>'');

        }
        /*$i=1;
        foreach ($diasMostrados as $key => $value) {
        	$diasMostrados[$key]['retiro']='r'.$i;
        	$diasMostrados[$key]['devolucion']='d'.$i;
        	$i++;
        }*/
		//print_r($diasMostrados);
        $this->set('meses',$mesesMostrar);
        //$this->set('dias',$diasMostrar);
		$this->set('diasSemana',$diasSemanaMostrar);

        $this->loadModel('Reserva');



        	//$reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array('retiro =' => $desde,'devolucion =' => $desde)), 'recursive' => 2));

        	$reservas = $this->Reserva->find('all',array('conditions' => array('or'=>array(array('retiro >=' => $desde,'retiro <=' => $hasta),array('devolucion >=' => $desde,'devolucion <=' => $hasta),array('retiro <' => $desde,'devolucion >' => $hasta))), 'order' => 'devolucion ASC', 'recursive' => 1));


        	/*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    $lastLog = $logs['log'][0];
		    echo $lastLog['query'];*/
        $date = new DateTime($desde);
       $reservasMostrar = array();


       $this->loadModel('Unidad');
		$unidads = $this->Unidad->find('all',array('conditions' => array('estado =' =>1)));
        $unidadesDias = array();
        foreach($unidads as $unidad){
        	$reservaMostrar = array();
        	$reservaMostrar['unidad']=$unidad['Unidad']['id'];
        	$reservaMostrar['orden']=$unidad['Unidad']['orden'];
            $reservaMostrar['categoria']=$unidad['Categoria']['categoria'];
			$reservaMostrar['marca']=$unidad['Unidad']['marca'];
			$reservaMostrar['modelo']=$unidad['Unidad']['modelo'];
			$reservaMostrar['patente']=$unidad['Unidad']['patente'];
			$unidadesDias[$unidad['Unidad']['id']] = $diasMostrados;
			$pri=1;
        	if(count($reservas) > 0){

	            foreach($reservas as $reserva){

	                //verifico que la reserva no este cancelada
	                if(($reserva['Reserva']['estado'] != 2)&&($unidad['Unidad']['id']==$reserva['Unidad']['id'])){
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


			            $facturado = 0;
			            if(count($reserva['ReservaFactura']) > 0){
			                foreach($reserva['ReservaFactura'] as $factura){
			                    $facturado += $factura['monto'];
			                }
			            }
			            $pendiente = round(round($reserva['Reserva']['total'],2) + round($no_adelantadas,2) - round($descontado,2) - round($pagado,2) + round($devoluciones,2),2);
			            $pendiente = ($pendiente==-0)?0:$pendiente;


			            switch($reserva['Reserva']['estado']){
			                case 0:
			                    if($pagado == 0){
			                        $color='#ff0033';

					            }
					            elseif($pendiente > 0){
			                        $color='#f44611';
		                    	}
		                    	else{
			                        $color='#579d1c';
			                    }
			                    break;
			                case 1:
			                    $color='#579d1c';
			                    break;

			            }
			            if ($unidad['Unidad']['id']==12) {
			            	 $color='#FFFF00';
			            }

			            	//echo $unidad['Unidad']['id'].' - '.$reserva['Cliente']['nombre_apellido'].' - '.$reserva['Reserva']['retiro'].' - '.$reserva['Reserva']['devolucion']."<br>";
			            	$date_parts = explode("/",$reserva['Reserva']['retiro']);
		        			$yy=$date_parts[2];
		        			$mm=$date_parts[1];
		        			$dd=$date_parts[0];
		        			$dateRetiroStr = $yy.'-'.$mm.'-'.$dd;
			            	$dateRetiro = new DateTime($yy.'-'.$mm.'-'.$dd);
			            	$date_parts = explode("/",$reserva['Reserva']['devolucion']);
		        			$yy=$date_parts[2];
		        			$mm=$date_parts[1];
		        			$dd=$date_parts[0];
		        			$dateDevolucionStr = $yy.'-'.$mm.'-'.$dd;
			            	$dateDevolucion = new DateTime($yy.'-'.$mm.'-'.$dd);
			            	$interval = $dateRetiro->diff($dateDevolucion);


			            $disReservados = intval($interval->format('%R%a'));

			            if (($disReservados > count($diasMostrados))&&($dateRetiroStr<$desde)&&($dateDevolucionStr>$hasta)) {
			            	$hr = explode(":", $reserva['Reserva']['hora_retiro']);
							$hd = explode(":", $reserva['Reserva']['hora_devolucion']);
							$canal = '<br>Retiro '.$hr[0].':'.$hr[1].'/'.$reserva['Lugar_Retiro']['lugar'].' <br>Devolucion '.$hd[0].':'.$hd[1].'/'.$reserva['Lugar_Devolucion']['lugar'];
							$unidadesDias[$unidad['Unidad']['id']][$desde]['retiro']=$reserva['Cliente']['nombre_apellido'].' '.$canal.'-'.count($diasMostrados).'-'.$color.'-'.$reserva['Reserva']['id'].'-dev';
			            }
						else{
							foreach ($diasMostrados as $day => $res) {
								//echo $reserva['Reserva']['retiro'].' - '.$day."<br>";

								$dateFormat = new DateTime($day);
								//$canal =  ($reserva['Subcanal']['id'])?'('.$reserva['Subcanal']['Canal']['canal'].' / '.$reserva['Subcanal']['subcanal'].')':'';
								$hr = explode(":", $reserva['Reserva']['hora_retiro']);
								$hd = explode(":", $reserva['Reserva']['hora_devolucion']);
								$canal = '<br>Retiro '.$hr[0].':'.$hr[1].'/'.$reserva['Lugar_Retiro']['lugar'].' <br>Devolucion '.$hd[0].':'.$hd[1].'/'.$reserva['Lugar_Devolucion']['lugar'];
								if ($reserva['Reserva']['retiro']==$dateFormat->format('d/m/Y')) {
									$pri=0;




									$unidadesDias[$unidad['Unidad']['id']][$day]['retiro']=$reserva['Cliente']['nombre_apellido'].' '.$canal.'-'.$reserva['Reserva']['noches'].'-'.$color.'-'.$reserva['Reserva']['id'];
									/*$nuevafecha = $day;
									for ($i = 1; $i <= $reserva['Reserva']['noches']; $i++) {
										$nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
										$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
										//echo $nuevafecha."<br>";
										 unset($unidadesDias[$unidad['Unidad']['id']][$nuevafecha]);
									}*/
								}

								if (($pri)&&($reserva['Reserva']['devolucion']==$dateFormat->format('d/m/Y'))) {
									$pri=0;
									//echo $desde;
									$interval = $date->diff($dateFormat);

									//echo intval($interval->format('%R%a')).'=>'. $reserva['Cliente']['nombre_apellido']."<br>";

									$unidadesDias[$unidad['Unidad']['id']][$desde]['retiro']=$reserva['Cliente']['nombre_apellido'].' '.$canal.'-'.intval($interval->format('%R%a')).'-'.$color.'-'.$reserva['Reserva']['id'].'-dev';
								}




							}
						}
						//print_r($diasMostrados);






	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0

	        $reservasMostrar[]=array('unidad'=>$reservaMostrar['unidad'],'orden'=>$reservaMostrar['orden'],'categoria'=>$reservaMostrar['categoria'],'marca'=>$reservaMostrar['marca'],'modelo'=>$reservaMostrar['modelo'],'patente'=>$reservaMostrar['patente'], 'diasMostrar'=>$unidadesDias);
        }


       /*foreach ($unidadesDias as $unidad => $dias) {
       	 echo $unidad."<br>";
       		print_r($dias);
       	 echo "<br>";
       } */

		$this->array_sort_by($reservasMostrar, 'orden');

		//print_r($reservasMostrar);



		//echo "El dia es ".$array_dias[date('l', strtotime($fecha))];

        $this->set(array(
            'desde' => $array_dias[date('l', strtotime($desde))].' '.$date->format('d/m/Y'),
         	'pdf' => $pdf,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Diario_operaciones_'.$date->format('d_m_Y').'.pdf');


        }
        /**/

     }


    function ventas_economico($ano,$desde,$hasta, $categoria_id, $unidad_id){
        //echo $desde;
        //print_r($unidad_id);
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");


        $this->layout = 'ajax';

        $this->loadModel('Reserva');
        $this->loadModel('CobroEfectivo');
        $this->loadModel('CobroTransferencia');
        $this->loadModel('CobroCheque');
        $this->loadModel('CobroTarjeta');
        $this->loadModel('CobroTarjetaPosnet');
        $this->loadModel('CobroTarjetaLote');
        $this->loadModel('Caja');
        $this->loadModel('Cuenta');
        $this->loadModel('CobroTransferencia');
        $this->loadModel('CobroCheque');
        $this->loadModel('Unidad');
        $this->loadModel('ExtraRubro');
        $this->loadModel('Extra');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);



        for($i=1; $i<=12; $i++){
            $alojamientos[$i] = 0;
            $adelantadas[$i] = 0;
            $no_adelantadas[$i] = 0;
            $descuentos[$i] = 0;
            $intereses[$i] = 0;
            $devoluciones[$i] = 0;
            $descuentos_tarjetas[$i] = 0;
            $capacidad_ocupada[$i] = 0;
            $ventas_netas[$i] = 0;
            $cobrado[$i] = 0;
            $pendiente_cobro[$i] = 0;
        }

        $extra_rubros = $this->ExtraRubro->find('list');
        $this->set('extra_rubros',$extra_rubros);
        foreach($extra_rubros as $id => $rubro){
            for($i=1; $i<=12; $i++){
                $adelantadas_rubro[$id][$i] = 0;
                $no_adelantadas_rubro[$id][$i] = 0;
            }
        }

        $cobro_posnets = $this->CobroTarjetaPosnet->find('list');
        $this->set('posnets',$cobro_posnets);
        foreach($cobro_posnets as $id => $posnet){
            for($i=1; $i<=12; $i++){
                $cobro_posnet[$id][$i] = 0;
            }
        }

        $cuentas = $this->Cuenta->find('list',array('conditions' => array('visible_en_informe' => 1)));
        $this->set('cuentas',$cuentas);
        foreach($cuentas as $id => $nombre){
            for($i=1; $i<=12; $i++){
                $cobro_cuenta[$id][$i] = 0;
            }
        }

        $cajas = $this->Caja->find('list',array('conditions' => array('visible_en_informe' => 1)));
        $this->set('cajas',$cajas);
        foreach($cajas as $id => $nombre){
            for($i=1; $i<=12; $i++){
                $cobro_caja[$id][$i] = 0;
            }
        }

        for($i=1; $i<=12; $i++){
            $cobro_cheque['COMUN'][$i] = 0;
            $cobro_cheque['DIFERIDO'][$i] = 0;
        }

        for($i=1; $i<=12; $i++){
            $devoluciones_pago['EFECTIVO'][$i] = 0;
            $devoluciones_pago['CHEQUE'][$i] = 0;
            $devoluciones_pago['TRANSFERENCIA'][$i] = 0;
        }
        $condicionUnidad = array();
        $condicionCategoria = array();
        if ($unidad_id!='null') {
            $arrayUnidad = explode(",", $unidad_id);
            $condicionUnidad = array('Unidad.id ' => $arrayUnidad);
        }
        elseif ($categoria_id!='Seleccionar...'){
            /*if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
                $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$hasta,'Unidad.baja >=' =>$desde);

            }
            elseif (($desde!='undefined-undefined-')) {
                    $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$desde,'Unidad.baja >=' =>$desde);

            }
            else{
                $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id);
            }*/
            $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$ano.'-12-31','Unidad.baja >=' =>$ano.'-01-01');
        }

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
            //$condicionYear = array('YEAR(devolucion)' => $ano, 'creado >=' => $desde, 'creado <=' => $hasta);
            $condicionYear =array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))),'creado >=' => $desde, 'creado <=' => $hasta);
        }
        else{
            //$condicionYear = array('YEAR(devolucion)' => $ano);

            $condicionYear =array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))));

        }
        //$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(creado)' => $ano, 'MONTH(creado)' => 3), 'recursive' => 2));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('creado <=' => '2014-02-28', 'creado >=' => '2014-02-01'), 'recursive' => 2));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('creado' => '2014-03-31'), 'recursive' => 2));
        //echo count($reservas)."<br>";

        $condicion=array($condicionUnidad,$condicionCategoria,$condicionYear);
        $reservas = $this->Reserva->find('all',array('conditions' => $condicion, 'recursive' => 1));
        //print_r($reservas);
        if(count($reservas) > 0){
            $this->loadModel('ExtraVariable');
            foreach($reservas as $reserva){
                $date_parts = explode("/",$reserva['Reserva']['devolucion']);
                $ano1=$date_parts[2];

                if($ano1==$ano) {
                    //if ($reserva['Unidad']['excluir']==0) {
                    //verifico que la reserva no este cancelada
                    if (($reserva['Reserva']['estado'] != 2) && ($reserva['Reserva']['estado'] != 3)) {
                        $alojamientos[$reserva['Reserva']['mes']] += $reserva['Reserva']['total_estadia'];
                        $ventas_netas[$reserva['Reserva']['mes']] += $reserva['Reserva']['total_estadia'];

                        //if(count($reserva['ReservaExtra']>0)){

                        foreach ($reserva['ReservaExtra'] as $extra) {


                            $extra_extra = $this->Extra->findById($extra['extra_id']);
                            $extraVariable = $this->ExtraVariable->findById($extra['extra_variable_id']);
                            //print_r($extra_extra);
                            if ($extra['adelantada'] == 1) {
                                if ($extra['extra_id']) {
                                    $adelantadas_rubro[$extra_extra['Extra']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                } elseif ($extra['extra_variable_id']) {
                                    $adelantadas_rubro[$extraVariable['ExtraVariable']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }
                                $adelantadas[$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                $ventas_netas[$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                            } else {
                                if ($extra['extra_id']) {
                                    $no_adelantadas_rubro[$extra_extra['Extra']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                } elseif ($extra['extra_variable_id']) {
                                    $no_adelantadas_rubro[$extraVariable['ExtraVariable']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }
                                $no_adelantadas[$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                $ventas_netas[$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                            }
                        }
                        //}
                    }
                    $cobroCancelado = 0;
                    if (count($reserva['ReservaCobro']) > 0) {

                        foreach ($reserva['ReservaCobro'] as $cobro) {
                            if ($cobro['tipo'] == 'DESCUENTO' and $reserva['Reserva']['estado'] != 2) { //no listamos los descuentos comerciales si se cancelo
                                $descuentos[$reserva['Reserva']['mes']] += $cobro['monto_neto'];
                                $ventas_netas[$reserva['Reserva']['mes']] -= $cobro['monto_neto'];
                            } else {
                                if ($reserva['Reserva']['estado'] != 2) {
                                    /*$INT = $cobro['monto_cobrado'] - $cobro['monto_neto'];
                                    echo $reserva['Reserva']['numero']." - ".$INT."<br>";*/
                                    $intereses[$reserva['Reserva']['mes']] += $cobro['monto_cobrado'] - $cobro['monto_neto'];

                                    //si se cancelo la reserva y existieron pagos se transoforma en venta neta el total de los cobros
                                    /*if($reserva['Reserva']['estado'] == 2){
                                        $ventas_netas[$reserva['Reserva']['mes']] += $cobro['monto_neto'];
                                    }*/

                                    $ventas_netas[$reserva['Reserva']['mes']] += $cobro['monto_cobrado'] - $cobro['monto_neto'];
                                }
                            }
                            switch ($cobro['tipo']) {
                                case 'TARJETA':
                                    $cobro_tarjeta = $this->CobroTarjeta->find('first', array('conditions' => array('reserva_cobro_id' => $cobro['id'])));
                                    //$cobro_tarjeta = $this->CobroTarjeta->findById($cobro['CobroTarjeta']['id']);
                                    if ($cobro_tarjeta['CobroTarjetaLote']['acreditado_por'] != 0) {
                                        $cobrado[$reserva['Reserva']['mes']] += $cobro_tarjeta['CobroTarjeta']['total'];
                                        $cobroCancelado += $cobro_tarjeta['CobroTarjeta']['total'];
                                        if ($reserva['Reserva']['estado'] != 2) {
                                            $ventas_netas[$reserva['Reserva']['mes']] -= $cobro_tarjeta['CobroTarjeta']['descuento_lote'];
                                        }
                                        $descuentos_tarjetas[$reserva['Reserva']['mes']] += $cobro_tarjeta['CobroTarjeta']['descuento_lote'];
                                        $cobro_posnet[$cobro_tarjeta['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$reserva['Reserva']['mes']] += $cobro_tarjeta['CobroTarjeta']['total'];
                                        //$intereses[$reserva['Reserva']['mes']] += $cobro_tarjeta['CobroTarjeta']['interes'];
                                    } else {
                                        $pendiente_cobro[$reserva['Reserva']['mes']] += $cobro_tarjeta['CobroTarjeta']['total'];
                                    }
                                    break;

                                case 'EFECTIVO':
                                    //print_r($cobro);
                                    $cobro_efectivo = $this->CobroEfectivo->find('first', array('conditions' => array('reserva_cobro_id' => $cobro['id'])));
                                    $caja_visible = $this->Caja->findById($cobro_efectivo['CobroEfectivo']['caja_id']);
                                    if ($caja_visible['Caja']['visible_en_informe']) {


                                        $cobro_caja[$cobro_efectivo['CobroEfectivo']['caja_id']][$reserva['Reserva']['mes']] += $cobro_efectivo['CobroEfectivo']['monto_neto'];
                                        $cobrado[$reserva['Reserva']['mes']] += $cobro_efectivo['CobroEfectivo']['monto_neto'];
                                        $cobroCancelado += $cobro_efectivo['CobroEfectivo']['monto_neto'];
                                    }
                                    break;

                                case 'TRANSFERENCIA':
                                    $cobro_transferencia = $this->CobroTransferencia->find('first', array('conditions' => array('reserva_cobro_id' => $cobro['id'])));

                                    if ($cobro_transferencia['CobroTransferencia']['acreditado']) {
                                        $cuenta_visible = $this->Cuenta->findById($cobro_transferencia['CobroTransferencia']['cuenta_id']);
                                        if ($cuenta_visible['Cuenta']['visible_en_informe']) {
                                            $cobro_cuenta[$cobro_transferencia['CobroTransferencia']['cuenta_id']][$reserva['Reserva']['mes']] += $cobro_transferencia['CobroTransferencia']['total'];
                                            $cobrado[$reserva['Reserva']['mes']] += $cobro_transferencia['CobroTransferencia']['total'];
                                            $cobroCancelado += $cobro_transferencia['CobroTransferencia']['total'];
                                            //$intereses[$reserva['Reserva']['mes']] += $cobro_transferencia['CobroTransferencia']['interes'];
                                        }
                                    } else {
                                        $pendiente_cobro[$reserva['Reserva']['mes']] += $cobro_transferencia['CobroTransferencia']['total'];
                                    }
                                    break;

                                case 'CHEQUE':
                                    $cobro_cheque = $this->CobroCheque->find('first', array('conditions' => array('reserva_cobro_id' => $cobro['id'])));
                                    if ($cobro_cheque['CobroCheque']['acreditado'] or $cobro_cheque['CobroCheque']['asociado_a_pagos']) {
                                        $cobro_cheque[$cobro_cheque['CobroCheque']['tipo']][$reserva['Reserva']['mes']] += $cobro_cheque['CobroCheque']['monto_neto'];
                                        $cobrado[$reserva['Reserva']['mes']] += $cobro_cheque['CobroCheque']['total'];
                                        $cobroCancelado += $cobro_cheque['CobroCheque']['total'];
                                        //$intereses[$reserva['Reserva']['mes']] += $cobro_cheque['CobroCheque']['interes'];
                                    } else {
                                        $pendiente_cobro[$reserva['Reserva']['mes']] += $cobro_cheque['CobroCheque']['total'];
                                    }
                                    break;
                            }
                        }
                        // }
                    }
                    if (count($reserva['ReservaDevolucion']) > 0) {
                        foreach ($reserva['ReservaDevolucion'] as $devolucion) {
                            $devoluciones[$reserva['Reserva']['mes']] += $devolucion['monto'];
                            $devoluciones_pago[$devolucion['forma_pago']][$reserva['Reserva']['mes']] -= $devolucion['monto'];
                            $cobroCancelado -= $devolucion['monto'];
                            //$ventas_netas[$reserva['Reserva']['mes']] -= $devolucion['monto'];
                        }
                    }

                    //$capacidad_ocupada[$reserva['Reserva']['mes']]  += ($reserva['Reserva']['pax_adultos'] + $reserva['Reserva']['pax_menores'] + $reserva['Reserva']['pax_bebes']) * $reserva['Reserva']['noches'];
                }
                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
                    $date_parts = explode("/",$reserva['Reserva']['retiro']);
                    $yy=$date_parts[2];
                    if($yy==$ano){
                        $mm=$date_parts[1];
                        $dd=ltrim($date_parts[0], '0');
                        $noches=$reserva['Reserva']['noches'];
                        $nuevafecha = $yy.'-'.$mm.'-'.$dd;
                        $mesretiro=$reserva['Reserva']['mesretiro'];
                        //echo $reserva['Reserva']['id'].' - '.$nuevafecha.' - '.$reserva['Reserva']['devolucion'].' - '.$noches."<br>";
                    }
                    else{
                        $yy=$ano;
                        $mm='01';
                        $dd=1;
                        $nuevafecha = $yy.'-'.$mm.'-'.$dd;
                        $mesretiro=1;
                        $datetime1 = new \DateTime($nuevafecha);

                        $date_parts2 = explode("/",$reserva['Reserva']['devolucion']);
                        $yy2=$date_parts2[2];
                        $mm2=$date_parts2[1];
                        $dd2=ltrim($date_parts2[0], '0');
                        $nuevafecha2 = $yy2.'-'.$mm2.'-'.$dd2;
                        $datetime2 = new \DateTime($nuevafecha2);
                        $interval = $datetime2->diff($datetime1);

                        $noches=$interval->days;
                        $noches=($noches==0)?1:$noches;
                    }


                    //echo "Dia 1:".$mm.'-'.$dd."-";

                    //echo "Dia 1:".$mm.'-'.$dd." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$reserva['Reserva']['mes']." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$reserva['Reserva']['mes'].'-'.$dd]."<br>";
                    if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd] ) {

                        $control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd]  = ($reserva['Unidad']['excluir']==0)?1:0;
                        //$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$dd]  += ($reserva['Unidad']['excluir']==0)?1:0;

                        $capacidad_ocupada[intval($mm)]  += ($reserva['Unidad']['excluir']==0)?1:0;


                    }

                    for ($i = 1; $i < $noches; $i++) {
                        $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
                        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                        $date_parts = explode("-",$nuevafecha);
                        //echo "Resto:".$date_parts[1].'-'.$date_parts[2]." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$mesretiro." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]."<br>";
                        if ($ano==$date_parts[0]){
                            if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]) {

                                $control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]  = ($reserva['Unidad']['excluir']==0)?1:0;
                                //$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$date_parts[2]]  += ($reserva['Unidad']['excluir']==0)?1:0;

                                $capacidad_ocupada[intval($date_parts[1])]  += ($reserva['Unidad']['excluir']==0)?1:0;

                            }
                        }




                    }
                    //$capacidad_ocupada[$reserva['Reserva']['mes']]  += ($reserva['Unidad']['excluir']==0)?$reserva['Reserva']['noches']:0;
                }
                if($ano1==$ano) {
                    if ($reserva['Reserva']['estado'] == 2) {
                        $alojamientos[$reserva['Reserva']['mes']] += $cobroCancelado;
                        $ventas_netas[$reserva['Reserva']['mes']] += $cobroCancelado;
                    }
                }

                //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0


//        $lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('YEAR(fecha_cierre)' => $ano, 'acreditado_por !=' => 0)));
//        foreach($lotes as $lote){
//            $descuentos_tarjetas[$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos'];
//            $descuentos_tarjeta_posnets[$lote['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos']; print_r($descuentos_tarjeta_posnets);
//            $cobrado[$lote['CobroTarjetaLote']['mes_cierre']] -= $lote['CobroTarjetaLote']['descuentos'];
//            $ventas_netas[$reserva['Reserva']['mes']] -= $lote['CobroTarjetaLote']['descuentos'];
//        }
        $condicionCategoria = array();
        $condicionUnidad = array();

        if ($unidad_id!='null') {
            $arrayUnidad = explode(",", $unidad_id);
            $condicionUnidad = array('Unidad.id ' => $arrayUnidad);
        }
        elseif ($categoria_id!='Seleccionar...'){
            /*if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
                $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$hasta,'Unidad.baja >=' =>$desde);

            }
            elseif (($desde!='undefined-undefined-')) {
                    $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$desde,'Unidad.baja >=' =>$desde);

            }
            else{
                $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id);
            }*/
            $condicionCategoria=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$ano.'-12-31','Unidad.baja >=' =>$ano.'-01-01');
        }
        $condicion=array($condicionUnidad,$condicionCategoria);
        $unidads = $this->Unidad->find('all',array('conditions' => $condicion));
        /*App::uses('ConnectionManager', 'Model');
        	$dbo = ConnectionManager::getDatasource('default');
		    $logs = $dbo->getLog();
		    //$lastLog = $logs['log'][0];
		    print_r($logs);*/
        $q_unidads = count($unidads);
        $capacidad_total = array();
        $unidades_activas = array();
        foreach($unidads as $unidad){
            //print_r($unidad);
            //if (($unidad['Unidad']['estado']==1 )&&($unidad['Unidad']['excluir']==0 )) {
            if ($unidad['Unidad']['excluir']==0 ) {
                for($i=1; $i<=12; $i++){
                    $dia = $ano.'-'.str_pad($i, 2, "0", STR_PAD_LEFT);
                    $date_parts = explode("/",$unidad['Unidad']['habilitacion']);
                    $yy=$date_parts[2];
                    $mm=$date_parts[1];
                    $habilitacion = $yy.'-'.$mm;
                    $date_parts = explode("/",$unidad['Unidad']['baja']);
                    $yy=$date_parts[2];
                    $mm=$date_parts[1];
                    $baja = $yy.'-'.$mm;
                    if (($habilitacion<=$dia)&&($baja>=$dia)) {
                        $capacidad_total[$i]+= 30;
                        $unidades_activas[$i]++;
                    }
                }
            }
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        //$capacidad_total = $capacidad_total * 30;

        $this->set(array(
            'ano' => $ano,
            'alojamientos' => $alojamientos,
            'no_adelantadas' => $no_adelantadas,
            'adelantadas' => $adelantadas,
            'descuentos' => $descuentos,
            'intereses' => $intereses,
            'devoluciones' => $devoluciones,
            'descuentos_tarjetas' => $descuentos_tarjetas,
            'descuentos_tarjeta_posnets' => $descuentos_tarjeta_posnets,
            'unidades_activas' => $unidades_activas,
            'capacidad_total' => $capacidad_total,
            'capacidad_ocupada' => $capacidad_ocupada,
            //'q_apartamentos' => $q_apartamentos,
            'ventas_netas' => $ventas_netas,
            'cobrado' => $cobrado,
            'pendiente_cobro' => $pendiente_cobro,
            'cobro_caja' => $cobro_caja,
            'cobro_cuenta' => $cobro_cuenta,
            'cobro_posnet' => $cobro_posnet,
            'cobro_cheque' => $cobro_cheque,
            'adelantadas_rubro' => $adelantadas_rubro,
            'no_adelantadas_rubro' => $no_adelantadas_rubro,
            'devoluciones_pago' => $devoluciones_pago
        ));

    }

	function ventas_extras($ano,$desde,$hasta){
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'ajax';

        $this->loadModel('Reserva');

        $this->loadModel('ExtraRubro');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(devolucion)' => $ano, 'creado >=' => $desde, 'creado <=' => $hasta), 'recursive' => 1));
        }
        else
        	$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(devolucion)' => $ano), 'recursive' => 1));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(check_out)' => $ano, 'MONTH(check_out)' => 3), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('check_out <=' => '2014-02-28', 'check_out >=' => '2014-02-01'), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('check_out' => '2014-03-31'), 'recursive' => 2));

        for($i=1; $i<=12; $i++){

            $adelantadas[$i] = 0;
            $no_adelantadas[$i] = 0;

        }

        $extra_rubros = $this->ExtraRubro->find('list');

        $this->set('extra_rubros',$extra_rubros);
        foreach($extra_rubros as $id => $rubro){
            for($i=1; $i<=12; $i++){
                $adelantadas_rubro[$id][$i] = 0;
                $no_adelantadas_rubro[$id][$i] = 0;
            }
        }






        $this->loadModel('Extra');
        $this->loadModel('ExtraVariable');

        if(count($reservas) > 0){

            foreach($reservas as $reserva){

                //verifico que la reserva no este cancelada
                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){


                    if(count($reserva['ReservaExtra']>0)){
                        foreach($reserva['ReservaExtra'] as $extra){

                        	$extraRubro=$this->Extra->findById($extra['extra_id']);
                        	$extraVariable=$this->ExtraVariable->findById($extra['extra_variable_id']);
                        	/*print_r($extraVariable);
                        	echo "<br>";*/
                            if($extra['adelantada'] == 1){
                                if($extra['extra_id']){
                                    $adelantadas_rubro[$extraRubro['Extra']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }elseif($extra['extra_variable_id']){
                                    $adelantadas_rubro[$extraVariable['ExtraVariable']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }
                                $adelantadas[$reserva['Reserva']['mes']]  += $extra['cantidad'] * $extra['precio'];

                            }else{
                                if($extra['extra_id']){
                                    $no_adelantadas_rubro[$extraRubro['Extra']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }elseif($extra['extra_variable_id']){
                                    $no_adelantadas_rubro[$extraVariable['ExtraVariable']['extra_rubro_id']][$reserva['Reserva']['mes']] += $extra['cantidad'] * $extra['precio'];
                                }
                                $no_adelantadas[$reserva['Reserva']['mes']]  += $extra['cantidad'] * $extra['precio'];

                            }
                        }
                    }
                }





	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0

//        $lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('YEAR(fecha_cierre)' => $ano, 'acreditado_por !=' => 0)));
//        foreach($lotes as $lote){
//            $descuentos_tarjetas[$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos'];
//            $descuentos_tarjeta_posnets[$lote['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos']; print_r($descuentos_tarjeta_posnets);
//            $cobrado[$lote['CobroTarjetaLote']['mes_cierre']] -= $lote['CobroTarjetaLote']['descuentos'];
//            $ventas_netas[$reserva['Reserva']['mes']] -= $lote['CobroTarjetaLote']['descuentos'];
//        }


        //$capacidad_total = $capacidad_total * 30;

        $this->loadModel('Usuario');
		 $user_id = $_SESSION['useridushuaia'];
        $user = $this->Usuario->find('first',array('conditions'=>array('Usuario.id'=>$_SESSION['useridushuaia'])));

		$permisoAdelantada=1;
		$permisoNoAdelantada=1;
		//print_r($user);
        if ($user['Usuario']['admin'] != '1'){
	        $this->loadModel('UsuarioPermiso');
	        $permisos = $this->UsuarioPermiso->findAllByUsuarioId($user_id);
	        $permisoAdelantada=0;
			$permisoNoAdelantada=0;
	    	foreach($permisos as $permiso){
               if ($permiso['UsuarioPermiso']['permiso_id']==130) {
               		$permisoAdelantada=1;
               		continue;
               }
    			if ($permiso['UsuarioPermiso']['permiso_id']==131) {
               		$permisoNoAdelantada=1;
               		continue;
               }

	        }
        }

      $this->set('permisoAdelantada',$permisoAdelantada);
       $this->set('permisoNoAdelantada',$permisoNoAdelantada);

        $this->set(array(

            'no_adelantadas' => $no_adelantadas,
            'adelantadas' => $adelantadas,

            'adelantadas_rubro' => $adelantadas_rubro,
            'no_adelantadas_rubro' => $no_adelantadas_rubro,

        ));

    }

	function ventas_extras_listado($mes,$ano,$tipo,$extra_rubro,$extra_subrubro){
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'ajax';

        $this->loadModel('Reserva');

       	$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(devolucion)' => $ano, 'MONTH(devolucion)' => $mes), 'order' => 'devolucion asc'));


        $this->loadModel('Usuario');
		 $user_id = $_SESSION['useridushuaia'];
        $user = $this->Usuario->find('first',array('conditions'=>array('Usuario.id'=>$_SESSION['useridushuaia'])));

		$permisoAdelantada=1;
		$permisoNoAdelantada=1;
		//print_r($user);
        if ($user['Usuario']['admin'] != '1'){
	        $this->loadModel('UsuarioPermiso');
	        $permisos = $this->UsuarioPermiso->findAllByUsuarioId($user_id);
	        $permisoAdelantada=0;
			$permisoNoAdelantada=0;
	    	foreach($permisos as $permiso){
               if ($permiso['UsuarioPermiso']['permiso_id']==130) {
               		$permisoAdelantada=1;
               		continue;
               }
    			if ($permiso['UsuarioPermiso']['permiso_id']==131) {
               		$permisoNoAdelantada=1;
               		continue;
               }

	        }
        }

      $this->set('permisoAdelantada',$permisoAdelantada);
       $this->set('permisoNoAdelantada',$permisoNoAdelantada);


        $reservasMostrar = array();





        $this->loadModel('ExtraRubro');
        $this->loadModel('ExtraSubrubro');
        $this->loadModel('Extra');
        $this->loadModel('ExtraVariable');
        if(count($reservas) > 0){

            foreach($reservas as $reserva){

                //verifico que la reserva no este cancelada
                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){


                    if(count($reserva['ReservaExtra']>0)){

                        foreach($reserva['ReservaExtra'] as $extra){
                        	/*print_r($extra);
	                        	echo "<br>";*/
                        	$mostrar=0;
                        	switch ($tipo) {
                        		case 1:
                        			if($extra['adelantada'] == 1){
                        				$mostrar=1;
                        			}
                        		break;
                        		case 2:
                        			if($extra['adelantada'] == 0){
                        				$mostrar=1;
                        			}
                        		break;
                        		default:
                        			$mostrar=1;
                        		break;
                        	}
                        	if(($extra['adelantada'] == 1)&&(!$permisoAdelantada)){
                        		$mostrar=0;
                        	}
                        	if(($extra['adelantada'] == 0)&&(!$permisoNoAdelantada)){
                        		$mostrar=0;
                        	}
                        	if ($mostrar) {
	                        	$filtroRubro = ($extra_rubro!='Seleccionar...')?array('Extra.extra_rubro_id'=>$extra_rubro):array(1=>1);
	                        	$filtroSubRubro = ($extra_subrubro!='Seleccionar...')?array('Extra.extra_subrubro_id'=>$extra_subrubro):array(1=>1);
	                        	if ($extra['extra_id']) {
	                        		$condicion=array(array('Extra.id'=>$extra['extra_id']),$filtroRubro,$filtroSubRubro);
	                        		$ex = $this->Extra->find('first',array('conditions' => $condicion));
	                        	}
	                        	else{
	                        		$filtroRubro = ($extra_rubro!='Seleccionar...')?array('ExtraVariable.extra_rubro_id'=>$extra_rubro):array(1=>1);
	                        		$condicion=array(array('ExtraVariable.id'=>$extra['extra_variable_id']),$filtroRubro);
	                        		$ex = $this->ExtraVariable->find('first',array('conditions' => $condicion));
	                        	}


	                        	/*print_r($ex);
	                        	echo "<br>";*/
	                        	if ($ex) {
	                        		$rubro = $this->ExtraRubro->findById($ex['Extra']['extra_rubro_id']);
		                        	$subrubro = $this->ExtraSubrubro->findById($ex['Extra']['extra_subrubro_id']);
		                        	$detalle=$ex['Extra']['detalle'];
		                        	if ($ex['ExtraVariable']) {
		                        		$rubro = $this->ExtraRubro->findById($ex['ExtraVariable']['extra_rubro_id']);
		                        		$detalle=$ex['ExtraVariable']['detalle'];
		                        	}

		                            $reservasMostrar[]=array('check_out'=>$reserva['Reserva']['devolucion'],'nro_reserva'=>$reserva['Reserva']['numero'],'titular'=>$reserva['Cliente']['nombre_apellido'],'unidad'=>$reserva['Unidad']['marca']." ".$reserva['Unidad']['modelo']." ".$reserva['Unidad']['patente'],'agregada'=>date('d/m/Y',strtotime($extra['agregada'])),'adelantada'=>($extra['adelantada'])?'SI':'NO','cantidad'=>$extra['cantidad'],'rubro'=>$rubro['ExtraRubro']['rubro'],'subrubro'=>$subrubro['ExtraSubrubro']['subrubro'],'detalle'=>$detalle,'monto'=>$extra['cantidad']*$extra['precio']);
	                        	}

                        	}

                        }
                    }
                }





	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0



        $this->set(array(
            'reservas' => $reservasMostrar
        ));

    }


	function ventas_ocupacion($ano,$desde,$hasta){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'ajax';

        $this->loadModel('Reserva');
        /*$this->loadModel('CobroTarjeta');
        $this->loadModel('CobroTarjetaPosnet');
        $this->loadModel('CobroTarjetaLote');
        $this->loadModel('Caja');
        $this->loadModel('Cuenta');
        $this->loadModel('CobroTransferencia');
        $this->loadModel('CobroCheque');*/
        $this->loadModel('Unidad');
        //$this->loadModel('ExtraRubro');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))),'creado >=' => $desde, 'creado <=' => $hasta)));

        }
        else
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))))));

        //$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(creado)' => $ano, 'MONTH(creado)' => 3), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado <=' => '2014-02-28', 'creado >=' => '2014-02-01'), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado' => '2014-03-31'), 'recursive' => 2));





        if(count($reservas) > 0){

            foreach($reservas as $reserva){


	                //print_r($reserva);

          			if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
	          			$date_parts = explode("/",$reserva['Reserva']['retiro']);
		        		$yy=$date_parts[2];
		        		if($yy==$ano){
								$mm=$date_parts[1];
			        			$dd=ltrim($date_parts[0], '0');
								$noches=$reserva['Reserva']['noches'];
								$nuevafecha = $yy.'-'.$mm.'-'.$dd;
								$mesretiro=$reserva['Reserva']['mesretiro'];
								//echo $reserva['Reserva']['id'].' - '.$nuevafecha.' - '.$reserva['Reserva']['devolucion'].' - '.$noches."<br>";
							}
							else{
								$yy=$ano;
								$mm='01';
								$dd=1;
			        			$nuevafecha = $yy.'-'.$mm.'-'.$dd;
								$mesretiro=1;
								$datetime1 = new \DateTime($nuevafecha);

								$date_parts2 = explode("/",$reserva['Reserva']['devolucion']);
								$yy2=$date_parts2[2];
								$mm2=$date_parts2[1];
			        			$dd2=ltrim($date_parts2[0], '0');
								$nuevafecha2 = $yy2.'-'.$mm2.'-'.$dd2;
								$datetime2 = new \DateTime($nuevafecha2);
								$interval = $datetime2->diff($datetime1);

								$noches=$interval->days;
								$noches=($noches==0)?1:$noches;
							}
		        		//echo "Dia 1:".$mm.'-'.$dd."-";

	            			//echo "Dia 1:".$mm.'-'.$dd." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$reserva['Reserva']['mes']." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$reserva['Reserva']['mes'].'-'.$dd]."<br>";
	            			if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd] ) {

	            				$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd]  = ($reserva['Unidad']['excluir']==0)?1:0;
		                		//$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$dd]  += ($reserva['Unidad']['excluir']==0)?1:0;
		                		$capacidad_ocupada[intval($mm)]  += ($reserva['Unidad']['excluir']==0)?1:0;
	            			}

		            		for ($i = 1; $i < $noches; $i++) {
								$nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
								$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
								$date_parts = explode("-",$nuevafecha);
								//echo "Resto:".$date_parts[1].'-'.$date_parts[2]." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$mesretiro." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]."<br>";
								if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]) {

									$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]  = ($reserva['Unidad']['excluir']==0)?1:0;
									//$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$date_parts[2]]  += ($reserva['Unidad']['excluir']==0)?1:0;
									$capacidad_ocupada[intval($date_parts[1])]  += ($reserva['Unidad']['excluir']==0)?1:0;
								}



							}
							//print_r($control_unidad_ocupada);

	                	//$capacidad_ocupada[$reserva['Reserva']['mes']]  += ($reserva['Unidad']['excluir']==0)?$reserva['Reserva']['noches']:0;
	                }


	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0

//        $lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('YEAR(fecha_cierre)' => $ano, 'acreditado_por !=' => 0)));
//        foreach($lotes as $lote){
//            $descuentos_tarjetas[$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos'];
//            $descuentos_tarjeta_posnets[$lote['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos']; print_r($descuentos_tarjeta_posnets);
//            $cobrado[$lote['CobroTarjetaLote']['mes_cierre']] -= $lote['CobroTarjetaLote']['descuentos'];
//            $ventas_netas[$reserva['Reserva']['mes']] -= $lote['CobroTarjetaLote']['descuentos'];
//        }

        $unidads = $this->Unidad->find('all');
        $q_unidads = count($unidads);
        $capacidad_total = array(0,0,0,0,0,0,0,0,0,0,0,0);
        foreach($unidads as $unidad){
        	//print_r($unidad);
        	if (($unidad['Unidad']['estado']==1 )&&($unidad['Unidad']['excluir']==0 )) {
        		for($i=1; $i<=12; $i++){
        			$dia = $ano.'-'.str_pad($i, 2, "0", STR_PAD_LEFT);
        			$date_parts = explode("/",$unidad['Unidad']['habilitacion']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$habilitacion = $yy.'-'.$mm;
        			$date_parts = explode("/",$unidad['Unidad']['baja']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$baja = $yy.'-'.$mm;
        			if (($habilitacion<=$dia)&&($baja>=$dia)) {
        				$capacidad_total[$i]+= 30;
        			}

        		}

        	}
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        //$capacidad_total = $capacidad_total * 30;

        $this->set(array(
            'ano' => $ano,
            'capacidad_total' => $capacidad_total,
            'capacidad_ocupada' => $capacidad_ocupada

        ));

    }

	function ventas_ocupacion_x_categoria($ano,$desde,$hasta){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'ajax';

        $this->loadModel('Reserva');

        $this->loadModel('Unidad');
        $this->loadModel('Categoria');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))),'creado >=' => $desde, 'creado <=' => $hasta)));

        }
        else
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))))));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(creado)' => $ano, 'MONTH(creado)' => 3), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado <=' => '2014-02-28', 'creado >=' => '2014-02-01'), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado' => '2014-03-31'), 'recursive' => 2));





        if(count($reservas) > 0){

            foreach($reservas as $reserva){


	                //print_r($reserva);

          			if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
          				$date_parts = explode("/",$reserva['Reserva']['retiro']);
		        		$yy=$date_parts[2];
		        		if($yy==$ano){
								$mm=$date_parts[1];
			        			$dd=ltrim($date_parts[0], '0');
								$noches=$reserva['Reserva']['noches'];
								$nuevafecha = $yy.'-'.$mm.'-'.$dd;
								$mesretiro=$reserva['Reserva']['mesretiro'];
								//echo $reserva['Reserva']['id'].' - '.$nuevafecha.' - '.$reserva['Reserva']['devolucion'].' - '.$noches."<br>";
							}
							else{
								$yy=$ano;
								$mm='01';
								$dd=1;
			        			$nuevafecha = $yy.'-'.$mm.'-'.$dd;
								$mesretiro=1;
								$datetime1 = new \DateTime($nuevafecha);

								$date_parts2 = explode("/",$reserva['Reserva']['devolucion']);
								$yy2=$date_parts2[2];
								$mm2=$date_parts2[1];
			        			$dd2=ltrim($date_parts2[0], '0');
								$nuevafecha2 = $yy2.'-'.$mm2.'-'.$dd2;
								$datetime2 = new \DateTime($nuevafecha2);
								$interval = $datetime2->diff($datetime1);

								$noches=$interval->days;
								$noches=($noches==0)?1:$noches;
							}
		        		//echo "Dia 1:".$mm.'-'.$dd."-";

	            			//echo "Dia 1:".$mm.'-'.$dd." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$reserva['Reserva']['mes']." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$reserva['Reserva']['mes'].'-'.$dd]."<br>";
	            			if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd] ) {

	            				$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd]  = ($reserva['Unidad']['excluir']==0)?1:0;
		                		//$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$dd]  += ($reserva['Unidad']['excluir']==0)?1:0;
		                		$capacidad_ocupada[$reserva['Unidad']['categoria_id']][intval($mm)]  += ($reserva['Unidad']['excluir']==0)?1:0;
	            			}

		            		for ($i = 1; $i < $noches; $i++) {
								$nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
								$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
								$date_parts = explode("-",$nuevafecha);
								//echo "Resto:".$date_parts[1].'-'.$date_parts[2]." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$mesretiro." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]."<br>";
								if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]) {

									$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]  = ($reserva['Unidad']['excluir']==0)?1:0;
									//$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$date_parts[2]]  += ($reserva['Unidad']['excluir']==0)?1:0;
									$capacidad_ocupada[$reserva['Unidad']['categoria_id']][intval($date_parts[1])]  += ($reserva['Unidad']['excluir']==0)?1:0;
								}



							}


	                	//$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Reserva']['mes']]  += ($reserva['Unidad']['excluir']==0)?$reserva['Reserva']['noches']:0;
	                }


	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0

//        $lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('YEAR(fecha_cierre)' => $ano, 'acreditado_por !=' => 0)));
//        foreach($lotes as $lote){
//            $descuentos_tarjetas[$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos'];
//            $descuentos_tarjeta_posnets[$lote['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$lote['CobroTarjetaLote']['mes_cierre']] += $lote['CobroTarjetaLote']['descuentos']; print_r($descuentos_tarjeta_posnets);
//            $cobrado[$lote['CobroTarjetaLote']['mes_cierre']] -= $lote['CobroTarjetaLote']['descuentos'];
//            $ventas_netas[$reserva['Reserva']['mes']] -= $lote['CobroTarjetaLote']['descuentos'];
//        }
        //print_r($capacidad_ocupada);
        $unidads = $this->Unidad->find('all');
        $q_unidads = count($unidads);
        $capacidad_total = array();
        $unidad_total = array();
        foreach($unidads as $unidad){
        	//print_r($unidad);
        	if (($unidad['Unidad']['estado']==1 )&&($unidad['Unidad']['excluir']==0 )) {
        		for($i=1; $i<=12; $i++){
        			$dia = $ano.'-'.str_pad($i, 2, "0", STR_PAD_LEFT);
        			$date_parts = explode("/",$unidad['Unidad']['habilitacion']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$habilitacion = $yy.'-'.$mm;
        			$date_parts = explode("/",$unidad['Unidad']['baja']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$baja = $yy.'-'.$mm;
        			if (($habilitacion<=$dia)&&($baja>=$dia)) {
        				$capacidad_total[$unidad['Unidad']['categoria_id']][$i]+= 30;
        				$unidad_total[$unidad['Unidad']['categoria_id']][$i]++;
        			}

        		}

        	}
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        //$capacidad_total = $capacidad_total * 30;
        //print_r($capacidad_total);

		$categorias = $this->Categoria->find('all');
        $q_categorias = count($categorias);
        $cat = array();
        foreach($categorias as $categoria){
        	//print_r($categoria);
        	if (($categoria['Categoria']['activa']==1 )) {
        		$cat[]=$categoria;

        	}
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        $this->set(array(
        	'ano' => $ano,
            'categorias' => $cat,
            'capacidad_total' => $capacidad_total,
        	'unidad_total' => $unidad_total,
            'capacidad_ocupada' => $capacidad_ocupada

        ));

    }

    function ventas_financiero($ano,$desde,$hasta){
        //error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('Caja');
        $this->loadModel('Cuenta');
        $this->loadModel('CobroTarjetaPosnet');
        $this->loadModel('CobroEfectivo');
        $this->loadModel('CobroCheque');
        $this->loadModel('CobroTransferencia');
        $this->loadModel('CobroTarjetaLote');
        $this->loadModel('ReservaDevolucion');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        for($i=1; $i<=12; $i++){
            $cobro_neto[$i] = 0;
        }

        //busoc los cobros en efectivo del ano
        $cajas = $this->Caja->find('list',array('conditions' => array('visible_en_informe' => 1)));
        $this->set('cajas',$cajas);
        foreach($cajas as $id => $nombre){
            for($i=1; $i<=12; $i++){
                $cobro_caja[$id][$i] = 0;
            }
        }

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$date_parts = explode("-",$desde);

        	$ano=$date_parts[0];
        	$cobro_efectivos = $this->CobroEfectivo->find('all',array('conditions' => array('ReservaCobro.fecha >=' => $desde, 'ReservaCobro.fecha <=' => $hasta), 'recursive' => 2));
        }
        else
        	$cobro_efectivos = $this->CobroEfectivo->find('all',array('conditions' => array('YEAR(ReservaCobro.fecha)' => $ano)));




        if(count($cobro_efectivos)>0){
            foreach($cobro_efectivos as $ce){
				$caja_visible=$this->Caja->findById($ce['CobroEfectivo']['caja_id']);
                if ($caja_visible['Caja']['visible_en_informe']) {
					$cobro_neto[$ce['ReservaCobro']['mes']] += $ce['ReservaCobro']['monto_cobrado'];
					$cobro_caja[$ce['CobroEfectivo']['caja_id']][$ce['ReservaCobro']['mes']] += $ce['CobroEfectivo']['monto_neto'];
				}
            }
        }

        //busco los cobros con cheques del ano
        for($i=1; $i<=12; $i++){
            $cobro_cheque['COMUN'][$i] = 0;
            $cobro_cheque['DIFERIDO'][$i] = 0;
        }

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {

        	$cobro_cheques = $this->CobroCheque->find('all',array('conditions' => array('OR' => array(array('fecha_acreditado >=' => $desde, 'fecha_acreditado <=' => $hasta), array('asociado_a_pagos_fecha >=' => $desde, 'asociado_a_pagos_fecha <=' => $hasta)))));
        }
        else
        	$cobro_cheques = $this->CobroCheque->find('all',array('conditions' => array('OR' => array('YEAR(fecha_acreditado)' => $ano, 'YEAR(asociado_a_pagos_fecha)' => $ano))));


        if(count($cobro_cheques) >0){
            foreach($cobro_cheques as $cc){
                if($cc['CobroCheque']['asociado_a_pagos']){
                    $cobro_neto[$cc['CobroCheque']['mes_asociado_a_pagos']] += $cc['CobroCheque']['total'];
                    $cobro_cheque[$cc['CobroCheque']['tipo']][$cc['CobroCheque']['mes_asociado_a_pagos']] += $cc['CobroCheque']['total'];
                }else{
                    $cobro_neto[$cc['CobroCheque']['mes_acreditado']] += $cc['CobroCheque']['total'];
                    $cobro_cheque[$cc['CobroCheque']['tipo']][$cc['CobroCheque']['mes_acreditado']] += $cc['CobroCheque']['total'];
                }

            }
        }

        //busco los cobros con transferencias del ano
        $cuentas = $this->Cuenta->find('list',array('conditions' => array('visible_en_informe' => 1)));
        $this->set('cuentas',$cuentas);
        foreach($cuentas as $id => $nombre){
            for($i=1; $i<=12; $i++){
                $cobro_cuenta[$id][$i] = 0;
            }
        }

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$cobro_transferencias = $this->CobroTransferencia->find('all',array('conditions' => array('fecha_acreditado >=' => $desde, 'fecha_acreditado <=' => $hasta), 'recursive' => 2));

        }
        else
        	$cobro_transferencias = $this->CobroTransferencia->find('all',array('conditions' => array('YEAR(fecha_acreditado)' => $ano)));



        if(count($cobro_transferencias) > 0){
            foreach($cobro_transferencias as $ct){
				$cuenta_visible=$this->Cuenta->findById($ct['CobroTransferencia']['cuenta_id']);
                if ($cuenta_visible['Cuenta']['visible_en_informe']) {
					$cobro_neto[$ct['CobroTransferencia']['mes_acreditado']] += $ct['CobroTransferencia']['total'];
					$cobro_cuenta[$ct['CobroTransferencia']['cuenta_id']][$ct['CobroTransferencia']['mes_acreditado']] += $ct['CobroTransferencia']['total'];
				}
            }
        }

        //busco los cobros con tarjeta
        $cobro_posnets = $this->CobroTarjetaPosnet->find('list');
        $this->set('posnets',$cobro_posnets);
        foreach($cobro_posnets as $id => $posnet){
            for($i=1; $i<=12; $i++){
                $cobro_posnet[$id][$i] = 0;
            }
        }

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$cobro_tarjeta_lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('fecha_acreditacion >=' => $desde, 'fecha_acreditacion <=' => $hasta), 'recursive' => 2));

        }
        else
        	$cobro_tarjeta_lotes = $this->CobroTarjetaLote->find('all',array('conditions' => array('YEAR(fecha_acreditacion)' => $ano)));
        if(count($cobro_tarjeta_lotes) >0){
            foreach($cobro_tarjeta_lotes as $ctl){
                $cobro_neto[$ctl['CobroTarjetaLote']['mes_acreditacion']] += $ctl['CobroTarjetaLote']['monto_total'] - $ctl['CobroTarjetaLote']['descuentos'];
                $cobro_posnet[$ctl['CobroTarjetaTipo']['cobro_tarjeta_posnet_id']][$ctl['CobroTarjetaLote']['mes_acreditacion']] += $ctl['CobroTarjetaLote']['monto_total'] - $ctl['CobroTarjetaLote']['descuentos'];
            }
        }

        //agrego las devoluciones
        for($i=1; $i<=12; $i++){
            $devoluciones_pago['EFECTIVO'][$i] = 0;
            $devoluciones_pago['CHEQUE'][$i] = 0;
            $devoluciones_pago['TRANSFERENCIA'][$i] = 0;
        }
    	if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$devoluciones = $this->ReservaDevolucion->find('all',array('conditions' => array('fecha >=' => $desde, 'fecha <=' => $hasta), 'recursive' => 2));

        }
        else
        	$devoluciones = $this->ReservaDevolucion->find('all',array('conditions' => array('YEAR(fecha)' => $ano)));
        if(count($devoluciones) >0){
            foreach($devoluciones as $devolucion){
                $cobro_neto[$devolucion['ReservaDevolucion']['mes']] -= $devolucion['ReservaDevolucion']['monto'];
                $devoluciones_pago[$devolucion['ReservaDevolucion']['forma_pago']][$devolucion['ReservaDevolucion']['mes']]  -= $devolucion['ReservaDevolucion']['monto'];
            }
        }

        $this->set(array(
            'ano' => $ano,
            'cobro_neto' => $cobro_neto,
            'cobro_caja' => $cobro_caja,
            'cobro_cuenta' => $cobro_cuenta,
            'cobro_posnet' => $cobro_posnet,
            'cobro_cheque' => $cobro_cheque,
            'devoluciones_pago' => $devoluciones_pago
        ));
    }

    function ventas_economico_financiero($mes,$ano){
        error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('Reserva');
        $this->loadModel('CobroTarjeta');
        $this->loadModel('CobroEfectivo');
        $this->loadModel('CobroTransferencia');
        $this->loadModel('CobroCheque');

        $cobrado = array();
        $pendiente_cobro = 0;
        $ventas_netas = 0;

        $meses = array('01'=>'Enero', '02'=> 'Febrero','03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        $reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(devolucion)' => $ano, 'MONTH(devolucion)' => $mes), 'recursive' => 1));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('devolucion <=' => '2014-03-31', 'devolucion >=' => '2014-03-01'), 'recursive' => 2));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('devolucion' => '2014-03-31'), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('numero' => '394'), 'recursive' => 2));
        if(count($reservas) > 0){

            foreach($reservas as $reserva){
            	$veAux = 0;
                if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
                    $ventas_netas += $reserva['Reserva']['total_estadia'];
					$veAux += $reserva['Reserva']['total_estadia'];
                    //if(count($reserva['ReservaExtra']>0)){
                        foreach($reserva['ReservaExtra'] as $extra){
                            $ventas_netas += $extra['cantidad'] * $extra['precio'];
                            $veAux += $extra['cantidad'] * $extra['precio'];
                        }
                    //}
                }
                $cobroCancelado = 0;
                if(count($reserva['ReservaCobro']) > 0){

                    foreach($reserva['ReservaCobro'] as $cobro){

                    	if($reserva['Reserva']['estado'] != 2){
	                        if($cobro['tipo'] == 'DESCUENTO'){
	                            $ventas_netas -= $cobro['monto_neto'];
	                            $veAux -= $cobro['monto_neto'];
	                        }else{

	                            /*if($reserva['Reserva']['estado'] == 2){
	                                $ventas_netas += $cobro['monto_neto'];
	                            }*/

	                            $ventas_netas += $cobro['monto_cobrado'] - $cobro['monto_neto'];
	                            $veAux += $cobro['monto_cobrado'] - $cobro['monto_neto'];

	                        }
                    	}
                        switch($cobro['tipo']){
                            case 'TARJETA':
                                $cobro_tarjeta = $this->CobroTarjeta->find('first',array('conditions'=>array('reserva_cobro_id'=>$cobro['id'])));

                                //echo $cobro_tarjeta['CobroTarjeta']['total']."<br>";
                                if($cobro_tarjeta['CobroTarjetaLote']['acreditado_por'] != 0){
                                	if($reserva['Reserva']['estado'] != 2){
                                		$ventas_netas -= $cobro_tarjeta['CobroTarjeta']['descuento_lote'];
                                		$veAux -= $cobro_tarjeta['CobroTarjeta']['descuento_lote'];
                                	}
                                    $cobrado[$cobro_tarjeta['CobroTarjetaLote']['ano_mes_acreditado']] += $cobro_tarjeta['CobroTarjeta']['total'];
                                    $cobroCancelado += $cobro_tarjeta['CobroTarjeta']['total'];
                                    //$ventas_netas += $cobro_tarjeta['CobroTarjeta']['interes'];
                                }else{
                                    $pendiente_cobro += $cobro_tarjeta['CobroTarjeta']['total'];
                                }
                                break;

                            case 'EFECTIVO':
                            	$cobro_efectivo = $this->CobroEfectivo->find('first',array('conditions'=>array('reserva_cobro_id'=>$cobro['id'])));
                                $cobrado[$cobro['ano_mes']] += $cobro_efectivo['CobroEfectivo']['monto_neto'];
                                $cobroCancelado += $cobro_efectivo['CobroEfectivo']['monto_neto'];
                                break;


                             case 'TRANSFERENCIA':
                            	$cobro_transferencia = $this->CobroTransferencia->find('first',array('conditions'=>array('reserva_cobro_id'=>$cobro['id'])));
                                if($cobro_transferencia['CobroTransferencia']['acreditado']){
                                    $cobrado[$cobro_transferencia['CobroTransferencia']['ano_mes_acreditado']] += $cobro_transferencia['CobroTransferencia']['total'];
                                    $cobroCancelado += $cobro_transferencia['CobroTransferencia']['total'];
                                    //$ventas_netas += $cobro_transferencia['interes'];
                                }else{
                                    $pendiente_cobro += $cobro_transferencia['CobroTransferencia']['total'];
                                }
                                break;

                           case 'CHEQUE':
                            	$cobro_cheque = $this->CobroCheque->find('first',array('conditions'=>array('reserva_cobro_id'=>$cobro['id'])));
                                if($cobro_cheque['CobroCheque']['acreditado'] ){
                                    $cobrado[$cobro_cheque['CobroCheque']['ano_mes_acreditado']] += $cobro_cheque['CobroCheque']['total'];
                                    $cobroCancelado += $cobro_cheque['CobroCheque']['total'];
                                    //$ventas_netas += $cobro_cheque['interes'];
                                }elseif($cobro_cheque['CobroCheque']['asociado_a_pagos']){
                                    $cobrado[$cobro_cheque['CobroCheque']['ano_mes_asociado_a_pagos']] += $cobro_cheque['CobroCheque']['total'];
                                    $cobroCancelado += $cobro_cheque['CobroCheque']['total'];
                                    //$ventas_netas += $cobro_cheque['interes'];
                                }else{
                                    $pendiente_cobro += $cobro_cheque['CobroCheque']['total'];
                                }
                                break;
                        }
                    }
                }

                if(count($reserva['ReservaDevolucion'])>0){
                    foreach($reserva['ReservaDevolucion'] as $devolucion){
                        //$ventas_netas -= $devolucion['monto'];
                        $cobrado[$devolucion['ano_mes']] -= $devolucion['monto'];
                        $cobroCancelado -= $devolucion['monto'];
                    }
                }
            	if($reserva['Reserva']['estado'] == 2){
                	$ventas_netas += $cobroCancelado;
                	$veAux += $cobroCancelado;
                	//echo $reserva['Reserva']['numero'].' - '.$cobroCancelado."<br>";
                }
            $total = $veAux - $cobroCancelado;
			//echo $reserva['Reserva']['numero'].' - '.$veAux.'-'.$cobroCancelado.'='.$total."<br>";
            }// foreach reservas
        }// if count reservas > 0

        ksort($cobrado);

        $this->set(array(
            'ano' => $ano,
            'mes' => $mes,
            'ventas_netas' => $ventas_netas,
            'cobrado' => $cobrado,
            'pendiente_cobro' => $pendiente_cobro
        ));
    }

	function ventas_ocupacion_porcentaje($ano,$desde,$hasta){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'ajax';

        $this->loadModel('Reserva');

        $this->loadModel('Unidad');
        $this->loadModel('Categoria');

        $meses = array(1=>'Enero', 2=> 'Febrero', 3=> 'Marzo', 4=> 'Abril', 5=> 'Mayo', 6=> 'Junio', 7=> 'Julio', 8=> 'Agosto', 9=> 'Septiembre', 10=>'Octubre', 11=> 'Noviembre', 12=>'Diciembre');
        $this->set('meses',$meses);

        if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))),'creado >=' => $desde, 'creado <=' => $hasta)));

        }
        else
        	$reservas = $this->Reserva->find('all',array('conditions' => array('OR' =>array(array('YEAR(devolucion)' => $ano),array('YEAR(retiro)' => $ano,'YEAR(devolucion)' => intval($ano+1))))));
        //$reservas = $this->Reserva->find('all',array('conditions' => array('YEAR(creado)' => $ano, 'MONTH(creado)' => 3), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado <=' => '2014-02-28', 'creado >=' => '2014-02-01'), 'recursive' => 2));
		//$reservas = $this->Reserva->find('all',array('conditions' => array('creado' => '2014-03-31'), 'recursive' => 2));



       $control_unidad_ocupada = array();

        if(count($reservas) > 0){

            foreach($reservas as $reserva){
	              	$date_parts = explode("/",$reserva['Reserva']['retiro']);
	        		$yy=$date_parts[2];
	        		if($yy==$ano){
						$mm=$date_parts[1];
						$dd=ltrim($date_parts[0], '0');
						$noches=$reserva['Reserva']['noches'];
						$nuevafecha = $yy.'-'.$mm.'-'.$dd;
						$mesretiro=$reserva['Reserva']['mesretiro'];
						//echo $reserva['Reserva']['id'].' - '.$nuevafecha.' - '.$reserva['Reserva']['devolucion'].' - '.$noches."<br>";
					}
					else{
						$yy=$ano;
						$mm='01';
						$dd=1;
						$nuevafecha = $yy.'-'.$mm.'-'.$dd;
						$mesretiro=1;
						$datetime1 = new \DateTime($nuevafecha);

						$date_parts2 = explode("/",$reserva['Reserva']['devolucion']);
						$yy2=$date_parts2[2];
						$mm2=$date_parts2[1];
						$dd2=ltrim($date_parts2[0], '0');
						$nuevafecha2 = $yy2.'-'.$mm2.'-'.$dd2;
						$datetime2 = new \DateTime($nuevafecha2);
						$interval = $datetime2->diff($datetime1);

						$noches=$interval->days;
						$noches=($noches==0)?1:$noches;
					}
	        		//echo "Dia 1:".$mm.'-'.$dd."-";
            		if(($reserva['Reserva']['estado'] != 2)&&($reserva['Reserva']['estado'] != 3)){
            			//echo "Dia 1:".$mm.'-'.$dd." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$reserva['Reserva']['mes']." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$reserva['Reserva']['mes'].'-'.$dd]."<br>";
            			if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd] ) {

            				$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$dd]  = ($reserva['Unidad']['excluir']==0)?1:0;
	                		$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$dd]  += ($reserva['Unidad']['excluir']==0)?1:0;
	                		$capacidad_ocupada_unidad[$mesretiro.'-'.$dd]  += ($reserva['Unidad']['excluir']==0)?1:0;
            			}

	            		for ($i = 1; $i < $noches; $i++) {
							$nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
							$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
							$date_parts = explode("-",$nuevafecha);
							//echo "Resto:".$date_parts[1].'-'.$date_parts[2]." Unidad: ".$reserva['Unidad']['id']." Categoria: ".$reserva['Unidad']['categoria_id']." Mes reserva: ".$reserva['Reserva']['mesretiro']." Arreglo: ".$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$reserva['Reserva']['mesretiro'].'-'.$date_parts[2]]."<br>";
							if (!$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]) {

								$control_unidad_ocupada[$reserva['Unidad']['categoria_id']][$reserva['Unidad']['id']][$mesretiro.'-'.$date_parts[2]]  = ($reserva['Unidad']['excluir']==0)?1:0;
								$capacidad_ocupada[$reserva['Unidad']['categoria_id']][$mesretiro.'-'.$date_parts[2]]  += ($reserva['Unidad']['excluir']==0)?1:0;
								$capacidad_ocupada_unidad[$mesretiro.'-'.$date_parts[2]]  += ($reserva['Unidad']['excluir']==0)?1:0;
							}



						}
						//print_r($control_unidad_ocupada);
	                }








	             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
            }// foreach reservas
        }// if count reservas > 0


        //print_r($capacidad_ocupada_unidad);
        $unidads = $this->Unidad->find('all');
        $q_unidads = count($unidads);
        $capacidad_total = array();
        $unidad_total = array();
        $porcentaje_ocupado = array();
        foreach($unidads as $unidad){
        	//print_r($unidad);
        	if (($unidad['Unidad']['estado']==1 )&&($unidad['Unidad']['excluir']==0 )) {
        		for($i=1; $i<=12; $i++){
        			$porcentaje_ocupado_unidad[$i]['100']=0;
        			$porcentaje_ocupado_unidad[$i]['75']=0;
        			$porcentaje_ocupado_unidad[$i]['50']=0;
        			$porcentaje_ocupado_unidad[$i]['25']=0;
        			$porcentaje_ocupado_unidad[$i]['0']=0;
        			$porcentaje_ocupado[$unidad['Unidad']['categoria_id']][$i]['100']=0;
        			$porcentaje_ocupado[$unidad['Unidad']['categoria_id']][$i]['75']=0;
        			$porcentaje_ocupado[$unidad['Unidad']['categoria_id']][$i]['50']=0;
        			$porcentaje_ocupado[$unidad['Unidad']['categoria_id']][$i]['25']=0;
        			$porcentaje_ocupado[$unidad['Unidad']['categoria_id']][$i]['0']=0;
        			$dia = $ano.'-'.str_pad($i, 2, "0", STR_PAD_LEFT);
        			$date_parts = explode("/",$unidad['Unidad']['habilitacion']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$habilitacion = $yy.'-'.$mm;
        			$date_parts = explode("/",$unidad['Unidad']['baja']);
        			$yy=$date_parts[2];
        			$mm=$date_parts[1];
        			$baja = $yy.'-'.$mm;
        			if (($habilitacion<=$dia)&&($baja>=$dia)) {
        				$capacidad_total[$unidad['Unidad']['categoria_id']][$i]+= 30;
        				$unidad_total[$unidad['Unidad']['categoria_id']][$i]++;
        				$unidad_tot[$i]++;
        			}

        		}

        	}
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        //$capacidad_total = $capacidad_total * 30;
        //print_r($capacidad_ocupada);
        //print_r($unidad_total);

        foreach ($capacidad_ocupada as $clave => $valor)  {

        	foreach ($valor as $key => $value) {
        		$arrayDiaMes = explode('-',$key);
        		$mes = $arrayDiaMes[0];
        		$porcentaje = round(($value*100)/$unidad_total[$clave][$mes],0);
        		switch (true) {
        			case ($porcentaje==100):
        				$porcentaje_ocupado[$clave][$mes]['100']++;
        			break;
        			case ($porcentaje >= 75 && $porcentaje < 100):
        				$porcentaje_ocupado[$clave][$mes]['75']++;
        			break;
        			case ($porcentaje >= 50 && $porcentaje < 75):
        				$porcentaje_ocupado[$clave][$mes]['50']++;
        			break;
        			case ($porcentaje >= 25 && $porcentaje < 50):
        				$porcentaje_ocupado[$clave][$mes]['25']++;
        			break;
        			case ($porcentaje >= 0 && $porcentaje < 25):
        				$porcentaje_ocupado[$clave][$mes]['0']++;
        			break;
        		}


        	}


        }

	foreach ($capacidad_ocupada_unidad as $key => $value)  {


        		$arrayDiaMes = explode('-',$key);
        		$mes = $arrayDiaMes[0];
        		$porcentaje = round(($value*100)/$unidad_tot[$mes],0);
        		switch (true) {
        			case ($porcentaje==100):
        				$porcentaje_ocupado_unidad[$mes]['100']++;
        			break;
        			case ($porcentaje >= 75 && $porcentaje < 100):
        				$porcentaje_ocupado_unidad[$mes]['75']++;
        			break;
        			case ($porcentaje >= 50 && $porcentaje < 75):
        				$porcentaje_ocupado_unidad[$mes]['50']++;
        			break;
        			case ($porcentaje >= 25 && $porcentaje < 50):
        				$porcentaje_ocupado_unidad[$mes]['25']++;
        			break;
        			case ($porcentaje >= 0 && $porcentaje < 25):
        				$porcentaje_ocupado_unidad[$mes]['0']++;
        			break;
        		}





        }


        //print_r($porcentaje_ocupado_unidad);

		$categorias = $this->Categoria->find('all');
        $q_categorias = count($categorias);
        $cat = array();
        foreach($categorias as $categoria){
        	//print_r($categoria);
        	if (($categoria['Categoria']['activa']==1 )) {
        		$cat[]=$categoria;

        	}
            //$capacidad_total += $apartamento['Apartamento']['capacidad'] * 30;
        }
        $this->set(array(
        	'ano' => $ano,
            'categorias' => $cat,
            'capacidad_total' => $capacidad_total,
        	'porcentaje_ocupado' => $porcentaje_ocupado,
        	'porcentaje_ocupado_unidad' => $porcentaje_ocupado_unidad,
        	'unidad_total' => $unidad_total,
        	'unidad_tot' => $unidad_tot,
            'capacidad_ocupada' => $capacidad_ocupada

        ));

    }

	function iva_compras($mes,$ano, $orden){
         //error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('Gasto');
       $this->loadModel('CondicionImpositiva');
       $this->loadModel('JurisdiccionInscripcion');


        //$gastos = $this->Gasto->find('all',array('conditions' => array('YEAR(fecha_vencimiento)' => $ano, 'MONTH(fecha_vencimiento)' => $mes, factura_tipo => array('A','M')), 'order' => $orden.' asc', 'recursive' => 1));
        $gastos = $this->Gasto->find('all',array('conditions' => array('YEAR(fecha_vencimiento)' => $ano, 'MONTH(fecha_vencimiento)' => $mes, factura_orden => 'B'), 'order' => $orden.' asc', 'recursive' => 1));

         $gastosMostrar = array();
        if(count($gastos) > 0){

            foreach($gastos as $gasto){

            	$facturaOrden = ($gasto['Gasto']['factura_orden']=='B')?'0001':'0002';
             	$factura = $gasto['Gasto']['factura_tipo'].$gasto['Gasto']['factura_punto_venta'].$gasto['Gasto']['factura_nro'];
	            $condicionImpositiva='';
	            $jurisdiccionInscripcion='';
             	if(isset($gasto['Proveedor']['id'])){
	                $proveedor = $gasto['Proveedor']['nombre'];
	                $condicion=$this->CondicionImpositiva->findById($gasto['Proveedor']['condicion_impositiva_id']);
	                $condicionImpositiva=$condicion['CondicionImpositiva']['nombre'];
	                $jurisdiccion=$this->JurisdiccionInscripcion->findById($gasto['Proveedor']['jurisdiccion_inscripcion_id']);
	                $jurisdiccionInscripcion=$jurisdiccion['JurisdiccionInscripcion']['nombre'];
             		$cuit=$gasto['Proveedor']['cuit'];
	                $razon=$gasto['Proveedor']['razon'];
	            }else{
                	$proveedor = $gasto['Gasto']['proveedor'];

	                $cuit=$gasto['Gasto']['cuit'];
	                $razon=$gasto['Gasto']['razon'];
	           		$this->loadModel('Proveedor');
	                $prov = $this->Proveedor->find('first',array('conditions'=>array('Proveedor.cuit'=>$cuit)));
	                $condicion=$this->CondicionImpositiva->findById($prov['Proveedor']['condicion_impositiva_id']);
	                $condicionImpositiva=$condicion['CondicionImpositiva']['nombre'];
	                $jurisdiccion=$this->JurisdiccionInscripcion->findById($prov['Proveedor']['jurisdiccion_inscripcion_id']);
	                $jurisdiccionInscripcion=$jurisdiccion['JurisdiccionInscripcion']['nombre'];
            	}

	            $date_parts = explode("/",$gasto['Gasto']['fecha_vencimiento']);

        		$yy=$date_parts[2];
        		$mm=$date_parts[1];
        		$dd=$date_parts[0];
        		$nuevafecha = $yy.$mm.$dd;
            	$gastosMostrar[]=array('fecha'=>$nuevafecha,'fechaMostrar'=>$gasto['Gasto']['fecha_vencimiento'],'origen'=>$gasto['Gasto']['origen'],'factura'=>$factura,'proveedor'=>$proveedor,'razon'=>$razon,'cuit'=>$cuit,'condicionImpositiva'=>$condicionImpositiva,'jurisdiccionInscripcion'=>$jurisdiccionInscripcion,'iva_27'=>$gasto['Gasto']['iva_27'],'iva_21'=>$gasto['Gasto']['iva_21'],'iva_10_5'=>$gasto['Gasto']['iva_10_5'],'otra_alicuota'=>$gasto['Gasto']['otra_alicuota'],'perc_iva'=>$gasto['Gasto']['perc_iva'],'perc_iibb_bsas'=>$gasto['Gasto']['perc_iibb_bsas'],'perc_iibb_caba'=>$gasto['Gasto']['perc_iibb_caba'],'exento'=>$gasto['Gasto']['exento'],'monto'=>$gasto['Gasto']['monto']);
            }
        }
        $this->array_sort_by($gastosMostrar, $orden);
        $this->set(array(
            'gastos' => $gastosMostrar
        ));
    }

	function exportarIvaCompra($mes,$ano, $orden){
         //error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('Gasto');
        $this->loadModel('CondicionImpositiva');
       $this->loadModel('JurisdiccionInscripcion');

        //$gastos = $this->Gasto->find('all',array('conditions' => array('YEAR(fecha_vencimiento)' => $ano, 'MONTH(fecha_vencimiento)' => $mes, factura_tipo => array('A','M')), 'order' => $orden.' asc', 'recursive' => 1));
        $gastos = $this->Gasto->find('all',array('conditions' => array('YEAR(fecha_vencimiento)' => $ano, 'MONTH(fecha_vencimiento)' => $mes, factura_orden => 'B'), 'order' => $orden.' asc', 'recursive' => 1));
    	$this->autoRender = false;
  		$this->layout = false;


		$fileName = "Iva_Compras_".$mes.'_'.$ano.".xls";
		//$fileName = "bookreport_".date("d-m-y:h:s").".csv";
		$headerRow = array("Fecha comprobante","As.Tipo","Factura","Proveedor","Tercero","CUIT","Cond.","Jurisd.","Neto","IVA 10.5%","IVA 21%","IVA 27%","Otra al�cuota","Percepci�n IVA","Perc. IIBB Bs.As.","Perc. IIBB CABA","Exento","Total Factura");

		$data = array();
		$total27=0;
	    $total21=0;
	    $total10_5=0;
	    $totalOtraAlicuota=0;
	    $totalperc_iva=0;
	    $totalperc_iibb_bsas=0;
	    $totalperc_iibb_caba=0;
	    $totalexento=0;
	    $totalMonto=0;
	    $creditoFiscal = 0;
	     $gastosMostrar = array();
	    foreach($gastos as $gasto){
             $total27 +=$gasto['Gasto']['iva_27'];
             $total21 +=$gasto['Gasto']['iva_21'];
             $total10_5 +=$gasto['Gasto']['iva_10_5'];
             $totalOtraAlicuota +=$gasto['Gasto']['otra_alicuota'];
             $totalperc_iva +=$gasto['Gasto']['perc_iva'];
             $totalperc_iibb_bsas +=$gasto['Gasto']['perc_iibb_bsas'];
             $totalperc_iibb_caba +=$gasto['Gasto']['perc_iibb_caba'];
             $totalexento +=$gasto['Gasto']['exento'];
             $totalMonto +=$gasto['Gasto']['monto'];
             $facturaOrden = ($gasto['Gasto']['factura_orden']=='B')?'0001':'0002';
             $factura = $gasto['Gasto']['factura_tipo'].$gasto['Gasto']['factura_punto_venta'].$gasto['Gasto']['factura_nro'];
	         $creditoFiscal +=$gasto['Gasto']['monto']-$gasto['Gasto']['iva_27']-$gasto['Gasto']['iva_21']-$gasto['Gasto']['iva_10_5']-$gasto['Gasto']['otra_alicuota']-$gasto['Gasto']['perc_iva']-$gasto['Gasto']['perc_iibb_bsas']-$gasto['Gasto']['perc_iibb_caba']-$gasto['Gasto']['exento'];


	    	 $condicionImpositiva='';
	            $jurisdiccionInscripcion='';
             	if(isset($gasto['Proveedor']['id'])){
	                $proveedor = $gasto['Proveedor']['nombre'];
	                $condicion=$this->CondicionImpositiva->findById($gasto['Proveedor']['condicion_impositiva_id']);
	                $condicionImpositiva=$condicion['CondicionImpositiva']['nombre'];
	                $jurisdiccion=$this->JurisdiccionInscripcion->findById($gasto['Proveedor']['jurisdiccion_inscripcion_id']);
	                $jurisdiccionInscripcion=$jurisdiccion['JurisdiccionInscripcion']['nombre'];
             	$cuit=$gasto['Proveedor']['cuit'];
	                $razon=$gasto['Proveedor']['razon'];
	            }else{
                	$proveedor = $gasto['Gasto']['proveedor'];

	                $cuit=$gasto['Gasto']['cuit'];
	                $razon=$gasto['Gasto']['razon'];
	                $this->loadModel('Proveedor');
	                $prov = $this->Proveedor->find('first',array('conditions'=>array('Proveedor.cuit'=>$cuit)));
	                $condicion=$this->CondicionImpositiva->findById($prov['Proveedor']['condicion_impositiva_id']);
	                $condicionImpositiva=$condicion['CondicionImpositiva']['nombre'];
	                $jurisdiccion=$this->JurisdiccionInscripcion->findById($prov['Proveedor']['jurisdiccion_inscripcion_id']);
	                $jurisdiccionInscripcion=$jurisdiccion['JurisdiccionInscripcion']['nombre'];

            	}

            $date_parts = explode("/",$gasto['Gasto']['fecha_vencimiento']);
            $yy=$date_parts[2];
        	$mm=$date_parts[1];
        	$dd=$date_parts[0];
        	$nuevafecha = $yy.$mm.$dd;
            $gastosMostrar[]=array('fecha'=>$nuevafecha,'fechaMostrar'=>$gasto['Gasto']['fecha_vencimiento'],'origen'=>$gasto['Gasto']['origen'],'factura'=>$factura,'proveedor'=>$proveedor,'razon'=>$razon,'cuit'=>$cuit,'condicionImpositiva'=>$condicionImpositiva,'jurisdiccionInscripcion'=>$jurisdiccionInscripcion,'iva_27'=>$gasto['Gasto']['iva_27'],'iva_21'=>$gasto['Gasto']['iva_21'],'iva_10_5'=>$gasto['Gasto']['iva_10_5'],'otra_alicuota'=>$gasto['Gasto']['otra_alicuota'],'perc_iva'=>$gasto['Gasto']['perc_iva'],'perc_iibb_bsas'=>$gasto['Gasto']['perc_iibb_bsas'],'perc_iibb_caba'=>$gasto['Gasto']['perc_iibb_caba'],'exento'=>$gasto['Gasto']['exento'],'monto'=>$gasto['Gasto']['monto']);

		}
		$this->array_sort_by($gastosMostrar, $orden);
		foreach($gastosMostrar as $gasto){

			$data[] = array($gasto['fechaMostrar'], $gasto['origen'], $gasto['factura'],$gasto['proveedor'],$gasto['razon'],$gasto['cuit'],$gasto['condicionImpositiva'],$gasto['jurisdiccionInscripcion'],str_replace('.',',',$gasto['monto']-$gasto['iva_10_5']-$gasto['iva_21']-$gasto['iva_27']-$gasto['otra_alicuota']-$gasto['perc_iva']-$gasto['perc_iibb_bsas']-$gasto['perc_iibb_caba']-$gasto['exento']),str_replace('.',',',$gasto['iva_10_5']), str_replace('.',',',$gasto['iva_21']),str_replace('.',',',$gasto['iva_27']), str_replace('.',',',$gasto['otra_alicuota']), str_replace('.',',',$gasto['perc_iva']), str_replace('.',',',$gasto['perc_iibb_bsas']), str_replace('.',',',$gasto['perc_iibb_caba']), str_replace('.',',',$gasto['exento']), str_replace('.',',',$gasto['monto']));
		}
		$data[] = array("","","","","","","","","","","","","","","","","","");
		$data[] = array("","","","","","","","","","","","","","","","","","");
		//$data[] = array("", "Total Cr�dito fiscal",$creditoFiscal,str_replace('.',',',$total27), str_replace('.',',',$total21),str_replace('.',',',$total10_5), str_replace('.',',',$totalOtraAlicuota),str_replace('.',',',$totalMonto));
		$data[] = array("", "", "", "", "", "", "", "",$creditoFiscal,str_replace('.',',',$total10_5), str_replace('.',',',$total21),str_replace('.',',',$total27), str_replace('.',',',$totalOtraAlicuota), str_replace('.',',',$totalperc_iva), str_replace('.',',',$totalperc_iibb_bsas), str_replace('.',',',$totalperc_iibb_caba), str_replace('.',',',$totalexento),str_replace('.',',',$totalMonto));

		$this->ExportXls->export($fileName, $headerRow, $data);
    }

	public function getUnidads($categoria_id, $ano){
        $this->layout = 'ajax';
        $this->loadModel('Unidad');
    	/*if (($desde!='undefined-undefined-')&&($hasta!='undefined-undefined-')) {
    			$condicion=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$hasta,'Unidad.baja >=' =>$desde);
				$unidades = $this->Unidad->find('all',array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' => $condicion));
		}
		elseif (($desde!='undefined-undefined-')) {
    			$condicion=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$desde,'Unidad.baja >=' =>$desde);
				$unidades = $this->Unidad->find('all',array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' => $condicion));
		}
		else{

       		$unidades = $this->Unidad->find('all',array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' =>array('Unidad.categoria_id =' => $categoria_id)));
		}*/
        $condicion=array('Unidad.categoria_id =' => $categoria_id, 'Unidad.habilitacion <=' =>$ano.'-12-31','Unidad.baja >=' =>$ano.'-01-01');
		$unidades = $this->Unidad->find('all',array('order' => array('Unidad.marca, Unidad.modelo ASC'), 'conditions' => $condicion));
		/*App::uses('ConnectionManager', 'Model');
	        	$dbo = ConnectionManager::getDatasource('default');
			    $logs = $dbo->getLog();
			    $lastLog = end($logs['log']);

			    echo $lastLog['query'];*/
		$this->set(array(
            'unidads' => $unidades
        ));

    }




	function iva_ventas($mes,$ano,$orden,$tipoDoc,$tipo,$puntoVenta,$buscar=''){
         //error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('ReservaFactura');

        $condicionTipoDoc = array();

    	if ($tipoDoc!='Seleccionar...') {

        	$condicionTipoDoc = array('ReservaFactura.tipoDoc ' => $tipoDoc);
        }

		$condicionTipo = array();

    	if ($tipo!='Seleccionar...') {

        	$condicionTipo = array('ReservaFactura.tipo ' => $tipo);
        }

		$condicionPuntoVenta = array();

    	if ($puntoVenta!='Seleccionar...') {

        	$condicionPuntoVenta = array('ReservaFactura.punto_venta_id ' => $puntoVenta);
        }

		$condicionBuscar = array();

    	if ($buscar!='') {

        	$condicionBuscar=array('or' =>
	        	  array('Reserva.numero LIKE '=>'%'.$buscar.'%', 'ReservaFactura.titular LIKE '=>'%'.$buscar.'%', 'ReservaFactura.numero LIKE '=>'%'.$buscar.'%', 'ReservaFactura.monto LIKE '=>'%'.$buscar.'%', 'ReservaFactura.fecha_emision LIKE '=>'%'.($buscar).'%',
			    ));
        }






        $reservas = $this->ReservaFactura->find('all',array('conditions' => array('YEAR(fecha_emision)' => $ano, 'MONTH(fecha_emision)' => $mes, ivaVentas => 1,$condicionTipoDoc,$condicionTipo,$condicionPuntoVenta,$condicionBuscar), 'order' => $orden.' asc', 'recursive' => 1));



         $reservasMostrar = array();
        if(count($reservas) > 0){

            foreach($reservas as $reserva){

            	$puntoVenta = ($reserva['ReservaFactura']['punto_venta_id'])?$reserva['PuntoVenta']['numero']:'';
            	$tipo = ($reserva['ReservaFactura']['tipoDoc']==1)?'Factura':'Nota de credito';
            	$puntoVenta = ($reserva['ReservaFactura']['punto_venta_id'])?$reserva['PuntoVenta']['numero']:'';
             	$factura = $tipo.'-'.$reserva['ReservaFactura']['tipo'].'-'.$puntoVenta.'-'.str_pad($reserva['ReservaFactura']['numero'], 6,0,STR_PAD_LEFT);


	            $date_parts = explode("/",$reserva['ReservaFactura']['fecha_emision']);

        		$yy=$date_parts[2];
        		$mm=$date_parts[1];
        		$dd=$date_parts[0];
        		$nuevafecha = $yy.$mm.$dd;
        		$iva =($reserva['PuntoVenta']['alicuota'])?$reserva['ReservaFactura']['monto']-($reserva['ReservaFactura']['monto']/(1+$reserva['PuntoVenta']['alicuota'])):0;
            	$reservasMostrar[]=array('fecha'=>$nuevafecha,'fechaMostrar'=>$reserva['ReservaFactura']['fecha_emision'],'factura'=>$factura,'titular'=>$reserva['ReservaFactura']['titular'],'nroReserva'=>$reserva['Reserva']['numero'],'iva_21'=>$iva,'monto'=>$reserva['ReservaFactura']['monto']);
            }
        }
        $this->array_sort_by($reservasMostrar, $orden);
        $this->set(array(
            'reservas' => $reservasMostrar
        ));
    }

	function exportarIvaVenta($mes,$ano,$orden,$tipoDoc,$tipo,$puntoVenta,$buscar=''){
         //error_reporting(0);
        $this->layout = 'ajax';

       $this->loadModel('ReservaFactura');


        $condicionTipoDoc = array();

    	if ($tipoDoc!='Seleccionar...') {

        	$condicionTipoDoc = array('ReservaFactura.tipoDoc ' => $tipoDoc);
        }

		$condicionTipo = array();

    	if ($tipo!='Seleccionar...') {

        	$condicionTipo = array('ReservaFactura.tipo ' => $tipo);
        }

		$condicionPuntoVenta = array();

    	if ($puntoVenta!='Seleccionar...') {

        	$condicionPuntoVenta = array('ReservaFactura.punto_venta_id ' => $puntoVenta);
        }

		$condicionBuscar = array();

    	if ($buscar!='') {

        	$condicionBuscar=array('or' =>
	        	  array('Reserva.numero LIKE '=>'%'.$buscar.'%', 'ReservaFactura.titular LIKE '=>'%'.$buscar.'%', 'ReservaFactura.numero LIKE '=>'%'.$buscar.'%', 'ReservaFactura.monto LIKE '=>'%'.$buscar.'%', 'ReservaFactura.fecha_emision LIKE '=>'%'.($buscar).'%',
			    ));
        }






        $reservas = $this->ReservaFactura->find('all',array('conditions' => array('YEAR(fecha_emision)' => $ano, 'MONTH(fecha_emision)' => $mes, ivaVentas => 1,$condicionTipoDoc,$condicionTipo,$condicionPuntoVenta,$condicionBuscar), 'order' => $orden.' asc', 'recursive' => 1));


    	$this->autoRender = false;
  		$this->layout = false;


		$fileName = "Iva_Ventas_".$mes.'_'.$ano.".xls";
		//$fileName = "bookreport_".date("d-m-y:h:s").".csv";
		$headerRow = array("Fecha comprobante","Factura/N. de credito","Titular","IVA","Monto bruto");

		$data = array();

	    $total21=0;

	     $gastosMostrar = array();
	    foreach($reservas as $reserva){

             $iva =($reserva['PuntoVenta']['alicuota'])?$reserva['ReservaFactura']['monto']-($reserva['ReservaFactura']['monto']/(1+$reserva['PuntoVenta']['alicuota'])):0;
             $total21 +=$iva;
             $totalMonto +=$reserva['ReservaFactura']['monto'];
            $puntoVenta = ($reserva['ReservaFactura']['punto_venta_id'])?$reserva['PuntoVenta']['numero']:'';
            $tipo = ($reserva['ReservaFactura']['tipoDoc']==1)?'Factura':'Nota de credito';
             	$factura = $tipo.'-'.$reserva['ReservaFactura']['tipo'].'-'.$puntoVenta.'-'.str_pad($reserva['ReservaFactura']['numero'], 6,0,STR_PAD_LEFT);


	            $date_parts = explode("/",$reserva['ReservaFactura']['fecha_emision']);

        		$yy=$date_parts[2];
        		$mm=$date_parts[1];
        		$dd=$date_parts[0];
        		$nuevafecha = $yy.$mm.$dd;
            $gastosMostrar[]=array('fecha'=>$nuevafecha,'fechaMostrar'=>$reserva['ReservaFactura']['fecha_emision'],'factura'=>$factura,'titular'=>$reserva['ReservaFactura']['titular'],'iva_21'=>$iva,'monto'=>$reserva['ReservaFactura']['monto']);

		}
		$this->array_sort_by($gastosMostrar, $orden);
		foreach($gastosMostrar as $gasto){

			$data[] = array($gasto['fechaMostrar'], $gasto['factura'],$gasto['titular'], str_replace('.',',',number_format($gasto['iva_21'],2)),str_replace('.',',',number_format($gasto['monto'],2)));
		}
		$data[] = array("","","","","");
		$data[] = array("","","","","");
		$data[] = array("","","", str_replace('.',',',number_format($total21,2)),str_replace('.',',',number_format($totalMonto,2)));
  		$this->ExportXls->export($fileName, $headerRow, $data);
    }

    function base_datos($mes,$ano, $colNombre, $colDni, $colTelefono, $colCelular, $colDireccion, $colLocalidad, $colEmail){
        //error_reporting(0);

        //echo $colNombre;
        $this->layout = 'ajax';

        $this->loadModel('Reserva');

        if ($mes!='N'){
            $from = $ano .'-'. $mes .'-01 00:00:00';
            $to = $ano .'-'. $mes .'-31 00:00:00';
        }
        else{
            $from = $ano .'-01-01 00:00:00';
            $to = $ano .'-12-31 00:00:00';
        }



        $reservas = $this->Reserva->find('all',array('order' => 'Reserva.id desc', 'conditions' => array('Reserva.devolucion between ? and ?' => array($from, $to))));

        //print_r($reservas);

        $clientesMostrar = array();
        if(count($reservas) > 0){

            foreach($reservas as $reserva){


                $clientesMostrar[]=array('nombre_apellido'=>$reserva['Cliente']['nombre_apellido'],'dni'=>$reserva['Cliente']['dni'],'telefono'=>$reserva['Cliente']['telefono'],'celular'=>$reserva['Cliente']['celular'],'direccion'=>$reserva['Cliente']['direccion'],'localidad'=>$reserva['Cliente']['localidad'],'email'=>$reserva['Cliente']['email']);
            }
        }
        //$this->array_sort_by($gastosMostrar, $orden);
        $this->set(array(
            'clientes' => $clientesMostrar,
            'colNombre' => $colNombre,
            'colDni' => $colDni,
            'colTelefono' => $colTelefono,
            'colCelular' => $colCelular,
            'colDireccion' => $colDireccion,
            'colLocalidad' => $colLocalidad,
            'colEmail' => $colEmail,
        ));
    }

    function exportarBaseDatos($mes, $ano, $colNombre, $colDni, $colTelefono, $colCelular, $colDireccion, $colLocalidad, $colEmail){
        //error_reporting(0);
        $this->layout = 'ajax';

        $this->loadModel('Reserva');


        if ($mes!='N'){
            $from = $ano .'-'. $mes .'-01 00:00:00';
            $to = $ano .'-'. $mes .'-31 00:00:00';
        }
        else{
            $from = $ano .'-01-01 00:00:00';
            $to = $ano .'-12-31 00:00:00';
        }

        $reservas = $this->Reserva->find('all',array('order' => 'Reserva.id desc', 'conditions' => array('Reserva.devolucion between ? and ?' => array($from, $to))));
        $this->autoRender = false;
        $this->layout = false;


        $fileName = "Base_Datos_".$mes.'_'.$ano.".xls";

        $headerRow = array();
        if ($colNombre){
            $headerRow[]="Nombre Apellido";
        }
        if ($colDni){
            $headerRow[]="DNI";
        }
        if ($colTelefono){
            $headerRow[]="Telefono";
        }
        if ($colCelular){
            $headerRow[]="Celular";
        }
        if ($colDireccion){
            $headerRow[]="Direccion";
        }
        if ($colLocalidad){
            $headerRow[]="Localidad";
        }
        if ($colEmail){
            $headerRow[]="E-mail";
        }

        //$headerRow = array("Nombre Apellido","DNI","Telefono","Celular","Direccion","Localidad","E-mail");

        $data = array();

        $clientesMostrar = array();
        if(count($reservas) > 0){

            foreach($reservas as $reserva){




                $clientesMostrar[]=array('nombre_apellido'=>$reserva['Cliente']['nombre_apellido'],'dni'=>$reserva['Cliente']['dni'],'telefono'=>$reserva['Cliente']['telefono'],'celular'=>$reserva['Cliente']['celular'],'direccion'=>$reserva['Cliente']['direccion'],'localidad'=>$reserva['Cliente']['localidad'],'email'=>$reserva['Cliente']['email']);
            }
        }
        // $this->array_sort_by($gastosMostrar, $orden);
        foreach($clientesMostrar as $cliente){

            $row = array();
            if ($colNombre){
                $row[]=utf8_decode(trim($cliente['nombre_apellido']));
            }
            if ($colDni){
                $row[]=utf8_decode(trim($cliente['dni']));
            }
            if ($colTelefono){
                $row[]=utf8_decode(trim($cliente['telefono']));
            }
            if ($colCelular){
                $row[]=utf8_decode(trim($cliente['celular']));
            }
            if ($colDireccion){
                $row[]=utf8_decode(trim($cliente['direccion']));
            }
            if ($colLocalidad){
                $row[]=utf8_decode(trim($cliente['localidad']));
            }
            if ($colEmail){
                $row[]=utf8_decode(trim($cliente['email']));
            }
            $data[] = $row;
        }


        $this->ExportXls->export($fileName, $headerRow, $data);
    }

}
?>
