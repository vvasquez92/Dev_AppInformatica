<?php 

require "../config/conexion.php";

	Class Tdispositivo{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttdispositivo(){
			$sql="SELECT * FROM tdispositivo WHERE condicion = 1"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM tdispositivo WHERE condicion = 1 order by nombre asc";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre){
			$sql="INSERT INTO tdispositivo (nombre,condicion) VALUES ('$nombre',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idtdispositivo, $nombre){
			$sql="UPDATE tdispositivo SET nombre='$nombre' WHERE idtdispositivo='$idtdispositivo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idtdispositivo){
			$sql="SELECT * FROM tdispositivo WHERE idtdispositivo='$idtdispositivo'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idtdispositivo){
			$sql="UPDATE tdispositivo SET condicion=0 WHERE idtdispositivo='$idtdispositivo'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>