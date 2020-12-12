<?php
class CategoriasController extends AppController {
    public $scaffold;
    public $components = array('Mpdf');

	public function index(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Categorias');
    }

	public function index2(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Habilitar Categorias');
    }

	public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('Categoria');
        if($limit == "todos"){
            $categorias = $this->Categoria->find('all');
        }else{
            $categorias = $this->Categoria->find('all',array('limit' => $limit));
        }
        foreach ($categorias as $categoria) {

        	$activa = ($categoria['Categoria']['activa'])?'Si':'No';
        	$descuento = ($categoria['Categoria']['descuento'])?'Si':'No';
        	$rows[] = array(
                $categoria['Categoria']['id'],
                $categoria['Categoria']['categoria'],
                $categoria['Categoria']['vehiculos'],
                $categoria['Categoria']['vehiculos_ingles'],
                $categoria['Categoria']['vehiculos_portugues']
            );

        }


        $this->set('aaData',$rows);
        $this->set('_serialize', array(
            'aaData'
        ));
    }

	public function dataTable2($limit = ""){
        $rows = array();
        $this->loadModel('Categoria');
        if($limit == "todos"){
            $categorias = $this->Categoria->find('all',array('order' => 'orden'));
        }else{
            $categorias = $this->Categoria->find('all',array('limit' => $limit,'order' => 'orden'));
        }
        foreach ($categorias as $categoria) {

        	$activa = ($categoria['Categoria']['activa'])?'Si':'No';
        	$descuento = ($categoria['Categoria']['descuento'])?'Si':'No';
        	$rows[] = array(
                $categoria['Categoria']['id'],
                $categoria['Categoria']['categoria'],

                $categoria['Categoria']['vehiculos'],
                $categoria['Categoria']['orden'],

                $activa,
                $descuento
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

		$this->Categoria->id = $id;
        $this->request->data = $this->Categoria->read();
        $categoria = $this->request->data;

        $this->set('categoria', $this->Categoria->read());
    }

	public function editar2($id = null){
        $this->layout = 'form';

		$this->Categoria->id = $id;
        $this->request->data = $this->Categoria->read();
        $categoria = $this->request->data;

        $this->set('categoria', $this->Categoria->read());
    }

	public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

         	//vaildo reserva
            $categoria = $this->request->data['Categoria'];
            $this->Categoria->set($categoria);
            if(!$this->Categoria->validates()){
                $errores['Categoria'] = $this->Categoria->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{

                $this->Categoria->save();



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


	function index_tarifas(){
        $this->layout = 'informe';
        $this->setLogUsuario('Tarifas por categorias');
    }

	function tarifas($desde,$hasta,$pdf=0){
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
        	$diasMostrados[$i]=array('fecha'=>'Sin especificar');

        }

        $this->set('meses',$mesesMostrar);

		$this->set('diasSemana',$diasSemanaMostrar);

		$this->loadModel('CategoriaTarifa');



        $reservas = $this->CategoriaTarifa->find('all',array('conditions' => (array('fecha >=' => $desde,'fecha <=' => $hasta)), 'recursive' => 2));



        $date = new DateTime($desde);
       	$reservasMostrar = array();


       $this->loadModel('Categoria');
		$categorias = $this->Categoria->find('all');
        $categoriaesDias = array();
        foreach($categorias as $categoria){
        	$reservaMostrar = array();
        	$reservaMostrar['id']=$categoria['Categoria']['id'];
            $reservaMostrar['categoria']=$categoria['Categoria']['categoria'];

			$unidadesDias[$categoria['Categoria']['id']] = $diasMostrados;
			//print_r($reservas);
        	if(count($reservas) > 0){

	            foreach($reservas as $reserva){
	                //print_r($reserva);

	                if(($categoria['Categoria']['id']==$reserva['Categoria']['id'])){


						foreach ($diasMostrados as $day => $res) {
							//echo $reserva['CategoriaTarifa']['fecha'].' - '.$day."<br>";

							//$dateFormat = new DateTime($day);

							if ($reserva['CategoriaTarifa']['fecha']==$day) {
								//echo $reserva['CategoriaTarifa']['fecha'].' - '.$day."<br>";
								$unidadesDias[$categoria['Categoria']['id']][$day]['fecha']=$reserva['CategoriaTarifa']['importe'];

							}

						}
						//print_r($diasMostrados);
	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0

	        $reservasMostrar[]=array('id'=>$reservaMostrar['id'],'categoria'=>$reservaMostrar['categoria'], 'diasMostrar'=>$unidadesDias);
        }

		//$this->array_sort_by($reservasMostrar, 'categoria');

		//print_r($reservasMostrar);

        $this->set(array(
            'desde' => $array_dias[date('l', strtotime($desde))].' '.$date->format('d/m/Y'),
         	'pdf' => $pdf,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Tarifas_categorias_'.$date->format('d_m_Y').'.pdf');


        }
        /**/

     }

	public function editar_tarifas($id,$desde,$hasta,$importe){
        $this->layout = 'form';

        $categorias = $this->Categoria->find('list');
        $this->set('selectedCategoriasID', $id);

        //print_r($categorias);
        $this->set('categorias',$categorias);
        $dateDesde = new DateTime($desde);
        $datehasta = new DateTime($hasta);
        $this->set('desde',$dateDesde->format('d/m/Y'));
        $this->set('hasta',$datehasta->format('d/m/Y'));
        $this->set('importe',$importe);
    }

	public function eliminar_tarifa(){

       if(!empty($this->request->data)) {



    		$categoriaTarifa = $this->request->data['CategoriaTarifa'];

    		//print_r($categoriaTarifa);
    		$categoria_id = $categoriaTarifa['categoria_id'];
    		$desdeArray = explode("/", $categoriaTarifa['fechaDesde']);
    		//print_r($desdeArray);
    		$desde = $desdeArray[2].'-'.$desdeArray[1].'-'.$desdeArray[0];
    		$hastaArray = explode("/", $categoriaTarifa['fechaHasta']);
    		$hasta = $hastaArray[2].'-'.$hastaArray[1].'-'.$hastaArray[0];

    		$importe = $categoriaTarifa['importe'];


    		//echo $desde.' => '.$hasta;
    		$this->loadModel('CategoriaTarifa');




	    		$this->CategoriaTarifa->deleteAll(array('fecha >=' => $desde,'fecha <=' => $hasta, 'categoria_id' => $categoria_id), false);

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

    public function guardar_tarifas(){
    	if(!empty($this->request->data)) {



    		$categoriaTarifa = $this->request->data['CategoriaTarifa'];

    		//print_r($categoriaTarifa);
    		$categoria_id = $categoriaTarifa['categoria_id'];
    		$desdeArray = explode("/", $categoriaTarifa['fechaDesde']);
    		//print_r($desdeArray);
    		$desde = $desdeArray[2].'-'.$desdeArray[1].'-'.$desdeArray[0];
    		$hastaArray = explode("/", $categoriaTarifa['fechaHasta']);
    		$hasta = $hastaArray[2].'-'.$hastaArray[1].'-'.$hastaArray[0];

    		$importe = $categoriaTarifa['importe'];


    		//echo $desde.' => '.$hasta;
    		$this->loadModel('CategoriaTarifa');

    		if (!$categoria_id) {
				$errores['CategoriaTarifa']['categoria_id'][]='Debe seleccionar una categoria';
    		}
    		if (!$importe) {
    			if ($importe!=0) {
    				$errores['CategoriaTarifa']['importe'][]='Debe cargar un importe';
    			}

    		}
    		if ($desde=='--') {
				$errores['CategoriaTarifa']['fechaDesde'][]='Ingrese una fecha valida';
    		}
    		if ($hasta=='--') {
				$errores['CategoriaTarifa']['fechaHasta'][]='Ingrese una fecha valida';
    		}
    		if ($desde>$hasta) {
				$errores['CategoriaTarifa']['fechaHasta'][]='La fecha hasta debe ser igual o posterior a la desde';
    		}
    		if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
	    		$this->CategoriaTarifa->deleteAll(array('fecha >=' => $desde,'fecha <=' => $hasta, 'categoria_id' => $categoria_id), false);
	    		$datos = array();
	    		for($i=$desde;$i<=$hasta;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){

				    $datos[] = array('CategoriaTarifa' => array('categoria_id' => $categoria_id, 'fecha' => $i, 'importe' => $importe));


	    		}
	    		$this->CategoriaTarifa->saveMany($datos);
	    		$this->set('resultado','OK');
	            $this->set('mensaje','Datos guardados');
	            $this->set('detalle','');
            }
    	}
    	$this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
    }

	function index_coheficientes($pdf=0){
    	//echo $desde;
        ini_set( "memory_limit", "-1" );
        ini_set('max_execution_time', "-1");
        //error_reporting(0);

        $this->layout = 'informe';

        $this->setLogUsuario('Coheficientes por categorias');


		$this->loadModel('CategoriaCoheficiente');

		$dias = $this->CategoriaCoheficiente->find('all', array('fields' => array('DISTINCT CategoriaCoheficiente.dia'), 'order' => 'CategoriaCoheficiente.dia ASC'));

		$diasMostrados = array();
        foreach ($dias as $dia) {
        	//print_r($dia);

        	$diasMostrados[$dia['CategoriaCoheficiente']['dia']]=array('dia'=>'Sin especificar');

        }
     	//print_r($diasMostrados);
        $reservas = $this->CategoriaCoheficiente->find('all');



        $date = new DateTime($desde);
       	$reservasMostrar = array();


       	$this->loadModel('Categoria');
		$categorias = $this->Categoria->find('all');
        foreach($categorias as $categoria){
        	$reservaMostrar = array();
        	$reservaMostrar['id']=$categoria['Categoria']['id'];
            $reservaMostrar['categoria']=$categoria['Categoria']['categoria'];

			$unidadesDias[$categoria['Categoria']['id']] = $diasMostrados;
			//print_r($reservas);
        	if(count($reservas) > 0){

	            foreach($reservas as $reserva){
	                //print_r($reserva);

	                if(($categoria['Categoria']['id']==$reserva['Categoria']['id'])){


						foreach ($diasMostrados as $day => $res) {
							//echo $reserva['CategoriaTarifa']['fecha'].' - '.$day."<br>";

							if ($reserva['CategoriaCoheficiente']['dia']==$day) {
								//echo $reserva['CategoriaCoheficiente']['fecha'].' - '.$day."<br>";
								$unidadesDias[$categoria['Categoria']['id']][$day]['dia']=$reserva['CategoriaCoheficiente']['coheficiente'].'-'.$reserva['CategoriaCoheficiente']['id'];

							}

						}
						//print_r($diasMostrados);
	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0

	        $reservasMostrar[]=array('id'=>$reservaMostrar['id'],'categoria'=>$reservaMostrar['categoria'], 'diasMostrar'=>$unidadesDias);
        }

		//$this->array_sort_by($reservasMostrar, 'categoria');

		//print_r($reservasMostrar);

        $this->set(array(

         	'pdf' => $pdf,
        	'diasMostrados' => $diasMostrados,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Coheficientes_categorias_'.$date->format('d_m_Y').'.pdf');


        }
        /**/

     }

	public function crear_coheficiente(){
        $this->layout = 'form';

        $categorias = $this->Categoria->find('list');
   		$this->set('dias', array('1' => '1', '2' => '2', '3' => '3','4' => '4', '5' => '5', '6' => '6', '7' => '7', '8 o +' => '8 o +'));
        $this->set('categorias',$categorias);

    }


	public function editar_coheficiente($id = null){
        $this->layout = 'form';
        $this->loadModel('CategoriaCoheficiente');
        $this->set('categorias', $this->CategoriaCoheficiente->Categoria->find('list'));
        $this->set('dias', array('1' => '1', '2' => '2', '3' => '3','4' => '4', '5' => '5', '6' => '6', '7' => '7', '8 o +' => '8 o +'));
		$this->CategoriaCoheficiente->id = $id;
        $this->request->data = $this->CategoriaCoheficiente->read();


        $this->set('categoria_coheficiente', $this->CategoriaCoheficiente->read());
    }


	public function guardar_coheficiente(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

         	$this->loadModel('CategoriaCoheficiente');
            $categoriaCoheficiente = $this->request->data['CategoriaCoheficiente'];
            $this->CategoriaCoheficiente->set($categoriaCoheficiente);
            if(!$this->CategoriaCoheficiente->validates()){
                $errores['CategoriaCoheficiente'] = $this->CategoriaCoheficiente->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
            	try {
                	$this->CategoriaCoheficiente->save();



	            	$this->set('resultado','OK');
		                $this->set('mensaje','Datos guardados');
		                $this->set('detalle','');
					} catch (PDOException $e) {
				  if ($e->errorInfo[1]=='1062') {
				  	$errores[CategoriaCoheficiente][dia]='El coheficiente para esa categoria y dia ya fue creado';
				  }
				  else {
				  	$errores[CategoriaCoheficiente][dia]=$e->errorInfo[1];
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

	public function eliminar_coheficiente(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

         	$this->loadModel('CategoriaCoheficiente');


            $this->CategoriaCoheficiente->delete($this->request->data['id'],true);




                $this->set('resultado','OK');
                $this->set('mensaje','Eliminado exitosamente');
                $this->set('detalle','');

            $this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
        }
    }

	function index_estadia(){
        $this->layout = 'informe';
         $this->setLogUsuario('Estadia minima');
    }

	function estadias($desde,$hasta,$pdf=0){
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
        	$diasMostrados[$i]=array('fecha'=>'1');

        }

        $this->set('meses',$mesesMostrar);

		$this->set('diasSemana',$diasSemanaMostrar);

		$this->loadModel('CategoriaEstadia');



        $reservas = $this->CategoriaEstadia->find('all',array('conditions' => (array('fecha >=' => $desde,'fecha <=' => $hasta)), 'recursive' => 2));



        $date = new DateTime($desde);
       	$reservasMostrar = array();


       $this->loadModel('Categoria');
		$categorias = $this->Categoria->find('all');
        $categoriaesDias = array();
        foreach($categorias as $categoria){
        	$reservaMostrar = array();
        	$reservaMostrar['id']=$categoria['Categoria']['id'];
            $reservaMostrar['categoria']=$categoria['Categoria']['categoria'];

			$unidadesDias[$categoria['Categoria']['id']] = $diasMostrados;
			//print_r($reservas);
        	if(count($reservas) > 0){

	            foreach($reservas as $reserva){
	                //print_r($reserva);

	                if(($categoria['Categoria']['id']==$reserva['Categoria']['id'])){


						foreach ($diasMostrados as $day => $res) {
							//echo $reserva['CategoriaEstadia']['fecha'].' - '.$day."<br>";

							//$dateFormat = new DateTime($day);

							if ($reserva['CategoriaEstadia']['fecha']==$day) {
								//echo $reserva['CategoriaEstadia']['fecha'].' - '.$day."<br>";
								$unidadesDias[$categoria['Categoria']['id']][$day]['fecha']=$reserva['CategoriaEstadia']['dias'];

							}

						}
						//print_r($diasMostrados);
	                }

		             //echo $reserva['Reserva']['mes']." - ".$ventas_netas[$reserva['Reserva']['mes']]."<br>";
	            }// foreach reservas
	        }// if count reservas > 0

	        $reservasMostrar[]=array('id'=>$reservaMostrar['id'],'categoria'=>$reservaMostrar['categoria'], 'diasMostrar'=>$unidadesDias);
        }

		//$this->array_sort_by($reservasMostrar, 'categoria');

		//print_r($reservasMostrar);

        $this->set(array(
            'desde' => $array_dias[date('l', strtotime($desde))].' '.$date->format('d/m/Y'),
         	'pdf' => $pdf,
        	'reservas' => $reservasMostrar
        ));
        if ($pdf) {

            require_once '../../vendor/autoload.php';

            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
            $mpdf->WriteHTML($this->render());
            $mpdf->Output('Estadias_categorias_'.$date->format('d_m_Y').'.pdf','D');


        }
        /**/

     }

	public function editar_estadia($id,$desde,$hasta,$dias){
        $this->layout = 'form';

        $categorias = $this->Categoria->find('list');
        $this->set('selectedCategoriasID', $id);

        //print_r($categorias);
        $this->set('categorias',$categorias);
        $dateDesde = new DateTime($desde);
        $datehasta = new DateTime($hasta);
        $this->set('desde',$dateDesde->format('d/m/Y'));
        $this->set('hasta',$datehasta->format('d/m/Y'));
        $this->set('dias',$dias);
    }

	public function eliminar_estadia(){

       if(!empty($this->request->data)) {



    		$categoriaEstadia = $this->request->data['CategoriaEstadia'];

    		//print_r($categoriaEstadia);
    		$categoria_id = $categoriaTarifa['categoria_id'];
    		$desdeArray = explode("/", $categoriaEstadia['fechaDesde']);
    		//print_r($desdeArray);
    		$desde = $desdeArray[2].'-'.$desdeArray[1].'-'.$desdeArray[0];
    		$hastaArray = explode("/", $categoriaEstadia['fechaHasta']);
    		$hasta = $hastaArray[2].'-'.$hastaArray[1].'-'.$hastaArray[0];

    		$dias = $categoriaEstadia['dias'];


    		//echo $desde.' => '.$hasta;
    		$this->loadModel('CategoriaEstadia');




	    		$this->CategoriaEstadia->deleteAll(array('fecha >=' => $desde,'fecha <=' => $hasta, 'categoria_id' => $categoria_id), false);

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

    public function guardar_estadia(){
    	if(!empty($this->request->data)) {



    		$categoriaEstadia = $this->request->data['CategoriaEstadia'];

    		//print_r($categoriaEstadia);
    		$categoria_id = $categoriaEstadia['categoria_id'];
    		$desdeArray = explode("/", $categoriaEstadia['fechaDesde']);
    		//print_r($desdeArray);
    		$desde = $desdeArray[2].'-'.$desdeArray[1].'-'.$desdeArray[0];
    		$hastaArray = explode("/", $categoriaEstadia['fechaHasta']);
    		$hasta = $hastaArray[2].'-'.$hastaArray[1].'-'.$hastaArray[0];

    		$dias = $categoriaEstadia['dias'];


    		//echo $desde.' => '.$hasta;
    		$this->loadModel('CategoriaEstadia');

    		if (!$categoria_id) {
				$errores['CategoriaEstadia']['categoria_id'][]='Debe seleccionar una categoria';
    		}
    		if (!$dias) {
    			if ($dias!=0) {
    				$errores['CategoriaEstadia']['dias'][]='Debe cargar un nro';
    			}

    		}
    		if ($desde=='--') {
				$errores['CategoriaEstadia']['fechaDesde'][]='Ingrese una fecha valida';
    		}
    		if ($hasta=='--') {
				$errores['CategoriaEstadia']['fechaHasta'][]='Ingrese una fecha valida';
    		}
    		if ($desde>$hasta) {
				$errores['CategoriaEstadia']['fechaHasta'][]='La fecha hasta debe ser igual o posterior a la desde';
    		}
    		if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{
	    		$this->CategoriaEstadia->deleteAll(array('fecha >=' => $desde,'fecha <=' => $hasta, 'categoria_id' => $categoria_id), false);
	    		$datos = array();
	    		for($i=$desde;$i<=$hasta;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){

				    $datos[] = array('CategoriaEstadia' => array('categoria_id' => $categoria_id, 'fecha' => $i, 'dias' => $dias));


	    		}
	    		$this->CategoriaEstadia->saveMany($datos);
	    		$this->set('resultado','OK');
	            $this->set('mensaje','Datos guardados');
	            $this->set('detalle','');
            }
    	}
    	$this->set('_serialize', array(
                'resultado',
                'mensaje' ,
                'detalle'
            ));
    }
}
?>
