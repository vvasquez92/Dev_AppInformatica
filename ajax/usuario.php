<?php 
session_start();
require_once "../modelos/Usuario.php";
require_once "../modelos/Role.php";


$user = new Usuario();

$iduser=isset($_POST["iduser"])?limpiarCadena($_POST["iduser"]):"";
$idrole=isset($_POST["idrole"])?limpiarCadena($_POST["idrole"]):"";
$username=isset($_POST["username"])?limpiarCadena($_POST["username"]):"";
$password=isset($_POST["password"])?limpiarCadena($_POST["password"]):"";
$nombre=isset($_POST["nombre"])?limpiarCadena($_POST["nombre"]):"";
$apellido=isset($_POST["apellido"])?limpiarCadena($_POST["apellido"]):"";
$tipo_documento=isset($_POST["tipo_documento"])?limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])?limpiarCadena($_POST["num_documento"]):"";
$fecha_nac=isset($_POST["fecha_nac"])?limpiarCadena($_POST["fecha_nac"]):"";
$direccion=isset($_POST["direccion"])?limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])?limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])?limpiarCadena($_POST["email"]):"";
$firma = isset($_POST["firma"]) ? limpiarCadena($_POST["firma"]) : '';

switch ($_GET["op"]) {
	case 'guardaryeditar':
		/*
		if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
			$imagen=$_POST["imagenactual"];	
		}else{
			$ext = explode(".",$_FILES['imagen']['name']);
			if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png" ){
				$imagen = round(microtime(true)).".".end($ext);
				move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/usuarios/".$imagen);
			}
		}*/
		if($_POST['imagen_cam']!=""){
			$imagen_php = explode(',', $_POST['imagen_cam']);
			$datos = base64_decode($imagen_php[1]);
			$imagen=round(microtime(true)).".png";
			file_put_contents("../files/empleados/".$imagen, $datos);
		}else{
			$imagen=$_POST["imagenactual"];
		}

		$clavehash = hash("SHA256", $password);

		if(empty($iduser)){
			$rspta=$user->insertar($idrole,$username,$clavehash,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen);
			connClose();
			echo $rspta ? "Usuario registrado" : $username."Usuario no pudo ser registrado";
		}
		else{
			$rspta=$user->editar($iduser,$idrole,$username,$clavehash,$nombre,$apellido,$tipo_documento,$num_documento,$fecha_nac,$direccion,$telefono,$email,$imagen);
			connClose();
			echo $rspta ? "Usuario editado" : "Usuario no pudo ser editado";
		}
		break;

	case 'desactivar':
		$rspta=$user->desactivar($iduser);
		echo $rspta ? "Usuario inhabilitado" : "Usuario no se pudo inhabilitar";
		connClose();
		break;

	case 'activar':
		$rspta=$user->activar($iduser);
		echo $rspta ? "Usuario habilitado" : "Usuario no se pudo habilitar";
		connClose();
		break;

	case 'mostar':
		$rspta=$user->mostrar($iduser);
		connClose();
		echo json_encode($rspta);
		break;
			
	case 'listar':
		$rspta=$user->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
			$data[] = array(
					"0"=>($reg->condicion)?
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->iduser.')"><i class="fa fa-close"></i></button>':
					'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->iduser.')"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre.''.$reg->apellido,
					"2"=>$reg->username,
					"3"=>$reg->email,
					"4"=>$reg->role,
					"5"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
				);
		}
		$results = array(
				"sEcho"=>1,
				"iTotalRecords"=>count($data),
				"iTotalDisplayRecords"=>count($data), 
				"aaData"=>$data
			);

		connClose();
		echo json_encode($results);
		break;

		case 'listado':
			$rspta=$user->listado();
			$data = Array();
			while ($reg = $rspta->fetch_object()){
				$data[] = array(
						"0"=>($reg->condicion)?
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->iduser.')"><i class="fa fa-close"></i></button>':
						'<button class="btn btn-warning btn-xs" onclick="mostar('.$reg->iduser.')"><i class="fa fa-pencil"></i></button>'.
						' <button class="btn btn-primary btn-xs" onclick="activar('.$reg->iduser.')"><i class="fa fa-check"></i></button>',
						"1"=>$reg->nombre,
						"2"=>$reg->apellido,
						"3"=>$reg->username,
						"4"=>$reg->email,
						"5"=>$reg->role,						
						"6"=>$reg->tipo_documento,
						"7"=>$reg->num_documento,
						"8"=>$reg->telefono,
						"9"=>$reg->direccion,
						"10"=>($reg->condicion)?'<span class="label bg-green">Habilitado</span>':'<span class="label bg-red">Inhabilitado</span>'
					);
			}
			$results = array(
					"sEcho"=>1,
					"iTotalRecords"=>count($data),
					"iTotalDisplayRecords"=>count($data), 
					"aaData"=>$data
				);

			connClose();
			echo json_encode($results);
			break;

		case 'selectRole':
			require_once "../modelos/Role.php";
			$role = new Role();
			$rspta = $role->select();
                        echo '<option value="" selected disabled>SELECCIONE ROLE</option>';
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idrole.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;

		case 'verificar':
			//require_once "../modelos/Usuario.php";
			//$usuario= new Usuario();

			$username_form = $_POST['username_form'];
			$password_form = $_POST['password_form'];

			$password_hash = hash("SHA256", $password_form);
			
			//$rspta=$usuario->verificar($username_form, $password_hash);
			$rspta=$user->verificar($username_form, $password_hash);
			$fecth = $rspta->fetch_object();

			if(isset($fecth)){
				$_SESSION['iduser']=$fecth->iduser;
				$_SESSION['nombre']=$fecth->nombre;
				$_SESSION['apellido']=$fecth->apellido;
				$_SESSION['imagen']=$fecth->imagen;
				$_SESSION['username']=$fecth->username;
				$_SESSION['idrole']=$fecth->idrole;

				$role= new Role();
				$permisos = $role->listarmarcados($fecth->idrole);

				$valores=array();

				while ($per = $permisos->fetch_object()){
					array_push($valores, $per->idpermiso);
				}

				in_array(1, $valores)? $_SESSION['administrador']=1:$_SESSION['administrador']=0;
				in_array(2, $valores)? $_SESSION['mantencion']=1:$_SESSION['mantencion']=0;
				in_array(4, $valores)? $_SESSION['vehiculos']=1:$_SESSION['vehiculos']=0;
				in_array(5, $valores)? $_SESSION['RRHH']=1:$_SESSION['RRHH']=0;
				in_array(6, $valores)? $_SESSION['Prevencion']=1:$_SESSION['Prevencion']=0;
				in_array(7, $valores)? $_SESSION['Mecanico']=1:$_SESSION['Mecanico']=0;

			}
			connClose();
			echo json_encode($fecth); 			
		break;

		case 'salir':
			session_unset();
			session_destroy();
			header("Location: ../index.php");
		break;

		case 'buscaFirma':
			$id_usuario = $_SESSION['iduser'];
			$rspta = $user->buscaFirma($id_usuario);
			$data = array();

			while ($reg = $rspta->fetch_object()) {
				$data[] = array(
					"iduser" => $reg->iduser
				);
			};
			echo json_encode($data);
		break;

		case 'guardaFirmaUsuario':
			$id_usuario = $_SESSION['iduser'];
			$rspta = $user->guardaFirmaUsuario($id_usuario,$firma);
			echo $rspta;
		break;
}

 ?>