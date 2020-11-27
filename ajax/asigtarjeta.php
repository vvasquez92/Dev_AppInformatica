<?php

session_start();

require_once "../modelos/AsigTarjeta.php";
require_once "../modelos/Tarjeta.php";

$asigtarjeta = new AsigTarjeta();
$tarjeta = new Tarjeta();


$idasigtarjeta = isset($_POST["idasigtarjeta"]) ? limpiarCadena($_POST["idasigtarjeta"]) : "";
$idtarjeta = isset($_POST["idtarjeta"]) ? limpiarCadena($_POST["idtarjeta"]) : "";
$idempleado = isset($_POST["idempleado"]) ? limpiarCadena($_POST["idempleado"]) : "";


switch ($_GET["op"]) {
    case 'guardaryeditar':
        $iduser = $_SESSION['iduser'];
        if (empty($idasignacion)) {
            $rspta = $asigtarjeta->insertar($idtarjeta, $idempleado, $iduser);
            if ($rspta) {
                $tarjeta->asignar($idtarjeta);
                echo "Asignacion realizada";
            } else {
                echo "Asignacion no pudo ser realizada";
            }
        } else {
//			$rspta=$asignacion->editar($idasignacion,$fromdate,$idequipo,$idempleado, $idchip, $iduser);
//			echo $rspta ? "Asignacion editada" : "Asginacion no pudo ser editada";
        }
        connClose();
    break;

    case 'desactivar':
        $tarjeta->liberar($idtarjeta);
        $rspta = $asigtarjeta->desactivar($idasigtarjeta);
        echo $rspta ? "Asignacion inhabilitada" : "Asignacion no se pudo inhabilitar";
        connClose();
    break;

    case 'mostar':
        $rspta = $asigtarjeta->mostrar($idasigtarjeta);
        echo json_encode($rspta);
        connClose();
    break;

    case 'pdfacta':
        $asigtarjeta->generaracta($idasigtarjeta);
        $rspta = $asigtarjeta->actapdf($idasigtarjeta);
        echo json_encode($rspta);
        connClose();
    break;

    case 'pdfdev':
        $asigtarjeta->generardevolucion($idasigtarjeta);
        $rspta = $asigtarjeta->devpdf($idasigtarjeta);
        echo json_encode($rspta);
        connClose();
    break;

    case 'listar':
        $rspta = $asigtarjeta->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {

            if (is_null($reg->acta)) {
                $respacta = '<span class="label bg-blue">SIN GENERAR</span>';
            } else {
                if ($reg->acta) {
                    $respacta = '<span class="label bg-green">ENTREGADO</span>';
                } else {
                    $respacta = '<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkacta(' . $reg->idasigtarjeta . ')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
                }
            }

            if (is_null($reg->devolucion)) {
                $respdev = '<span class="label bg-blue">SIN GENERAR</span>';
            } else {
                if ($reg->devolucion) {
                    $respdev = '<span class="label bg-green">ENTREGADO</span>';
                } else {
                    $respdev = '<span class="label bg-red">POR ENTREGAR</span> <button class="btn btn-success btn-xs" onclick="checkdev(' . $reg->idasigtarjeta . ')" data-tooltip="tooltip" title="Marcar acta entregada"><i class="fa fa-check-square-o"></i></button>';
                }
            }

            if ((is_null($reg->acta) && $reg->condicion ) || (!$reg->acta && $reg->condicion)) {
                $opciones = '<button class="btn btn-xs" onclick="acta(' . $reg->idasigtarjeta . ')" data-tooltip="tooltip" title="Acta Entrega"><i class="fa fa-file-text-o"></i></button>';
            } elseif ($reg->acta && (is_null($reg->devolucion) || !$reg->devolucion ) && $reg->condicion) {
                $opciones = '<button class="btn btn-xs" onclick="devolucion(' . $reg->idasigtarjeta . ')" data-tooltip="tooltip" title="Acta Devolucion"><i class="fa fa-file-text-o"></i></button>';
            } elseif ($reg->devolucion && $reg->condicion) {
                $opciones = '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idasigtarjeta . ',' . $reg->idtarjeta . ')" data-tooltip="tooltip" title="Inhabilitar"><i class="fa fa-close"></i></button>';
            } elseif (!$reg->condicion) {
                $opciones = '<span class="label bg-red">INHABILITADO</span>';
            }

            $data[] = array(
                "0" => $opciones,
                "1" => $reg->nombre . ' ' . $reg->apellido,
                "2" => $reg->num_documento,
                "3" => $reg->codigo,
                "4" => $reg->nivel,
                "5" => $reg->fecha,
                "6" => $respacta,
                "7" => $respdev
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


    case 'checkacta':
        $rspta = $asigtarjeta->checkacta($idasigtarjeta);
        echo $rspta ? "Acta entrega recibida" : "No pudo realizar la actualizacion";
        connClose();
    break;

    case 'checkdevolucion':
        $rspta = $asigtarjeta->checkdevolucion($idasigtarjeta);
        echo $rspta ? "Acta de devolucion recibida" : "No pudo realizar la actualizacion";
        connClose();
    break;

    case 'mostrarAsignaciones':

        $rspta = $asigtarjeta->mostrarAsignaciones($idempleado);
        $data = Array();
        $k = 0;
        while ($reg = $rspta->fetch_object()) {
            $k++;
            $data[] = array(
                "0" => $k,
                "1" => $reg->codigo,
                "2" => $reg->nivel,
                "3" => $reg->created_time,
                "4" => "",
                "5" => $reg->condicion_asignacion,
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
}
?>