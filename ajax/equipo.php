<?php 
session_start();

require_once "../modelos/Equipo.php";

$equipo = new Equipo();

$idequipo=isset($_POST["idequipo"])?limpiarCadena($_POST["idequipo"]):"";
$iddetalle=isset($_POST["iddetalle"])?limpiarCadena($_POST["iddetalle"]):"";
$imei=isset($_POST["imei"])?limpiarCadena($_POST["imei"]):"";
$serial=isset($_POST["serial"])?limpiarCadena($_POST["serial"]):"";
$caja=isset($_POST["caja"])?limpiarCadena($_POST["caja"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$detalle=mb_strtoupper(isset($_POST["detalle"])?limpiarCadena($_POST["detalle"]):"");


switch ($_GET["op"]) {
	case 'guardaryeditar':
		$iduser=$_SESSION['iduser'];
		if(empty($idequipo)){
			$rspta=$equipo->insertar($imei,$serial,$caja,$estado,$iduser,$iddetalle);
			echo $rspta ? "Movil registrado" : "Movil no pudo ser registrado";
		}
		else{
			$rspta=$equipo->editar($idequipo, $imei,$serial,$caja,$estado,$iduser,$iddetalle);
			echo $rspta ? "Movil editado" : "Movil no pudo ser editado";
		}
		connClose();
		break;

	case 'desactivar':
		$rspta=$equipo->desactivar($idequipo);
			echo $rspta ? "Movil inhabilitado" : "Movil no se pudo inhabilitar";
			connClose();
		break;

	case 'activar':
		$rspta=$equipo->activar($idequipo);
			echo $rspta ? "Movil habilitado" : "Movil no se pudo habilitar";
			connClose();
		break;

	case 'cargaTotales':
		$rspta=$equipo->cargaTotales();
			echo json_encode($rspta);
			connClose();
		break;

	case 'mostar':
		$rspta=$equipo->mostrar($idequipo);
			echo json_encode($rspta);
			connClose();
		break;
			
	case 'listar':
		$rspta=$equipo->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
                    
                        $cond="";$class="";
                        if($reg->condicion==0){
                        $cond="INHABILITADO";$class="bg-red";                            
                        }elseif($reg->condicion==1){
                        $cond="HABILITADO";$class="bg-green";
                        }elseif ($reg->condicion==2){
                        $cond="TELEFONO DESCOMPUESTO";$class="bg-red"; 
                        } elseif ($reg->condicion==3){
                        $cond="TELEFONO ROBADO";$class="bg-red";                              
                        }
                        
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idequipo.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idequipo.','.$reg->disponible.')" title="Deshabilitar"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idequipo.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->idequipo.')" title="Habilitar"><i class="fa fa-check"></i></button>',
					"1"=>$reg->marca.' '.$reg->nombre,
					"2"=>$reg->imei,
					"3"=>$reg->serial,
					"4"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
					"5"=>($reg->estado)?'<span class="label bg-green">NUEVO</span>':'<span class="label bg-red">USADO</span>',
					"6"=>'<span class="label '.$class.'">'.$cond.'</span>'
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

		case 'listarhistorico':
			$rspta=$equipo->listarhistorico();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
						
							$cond="";$class="";
							if($reg->condicion==0){
							$cond="INHABILITADO";$class="bg-red";                            
							}elseif($reg->condicion==1){
							$cond="HABILITADO";$class="bg-green";
							}elseif ($reg->condicion==2){
							$cond="TELEFONO DESCOMPUESTO";$class="bg-red"; 
							} elseif ($reg->condicion==3){
							$cond="TELEFONO ROBADO";$class="bg-red";                              
							}
							
				$data[] = array(
						"0"=>($reg->condicion)?
						'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idequipo.','.$reg->disponible.')" title="Deshabilitar"><i class="fa fa-close"></i></button>':
						'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idequipo.')" title="Habilitar"><i class="fa fa-check"></i></button>',
						"1"=>$reg->marca.' '.$reg->nombre,
						"2"=>$reg->imei,
						"3"=>$reg->serial,
						"4"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
						"5"=>($reg->estado)?'<span class="label bg-green">NUEVO</span>':'<span class="label bg-red">USADO</span>',
						"6"=>'<span class="label '.$class.'">'.$cond.'</span>'
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

		case 'selectequipo':
			$rspta = $equipo->selectequipo();
                        echo '<option value="" selected disabled>SELECCIONE EQUIPO</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idequipo.'>'.$reg->imei.' / '.$reg->marca.' '.$reg->nombre.'</option>';
			}
			connClose();
		break;

                case 'inhabilitarEquipo':
                    
                $equipo->actualizarDetalle($idequipo,$detalle);
                    
		$rspta=$equipo->desactivar($idequipo);
                
		echo $rspta ? "Movil inhabilitado" : "Movil no se pudo inhabilitar";
		connClose();
		break;
}

 ?>