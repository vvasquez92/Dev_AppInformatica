<?php 
session_start();

require_once "../modelos/Asignacion.php";
require_once "../modelos/Equipo.php";
require_once "../modelos/Chip.php";

$asignacion = new Asignacion();
$equipo = new Equipo();
$chip = new Chip();

$idasignacion=isset($_POST["idasignacion"])?limpiarCadena($_POST["idasignacion"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$idequipo=isset($_POST["idequipo"])?limpiarCadena($_POST["idequipo"]):"";
$idempleado=isset($_POST["idempleado"])?limpiarCadena($_POST["idempleado"]):"";
$idchip=isset($_POST["idchip"])?limpiarCadena($_POST["idchip"]):"";
$tasignacion=isset($_POST["tasignacion"])?limpiarCadena($_POST["tasignacion"]):"";
$condicion=isset($_POST["condicion"])?limpiarCadena($_POST["condicion"]):"";
$fromdate = date("Y-m-d", strtotime($fecha)); 
$detalle = mb_strtoupper(isset($_POST['detalle']) ? limpiarCadena($_POST['detalle']) : "");

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$iduser=$_SESSION['iduser'];
		if(empty($idasignacion)){
			$rspta=$asignacion->insertar($fromdate,$idequipo,$idempleado,$idchip,$tasignacion,$iduser);
			if($rspta){
				$equipo->asignar($idequipo);
				$chip->asignar($idchip);
				echo "Asignacion realizada";
			}else{
				echo "Asignacion no pudo ser realizada";
			}
		}
		else{
			$rspta=$asignacion->editar($idasignacion,$fromdate,$idequipo,$idempleado, $idchip, $iduser);
			echo $rspta ? "Asignacion editada" : "Asginacion no pudo ser editada";
		}
		connClose();
	break;

	case 'desactivar':  
            
		$equipo->cambiarEstado($idequipo);  
		$equipo->liberar($idequipo);
		
		if($condicion==0){  //devolucion normal de telefono.
												
			$equipo->activar($idequipo);
			$chip->liberar($idchip);
			$chip->activar($idchip);
			$rspta=$asignacion->cambiarCondicion($idasignacion,$condicion);           	
			
		}elseif ($condicion==2) { //telefono devuelto dañado.
								
			$equipo->cambiarCondicion($idequipo, $condicion);
			$chip->liberar($idchip);
			$chip->activar($idchip);
			$rspta=$asignacion->cambiarCondicion($idasignacion,$condicion);
					
		}elseif ($condicion==3) {   //telefono robado.
								
			$equipo->cambiarCondicion($idequipo, $condicion);
			$chip->asignar($idchip);
			$chip->cambiarCondicion($idchip,$condicion);
			$rspta=$asignacion->cambiarCondicion($idasignacion,$condicion);
	
		}
		echo $rspta ? "Asignacion inhabilitada" : "Asignacion no se pudo inhabilitar";
		connClose();
	break;
/*
	case 'activar':
		$rspta=$asignacion->activar($idasignacion);
		echo $rspta ? "Asignacion habilitada" : "Asignacion no se pudo habilitar";
		connClose();
	break;
*/
	case 'mostar':
		$rspta=$asignacion->mostrar($idasignacion);
		echo json_encode($rspta);
		connClose();
	break;
            
	case 'pdfcontrato':
		$asignacion->generarcontrato($idasignacion);
		$rspta=$asignacion->contratopdf($idasignacion);
		echo json_encode($rspta);
		connClose();
	break;
        
	case 'pdfacta':
		$asignacion->generaracta($idasignacion);
		$rspta=$asignacion->contratopdf($idasignacion);
		echo json_encode($rspta);
		connClose();
	break;
            
	case 'pdfdev':
		$asignacion->guardarMotivoDevolucion($idasignacion,$detalle);
		$asignacion->generardevolucion($idasignacion);
		$rspta=$asignacion->contratopdf($idasignacion);
		echo json_encode($rspta);
		connClose();
	break;
			
	case 'listar':
		$rspta=$asignacion->listar();

		$data = Array();
		while ($reg = $rspta->fetch_object()){
                    
			if(is_null($reg->contrato)){
				$respcontrato='<span class="label bg-blue">SIN GENERAR</span>';
			}else{
				if($reg->contrato){
					$respcontrato='<span class="label bg-green">ENTREGADO</span>';
				}else{
					$respcontrato='<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkcontrato('.$reg->idasignacion.')" data-tooltip="tooltip" title="Marcar contrato entregado"><i class="fa fa-check-square-o"></i></button>';
				}
			}

			if(is_null($reg->acta)){
				$respacta='<span class="label bg-blue">SIN GENERAR</span>';
			}else{
				if($reg->acta){
					$respacta='<span class="label bg-green">ENTREGADO</span>';
				}else{
					$respacta='<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkacta('.$reg->idasignacion.')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
				}
			}
			
			if(is_null($reg->devolucion)){
				$respdev='<span class="label bg-blue">SIN GENERAR</span>';
			}else{
				if($reg->devolucion){
					$respdev='<span class="label bg-green">ENTREGADO</span>';
				}else{
					$respdev='<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkdev('.$reg->idasignacion.')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
				}
			}
			
			
			if((is_null($reg->acta) || is_null($reg->contrato)) || $reg->acta == 0 || $reg->contrato == 0){
				$opciones='<button class="btn btn-xs" onclick="contrato('.$reg->idasignacion.')" data-tooltip="tooltip" title="Anexo Contrato"><i class="fa fa-file-text-o"></i></button>'.
							'<button class="btn btn-xs" onclick="acta('.$reg->idasignacion.')" data-tooltip="tooltip" title="Acta Entrega"><i class="fa fa-file-text-o"></i></button>'.
							'<button class="btn btn-warning btn-xs" onclick="" data-tooltip="tooltip" title="Mostrar"><i class="fa fa-pencil"></i></button>';
			}elseif($reg->acta == 1 && $reg->contrato == 1 && is_null($reg->devolucion)){
				$opciones='<button class="btn btn-xs" onclick="devolucion('.$reg->idasignacion.')" data-tooltip="tooltip" title="Acta Devolucion"><i class="fa fa-file-text-o"></i></button>'.
							'<button class="btn btn-warning btn-xs" onclick="" data-tooltip="tooltip" title="Mostrar"><i class="fa fa-pencil"></i></button>';
			}elseif($reg->acta == 1 && $reg->contrato == 1 && $reg->devolucion == 0){
				$opciones='<button class="btn btn-xs" onclick="devolucion('.$reg->idasignacion.')" data-tooltip="tooltip" title="Acta Devolucion"><i class="fa fa-file-text-o"></i></button>'.
							'<button class="btn btn-warning btn-xs" onclick="" data-tooltip="tooltip" title="Mostrar"><i class="fa fa-pencil"></i></button>';
			}elseif($reg->devolucion){
				$opciones='<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idasignacion.','.$reg->idequipo.','.$reg->idchip.')" data-tooltip="tooltip" title="Inhabilitar"><i class="fa fa-close"></i></button>'.
							'<button class="btn btn-warning btn-xs" onclick="" data-tooltip="tooltip" title="Mostrar"><i class="fa fa-pencil"></i></button>';
			}
														
			$data[] = array(
					"0"=>$opciones,
					"1"=>$reg->nombre.' '.$reg->apellido,
					"2"=>$reg->num_documento,
					"3"=>$reg->marca.' '.$reg->equipo,
					"4"=>$reg->imei,
					"5"=>$reg->numero,
					"6"=>$reg->created_time,
					"7"=>$reg->tasignacion,
					"8"=>$respcontrato,
					"9"=>$respacta,
					"10"=>$respdev
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
                
	case 'listartelf':
		$rspta=$asignacion->listartelf();
		$data = Array();
		while ($reg = $rspta->fetch_object()){                    
			$data[] = array(		
					"0"=>$reg->nombre.' '.$reg->apellido,
					"1"=>$reg->num_documento,
                                        "2"=>$reg->num_documento,
					"3"=>$reg->marca.' '.$reg->equipo,
					"4"=>$reg->imei,
                                        "5"=>$reg->numero,
                                        "6"=>$reg->operador,
					"7"=>$reg->created_time,
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
        
	case 'checkcontrato':
		$rspta=$asignacion->checkcontrato($idasignacion);
		echo $rspta ? "Anexo de contrato recibido" : "No pudo realizar la actualizacion";
		connClose();
	break;
        
	case 'checkacta':
		$rspta=$asignacion->checkacta($idasignacion);
		echo $rspta ? "Acta entrega recibida" : "No pudo realizar la actualizacion";
		connClose();
	break;
    
	case 'checkdevolucion':
		$rspta=$asignacion->checkdevolucion($idasignacion);
		echo $rspta ? "Acta de devolucion recibida" : "No pudo realizar la actualizacion";
		connClose();
	break;
    
	case 'mostrarAsignaciones':
		$rspta=$asignacion->mostrarAsignaciones($idempleado);
		$data = Array();
		$k=0;
		while ($reg = $rspta->fetch_object()){
            $k++;    
			$data[] = array(
				"0"=>$k,
				"1"=>$reg->marca.", ".$reg->equipo,
				"2"=>$reg->imei,
				"3"=>$reg->numero,
				"4"=>$reg->fecha_asignacion,
				"5"=>"",
				"6"=>$reg->condicion_asignacion
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

	case 'selectTipoMotivo':
		$rspta = $asignacion->selectTipoMotivo();
					echo '<option value="" selected disabled>SELECCIONE MOTIVO</option>';
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->idtipo_devolucion.'>'.$reg->desc_tipo_devolucion.'</option>';
		}
		connClose();
	break;
            

}

 ?>