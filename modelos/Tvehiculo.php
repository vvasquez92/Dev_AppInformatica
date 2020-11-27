<?php 

require "../config/conexion.php";

	Class Tvehiculo{
		//Constructor para instancias
		public function __construct(){

		}

		public function selecttvehiculo(){
			$sql="SELECT * FROM `tvehiculo` WHERE 1"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM tvehiculo WHERE condicion = 1 order by nombre asc";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre){
			$sql="INSERT INTO tvehiculo (nombre,condicion) VALUES ('$nombre',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idtvehiculo, $nombre){
			$sql="UPDATE tvehiculo SET nombre='$nombre' WHERE idtvehiculo='$idtvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idtvehiculo){
			$sql="SELECT * FROM tvehiculo WHERE idtvehiculo='$idtvehiculo'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idtvehiculo){
			$sql="UPDATE tvehiculo SET condicion=0 WHERE idtvehiculo='$idtvehiculo'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>