<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "../modelos/ServiciosTaller.php";
require '../public/api/PHPMailer/src/Exception.php';
require '../public/api/PHPMailer/src/PHPMailer.php';
require '../public/api/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

$ServiciosTaller = new ServiciosTaller();

$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$id_servicio=isset($_POST["id_servicio"])?limpiarCadena($_POST["id_servicio"]):"";
$inicio=isset($_POST["inicio"])?limpiarCadena($_POST["inicio"]):"";
$fin=isset($_POST["fin"])?limpiarCadena($_POST["fin"]):"";
$fidCatRepin=isset($_POST["idCatRep"])?limpiarCadena($_POST["idCatRep"]):"";
$idRep=isset($_POST["idRep"])?limpiarCadena($_POST["idRep"]):"";
$cantidad=isset($_POST["cantidad"])?limpiarCadena($_POST["cantidad"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$motivoReprog=isset($_POST["motivoReprog"])?limpiarCadena($_POST["motivoReprog"]):"";

$firma = isset($_POST["firma"]) ? limpiarCadena($_POST["firma"]) : '';
$rutRecibe=isset($_POST["rutRecibe"])?limpiarCadena($_POST["rutRecibe"]):"";
$nombreRecibe=isset($_POST["nombreRecibe"])?limpiarCadena($_POST["nombreRecibe"]):"";
$correoRecibe=isset($_POST["correoRecibe"])?limpiarCadena($_POST["correoRecibe"]):"";

switch ($_GET['op']) {

    case 'listaServicios':
        $rspta = $ServiciosTaller->listaServicios($estado);

        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "id_servicio" => $reg->id_servicio,
                "tipo_servicio" => $reg->tipo_servicio,
                "color" => $reg->color,
                "fh_inicio" => $reg->fh_inicio,
                "fh_fin" => $reg->fh_fin,
                "motivo" => $reg->motivo,
                "estado" => $reg->estado,
                "kms_actual" => $reg->kms_actual,
                "fh_solicitud" => $reg->fh_solicitud,
                "fini" => $reg->fini,
                "ffin" => $reg->ffin,
                "mecanico" => $reg->mecanico,
                "patente" => $reg->patente,
                "datos_veh" => $reg->datos_veh,
                "tipo_servicio_desc" => $reg->tipo_servicio_desc,
            );
        };
        echo json_encode($data);

    break;

    case 'tomaServicio':
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->tomaServicio($id_usuario,$id_servicio,$inicio,$fin);
        echo $rspta;
    break;

    case 'cargaRepXCat':
        echo '<option value="" selected disabled>SELECCIONE REPUESTO</option>';
        $rspta = $ServiciosTaller->cargaRepXCat();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_repuesto.'>'.$reg->nombre. ' '.$reg->marca. ' '. $reg->modelo. ' - '. $reg->nro_serie. '</option>';
        }
    break;

    case 'cantidadMaxRep':
        $rspta = $ServiciosTaller->cantidadMaxRep($idRep);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "cantidad" => $reg->cantidad
            );
        };
        echo json_encode($data);
        break;
    break;

    case 'cargaRepuestosServicio':
        $rspta=$ServiciosTaller->cargaRepuestosServicio($id_servicio);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $boton='';            

            $data[] = array(
                "0"=>'<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarRep('.$reg->id_repuesto.')"><i class="fa fa-times-circle"></i></button>',
                "1"=>$reg->repuesto,
                "2"=>$reg->cantidad
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

    case 'cargaRepuestosServicioEntregado':
        $rspta=$ServiciosTaller->cargaRepuestosServicio($id_servicio);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $boton='';            

            $data[] = array(                
                "0"=>$reg->repuesto,
                "1"=>$reg->cantidad
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

    case 'agregaRepAServicio':
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->agregaRepAServicio($id_servicio,$idRep,$cantidad,$id_usuario);
        echo $rspta;
    break;

    case 'finalizarServicio':
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->finalizarServicio($id_servicio,$observaciones,$id_usuario);
        echo $rspta;
    break;

    case 'pdf':        
        $rspta=$ServiciosTaller->pdf($id_servicio);
        echo json_encode($rspta);
    break;

    case 'cargaRepuestosServicioPDF':
        $rspta = $ServiciosTaller->cargaRepuestosServicioPDF($id_servicio);

        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "marca" => $reg->marca,
                "nombre" => $reg->nombre,
                "modelo" => $reg->modelo,
                "cantidad" => $reg->cantidad
            );
        };
        echo json_encode($data);
    break;

    case 'iniciaServicio':
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->iniciaServicio($id_servicio, $id_usuario);
        echo $rspta;
    break;

    case 'quitarRep':
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->quitarRep($id_servicio,$idRep, $id_usuario);
        echo $rspta;
    break;

    case 'reprogramaServicio':
        $rspta = $ServiciosTaller->reprogramaServicio($id_servicio, $inicio, $fin, $motivoReprog);
        echo $rspta;
    break;

    case 'recibeVehiculo':   
        $id_usuario = $_SESSION['iduser'];
        $rspta = $ServiciosTaller->recibeServicio($id_servicio,$firma,$rutRecibe, $nombreRecibe, $correoRecibe, $id_usuario);
        echo $rspta;
    break;

    case 'guardaPDF':
        if ($_FILES['pdf']['name']) {
            $ext = explode(".", $_FILES['pdf']['name']);            
            $link = "../files/taller";
            $archivoname = "ServicioTallerMecanico_" . $id_servicio . ".pdf";
            if (!file_exists($link)) {
                mkdir($link, 0777, true);
            }
            move_uploaded_file($_FILES['pdf']['tmp_name'], $link . '/' . $archivoname);            
        }
    break;

    case 'enviaCorreo':

        $body= '<html>
                    <head>
                        <meta charset="utf-8">
                    </head>
                    <body>
                        <p>Estimado/a</p>
                        <p>Adjunto encontrará el detalle de lo realizado en el taller mecánico al vehículo que acaba de recibir</p>
                        <p>Saludos</p>                        
                    </body>
                </html>';

        $Mailer = new PHPMailer();
        $Mailer->isSMTP();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->Port = 587;
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = "tls";
        $Mailer->SMTPDebug = 0;
        $Mailer->Debugoutput = 'html';
        $Mailer->Host = "smtp.gmail.com";
        $Mailer->Username = "notificaciones.fabrimetal@gmail.com";
        $Mailer->Password = "fm818820";
        $Mailer->From = "notificaciones@fabrimetalsa.cl";
        $Mailer->FromName = "AppInformatica - Fabrimetal";
        $Mailer->Subject = "Detalle Servicio Taller Mecanico" ;        
        $Mailer->addAttachment('../files/taller/ServicioTallerMecanico_' . $id_servicio .'.pdf');
        $Mailer->msgHTML($body);
        $Mailer->addAddress($correoRecibe, '');

        if (!$Mailer->send()) {
            echo "Mailer: " . $Mailer->ErrorInfo;
        } else {
            echo 1;
        }        
        
    break;
    
}
