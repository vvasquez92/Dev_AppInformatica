<?php

session_start();

require_once "../modelos/Dispositivo.php";

$dispositivo = new Dispositivo();

$iddispositivo = isset($_POST["iddispositivo"]) ? limpiarCadena($_POST["iddispositivo"]) : "";
$marca = isset($_POST["marca"]) ? limpiarCadena($_POST["marca"]) : "";
$modelo = isset($_POST["modelo"]) ? limpiarCadena($_POST["modelo"]) : "";
$tdispositivo = isset($_POST["tdispositivo"]) ? limpiarCadena($_POST["tdispositivo"]) : "";
$ip = isset($_POST["ip"]) ? limpiarCadena($_POST["ip"]) : "";
$serial = isset($_POST["serial"]) ? limpiarCadena($_POST["serial"]) : "";
$maclan = isset($_POST["maclan"]) ? limpiarCadena($_POST["maclan"]) : "";
$macwifi = isset($_POST["macwifi"]) ? limpiarCadena($_POST["macwifi"]) : "";
$factura_actual = isset($_POST["factura_actual"]) ? limpiarCadena($_POST["factura_actual"]) : "";
$fvencimiento_garantia = isset($_POST["fvencimiento_garantia"]) ? limpiarCadena($_POST["fvencimiento_garantia"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

switch ($_GET["op"]) {

    case 'guardaryeditar':
        

        if (!file_exists($_FILES['factura']['tmp_name']) || !is_uploaded_file($_FILES['factura']['tmp_name'])) {
            $factura = $factura_actual;
        } else {
            $ext = explode(".", $_FILES['factura']['name']);
            if ($_FILES['factura']['type'] == 'application/pdf') {
                $factura = round(microtime(true)) . "." . end($ext);
                move_uploaded_file($_FILES['factura']['tmp_name'], "../files/facturasdispositivo/" . $factura);
            }
        }

        if (empty($iddispositivo)) {
            $rspta = $dispositivo->insertar($marca, $modelo, $tdispositivo, $ip, $serial, $maclan, $macwifi, $factura, $fvencimiento_garantia, $observaciones, $estado);
            echo $rspta ? 'Dispositivo registrado' : "Dispositivo no pudo ser registrado";
        } else {
            $rspta = $dispositivo->editar($iddispositivo, $marca, $modelo, $tdispositivo, $ip, $serial, $maclan, $macwifi, $factura, $fvencimiento_garantia, $observaciones, $estado);
            echo $rspta ? "Dispositivo editado" : "Dispositivo no pudo ser editado";
        }
        connClose();
        break;

    case 'activar':
        $rspta = $dispositivo->activar($iddispositivo);
        echo $rspta ? "Dispositivo habilitado" : "Dispositivo no se pudo habilitar";
        connClose();
        break;

    case 'desactivar':
        $rspta = $dispositivo->desactivar($iddispositivo);
        echo $rspta ? "Dispositivo inhabilitado" : "Dispositivo no se pudo inhabilitar";
        connClose();
        break;

    case 'mostrar':
        $rspta = $dispositivo->mostrar($iddispositivo);
        echo json_encode($rspta);
        connClose();
        break;

    case 'listar':
        $rspta = $dispositivo->listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ?
                '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->iddispositivo . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
                ' <button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->iddispositivo . ')" title="Deshabilitar"><i class="fa fa-close"></i></button>' :
                '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->iddispositivo . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
                ' <button class="btn btn-primary btn-xs" onclick="activar(' . $reg->iddispositivo . ')" title="Habilitar"><i class="fa fa-check"></i></button>',
                "1" => $reg->tipo_dispositivo,
                "2" => $reg->marca . ' ' . $reg->modelo,
                "3" => $reg->serial,
                "4" => $reg->maclan,
                "5" => ($reg->disponible) ? '<span class="label bg-green">SIN ASIGNAR</span>' : '<span class="label bg-red">ASIGNADO</span>',
                "6" => ($reg->estado) ? '<span class="label bg-green">NUEVO</span>' : '<span class="label bg-red">USADO</span>',
                "7" => ($reg->condicion) ? '<span class="label bg-green">HABILITADO</span>' : '<span class="label bg-red">INHABILITADO</span>'
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

    case 'listarDispositivosDocumentacion':

        $rspta = $dispositivo->listarDispositivosDocumentacion();

        $data = Array();
        $fecha30dias = new DateTime("now");
        $fecha30dias->modify('+1 month');

        $date_actual = new DateTime("now");

        while ($reg = $rspta->fetch_object()) {

            $date_vencimiento_garantia = new DateTime($reg->fvencimiento_garantia);

            $dif = $date_actual->diff($date_vencimiento_garantia);

            if ($dif->invert == 1) {

                $clase_garantia = "label label-danger";
                $texto_garantia = "VENCIDA";
            } else {

                $dif = $fecha30dias->diff($date_vencimiento_garantia);
                //COMPARO CON LA FECHA ACTUAL +30 DIAS.

                if ($dif->invert == 1) {
                    $clase_garantia = "label label-warning";
                    $texto_garantia = "POR VENCER < 30 dias";
                } else {
                    $clase_garantia = "label label-success";
                    $texto_garantia = "VIGENTE > 30 dias";
                }
            }

            $notificacion_garantia = '<span class="' . $clase_garantia . '">' . $texto_garantia . '</span>';

            $boton_factura = '';

            if ($reg->factura != "") {
                $boton_factura = '<button class="btn btn-secondary" onclick="window.open(\'../files/facturasdispositivo/' . $reg->factura . '\')" data-tooltip="tooltip" title="Factura digital"><i class="fa fa-file-pdf-o"></i></button>';
            }

            $data[] = array(
                "0" => $reg->tipo_dispositivo,
                "1" => $reg->marca . ' ' . $reg->modelo,
                "2" => $reg->serial,
                "3" => $reg->fvencimiento_garantia . ' ' . $notificacion_garantia,
                "4" => $boton_factura,
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

    case 'validarIpRegistrada':

        $rspta = $dispositivo->validarIpRegistrada($iddispositivo, $ip);
        echo json_encode($rspta);
        connClose();
        break;
}
?>