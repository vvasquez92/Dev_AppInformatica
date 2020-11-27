<?php 

require "../config/conexion.php";

	Class Cargos{
		//Constructor para instancias
		public function __construct(){

		}

		
		public function insertar($nombre, $iddepartamento){
			$sql="INSERT INTO cargos (nombre, iddepartamento) VALUES ('$nombre', '$iddepartamento')";
			return ejecutarConsulta($sql);
		}

		
		public function editar($idcargos, $nombre, $iddepartamento){
			$sql="UPDATE cargos SET nombre='$nombre', iddepartamento='$iddepartamento' WHERE idcargos='$idcargos'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idcargos){
			$sql="SELECT * FROM cargos WHERE idcargos='$idcargos'";
			return ejecutarConsultaSimpleFila($sql);
		}

		
		public function eliminar($idcargos){
			$sql="DELETE FROM cargos WHERE idcargos='$idcargos'";
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT c.*, d.nombre AS departamento FROM cargos c INNER JOIN departamento d ON c.iddepartamento = d.iddepartamento";
			return ejecutarConsulta($sql);
		}
		public function selectCargos($iddepartamento){
			$sql="SELECT * FROM cargos WHERE iddepartamento='$iddepartamento' order by nombre asc"; 
			return ejecutarConsulta($sql);
		}

		/*
		public function listarmarcados($idrole){
			$sql="SELECT * FROM role_permiso WHERE idrole=$idrole"; 
			return ejecutarConsulta($sql);
		}
		*/
	}
?>