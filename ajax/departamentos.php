<?php 

require_once "../modelos/Departamentos.php";

$departamentos = new Departamentos();

$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

	case 'guardaryeditar':
		if(empty($iddepartamento)){
			$rspta=$departamentos->insertar($nombre);
			echo $rspta ? "Departamento registrado" : "Departamento no se registro";
			connClose();
		}
		else{
			$rspta=$departamentos->editar($iddepartamento, $nombre);
			echo $rspta ? "Departamento editado" : "Departamento no se edito";
			connClose();
		}
	break;

	case 'eliminar':
		$rspta=$departamentos->eliminar($iddepartamento);
		echo $rspta ? "Departamento eliminado" : "Departamento no puso ser eliminado";
		connClose();
	break;
	
	case 'mostar':
		$rspta=$departamentos->mostrar($iddepartamento);
		connClose();
		echo json_encode($rspta);
	break;
	
	case 'listar':
		$rspta=$departamentos->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iddepartamento.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->iddepartamento.')"><i class="fa fa-trash"></i></button>',
					"1"=>$reg->nombre					
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

	case 'selectDepartamentos':
		$rspta = $departamentos->selectDepartamentos();
		echo '<option value="" selected disabled>SELECCIONE DEPARTAMENTO</option>';
		
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->iddepartamento.'>'.$reg->nombre.'</option>';
		}
		connClose();
	break;
					
	case 'selectDepartamentosIntranet':
		$rspta = $departamentos->selectDepartamentosIntranet();
		echo '<option value="" selected disabled>SELECCIONE DEPARTAMENTO</option>';
		while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->iddepartamento.'>'.$reg->nombre.'</option>';
		}
		connClose();
	break;
}

 ?>