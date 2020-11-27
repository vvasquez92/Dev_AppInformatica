<?php 

require_once "../modelos/Marcave.php";

$marcave = new Marcave();

$idmarcave=isset($_POST["idmarcave"])?limpiarCadena($_POST["idmarcave"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

		case 'selectmarcave':
			$rspta = $marcave->selectmarcave();
                        echo '<option value="" selected disabled>SELECCIONE MARCA</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idmarca.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;
		
		case 'listar':
			$rspta=$marcave->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idmarca.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idmarca.')"><i class="fa fa-trash"></i></button>',
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

		case 'guardaryeditar':
			if(empty($idmarcave)){
				$rspta=$marcave->insertar($nombre);
				echo $rspta ? "Marca registrada" : "Marca no se registro";
			}
			else{
				$rspta=$marcave->editar($idmarcave, $nombre);
				echo $rspta ? "Marca editada" : "Marca no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$marcave->mostrar($idmarcave);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$marcave->eliminar($idmarcave);
			echo $rspta ? "Marca quitada" : "Marca no se quitó";
			connClose();			
			break;
}

 ?>