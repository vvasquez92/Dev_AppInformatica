<?php 

require "../config/conexion.php";

	Class GestionChip{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idchip,$detalle,$descripcion,$created_user){
			$sql="INSERT INTO `gestionchip`(`idchip`, `detalle`, `descripcion`, `created_user`) VALUES ($idchip,'$detalle','$descripcion',$created_user)";
			return ejecutarConsulta($sql);
		}

		public function listar($idchip){
			$sql="SELECT a.`idgestion`, a.`idchip`, a.`detalle`, a.`descripcion`, a.`created_user`,b.nombre,b.apellido,b.imagen,DAY(a.created_time) as dia,
                              ELT(DATE_FORMAT(a.created_time,'%m'),'ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE') as mes
                              FROM `gestionchip` as a
                              INNER JOIN user as b ON b.iduser = a.created_user
                              WHERE a.idchip=$idchip
                              ORDER BY a.created_time DESC";
			return ejecutarConsulta($sql);
		}                   
	}
?>