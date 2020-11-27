<?php 

require_once "../modelos/Permiso.php";

$permiso = new Permiso();

switch ($_GET["op"]) {
	
	case 'listar':
		$rspta=$permiso->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>$reg->nombre,
					"1"=>$reg->descripcion
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
}

 ?>