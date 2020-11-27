<?php 

require_once "../modelos/Oficinas.php";

$oficinas = new Oficinas();

//$idrole=isset($_POST["idrole"])?limpiarCadena($_POST["idrole"]):"";
//$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
//$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";

$idoficinas=isset($_POST["idoficinas"])?limpiarCadena($_POST["idoficinas"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");
$direccion=mb_strtoupper(isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"");
$idregiones=mb_strtoupper(isset($_POST["idregiones"])?limpiarCadena($_POST["idregiones"]):"");
$idprovincias=mb_strtoupper(isset($_POST["idprovincias"])?limpiarCadena($_POST["idprovincias"]):"");
$idcomunas=mb_strtoupper(isset($_POST["idcomunas"])?limpiarCadena($_POST["idcomunas"]):"");


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
		case 'selectOficinas':
			$rspta = $oficinas->selectOficinas();
						echo '<option value="" selected disabled>SELECCIONE OFICINA</option>';
						
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idoficinas.'>'.$reg->nombre.'</option>';
				
			}
			connClose();
			break;
		
		case 'listar':
			$rspta=$oficinas->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idoficinas.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idoficinas.')"><i class="fa fa-trash"></i></button>',
						"1"=>$reg->nombre,
						"2"=>$reg->direccion,
						"3"=>$reg->comuna,
						"4"=>$reg->provincia,
						"5"=>$reg->region					
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

		case 'guardaryeditar':
			if(empty($idoficinas)){
				$rspta=$oficinas->insertar($nombre,$direccion, $idregiones,$idprovincias,$idcomunas);
				echo $rspta ? "Oficina registrada" : "Oficina no se registro";
				connClose();
			}
			else{
				$rspta=$oficinas->editar($idoficinas, $nombre,$direccion, $idregiones,$idprovincias,$idcomunas);
				echo $rspta ? "Oficina editada" : "Oficina no se edito";
				connClose();
			}
			break;
																												
		case 'mostar':
			$rspta=$oficinas->mostrar($idoficinas);
			connClose();
			echo json_encode($rspta);
			break;

		case 'eliminar':
		
			$rspta=$oficinas->eliminar($idoficinas);
			echo $rspta ? "Oficina quitada" : "Oficina no se quitó";
			connClose();
			
			break;			
}

 ?>