<?php 
session_start();
        
require_once "../modelos/GestionVehiculo.php";
require_once "../modelos/Vehiculo.php";

$gestionvehiculo = new GestionVehiculo();
$vehiculo = new Vehiculo();

$idgestion_ve=isset($_POST["idgestion_ve"])?limpiarCadena($_POST["idgestion_ve"]):"";
$idvehiculo=isset($_POST["idvehiculo"])?limpiarCadena($_POST["idvehiculo"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";

switch ($_GET["op"]) {
			
        case 'listar':
            
		$rspta=$gestionvehiculo->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){                    
			$data[] = array(
					"0"=>'<button class="btn btn-primary btn-xs" data-toggle="tooltip" title="Visualizar Gestion" onclick="mostrar_gestion('.$reg->idgestion_ve.')"><i class="fa fa-eye"></i></button>'.
                        '<button title="Realizar Gestión" class="btn btn-success btn-xs" type="button" data-toggle="modal" data-target="#modalGestion"  data-idgestion_ve="'.$reg->idgestion_ve.'" data-idvehiculo="'.$reg->idvehiculo.'" ><i class="fa fa-plus-circle"></i></button>', //.
                        //'<button data-toggle="tooltip" data-placement="top" title="Inhabilitar Definitivamente el Vehículo" class="btn btn-danger btn-xs" onclick="inhabilitarDefinitivamenteVehiculo('.$reg->idgestion_ve.','.$reg->idvehiculo.')"><i class="fa fa-window-close"></i></button>',
                    "1"=>$reg->tipo_gestion,            
                    "2"=>$reg->marca.' '.$reg->modelo,
					"3"=>$reg->ano,
                    "4"=>$reg->patente,
					"5"=>$reg->serialmotor,
                    "6"=>$reg->updated_time,
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
        
        case 'listarHist':
            
            $rspta=$gestionvehiculo->listarHist();
            $data = Array();
            while ($reg = $rspta->fetch_object()){                    
                $data[] = array(
                        "0"=>'<button class="btn btn-primary btn-xs" data-toggle="tooltip" title="Visualizar Gestion" onclick="mostrar_gestion('.$reg->idgestion_ve.')"><i class="fa fa-eye"></i></button>',
                        "1"=>$reg->tipo_gestion,            
                        "2"=>$reg->marca.' '.$reg->modelo,
                        "3"=>$reg->ano,
                        "4"=>$reg->patente,
                        "5"=>$reg->serialmotor,
                        "6"=>$reg->created_time,
                        "7"=>$reg->updated_time,
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
                
        case 'mostrar':
            
            $rspta=$gestionvehiculo->mostrar($idgestion_ve);
            
            echo json_encode($rspta);
            connClose();
        break;   
    
        case 'inhabilitarDefinitivamenteVehiculo':
            
            $closed_user=$_SESSION['iduser']; 
            $updated_user=$_SESSION['iduser'];
            
            //actualizar gestion actual
            $gestionvehiculo->ActualizarGestion($idgestion_ve, $updated_user);
            
            //finalizar gestion actual           
            $condicion=0;
            $gestionvehiculo->finalizarGestion($idgestion_ve, $condicion, $closed_user);
                                    
            //inhabilitar vehiculo.
            $vehiculo->desactivar($idvehiculo);

            //se agrega una observacion del motivo pór el que se esta inhabilitando.
            $vehiculo->actualizarObservaciones($idvehiculo, $observaciones);
            connClose();
        break;    

                
}

 ?>