<?php 

require "../config/conexion.php";

	Class Permiso{
		//Constructor para instancias
		public function __construct(){

		}

		public function listar(){
			$sql="SELECT * FROM permiso";
			return ejecutarConsulta($sql);
		}

	}
?>