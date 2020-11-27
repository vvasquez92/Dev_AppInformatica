<?php 

require "../config/conexion.php";

	Class AsigComputador{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idcomputador, $idempleado, $nomequipo, $usuario, $pass, $ip, $iduser){
			$sql="INSERT INTO asigcompu (idcomputador, idempleado, nomequipo, usuario, pass, ip, created_user) VALUES ('$idcomputador', '$idempleado', '$nomequipo', '$usuario', '$pass', '$ip', '$iduser')";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idasigcompu){
			$sql="UPDATE asigcompu SET condicion='0' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idasigcompu){
			$sql="SELECT * FROM asigcompu WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                
                public function checkacta($idasigcompu){
			$sql="UPDATE asigcompu SET acta='1' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}
                
                public function checkdevolucion($idasigcompu){
			$sql="UPDATE asigcompu SET devolucion='1' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}
               
                
                public function generaracta($idasigcompu){
			$sql="UPDATE asigcompu SET acta='0' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}
                
                public function generardevolucion($idasigcompu){
			$sql="UPDATE asigcompu SET devolucion='0' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT a.idasigcompu, a.idcomputador, e.num_documento, e.nombre, e.apellido, t.nombre AS tipo, m.nombre AS marca, c.modelo, DATE(a.created_time) AS fecha, a.usuario, a.pass, a.condicion, a.devolucion, a.acta,a.ip FROM asigcompu a INNER JOIN computador c ON a.idcomputador=c.idcomputador INNER JOIN marcacom m ON c.idmarca = m.idmarcacom INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador INNER JOIN empleado e ON a.idempleado = e.idempleado WHERE a.condicion = 1";
			return ejecutarConsulta($sql);
		}
		
                public function actapdf($idasigcompu){
			$sql="SELECT a.idasigcompu, DAY(a.created_time) AS dia, MONTH(a.created_time) AS mes, YEAR(a.created_time) AS año, e.nombre, e.apellido, e.num_documento, a.acta, a.devolucion, t.nombre AS tipo, m.nombre AS marca, e.direccion, x.nombre AS cargo, w.comuna_nombre AS comuna,c.estado, x.nombre as cargo, t.nombre AS tipo, m.nombre AS marca, c.modelo, c.serial, a.usuario, a.pass, a.nomequipo, a.ip, c.precio, c.observaciones FROM asigcompu a INNER JOIN computador c ON a.idcomputador=c.idcomputador INNER JOIN marcacom m ON c.idmarca=m.idmarcacom INNER JOIN tcomputador t ON c.tcomputador=t.idtcomputador INNER JOIN empleado e ON a.idempleado = e.idempleado INNER JOIN cargos x ON e.idcargo = x.idcargos INNER JOIN comunas w ON e.idcomunas = w.comuna_id WHERE a.idasigcompu='$idasigcompu'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function devpdf($idasigcompu){
			$sql="SELECT a.*, e.nombre, e.apellido, e.num_documento, e.direccion, c.nombre AS cargo, x.comuna_nombre AS comuna, DAY(sysdate()) AS dia, MONTH(sysdate()) AS mes, YEAR(sysdate()) AS año, t.nombre as tipo, m.nombre as marca, co.modelo, co.serial,co.observaciones, co.precio  FROM asigcompu a INNER JOIN computador co ON a.idcomputador=co.idcomputador INNER JOIN marcacom m ON co.idmarca = m.idmarcacom INNER JOIN tcomputador t ON co.tcomputador = t.idtcomputador INNER JOIN empleado e ON a.idempleado = e.idempleado INNER JOIN cargos c ON e.idcargo = c.idcargos INNER JOIN comunas x ON e.idcomunas = x.comuna_id WHERE a.idasigcompu = '$idasigcompu';";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function CantidadAsignaciones($idempleado) {
                    $sql="SELECT `idasigcompu` FROM `asigcompu` WHERE idempleado=$idempleado AND condicion=1";
                    return Filas($sql);
                }
                
                public function mostrarAsignaciones($idempleado) {
                    $sql="SELECT a.idasigcompu, a.idcomputador, t.nombre AS tipo, m.nombre AS marca,
                         c.modelo,a.usuario,a.pass,a.ip,c.modelo,a.created_time,
                         ( CASE 
                          WHEN a.condicion = 0 THEN 'DEVOLUCION NORMAL'
                          WHEN a.condicion = 1 THEN 'COMPUTADOR ASIGNADO'
                          WHEN a.condicion = 2 THEN 'DEVOLUCION POR COMPUTADOR DAÑADO'
                          WHEN a.condicion = 3 THEN 'DEVOLUCION POR COMPUTADOR ROBADO' 
                          END ) 
                          as condicion_asignacion
                         FROM asigcompu a 
                         INNER JOIN computador c ON a.idcomputador=c.idcomputador 
                         INNER JOIN marcacom m ON c.idmarca = m.idmarcacom
                         INNER JOIN tcomputador t ON c.tcomputador = t.idtcomputador  
                         WHERE a.idempleado=$idempleado
                         ORDER BY a.created_time DESC";
                    return ejecutarConsulta($sql);
                }
                
                public function guardarMotivoDevolucion($idasigcompu,$detalle){
			$sql="UPDATE asigcompu SET detalle='$detalle' WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}
                
                public function cambiarCondicion($idasigcompu,$condicion){
			$sql="UPDATE asigcompu SET condicion=$condicion WHERE idasigcompu='$idasigcompu'";
			return ejecutarConsulta($sql);
		}
                
                public function validarIpRegistrada($idasigcompu,$ip) {
                    
                    $sql="SELECT count(1) as cantidad 
                          FROM (                        
                            SELECT a.idasigcompu AS id FROM `asigcompu`  as a
                            WHERE a.condicion=1
                            AND  a.ip='$ip'";
                    
                    if($idasigcompu != ""){
                        $sql.=" AND a.idasigcompu <> $idasigcompu";
                    }
                                
                    $sql.=" UNION
                            
                            SELECT b.iddispositivo AS id 
                            FROM dispositivo as b
                            WHERE b.ip='$ip'
                            AND b.condicion=1
                            ) as c";

                    return ejecutarConsultaSimpleFila($sql);
                }
                
       
	}
