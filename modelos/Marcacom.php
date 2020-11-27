<?php 

require "../config/conexion.php";

	Class Marcacom{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectmarcacom(){
			$sql="SELECT * FROM marcacom WHERE condicion=1 order by nombre asc"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM marcacom WHERE condicion = 1 order by nombre asc";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre){
			$sql="INSERT INTO marcacom (nombre,condicion) VALUES ('$nombre',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idmarcacom, $nombre){
			$sql="UPDATE marcacom SET nombre='$nombre' WHERE idmarcacom='$idmarcacom'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idmarcacom){
			$sql="SELECT * FROM marcacom WHERE idmarcacom='$idmarcacom'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idmarcacom){
			$sql="UPDATE marcacom SET condicion=0 WHERE idmarcacom='$idmarcacom'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>