<?php
class VouchersController extends AppController {
    public $scaffold;
    public $components = array('Mpdf');

    public function actualizar($reserva_id){
        $this->layout = 'form';
        $this->set('voucher',$this->Voucher->find('first'));
        $this->set('reserva_id',$reserva_id);
        //idiomas
        $this->set('idiomas', array('1' => utf8_encode('Espa�ol'), '2' => utf8_encode('Ingl�s'), '3' => utf8_encode('Portugu�s')));
    }

    public function guardar(){
        $this->Voucher->set($this->request->data['Voucher']);
        $this->Voucher->save();

        $this->set('resultado','OK');
        $this->set('mensaje','Datos guardados');
        $this->set('detalle','');

        $this->set('_serialize', array(
            'resultado',
            'mensaje' ,
            'detalle'
        ));
    }

    public function ver($reserva_id,$idioma, $output='D'){
        $this->layout = 'voucher';
        $voucher = $this->Voucher->find('first');
        $this->set('voucher',$voucher);

        switch ($idioma) {
        	case 1:
        		$this->set('solo_desde','solo desde');
        		$this->set('confirmacion_reserva',utf8_encode('Confirmaci�n de la reserva'));
        		$this->set('titular',utf8_encode('Titular de la reserva'));
        		$this->set('total_pasajeros',utf8_encode('Cantidad de pasajeros'));
        		$this->set('adultos',utf8_encode('Mayores'));
        		$this->set('menores',utf8_encode('Menores'));
        		$this->set('bebes',utf8_encode('Beb�s'));
        		$this->set('categoria_label',utf8_encode('Categoría'));
				$this->set('descripcion_label',utf8_encode('Descripción'));
        		$this->set('lugar_retiro',utf8_encode('Lugar Retiro'));
        		$this->set('retiro',utf8_encode('Retiro'));
        		$this->set('lugar_devolucion',utf8_encode('Lugar Devoluci�n'));
        		$this->set('devolucion',utf8_encode('Devoluci�n'));
        		$this->set('seguro',utf8_encode('Seguro contratado'));
        		$this->set('saldo',utf8_encode('Saldo a pagar'));
        		$this->set('numero',utf8_encode('N�mero de la reserva'));
        		$this->set('restricciones',($voucher['Voucher']['restricciones']));
        		$this->set('politica_cancelacion_label',utf8_encode('Pol�tica de cancelaci�n'));
        		$this->set('politica_cancelacion',($voucher['Voucher']['politica_cancelacion']));
        		$this->set('gracias',utf8_encode('Gracias por habernos elegido!'));
        		$nombreArchivo='Reserva';
        	break;

        	case 2:
        		$this->set('solo_desde','only from');
        		$this->set('confirmacion_reserva',utf8_encode('Booking confirmation'));
        		$this->set('titular',utf8_encode('Reservation Holder'));
        		$this->set('total_pasajeros',utf8_encode('Total passengers'));
        		$this->set('adultos',utf8_encode('Adults'));
        		$this->set('menores',utf8_encode('Childs'));
        		$this->set('bebes',utf8_encode('Babies'));
        		$this->set('categoria_label',utf8_encode('Category'));
				$this->set('descripcion_label',utf8_encode('Description'));
        		$this->set('lugar_retiro',utf8_encode('Pick up location'));
        		$this->set('retiro',utf8_encode('Pick up date and time'));
        		$this->set('lugar_devolucion',utf8_encode('Drop off location'));
        		$this->set('devolucion',utf8_encode('Drop off date and time'));
        		$this->set('seguro',utf8_encode('Insurance Included'));
        		$this->set('saldo',utf8_encode('Balance to pay'));
        		$this->set('numero',utf8_encode('Reservation Number'));
        		$this->set('restricciones',($voucher['Voucher']['restricciones_en']));
        		$this->set('politica_cancelacion_label',utf8_encode('Cancellation policy'));
        		$this->set('politica_cancelacion',($voucher['Voucher']['politica_cancelacion_en']));
        		$this->set('gracias',utf8_encode('Thanks for choosing us!'));
        		$nombreArchivo='Booking';
        	break;

        	case 3:
        		$this->set('solo_desde','somente desde');
        		$this->set('confirmacion_reserva',utf8_encode('confirma��o da reserva'));
        		$this->set('titular',utf8_encode('Propriet�rio da reserva'));
        		$this->set('total_pasajeros',utf8_encode('N�mero de passageiros'));
        		$this->set('adultos',utf8_encode('Adultos'));
        		$this->set('menores',utf8_encode('Crian�as'));
        		$this->set('bebes',utf8_encode('Beb�s'));
        		$this->set('categoria_label',utf8_encode('Categoria'));
				$this->set('descripcion_label',utf8_encode('Descrição'));
        		$this->set('lugar_retiro',utf8_encode('Local da retirada'));
        		$this->set('retiro',utf8_encode('Data e hora da retirada'));
        		$this->set('lugar_devolucion',utf8_encode('Local da devolu��o'));
        		$this->set('devolucion',utf8_encode('Data e hora da devolu��o'));
        		$this->set('seguro',utf8_encode('Seguro inclu�do'));
        		$this->set('saldo',utf8_encode('Saldo para pagar'));
        		$this->set('numero',utf8_encode('N�mero da reserva'));
        		$this->set('restricciones',($voucher['Voucher']['restricciones_po']));
        		$this->set('politica_cancelacion_label',utf8_encode('Pol�tica de cancelamento'));
        		$this->set('politica_cancelacion',($voucher['Voucher']['politica_cancelacion_po']));
        		$this->set('gracias',utf8_encode('Obrigado por nos escolher!'));
        		$nombreArchivo='Reserva';
        	break;
        }

        $this->loadModel('Reserva');
        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        $this->set('reserva',$reserva);

        $this->loadModel('Categoria');
		$this->Categoria->id = $reserva['Unidad']['categoria_id'];
		$categoria = $this->Categoria->read();
		$this->set('categoria',$categoria);

		switch ($idioma) {
			case 1:
				$this->set('descripcion',$categoria['Categoria']['descripcion']);
				break;

			case 2:
				$this->set('descripcion',$categoria['Categoria']['descripcion_ingles']);
				break;

			case 3:
				$this->set('descripcion',$categoria['Categoria']['descripcion_portugues']);
				break;
		}

		$this->set('idioma',$idioma);
        $extras = $this->Reserva->ReservaExtra->find('all',array('conditions' => array('reserva_id' => $reserva_id),'recursive' => 2));
        $this->set('extras',$extras);

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

        $this->set('pagado',$pagado);
        $this->set('pendiente',$reserva['Reserva']['total'] - $descontado - $pagado);
        $this->set('total',$reserva['Reserva']['total'] - $descontado);

       $fileName = ($output=='F')?'files/'.$nombreArchivo.'('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_voucher_'.date('d_m_Y').'.pdf':$nombreArchivo.'('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_voucher_'.date('d_m_Y').'.pdf';

        //genero el pdf



        require_once '../../vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($this->render());
        $mpdf->Output($fileName,$output);
    }

	public function formMail($reserva_id,$idioma){
        $this->layout = 'form';



        $this->loadModel('Reserva');
        $this->Reserva->id = $reserva_id;
        $reserva = $this->Reserva->read();
        $this->set('reserva',$reserva);
        $this->set('idioma',$idioma);
        //print_r($reserva);

    }

	public function enviar(){
 	 	$this->layout = 'json';

        if(!empty($this->request->data)) {

        	$errores=array();
        	$mails=$this->request->data['Voucher']['mails'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Voucher']['mails'][] = 'Error en el/los mail/s';
	            }

        	}
        	/*$mails=$this->request->data['Voucher']['mailsCC'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Voucher']['mailsCC'][] = 'Error en el/los mail/s';
	            }

        	}*/
        	$mails=$this->request->data['Voucher']['mailsCCO'];
        	$mailsArray=explode(",",$mails);
        	$this->loadModel('EmailValidate');

        	foreach ($mailsArray as $mail) {
        		$this->EmailValidate->set('email',trim($mail));

        		//print_r($this->EmailValidate);
	        	 if(!$this->EmailValidate->validates()){


	                $errores['Voucher']['mailsCCO'][] = 'Error en el/los mail/s';
	            }

        	}

            if(isset($errores) and count($errores) > 0){
                $this->set('resultado','ERROR');
                $this->set('mensaje','No se pudo enviar');
                $this->set('detalle',$errores);
            }else{
            	$idioma=$this->request->data['Voucher']['idioma'];
	            switch ($idioma) {
		        	case 1:
		        		$nombreArchivo='Reserva';
		        		$asunto='Confirmaci�n de reserva - Discover Ushuaia Rent a Car';
		        		$estimado='Estimada/o';
		        		$primerParrafo='�Gracias por habernos elegido!Le enviamos de manera adjunta el voucher que confirma su reserva.';
		        		$segundoParrafo='Si la entrega del veh�culo fue solicitada en aeropuerto, lo estaremos esperando en el �rea de desembarque con un cartel con su nombre.';
		        		$tercerParrafo='En caso de modificar hora y/o lugar de entrega y/o devoluci�n le agradecemos nos lo informen con anticipaci�n para evitar penalidades.';
		        		$cuartoParrafo='Quedamos a su disposici�n para todas las consultas que quiera realizar, ser� un placer atenderlos!';
		        		$saludo='Un saludo Cordial';
		        	break;

		        	case 2:
		        		$nombreArchivo='Booking';
		        		$asunto='Confirmation - Discover Ushuaia Rent a Car';
		        		$estimado='Dear';
		        		$primerParrafo='Thank you for your reservation! We send you the voucher attached that confirms your reservation.';
		        		$segundoParrafo='If the pick up of the vehicle was requested at the airport, we will be waiting for you at the arrival gate holding a sign with your name.';
		        		$tercerParrafo='In case of changing time and/or place of pick up and/or drop off, we thank you inform us in advance to avoid penalties.';
		        		$cuartoParrafo='We are at your disposal for any queries you wish to make, it will be a pleasure to receive you.';
		        		$saludo='Sincerely';
		        	break;

		        	case 3:
		        		$nombreArchivo='Reserva';
		        		$asunto='Confirma��o de Reserva - Discover Ushuaia Rent a Car';
		        		$estimado='Prezado';
		        		$primerParrafo='�Obrigado por sua reserva!Segue, em anexo, o voucher de confirma��o.';
		        		$segundoParrafo='Se a entrega do ve�culo foi solicitada no aeroporto, estaremos esperando por voc� na �rea de desembarque com uma placa com o seu nome.';
		        		$tercerParrafo='Se voc� quiser mudar a hora e/ou lugar da entrega e/ou retorno � necess�rio nos informar com anteced�ncia para evitar penalidades.';
		        		$cuartoParrafo='Ficamos a sua disposi��o para outras eventuais consultas. Sempre ser� um prazer atend�-lo.';
		        		$saludo='Sauda��es';
		        	break;
		        }
            	$this->loadModel('Reserva');
		        $this->Reserva->id = $this->request->data['Voucher']['reserva_id'];
		        $reserva = $this->Reserva->read();
            	$fileName = $nombreArchivo.'('.$reserva['Reserva']['numero'].')_'.$reserva['Cliente']['nombre_apellido'].'_voucher_'.date('d_m_Y').'.pdf';
            	$file ='files/'.$fileName;

            	if(is_file($file)){
				       // echo 'esta';
				        $fp =    @fopen($file,"rb");
				        $data =  @fread($fp,filesize($file));

				        @fclose($fp);


						$attachment = chunk_split(base64_encode($data));
            	}

				$textMessage = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY><div class=""><div class="aHl"></div><div id=":od" tabindex="-1"></div><div id=":o2" class="ii gt">
		<div id=":o1" class="a3s aXjCH "><div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:#666666">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)"><div dir="ltr">
		<div class="gmail_default" style="font-family:verdana,sans-serif;color:rgb(102,102,102)">
		<div dir="ltr"><div class="gmail_default" style="font-family:verdana,sans-serif">


		<span class="m_5146862768849835395m_-4273096359447565817gmail-m_2461132594155676157gmail-m_-1279388942409998069gmail-im" style="font-family:tahoma,sans-serif">
		<font color="#444444"><p class="MsoNormal" style="margin-bottom:0.0001pt;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif">'.$estimado.' '.$reserva['Cliente']['nombre_apellido'];
		$textMessage .= '</span></font></p>



		<br>

		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444">'.utf8_encode($primerParrafo).'</font></span></p>
		<br>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444"><strong>'.utf8_encode($segundoParrafo).'</strong></font></span></p>
		<br>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444"><strong>'.utf8_encode($tercerParrafo).'</strong></font></span></p>
		<br>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444">'.utf8_encode($cuartoParrafo).'</font></span></p>
		<br>
		<p class="MsoNormal" style="margin-bottom:0.0001pt;font-family:tahoma,sans-serif;text-align:left;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
		<span style="font-family:verdana,sans-serif"><font color="#444444">'.utf8_encode($saludo).',</font></span></p>
		<br>
		<i style="font-size:x-small">
		<img src="http://104.238.101.101/ushuaia/images/Banner_USH.png" style="margin-right:0px" data-image-whitelisted="" class="CToWUd" width="350"></i></div></div>
<br></div></div>
<br></div></div>
<br></div></div>
<br></div></div>
<br><p></p><p class="MsoNormal" style="color:rgb(68,68,68);margin-bottom:0.0001pt;font-family:tahoma,sans-serif;font-size:12.8px;text-align:center;background-image:initial;background-size:initial;background-origin:initial;background-clip:initial;background-position:initial;background-repeat:initial" align="left">
<span style="font-size:8.5pt;font-family:verdana,sans-serif"><br></span></p></div></div><div class="yj6qo"></div><div class="adL">
<br></div></div></div><div class="adL">
<br></div></div></div><div class="adL">
<br></div></div></div><div class="adL">
</div></div></div><div id=":og" class="ii gt" style="display:none"><div id=":oh" class="a3s aXjCH undefined"></div></div>
<div class="hi"></div></div></BODY>
</HTML>';




	    $separator = md5(uniqid(time()));
		// carriage return type (we use a PHP end of line constant)
		$eol = PHP_EOL;
		// attachment name


		// main header (multipart mandatory)
		$headers  = "From: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> ".$eol;

	    $headers .= "Return-path: Discover Ushuaia-rent a car <info@discoverushuaia.com.ar> ".$eol;
	    //$headers .= "CC: ".$this->request->data['Voucher']['mailsCC']." \r\n";
	    $headers .= "BCC: ".$this->request->data['Voucher']['mailsCCO']." \r\n";
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




	    $success= mail($this->request->data['Voucher']['mails'], utf8_encode($asunto), $nmessage, $headers, "-finfo@discoverushuaia.com.ar");

		/*echo $this->request->data['Voucher']['mails'].", ".utf8_encode("Confirmaci�n de Reserva - Discover Ushuaia-rent a car").", ".$textMessage.", ". $headers.", -finfo@discoverushuaia.com.ar";*/
		if ($success){
			$enviada = ($reserva['Reserva']['voucher'])?1:$reserva['Reserva']['voucher'] + 1;
			$this->Reserva->set('voucher',$enviada);
		    $this->Reserva->save();
			$this->set('resultado','OK');
            $this->set('mensaje','Voucher enviado');
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

}
?>
