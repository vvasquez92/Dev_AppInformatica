<?php 

require "../config/conexion.php";

	Class ServiciosTaller{
		//Constructor para instancias
		public function __construct(){

		}

		public function listaServicios($estado) {
            $sql = "SELECT st.id_servicio,tst.tipo_servicio, st.motivo, st.estado, st.kms_actual, 
                            DATE_FORMAT(st.fh_solicitud,'%d-%m-%Y') fh_solicitud,
                        (CASE WHEN st.estado = 1 THEN 
                                '#314A65' 
                                WHEN st.estado = 2 THEN 
                                '#C10000'
                                WHEN st.estado = 3 THEN 
                                '#D0C700' 
                                WHEN st.estado = 4 THEN 
                                '#149100' 
                                WHEN st.estado = 5 THEN
                                '#989898'                           
                        END) color,
                        (CASE WHEN st.fh_inicio IS NULL THEN
                            CONCAT(DATE_FORMAT(st.fh_solicitud,'%Y-%m-%d'),' 08:00:00')
                            WHEN st.fh_inicio IS NOT NULL THEN
                            st.fh_inicio
                            END) fh_inicio,
                        (CASE WHEN st.fh_fin IS NULL THEN
                            CONCAT(DATE_FORMAT(st.fh_solicitud,'%Y-%m-%d'),' 17:30:00')
                            WHEN st.fh_fin IS NOT NULL THEN
                            st.fh_fin
                            END) fh_fin,
                        DATE_FORMAT(st.fh_inicio,'%Y-%m-%dT%H:%i') fini,
                        DATE_FORMAT(st.fh_fin,'%Y-%m-%dT%H:%i') ffin,
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
                        v.patente,
                        CONCAT(mv.nombre, ' ', mov.nombre, ' - ', v.patente) datos_veh,
                        (CASE WHEN tst.tipo = 'M' THEN
                            'MANTENCION'
                            WHEN tst.tipo = 'R' THEN
                            'REPARACION'
                            WHEN tst.tipo = 'I' THEN
                            'INSPECCION'
                        END) tipo_servicio_desc
                    FROM servicio_taller st
                    INNER JOIN tipo_servicio_taller tst ON st.id_tiposervicio = tst.id_tiposervicio
                    LEFT JOIN user u ON st.id_mecanico = u.iduser
                    LEFT JOIN empleado e ON u.num_documento = e.num_documento
                    INNER JOIN vehiculo v ON st.id_vehiculo = v.idvehiculo
                    INNER JOIN marcave mv ON v.idmarca = mv.idmarca
                    INNER JOIN modelove mov ON v.idmodelo = mov.idmodelove
                    WHERE st.estado IN ($estado)";
            return ejecutarConsulta($sql);
            
        }

        public function tomaServicio($id_usuario,$id_servicio,$inicio,$fin){
            $sql="UPDATE servicio_taller
                    SET fh_inicio = '$inicio',
                        fh_fin = '$fin',
                        estado = 2,
                        id_mecanico = $id_usuario
                    WHERE id_servicio = $id_servicio;";
            //echo $sql;
			return ejecutarConsulta($sql);
        }

        public function cargaRepXCat(){        
            $sql="SELECT r.id_repuesto, r.nombre, r.modelo, mr.descripcion marca, rn.nro_serie, rn.idRepuestoNumero
                    FROM repuesto r
                    INNER JOIN marca_rep mr ON r.id_marca = mr.id_marca
                    INNER JOIN repuestos_numeros rn ON r.id_repuesto = rn.id_repuesto
                    WHERE rn.disponible = 1
                    ORDER BY 2, 4, 3, 5 ASC;";                    
            return ejecutarConsulta($sql);   
                
        }

        function cantidadMaxRep($idRep){
            $sql = "SELECT cantidad
                    FROM repuesto
                    where id_repuesto = $idRep;";
            return ejecutarConsulta($sql); 
            
        }

        public function cargaRepuestosServicio($id_servicio){
			$sql="SELECT CONCAT(mr.descripcion, ' - ', r.nombre, ' ', r.modelo) repuesto,
                            cr.descripcion categoria,
                            SUM(sr.cantidad) cantidad,
                            sr.id_repuesto
                    FROM repuesto r
                    INNER JOIN marca_rep mr ON r.id_marca = mr.id_marca
                    INNER JOIN categoria_rep cr ON r.id_categoria = cr.id_categoria
                    INNER JOIN servicio_repuestos sr ON sr.id_repuesto = r.id_repuesto
                    WHERE sr.id_servicio = $id_servicio
                    GROUP BY CONCAT(mr.descripcion, ' - ', r.nombre, ' ', r.modelo), cr.descripcion, sr.id_repuesto;";
			return ejecutarConsulta($sql);
        }

        public function agregaRepAServicio($id_servicio,$idRep,$cantidad,$id_usuario){
            $sql= "CALL agrega_repuesto_servicio($id_servicio, $idRep, $cantidad, $id_usuario);";
            return ejecutarConsulta($sql);
        }

        public function finalizarServicio($id_servicio,$observaciones, $id_usuario){
            $sql= "CALL finaliza_servicio ($id_servicio, upper('$observaciones'), $id_usuario)";
            return ejecutarConsulta($sql);
        }

        public function pdf($id_servicio){
            
            $sql = "SELECT CONCAT(e.nombre, ' ', e.apellido) nombre_completo,
                            e.num_documento rut,
                            d.nombre departamento,
                            c.nombre cargo,
                            e.movil telefono,
                            IFNULL(e.email_corporativo,e.email) correo,
                            mv.nombre marca_vehiculo,
                            v.patente,
                            mov.nombre modelo_vehiculo,
                            st.kms_actual,
                            v.serialmotor,
                            v.ano,
                            UPPER(st.observaciones) observaciones,
                            DAY(st.fh_fin) dia,
                            MONTH (st.fh_fin) mes,
                            YEAR(st.fh_fin) anio,
                            mec.num_documento rut_mecanico,
                            CONCAT((CASE WHEN INSTR(mec.nombre,  ' ') = 0 THEN
                                            mec.nombre
										ELSE
                                            LEFT(mec.nombre, INSTR(mec.nombre,  ' ') -1 )
										END) 
                                    , ' ', 
                                    (CASE WHEN INSTR( mec.apellido,  ' ') = 0 THEN
											mec.apellido
										ELSE
											LEFT(mec.apellido,INSTR( mec.apellido,  ' ') -1 )
										END)) mecanico,
                            UPPER(st.nombre_recibe) nombre_recibe,
                            st.rut_recibe,
                            st.correo_recibe,
                            st.firma_empleado,
                            uf.firma firma_mecanico
                    FROM servicio_taller st
                    INNER JOIN empleado e ON st.id_empleado = e.idempleado
                    INNER JOIN user mec ON st.id_mecanico = mec.iduser
                    LEFT JOIN user_firma uf ON mec.iduser = uf.iduser
                    INNER JOIN oficina_departamento od ON e.idoficina_departamento = od.idoficina_departamento
                    INNER JOIN departamento d ON od.iddepartamento = d.iddepartamento
                    INNER JOIN cargos c ON e.idcargo = c.idcargos
                    INNER JOIN vehiculo v ON st.id_vehiculo = v.idvehiculo
                    INNER JOIN marcave mv ON v.idmarca = mv.idmarca
                    INNER JOIN modelove mov ON v.idmodelo =mov.idmodelove                    
                    WHERE st.id_servicio = $id_servicio;";
            
            return ejecutarConsultaSimpleFila($sql);
        }

        public function cargaRepuestosServicioPDF($id_servicio){
			$sql="SELECT mr.descripcion marca, 
                            r.nombre, 
                            r.modelo,
                            SUM(sr.cantidad) cantidad
                    FROM repuesto r
                    INNER JOIN marca_rep mr ON r.id_marca = mr.id_marca
                    INNER JOIN servicio_repuestos sr ON sr.id_repuesto = r.id_repuesto
                    WHERE sr.id_servicio = $id_servicio
                    GROUP BY mr.descripcion, r.nombre, r.modelo;";
            return ejecutarConsulta($sql);
        }

        public function iniciaServicio($id_servicio, $id_usuario){
            $sql="CALL inicia_servicio ($id_servicio, $id_usuario)";
			return ejecutarConsulta($sql);
        }

        public function quitarRep($id_servicio,$idRep, $id_usuario){
            $sql= "CALL quita_repuesto_servicio($id_servicio, $idRep, $id_usuario);";
            return ejecutarConsulta($sql);
        }

        public function reprogramaServicio($id_servicio, $inicio, $fin, $motivoReprog){
            $sql= "CALL reprograma_servicio($id_servicio, '$inicio', '$fin', upper('$motivoReprog'));";            
            return ejecutarConsulta($sql);
        }

        public function recibeServicio($id_servicio,$imgjpg,$rutRecibe, $nombreRecibe, $correoRecibe, $id_usuario){
            $sql="CALL recibe_servicio ($id_servicio, '$imgjpg', '$rutRecibe',upper('$nombreRecibe'),lower('$correoRecibe'), $id_usuario)";
			return ejecutarConsulta($sql);
        }

        public function UpFile($archivo, $id_servicio){
            $sql="UPDATE servicio_taller 
                    SET file='$archivo'
                    WHERE id_servicio = $id_servicio;";
            return ejecutarConsulta($sql);
        }

        public function email($id_servicio){
            $sql="SELECT file, correo_recibe
                    FROM servicio_taller
                    WHERE id_servicio = $id_servicio;";
            return ejecutarConsulta($sql);
        }

	}
?>