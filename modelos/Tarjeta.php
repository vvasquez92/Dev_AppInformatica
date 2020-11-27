<?php 

require "../config/conexion.php";

	Class Tarjeta{
		//Constructor para instancias
		public function __construct(){

		}
       
		public function listar(){
			$sql="SELECT t.idtarjeta, t.codigo, t.codigosys, t.disponible, t.condicion, n.nombre AS nivel FROM tarjeta t INNER JOIN nivel n ON t.idnivel = n.idnivel WHERE t.condicion = 1";
			return ejecutarConsulta($sql);
		}
                
		public function selecttarjeta(){
			$sql="SELECT t.idtarjeta, t.codigo, n.nombre FROM tarjeta t INNER JOIN nivel n ON t.idnivel = n.idnivel WHERE t.condicion = 1 AND t.disponible=1"; 
			return ejecutarConsulta($sql);
		}
                
		public function asignar($idtarjeta){
			$sql="UPDATE tarjeta SET disponible='0' WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsulta($sql);
		}

		public function cargaTotales(){
			$sql="call trae_totales_tarjeta();";
			return ejecutarConsultaSimpleFila($sql);
		}
                
		public function liberar($idtarjeta){
			$sql="UPDATE tarjeta SET disponible='1' WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsulta($sql);
		}
                
		public function insertar($idnivel,$codigo,$codigosys, $iduser){
			$sql="INSERT INTO tarjeta (idnivel, codigo, codigosys, created_user) VALUES ('$idnivel', '$codigo', '$codigosys', '$iduser')";
			return ejecutarConsulta($sql);
		}
                
		public function editar($idtarjeta,$idnivel,$codigo,$codigosys, $iduser){
			$sql="UPDATE tarjeta SET idnivel='$idnivel', codigo='$codigo', codigosys='$codigosys', updated_user='$iduser', updated_time=CURRENT_TIMESTAMP WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idtarjeta){
			$sql="UPDATE tarjeta SET condicion='0' WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsulta($sql);
		}

		public function activar($idtarjeta){
			$sql="UPDATE tarjeta SET condicion='1' WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsulta($sql);
		}
                
		public function mostrar($idtarjeta){
			$sql="SELECT * FROM tarjeta WHERE idtarjeta='$idtarjeta'";
			return ejecutarConsultaSimpleFila($sql);
		}

	}
?>