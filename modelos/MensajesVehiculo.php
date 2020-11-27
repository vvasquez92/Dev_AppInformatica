<?php 

require "../config/conexion.php";

	Class MensajesVehiculo{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idgestion_ve,$titulo,$descripcion,$created_user){
			$sql="INSERT INTO `mensajes_ve`(`idgestion_ve`, `titulo`, `descripcion`, `created_user`) VALUES ($idgestion_ve,'$titulo','$descripcion',$created_user)";
			return ejecutarConsulta($sql);
		}

		public function listar($idgestion_ve){
			$sql="SELECT a.`idmensaje_ve`, a.`idgestion_ve`, a.`titulo`, a.`descripcion`, a.`created_time`, a.`created_user` ,b.nombre,b.apellido,b.imagen,DAY(a.created_time) as dia,
                              ELT(DATE_FORMAT(a.created_time,'%m'),'ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE') as mes
                              FROM `mensajes_ve` as a
                              INNER JOIN user as b on b.iduser = a.created_user
                              WHERE a.idgestion_ve=$idgestion_ve
							  ORDER BY a.created_time DESC;";
			//echo $sql;							  
			return ejecutarConsulta($sql);
		}                   
	}
?>