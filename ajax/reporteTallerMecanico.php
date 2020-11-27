<?php 
session_start();
require_once "../modelos/ReporteTallerMecanico.php";

$reporte = new Reporte();

$patente=isset($_POST["patente"])?limpiarCadena($_POST["patente"]):"";
$desde=isset($_POST["desde"])?limpiarCadena($_POST["desde"]):"";
$hasta=isset($_POST["hasta"])?limpiarCadena($_POST["hasta"]):"";
$idServicio=isset($_POST["idServicio"])?limpiarCadena($_POST["idServicio"]):"";

switch ($_GET["op"]) {
    case 'cargaPatentes':

        echo '<option value="0" selected>TODAS</option>';
        $rspta = $reporte->cargaPatentes();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->idvehiculo.'>'.$reg->patente.'</option>';
        }
    break;

    case "selectano":
        $rspta = $reporte->selectano();
        echo '<option value="0" selected>TODOS</option>';
        while($reg = $rspta->fetch_object()){
             echo '<option value='.$reg->ano.'>'.$reg->ano.'</option>';
        }
    break;

    case "listar":
        $rspta=$reporte->listar($patente, $desde, $hasta);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $boton='';            

            $data[] = array(
                "0"=>$reg->patente,
                "1"=>$reg->cod_producto,
                "2"=>$reg->nombre,
                "3"=>$reg->marca,
                "4"=>$reg->modelo,
                "5"=>$reg->cantidad,
                "6"=>$reg->fh_mov
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

    case 'listaDatosServicio':

        $rspta = $reporte->listaDatosServicio($idServicio);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "mecanico" => $reg->mecanico,
                "chofer" => $reg->chofer,
                "observaciones" => $reg->observaciones
            );
        };
        echo json_encode($data);
    break;
}

 ?>