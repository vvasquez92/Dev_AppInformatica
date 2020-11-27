<?php 
session_start();

require_once "../modelos/Vehiculo.php";

$vehiculo = new Vehiculo();

$idvehiculo=isset($_POST["idvehiculo"])?limpiarCadena($_POST["idvehiculo"]):"";
$idmarca=isset($_POST["idmarca"])?limpiarCadena($_POST["idmarca"]):"";
$idmodelo=isset($_POST["idmodelo"])?limpiarCadena($_POST["idmodelo"]):"";
$kilometraje=isset($_POST["kilometraje"])?limpiarCadena($_POST["kilometraje"]):"";
$ano=isset($_POST["ano"])?limpiarCadena($_POST["ano"]):"";
$patente=isset($_POST["patente"])?limpiarCadena($_POST["patente"]):"";
$serialmotor=isset($_POST["serialmotor"])?limpiarCadena($_POST["serialmotor"]):"";
$serialcarroceria=isset($_POST["serialcarroceria"])?limpiarCadena($_POST["serialcarroceria"]):"";
$gases=isset($_POST["gases"])?limpiarCadena($_POST["gases"]):"";
$tecnica=isset($_POST["tecnica"])?limpiarCadena($_POST["tecnica"]):"";
$circulacion=isset($_POST["circulacion"])?limpiarCadena($_POST["circulacion"]):"";
$tvehiculo=isset($_POST["tvehiculo"])?limpiarCadena($_POST["tvehiculo"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$id_vehiculo=isset($_POST["id_vehiculo"])?limpiarCadena($_POST["id_vehiculo"]):"";
$doc_gases=isset($_POST["doc_gases"])?limpiarCadena($_POST["doc_gases"]):"";
$doc_tecnica=isset($_POST["doc_tecnica"])?limpiarCadena($_POST["doc_tecnica"]):"";
$doc_circulacion=isset($_POST["doc_circulacion"])?limpiarCadena($_POST["doc_circulacion"]):"";

$fhInstalacionGPS=isset($_POST["fhInstalacionGPS"])?limpiarCadena($_POST["fhInstalacionGPS"]):"";
$sProvGps=isset($_POST["sProvGps"])?limpiarCadena($_POST["sProvGps"]):"";

$tieneGPS=isset($_POST["tieneGPS"])?limpiarCadena($_POST["tieneGPS"]):"";
$instalaGPS=isset($_POST["instalaGPS"])?limpiarCadena($_POST["instalaGPS"]):"";
$sProvGpsNew=isset($_POST["sProvGpsNew"])?limpiarCadena($_POST["sProvGpsNew"]):"";

$motivo=isset($_POST["motivo"])?limpiarCadena($_POST["motivo"]):"";
$idprestamo=isset($_POST["idprestamo"])?limpiarCadena($_POST["idprestamo"]):"";
$idasig=isset($_POST["idasig"])?limpiarCadena($_POST["idasig"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$iduser=$_SESSION['iduser'];
		if(empty($idvehiculo)){
            if ($tieneGPS == 0){
                $sProvGpsNew = 0;
            }
            //$rspta=$vehiculo->insertar($idmarca, $idmodelo, $ano, $kilometraje, $patente, $serialmotor, $serialcarroceria,$gases,$tecnica,$circulacion, $tvehiculo, $observaciones, $estado);
            $existePatente = $vehiculo->CantidadPatentes($patente);
            if ($existePatente == 0){
                $rspta=$vehiculo->insertar($idmarca, $idmodelo, $ano, $kilometraje, $patente, $serialmotor, $serialcarroceria,$gases,$tecnica,$circulacion, $tvehiculo, $observaciones, $estado, $tieneGPS, $instalaGPS, $sProvGpsNew);
                echo $rspta ? 'Vehiculo registrado patente '.$patente.'' : "Vehiculo no pudo ser registrado";
            }
            else{
                echo 'La patente ingresada ya existe. Por favor verifique los datos';
            }
		}
		else{
            $existePatente = $vehiculo->CantidadPatentesEdit($idvehiculo, $patente);
            if ($existePatente == 0){
                $rspta0=$vehiculo->editarGPS($idvehiculo,$tieneGPS, $instalaGPS, $sProvGpsNew);
                $rspta=$vehiculo->editar($idvehiculo, $idmarca,$idmodelo, $ano, $kilometraje, $patente, $serialmotor, $serialcarroceria,$gases,$tecnica,$circulacion, $tvehiculo, $observaciones, $estado, $tieneGPS, $instalaGPS, $sProvGpsNew);
                echo $rspta ? "Vehiculo editado" : "Vehiculo no pudo ser editado";
            }
            else{
                echo 'La patente ingresada ya existe asociada a otro vehículo. Por favor verifique los datos';
            }
        }
        connClose();
		break;

	case 'activar':
                //echo $idvehiculo;
		$rspta=$vehiculo->activar($idvehiculo);
            echo $rspta ? "Vehiculo habilitado" : "Vehiculo no se pudo habilitar";
            connClose();
		break;

	case 'desactivar':
                //echo $idvehiculo;
		$rspta=$vehiculo->desactivar($idvehiculo);
            echo $rspta ? "Vehiculo inhabilitado" : "Vehiculo no se pudo inhabilitar";
            connClose();
		break;
            
        case 'asignar':
		$rspta=$vehiculo->asignar($idvehiculo);
            echo $rspta ? "Vehiculo Asginado" : "Vehiculo no pudo ser asignado";
            connClose();
		break;
        
        case 'liberar':
		$rspta=$vehiculo->liberar($idvehiculo);
            echo $rspta ? "Vehiculo liberado" : "Vehiculo no pudo ser liberado";
            connClose();
		break;

	case 'mostar':
              
		$rspta=$vehiculo->mostrar($idvehiculo);
            echo json_encode($rspta);
            connClose();
		break;
			
	case 'listar':
		$rspta=$vehiculo->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
                    $boton='';
                    if(is_null($reg->gases) || is_null($reg->tecnica) || is_null($reg->circulacion) ){
                        $boton ='<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalDocumentos" data-idvehiculo="'.$reg->idvehiculo.'" title="registrar" ><i class="fa fa-newspaper-o"></i></button>';                        
                    }
                    $disp="";
                    if($reg->disponible==0){
                        if ($reg->taller == null){
                            $disp='<span class="label bg-red">ASIGNADO</span>';
                        }
                        else{
                            $disp='<span class="label bg-orange">ASIGNADO - ' . $reg->taller . '</span>';
                        }
                    }elseif ($reg->disponible==1) {
                        if ($reg->taller == null){
                            $disp='<span class="label bg-green">SIN ASIGNAR</span>';
                        }
                        else{
                            $disp='<span class="label bg-orange">SIN ASIGNAR - ' . $reg->taller . '</span>';
                        }
                    } elseif ($reg->disponible==2) {
                        if ($reg->taller == null){
                            $disp='<span class="label bg-orange">NO DISPONIBLE</span>';
                        }
                        else{
                            $disp='<span class="label bg-orange">NO DISPONIBLE - ' . $reg->taller . '</span>';
                        }
                    }

                    if ($reg->taller == null){
                        $btnTaller = '<button class="btn btn-info btn-xs" data-toggle="tooltip" title="Inspección" onclick="taller('.$reg->idvehiculo.')"><i class="fa fa-wrench"></i></button>';
                    }
                    else{
                        $btnTaller = '';
                    }

                    if ($reg->gps == "NO"){
                        $btnGPS = '<button class="btn btn-primary btn-xs" data-toggle="tooltip" title="GPS" onclick="infoGPS('.$reg->idvehiculo.')"><i class="fa fa-map-marker"></i></button>';
                    }
                    else{
                        $btnGPS = '';
                    }

                    $data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" data-toggle="tooltip" title="Editar" onclick="mostar('.$reg->idvehiculo.')"><i class="fa fa-pencil"></i></button>'.
                    '<button class="btn btn-danger btn-xs"  data-toggle="tooltip" title="Desactivar" onclick="desactivar('.$reg->idvehiculo.','.$reg->disponible.')"><i class="fa fa-close"></i></button>'.$boton.
                    $btnGPS.
                    $btnTaller:
					'<button class="btn btn-warning btn-xs" data-toggle="tooltip" title="Editar" onclick="mostar('.$reg->idvehiculo.')"><i class="fa fa-pencil"></i></button>'.
					'<button class="btn btn-primary btn-xs" data-toggle="tooltip" title="activar" onclick="activar('.$reg->idvehiculo.')"><i class="fa fa-check"></i></button>'.$boton,
					"1"=>$reg->marca.' '.$reg->modelo,
					"2"=>$reg->tipo,
					"3"=>$reg->ano,
                    "4"=>$reg->patente,
                    "5"=>$reg->kilometraje,
                    "6"=>$reg->gps,
					"7"=>$disp,
					"8"=>($reg->estado)?'<span class="label bg-green">NUEVO</span>':'<span class="label bg-red">USADO</span>',
					"9"=>($reg->condicion)?'<span class="label bg-green">HABILITADO</span>':'<span class="label bg-red">INHABILITADO</span>'
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
        
		case 'selectvehiculo':

                $rspta = $vehiculo->selectvehiculo();
                echo '<option value="" selected disabled>SELECCIONE VEHICULO</option>';
                while($reg = $rspta->fetch_object()){
                   
                    if($reg->tiene_fecha_gases==1 
                       && $reg->tiene_fecha_tecnica==1 
                       && $reg->tiene_fecha_circulacion==1 
                       && $reg->cantidad_dias_gases > 0 
                       && $reg->cantidad_dias_tecnica > 0 
                       && $reg->cantidad_dias_circulacion > 0 ){                        
                        echo '<option value='.$reg->idvehiculo.'>'.$reg->marca.' '.$reg->modelo.' / '.$reg->patente.'</option>';                        
                       }
                }
                connClose();
		break;
                
                case 'guardarPlazoDocumentosVehiculo':
                    
                $rspta=$vehiculo->actualizarPlazoDocumentosVehiculo($id_vehiculo,$doc_gases,$doc_tecnica,$doc_circulacion);
                    
                echo $rspta ? "Fechas de Documentos del Vehiculo actualizado" : "Fechas de Documentos del Vehiculo no pudo ser actualizado";                
                connClose();
                break;   
            
        case 'listarVehiculosDocumentacionCompleta':
                
            $rspta=$vehiculo->listarVehiculosDocumentacionCompleta();
            $data = Array();
            $fecha30dias = new DateTime("now");
            $fecha30dias->modify('+1 month');
            $date_actual = new DateTime("now");
                
		    while ($reg = $rspta->fetch_object()){
                        
                $date_gases = new DateTime($reg->gases);                     

                $dif = $date_actual->diff($date_gases);
                
                if($dif->invert == 1){
                        $clase_gases="label label-danger";
                        $texto_gases="VENCIDA";
                }else{ 
                    
                    $dif = $fecha30dias->diff($date_gases);//COMPARO CON LA FECHA ACTUAL +3 DIAS.
                    
                    if($dif->invert == 1){
                        $clase_gases="label label-warning";
                        $texto_gases="POR VENCER < 30 dias";
                    } else{
                        $clase_gases="label label-success";
                        $texto_gases="VIGENTE > 30 dias";
                    }                                          
                }

                $notificacion_gases='<span class="'.$clase_gases.'">'.$texto_gases.'</span>';

                /* -------------------------------------------------------------------------*/
                $date_tecnica = new DateTime($reg->tecnica);                     

                $dif = $date_actual->diff($date_tecnica);
                
                if($dif->invert == 1){
                        $clase_tecnica="label label-danger";
                        $texto_tecnica="VENCIDA";
                }else{ 
                    
                    $dif = $fecha30dias->diff($date_tecnica);//COMPARO CON LA FECHA ACTUAL +3 DIAS.
                    
                    if($dif->invert == 1){
                        $clase_tecnica="label label-warning";
                        $texto_tecnica="POR VENCER < 30 dias";
                    } else{
                        $clase_tecnica="label label-success";
                        $texto_tecnica="VIGENTE > 30 dias"; 
                    }                                          
                }

                $notificacion_tecnica='<span class="'.$clase_tecnica.'">'.$texto_tecnica.'</span>';
                
                /* -------------------------------------------------------------------------*/
                $date_circulacion = new DateTime($reg->circulacion);                     

                $dif = $date_actual->diff($date_circulacion);
                
                if($dif->invert == 1){
                        $clase_circulacion="label label-danger";
                        $texto_circulacion="VENCIDA";
                }else{ 
                    
                    $dif = $fecha30dias->diff($date_circulacion);//COMPARO CON LA FECHA ACTUAL +3 DIAS.
                    
                    if($dif->invert == 1){
                        $clase_circulacion="label label-warning";
                        $texto_circulacion="POR VENCER < 30 dias";
                    } else{
                        $clase_circulacion="label label-success";
                        $texto_circulacion="VIGENTE > 30 dias";   
                    }                                          
                }

                $notificacion_circulacion='<span class="'.$clase_circulacion.'">'.$texto_circulacion.'</span>';
                
                $doctos = '<button class="btn btn-success btn-xs" data-toggle="tooltip" title="Actualizar documentos" onclick="documentos('.$reg->idvehiculo.')"><i class="fa fa-newspaper-o"></i></button>';

                $data[] = array(
                    "0"=>$doctos,
					"1"=>$reg->marca.' '.$reg->modelo,
					"2"=>$reg->tipo,
                    "3"=>$reg->patente,
					"4"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
					"5"=>$reg->gases.' '.$notificacion_gases,
					"6"=>$reg->tecnica.' '.$notificacion_tecnica,
                    "7"=>$reg->circulacion.' '.$notificacion_circulacion,
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
        
        //! código nuevo
        case 'cargaProveedorGPS':
            echo '<option value="" selected disabled>SELECCIONE PROVEEDOR</option>';
            $rspta = $vehiculo->cargaProveedorGPS();        
    
            while($reg = $rspta->fetch_object()){
                echo '<option value='.$reg->id_proveedor.'>'.$reg->nombre.'</option>';
            }
        break;

        case 'guardaDispositivoGPS':
            $rspta = $vehiculo->guardaDispositivoGPS($id_vehiculo,$fhInstalacionGPS,$sProvGps);
            echo $rspta;
        break;

        case 'listarhist':
            $rspta=$vehiculo->listarhist();
            $data = Array();
            while ($reg = $rspta->fetch_object()){
                            
                        $data[] = array(
                        "0"=>'<button class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Activar" onclick="activar('.$reg->idvehiculo.')"><i class="fa fa-check"></i></button>',    
                        "1"=>$reg->marca.' '.$reg->modelo,
                        "2"=>$reg->tipo,
                        "3"=>$reg->ano,
                        "4"=>$reg->patente,
                        "5"=>$reg->kilometraje
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

        case 'inspeccion':
            $iduser = $_SESSION['iduser'];
            $rspta = $vehiculo->inspeccion($id_vehiculo,$kilometraje,$motivo, $iduser);
            echo $rspta;
        break;

        case 'listaDatosVehiculo':
            $rspta = $vehiculo->listaDatosVehiculo($idvehiculo);
            $data = array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "marca" => $reg->marca,
                    "modelo" => $reg->modelo,
                    "patente" => $reg->patente
                );
            };
            echo json_encode($data);
        break;

        case 'listaDatosVehiculoPrestamo':
            $rspta = $vehiculo->listaDatosVehiculoPrestamo($idasig, $idprestamo);
            $data = array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "marca" => $reg->marca,
                    "modelo" => $reg->modelo,
                    "patente" => $reg->patente,
                    "idempleado" => $reg->idempleado,
                    "fhPrestamo" => $reg->created_time,
                    "fhCompromiso" => $reg->fecha_compromiso
                );
            };
            echo json_encode($data);
        break;

        //! fin código nuevo
}

 ?>