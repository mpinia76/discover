<?php
class Reserva extends AppModel {
    public $belongsTo = array('Cliente','Unidad','Subcanal',
    	'Lugar_Retiro' => array(
            'className'    => 'Lugar',
            'foreignKey'   => 'lugar_retiro_id'
        ),
        'Lugar_Devolucion' => array(
            'className'    => 'Lugar',
            'foreignKey'   => 'lugar_devolucion_id'
        ),
        'Usuario' => array(
            'className'    => 'Usuario',
            'foreignKey'   => 'cargado_por'
        ),
        'Empleado' => array(
            'className'    => 'Empleado',
            'foreignKey'   => 'reservado_por'
        )
    );
    public $hasMany = array('ReservaCobro','ReservaExtra','ReservaFactura','ReservaDevolucion','ReservaConductor');

    public $validate = array(
        'retiro' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'message' => 'Ingrese una fecha valida'
        ),

        'devolucion' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'message' => 'Ingrese una fecha valida'
        ),

        'unidad_id' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar una unidad'
        ),
        /*'lugar_retiro_id' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar un lugar'
        ),

        'lugar_devolucion_id' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar un lugar'
        ),*/

        'hora_retiro' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar una hora'
        ),

        'vuelo' => array(
            'rule' => array('between', 4, 6),
        	'allowEmpty' => true,
            'message' => 'Ingrese como minimo 4 digitos.'
        ),

        'hora_devolucion' => array(
            'format' => array(
                'required'   => true,
	            'rule' => 'notEmpty',
	            'message' => 'Debe seleccionar una hora'
            ),
            'after' => array(
                'rule' => 'after_devolucion',
                'message' => 'La fecha y hora debe ser posterior al retiro'
            )
        )

        /*,

        'reservado_por' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar quien realizo la reserva'
        )*/
    );

    public $virtualFields = array(
        'mes' => 'MONTH(devolucion)',
    	'mesretiro' => 'MONTH(retiro)',
        'noches' => 'DATEDIFF(devolucion,retiro)'
    );

    public function after_devolucion($data){

        $retiro_part = explode("/",$this->data[$this->alias]['retiro']);
        $devolucion_part = explode("/",$this->data[$this->alias]['devolucion']);
        $retiro = strtotime($retiro_part[2]."-".$retiro_part[1]."-".$retiro_part[0].' '.str_replace(' ', '', $this->data[$this->alias]['hora_retiro']));
        $devolucion = strtotime($devolucion_part[2]."-".$devolucion_part[1]."-".$devolucion_part[0].' '.str_replace(' ', '', $data['hora_devolucion']));
        //echo $retiro_part[2]."-".$retiro_part[1]."-".$retiro_part[0].' '.str_replace(' ', '', $this->data[$this->alias]['hora_retiro']).' '.$devolucion_part[2]."-".$devolucion_part[1]."-".$devolucion_part[0].' '.str_replace(' ', '', $data['hora_devolucio']);
        //echo $retiro.'  '.$devolucion;
        if($retiro < $devolucion) return true;
    }

    public function beforeSave($options = Array()) {


    	if(isset($this->data['Reserva']['retiro'])){
            $this->data['Reserva']['retiro'] = $this->dateFormatBeforeSave($this->data['Reserva']['retiro']);
        }

    	if(($this->data['Reserva']['devolucion'])){
            $this->data['Reserva']['devolucion'] = $this->dateFormatBeforeSave($this->data['Reserva']['devolucion']);
        }

        if(isset($this->data['Reserva']['creado'])){
            $this->data['Reserva']['creado'] = $this->dateFormatBeforeSave($this->data['Reserva']['creado']);
        }

    	if(isset($this->data['Reserva']['hora_retiro'])){
            $this->data['Reserva']['hora_retiro'] = str_replace(' ', '', ($this->data['Reserva']['hora_retiro']));
        }

    	if(isset($this->data['Reserva']['hora_devolucion'])){
            $this->data['Reserva']['hora_devolucion'] = str_replace(' ', '', ($this->data['Reserva']['hora_devolucion']));
        }

        return true;
    }

    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {

            if (!empty($val) and isset($val['Reserva']['retiro'])) {
                $results[$key]['Reserva']['retiro']= $this->dateFormatAfterFind($val['Reserva']['retiro']);
            }
            if (!empty($val) and isset($val['Reserva']['devolucion'])) {
                $results[$key]['Reserva']['devolucion']= $this->dateFormatAfterFind($val['Reserva']['devolucion']);
            }
            if (!empty($val) and isset($val['Reserva']['creado'])) {
                $results[$key]['Reserva']['creado']= $this->dateFormatAfterFind($val['Reserva']['creado']);
            }
        }
        return $results;
    }

}
?>
