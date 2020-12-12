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

        $this->set('descuento', $this->Descuento->read());
    }


	public function guardar(){



        //print_r($this->request->data);
        if(!empty($this->request->data)) {

         	//vaildo reserva
            $descuento = $this->request->data['Descuento'];
            $this->Descuento->set($descuento);
            if(!$this->Descuento->validates()){
                $errores['Descuento'] = $this->Descuento->validationErrors;
            }

            //muestro resultado
            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo guardar');
                $this->set('detalle',$errores);
            }else{

                $this->Descuento->save();



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

	public function eliminar($id = null){
        if(!empty($this->request->data)) {

         	$this->loadModel('Descuento');


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
