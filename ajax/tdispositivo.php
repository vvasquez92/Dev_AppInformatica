<?php 

require_once "../modelos/Tdispositivo.php";

$tdispositivo = new Tdispositivo();

$idtdispositivo=isset($_POST["idtdispositivo"])?limpiarCadena($_POST["idtdispositivo"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

		case 'selecttdispositivo':
                    
			$rspta = $tdispositivo->selecttdispositivo();
                        echo '<option value="" selected disabled>SELECCIONE TIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idtdispositivo.'>'.$reg->nombre.'</option>';
			}
			connClose();      
			break;
		case 'listar':
			$rspta=$tdispositivo->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idtdispositivo.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idtdispositivo.')"><i class="fa fa-trash"></i></button>',
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
			if(empty($idtdispositivo)){
				$rspta=$tdispositivo->insertar($nombre);
				echo $rspta ? "Tipo registrado" : "Tipo no se registro";
			}
			else{
				$rspta=$tdispositivo->editar($idtdispositivo, $nombre);
				echo $rspta ? "Tipo editado" : "Tipo no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$tdispositivo->mostrar($idtdispositivo);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$tdispositivo->eliminar($idtdispositivo);
			echo $rspta ? "Tipo quitado" : "Tipo no se quitó";
			connClose();
			
			break;
}

 ?>