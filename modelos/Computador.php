<?php 

require "../config/conexion.php";

	Class Computador{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idmarca, $modelo, $tcomputador, $serial, $maclan, $macwifi,$factura,$fvencimiento_garantia, $observaciones, $estado,$precio){
			$sql="INSERT INTO computador (idmarca, modelo, tcomputador, serial, maclan, macwifi,factura,fvencimiento_garantia,observaciones, estado, precio) VALUES ('$idmarca', '$modelo', '$tcomputador', '$serial', '$maclan', '$macwifi','$factura','$fvencimiento_garantia','$observaciones', '$estado','$precio')";
			return ejecutarConsulta($sql);
		}

		public function editar($idcomputador, $idmarca, $modelo, $tcomputador, $serial, $maclan, $macwifi,$factura,$fvencimiento_garantia, $observaciones, $estado){
			$sql="UPDATE computador SET idmarca='$idmarca', modelo='$modelo', tcomputador='$tcomputador', serial='$serial', maclan='$maclan', macwifi='$macwifi',factura='$factura',fvencimiento_garantia='$fvencimiento_garantia', observaciones='$observaciones', estado='$estado' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idcomputador){
			$sql="UPDATE computador SET condicion='0' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

		public function activar($idcomputador){
			$sql="UPDATE computador SET condicion='1' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

		public function asignar($idcomputador){
			$sql="UPDATE computador SET disponible='0' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

		public function liberar($idcomputador){
			$sql="UPDATE computador SET disponible='1' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idcomputador){
			$sql="SELECT * FROM computador WHERE idcomputador='$idcomputador'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function cargaTotales(){
			$sql="call trae_totales_computador();";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT c.idcomputador, t.nombre as tipo, m.nombre as marca, c.serial, c.maclan, c.condicion, c.estado, c.disponible,c.modelo FROM computador c INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador INNER JOIN marcacom m ON c.idmarca = m.idmarcacom WHERE c.condicion = 1";
			return ejecutarConsulta($sql);
		}

		public function listarhistorico(){
			$sql="SELECT c.idcomputador, t.nombre as tipo, m.nombre as marca, c.serial, c.maclan, c.condicion, c.estado, c.disponible,c.modelo FROM computador c INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador INNER JOIN marcacom m ON c.idmarca = m.idmarcacom WHERE c.condicion = 0";
			return ejecutarConsulta($sql);
		}

                public function listarComputadoresDocumentacion(){
			$sql="SELECT c.idcomputador, t.nombre as tipo, m.nombre as marca, c.serial, c.maclan, c.condicion, c.estado, c.disponible,c.modelo ,c.fvencimiento_garantia,c.factura
                            FROM computador c 
                            INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador 
                            INNER JOIN marcacom m ON c.idmarca = m.idmarcacom
                            WHERE c.fvencimiento_garantia is not null
                            AND c.fvencimiento_garantia <> '0000-00-00'
                            AND c.factura is not null
							AND c.factura <> ''
							AND c.fvencimiento_garantia >= sysdate() ";
			return ejecutarConsulta($sql);
		}

        public function listarComputadoresDocumentacionVencida(){
			$sql="SELECT c.idcomputador, t.nombre as tipo, m.nombre as marca, c.serial, c.maclan, c.condicion, c.estado, c.disponible,c.modelo ,c.fvencimiento_garantia,c.factura
                            FROM computador c 
                            INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador 
                            INNER JOIN marcacom m ON c.idmarca = m.idmarcacom
                            WHERE c.fvencimiento_garantia is not null
                            AND c.fvencimiento_garantia <> '0000-00-00'
                            AND c.factura is not null
                            AND c.factura <> '' 
							AND c.fvencimiento_garantia < sysdate() ";
			return ejecutarConsulta($sql);
		}

		public function selectcomputador(){
			$sql="SELECT c.idcomputador, t.nombre as tipo, m.nombre as marca, c.serial, c.modelo FROM computador c INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador INNER JOIN marcacom m ON c.idmarca = m.idmarcacom WHERE c.condicion=1 AND c.disponible=1"; 
			return ejecutarConsulta($sql);
		}

                public function cambiarEstadoUsado($idcomputador){
			$sql="UPDATE computador SET estado='0' WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}
                
                public function cambiarCondicion($idcomputador,$condicion){
			$sql="UPDATE computador SET condicion=$condicion WHERE idcomputador='$idcomputador'";
			return ejecutarConsulta($sql);
		}

	}
