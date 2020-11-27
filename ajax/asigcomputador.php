<?php 
session_start();

require_once "../modelos/AsigComputador.php";
require_once "../modelos/Computador.php";

$asigcomputador = new AsigComputador();
$computador = new Computador();


$idasigcompu=isset($_POST["idasigcompu"])?limpiarCadena($_POST["idasigcompu"]):"";
$idcomputador=isset($_POST["idcomputador"])?limpiarCadena($_POST["idcomputador"]):"";
$idempleado=isset($_POST["idempleado"])?limpiarCadena($_POST["idempleado"]):"";
$nomequipo=isset($_POST["nomequipo"])?limpiarCadena($_POST["nomequipo"]):"";
$ip=isset($_POST["ip"])?limpiarCadena($_POST["ip"]):"";
$usuario=isset($_POST["usuario"])?limpiarCadena($_POST["usuario"]):"";
$pass=isset($_POST["pass"])?limpiarCadena($_POST["pass"]):"";
$detalle=mb_strtoupper(isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"");
$condicion=isset($_POST["condicion"])?limpiarCadena($_POST["condicion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$iduser=$_SESSION['iduser'];
		if(empty($idasigcompu)){
            //var_dump($idcomputador, $idempleado, $nomequipo, $usuario, $pass, $ip, $iduser);
			$rspta=$asigcomputador->insertar($idcomputador, $idempleado, $nomequipo, $usuario, $pass, $ip, $iduser);
			if($rspta){
				$computador->asignar($idcomputador);
				echo "ASIGNACION REALIZADA";
			}else{
				echo "ASIGNACION NO PUDO SER REALIZADA";
			}
		}
		else{

        }
        connClose();
    break;

    case 'desactivar':

        if($condicion==0){  //devolucion de computador.
            $computador->liberar($idcomputador);
        }elseif ($condicion==2) {   //devolucion por computador dañado.
            $computador->asignar($idcomputador);
            $computador->cambiarCondicion($idcomputador,$condicion);
        }elseif ($condicion==3) {   //devolucion por computador robado.
            $computador->asignar($idcomputador);
            $computador->cambiarCondicion($idcomputador,$condicion);
        }

        $computador->cambiarEstadoUsado($idcomputador); //cambia estado de computador a usado.
        $rspta=$asigcomputador->cambiarCondicion($idasigcompu,$condicion); //actualiza la condicion de la asignacion.

        echo $rspta ? "Asignacion inhabilitada" : "Asignacion no se pudo inhabilitar";
        connClose();
    break;

	case 'mostar':
		$rspta=$asigcomputador->mostrar($idasigcompu);
        echo json_encode($rspta);
        connClose();
    break;
        
    case 'pdfacta':
        $asigcomputador->generaracta($idasigcompu);
        $rspta=$asigcomputador->actapdf($idasigcompu);
        echo json_encode($rspta);
        connClose();
    break;
            
    case 'pdfdev':
        $asigcomputador->guardarMotivoDevolucion($idasigcompu,$detalle);
        $asigcomputador->generardevolucion($idasigcompu);
        $rspta=$asigcomputador->devpdf($idasigcompu);
        echo json_encode($rspta);
        connClose();
    break;
			
	case 'listar':
		$rspta=$asigcomputador->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){    
            if(is_null($reg->acta)){
                $respacta='<span class="label bg-blue">SIN GENERAR</span>';
            }else{
                if($reg->acta){
                    $respacta='<span class="label bg-green">ENTREGADO</span>';
                }else{
                    $respacta='<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkacta('.$reg->idasigcompu.')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
                }
            }
            
            if(is_null($reg->devolucion)){
                $respdev='<span class="label bg-blue">SIN GENERAR</span>';
            }else{
                if($reg->devolucion){
                    $respdev='<span class="label bg-green">ENTREGADO</span>';
                }else{
                    $respdev='<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkdev('.$reg->idasigcompu.')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
                }
            }            
            
            if((is_null($reg->acta) && $reg->condicion ) || (!$reg->acta && $reg->condicion)){
                $opciones='<button class="btn btn-xs" onclick="acta('.$reg->idasigcompu.')" data-tooltip="tooltip" title="Acta Entrega"><i class="fa fa-file-text-o"></i></button>';
            }elseif($reg->acta && (is_null ($reg->devolucion) || !$reg->devolucion ) && $reg->condicion){
                $opciones='<button class="btn btn-xs" onclick="devolucion('.$reg->idasigcompu.')" data-tooltip="tooltip" title="Acta Devolucion"><i class="fa fa-file-text-o"></i></button>';
            }elseif($reg->devolucion && $reg->condicion){
                $opciones='<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idasigcompu.','.$reg->idcomputador.')" data-tooltip="tooltip" title="Inhabilitar"><i class="fa fa-close"></i></button>';
            }elseif(!$reg->condicion){
                $opciones='<span class="label bg-red">INHABILITADO</span>';
            }
            
            $data[] = array(
                "0"=>$opciones,
                "1"=>$reg->nombre.' '.$reg->apellido,
                "2"=>$reg->tipo.' '.$reg->marca.' '.$reg->modelo,
                "3"=>$reg->ip,					
                "4"=>$reg->usuario,
                "5"=>$reg->pass,
                "6"=>$reg->fecha,
                "7"=>$respacta,
                "8"=>$respdev
            );
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

        echo json_encode($results);
        connClose();
    break;                
        
    case 'checkacta':
        $rspta=$asigcomputador->checkacta($idasigcompu);
        echo $rspta ? "Acta entrega recibida" : "No pudo realizar la actualizacion";
        connClose();
	break;
    
    case 'checkdevolucion':
        $rspta=$asigcomputador->checkdevolucion($idasigcompu);
        echo $rspta ? "Acta de devolucion recibida" : "No pudo realizar la actualizacion";
        connClose();
	break;
           
    case 'mostrarAsignaciones':
            
        $rspta=$asigcomputador->mostrarAsignaciones($idempleado);
        $data = Array();
        $k=0;
        while ($reg = $rspta->fetch_object()){
            $k++;    
			$data[] = array(
                "0"=>$k,
                "1"=>$reg->marca.", ".$reg->modelo,
                "2"=>$reg->tipo,
                "3"=>$reg->ip,
                "4"=>$reg->usuario,
                "5"=>$reg->pass,
                "6"=>$reg->created_time,
                "7"=>"",
                "8"=>$reg->condicion_asignacion,
            );
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

		echo json_encode($results);
        connClose();       
	break;
        
    case 'validarIpRegistrada':                
        $rspta = $asigcomputador->validarIpRegistrada($idasigcompu,$ip); 
        echo json_encode($rspta);
        connClose();   
    break;
        
}

 ?>