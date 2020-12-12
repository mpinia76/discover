<?php
function _log($msg){
$nombreFile = date('Ymd') . '_log';
         $dt = date('Y-m-d G:i:s');

         $_Log = fopen("logs/" . $nombreFile . ".log", "a+") or die("Operation Failed!");

         fputs($_Log, $dt . " --> " . $msg . "\n");

         fclose($_Log);
}

	function Format_toMoney( $pNum ){

		return( trim( '$'. Format_toDecimal($pNum) ) );

	}

	function Format_toDecimal( $pNum ){
		if ( is_null($pNum) ) {
			return( '0,00' );
		}else{
			return( trim( number_format($pNum, 2, ',', '.') ) );
		}
	}
	
	function Format_toCuil( $dni, $sexo ){
		if( $sexo == 1 )
		//si es masculino
		$Primero = '20';
		else if( $sexo == 2 )
		//si es femenino
		$Primero = '27';
		else
		//si es sociedad
		$Primero = '30';
		
		$multiplicadores = Array('3', '2', '7','6', '5', '4', '3', '2');
		$calculo = (substr($Primero,0,1)*5)+(substr($Primero,1,1)*4);
		
		for($i=0;$i<8;$i++) {
		$calculo += substr($dni,$i,1) * $multiplicadores[$i];
		}
		
		$resto = ($calculo)%11;
		
		if( ( $sexo!='3' ) && ( $resto<=1 ) ){
		if($resto==0){
		$C = '0';
		} else {
		if($sexo==1){
		$C = '9';
		} else {
		$C = '4';
		}
		}
		$Primero = '23';
		} else {
		$C = 11-$resto;
		}
		
		return $cuil_cuit = $Primero . "-" . $dni . "-" . $C;
	}
	
	function textoRadom($length = 8) {
        $string = "";
        $possible = "0123456789abcdfghjkmnpqrstvwxyz";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $string .= $char;
            $i++;
        }
        return $string;
    }
    
    function getRealIP() {
		 $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        elseif(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        elseif(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        elseif(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        elseif(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        elseif(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
	}