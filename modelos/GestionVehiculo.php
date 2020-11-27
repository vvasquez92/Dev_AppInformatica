<?php 

require "../config/conexion.php";

	Class GestionVehiculo{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idvehiculo,$idtgestion,$created_user){
			$sql="INSERT INTO `gestion_ve`(`idvehiculo`, `idtgestion`, `created_user` ) VALUES ($idvehiculo,$idtgestion,$created_user)";			
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT a.`idgestion_ve`, a.`idvehiculo`, a.`idtgestion`, a.`condicion`,e.titulo as tipo_gestion,
                              a.updated_time,b.ano,b.patente,c.nombre as marca,d.nombre as modelo,b.serialmotor
                              FROM `gestion_ve` as a
                              INNER JOIN vehiculo as b on a.idvehiculo = b.idvehiculo
                              INNER JOIN marcave as c on c.idmarca = b.idmarca
                              INNER JOIN modelove as d on d.idmodelove = b.idmodelo
                              INNER JOIN tgestion_ve as e on e.idtgestion_ve = a.idtgestion
                              WHERE a.condicion=1";
			return ejecutarConsulta($sql);
		} 

		public function listarHist(){
			$sql="SELECT a.`idgestion_ve`, a.`idvehiculo`, a.`idtgestion`, a.`condicion`,e.titulo as tipo_gestion,
                              a.updated_time,b.ano,b.patente,c.nombre as marca,d.nombre as modelo,b.serialmotor, a.created_time
                              FROM `gestion_ve` as a
                              INNER JOIN vehiculo as b on a.idvehiculo = b.idvehiculo
                              INNER JOIN marcave as c on c.idmarca = b.idmarca
                              INNER JOIN modelove as d on d.idmodelove = b.idmodelo
                              INNER JOIN tgestion_ve as e on e.idtgestion_ve = a.idtgestion
                              WHERE a.condicion=0";
			return ejecutarConsulta($sql);
		}
                
                public function mostrar($idgestion_ve){
			$sql="SELECT a.`idgestion_ve`, a.`idvehiculo`, a.`idtgestion`, a.`condicion`, 
							a.`created_time`  ,b.descripcion as tipo_gestion,
							a.`updated_time`
                              FROM `gestion_ve` as a
                              INNER JOIN tgestion_ve as b on b.idtgestion_ve = a.idtgestion
                              where idgestion_ve=$idgestion_ve";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function finalizarGestion($idgestion_ve,$condicion,$closed_user){
			$sql="UPDATE gestion_ve SET condicion=$condicion,closed_user=$closed_user,closed_time=now() where idgestion_ve=$idgestion_ve";
			return ejecutarConsulta($sql);
		}
                
                public function ActualizarGestion($idgestion_ve,$updated_user){
			$sql="UPDATE gestion_ve SET  updated_user=$updated_user,updated_time=now() where idgestion_ve=$idgestion_ve";
			return ejecutarConsulta($sql);
		}
	}
?>