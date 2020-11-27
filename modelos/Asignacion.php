<?php 

require "../config/conexion.php";

	Class Asignacion{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($fecha,$idequipo,$idempleado, $idchip, $tasignacion, $iduser){
			$sql="INSERT INTO asignacion (fecha, idequipo, idempleado, idchip, tasignacion, created_user, condicion) VALUES ('$fecha', '$idequipo', '$idempleado','$idchip','$tasignacion','$iduser',1)";
			return ejecutarConsulta($sql);
		}

		public function editar($idasignacion,$fecha,$idequipo,$idempleado, $idchip, $iduser){
			$sql="UPDATE asignacion SET fecha='$fecha', idequipo='$idequipo', idempleado='$idempleado', idchip='$idchip', updated_user='$iduser', updated_time=CURRENT_TIMESTAMP WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idasignacion){
			$sql="UPDATE asignacion SET condicion='0' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}


		public function mostrar($idasignacion){
			$sql="SELECT * FROM asignacion WHERE idasignacion='$idasignacion'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function checkcontrato($idasignacion){
			$sql="UPDATE asignacion SET contrato='1' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function checkacta($idasignacion){
			$sql="UPDATE asignacion SET acta='1' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function checkdevolucion($idasignacion){
			$sql="UPDATE asignacion SET devolucion='1' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function generarcontrato($idasignacion){
			$sql="UPDATE asignacion SET contrato='0' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function generaracta($idasignacion){
			$sql="UPDATE asignacion SET acta='0' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function generardevolucion($idasignacion){
			$sql="UPDATE asignacion SET devolucion='0' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}

		public function listar(){
			$sql="SELECT a.idasignacion, a.created_time, d.marca, d.nombre as equipo, e.imei, c.numero, o.nombre as operador, w.nombre, w.apellido, w.num_documento, f.nombre as cargo, a.condicion, a.contrato, a.acta, a.devolucion, e.idequipo, c.idchip,IF(a.tasignacion=0,'NUEVA ASIGNACION',IF(a.tasignacion=1,'REPOSICION','')) as tasignacion FROM asignacion a INNER JOIN equipo e ON a.idequipo = e.idequipo INNER JOIN detalle d ON e.iddetalle=d.iddetalle INNER JOIN empleado w ON a.idempleado = w.idempleado INNER JOIN cargos f ON w.idcargo=f.idcargos INNER JOIN chip c ON a.idchip = c.idchip INNER JOIN operador o on c.idoperador= o.idoperador WHERE a.condicion = 1";
			return ejecutarConsulta($sql);
		}
		
                public function contratopdf($idasignacion){
			$sql="SELECT a.idasignacion, DAY(CURRENT_TIMESTAMP) AS dia, MONTH(CURRENT_TIMESTAMP) AS mes, YEAR(CURRENT_TIMESTAMP) AS año, d.marca, d.precio, d.color, d.tipo, d.nombre as equipo, e.imei, c.numero, o.nombre as operador, c.serial, w.nombre, w.apellido, w.tipo_documento, w.num_documento, w.direccion, p.comuna_nombre AS comuna, f.nombre as cargo, e.estado FROM asignacion a INNER JOIN equipo e ON a.idequipo = e.idequipo INNER JOIN detalle d ON e.iddetalle=d.iddetalle INNER JOIN empleado w ON a.idempleado = w.idempleado INNER JOIN cargos f ON w.idcargo=f.idcargos INNER JOIN chip c ON a.idchip = c.idchip INNER JOIN operador o on c.idoperador= o.idoperador INNER JOIN comunas p ON w.idcomunas = p.comuna_id WHERE a.idasignacion='$idasignacion'";
			return ejecutarConsultaSimpleFila($sql);
		}
                
                public function listartelf(){
			$sql="SELECT a.idasignacion, a.created_time, d.marca, d.nombre as equipo, e.imei, c.numero, o.nombre as operador, w.nombre, w.apellido, w.num_documento, f.nombre as cargo, a.condicion, a.contrato, a.acta, a.devolucion, e.idequipo, c.idchip FROM asignacion a INNER JOIN equipo e ON a.idequipo = e.idequipo INNER JOIN detalle d ON e.iddetalle=d.iddetalle INNER JOIN empleado w ON a.idempleado = w.idempleado INNER JOIN cargos f ON w.idcargo=f.idcargos INNER JOIN chip c ON a.idchip = c.idchip INNER JOIN operador o on c.idoperador= o.idoperador WHERE a.condicion = 1";
			return ejecutarConsulta($sql);
		}
                
                public function CantidadAsignaciones($idempleado) {
                    $sql="SELECT `idasignacion` FROM `asignacion` WHERE idempleado=$idempleado AND condicion=1";
                    return Filas($sql);
                }
                
                public function mostrarAsignaciones($idempleado) {
                    $sql="SELECT a.idasignacion, d.marca, d.nombre as equipo, c.numero,
                          e.imei,a.fecha as fecha_asignacion,
                         ( CASE 
                          WHEN a.condicion = 0 THEN 'DEVOLUCION NORMAL'
                          WHEN a.condicion = 1 THEN 'TELEFONO ASIGNADO'
                          WHEN a.condicion = 2 THEN 'DEVOLUCION POR TELEFONO DAÑADO'
                          WHEN a.condicion = 3 THEN 'DEVOLUCION POR TELEFONO ROBADO' 
                          END ) 
                          as condicion_asignacion
                          FROM asignacion a 
                          INNER JOIN equipo e ON a.idequipo = e.idequipo 
                          INNER JOIN detalle d ON e.iddetalle=d.iddetalle   
                          INNER JOIN chip c ON a.idchip = c.idchip 
                          WHERE a.idempleado=$idempleado
                          ORDER BY a.fecha DESC";
                    return ejecutarConsulta($sql);
                }
                
                public function guardarMotivoDevolucion($idasignacion,$detalle){
			$sql="UPDATE asignacion SET detalle='$detalle' WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}
                
                public function cambiarCondicion($idasignacion,$condicion){
			$sql="UPDATE asignacion SET condicion=$condicion WHERE idasignacion='$idasignacion'";
			return ejecutarConsulta($sql);
		}

		public function selectTipoMotivo(){
			$sql="SELECT * FROM tipo_devolucion WHERE condicion=1"; 
			return ejecutarConsulta($sql);
		}
        
	}
