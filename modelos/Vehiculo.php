<?php 

require "../config/conexion.php";

	Class Vehiculo{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idmarca, $idmodelo, $ano, $kilometraje, $patente, $serialmotor, $serialcarroceria,$gases,$tecnica,$circulacion, $tvehiculo, $observaciones, $estado, $tieneGPS, $instalaGPS, $sProvGpsNew){
			
            if($gases!=""){
                $valor_gases="'$gases',";
            }else{
                $valor_gases="NULL,";
            }
            if($tecnica!=""){
                $valor_tecnica = "'$tecnica',";
            }else{
                $valor_tecnica="NULL,";
            }
            if($circulacion!=""){
                $valor_circulacion="'$circulacion',";
            }else{
                $valor_circulacion="NULL,";
            }
                    
            //$sql="INSERT INTO vehiculo (idmarca, idmodelo, ano, kilometraje, patente, serialmotor, serialcarroceria,gases,tecnica,circulacion,tvehiculo, observaciones, condicion, estado, disponible) VALUES ('$idmarca', '$idmodelo', '$ano', '$kilometraje', '$patente', '$serialmotor', '$serialcarroceria', $valor_gases $valor_tecnica $valor_circulacion '$tvehiculo','$observaciones', 1, '$estado', 1)";
            $sql = "CALL inserta_vehiculo ($idmarca, $idmodelo, $ano, $kilometraje, upper('$patente'), '$serialmotor', '$serialcarroceria', $valor_gases 
                                            $valor_tecnica $valor_circulacion $tvehiculo, upper('$observaciones'), 1, $estado, 1, $tieneGPS, '$instalaGPS', $sProvGpsNew);";
            //echo $sql;
            return ejecutarConsulta($sql);
		}

		public function editar($idvehiculo, $idmarca, $idmodelo, $ano, $kilometraje, $patente, $serialmotor, $serialcarroceria,$gases,$tecnica,$circulacion, $tvehiculo, $observaciones, $estado){
                        
                        if($gases!=""){
                            $condicion_gases="gases='$gases',";
                        }else{
                            $condicion_gases="gases=NULL,";
                        }
                        if($tecnica!=""){
                            $condicion_tecnica="tecnica='$tecnica',";
                        }else{
                            $condicion_tecnica="tecnica=NULL,";
                        }
                        if($circulacion!=""){
                            $condicion_circulacion="circulacion='$circulacion',";
                        }else{
                            $condicion_circulacion="circulacion=NULL,";
                        }
                      
                        $sql="UPDATE vehiculo SET idmarca='$idmarca', idmodelo='$idmodelo', ano='$ano', kilometraje='$kilometraje', estado = $estado, patente='$patente', serialmotor='$serialmotor', serialcarroceria='$serialcarroceria', $condicion_gases $condicion_tecnica $condicion_circulacion tvehiculo='$tvehiculo', observaciones='$observaciones', updated_time=CURRENT_TIMESTAMP  WHERE idvehiculo='$idvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function desactivar($idvehiculo){
			$sql="UPDATE vehiculo SET condicion='0' WHERE idvehiculo='$idvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function activar($idvehiculo){
			$sql="UPDATE vehiculo SET condicion='1' WHERE idvehiculo='$idvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function asignar($idvehiculo, $kilometraje){
			$sql="UPDATE vehiculo SET disponible='0', kilometraje='$kilometraje' WHERE idvehiculo='$idvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function liberar($idvehiculo, $kilometraje){
			$sql="UPDATE vehiculo SET disponible='1', kilometraje='$kilometraje' WHERE idvehiculo='$idvehiculo'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idvehiculo){
			$sql="SELECT a.* ,
                        b.nombre as tipo_vehiculo,
                        c.nombre as nombre_marca,
                        d.nombre as nombre_modelo,
                        (CASE WHEN vg.id_veh_gps IS NULL THEN
                            0
                            ELSE
                            1
                            END) tiene_gps,
                        vg.fh_instalacion,
                        vg.id_proveedor
                FROM vehiculo as a 
                LEFT JOIN tvehiculo as b on b.idtvehiculo = a.tvehiculo
                LEFT JOIN marcave as c ON c.idmarca = a.idmarca
                LEFT JOIN modelove as d ON d.idmodelove = a.idmodelo
                LEFT JOIN vehiculo_gps vg ON a.idvehiculo = vg.id_vehiculo
                WHERE a.idvehiculo=$idvehiculo";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT v.idvehiculo, v.kilometraje, v.ano, v.patente, t.nombre AS tipo, 
                        m.nombre as modelo, n.nombre as marca, v.condicion, v.estado, 
                        v.disponible,v.gases,v.tecnica,v.circulacion, 
                        (CASE WHEN vg.id_vehiculo is null then
                        'NO'
                        ELSE
                        'SI'                       
                        END) gps,
                        (CASE WHEN gv.idtgestion = 1 THEN
                            'DEVOLUCION'
                            WHEN gv.idtgestion = 2 THEN
							'REPARACION'
                            WHEN gv.idtgestion = 3 THEN
							'SINIESTRO'
                            WHEN gv.idtgestion = 4 THEN
							'ROBO'
                            WHEN gv.idtgestion = 5 THEN
                            'MANTENCION'
                            WHEN gv.idtgestion = 6 THEN
                            'INSPECCION'
						END) taller
                FROM vehiculo v 
                INNER JOIN tvehiculo t ON v.tvehiculo = t.idtvehiculo 
                INNER JOIN modelove m ON v.idmodelo = m.idmodelove 
                INNER JOIN marcave n ON v.idmarca = n.idmarca 
                LEFT JOIN vehiculo_gps vg ON v.idvehiculo = vg.id_vehiculo
                LEFT JOIN proveedor_gps pg ON vg.id_proveedor = pg.id_proveedor
                LEFT JOIN gestion_ve gv ON v.idvehiculo = gv.idvehiculo AND gv.condicion = 1
                WHERE v.condicion = 1";
			return ejecutarConsulta($sql);
        }

		public function selectvehiculo(){
			$sql="SELECT v.idvehiculo, v.patente, t.nombre as tipo, m.nombre as modelo, n.nombre as marca,
                                IF(v.gases IS NOT NULL,1,0) as tiene_fecha_gases,
                                IF(v.gases IS NOT NULL,DATEDIFF(v.gases,now()),0) AS cantidad_dias_gases,
                                IF(v.tecnica IS NOT NULL,1,0) as tiene_fecha_tecnica,
                                IF(v.tecnica IS NOT NULL,DATEDIFF(v.tecnica,now()),0) AS cantidad_dias_tecnica,
                                IF(v.circulacion IS NOT NULL,1,0) as tiene_fecha_circulacion,
                                IF(v.circulacion IS NOT NULL,DATEDIFF(v.circulacion,now()),0) AS cantidad_dias_circulacion
                                FROM vehiculo v
                                INNER JOIN tvehiculo t ON v.tvehiculo = t.idtvehiculo 
                                INNER JOIN modelove m ON v.idmodelo = m.idmodelove 
                                INNER JOIN marcave n ON v.idmarca = n.idmarca 
                                WHERE v.condicion=1 
                                AND v.disponible=1;"; 
			return ejecutarConsulta($sql);
		}
                
                public function actualizarPlazoDocumentosVehiculo($id_vehiculo,$doc_gases,$doc_tecnica,$doc_circulacion){
                    
                        if($doc_gases!=""){
                            $condicion_gases="gases='$doc_gases',";
                        }else{
                            $condicion_gases="gases=NULL,";
                        }
                        
                        if($doc_tecnica!=""){
                            $condicion_tecnica="tecnica='$doc_tecnica',";
                        }else{
                            $condicion_tecnica="tecnica=NULL,";
                        }
                        
                        if($doc_circulacion!=""){
                            $condicion_circulacion="circulacion='$doc_circulacion'";
                        }else{
                            $condicion_circulacion="circulacion=NULL";
                        }
                        
			$sql="UPDATE vehiculo SET $condicion_gases $condicion_tecnica $condicion_circulacion WHERE idvehiculo=$id_vehiculo"; 
			
                       	return ejecutarConsulta($sql);
		}

                public function listarVehiculosDocumentacionCompleta(){
			$sql="SELECT v.idvehiculo, v.kilometraje, v.ano, v.patente, t.nombre AS tipo, m.nombre as modelo, n.nombre as marca, v.condicion, v.estado, v.disponible,v.gases,v.tecnica,v.circulacion 
                                FROM vehiculo v 
                                INNER JOIN tvehiculo t ON v.tvehiculo = t.idtvehiculo 
                                INNER JOIN modelove m ON v.idmodelo = m.idmodelove 
                                INNER JOIN marcave n ON v.idmarca = n.idmarca
                                where gases is not null and tecnica is not null and circulacion is not null";
			return ejecutarConsulta($sql);
		}    
                
                public function actualizarDatosVehiculo($idvehiculo,$estado,$disponible,$gestion) {
                    $sql="UPDATE vehiculo SET estado=$estado,disponible=$disponible,gestion=$gestion where idvehiculo=".$idvehiculo;
                    return ejecutarConsulta($sql);
                }

                
                public function actualizarKilometraje($idvehiculo,$kilometraje){
                    $sql="UPDATE vehiculo SET kilometraje=$kilometraje where idvehiculo=".$idvehiculo;
                    return ejecutarConsulta($sql);
                }
                
                public function actualizarObservaciones($idvehiculo,$observaciones){
                    $sql="UPDATE vehiculo SET observaciones='$observaciones' where idvehiculo=".$idvehiculo;
                    return ejecutarConsulta($sql);
                }

                //! codigo nuevo
                public function actualizarDatosVehiculo_v2($idvehiculo,$estado,$disponible,$gestion,$tipoServ, $fhSolic, $comentServ, $idempleado, $kilometraje) {
                    $sql="CALL inserta_servicio($estado, $disponible, $gestion, $idvehiculo, $tipoServ, '$fhSolic', '$comentServ', $idempleado, $kilometraje);";
                    return ejecutarConsulta($sql);
                }

                public function cargaProveedorGPS(){        
                    $sql="SELECT id_proveedor, rut, nombre
                            FROM proveedor_gps
                            WHERE activo = 1
                            ORDER BY nombre ASC;";
                    return ejecutarConsulta($sql);   
                        
                }

                public function guardaDispositivoGPS($id_vehiculo,$fhInstalacionGPS,$sProvGps){
                    $sql="CALL agrega_vehiculo_gps ($id_vehiculo, '$fhInstalacionGPS', $sProvGps);";
			        return ejecutarConsulta($sql);
                }

                public function editarGPS($idvehiculo,$tieneGPS, $instalaGPS, $sProvGpsNew){                      
                    $sql="CALL edita_vehiculo_gps ($idvehiculo, $tieneGPS, '$instalaGPS',$sProvGpsNew)";
                    return ejecutarConsulta($sql);      
                }

                public function listarhist(){
                    $sql="SELECT v.idvehiculo, v.kilometraje, v.ano, v.patente, t.nombre AS tipo, 
                                m.nombre as modelo, n.nombre as marca, v.condicion, v.estado, 
                                v.disponible,v.gases,v.tecnica,v.circulacion, 
                                (CASE WHEN vg.id_vehiculo is null then
                                'NO'
                                ELSE
                                'SI'                       
                                END) gps
                        FROM vehiculo v 
                        INNER JOIN tvehiculo t ON v.tvehiculo = t.idtvehiculo 
                        INNER JOIN modelove m ON v.idmodelo = m.idmodelove 
                        INNER JOIN marcave n ON v.idmarca = n.idmarca 
                        LEFT JOIN vehiculo_gps vg ON v.idvehiculo = vg.id_vehiculo
                        LEFT JOIN proveedor_gps pg ON vg.id_proveedor = pg.id_proveedor
                        WHERE v.condicion = 0";
                    return ejecutarConsulta($sql);
                }

                public function inspeccion($id_vehiculo,$kilometraje,$motivo, $iduser){
                    $sql="CALL agrega_inspeccion ($id_vehiculo,$kilometraje,'$motivo', $iduser)";
                    return ejecutarConsulta($sql);  
                }

                public function CantidadPatentes($patente) {
                    $sql="SELECT patente 
                        FROM vehiculo 
                        WHERE patente='$patente';";
                    return Filas($sql);
                }

                public function CantidadPatentesEdit($idvehiculo, $patente) {
                    $sql="SELECT patente 
                        FROM vehiculo 
                        WHERE patente='$patente'
                        AND idvehiculo <> $idvehiculo;";
                    return Filas($sql);
                }

                public function listaDatosVehiculo($idvehiculo){
                    $sql = "SELECT mav.nombre marca, mov.nombre modelo, v.patente
                            FROM afabrimetalsa_informatica.vehiculo v
                            INNER JOIN marcave mav ON v.idmarca = mav.idmarca
                            INNER JOIN modelove mov ON v.idmodelo = mov.idmodelove
                            WHERE v.idvehiculo = $idvehiculo;";
                    return ejecutarConsulta($sql); 
                }

                public function listaDatosVehiculoPrestamo($idasig, $idprestamo){
                    $sql = "SELECT mav.nombre marca, mov.nombre modelo, v.patente, p.idempleado, date(p.created_time) created_time, date(p.fecha_compromiso) fecha_compromiso
                            FROM asigvehi av 
                            INNER JOIN vehiculo v ON av.idvehiculo = v.idvehiculo
                            INNER JOIN marcave mav ON v.idmarca = mav.idmarca
                            INNER JOIN modelove mov ON v.idmodelo = mov.idmodelove
                            INNER JOIN prestamo p ON av.idasigvehi = p.idasigvehi
                            WHERE av.idasigvehi = $idasig
                            AND p.idprestamo = $idprestamo;";
                    return ejecutarConsulta($sql); 
                }
                //! fin codigo nuevo


	}
?>