<?php 

require_once "../modelos/Modelove.php";

$modelove = new Modelove();

$idmodelove=isset($_POST["idmodelove"])?limpiarCadena($_POST["idmodelove"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");
$idmarca=mb_strtoupper(isset($_POST["idmarca"])?limpiarCadena($_POST["idmarca"]):"");

switch ($_GET["op"]) {

		case 'selectmodelove':
			$idmarca=$_GET["id"];
			$rspta = $modelove->selectmodelove($idmarca);
                        echo '<option value="" selected disabled>SELECCIONE MODELO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idmodelove.'>'.$reg->nombre.'</option>';
			}   
			connClose();                     
		break;

		case 'listar':
			$rspta=$modelove->listar();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idmodelove.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idmodelove.')"><i class="fa fa-trash"></i></button>',
						"1"=>$reg->marca,
						"2"=>$reg->nombre					
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
			if(empty($idmodelove)){
				$rspta=$modelove->insertar($nombre,$idmarca);
				echo $rspta ? "Marca registrada" : "Marca no se registro";
			}
			else{
				$rspta=$modelove->editar($idmodelove, $nombre);
				echo $rspta ? "Marca editada" : "Marca no se edito";
			}
			connClose();
			break;
																												
		case 'mostar':
			$rspta=$modelove->mostrar($idmodelove);
				echo json_encode($rspta);
				connClose();
			break;

		case 'eliminar':
		
			$rspta=$modelove->eliminar($idmodelove);
			echo $rspta ? "Modelo quitado" : "Modelo no se quitó";
			
			connClose();
			break;
}

 ?>