<?php 

require "../config/conexion.php";

	Class Oficinas{
		//Constructor para instancias
		public function __construct(){

		}

		/*
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
		*/
		public function selectOficinas(){
			$sql="SELECT * FROM oficinas"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT o.*, c.comuna_nombre as comuna, p.provincia_nombre as provincia, r.region_nombre as region "
					. "from oficinas o "
					. "inner join comunas c on o.idcomunas = c.comuna_id "
					. "inner join provincias p on c.provincia_id = p.provincia_id "
					. "inner join regiones r on p.region_id = r.region_id "
					. "order by o.nombre asc" ;
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre,$direccion, $idregiones,$idprovincias,$idcomunas){
			$sql="INSERT INTO oficinas (nombre,direccion,idregiones,idcomunas,idprovincias) VALUES ('$nombre','$direccion','$idregiones','$idcomunas','$idprovincias')";
			return ejecutarConsulta($sql);
		}

		public function editar($idoficinas, $nombre,$direccion, $idregiones,$idprovincias,$idcomunas){
			$sql="UPDATE oficinas SET nombre='$nombre', direccion = '$direccion' , idregiones = '$idregiones' , idprovincias = '$idprovincias' , idcomunas = '$idcomunas'" 
					." WHERE idoficinas='$idoficinas'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idoficinas){
			$sql="SELECT * FROM oficinas WHERE idoficinas='$idoficinas'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idoficinas){
			$sql="UPDATE oficinas SET condicion=0 WHERE idoficinas='$idoficinas'";
			return ejecutarConsultaSimpleFila($sql);
		}

		/*
		public function listarmarcados($idrole){
			$sql="SELECT * FROM role_permiso WHERE idrole=$idrole"; 
			return ejecutarConsulta($sql);
		}
		*/
	}
?>