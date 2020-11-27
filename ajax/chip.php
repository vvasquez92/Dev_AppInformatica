<?php
session_start();

require_once "../modelos/Chip.php";

$chip = new Chip();

$idchip = isset($_POST["idchip"]) ? limpiarCadena($_POST["idchip"]) : "";
$idoperador = isset($_POST["idoperador"]) ? limpiarCadena($_POST["idoperador"]) : "";
$numero = isset($_POST["numero"]) ? limpiarCadena($_POST["numero"]) : "";
$serial = isset($_POST["serial"]) ? limpiarCadena($_POST["serial"]) : "";
$pin = isset($_POST["pin"]) ? limpiarCadena($_POST["pin"]) : "";
$puk = isset($_POST["puk"]) ? limpiarCadena($_POST["puk"]) : "";
$detalle = mb_strtoupper(isset($_POST["detalle"]) ? limpiarCadena($_POST["detalle"]) : "");


switch ($_GET["op"]) {
	case 'guardaryeditar':
		$iduser = $_SESSION['iduser'];
		if (empty($idchip)) {
			$rspta = $chip->insertar($serial, $numero, $pin, $puk, $idoperador, $iduser);
			echo $rspta ? "Tarjeta SIM registrada" : "Tarjeta SIM no pudo ser registrada";
		} else {
			$rspta = $chip->editar($idchip, $serial, $numero, $pin, $puk, $idoperador, $iduser);
			echo $rspta ? "Tarjeta SIM editada" : "Tarjeta SIM no pudo ser editada";
		}
		connClose();
		break;

	case 'desactivar':
		$rspta = $chip->desactivar($idchip);
		echo $rspta ? "Tarjeta SIM inhabilitada" : "Tarjeta SIM no se pudo inhabilitar";
		connClose();
		break;

	case 'activar':
		$rspta = $chip->activar($idchip);
		echo $rspta ? "Tarjeta SIM habilitada" : "Tarjeta SIM no se pudo habilitar";
		connClose();
		break;

	case 'mostar':
		$rspta = $chip->mostrar($idchip);
		echo json_encode($rspta);
		connClose();
		break;

	case 'listar':
		$rspta = $chip->listar();
		$data = array();
		while ($reg = $rspta->fetch_object()) {

			$cond = "";
			$class = "";
			if ($reg->condicion == 0) {
				$cond = "INHABILITADO";
				$class = "bg-red";
			} elseif ($reg->condicion == 1) {
				$cond = "HABILITADO";
				$class = "bg-green";
			} elseif ($reg->condicion == 3) {
				$cond = "SIM ROBADA";
				$class = "bg-red";
			}

			$data[] = array(
				"0" => ($reg->condicion) ?
					'<button class="btn btn-warning btn-xs" onclick="mostar(' . $reg->idchip . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
					' <button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idchip . ')" title="Deshabilitar"><i class="fa fa-close"></i></button>' :
					'<button class="btn btn-warning btn-xs" onclick="mostar(' . $reg->idchip . ')" title="Editar"><i class="fa fa-pencil"></i></button>' .
					' <button class="btn btn-primary btn-xs" onclick="activar(' . $reg->idchip . ')" title="Habilitar"><i class="fa fa-check"></i></button>',
				"1" => $reg->nombre,
				"2" => $reg->numero,
				"3" => $reg->serial,
				"4" => ($reg->disponible) ? '<span class="label bg-green">SIN ASIGNAR</span>' : '<span class="label bg-red">ASIGNADO</span>',
				"5" => '<span class="label ' . $class . '">' . $cond . '</span>'
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

	case 'listarSimRobadas':

		$rspta = $chip->listarSimRobadas();
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button data-toggle="tooltip" data-placement="top" title="Visualizar Gestión" class="btn btn-info btn-xs" onclick="mostrarGestion(' . $reg->idchip . ')" ><i class="fa fa-search"></i></button>' .
					'<button title="Realizar Gestión" class="btn btn-success btn-xs" type="button" data-toggle="modal" data-target="#modalGestion" data-idchip="' . $reg->idchip . '"  ><i class="fa fa-plus-circle"></i></button>' .
					'<button data-toggle="tooltip" data-placement="top" title="Inhabilitar SIM Definitivamente" class="btn btn-danger btn-xs" onclick="inhabilitarSim(' . $reg->idchip . ')"><i class="fa fa-window-close"></i></button>',
				"1" => $reg->nombre,
				"2" => $reg->numero,
				"3" => $reg->serial,
				"4" => ($reg->disponible) ? '<span class="label bg-green">SIN ASIGNAR</span>' : '<span class="label bg-red">ROBADO</span>',
				"5" => $reg->detalle_ultima_gestion,
				"6" => $reg->fecha_ultima_gestion,
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

	case 'selectChip':
		$rspta = $chip->selectChip();
		echo '<option value="" selected disabled>SELECCIONE TARJETA SIM</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idchip . '>' . $reg->nombre . ' - ' . $reg->numero . '</option>';
		}
		connClose();
		break;

	case 'inhabilitarDefinitivamente':

		$chip->actualizarDetalle($idchip, $detalle);

		$rspta = $chip->desactivar($idchip);
		echo $rspta ? "Tarjeta SIM inhabilitada" : "Tarjeta SIM no se pudo inhabilitar";
		connClose();
		break;
}
