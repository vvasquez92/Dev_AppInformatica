<?php 

require "../config/conexion.php";

	Class Bitacora{
		//Constructor para instancias
		public function __construct(){

		}

		public function bitacora(){
			$sql="SELECT d.idascensor, a.codigo, e.nombre, SUM(d.recorrido) AS recorrido, SUM(d.movimientos) AS movimientos FROM datos d INNER JOIN ascensor a ON d.idascensor=a.idascensor INNER JOIN edificio e ON a.id_edificio=e.idedificio GROUP BY idascensor";
			return ejecutarConsulta($sql);
		}
		
		public function bitacora_asc($idascensor){
		    $sql="SELECT d.idascensor, a.codigo, e.nombre, d.recorrido, d.movimientos FROM datos d INNER JOIN ascensor a ON d.idascensor=a.idascensor INNER JOIN edificio e ON a.id_edificio=e.idedificio WHERE d.idascensor = '$idascensor' ORDER BY d.created_time DESC";
		    return ejecutarConsulta($sql);
		}
		
		public function listarbitacora(){
		    $sql="SELECT d.idascensor, a.codigo, e.nombre, d.recorrido, d.movimientos, d.created_time FROM datos d INNER JOIN ascensor a ON d.idascensor=a.idascensor INNER JOIN edificio e ON a.id_edificio=e.idedificio ORDER BY d.created_time DESC";
		    return ejecutarConsulta($sql);
		}

	}
?>