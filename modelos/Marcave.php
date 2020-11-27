<?php 

require "../config/conexion.php";

	Class Marcave{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectmarcave(){
			$sql="SELECT * FROM marcave order by nombre asc"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM marcave WHERE condicion = 1 order by nombre asc";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre){
			$sql="INSERT INTO marcave (nombre,condicion) VALUES ('$nombre',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idmarcave, $nombre){
			$sql="UPDATE marcave SET nombre='$nombre' WHERE idmarca='$idmarcave'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idmarcave){
			$sql="SELECT * FROM marcave WHERE idmarca='$idmarcave'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idmarcave){
			$sql="UPDATE marcave SET condicion=0 WHERE idmarca='$idmarcave'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>