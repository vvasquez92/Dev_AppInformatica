<?php 

require "../config/conexion.php";

	Class Dispositivo{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($marca,$modelo,$tdispositivo,$ip,$serial,$maclan,$macwifi,$factura,$fvencimiento_garantia,$observaciones,$estado){
			$sql="INSERT INTO `dispositivo`(`marca`, `modelo`, `tdispositivo`,ip, `serial`, `maclan`, `macwifi`,factura,fvencimiento_garantia, `observaciones`,`estado`) VALUES ('$marca','$modelo',$tdispositivo,'$ip','$serial','$maclan','$macwifi','$factura','$fvencimiento_garantia','$observaciones',$estado)";
			return ejecutarConsulta($sql);
		}

		public function editar($iddispositivo,$marca,$modelo,$tdispositivo,$ip,$serial,$maclan,$macwifi,$factura,$fvencimiento_garantia,$observaciones,$estado){
			$sql="UPDATE `dispositivo` SET `marca`='$marca',`modelo`='$modelo',`tdispositivo`=$tdispositivo,ip='$ip',`serial`='$serial',`maclan`='$maclan',`macwifi`='$macwifi',factura='$factura',fvencimiento_garantia='$fvencimiento_garantia',`observaciones`='$observaciones',estado=$estado WHERE iddispositivo=$iddispositivo";
			return ejecutarConsulta($sql);
		}

		public function desactivar($iddispositivo){
			$sql="UPDATE dispositivo SET condicion='0' WHERE iddispositivo='$iddispositivo'";
			return ejecutarConsulta($sql);
		}

		public function activar($iddispositivo){
			$sql="UPDATE dispositivo SET condicion='1' WHERE iddispositivo='$iddispositivo'";
			return ejecutarConsulta($sql);
		}

		public function asignar($iddispositivo){
			$sql="UPDATE dispositivo SET disponible='0' WHERE iddispositivo='$iddispositivo'";
			return ejecutarConsulta($sql);
		}

		public function liberar($iddispositivo){
			$sql="UPDATE dispositivo SET disponible='1' WHERE iddispositivo='$iddispositivo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($iddispositivo){
			$sql="SELECT * FROM dispositivo WHERE iddispositivo='$iddispositivo'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT d.iddispositivo, t.nombre as tipo_dispositivo, d.marca, d.serial, d.maclan, d.condicion, d.estado, d.disponible,d.modelo 
                              FROM dispositivo d 
                              INNER JOIN tdispositivo t ON t.idtdispositivo = d.tdispositivo ";
			return ejecutarConsulta($sql);
		}
                
                public function listarDispositivosDocumentacion(){
			$sql="SELECT d.iddispositivo, t.nombre as tipo_dispositivo, d.marca, d.serial, d.maclan, d.condicion, d.estado, d.disponible,d.modelo,d.factura,d.fvencimiento_garantia 
                              FROM dispositivo d 
                              INNER JOIN tdispositivo t ON t.idtdispositivo = d.tdispositivo 
                              WHERE d.fvencimiento_garantia is not null 
                              AND d.fvencimiento_garantia <> '0000-00-00' 
                              AND d.factura is not null
                              AND d.factura <> '' ";
			return ejecutarConsulta($sql);
		}
                
		 public function validarIpRegistrada($iddispositivo,$ip) {
                    
                    $sql="SELECT count(1) as cantidad 
                          FROM (                        
                            SELECT a.idasigcompu AS id FROM `asigcompu`  as a
                            WHERE a.condicion=1
                            AND  a.ip='$ip'";
        
                    $sql.=" UNION
                            
                            SELECT b.iddispositivo AS id FROM dispositivo as b
                            WHERE b.ip='$ip'
                            AND b.condicion=1 ";
                    
                        if($iddispositivo != ""){
                            $sql.=" AND b.iddispositivo <> $iddispositivo";
                        }
                            
                          $sql.=" ) as c";
                    
              
                        
                    return ejecutarConsultaSimpleFila($sql);
                }


	}
?>