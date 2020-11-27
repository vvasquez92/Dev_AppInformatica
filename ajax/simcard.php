<?php 
session_start();

require_once "../modelos/SimCard.php";

$simcard = new SimCard();

$idsimcard=isset($_POST["idsimcard"])?limpiarCadena($_POST["idsimcard"]):"";
$serial=isset($_POST["serial"])?limpiarCadena($_POST["serial"]):"";
$numero=isset($_POST["numero"])?limpiarCadena($_POST["numero"]):"";
$pin=isset($_POST["pin"])?limpiarCadena($_POST["pin"]):"";
$puk=isset($_POST["puk"])?limpiarCadena($_POST["puk"]):"";
$idoperador=isset($_POST["idoperador"])?limpiarCadena($_POST["idoperador"]):"";

switch ($_GET["op"]) {
    
	case 'guardaryeditar':
            
		$iduser=$_SESSION['iduser'];
            
		if(empty($idsimcard)){
                    
			$rspta=$simcard->insertar($serial,$numero,$pin,$puk,$idoperador,$iduser);
                        
			echo $rspta ? "Tarjeta SIM registrada" : "Tarjeta SIM no pudo ser registrada";
                        
		}
		else{
			$rspta=$simcard->editar($idsimcard, $serial,$numero,$pin,$puk,$idoperador,$iduser);
                        
			echo $rspta ? "Tarjeta SIM editada" : "Tarjeta SIM no pudo ser editada";
		}
		connClose();
		break;

	case 'desactivar':
            
		$rspta=$simcard->desactivar($idsimcard);
            
		echo $rspta ? "Tarjeta SIM inhabilitada" : "Tarjeta SIM no se pudo inhabilitar";
		connClose();         
		break;

	case 'activar':
            
		$rspta=$simcard->activar($idsimcard);
            
		echo $rspta ? "Tarjeta SIM habilitada" : "Tarjeta SIM no se pudo habilitar";
		connClose();
		break;

	case 'mostrar':
            
		$rspta=$simcard->mostrar($idsimcard);
            
		echo json_encode($rspta);
		connClose();
		break;
			
	case 'listar':
            
		$rspta=$simcard->listar();
            
		$data = Array();
                
		while ($reg = $rspta->fetch_object()){
                    
                        $cond="";$class="";
                        
                        if($reg->condicion==0){
                        $cond="INHABILITADO";$class="bg-red";                            
                        }elseif($reg->condicion==1){
                        $cond="HABILITADO";$class="bg-green";
                        }
                        
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idsimcard.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idsimcard.')" title="Deshabilitar"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idsimcard.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idsimcard.')" title="Habilitar"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->numero,
					"3"=>$reg->serial,
					"4"=>($reg->disponible)?'<span class="label bg-green">SIN ASIGNAR</span>':'<span class="label bg-red">ASIGNADO</span>',
					"5"=>'<span class="label '.$class.'">'.$cond.'</span>'
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
                
            case 'validarNumeroRegistrado':
                    
                $rspta = $simcard->validarNumeroRegistrado($idsimcard,$numero);
                
                echo json_encode($rspta);
				connClose();  
            break;
}

 ?>