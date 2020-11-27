<?php 

require_once "../modelos/Tvehiculo.php";

$tvehiculo = new Tvehiculo();

$idtvehiculo=isset($_POST["idtvehiculo"])?limpiarCadena($_POST["idtvehiculo"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

		case 'selecttvehiculo':
			$rspta = $tvehiculo->selecttvehiculo();
                        echo '<option value="" selected disabled>SELECCIONE TIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtvehiculo.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;

		case 'listar':
			$rspta=$tvehiculo->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idtvehiculo.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idtvehiculo.')"><i class="fa fa-trash"></i></button>',
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
			if(empty($idtvehiculo)){
				$rspta=$tvehiculo->insertar($nombre);
				echo $rspta ? "Tipo registrado" : "Tipo no se registro";
			}
			else{
				$rspta=$tvehiculo->editar($idtvehiculo, $nombre);
				echo $rspta ? "Tipo editado" : "Tipo no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$tvehiculo->mostrar($idtvehiculo);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$tvehiculo->eliminar($idtvehiculo);
			echo $rspta ? "Tipo quitado" : "Tipo no se quitó";
			
			connClose();
			break;
}

 ?>