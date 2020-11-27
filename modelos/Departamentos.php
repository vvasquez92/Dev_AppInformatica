<?php 

require "../config/conexion.php";

	Class Departamentos{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre){
			$sql="INSERT INTO departamento (nombre) VALUES ('$nombre')";
			return ejecutarConsulta($sql);
		}

		
		public function editar($iddepartamento, $nombre){
			$sql="UPDATE departamento SET nombre='$nombre' WHERE iddepartamento='$iddepartamento'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($iddepartamento){
			$sql="SELECT * FROM departamento WHERE iddepartamento='$iddepartamento'";
			return ejecutarConsultaSimpleFila($sql);
		}

		
		public function eliminar($iddepartamento){
			$sql="DELETE FROM departamento WHERE iddepartamento ='$iddepartamento'";
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT * FROM departamento";
			return ejecutarConsulta($sql);
		}

		public function selectDepartamentos(){
			$sql="SELECT * FROM departamento order by nombre asc"; 
			return ejecutarConsulta($sql);
		}

                public function selectDepartamentosIntranet(){
			$sql="SELECT * FROM departamento where intranet=1 order by nombre asc"; 
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