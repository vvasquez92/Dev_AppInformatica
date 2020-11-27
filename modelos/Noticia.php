<?php 

require "../config/conexion.php";

	Class Noticia{

                //Constructor para instancias
		public function __construct(){

		}

		public function insertar($titulo,$contenido,$estado,$visible_portada,$iddepartamento,$update_time,$created_user,$updated_user,$condicion){
			$sql="INSERT INTO `noticia`(`titulo`, `contenido`, `estado`, `visible_portada`, `iddepartamento`, `update_time`, `created_user`, `updated_user`,condicion) VALUES ('$titulo','$contenido',$estado,$visible_portada,$iddepartamento,'$update_time','$created_user','$updated_user',$condicion)";
			return ejecutarConsulta_retornarID($sql);
		}

		public function editar($idnoticia,$titulo,$contenido,$iddepartamento,$update_time,$updated_user){
			$sql="UPDATE `noticia` SET `titulo`='$titulo',`contenido`='$contenido',`iddepartamento`=$iddepartamento,`update_time`='$update_time',`updated_user`=$updated_user WHERE idnoticia=".$idnoticia;
			return ejecutarConsulta($sql);
		}

                public function activar($idnoticia) {
                    $sql="UPDATE noticia SET estado = 1 WHERE idnoticia=".$idnoticia;
                    return ejecutarConsulta($sql);
                }
                
                public function desactivar($idnoticia) {
                    $sql="UPDATE noticia SET estado = 0 WHERE idnoticia=".$idnoticia;
                    return ejecutarConsulta($sql);
                }
                
                public function agregarHome($idnoticia) {
                    $sql="UPDATE noticia SET visible_portada = 1 WHERE idnoticia=".$idnoticia;
                    return ejecutarConsulta($sql);
                }
                
                public function quitarHome($idnoticia) {
                    $sql="UPDATE noticia SET visible_portada = 0 WHERE idnoticia=".$idnoticia;
                    return ejecutarConsulta($sql);
                }
                
                public function eliminar($idnoticia) {
                    $sql="UPDATE noticia SET condicion = 0 WHERE idnoticia=".$idnoticia;
                    return ejecutarConsulta($sql);
                }
                
		public function mostrar($idnoticia){
                    $sql="SELECT * FROM noticia  WHERE idnoticia='$idnoticia'";
                    return ejecutarConsultaSimpleFila($sql);
		}

		public function listar($iddepartamento,$estado,$visible_portada){
         
                    $sql="SELECT a.`idnoticia`, a.`titulo`, a.`contenido`, a.`estado`,a.visible_portada,a.iddepartamento, a.`created_time`, a.`update_time`, a.`created_user`, a.`updated_user`,b.nombre,b.apellido ,
                          c.nombre as nombre_departamento,IF(a.estado=1,'ACTIVADA','DESACTIVADA') as nombre_estado
                          FROM `noticia` as a
                          INNER JOIN user as b on a.created_user = b.iduser
                          INNER JOIN departamento AS c on a.iddepartamento =c.iddepartamento
                          WHERE a.condicion=1";

                    if($iddepartamento!=""){
                        $sql.=" AND a.iddepartamento=".$iddepartamento;
                    }
                    
                    if($estado!=""){
                        $sql.=" AND a.estado=".$estado;
                    }
                    
                    if($visible_portada!=""){
                        $sql.=" AND a.visible_portada=".$visible_portada;
                    }
                    
                    return ejecutarConsulta($sql);
		}
                
                
                public function contarNoticiasActivas($iddepartamento,$idnoticia) {
                    $sql="SELECT count(1) as cantidad "
                            . "FROM `noticia` "
                            . "WHERE iddepartamento=$iddepartamento "
                            . "AND estado=1";
                    if($idnoticia !=""){
                      $sql .= " AND idnoticia <>".$idnoticia;  
                    }
                    
                    return ejecutarConsultaSimpleFila($sql);
                }
                
                public function contarNoticiasHome($iddepartamento) {
                    $sql="SELECT count(1) as cantidad FROM `noticia` WHERE iddepartamento=$iddepartamento  AND visible_portada=1";
                    return ejecutarConsultaSimpleFila($sql);
                }

	}
?>