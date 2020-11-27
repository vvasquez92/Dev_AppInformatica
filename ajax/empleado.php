<?php 
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

session_start();
require_once "../modelos/Empleado.php";
require_once "../modelos/OfiDepartamento.php";
require_once "../modelos/AsigComputador.php";
require_once "../modelos/AsigTarjeta.php";
require_once "../modelos/Asignacion.php";
require_once "../modelos/AsignacionVehiculo.php";

$empleado = new Empleado();
$ofidepartamento = new OfiDepartamento();
$asigtarjeta = new AsigTarjeta();
$asigcomputador = new AsigComputador();
$asignacionvehiculo = new AsignacionVehiculo();
$asignacion = new Asignacion();

$idempleado=isset($_POST["idempleado"])?limpiarCadena($_POST["idempleado"]):"";
$nombre=mb_strtoupper(isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"");
$apellido=mb_strtoupper(isset($_POST["apellido"])?limpiarCadena($_POST["apellido"]):"");
$tipo_documento=isset($_POST["tipo_documento"])?limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])?limpiarCadena($_POST["num_documento"]):"";
if($tipo_documento=="P"){$num_documento = substr($num_documento, 2);}
$vencimiento_carnet=isset($_POST["vencimiento_carnet"])?limpiarCadena($_POST["vencimiento_carnet"]):"";
$licencia=isset($_POST["licencia"])?limpiarCadena($_POST["licencia"]):"";
$fecha_nac=isset($_POST["fecha_nac"])?limpiarCadena($_POST["fecha_nac"]):"";
$direccion=mb_strtoupper(isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"");
$movil=isset($_POST["movil"])?limpiarCadena($_POST["movil"]):"";
$residencial=isset($_POST["residencial"])?limpiarCadena($_POST["residencial"]):"";
if(trim($residencial)==""){$residencial="S/N";}
$email=mb_strtoupper(isset($_POST["email"])?limpiarCadena($_POST["email"]):"");
$email_corporativo=mb_strtoupper(isset($_POST["email_corporativo"])?limpiarCadena($_POST["email_corporativo"]):"");
$idregiones=isset($_POST["idregiones"])?limpiarCadena($_POST["idregiones"]):"";
$idcomunas=isset($_POST["idcomunas"])?limpiarCadena($_POST["idcomunas"]):"";
$idoficina=isset($_POST["idoficina"])?limpiarCadena($_POST["idoficina"]):"";
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):"";
$idcargo=isset($_POST["idcargo"])?limpiarCadena($_POST["idcargo"]):"";
$id_empleado=isset($_POST["id_empleado"])?limpiarCadena($_POST["id_empleado"]):"";
$id_empl=isset($_POST["id_empl"])?limpiarCadena($_POST["id_empl"]):"";
$doc_vencimiento_carnet=isset($_POST["doc_vencimiento_carnet"])?limpiarCadena($_POST["doc_vencimiento_carnet"]):"";
$doc_licencia=isset($_POST["doc_licencia"])?limpiarCadena($_POST["doc_licencia"]):"";
$doc_correo_corporativo=isset($_POST["doc_correo_corporativo"])?limpiarCadena($_POST["doc_correo_corporativo"]):"";

$fromdate = date("Y-m-d", strtotime($fecha_nac)); 


switch ($_GET["op"]) {
	case 'guardaryeditar':
            
            /*
		if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
			$imagen=$_POST["imagenactual"];
		}else{
			$ext = explode(".",$_FILES['imagen']['name']);
			if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png" ){
				$imagen = round(microtime(true)).".".end($ext);
				move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/empleados/".$imagen);
			}
		}
             
             */
            
		if($_POST['imagen_cam']!=""){
			$imagen_php = explode(',', $_POST['imagen_cam']);
			$datos = base64_decode($imagen_php[1]);
			$imagen=round(microtime(true)).".png";
			file_put_contents("../files/empleados/".$imagen, $datos);
		}else{
			$imagen=$_POST["imagenactual"];
		}
            
		$iduser=$_SESSION['iduser'];
		if(empty($idempleado)){		
			$idoficina_departamento=$ofidepartamento->id_ofidepartamento($idoficina, $iddepartamento);
			$id=intval($idoficina_departamento["idoficina_departamento"]);
			if(empty($id)){
				$id=$ofidepartamento->insertar($idoficina, $iddepartamento);
				$rspta=$empleado->insertar($nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fromdate,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $id);
				echo $rspta ? "Usuario registrado" : "Usuario no pudo ser registrado";
			}else{
				$rspta=$empleado->insertar($nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fromdate,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $id);
				echo $rspta ? "Usuario registrado" : "Usuario no pudo ser registrado";
			}		
		}
		else{
			$idoficina_departamento=$ofidepartamento->id_ofidepartamento($idoficina, $iddepartamento);
			$id=intval($idoficina_departamento["idoficina_departamento"]);
			if(empty($id)){
				$id=$ofidepartamento->insertar($idoficina, $iddepartamento);
				$rspta=$empleado->editar($idempleado,$nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fecha_nac,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $id);
				echo $rspta ? "Usuario editado" : "Usuario no pudo ser editado";
			}else{
				$rspta=$empleado->editar($idempleado,$nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fecha_nac,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $id);
				echo $rspta ? "Usuario editado" : "Usuario no pudo ser editado";
			}
		}
		connClose();
	break;

	case 'guardar':
		$iduser=1;
		$ofidepartamento = new OfiDepartamento();
		$idoficina_departamento=$ofidepartamento->id_ofidepartamento($idoficina, $iddepartamento);
		$id=intval($idoficina_departamento["idoficina_departamento"]);
		if(empty($id)){
			$id=$ofidepartamento->insertar($idoficina, $iddepartamento);
			$rspta=$empleado->insertar($nombre,$apellido,$tipo_documento,$num_documento,$fromdate,$direccion,$movil, $residencial,$email,null, $iduser, $idcargo, $idcomunas, $idregiones, $id);
			echo $rspta ? "Usuario registrado" : "Usuario no pudo ser registrado";
		}else{
			$rspta=$empleado->insertar($nombre,$apellido,$tipo_documento,$num_documento,$fromdate,$direccion,$movil, $residencial,$email,null, $iduser, $idcargo, $idcomunas, $idregiones, $id);
			echo $rspta ? "Usuario registrado" : "Usuario no pudo ser registrado";
		}
		connClose();		
	break;

	case 'desactivar': 
                $rspta=$empleado->desactivar($idempleado);
		echo $rspta ? "Empleado inhabilitado" : "Empleado no se pudo inhabilitar";
		connClose();
	break;

	case 'activar':
		$rspta=$empleado->activar($idempleado);
		echo $rspta ? "Empleado habilitado" : "Empleado no se pudo habilitar";
		connClose();
	break;

	case 'mostar':
		$rspta=$empleado->mostrar($idempleado);
		echo json_encode($rspta);
		connClose();
	break;
			
	case 'listar':
		$rspta=$empleado->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){                    
			$boton_doc='';
			$boton_correo='';
			
			if($reg->condicion){
				$boton_act = '<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idempleado.')" title="Deshabilitar"><i class="fa fa-close"></i></button>';
			}else{
				$boton_act = '<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idempleado.')" title="Habilitar"><i class="fa fa-check"></i></button>';
			}
			
			if(is_null($reg->vencimiento_carnet) || is_null($reg->licencia)){
				$boton_doc ='<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalDocumentos" data-idempleado="'.$reg->idempleado.'" title="Registrar documentos" ><i class="fa fa-newspaper-o"></i></button>';                        
			}

			if(is_null($reg->email_corporativo)  || $reg->email_corporativo ==""){
				$boton_correo ='<button class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#modalCorreoCorporativo" data-idempleado="'.$reg->idempleado.'" title="Registrar correo corporativo" ><i class="fa fa-envelope"></i></button>';                        
			}
			
			$data[] = array(
						"0"=>'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->idempleado.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
								$boton_act.
								'<button class="btn btn-primary btn-xs" onclick="verFichaEmpleado('.$reg->idempleado.')" title="Ver ficha"><i class="fa fa-id-card"></i></button>'.
								$boton_doc.
								$boton_correo,                            
						"1"=>$reg->nombre.' '.$reg->apellido,
						"2"=>$reg->tipo_documento.'-'.$reg->num_documento,
						"3"=>$reg->cargo,
						"4"=>$reg->departamento,
						"5"=>$reg->oficinas,
						"6"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
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
		$rspta=$empleado->listarhistorico();
		$data = Array();
		while ($reg = $rspta->fetch_object()){                    
			$boton_doc='';
			$boton_correo='';
			
			if($reg->condicion){
				$boton_act = '<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idempleado.')" title="Deshabilitar"><i class="fa fa-close"></i></button>';
			}else{
				$boton_act = '<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idempleado.')" title="Habilitar"><i class="fa fa-check"></i></button>';
			}	
			
			$data[] = array(
						"0"=>$boton_act.
							'<button class="btn btn-primary btn-xs" onclick="verFichaEmpleado('.$reg->idempleado.')" title="Ver ficha"><i class="fa fa-id-card"></i></button>',                            
						"1"=>$reg->nombre.' '.$reg->apellido,
						"2"=>$reg->tipo_documento.'-'.$reg->num_documento,
						"3"=>$reg->cargo,
						"4"=>$reg->departamento,
						"5"=>$reg->oficinas,
						"6"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
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

	case 'listaroficina':
		$idoficina = $_GET["id"];
		$rspta=$empleado->listar_oficina($idoficina);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
				"0"=>$reg->nombre.' '.$reg->apellido,
				"1"=>$reg->tipo_documento.'-'.$reg->num_documento,
				"2"=>$reg->departamento.' - '.$reg->cargo	        
				);
		}
		$results = array(
			"iTotalRecords"=>count($data),
			"aaData"=>$data
			);
		echo json_encode($results);
		connClose();
	break;

	case 'listardepartamento':
		$iddepartamento = $_GET["id"];
		$rspta=$empleado->listar_departamento($iddepartamento);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
				"0"=>$reg->nombre.' '.$reg->apellido,
				"1"=>$reg->tipo_documento.'-'.$reg->num_documento,
				"2"=>$reg->oficinas.' - '.$reg->cargo	        
				);
		}
		$results = array(
			"iTotalRecords"=>count($data),
			"aaData"=>$data
			);
		echo json_encode($results);
		connClose();
	break;

	case 'listarcargo':
		$idcargo = $_GET["id"];
		$rspta=$empleado->listar_cargo($idcargo);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
				"0"=>$reg->nombre.' '.$reg->apellido,
				"1"=>$reg->tipo_documento.'-'.$reg->num_documento,
				"2"=>$reg->oficinas.' - '.$reg->departamento	        
				);
		}
		$results = array(
			"iTotalRecords"=>count($data),
			"aaData"=>$data
			);
		echo json_encode($results);
		connClose();
	break;


	case 'selectEmpleadosDocCompleta':
		$rspta = $empleado->selectempleado();
		echo '<option value="" selected disabled>SELECCIONE EMPLEADO</option>';
		while($reg = $rspta->fetch_object()){
			if($reg->tiene_licencia==1 
			&& $reg->tiene_fecha_carnet==1 
			&& $reg->cantidad_dias_licencia > 0 
			&& $reg->cantidad_dias_carnet > 0  ){
				echo '<option value='.$reg->idempleado.'>'.$reg->nombre.' '.$reg->apellido.' / '.$reg->num_documento.'</option>';
			}
		}
		connClose();
	break;
			
	case 'selectempleado':
		$rspta = $empleado->selectempleado();
		echo '<option value="" selected disabled>SELECCIONE EMPLEADO</option>';
		while($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->idempleado.'>'.$reg->nombre.' '.$reg->apellido.' / '.$reg->num_documento.'</option>';
		}
		connClose();
	break;                
                
	case 'listartelefonos':
		$nombre = $_GET["nombres"];
		$apellido = $_GET["apellidos"]; 
		$rspta = $empleado->listartelefonos($nombre, $apellido);
		$data = Array();
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => $reg->nombre,
				"1" => $reg->apellido,
				"2" => $reg->numero,
				"3" => $reg->email,
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
                
	case 'validarExisteNumDocumento':
				
		$rspta = $empleado->validarExisteNumDocumento($num_documento,$idempleado);  
		echo json_encode($rspta);
		connClose();
				
	break;
            
	case 'verAsignacionesEmpleado':
                
		$cantAsig=$asignacion->CantidadAsignaciones($idempleado);			
		$cantAsigVehic=$asignacionvehiculo->CantidadAsignaciones($idempleado);		
		$cantAsigComp=$asigcomputador->CantidadAsignaciones($idempleado);		
		$cantAsigTarj=$asigtarjeta->CantidadAsignaciones($idempleado);
		
		$results=array(
			"asigMovil"=>$cantAsig,
			"asigVehic"=>$cantAsigVehic,
			"asigComp"=>$cantAsigComp,
			"asigTarj"=>$cantAsigTarj
		);

		echo json_encode($results);  
		connClose();                   
	break;    
        
	case 'selectempleado2':
		$rspta = $empleado->selectempleado();
		$data = Array();
		while($reg = $rspta->fetch_object()){
			$data[] = array(
			"id" => $reg->idempleado,
			"text" => $reg->nombre.", ".$reg->apellido." (".$reg->tipo_documento.": ".$reg->num_documento." )",
			);
		}
		echo json_encode($data);
		connClose();
	break;
                
	case 'guardarPlazoDocumentosVehiculo':

		$rspta=$empleado->actualizarPlazoDocumentosEmpleado($id_empleado,$doc_vencimiento_carnet,$doc_licencia);
		echo $rspta ? "Fechas de Documentos del empleado actualizado" : "Fechas de Documentos del empleado no pudo ser actualizado";                
		connClose();

	break; 
    
    
    case 'listarEmpleadosDocumentacionCompleta':
                
		$rspta=$empleado->listarEmpleadosDocumentacionCompleta();
		$data = Array();
		$fecha30dias = new DateTime("now");
		$fecha30dias->modify('+1 month');
		$date_actual = new DateTime("now"); 
                
		while ($reg = $rspta->fetch_object()){
                        
			$date_licencia = new DateTime($reg->licencia);                     

			$dif = $date_actual->diff($date_licencia);
			
			if($dif->invert == 1){
					$clase_licencia="label label-danger";
					$texto_licencia="VENCIDA";
			}else{ 
				
				$dif = $fecha30dias->diff($date_licencia);//COMPARO CON LA FECHA ACTUAL +3 DIAS.
				
				if($dif->invert == 1){
					$clase_licencia="label label-warning";
					$texto_licencia="POR VENCER < 30 dias";
				} else{
					$clase_licencia="label label-success";
					$texto_licencia="VIGENTE > 30 dias";   
				}                                          
			}

			$notificacion_licencia='<span class="'.$clase_licencia.'">'.$texto_licencia.'</span>';

			/* -------------------------------------------------------------------------*/
			$date_vencimiento_carnet = new DateTime($reg->vencimiento_carnet);                     

			$dif = $date_actual->diff($date_vencimiento_carnet);
			
			if($dif->invert == 1){
					$clase_vencimiento_carnet="label label-danger";
					$texto_vencimiento_carnet="VENCIDA";
			}else{ 
				
				$dif = $fecha30dias->diff($date_vencimiento_carnet);//COMPARO CON LA FECHA ACTUAL +3 DIAS.
				
				if($dif->invert == 1){
					$clase_vencimiento_carnet="label label-warning";
					$texto_vencimiento_carnet="POR VENCER < 30 dias";
				} else{
					$clase_vencimiento_carnet="label label-success";
					$texto_vencimiento_carnet="VIGENTE > 30 dias";   
				}                                          
			}

			$notificacion_vencimiento_carnet='<span class="'.$clase_vencimiento_carnet.'">'.$texto_vencimiento_carnet.'</span>';
			
			$data[] = array(
				"0"=>$reg->nombre.' '.$reg->apellido,
				"1"=>$reg->departamento,
				"2"=>$reg->licencia.' '.$notificacion_licencia,
									"3"=>$reg->vencimiento_carnet.' '.$notificacion_vencimiento_carnet,
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
     
    case 'ActualizarCorreoCorporativo':
    
        $rspta=$empleado->actualizarCorreoCorporativo($id_empl,$doc_correo_corporativo);
		echo $rspta ? "Correo Corporativo del empleado actualizado" : "Correo Corporativo del empleado no pudo ser actualizado";                
		connClose();

    break;    
                
}

 ?>