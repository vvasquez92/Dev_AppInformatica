<?php

session_start();

require_once "../modelos/Tarjeta.php";

$tarjeta = new Tarjeta();

$idtarjeta = isset($_POST["idtarjeta"]) ? limpiarCadena($_POST["idtarjeta"]) : "";
$idnivel = isset($_POST["idnivel"]) ? limpiarCadena($_POST["idnivel"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$codigosys = isset($_POST["codigosys"]) ? limpiarCadena($_POST["codigosys"]) : "";


switch ($_GET["op"]) {
    
    case 'guardaryeditar':
        $iduser = $_SESSION['iduser'];
        if (empty($idtarjeta)) {
            $rspta = $tarjeta->insertar($idnivel, $codigo, $codigosys, $iduser);
            echo $rspta ? "Tarjeta de acceso registrada" : "Tarjeta de acceso no pudo ser registrada";
        } else {
            $rspta = $tarjeta->editar($idtarjeta, $idnivel, $codigo, $codigosys, $iduser);
            echo $rspta ? "Tarjeta de acceso editada" : "Tarjeta de acceso no pudo ser editada";
        }
        connClose();
    break;

    case 'desactivar':
        $rspta = $tarjeta->desactivar($idtarjeta);
        echo $rspta ? "Tarjeta de acceso inhabilitada" : "Tarjeta de acceso no se pudo inhabilitar";
        connClose();
    break;

    case 'activar':
        $rspta = $tarjeta->activar($idtarjeta);
        echo $rspta ? "Tarjeta de acceso habilitada" : "Tarjeta de acceso no se pudo habilitar";
        connClose();
    break;

    case 'cargaTotales':
		$rspta=$tarjeta->cargaTotales();
			echo json_encode($rspta);
			connClose();
		break;

    case 'mostar':
        $rspta = $tarjeta->mostrar($idtarjeta);
        echo json_encode($rspta);
        connClose();
    break;

    case 'listar':
        $rspta = $tarjeta->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ?
                '<button class="btn btn-warning btn-xs" onclick="mostar(' . $reg->idtarjeta . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
                ' <button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idtarjeta . ','.$reg->disponible.')" title="Deshabilitar"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning btn-xs" onclick="mostar(' . $reg->idtarjeta . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
                ' <button class="btn btn-primary btn-xs" onclick="activar(' . $reg->idtarjeta . ')" title="Habilitar"><i class="fa fa-check"></i></button>',
                "1" => $reg->codigo,
                "2" => $reg->codigosys,
                "3" => $reg->nivel,
                "4" => ($reg->disponible) ? '<span class="label bg-green">SIN ASIGNAR</span>' : '<span class="label bg-red">ASIGNADO</span>',
                "5" => ($reg->condicion) ? '<span class="label bg-green">HABILITADO</span>' : '<span class="label bg-red">INHABILITADO</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        connClose();
    break;

    case 'SLTarjeta':
        $rspta = $tarjeta->selecttarjeta();
        echo '<option value="" selected disabled>SELECCIONE TARJETA</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idtarjeta . '>' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
        }
        connClose();
    break;
}
?>