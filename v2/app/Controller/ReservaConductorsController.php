<?php
class ReservaConductorsController extends AppController {
    public $scaffold;
    public function index(){
        $this->set('rows',$this->ReservaExtra->find('all'));
        $this->set('_serialize', array(
            'rows'
        ));
    }
    public function eliminar(){
        $id = $this->request->data['reserva_extra_id'];
        $this->ReservaExtra->id = $id;
        $reserva_extra = $this->ReservaExtra->read();
        $this->loadModel('Reserva');
        $this->Reserva->id = $reserva_extra['ReservaExtra']['reserva_id'];
        $reserva = $this->Reserva->read();
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
        if ($pendiente > 0) {
	        if($reserva_extra['ReservaExtra']['extra_id']){
	            $this->ReservaExtra->delete($id);
	        }else if($reserva_extra['ReservaExtra']['extra_variable_id']){
	            $this->loadModel('ExtraVariable');
	            $this->ExtraVariable->delete($reserva_extra['ReservaExtra']['extra_variable_id']);
	            $this->ReservaExtra->delete($id);
	        }
	        $this->set('resultado','OK');
	        $this->set('mensaje','Extra eliminada');
	        $this->set('detalle','');
        }
        else{

	        $this->set('resultado','ERROR');
	        $this->set('mensaje','Extra no eliminada - ');
	        $this->set('detalle','Realice antes la devolucion correspondiente y luego elimine los extras que equivalen al monto devuelto');
        }

        $this->set('_serialize', array(
            'resultado',
            'mensaje' ,
            'detalle'
        ));
       // $this->autoRender = false;

    }



    public function getRow(){
        $this->layout = 'ajax';

        if($this->request->data){


            //guardo la relacion automaticamente
            $this->ReservaConductor->set(array(
                'reserva_id' => $this->request->data['reserva_id'],
                'nombre_apellido' => $this->request->data['dni'],
                'dni' => $this->request->data['dni'],
                'telefono' => $this->request->data['telefono'],
                'email' => $this->request->data['email'],
                'nroLicencia' => $this->request->data['nroLicencia'],
                'vencimiento' => $this->request->data['vencimiento'],
                'lugarEmision' => $this->request->data['lugarEmision'],
                'direccion' => $this->request->data['direccion'],
                'localidad' => $this->request->data['localidad']

            ));
            $this->ReservaConductor->save();
            $this->set('reserva_conductor_id',$this->ReservaConductor->id);
        }
        $this->set('nombre_apellido',$this->request->query['nombreApellido']);
        $this->set('dni',$this->request->query['dni']);
        $this->set('telefono',$this->request->query['telefono']);
        $this->set('email',$this->request->query['email']);
        $this->set('nroLicencia',$this->request->query['nroLicencia']);
        $this->set('vencimiento',$this->request->query['vencimiento']);
        $this->set('lugarEmision',$this->request->query['lugarEmision']);
        $this->set('direccion',$this->request->query['direccion']);
        $this->set('localidad',$this->request->query['localidad']);
    }

}
?>
