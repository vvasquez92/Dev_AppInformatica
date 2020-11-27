<?php 

require "../config/conexion.php";

	Class Reporte{
		//Constructor para instancias
		public function __construct(){

		}

		public function cargaPatentes(){        
            $sql="SELECT distinct v.idvehiculo, v.patente
                    FROM servicio_taller st
                    INNER JOIN servicio_repuestos sr ON st.id_servicio = sr.id_servicio
                    INNER JOIN vehiculo v ON st.id_vehiculo = v.idvehiculo
                    WHERE st.estado in (4,5)
                    ORDER BY v.patente ASC;";
            return ejecutarConsulta($sql);   
                
        }

        public function selectano() {
            $sql = "SELECT DISTINCT YEAR(fh_fin) AS 'ano' FROM servicio_taller ORDER BY 1 asc";
            return ejecutarConsulta($sql);
        }

        public function listar($patente, $desde, $hasta) {
            $sql = "SELECT r.cod_producto, r.nombre, ma.descripcion marca, r.modelo, 
                            sr.cantidad, v.patente, st.id_servicio,
                            DATE_FORMAT(sr.fh_movimiento,'%d-%m-%Y %H:%i:%s') fh_mov
                        FROM repuesto r
                        INNER JOIN marca_rep ma ON r.id_marca = ma.id_marca
                        INNER JOIN servicio_repuestos sr ON r.id_repuesto = sr.id_repuesto
                        INNER JOIN servicio_taller st ON sr.id_servicio = st.id_servicio
                        INNER JOIN vehiculo v ON st.id_vehiculo = v.idvehiculo
                        WHERE (v.idvehiculo = $patente OR $patente = 0)
                        AND sr.fh_movimiento BETWEEN '$desde 00:00' AND '$hasta 23:59'
                        AND st.estado IN (4,5)";
            //echo $sql;
            return ejecutarConsulta($sql);
        }

        public function listaDatosServicio($idServicio) {
            $sql = "SELECT st.observaciones,
                            CONCAT((CASE WHEN INSTR(e.nombre,  ' ') = 0 THEN
											e.nombre
										ELSE
                                            LEFT(e.nombre, INSTR(e.nombre,  ' ') -1 )
										END) 
                                    , ' ', 
                                    (CASE WHEN INSTR( e.apellido,  ' ') = 0 THEN
											e.apellido
										ELSE
											LEFT(e.apellido,INSTR( e.apellido,  ' ') -1 )
										END)) mecanico,
                            CONCAT((CASE WHEN INSTR(ec.nombre,  ' ') = 0 THEN
											ec.nombre
										ELSE
                                            LEFT(ec.nombre, INSTR(ec.nombre,  ' ') -1 )
										END) 
                                    , ' ', 
                                    (CASE WHEN INSTR( ec.apellido,  ' ') = 0 THEN
											ec.apellido
										ELSE
											LEFT(ec.apellido,INSTR( ec.apellido,  ' ') -1 )
										END)) chofer
                    FROM servicio_taller st
                    INNER JOIN user u ON st.id_mecanico = u.iduser
                    INNER JOIN empleado e ON u.num_documento = e.num_documento
                    INNER JOIN empleado ec ON st.id_empleado = ec.idempleado
                    WHERE st.id_servicio = $idServicio;";
            return ejecutarConsulta($sql);
        }

	}
?>