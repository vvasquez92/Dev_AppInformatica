<?php 

require "../config/conexion.php";

	Class SimCard{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($serial,$numero,$pin,$puk,$idoperador,$iduser){
			$sql="INSERT INTO simcard (serial, numero, pin, puk, idoperador, condicion, disponible, created_user) VALUES ('$serial', '$numero', '$pin', '$puk', '$idoperador', 1, 1,'$iduser')";
			return ejecutarConsulta($sql);
		}

		public function editar($idsimcard, $serial,$numero,$pin,$puk,$idoperador,$iduser){
			$sql="UPDATE simcard SET serial='$serial', numero='$numero', pin='$pin', puk='$puk', idoperador='$idoperador', updated_user='$iduser', updated_time=CURRENT_TIMESTAMP WHERE idsimcard='$idsimcard'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idsimcard){
			$sql="UPDATE simcard SET condicion='0' WHERE idsimcard='$idsimcard'";
			return ejecutarConsulta($sql);
		}

		public function activar($idsimcard){
			$sql="UPDATE simcard SET condicion='1' WHERE idsimcard='$idsimcard'";
			return ejecutarConsulta($sql);
		}

		public function asignar($idsimcard){
			$sql="UPDATE simcard SET disponible='0' WHERE idsimcard='$idsimcard'";
			return ejecutarConsulta($sql);
		}

		public function liberar($idsimcard){
			$sql="UPDATE simcard SET disponible='1' WHERE idsimcard='$idsimcard'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idsimcard){
			$sql="SELECT a.*,b.nombre as nombre_operador FROM simcard a INNER JOIN operador b ON a.idoperador = b.idoperador WHERE a.idsimcard='$idsimcard'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT c.*, o.nombre FROM simcard c INNER JOIN operador o ON c.idoperador = o.idoperador";
			return ejecutarConsulta($sql);
		}   
                
                public function validarNumeroRegistrado($idsimcard,$numero) {
                    $sql="SELECT count(1) as cantidad 
                          FROM `simcard` 
                          WHERE numero = $numero";
                    
                    if($idsimcard != ""){
                        $sql.=" AND a.idsimcard <> $idsimcard";
                    }
                    return ejecutarConsultaSimpleFila($sql);
                }
	}
?>