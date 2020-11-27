<?php 

require_once "../modelos/Cargos.php";

$cargos = new Cargos();

$idcargos =isset($_POST["idcargos"])?limpiarCadena($_POST["idcargos"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):"";


switch ($_GET["op"]) {

	case 'guardaryeditar':
		if(empty($idcargos)){
			$rspta=$cargos->insertar($nombre,$iddepartamento);
			echo $rspta ? "Cargo registrado" : "Cargo no se registro";
			connClose();
		}
		else{
			$rspta=$cargos->editar($idcargos, $nombre, $iddepartamento);
			echo $rspta ? "Cargo editado" : "Cargo no se edito";
			connClose();
		}
	break;

	case 'eliminar':
		$rspta=$cargos->eliminar($idcargos);
		echo $rspta ? "Cargo eliminado" : "Cargo no puso ser eliminado";
		connClose();
	break;
	
	case 'mostar':
		$rspta=$cargos->mostrar($idcargos);
		connClose();
		echo json_encode($rspta);
	break;
	

	case 'listar':
		$rspta=$cargos->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idcargos.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idcargos.')"><i class="fa fa-trash"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->departamento
				);
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);
		connClose();
		echo json_encode($results);
	break;

	case 'selectCargos':
		$iddepartamento = $_POST["iddepartamento"];
		$rspta = $cargos->selectCargos($iddepartamento);
		echo '<option value="" selected disabled>SELECCIONE CARGO</option>';
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->idcargos.'>'.$reg->nombre.'</option>';
		}
		connClose();
	break;
}

 ?>