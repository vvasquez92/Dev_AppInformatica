<?php 

require_once "../modelos/Operador.php";

$operador = new Operador();

//$idrole=isset($_POST["idrole"])?limpiarCadena($_POST["idrole"]):"";
//$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
//$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";

$idoperador=isset($_POST["idoperador"])?limpiarCadena($_POST["idoperador"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");


switch ($_GET["op"]) {
/*
	case 'guardaryeditar':
		if(empty($idrole)){
			$rspta=$role->insertar($nombre,$descripcion, $_POST['permiso']);
			echo $rspta ? "Role registrado" : "Role no se registro";
		}
		else{
			$rspta=$role->editar($idrole, $nombre, $descripcion, $_POST['permiso']);
			echo $rspta ? "Role editado" : "Role no se edito";
		}
		break;

	case 'eliminar':
		$rspta=$role->eliminar($idrole);
			echo $rspta ? "Role eliminado" : "Role eliminado";
		break;

	case 'mostar':
		$rspta=$role->mostrar($idrole);
			echo json_encode($rspta);
		break;
			
	case 'listar':
		$rspta=$role->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idrole.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idrole.')"><i class="fa fa-trash"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->descripcion
				);
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

		echo json_encode($results);
		break;
*/
		case 'selectOperador':
			$rspta = $operador->selectOperador();
                        echo '<option value="" selected disabled>SELECCIONE OPERADOR</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idoperador.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;

		case 'listar':
			$rspta=$operador->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idoperador.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idoperador.')"><i class="fa fa-trash"></i></button>',
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
			if(empty($idoperador)){
				$rspta=$operador->insertar($nombre);
				echo $rspta ? "Operador registrado" : "Operador no se registro";
			}
			else{
				$rspta=$operador->editar($idoperador, $nombre);
				echo $rspta ? "Operador editado" : "Operador no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$operador->mostrar($idoperador);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$operador->eliminar($idoperador);
			echo $rspta ? "Operador quitado" : "Operador no se quitó";
			
			connClose();
			break;
}

 ?>