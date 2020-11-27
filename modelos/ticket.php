<?php 

require "../config/conexion.php";

	Class Ticket{
		//Constructor para instancias
		public function __construct(){

		}
       
		public function listar($usuario){
            $sql="SELECT t.id_ticket, t.fh_ticket, tt.desc_tipoticket, e.nombre, e.apellido, t.respuesta_ticket, "
                ."(case when t.estado_ticket = 1 then 'Pendiente' "
                ." when t.estado_ticket = 2 then 'Finalizado' end) estado_ticket, ttc.desc_tipoticket_cabecera "    
                ."from tickets t "
                ."inner join tipo_ticket tt on t.tipo_ticket = tt.idtipo_ticket "
                ."inner join tipo_ticket_cabecera ttc on tt.id_cabecera = ttc.id_tipoticket_cabecera "
                ."inner join empleado e on t.empleado_ticket = e.idempleado "
                ."WHERE t.usuario_creacion_ticket = '$usuario'";
            
			return ejecutarConsulta($sql);
        }    
        
        public function listar_todo(){
            $sql="SELECT t.id_ticket, t.fh_ticket, tt.desc_tipoticket, e.nombre, e.apellido, t.respuesta_ticket, "
                ."(case when t.estado_ticket = 1 then 'Pendiente' "
                ." when t.estado_ticket = 2 then 'Finalizado' end) estado_ticket, ttc.desc_tipoticket_cabecera, tt.id_cabecera "
                ."from tickets t "
                ."inner join tipo_ticket tt on t.tipo_ticket = tt.idtipo_ticket "
                ."inner join empleado e on t.empleado_ticket = e.idempleado "
                ."inner join tipo_ticket_cabecera ttc on tt.id_cabecera = ttc.id_tipoticket_cabecera "
                ."where t.estado_ticket = 1";
            
			return ejecutarConsulta($sql);
        } 
        
        public function listar_todohistorico(){
            $sql="SELECT t.id_ticket, t.fh_ticket, tt.desc_tipoticket, e.nombre, e.apellido, t.respuesta_ticket, "
                ."(case when t.estado_ticket = 1 then 'Pendiente' "
                ." when t.estado_ticket = 2 then 'Finalizado' end) estado_ticket, ttc.desc_tipoticket_cabecera "
                ."from tickets t "
                ."inner join tipo_ticket tt on t.tipo_ticket = tt.idtipo_ticket "
                ."inner join empleado e on t.empleado_ticket = e.idempleado "
                ."inner join tipo_ticket_cabecera ttc on tt.id_cabecera = ttc.id_tipoticket_cabecera "
                ."where t.estado_ticket = 2";
            
			return ejecutarConsulta($sql);
		}
                
		public function insertar($idtipo,$idempleado,$observacion,$usuario){
			$sql="INSERT INTO tickets (fh_ticket, tipo_ticket, empleado_ticket, comentario_ticket, estado_ticket, usuario_creacion_ticket) 
                    VALUES (sysdate(), '$idtipo', '$idempleado', '$observacion',1,$usuario)";
			return ejecutarConsulta($sql);
		}
                
		public function editar($idticket,$idtipo,$idempleado,$observacion){
			$sql="UPDATE tickets ";
			return ejecutarConsulta($sql);
        }
        
        public function responder($idticket,$respuesta,$usuario){
            $sql="UPDATE tickets SET respuesta_ticket='$respuesta', estado_ticket = 2, usuario_respuesta_ticket='$usuario', fh_respuesta_ticket = sysdate() WHERE id_ticket=$idticket";
            
			return ejecutarConsulta($sql);
		}
                
		public function mostrar($idticket){
			$sql="SELECT * FROM tickets t INNER JOIN tipo_ticket tt on t.tipo_ticket = tt.idtipo_ticket WHERE t.id_ticket='$idticket'";
			return ejecutarConsultaSimpleFila($sql);
        }
        
        public function tipo_solicitud($idtipoticket){
            $sql="SELECT idtipo_ticket, desc_tipoticket FROM tipo_ticket WHERE condicion=1 and (id_cabecera = '$idtipoticket' or '$idtipoticket' = 0) ORDER BY 2 ASC"; 
			return ejecutarConsulta($sql);
        }

        public function tipo_ticket(){
            $sql="SELECT id_tipoticket_cabecera, desc_tipoticket_cabecera FROM tipo_ticket_cabecera WHERE condicion=1 ORDER BY 2 ASC"; 
			return ejecutarConsulta($sql);
        }

	}
?>