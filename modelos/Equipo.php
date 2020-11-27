<?php 

require "../config/conexion.php";

	Class Equipo{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($imei,$serial,$caja,$estado,$iduser,$iddetalle){
			$sql="INSERT INTO equipo (imei, serial, caja, create_user, condicion, estado, disponible, iddetalle) VALUES ('$imei', '$serial', '$caja', '$iduser', 1, '$estado', 1,$iddetalle)";
			return ejecutarConsulta($sql);
		}

		public function editar($idequipo, $imei,$serial,$caja,$estado,$iduser,$iddetalle){
			$sql="UPDATE equipo SET imei='$imei', serial='$serial', caja='$caja', updated_user='$iduser', estado='$estado', updated_time=CURRENT_TIMESTAMP, iddetalle='$iddetalle' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idequipo){
			$sql="UPDATE equipo SET condicion='0' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

		public function activar($idequipo){
			$sql="UPDATE equipo SET condicion='1' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

		public function asignar($idequipo){
			$sql="UPDATE equipo SET disponible='0' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

		public function liberar($idequipo){
			$sql="UPDATE equipo SET disponible='1' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idequipo){
			$sql="SELECT * FROM equipo WHERE idequipo='$idequipo'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function cargaTotales(){
			$sql="call trae_totales_equipo();";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT e.*, d.marca, d.nombre FROM equipo e INNER JOIN detalle d ON e.iddetalle = d.iddetalle WHERE condicion = 1";
			return ejecutarConsulta($sql);
		}

		public function listarhistorico(){
			$sql="SELECT e.*, d.marca, d.nombre FROM equipo e INNER JOIN detalle d ON e.iddetalle = d.iddetalle WHERE condicion <> 1";
			return ejecutarConsulta($sql);
		}

		public function selectequipo(){
			$sql="SELECT e.idequipo, e.imei, d.marca, d.nombre FROM equipo e INNER JOIN detalle d ON e.iddetalle = d.iddetalle WHERE e.condicion=1 AND e.disponible=1"; 
			return ejecutarConsulta($sql);
		}

                public function cambiarEstado($idequipo){
			$sql="UPDATE equipo SET estado='0' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}
                
                public function cambiarCondicion($idequipo,$condicion){
			$sql="UPDATE equipo SET condicion=$condicion WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}
                
                public function actualizarDetalle($idequipo,$detalle){
			$sql="UPDATE equipo SET detalle='$detalle' WHERE idequipo='$idequipo'";
			return ejecutarConsulta($sql);
		}

	}
?>