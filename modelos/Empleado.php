<?php 

require "../config/conexion.php";

	Class Empleado{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fecha_nac,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $idoficina_departamento){
			
                        if($vencimiento_carnet!=""){
                          $valor_vencimiento_carnet="'$vencimiento_carnet',";
                        }else{
                          $valor_vencimiento_carnet="NULL,";
                        }
                        
                        if($licencia!=""){
                          $valor_licencia="'$licencia',";
                        }else{
                          $valor_licencia="NULL,";
                        }
                        
                        $sql="INSERT INTO empleado (nombre, apellido, tipo_documento, num_documento,vencimiento_carnet,licencia,fecha_nac, direccion, movil, residencial, email, email_corporativo, imagen, condicion, create_user, idcargo, idcomunas, idregiones, idoficina_departamento) VALUES ('$nombre', '$apellido', '$tipo_documento', '$num_documento', $valor_vencimiento_carnet $valor_licencia '$fecha_nac','$direccion', '$movil', '$residencial', '$email','$email_corporativo', '$imagen',1, '$iduser', '$idcargo', '$idcomunas', '$idregiones', '$idoficina_departamento')";
			
                        return ejecutarConsulta($sql);
		}
                
		public function editar($idempleado,$nombre,$apellido,$tipo_documento,$num_documento,$vencimiento_carnet,$licencia,$fecha_nac,$direccion,$movil, $residencial,$email,$email_corporativo,$imagen, $iduser, $idcargo, $idcomunas, $idregiones, $idoficina_departamento){
                    
                        if($vencimiento_carnet!=""){
                            $condicion_vencimiento_carnet="vencimiento_carnet='$vencimiento_carnet',";
                        }else{
                            $condicion_vencimiento_carnet="vencimiento_carnet=NULL,";
                        }
                        
                        if($licencia!=""){
                            $condicion_licencia="licencia='$licencia',";
                        }else{
                            $condicion_licencia="licencia=NULL,";
                        }
                        
                    $sql="UPDATE empleado SET nombre='$nombre', apellido='$apellido', tipo_documento='$tipo_documento', num_documento='$num_documento',$condicion_vencimiento_carnet $condicion_licencia direccion='$direccion', movil='$movil', residencial='$residencial', email='$email', email_corporativo='$email_corporativo', fecha_nac='$fecha_nac', imagen='$imagen' , updated_user='$iduser', idcargo='$idcargo', idcomunas='$idcomunas', idregiones='$idregiones', idoficina_departamento='$idoficina_departamento', updated_time=CURRENT_TIMESTAMP WHERE idempleado='$idempleado'";
                    
                    return ejecutarConsulta($sql);
		}

		public function desactivar($idempleado){
			$sql="UPDATE empleado SET condicion='0' WHERE idempleado='$idempleado'";
			return ejecutarConsulta($sql);
		}

		public function activar($idempleado){
			$sql="UPDATE empleado SET condicion='1' WHERE idempleado='$idempleado'";
			return ejecutarConsulta($sql);
		}

		public function mostrar($idempleado){
			$sql="SELECT e.*, w.idoficinas, w.iddepartamento ,d.nombre as nombre_departamento, c.nombre as nombre_cargo FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE e.idempleado='$idempleado'";
			return ejecutarConsultaSimpleFila($sql);
		}

		public function listar(){
			$sql="SELECT e.idempleado, e.condicion, e.nombre, e.apellido, e.tipo_documento, e.num_documento,e.vencimiento_carnet,e.licencia, e.movil, e.email, e.email_corporativo, c.nombre AS cargo, d.nombre AS departamento, o.nombre AS oficinas FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE e.condicion = 1";
			return ejecutarConsulta($sql);
        }
        
        public function listarhistorico(){
			$sql="SELECT e.idempleado, e.condicion, e.nombre, e.apellido, e.tipo_documento, e.num_documento,e.vencimiento_carnet,e.licencia, e.movil, e.email, e.email_corporativo, c.nombre AS cargo, d.nombre AS departamento, o.nombre AS oficinas FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE e.condicion = 0";
			return ejecutarConsulta($sql);
		}

		public function listar_departamento($iddepartamento){
			$sql="SELECT e.nombre, e.apellido, e.tipo_documento, e.num_documento, e.movil, e.email, c.nombre AS cargo, o.nombre AS oficinas FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE w.iddepartamento = '$iddepartamento'";
			return ejecutarConsulta($sql);			
		}

		public function listar_oficina($idoficinas){
			$sql="SELECT e.nombre, e.apellido, e.tipo_documento, e.num_documento, e.movil, e.email, c.nombre AS cargo, d.nombre AS departamento FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE w.idoficinas = '$idoficinas'";
			return ejecutarConsulta($sql);
		}

		public function listar_cargo($idcargo){
			$sql="SELECT e.nombre, e.apellido, e.tipo_documento, e.num_documento, e.movil, e.email, d.nombre AS departamento, o.nombre AS oficinas FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE e.idcargo = '$idcargo'";
			return ejecutarConsulta($sql);
		}

		public function selectempleado(){
			$sql="SELECT idempleado, nombre, apellido,tipo_documento, num_documento , 
                                IF(licencia IS NOT NULL,1,0) as tiene_licencia,
                                IF(licencia IS NOT NULL,DATEDIFF(licencia,now()),0) AS cantidad_dias_licencia,
                                IF(vencimiento_carnet IS NOT NULL,1,0) as tiene_fecha_carnet,
                                IF(vencimiento_carnet IS NOT NULL,DATEDIFF(vencimiento_carnet,now()),0) AS cantidad_dias_carnet
                                FROM empleado order by nombre asc";
			return ejecutarConsulta($sql);
		}
                
                public function listartelefonos($nombres, $apellidos){
			$sql="SELECT e.nombre, e.apellido, e.email, c.numero FROM empleado e INNER JOIN asignacion a ON e.idempleado=a.idempleado INNER JOIN chip c ON a.idchip=c.idchip WHERE e.nombre LIKE '%".$nombres."%' AND e.apellido LIKE '%".$apellidos."%'";
			return ejecutarConsulta($sql);
		}
                
                
                public function cumpleanos(){
                    $sql = "SELECT e.idempleado, concat(e.nombre, ' ', e.apellido) as 'nombreemp', DATE_FORMAT(e.fecha_nac, '%d-%m-%Y') as fecha_nac, e.idcargo, c.nombre as 'cargo', e.idoficina_departamento, of.nombre as 'oficina' , "
                        . " concat(YEAR(curdate()), '-', MONTH(e.fecha_nac), '-',DAY(e.fecha_nac)) as 'fecha' "
                        . " FROM `empleado` e "
                        . " INNER JOIN cargos c on c.idcargos = e.idcargo "
                        . " INNER JOIN oficina_departamento o on o.idoficina_departamento = e.idoficina_departamento "
                        . " INNER JOIN oficinas of on of.idoficinas = o.idoficinas ";
                        //. " WHERE MONTH(e.fecha_nac) = MONTH(curdate())";
                //var_dump($sql);
                    return ejecutarConsulta($sql);
                }
                
                /*public function validarExisteNumDocumento($num_documento,$idempleado) {
                                        
                    $sql="SELECT count(1) as cantidad FROM `empleado` WHERE num_documento='$num_documento'";
                    
                    if($idempleado !=""){
                    $sql.=" AND idempleado <>".$idempleado;   
                    }
                    
                    return ejecutarConsultaSimpleFila($sql);
                }*/

                public function validarExisteNumDocumento($num_documento,$idempleado) {
                                        
                    $sql="SELECT condicion FROM `empleado` WHERE num_documento='$num_documento'";
                    
                    if($idempleado !=""){
                    $sql.=" AND idempleado <>".$idempleado;   
                    }
                    
                    return ejecutarConsultaSimpleFila($sql);
                }
                
                public function actualizarPlazoDocumentosEmpleado($id_empleado,$doc_vencimiento_carnet,$doc_licencia){
                    
                    if($doc_vencimiento_carnet!=""){
                        $condicion_vencimiento_carnet="vencimiento_carnet='$doc_vencimiento_carnet',";
                    }else{
                        $condicion_vencimiento_carnet="vencimiento_carnet=NULL,";
                    }

                    if($doc_licencia!=""){
                        $condicion_licencia="licencia='$doc_licencia'";
                    }else{
                        $condicion_licencia="licencia=NULL";
                    }

                    $sql="UPDATE empleado SET $condicion_vencimiento_carnet $condicion_licencia  WHERE idempleado=$id_empleado"; 

                    return ejecutarConsulta($sql);
		}
                                
		public function listarEmpleadosDocumentacionCompleta(){
                    
			$sql="SELECT e.idempleado, e.condicion, e.nombre, e.apellido, e.tipo_documento, e.num_documento,e.vencimiento_carnet,e.licencia, e.movil, e.email, c.nombre AS cargo, d.nombre AS departamento, o.nombre AS oficinas,e.vencimiento_carnet,e.licencia 
                              FROM empleado e 
                              INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento 
                              INNER JOIN oficinas o ON w.idoficinas = o.idoficinas 
                              INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento 
                              INNER JOIN cargos c ON e.idcargo = c.idcargos
                              WHERE e.vencimiento_carnet is not null AND e.licencia is not null";
                        
			return ejecutarConsulta($sql);
		}
                
                public function actualizarCorreoCorporativo($id_empl,$doc_correo_corporativo) {                    
                    $sql="UPDATE empleado SET email_corporativo='$doc_correo_corporativo' WHERE idempleado=$id_empl";   
                    return ejecutarConsulta($sql);
                }

                public function mostrarPorRut($rut){
                    $sql="SELECT e.*, w.idoficinas, w.iddepartamento ,d.nombre as nombre_departamento, c.nombre as nombre_cargo FROM empleado e INNER JOIN oficina_departamento w ON w.idoficina_departamento = e.idoficina_departamento INNER JOIN oficinas o ON w.idoficinas = o.idoficinas INNER JOIN departamento d ON w.iddepartamento = d.iddepartamento INNER JOIN cargos c ON e.idcargo = c.idcargos WHERE e.num_documento='$rut'";
                    return ejecutarConsultaSimpleFila($sql);
                }
                
	}
