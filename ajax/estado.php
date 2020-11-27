<?php 
session_start();
require_once "../modelos/Estado.php";


$estado = new Estado();

$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : "";

switch ($_GET["op"]) {
        
        case 'ContarMoviles':
            
        $rspta=$estado->contarmoviles();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                            "cantidad"=>$reg->cantidad,
                            "disponible"=>$reg->disponible
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
         case 'contarMovilesGestion':
            
        $rspta=$estado->contarMovilesGestion();
             
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                            "cantidad"=>$reg->cantidad,
                            "condicion"=>$reg->condicion
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
    case 'ContarSim':
        
        $rspta=$estado->ContarSim();
        
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            
            $data[] = array(
                    "cantidad"=>$reg->cantidad,
                    "disponible"=>$reg->disponible
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'ContarSimGestion':
        
        $rspta=$estado->ContarSimGestion();
        
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            
            $data[] = array(
                    "cantidad"=>$reg->cantidad,
                    "condicion"=>$reg->condicion
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'contarEquipos':
            
        $rspta=$estado->contarEquipos();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "cantidad"=>$reg->cantidad,
                    "disponible"=>$reg->disponible
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'contarEquiposGestion':
            
        $rspta=$estado->contarEquiposGestion();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "cantidad"=>$reg->cantidad,
                    "condicion"=>$reg->condicion
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'contarTiposEquipo':
            
        $rspta=$estado->contarTiposEquipo();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            
          $data[] = array(
                        "tcomputador"=>$reg->tcomputador,
                        "tipo_computador"=>$reg->tipo_computador,
                        "cantidad"=>$reg->cantidad
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'ContarVehiculos':
            
        $rspta=$estado->contarvehiculo();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                    "0"=>$reg->vehiculo,
                    "1"=>$reg->disponible
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
          case 'contarVehiculoGestion':
            
        $rspta=$estado->contarVehiculoGestion();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array("cant_vehiculos"=>$reg->cant_vehiculos,
                            "gestion"=>$reg->gestion);
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'contarTarjetas':
            
        $rspta=$estado->contarTarjetas();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                        "cantidad"=>$reg->cantidad,
                        "disponible"=>$reg->disponible
                        );
        }
        
        $results = array(
                "aaData"=>$data
            );
        
        echo json_encode($results);
        connClose();
        break;
        
        case 'contarNivelesTarjeta':
            
        $rspta=$estado->contarNivelesTarjeta();
            
        $data = Array();
        
        while ($reg = $rspta->fetch_object()){
            $data[] = array("idnivel"=>$reg->idnivel,
                            "nombre_nivel"=>$reg->nombre_nivel,
                            "cantidad"=>$reg->cantidad);
        }

        $results = array(
                "aaData"=>$data
            );

        echo json_encode($results);
        connClose();
        break;

        case 'listarVehDet':
            $rspta=$estado->listarVehDet($tipo);
            $data = Array();
            while ($reg = $rspta->fetch_object()){
                $boton='';            
    
                $data[] = array(                    
                    "0"=>$reg->patente,
                    "1"=>$reg->ult_gestion
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

        case 'listarVehAsig':
            $rspta=$estado->listarVehAsig();
            $data = Array();
            while ($reg = $rspta->fetch_object()){
                $boton='';            
    
                $data[] = array(                    
                    "0"=>$reg->patente,
                    "1"=>$reg->nombre
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

        case 'listarVehLib':
            $rspta=$estado->listarVehLib();
            $data = Array();
            while ($reg = $rspta->fetch_object()){
                $boton='';            
    
                $data[] = array(                    
                    "0"=>$reg->patente,
                    "1"=>$reg->vehiculo
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
        
}

 ?>