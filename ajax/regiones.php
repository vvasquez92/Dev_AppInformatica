<?php 

require_once "../modelos/Regiones.php";

$regiones = new Regiones();

//$idrole=isset($_POST["idrole"])?limpiarCadena($_POST["idrole"]):"";
//$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
//$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";


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
	case 'selectRegiones':
		$rspta = $regiones->selectRegiones();             
		echo '<option value="" selected disabled>Seleccione Region</option>';
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->region_id.'>'.$reg->region_nombre.'</option>';
		}
		connClose();
	break;
}

 ?>