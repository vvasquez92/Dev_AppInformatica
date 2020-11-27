<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

require_once "../modelos/Empleado.php";

$empleado = new Empleado();

switch ($_GET["op"]) {
    case 'cumpleanos':
        $rspta = $empleado->cumpleanos();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "idempleado" => $reg->idempleado,
                "nombreemp" => $reg->nombreemp,
                "cargo" => $reg->cargo,
                "oficina" => $reg->oficina,
                "fecha_nac" => $reg->fecha_nac,
                "fecha" => $reg->fecha
            );
        }
        echo json_encode($data);
        connClose();
        break;
}