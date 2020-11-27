<?php 
session_start();

require_once "../modelos/Nivel.php";

$nivel = new Nivel();

$idnivel=isset($_POST["idnivel"])?limpiarCadena($_POST["idnivel"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])?limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if(empty($idequipo)){
			$rspta=$nivel->insertar($nombre,$descripcion);
			echo $rspta ? "NIVEL DE ACCESO REGISTRADO" : "NIVEL DE ACCESO NO PUDO SER REGISTRADO";
		}
		else{
			$rspta=$nivel->editar($idnivel, $nombre, $descripcion);
			echo $rspta ? "NIVEL DE ACCESO EDITADO" : "NIVEL DE ACCESO NO PUDO SER EDITADO";
		}
		connClose();
		break;

	case 'desactivar':
		$rspta=$nivel->desactivar($idnivel);
			echo $rspta ? "NIVEL DE ACCESO INHABILITADO" : "NIVEL DE ACCESO NO SE PUEDO INHABILITAR";
			connClose();
		break;

	case 'activar':
		$rspta=$nivel->activar($idnivel);
			echo $rspta ? "NIVEL DE ACCESO HABILITADO" : "NIVEL DE ACCESO NO SE PUDO HABILITAR";
			connClose();
		break;

	case 'mostar':
		$rspta=$nivel->mostrar($idnivel);
			echo json_encode($rspta);
			connClose();
		break;
			
	case 'listar':
		$rspta=$nivel->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idnivel.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idnivel.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idnivel.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idequipo.')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,				
					"2"=>($reg->condicion)?'<span class="label bg-green">HABILITADO</span>':'<span class="label bg-red">INHABILITADO</span>'
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

		case 'SLNivel':
			$rspta = $nivel->SLNivel();
                        echo '<option value="" selected disabled>SELECCIONE NIVEL</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idnivel.'>'.$reg->nombre.'</option>';
			}
			connClose();
		break;


}

 ?>