<?php 

require_once "../modelos/Comunas.php";

$comunas = new Comunas();

switch ($_GET["op"]) {
	case 'selectComunas':
		$idregiones=$_GET["id"];
		$rspta = $comunas->selectComunas($idregiones);
		echo '<option value="" selected disabled>SELECCIONE COMUNA</option>';
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->comuna_id.'>'.$reg->comuna_nombre.'</option>';
		}
		connClose();
	break;
}

 ?>