<?php 

require_once "../modelos/Tcomputador.php";

$tcomputador = new Tcomputador();

$idtcomputador=isset($_POST["idtcomputador"])?limpiarCadena($_POST["idtcomputador"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

		case 'selecttcomputador':
			$rspta = $tcomputador->selecttcomputador();
                        echo '<option value="" selected disabled>SELECCIONE TIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtcomputador.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;
		case 'listar':
			$rspta=$tcomputador->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idtcomputador.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idtcomputador.')"><i class="fa fa-trash"></i></button>',
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
			if(empty($idtcomputador)){
				$rspta=$tcomputador->insertar($nombre);
				echo $rspta ? "Tipo registrado" : "Tipo no se registro";
			}
			else{
				$rspta=$tcomputador->editar($idtcomputador, $nombre);
				echo $rspta ? "Tipo editado" : "Tipo no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$tcomputador->mostrar($idtcomputador);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$tcomputador->eliminar($idtcomputador);
			echo $rspta ? "Tipo quitado" : "Tipo no se quitó";
			connClose();
			
			break;
}

 ?>