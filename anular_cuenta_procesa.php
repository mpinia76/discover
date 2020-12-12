<?php
include_once("config/db.php");
include_once("functions/util.php");


	$cuenta_a_pagar_id = $_GET['dataid'];



$sql = "SELECT * FROM cuenta_a_pagar WHERE id = ".$cuenta_a_pagar_id;

if(mysqli_num_rows(mysqli_query($conn,$sql)) != 0){
	$rsCuentaAPagar = mysqli_fetch_array(mysqli_query($conn,$sql));
	if ($rsCuentaAPagar['estado']==1) {
        if (empty($rsCuentaAPagar['plan_id'])){
            $ok=1;
            $sql = "SELECT * FROM rel_pago_operacion WHERE operacion_id = ".$rsCuentaAPagar['operacion_id']." AND operacion_tipo = '".$rsCuentaAPagar['operacion_tipo']."' and forma_pago = 'tarjeta'";

            $rsTemp = mysqli_query($conn,$sql);
            while($rs = mysqli_fetch_array($rsTemp)){
                $sql = "SELECT id, tarjeta_id FROM tarjeta_consumo WHERE id = ".$rs['forma_pago_id'];
                //echo "<br>".$sql;
                $rsTempTarjeta = mysqli_query($conn,$sql);
                if($rsTarjeta = mysqli_fetch_array($rsTempTarjeta)){
                    $sql = "SELECT fecha FROM tarjeta_consumo_cuota WHERE tarjeta_consumo_id = ".$rsTarjeta['id'];
                    //echo "<br>".$sql;
                    $rsTempTarjetaCuota = mysqli_query($conn,$sql);
                    while($rsTarjetaCuota = mysqli_fetch_array($rsTempTarjetaCuota)){
                        //$sql = "SELECT id FROM tarjeta_resumen WHERE estado = 1  AND tarjeta_id = ".$rsTarjeta['tarjeta_id'];
                        $part=explode("-",$rsTarjetaCuota['fecha']);
                        $sql = "SELECT id FROM tarjeta_resumen WHERE estado = 1 AND CONCAT(ano,mes) = '".$part[0].intval($part[1])."' AND tarjeta_id = ".$rsTarjeta['tarjeta_id'];
                        //echo "<br>".$sql;
                        $rsTempTarjetaResumen = mysqli_query($conn,$sql);
                        if(mysqli_fetch_array($rsTempTarjetaResumen)){
                            $ok=0;
                            echo "<br>No es posible borrar este pago. Se encuentra incluido en un resumen de tarjeta de cr&eacute;dito que ya se encuentra ABONADO";
                            break;
                        }
                    }
                }
            }
            include_once("config/user.php");
            if(!ACCION_139){
                $sql = "SELECT * FROM rel_pago_operacion WHERE operacion_id = ".$rsCuentaAPagar['operacion_id']." AND operacion_tipo = '".$rsCuentaAPagar['operacion_tipo']."' and forma_pago = 'efectivo'";

                $rsTemp = mysqli_query($conn,$sql);
                while($rs = mysqli_fetch_array($rsTemp)){
                    $sql = "SELECT id, caja_id, fecha FROM caja_movimiento WHERE origen = 'efectivo_consumo' AND registro_id = ".$rs['forma_pago_id'];
                    $rsTempCajaMovimiento = mysqli_query($conn,$sql);
                    if($rsCajaMovimiento = mysqli_fetch_array($rsTempCajaMovimiento)){
                        $sql = "SELECT MAX(fecha) AS fecha
					FROM caja_sincronizada
					WHERE caja_id = ".$rsCajaMovimiento['caja_id'];

                        $rsCajaSincronizada = mysqli_fetch_array(mysqli_query($conn,$sql));
                        $fecha_sincronizacion = $rsCajaSincronizada['fecha'];
                        if ($fecha_sincronizacion>=$rsCajaMovimiento['fecha']) {
                            $ok=0;
                            echo "<br>No es posible borrar este pago. La caja se encuentra conciliada y sincronizada para la fecha del movimiento que intenta realizar. Contactar administrador";
                            break;
                        }

                    }
                }
            }
            if ($ok) {
                $sql = "SELECT * FROM rel_pago_operacion WHERE operacion_id = ".$rsCuentaAPagar['operacion_id']." AND operacion_tipo = '".$rsCuentaAPagar['operacion_tipo']."'";

                $rsTemp = mysqli_query($conn,$sql);
                while($rs = mysqli_fetch_array($rsTemp)){

                    switch($rs['forma_pago']){
                        case 'cheque':
                            $sql = "DELETE FROM cuenta_movimiento WHERE origen = 'cheque' AND registro_id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            //_log($sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>Forma de pago: cheque";
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de cuenta";
                            }

                            $sql = "DELETE FROM cheque_consumo WHERE id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            //_log($sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>&nbsp; &nbsp; Eliminando el cheque emitido";
                            }
                            break;

                        case 'debito':
                            $sql = "DELETE FROM cuenta_movimiento WHERE id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>Forma de pago: debito de cuenta";
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de cuenta";
                            }
                            break;

                        case 'efectivo':

                            $sql = "DELETE FROM caja_movimiento WHERE origen = 'efectivo_consumo' AND registro_id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>Forma de pago: efectivo";
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de la caja";
                            }

                            $sql = "DELETE FROM efectivo_consumo WHERE id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de efectivo";
                            }

                            break;

                        case 'tarjeta':

                            $sql = "DELETE FROM tarjeta_consumo_cuota WHERE tarjeta_consumo_id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>Forma de pago: tarjeta";
                                echo "<br>&nbsp; &nbsp; Eliminando las cuotas";
                            }

                            $sql = "DELETE FROM tarjeta_consumo WHERE id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de tarjeta";
                            }

                            break;

                        case 'transferencia':
                            $sql = "DELETE FROM cuenta_movimiento WHERE origen = 'transferencia' AND registro_id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>Forma de pago: transferencia";
                                echo "<br>&nbsp; &nbsp; Eliminando el movimiento de cuenta";
                            }

                            $sql = "DELETE FROM transferencia_consumo WHERE id = ".$rs['forma_pago_id'];
                            mysqli_query($conn,$sql);
                            if(mysqli_affected_rows($conn) > 0){
                                echo "<br>&nbsp; &nbsp; Eliminando el consumo de transferencia";
                            }
                            break;
                    }

                    echo "<br>Eliminando datos de tabla relacional";
                    $sql = "DELETE FROM rel_pago_operacion WHERE operacion_id = ".$rsCuentaAPagar['operacion_id']." AND operacion_tipo = '".$rsCuentaAPagar['operacion_tipo']."'";

                    mysqli_query($conn,$sql);


                    $sql = "UPDATE cuenta_a_pagar SET
										estado=0,fecha_pago=null
									WHERE id=".$cuenta_a_pagar_id;

                    //echo $sql;
                    mysqli_query($conn,$sql);
                    echo "<br>Actualizando a NO pagada";
                    echo "<br>Listo!";
                }
            }
        }else{
            echo "La cuenta se encuentra incluída en un plan de pagos";
        }




	}else{
		echo "La cuenta debe estar en estado pagada";
	}



}else{
	echo "No hay registro";
}

?>
