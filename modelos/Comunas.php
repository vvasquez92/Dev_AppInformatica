<?php 

require "../config/conexion.php";

	Class Comunas{
		//Constructor para instancias
		public function __construct(){

		}

		public function selectComunas($idregion){
                        $sql="SELECT c.* FROM comunas c INNER JOIN provincias p ON c.provincia_id=p.provincia_id INNER JOIN regiones r on p.region_id = r.region_id WHERE r.region_id = '$idregion'"; 
			return ejecutarConsulta($sql);
		}


	}
?>