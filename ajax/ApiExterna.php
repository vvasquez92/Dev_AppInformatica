<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

//Modelos
require_once "../modelos/Empleado.php";

//Funcion para validar pasametros por POST
function ParametrosDisponiblesPost($params) {
    //Suponemos que todos los parametros vienene disponibles
    $disponible = true;
    $sinparams = "";

    foreach ($params as $param) {
        if (!isset($_POST[$param]) || strlen($_POST[$param]) <= 0) {
            $disponible = false;
            $sinparams = $sinparams . ", " . $param;
        }
    }

    //si faltan parametros
    if (!$disponible) {
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parametro' . substr($sinparams, 1, strlen($sinparams)) . ' vacio';

        //error de visualización
        echo json_encode($response);

        //detener la ejecición adicional
        die();
    }
}

//Funcion para validar pasametros por GET
function ParametrosDisponiblesGet($params) {
    //Suponemos que todos los parametros vienene disponibles
    $disponible = true;
    $sinparams = "";

    foreach ($params as $param) {
        if (!isset($_GET[$param]) || strlen($_GET[$param]) <= 0) {
            $disponible = false;
            $sinparams = $sinparams . ", " . $param;
        }
    }

    //si faltan parametros
    if (!$disponible) {
        $response = array();
        $response['error'] = true;
        $response['message'] = 'Parametro' . substr($sinparams, 1, strlen($sinparams)) . ' vacio';

        //error de visualización
        echo json_encode($response);

        //detener la ejecición adicional
        die();
    }
}

$rspta = '';
if (isset($_GET['apicall'])) {

    //Aqui iran todos los llamados de nuestra api
    switch ($_GET['apicall']) {

        case 'SelectEmpleado':
            //Objeto del modelo
            $empleado = new Empleado();

            $rut=$_GET['rut'];

            $rspta = $empleado->mostrarPorRut($rut);
            

            break;
    }
} else {
    //Valida que venga una llamada correcta a la APi
    $rspta = 'Llamda incorrecta a la API';
}

echo json_encode($rspta);
?>