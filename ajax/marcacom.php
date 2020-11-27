<?php 

require_once "../modelos/Marcacom.php";

$marcacom = new Marcacom();

$idmarcacom=isset($_POST["idmarcacom"])?limpiarCadena($_POST["idmarcacom"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");

switch ($_GET["op"]) {

		case 'selectmarcacom':
			$rspta = $marcacom->selectmarcacom();
                        echo '<option value="" selected disabled>SELECCIONE MARCA</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idmarcacom.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;

		case 'listar':
			$rspta=$marcacom->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idmarcacom.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idmarcacom.')"><i class="fa fa-trash"></i></button>',
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
			if(empty($idmarcacom)){
				$rspta=$marcacom->insertar($nombre);
				echo $rspta ? "Marca registrada" : "Marca no se registro";
			}
			else{
				$rspta=$marcacom->editar($idmarcacom, $nombre);
				echo $rspta ? "Marca editada" : "Marca no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$marcacom->mostrar($idmarcacom);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
			
			$rspta=$marcacom->eliminar($idmarcacom);
			echo $rspta ? "Marca quitada" : "Marca no se quitó";
			
			connClose();
			break;
}

 ?>