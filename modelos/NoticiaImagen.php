<?php 

require "../config/conexion.php";

	Class NoticiaImagen{
            
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($url,$idnoticia){
			$sql="INSERT INTO `noticia_imagen`(`url`, `idnoticia`) VALUES ('$url',$idnoticia)";
			return ejecutarConsulta($sql);
		}
                
		public function mostrar($idnoticia){
			$sql="SELECT * FROM noticia_imagen  WHERE idnoticia='$idnoticia'";
			return ejecutarConsulta($sql);
		}
                
                public function eliminar($idnoticia) {
                        $sql="DELETE FROM noticia_imagen WHERE idnoticia=".$idnoticia;
			return ejecutarConsulta($sql);
                }
	}
?>