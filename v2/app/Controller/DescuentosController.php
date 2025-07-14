<?php
class DescuentosController extends AppController {
    public $scaffold;


	public function index(){
    	$this->layout = 'index';
    	$this->setLogUsuario('Opciones de Cobro/descuentos');
    }



	public function dataTable($limit = ""){
        $rows = array();
        $this->loadModel('Descuento');
        if($limit == "todos"){
            $descuentos = $this->Descuento->find('all');
        }else{
            $descuentos = $this->Descuento->find('all',array('limit' => $limit));
        }
        foreach ($descuentos as $descuento) {

        	$activa = ($descuento['Descuento']['activo'])?'Si':'No';
        	$activa_ingles = ($descuento['Descuento']['activo_ingles'])?'Si':'No';
        	$activa_portugues = ($descuento['Descuento']['activo_portugues'])?'Si':'No';
        	$tarjeta = ($descuento['Descuento']['tarjeta'])?'Si':'No';
        	$tarjeta_ingles = ($descuento['Descuento']['tarjeta_ingles'])?'Si':'No';
        	$tarjeta_portugues = ($descuento['Descuento']['tarjeta_portugues'])?'Si':'No';
            $mercadopago = ($descuento['Descuento']['mercadopago'])?'Si':'No';
            $mercadopago_ingles = ($descuento['Descuento']['mercadopago_ingles'])?'Si':'No';
            $mercadopago_portugues = ($descuento['Descuento']['mercadopago_portugues'])?'Si':'No';
        	$rows[] = array(
                $descuento['Descuento']['id'],
                $descuento['Descuento']['descuento'],
                $activa,
                $activa_ingles,
                $activa_portugues,
                $tarjeta,
                $tarjeta_ingles,
                $tarjeta_portugues,
                $mercadopago,
                $mercadopago_ingles,
                $mercadopago_portugues,
                $descuento['Descuento']['coheficiente'],

                $descuento['Descuento']['parcial'],
                $descuento['Descuento']['orden']
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

		$this->Descuento->id = $id;
        $this->request->data = $this->Descuento->read();
        $descuento = $this->request->data;

        $descuentoPeriodos = $this->Descuento->DescuentoPeriodo->find('all',array('conditions' => array('descuento_id' => $id),'recursive' => 2));
        $this->set('descuentoPeriodos',$descuentoPeriodos);

        $this->set('descuento', $this->Descuento->read());
    }


    public function guardar() {
        if (!empty($this->request->data)) {

            $descuento = $this->request->data['Descuento'];
            $this->Descuento->set($descuento);

            $errores = [];

            // Validar Descuento
            if (!$this->Descuento->validates()) {
                $errores['Descuento'] = $this->Descuento->validationErrors;
            }

            // Validar periodos (según estructura de inputs)
            // Si los periodos vienen con nombres separados como arrays paralelos
            $periodos = [];
            if (!empty($this->request->data['DescuentoPeriodoCounter']) &&
                !empty($this->request->data['DescuentoPeriodoDesde']) &&
                !empty($this->request->data['DescuentoPeriodoHasta'])) {

                $counters = $this->request->data['DescuentoPeriodoCounter'];
                $desdeArr = $this->request->data['DescuentoPeriodoDesde'];
                $hastaArr = $this->request->data['DescuentoPeriodoHasta'];

                foreach ($desdeArr as $i => $desde) {
                    $periodo = [
                        'id' => isset($counters[$i]) ? $counters[$i] : null,
                        'desde' => $desde,
                        'hasta' => isset($hastaArr[$i]) ? $hastaArr[$i] : null
                    ];

                    $this->Descuento->DescuentoPeriodo->create();
                    if ($periodo['id']) {
                        $this->Descuento->DescuentoPeriodo->id = $periodo['id'];
                    }
                    $this->Descuento->DescuentoPeriodo->set($periodo);

                    if (!$this->Descuento->DescuentoPeriodo->validates()) {
                        foreach ($this->Descuento->DescuentoPeriodo->validationErrors as $field => $messages) {
                            $errores['DescuentoPeriodo'][$i][$field] = is_array($messages) ? implode(', ', $messages) : $messages;
                        }
                    } else {
                        $periodos[] = $periodo;
                    }
                }
            }

            // Mostrar errores si los hay
            if (!empty($errores)) {
                $this->set('resultado', 'ERROR');
                $this->set('mensaje', 'No se pudo guardar');
                $this->set('detalle', $errores);
            } else {
                // Guardar Descuento
                $this->Descuento->save($descuento);
                $descuento_id = $this->Descuento->id;

                // Borrar periodos anteriores
                $this->Descuento->DescuentoPeriodo->deleteAll(['DescuentoPeriodo.descuento_id' => $descuento_id], false);

                // Guardar nuevos periodos
                foreach ($periodos as $periodo) {
                    $periodo['descuento_id'] = $descuento_id;
                    $this->Descuento->DescuentoPeriodo->create();
                    $this->Descuento->DescuentoPeriodo->save($periodo);
                }

                $this->set('resultado', 'OK');
                $this->set('mensaje', 'Datos guardados');
                $this->set('detalle', '');
            }

            $this->set('_serialize', ['resultado', 'mensaje', 'detalle']);
        }
    }



    public function eliminar($id = null){
        if(!empty($this->request->data)) {

         	$this->loadModel('Descuento');

            $this->Descuento->DescuentoPeriodo->deleteAll(['DescuentoPeriodo.descuento_id' => $this->request->data['id']], false);
            $this->Descuento->delete($this->request->data['id'],true);


	        $this->set('resultado','OK');
	        $this->set('mensaje','Opcion de cobro eliminada');
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
