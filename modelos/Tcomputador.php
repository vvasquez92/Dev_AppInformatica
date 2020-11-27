<?php 

require "../config/conexion.php";

	Class Tcomputador{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttcomputador(){
			$sql="SELECT * FROM tcomputador WHERE condicion = 1"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM tcomputador WHERE condicion = 1 order by nombre asc";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre){
			$sql="INSERT INTO tcomputador (nombre,condicion) VALUES ('$nombre',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idtcomputador, $nombre){
			$sql="UPDATE tcomputador SET nombre='$nombre' WHERE idtcomputador='$idtcomputador'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idtcomputador){
			$sql="SELECT * FROM tcomputador WHERE idtcomputador='$idtcomputador'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idtcomputador){
			$sql="UPDATE tcomputador SET condicion=0 WHERE idtcomputador='$idtcomputador'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>