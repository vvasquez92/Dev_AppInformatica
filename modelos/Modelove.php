<?php 

require "../config/conexion.php";

	Class Modelove{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectmodelove($idmarca){
			$sql="SELECT * FROM modelove WHERE idmarca = '$idmarca'"; 
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT mo.*, ma.nombre as marca "
				."from modelove mo inner join marcave ma on mo.idmarca = ma.idmarca "
				."where mo.condicion = 1";
			return ejecutarConsulta($sql);
		}

		public function insertar($nombre,$idmarca){
			$sql="INSERT INTO modelove (nombre,idmarca,condicion) VALUES ('$nombre','$idmarca',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idmarcacom, $nombre){
			$sql="UPDATE modelove SET nombre='$nombre' WHERE idmarcacom='$idmarcacom'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idmodelove){
			$sql="SELECT * FROM modelove WHERE idmodelove='$idmodelove'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function eliminar($idmodelove){
			$sql="UPDATE modelove SET condicion=0 WHERE idmodelove='$idmodelove'";
			return ejecutarConsultaSimpleFila($sql);
		}


	}
?>