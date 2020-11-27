<?php 

require "../config/conexion.php";

	Class Chip{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($serial,$numero,$pin,$puk,$idoperador,$iduser){
			$sql="INSERT INTO chip (serial, numero, pin, puk, idoperador, condicion, disponible, create_user) VALUES ('$serial', '$numero', '$pin', '$puk', '$idoperador', 1, 1,'$iduser')";
			return ejecutarConsulta($sql);
		}

		public function editar($idchip, $serial,$numero,$pin,$puk,$idoperador,$iduser){
			$sql="UPDATE chip SET serial='$serial', numero='$numero', pin='$pin', puk='$puk', idoperador='$idoperador', update_user='$iduser', updated_time=CURRENT_TIMESTAMP WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idchip){
			$sql="UPDATE chip SET condicion='0' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function activar($idchip){
			$sql="UPDATE chip SET condicion='1' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function asignar($idchip){
			$sql="UPDATE chip SET disponible='0' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function liberar($idchip){
			$sql="UPDATE chip SET condicion='1', disponible = '1' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function serial($idchip, $nserie){
			$sql="UPDATE chip SET serial='$nserie' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idchip){
			$sql="SELECT a.*,b.nombre as nombre_operador FROM chip a INNER JOIN operador b ON a.idoperador = b.idoperador WHERE a.idchip='$idchip'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT c.*, o.nombre FROM chip c INNER JOIN operador o ON c.idoperador = o.idoperador";
			return ejecutarConsulta($sql);
		}   
                
		public function listarSimRobadas(){
			$sql="SELECT c.*, o.nombre ,
                              iFNULL((select detalle from gestionchip where idchip=c.idchip order by created_time DESC limit 1),'NO SE HA REALIZADO GESTION') as detalle_ultima_gestion,
                              iFNULL((select created_time from gestionchip where idchip=c.idchip order by created_time DESC limit 1),'NO SE HA REALIZADO GESTION')  as fecha_ultima_gestion
                              FROM chip c 
                              INNER JOIN operador o ON c.idoperador = o.idoperador 
                              WHERE c.condicion=3";
			return ejecutarConsulta($sql);
		}

		public function selectChip(){
			$sql="SELECT c.*, o.nombre FROM chip c INNER JOIN operador o ON c.idoperador = o.idoperador WHERE c.condicion='1' AND c.disponible='1'"; 
			return ejecutarConsulta($sql);
		}
                
		public function cambiarCondicion($idchip,$condicion){
			$sql="UPDATE chip SET condicion=$condicion WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}
                
		public function actualizarDetalle($idchip,$detalle) {
                        $sql="UPDATE chip SET detalle='$detalle' WHERE idchip='$idchip'";
			return ejecutarConsulta($sql);
		}


	}
?>