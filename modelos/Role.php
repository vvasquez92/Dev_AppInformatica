<?php 

require "../config/conexion.php";

	Class Role{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre, $descripcion, $permisos){
			$sql="INSERT INTO role (nombre, descripcion, condicion, publico) VALUES ('$nombre', '$descripcion',1,1)";
			//return ejecutarConsulta($sql);
			$idrolenew = ejecutarConsulta_retornarID($sql);

			$num_permisos = 0;
			$sw = true;

			while($num_permisos < count($permisos)){
				$sql_detalle = "INSERT INTO role_permiso(idrole, idpermiso) VALUES ('$idrolenew', '$permisos[$num_permisos]')";

				ejecutarConsulta($sql_detalle) or $sw=false;

				$num_permisos = $num_permisos + 1;
			}

			return $sw;
		}

		public function editar($idrole, $nombre, $descripcion, $permisos){
			$sql="UPDATE role SET nombre='$nombre', descripcion='$descripcion',  updated_time=CURRENT_TIMESTAMP WHERE idrole='$idrole'";
			ejecutarConsulta($sql);

			// Eliminamos permisos
			$sqldel="DELETE FROM role_permiso WHERE idrole='$idrole'";
			ejecutarConsulta($sqldel);

			//Crgamos nuevos permisos
			$num_permisos = 0;
			$sw = true;

			while($num_permisos < count($permisos)){
				$sql_detalle = "INSERT INTO role_permiso(idrole, idpermiso) VALUES ('$idrole', '$permisos[$num_permisos]')";

				ejecutarConsulta($sql_detalle) or $sw=false;

				$num_permisos = $num_permisos + 1;
			}

			return $sw;
		}

		public function eliminar($idrole){
			$sql="DELETE FROM role WHERE idrole='$idrole'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idrole){
			$sql="SELECT * FROM role WHERE idrole='$idrole'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT * FROM role";
			return ejecutarConsulta($sql);
		}

		public function select(){
			$sql="SELECT * FROM role WHERE condicion=1"; 
			return ejecutarConsulta($sql);
		}

		public function listarmarcados($idrole){
			$sql="SELECT * FROM role_permiso WHERE idrole=$idrole"; 
			return ejecutarConsulta($sql);
		}
	}
?>