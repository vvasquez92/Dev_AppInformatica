<?php 
session_start();
require_once "../modelos/Bitacora.php";



$bitacora = new Bitacora();



switch ($_GET["op"]) {
			
	case 'listarbitacora':
		$rspta=$bitacora->listarbitacora();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
		    //$tiempo=round(($reg->recorrido)/60, 2);
			$data[] = array(
					"0"=>$reg->codigo,
					"1"=>$reg->nombre,
					"2"=>$reg->movimientos,
			        "3"=>$reg->recorrido,
			        "4"=>$reg->created_time
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

		case 'selectRole':
			require_once "../modelos/Role.php";
			$role = new Role();
			$rspta = $role->select();
			while($reg = $rspta->fetch_object()){
				echo '<option value='.$reg->idrole.'>'.$reg->nombre.'</option>';
			}
			connClose();
			break;

		case 'verificar':
			require_once "../modelos/Usuario.php";
			$usuario= new Usuario();

			$username_form = $_POST['username_form'];
			$password_form = $_POST['password_form'];

			$password_hash = hash("SHA256", $password_form);
			
			$rspta=$usuario->verificar($username_form, $password_hash);
           
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
				

			}

			echo json_encode($fecth); 	
			connClose();		
			break;

			case 'salir':
			session_unset();
			session_destroy();
			header("Location: ../index.php");
			connClose();
			break;
}

 ?>