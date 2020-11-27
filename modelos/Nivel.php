<?php 

require "../config/conexion.php";

	Class Nivel{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre,$descripcion){
			$sql="INSERT INTO nivel (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
			return ejecutarConsulta($sql);
		}

		public function editar($idnivel, $nombre, $descripcion){
			$sql="UPDATE nivel SET nombre='$nombre', descripcion='$descripcion' WHERE idnivel='$idnivel'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idnivel){
			$sql="UPDATE nivel SET condicion='0' WHERE idnivel='$idnivel'";
			return ejecutarConsulta($sql);
		}

		public function activar($idnivel){
			$sql="UPDATE nivel SET condicion='1' WHERE idnivel='$idnivel'";
			return ejecutarConsulta($sql);
		}
	
		public function mostrar($idnivel){
			$sql="SELECT * FROM nivel WHERE idnivel='$idnivel'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT * FROM nivel";
			return ejecutarConsulta($sql);
		}

		public function SLNivel(){
			$sql="SELECT idnivel, nombre, descripcion FROM nivel WHERE condicion=1"; 
			return ejecutarConsulta($sql);
		}



	}
?>