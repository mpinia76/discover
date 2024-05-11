<?php
class Unidad extends AppModel {
	public $belongsTo = array('Categoria');
    public $displayField = 'unidad';
    public $virtualFields = array(
			'unidad' => "CONCAT(marca,' ', modelo,' ',patente,' (km: ',km,')')",
    		'unidadContrato' => "CONCAT(marca,' ', modelo)",
            'unidadPatente' => "CONCAT(marca,' ', modelo,' ',patente)",
			);
    public $validate = array(
    	'categoria_id' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Debe seleccionar una categoria'
        ),
        'marca' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese una marca'
        ),
        'capacidad' => array(
            'required'   => true,
            'rule' => 'numeric',
            'message' => 'Ingrese una capacidad homologada valida'
        ),
        'km' => array(
            'required'   => true,
            'rule' => 'numeric',
            'message' => 'Ingrese un KM valido'
        ),
        'modelo' => array(
            'required'   => true,
            'rule' => 'notEmpty',
            'message' => 'Ingrese un modelo'
        ),
        'habilitacion' => array(
            'rule'     => array('date','dmy'),
            'required' => true,
            'message' => 'Ingrese una fecha valida'
        ),
        'baja' => array(
            'rule'     => array('date','dmy'),

            'message' => 'Ingrese una fecha valida'
        )
    );


	public function beforeSave($options = Array()) {
        if (!empty($this->data['Unidad']['habilitacion'])) {
            $this->data['Unidad']['habilitacion'] = $this->dateFormatBeforeSave($this->data['Unidad']['habilitacion']);

        }
		if (!empty($this->data['Unidad']['baja'])) {
            $this->data['Unidad']['baja'] = $this->dateFormatBeforeSave($this->data['Unidad']['baja']);

        }

        return true;
    }

	public function afterFind($results, $primary = false) {
        foreach ($results as $key => $val) {
            if (!empty($val) and isset($val['Unidad']['habilitacion'])) {
                $results[$key]['Unidad']['habilitacion']= $this->dateFormatAfterFind($val['Unidad']['habilitacion']);
            }
            if (!empty($val) and isset($val['Unidad']['baja'])) {
                $results[$key]['Unidad']['baja']= $this->dateFormatAfterFind($val['Unidad']['baja']);
            }

        }
        return $results;
    }
}
?>
