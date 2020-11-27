<?php 
session_start();

require_once "../modelos/GestionChip.php";
require_once "../modelos/Chip.php";

$gestionchip = new GestionChip();
$chip = new Chip();

$idgestion=isset($_POST["idgestion"])?limpiarCadena($_POST["idgestion"]):"";
$idchip=isset($_POST["idchip"])?limpiarCadena($_POST["idchip"]):"";
$detalle=mb_strtoupper(isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"");
$descripcion=mb_strtoupper(isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"");
$hablilitar = isset($_POST['hablilitar']) ? 1 : 0;
$nserie = isset($_POST['nserie']) ? limpiarCadena($_POST["nserie"]) : "";
   

switch ($_GET["op"]) {
    
	case 'guardaryeditar':
            
		$created_user=$_SESSION['iduser'];
            
		if(empty($idgestion)){
			$rspta=$gestionchip->insertar($idchip,$detalle,$descripcion,$created_user);
			echo $rspta ? "gestión registrada" : "gestión no pudo ser registrado";
		}
                
        if($hablilitar==1){
			$chip->serial($idchip, $nserie);
            $chip->liberar($idchip);
            $chip->actualizarDetalle($idchip,"");
        }
        connClose();
		break;
			
	case 'listar':
            
		$rspta=$gestionchip->listar($idchip);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
				"detalle"=>$reg->detalle,
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
                
}

 ?>