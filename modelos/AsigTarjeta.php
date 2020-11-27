<?php 

require "../config/conexion.php";

	Class AsigTarjeta{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idtarjeta,$idempleado, $iduser){
			$sql="INSERT INTO asigtarjeta (idtarjeta, idempleado, created_user) VALUES ('$idtarjeta', '$idempleado', '$iduser')";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idasigtarjeta){
			$sql="UPDATE asigtarjeta SET condicion='0' WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idasigtarjeta){
			$sql="SELECT * FROM asigtarjeta WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                
                public function checkacta($idasigtarjeta){
			$sql="UPDATE asigtarjeta SET acta='1' WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsulta($sql);
		}
                
                public function checkdevolucion($idasigtarjeta){
			$sql="UPDATE asigtarjeta SET devolucion='1' WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsulta($sql);
		}
               
                
                public function generaracta($idasigtarjeta){
			$sql="UPDATE asigtarjeta SET acta='0' WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsulta($sql);
		}
                
                public function generardevolucion($idasigtarjeta){
			$sql="UPDATE asigtarjeta SET devolucion='0' WHERE idasigtarjeta='$idasigtarjeta'";
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT a.idasigtarjeta, a.idtarjeta, t.codigo, t.codigosys, a.condicion, DATE(a.created_time) AS fecha, n.nombre AS nivel, e.nombre, e.apellido, e.num_documento, a.acta, a.devolucion FROM asigtarjeta a INNER JOIN tarjeta t ON a.idtarjeta=t.idtarjeta INNER JOIN nivel n ON t.idnivel = n.idnivel INNER JOIN empleado e ON a.idempleado = e.idempleado WHERE a.condicion = 1";
			return ejecutarConsulta($sql);
		}
		
                public function actapdf($idasigtarjeta){
			$sql="SELECT a.idasigtarjeta, DAY(a.created_time) AS dia, MONTH(a.created_time) AS mes, YEAR(a.created_time) AS año, e.nombre, e.apellido, e.num_documento, a.acta, a.devolucion, t.codigo, n.nombre AS nivel, e.direccion, c.nombre AS cargo, x.comuna_nombre AS comuna FROM asigtarjeta a INNER JOIN tarjeta t ON a.idtarjeta=t.idtarjeta INNER JOIN nivel n ON t.idnivel = n.idnivel INNER JOIN empleado e ON a.idempleado = e.idempleado INNER JOIN cargos c ON e.idcargo = c.idcargos INNER JOIN comunas x ON e.idcomunas = x.comuna_id WHERE a.idasigtarjeta = '$idasigtarjeta'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function devpdf($idasigtarjeta){
			$sql="SELECT a.idasigtarjeta, DATE(a.created_time) AS entrega, DAY(sysdate()) AS dia, MONTH(sysdate()) AS mes, YEAR(sysdate()) AS año, DATE(a.closed_time) AS devolucion, e.nombre, e.apellido, e.num_documento, a.acta, a.devolucion, t.codigo, n.nombre AS nivel, e.direccion, c.nombre AS cargo, x.comuna_nombre AS comuna FROM asigtarjeta a INNER JOIN tarjeta t ON a.idasigtarjeta=t.idtarjeta INNER JOIN nivel n ON t.idnivel = n.idnivel INNER JOIN empleado e ON a.idempleado = e.idempleado INNER JOIN cargos c ON e.idcargo = c.idcargos INNER JOIN comunas x ON e.idcomunas = x.comuna_id WHERE a.idasigtarjeta = '$idasigtarjeta'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                 public function CantidadAsignaciones($idempleado) {
                    $sql="SELECT `idasigtarjeta` FROM `asigtarjeta` WHERE idempleado=$idempleado and condicion=1";
                    return Filas($sql);
                }
                
                public function mostrarAsignaciones($idempleado) {
                    $sql="SELECT a.idasigtarjeta, a.idtarjeta,t.codigo, n.nombre AS nivel,
                        ( CASE 
                          WHEN a.condicion = 0 THEN 'TARJETA INHABILITADA'
                          WHEN a.condicion = 1 THEN 'TARJETA ASIGNADA'
                          END ) 
                          as condicion_asignacion, 
                         a.created_time   
                         FROM asigtarjeta a 
                         INNER JOIN tarjeta t ON a.idtarjeta=t.idtarjeta 
                         INNER JOIN nivel n ON t.idnivel = n.idnivel 
                         WHERE a.idempleado=$idempleado
                         ORDER BY a.created_time DESC";
                    return ejecutarConsulta($sql);
                }
        
	}
