<?php 
session_start();

require_once "../modelos/Computador.php";

$computador = new Computador();

$idcomputador=isset($_POST["idcomputador"])?limpiarCadena($_POST["idcomputador"]):"";
$idmarca=isset($_POST["idmarca"])?limpiarCadena($_POST["idmarca"]):"";
$modelo=isset($_POST["modelo"])?limpiarCadena($_POST["modelo"]):"";
$tcomputador=isset($_POST["tcomputador"])?limpiarCadena($_POST["tcomputador"]):"";
$serial=isset($_POST["serial"])?limpiarCadena($_POST["serial"]):"";
$maclan=isset($_POST["maclan"])?limpiarCadena($_POST["maclan"]):"";
$macwifi=isset($_POST["macwifi"])?limpiarCadena($_POST["macwifi"]):"";
$factura_actual=isset($_POST["factura_actual"])?limpiarCadena($_POST["factura_actual"]):"";
$fvencimiento_garantia=isset($_POST["fvencimiento_garantia"])?limpiarCadena($_POST["fvencimiento_garantia"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$precio=isset($_POST["precio"])?limpiarCadena($_POST["precio"]):"";

switch ($_GET["op"]) {
    
	case 'guardaryeditar':
                
                if(!file_exists($_FILES['factura']['tmp_name']) || !is_uploaded_file($_FILES['factura']['tmp_name'])){
			$factura=$factura_actual;
		}else{
			$ext = explode(".",$_FILES['factura']['name']);
			if (($_FILES['factura']['type']) == 'application/pdf'){
                            $factura = round(microtime(true)).".".end($ext);
                            move_uploaded_file($_FILES['factura']['tmp_name'], "../files/facturascomputador/".$factura);
			}
		}
                
		if(empty($idcomputador)){
			$rspta=$computador->insertar($idmarca, $modelo, $tcomputador, $serial, $maclan, $macwifi,$factura,$fvencimiento_garantia, $observaciones, $estado,$precio);
			echo $rspta ? 'Computador registrado' : "Computador no pudo ser registrado";
		}
		else{
			$rspta=$computador->editar($idcomputador, $idmarca, $modelo, $tcomputador, $serial, $maclan, $macwifi,$factura,$fvencimiento_garantia, $observaciones, $estado);
			echo $rspta ? "Computador editado" : "Computador no pudo ser editado";
		}
		connClose();
		break;

	case 'activar':
		$rspta=$computador->activar($idcomputador);
			echo $rspta ? "Computador habilitar" : "Computador no se pudo habilitar";
			connClose();
		break;

	case 'desactivar':
		$rspta=$computador->desactivar($idcomputador);
			echo $rspta ? "Computador inhabilitado" : "Computador no se pudo inhabilitar";
			connClose();
		break;
            
        case 'asignar':
		$rspta=$computador->asignar($idcomputador);
			echo $rspta ? "Computador Asginado" : "Computador no pudo ser asignado";
			connClose();
		break;
        
        case 'liberar':
		$rspta=$computador->liberar($idcomputador);
			echo $rspta ? "Computador liberado" : "Computador no pudo ser liberado";
			connClose();
		break;

	case 'mostar':
		$rspta=$computador->mostrar($idcomputador);
			echo json_encode($rspta);
			connClose();
		break;
			
	case 'listar':
		$rspta=$computador->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idcomputador.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idcomputador.','.$reg->disponible.')" title="Deshabilitar"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idcomputador.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idcomputador.')" title="Habilitar"><i class="fa fa-check"></i></button>',
					"1"=>$reg->tipo,
                                        "2"=>$reg->marca.' '.$reg->modelo,
					"3"=>$reg->serial,
					"4"=>$reg->maclan,
					"5"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
					"6"=>($reg->estado)?'<span class="label bg-green">NUEVO</span>':'<span class="label bg-red">USADO</span>',
					"7"=>($reg->condicion)?'<span class="label bg-green">HABILITADO</span>':'<span class="label bg-red">INHABILITADO</span>'
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

		case 'cargaTotales':
			$rspta=$computador->cargaTotales();
				echo json_encode($rspta);
				connClose();
			break;

		case 'listarhistorico':
			$rspta=$computador->listarhistorico();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>($reg->condicion)?
						' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idcomputador.','.$reg->disponible.')" title="Deshabilitar"><i class="fa fa-close"></i></button>':
						' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idcomputador.')" title="Habilitar"><i class="fa fa-check"></i></button>',
						"1"=>$reg->tipo,
						"2"=>$reg->marca.' '.$reg->modelo,
						"3"=>$reg->serial,
						"4"=>$reg->maclan,
						"5"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
						"6"=>($reg->estado)?'<span class="label bg-green">NUEVO</span>':'<span class="label bg-red">USADO</span>',
						"7"=>($reg->condicion)?'<span class="label bg-green">HABILITADO</span>':'<span class="label bg-red">INHABILITADO</span>'
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
                
	case 'listarComputadoresDocumentacion':
        
		$rspta=$computador->listarComputadoresDocumentacion();
            
		$data = Array();
                $fecha30dias = new DateTime("now");
                $fecha30dias->modify('+1 month');
                
                $date_actual = new DateTime("now"); 
                
		while ($reg = $rspta->fetch_object()){
                    
                  $date_vencimiento_garantia = new DateTime($reg->fvencimiento_garantia);                     

                        $dif = $date_actual->diff($date_vencimiento_garantia);
                        
                        if($dif->invert == 1){
                            
                             $clase_garantia="label label-danger";
                             $texto_garantia="VENCIDA";
                             
                        }else{ 
                            
                            $dif = $fecha30dias->diff($date_vencimiento_garantia);
                            //COMPARO CON LA FECHA ACTUAL +30 DIAS.
                            
                            if($dif->invert == 1){
                                $clase_garantia="label label-warning";
                                $texto_garantia="POR VENCER < 30 dias";
                            } else{
                                $clase_garantia="label label-success";
                                $texto_garantia="VIGENTE > 30 dias";   
                            }                                          
                        }

                        $notificacion_garantia='<span class="'.$clase_garantia.'">'.$texto_garantia.'</span>';
    
                        $boton_factura='';
                        
                        if($reg->factura != ""){    
                            $boton_factura='<button class="btn btn-secondary" onclick="window.open(\'../files/facturascomputador/'.$reg->factura.'\')" data-tooltip="tooltip" title="Factura digital"><i class="fa fa-file-pdf-o"></i></button>';
                        }
                        
			$data[] = array(
                                        "0"=>$reg->tipo,
                                        "1"=>$reg->marca.' '.$reg->modelo,
					"2"=>$reg->serial,
					"3"=>$reg->fvencimiento_garantia.' '.$notificacion_garantia,
                                        "4"=>$boton_factura,
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

		case 'listarComputadoresDocumentacionVencida':
        
			$rspta=$computador->listarComputadoresDocumentacionVencida();
				
			$data = Array();
					$fecha30dias = new DateTime("now");
					$fecha30dias->modify('+1 month');
					
					$date_actual = new DateTime("now"); 
					
			while ($reg = $rspta->fetch_object()){
						
					  $date_vencimiento_garantia = new DateTime($reg->fvencimiento_garantia);                     
	
							$dif = $date_actual->diff($date_vencimiento_garantia);
							
							if($dif->invert == 1){
								
								 $clase_garantia="label label-danger";
								 $texto_garantia="VENCIDA";
								 
							}else{ 
								
								$dif = $fecha30dias->diff($date_vencimiento_garantia);
								//COMPARO CON LA FECHA ACTUAL +30 DIAS.
								
								if($dif->invert == 1){
									$clase_garantia="label label-warning";
									$texto_garantia="POR VENCER < 30 dias";
								} else{
									$clase_garantia="label label-success";
									$texto_garantia="VIGENTE > 30 dias";   
								}                                          
							}
	
							$notificacion_garantia='<span class="'.$clase_garantia.'">'.$texto_garantia.'</span>';
		
							$boton_factura='';
							
							if($reg->factura != ""){    
								$boton_factura='<button class="btn btn-secondary" onclick="window.open(\'../files/facturascomputador/'.$reg->factura.'\')" data-tooltip="tooltip" title="Factura digital"><i class="fa fa-file-pdf-o"></i></button>';
							}
							
				$data[] = array(
											"0"=>$reg->tipo,
											"1"=>$reg->marca.' '.$reg->modelo,
						"2"=>$reg->serial,
						"3"=>$reg->fvencimiento_garantia.' '.$notificacion_garantia,
											"4"=>$boton_factura,
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
                
		case 'selectcomputador':
			$rspta = $computador->selectcomputador();
                        echo '<option value="" selected disabled>SELECCIONE COMPUTADOR</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idcomputador.'>'.$reg->tipo.' / '.$reg->marca.' '.$reg->modelo.' / '.$reg->serial.'</option>';
			}
			connClose();
		break;


}
