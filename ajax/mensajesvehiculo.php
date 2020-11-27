<?php 
session_start();

require_once "../modelos/MensajesVehiculo.php";
require_once "../modelos/Vehiculo.php";
require_once "../modelos/GestionVehiculo.php";

$mensajesvehiculo = new MensajesVehiculo();
$vehiculo = new Vehiculo();
$gestionvehiculo = new GestionVehiculo();

$idmensaje_ve=isset($_POST["idmensaje_ve"])?limpiarCadena($_POST["idmensaje_ve"]):"";
$idgestion_ve=isset($_POST["idgestion_ve"])?limpiarCadena($_POST["idgestion_ve"]):"";
$idvehiculo=isset($_POST["idvehiculo"])?limpiarCadena($_POST["idvehiculo"]):"";
$titulo=mb_strtoupper(isset($_POST["titulo"])?limpiarCadena($_POST["titulo"]):"");
$descripcion=mb_strtoupper(isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):""); 
$check_finGestion = isset($_POST['check_finGestion']) ? 1 : 0;
$estado_final=mb_strtoupper(isset($_POST["estado_final"])?limpiarCadena($_POST["estado_final"]):"");

switch ($_GET["op"]) {
    
	case 'guardaryeditar':
            
            $created_user=$_SESSION['iduser'];
            $closed_user=$_SESSION['iduser'];
            $updated_user=$_SESSION['iduser'];
            
		if(empty($idmensaje_ve)){
			$rspta=$mensajesvehiculo->insertar($idgestion_ve,$titulo,$descripcion,$created_user);
			echo $rspta ? "gestión registrada" : "gestión no pudo ser registrado";
		}
                
                $gestionvehiculo->ActualizarGestion($idgestion_ve, $updated_user);
                
                if($check_finGestion==1){ // si se hace click sobre el check para finalizar gestion.
                    
                        $estado=0;
                        $disponible=($estado_final!=0)?"2":"1";
                        $vehiculo->actualizarDatosVehiculo($idvehiculo, $estado, $disponible, $estado_final); 
                        
                        //finalizar gestion
                        $condicion=0;
                        $gestionvehiculo->finalizarGestion($idgestion_ve, $condicion, $closed_user);
                                 
                        //genera un nuevo registro para una nueva gestion.
                        if($estado_final!=0){
                            $gestionvehiculo->insertar($idvehiculo, $estado_final, $created_user);
                        }
                        
                }
                connClose();            
	break;
			
        case 'listar':
            
		$rspta=$mensajesvehiculo->listar($idgestion_ve);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"titulo"=>$reg->titulo,
					"descripcion"=>$reg->descripcion,
                                        "mes"=>$reg->mes,
                                        "dia"=>$reg->dia,
                                        "created_user"=>$reg->created_user,
                                        "nombre"=>$reg->nombre,
                                        "apellido"=>$reg->apellido,
                                        "imagen"=>$reg->imagen
				);
		}
                
		$results = array(
				"TotalRecords"=>count($data),
				"registros"=>$data
			);

                echo json_encode($results);
                connClose();
                break;      
                
        case 'listar2':        
                $rspta=$mensajesvehiculo->listar($idgestion_ve);
                $data = Array();
                while ($reg = $rspta->fetch_object()){
                        $data[] = array(
                                "0"=>$reg->nombre . ' ' . $reg->apellido,
                                "1"=>$reg->titulo,
                                "2"=>$reg->descripcion,
                                "3"=>$reg->created_time
                        );
                }
                
                $results = array(
                        "sEcho" => 1,
                        "iTotalRecords" => count($data),
                        "iTotalDisplayRecords" => count($data),
                        "aaData" => $data
                );

                echo json_encode($results);
                connClose();
        break;  
}

 ?>