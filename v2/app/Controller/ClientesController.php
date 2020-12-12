<?php
class ClientesController extends AppController {
    public $scaffold;
    
	public function autoComplete(){
	    Configure::write('debug', 0);
	    $this->autoRender=false;
	    $this->layout = 'ajax';
	
	    $query = $_GET['term'];
	    $this->loadModel('Pais');
	    $paises = $this->Pais->find('all', array(
	        'conditions' => array('nombre LIKE' => '%' . $query . '%'),
	        'fields' => array('nombre', 'id')));
	    $i=0;
	    //print_r($paises);
	    foreach($paises as $pais){
	        $response[$i]['id']=utf8_encode($pais['Pais']['nombre']);
	        $response[$i]['label']=utf8_encode($pais['Pais']['nombre']);
	        $response[$i]['value']=utf8_encode($pais['Pais']['nombre']);
	        $i++;
	    }
	    echo json_encode($response);
	}
}
?>
