<?php

session_start();

require_once "../modelos/ticket.php";

$ticket = new Ticket();

$idticket = isset($_POST["idticket"]) ? limpiarCadena($_POST["idticket"]) : "";
$idtipo = isset($_POST["idtipo"]) ? limpiarCadena($_POST["idtipo"]) : "";
$idempleado = isset($_POST["idempleado"]) ? limpiarCadena($_POST["idempleado"]) : "";
$observacion = isset($_POST["observacion"]) ? limpiarCadena($_POST["observacion"]) : "";
$respuesta = isset($_POST["respuesta"]) ? limpiarCadena($_POST["respuesta"]) : "";
$idtipoticket = isset($_POST["idtipoticket"]) ? limpiarCadena($_POST["idtipoticket"]) : "";


switch ($_GET["op"]) {

    case 'guardaryeditar':
        $iduser = $_SESSION['iduser'];
        if (empty($idticket)) {
            $rspta = $ticket->insertar($idtipo, $idempleado, $observacion, $iduser);
            echo $rspta ? "Ticket registrado" : "Ticket no pudo ser registrado";
        } else {
            $rspta = $ticket->editar($idticket, $idtipo, $idempleado, $observacion);
            echo $rspta ? "Ticket editado" : "Ticket no pudo ser editado";
        }
        connClose();
        break;

    case 'responder':
        $iduser = $_SESSION['iduser'];
        $rspta = $ticket->responder($idticket, $respuesta, $iduser);

        echo $rspta ? "Ticket editado" : "Ticket no pudo ser editado";

        connClose();
        break;

    case 'tipo_solicitud':
        $rspta = $ticket->tipo_solicitud($idtipoticket);
        echo '<option value="" selected disabled>SELECCIONE TIPO</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idtipo_ticket . '>' . $reg->desc_tipoticket . '</option>';
        }
        connClose();
        break;

    case 'tipo_ticket':
        $rspta = $ticket->tipo_ticket();
        echo '<option value="" selected disabled>SELECCIONE TIPO</option>';
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->id_tipoticket_cabecera . '>' . $reg->desc_tipoticket_cabecera . '</option>';
        }
        connClose();
        break;

    case 'mostar':
        $rspta = $ticket->mostrar($idticket);
        echo json_encode($rspta);
        connClose();
        break;

    case 'listar':
        $iduser = $_SESSION['iduser'];
        $rspta = $ticket->listar($iduser);

        $data = array();
        while ($reg = $rspta->fetch_object()) {

            $cond = "";
            $class = "";
            if ($reg->estado_ticket == 'Pendiente') {
                $cond = "PENDIENTE";
                $class = "bg-red";
            } else {
                $cond = "FINALIZADO";
                $class = "bg-green";
            }

            $data[] = array(
                "0" => '',
                "1" => $reg->fh_ticket,
                "2" => $reg->desc_tipoticket_cabecera,
                "3" => $reg->desc_tipoticket,
                "4" => $reg->nombre . ' ' . $reg->apellido,
                "5" => '<span class="label ' . $class . '">' . $cond . '</span>',
                "6" => $reg->respuesta_ticket
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

    case 'listar_todo':
        $iduser = $_SESSION['iduser'];
        $rspta = $ticket->listar_todo($iduser);

        $data = array();
        while ($reg = $rspta->fetch_object()) {

            if ($reg->estado_ticket == 'Pendiente') {
                $opciones = '<button class="btn btn-success btn-xs" onclick="gestionar(' . $reg->id_ticket . ')" title="Gestionar"><i class="fa fa-plus-circle"></i></button>';
            } else {
                $opciones = '';
            }

            $cond = "";
            $class = "";
            if ($reg->estado_ticket == 'Pendiente') {
                $cond = "PENDIENTE";
                $class = "bg-red";
            } else {
                $cond = "FINALIZADO";
                $class = "bg-green";
            }

            $data[] = array(
                "0" => $opciones,
                "1" => $reg->fh_ticket,
                "2" => $reg->desc_tipoticket_cabecera,
                "3" => $reg->desc_tipoticket,
                "4" => $reg->nombre . ' ' . $reg->apellido,
                "5" => '<span class="label ' . $class . '">' . $cond . '</span>',
                "6" => $reg->respuesta_ticket
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

        case 'listar_todohistorico':
            $iduser = $_SESSION['iduser'];
            $rspta = $ticket->listar_todohistorico($iduser);
    
            $data = array();
            while ($reg = $rspta->fetch_object()) {
    
                if ($reg->estado_ticket == 'Pendiente') {
                    $opciones = '<button class="btn btn-success btn-xs" onclick="gestionar(' . $reg->id_ticket . ')" title="Gestionar"><i class="fa fa-plus-circle"></i></button>';
                } else {
                    $opciones = '';
                }
    
                $cond = "";
                $class = "";
                if ($reg->estado_ticket == 'Pendiente') {
                    $cond = "PENDIENTE";
                    $class = "bg-red";
                } else {
                    $cond = "FINALIZADO";
                    $class = "bg-green";
                }
    
                $data[] = array(
                    "0" => $opciones,
                    "1" => $reg->fh_ticket,
                    "2" => $reg->desc_tipoticket_cabecera,
                    "3" => $reg->desc_tipoticket,
                    "4" => $reg->nombre . ' ' . $reg->apellido,
                    "5" => '<span class="label ' . $class . '">' . $cond . '</span>',
                    "6" => $reg->respuesta_ticket
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
