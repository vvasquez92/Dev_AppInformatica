<?php 
session_start();

require_once "../modelos/AsignacionVehiculo.php";
require_once "../modelos/Vehiculo.php";
require_once "../modelos/Revision.php";
require_once "../modelos/GestionVehiculo.php";

$asignacion = new AsignacionVehiculo();
$vehiculo = new Vehiculo();
$revision = new Revision();
$gestionVehiculo = new GestionVehiculo();

$idasigvehi=isset($_POST["idasigvehi"])?limpiarCadena($_POST["idasigvehi"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$idvehiculo=isset($_POST["idvehiculo"])?limpiarCadena($_POST["idvehiculo"]):"";
$idempleado=isset($_POST["idempleado"])?limpiarCadena($_POST["idempleado"]):"";
$kilometraje=isset($_POST["kilometraje"])?limpiarCadena($_POST["kilometraje"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$gestion=isset($_POST["gestion"])?limpiarCadena($_POST["gestion"]):"";

//! codigo nuevo
$tipoServ=isset($_POST["tipoServ"])?limpiarCadena($_POST["tipoServ"]):"";
$fhSolic=isset($_POST["fhSolic"])?limpiarCadena($_POST["fhSolic"]):"";
$comentServ=isset($_POST["comentServ"])?limpiarCadena($_POST["comentServ"]):"";
$iddevvehiculo=isset($_POST["iddevvehiculo"])?limpiarCadena($_POST["iddevvehiculo"]):"";
//! fin codigo nuevo

switch ($_GET["op"]) {
	case 'entrada':
        $numrevision= count($_POST["revision"]);
        $oprevision = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        for ($o=0; $o < (int)$numrevision; $o++) {
            $oprevision[$_POST["revision"][$o]] = 1;
        }          
		$iduser=$_SESSION['iduser'];
		if(empty($idasigvehi)){    
			$idasigvehi=$asignacion->insertar($idvehiculo, $idempleado, $iduser);
			if($idasigvehi > 0){
				$vehiculo->asignar($idvehiculo, $kilometraje);
                $rsp=$revision->insertar($idasigvehi, 0, $fecha, $kilometraje, $oprevision[0], $oprevision[1], $oprevision[2], $oprevision[3], $oprevision[4], $oprevision[5], $oprevision[6], $oprevision[7], $oprevision[8], $oprevision[9], $oprevision[10], $oprevision[11], $oprevision[12], $oprevision[13], $oprevision[14], $oprevision[15], $oprevision[16], $oprevision[17], $oprevision[18], $oprevision[19], $oprevision[20], $oprevision[21], $oprevision[22], $oprevision[23], $oprevision[24], $oprevision[25], $oprevision[26], $oprevision[27], $oprevision[28], $oprevision[29], $oprevision[30], $oprevision[31], $oprevision[32], $observaciones);                                
                if($rsp){
                    echo "Asignacion registrada";
                }else{
                    echo "Asignacion registrada con error en la revision";
                }
			}else{
				echo "Error al registrar asignacion";
			}
        }	
        connClose();	
    break;
        
    case 'salida':
        $numrevision= count($_POST["revision"]);
        $oprevision = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        for ($o=0; $o < (int)$numrevision; $o++) {
            $oprevision[$_POST["revision"][$o]] = 1;
        }          
		$iduser=$_SESSION['iduser'];
                
		if(!empty($idasigvehi)){  
                    
			$rspta=$asignacion->devolucion($idasigvehi, $iduser);
                        
			if($rspta){
                            
                $vehiculo->actualizarKilometraje($iddevvehiculo, $kilometraje);                
                $rsp=$revision->insertar($idasigvehi, 1, $fecha, $kilometraje, $oprevision[0], $oprevision[1], $oprevision[2], $oprevision[3], $oprevision[4], $oprevision[5], $oprevision[6], $oprevision[7], $oprevision[8], $oprevision[9], $oprevision[10], $oprevision[11], $oprevision[12], $oprevision[13], $oprevision[14], $oprevision[15], $oprevision[16], $oprevision[17], $oprevision[18], $oprevision[19], $oprevision[20], $oprevision[21], $oprevision[22], $oprevision[23], $oprevision[24], $oprevision[25], $oprevision[26], $oprevision[27], $oprevision[28], $oprevision[29], $oprevision[30], $oprevision[31], $oprevision[32], $observaciones);                                
                
                if($rsp){
                    echo "Devolucion realizada";
                }else{
                    echo "Devolucion realizada con error al registrar revision";
                }
			}else{
				echo "Error al registrar devolucion";
			}
        }else{
            echo "No trajo id de asignacion ".$idasigvehi;
        }
        connClose();
    break;

	case 'desactivar': 
                
        $reg=$asignacion->datosdev($idasigvehi);
        $idvehiculo = $reg['idvehiculo'];
        $estado=0;//usado
        $iduser=$_SESSION['iduser'];

        $disponible=($gestion==1)?"1":"2"; // si el motivo es "devolucion de vehiculo" entonces el campo disponible debe ser seteado a "1".
                                            // todas las demas opciones de devolucion debe ser actualizado el campo disponible a "2".

        // TODO comento funcion original
        //$rspta=$vehiculo->actualizarDatosVehiculo($idvehiculo, $estado, $disponible, $gestion);
        $rspta=$vehiculo->actualizarDatosVehiculo_v2($idvehiculo, $estado, $disponible, $gestion, $tipoServ, $fhSolic, strtoupper($comentServ), $idempleado, $kilometraje);
        
        if($rspta){               
            if($gestion != 1){ // Si el motivo es diferente a "Devolucion de Vehiculo" debe generar registro en tabla gestion_ve.
                
                $rs=$gestionVehiculo->insertar($idvehiculo, $gestion, $iduser);
                if($rs){
                    $rspta=$asignacion->desactivar($idasigvehi);
                    if($rspta){
                        echo "Asignacion inhabilitada.";
                    }else{
                    echo "No se pudo inhabilitar la asignacion";
                    }
                }else{
                    echo "Error al guardar el registro en la tabla gestion_ve";
                }
            }else{
                $rspta=$asignacion->desactivar($idasigvehi);
                if($rspta){
                    echo "Asignacion inhabilitada";
                }else{
                    echo "No se pudo inhabilitar la asignacion";
                }
            }
        }else{
            echo "Error al actualizar los datos del vehículo.";
        }
        connClose();
    break;
                
	case 'devolucion':
		$rspta=$asignacion->datosdev($idasigvehi);
        echo json_encode($rspta);
        connClose();
    break;
            
    case 'pdf':
        $tipo=$_POST["tipo"];
        $rspta=$asignacion->pdf($idasigvehi, $tipo, 0);
        echo json_encode($rspta);
        connClose();
    break;

    case 'pdfPrestamo':
        $tipo=$_POST["tipo"];
        $rspta=$asignacion->pdfPrestamo($idasigvehi, $tipo, 0);
        echo json_encode($rspta);
        connClose();
    break;
        
        
	case 'listar':
		$rspta=$asignacion->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $opciones="";
            $devolver_prestamo="";
            if($reg->condicion==1){
                if($reg->entregado==1){
                    $opciones.='<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idasigvehi.','.$reg->idempleado.','.$reg->kilometraje.')" data-tooltip="tooltip" title="Inhabilitar"><i class="fa fa-close"></i></button>';
                }else{
                    if( is_null($reg->idprestamo)){
                        $opciones.='<button class="btn btn-warning btn-xs" onclick="devolucion('.$reg->idasigvehi.')" data-tooltip="tooltip" title="Devolucion"><i class="fa fa-reply"></i></button>'; 
                    }
                }
                
                if( is_null($reg->idprestamo) && $reg->entregado!=1){                    
                    //$opciones.='<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalPrestamo" data-id_asigvehi="'.$reg->idasigvehi.'" data-tooltip="tooltip" title="Prestamo de vehiculo"><i class="fa fa-handshake-o"></i></button>'; 
                    $opciones.='<button class="btn btn-success btn-xs" onclick="prestamo('. $reg->idasigvehi.','. $reg->idvehiculo.')" data-tooltip="tooltip" title="Prestamo de vehiculo"><i class="fa fa-handshake-o"></i></button>'; 
                }
                    
            }else{
                //$opciones.='<button class="btn btn-info btn-xs" onclick="" data-tooltip="tooltip" title="Mostrar"><i class="fa fa-eye"></i></button>';
            }
            
            if( !is_null($reg->idprestamo)){
                $devolver_prestamo='<button class="btn btn-success btn-xs" onclick="desactivar_prestamo('.$reg->idasigvehi.','.$reg->idvehiculo.','. $reg->idprestamo.')"  data-tooltip="tooltip" title="Devolver Prestamo de vehiculo"><i class="fa fa-reply"></i></button>';     
            }

            $data[] = array(                        
                "0"=>$opciones,                                      
                "1"=>$reg->nombre.' '.$reg->apellido,
                "2"=>$reg->num_documento,
                "3"=>$reg->empleado_prestamo." ".$devolver_prestamo,
                "4"=>$reg->marca.' '.$reg->modelo,
                "5"=>$reg->patente,
                "6"=>$reg->fecha,
                "7"=>'<button class="btn btn-info btn-xs" onclick="pdf('.$reg->idasigvehi.',0, 0)" data-tooltip="tooltip" title="Acta de entrega"><i class="fa fa-file-pdf-o"></i></button>',
                "8"=>($reg->entregado)?'<button class="btn btn-info btn-xs" onclick="pdf('.$reg->idasigvehi.',1, 0)" data-tooltip="tooltip" title="Acta de salida"><i class="fa fa-file-pdf-o"></i></button>':'',
                "9"=>($reg->condicion)?'<span class="label bg-blue">HABILITADO</span>':'<span class="label bg-red">INHABILITADO</span>'
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
          
    case 'mostrarAsignaciones':
        $rspta=$asignacion->mostrarAsignaciones($idempleado);
        $data = Array();
        $k=0;
        while ($reg = $rspta->fetch_object()){
            $k++;    
            $data[] = array(
                "0"=>$k,
                "1"=>$reg->marca.", ".$reg->modelo,
                "2"=>$reg->patente,
                "3"=>$reg->created_time,
                "4"=>$reg->close_time,
                "5"=>$reg->condicion_asignacion,
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
    
    case 'cargaTipoDevolucion':
        echo '<option value="" selected disabled>SELECCIONE MOTIVO</option>';
        $rspta = $asignacion->cargaTipoDevolucion();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->idtgestion_ve.'>'.$reg->titulo.'</option>';
        }
    break;

    case 'cargaTiposMantencion':
        echo '<option value="" selected disabled>SELECCIONE TIPO SERVICIO</option>';
        $rspta = $asignacion->cargaTiposMantencion();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_tiposervicio.'>'.$reg->tipo_servicio.'</option>';
        }
    break;

    case 'cargaTiposReparacion':
        echo '<option value="" selected disabled>SELECCIONE TIPO SERVICIO</option>';
        $rspta = $asignacion->cargaTiposReparacion();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_tiposervicio.'>'.$reg->tipo_servicio.'</option>';
        }
    break;

}

 ?>