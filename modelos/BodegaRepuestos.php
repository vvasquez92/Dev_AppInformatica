<?php 

require "../config/conexion.php";

	Class bodRepuesto{
		//Constructor para instancias
		public function __construct(){

		}

		public function listar(){
			$sql="SELECT DISTINCT c.descripcion categoria, 
                                r.nombre, 
                                m.descripcion marca, 
                                r.modelo, 
                                SUM(fr.cantidad) - (SELECT IFNULL(SUM(cantidad),0) FROM servicio_repuestos WHERE id_repuesto = fr.id_repuesto) cantidad, 
                                r.id_repuesto
                    FROM repuesto r                    
                    INNER JOIN categoria_rep c ON r.id_categoria = c.id_categoria
                    INNER JOIN marca_rep m ON r.id_marca = m.id_marca
                    INNER JOIN factura_rep fr ON r.id_repuesto = fr.id_repuesto
                    GROUP BY c.descripcion, r.nombre, m.descripcion, r.modelo, r.id_repuesto
                    ORDER BY 2 ASC ";
			return ejecutarConsulta($sql);
        }

        public function cargaMarcasRep(){        
            $sql="SELECT id_marca, descripcion
                    FROM marca_rep
                    WHERE activo = 1
                    ORDER BY descripcion ASC";
            return ejecutarConsulta($sql);   
                
        }

        public function cargaCategoriasRep(){        
            $sql="SELECT id_categoria, descripcion
                    FROM categoria_rep
                    WHERE activo = 1
                    ORDER BY descripcion ASC";
            return ejecutarConsulta($sql);   
                
        }

        public function cargaProveedoresRep(){        
            $sql="SELECT id_proveedor, nombre nombre, rut
                    FROM proveedor_rep
                    WHERE activo = 1
                    ORDER BY nombre ASC";
            return ejecutarConsulta($sql);   
                
        }

        function consultaMarca($marca){
            $sql = "SELECT id_marca FROM marca_rep WHERE descripcion = '$marca';";
            return Filas($sql);
        }

        function agregarMarca($marca) {  
            $sql = "INSERT INTO marca_rep (descripcion, activo)
                    VALUES(UPPER('$marca'), 1);";
            return ejecutarConsulta($sql);            
        }

        function consultaProveedor($rutProveedor, $nombreProveedor){
            $sql = "SELECT id_proveedor 
                    FROM proveedor_rep 
                    WHERE (rut = '$rutProveedor' OR nombre = '$nombreProveedor');";
            return Filas($sql);
        }

        function agregarProveedor($nombreProveedor,$rutProveedor,$direccionProveedor,$regionProveedor,
                                    $comunaProveedor,$contactoProveedor,$telefonoProveedor,$correoProveedor) {

            $sql = "INSERT INTO proveedor_rep (nombre, rut, direccion, region, comuna, contacto, telefono, correo, activo)
                    VALUES(UPPER('$nombreProveedor'),'$rutProveedor',UPPER('$direccionProveedor'), $regionProveedor, $comunaProveedor,
                            UPPER('$contactoProveedor'), '$telefonoProveedor',LOWER('$correoProveedor'), 1);";
            return ejecutarConsulta($sql);
            
        }

        function agregarRepuesto($cod_producto,$nro_serie,$marca_rep,$nombre_rep,$modelo_rep,$observaciones_rep,$categoria_rep,$proveedor_rep,$nro_factura,$archivoname,$precio_rep,$cantidad_rep,$ubicacion_rep) {

            $sql = "CALL INSERTA_REPUESTO ('$cod_producto','$nro_serie',$marca_rep,upper('$nombre_rep'),upper('$modelo_rep'),$cantidad_rep,$categoria_rep,upper('$ubicacion_rep'),upper('$observaciones_rep'),$proveedor_rep,$precio_rep,$nro_factura,'$archivoname')";
            //echo $sql;
            return ejecutarConsulta($sql);
            
        }

        function consultaRepuesto($idRpuesto){
            $sql="SELECT r.id_repuesto, r.cod_producto, r.nro_serie, fr.id_proveedor, r.id_marca, r.nombre, r.modelo, r.cantidad, fr.precio,
                            r.id_categoria, r.ubicacion, fr.factura_nro, fr.factura_file, r.descripcion, fr.id_factura_rep
                    FROM repuesto r
                    INNER JOIN factura_rep fr on r.id_repuesto = fr.id_repuesto
                    WHERE r.id_repuesto = $idRpuesto
                    ORDER BY fr.id_factura_rep DESC
                    LIMIT 1;";
			return ejecutarConsulta($sql);
        }

        function editarRepuesto($idRpuesto,$id_factura_rep,$cod_producto,$nro_serie,$marca_rep,$nombre_rep,$modelo_rep,$observaciones_rep,$categoria_rep,
                                $proveedor_rep,$nro_factura,$archivoname,$precio_rep,$cantidad_rep,$ubicacion_rep) {
            $sql = "CALL edita_repuesto('$cod_producto','$nro_serie',$marca_rep, upper('$nombre_rep'), upper('$modelo_rep'), $cantidad_rep, $categoria_rep, 
                    upper('$ubicacion_rep'), upper('$observaciones_rep'), $proveedor_rep, $precio_rep, $nro_factura, '$archivoname', $idRpuesto, $id_factura_rep);";            
            
            return ejecutarConsulta($sql);
            
        }

        function sumaStockRepuesto($idRpuesto,$nro_serie,$cantidad_rep,$precio_rep,$proveedor_rep,$nro_factura,$archivoname, $ubicacion_rep) {
            $sql = "CALL suma_repuesto($idRpuesto,'$nro_serie',$cantidad_rep,$precio_rep,$proveedor_rep,$nro_factura, '$archivoname', upper('$ubicacion_rep'));";            
            //echo $sql;
            return ejecutarConsulta($sql);
            
        }

        public function detalle($idRpuesto){
			$sql="SELECT 'ENTRADA' tipo, fr.fh_ingreso, fr.cantidad, fr.factura_nro, fr.factura_file archivo, 'N/A' nro_servicio 
                    FROM factura_rep fr
                    WHERE fr.id_repuesto = $idRpuesto
                    UNION ALL
                    SELECT 'SALIDA' tipo, sr.fh_movimiento fh_ingreso, sr.cantidad, 'N/A', CONCAT(sr.id_servicio, '.pdf'), sr.id_servicio
                    FROM servicio_repuestos sr
                    INNER JOIN servicio_taller st ON sr.id_servicio = st.id_servicio
                    WHERE sr.id_repuesto = $idRpuesto
                    AND st.estado = 5
                    and sr.fh_movimiento is not null
                    UNION ALL
                    SELECT 'EN USO' tipo, sr.fh_movimiento fh_ingreso, sr.cantidad, 'N/A', CONCAT(sr.id_servicio, '.pdf'), sr.id_servicio
                    FROM servicio_repuestos sr
                    INNER JOIN servicio_taller st ON sr.id_servicio = st.id_servicio
                    WHERE sr.id_repuesto = $idRpuesto
                    AND st.estado <> 5
                    and sr.fh_movimiento is not null";
			return ejecutarConsulta($sql);
        }

        function validaCodigo($cod_producto){
            $sql = "SELECT cod_producto 
                    FROM repuesto
                    WHERE cod_producto = '$cod_producto';";
            return Filas($sql);
        }

        function consultaNroSerie($idRpuesto, $id_factura_rep){
            $sql="SELECT id_factura, id_repuesto, nro_serie
                    FROM repuestos_numeros
                    WHERE id_repuesto = $idRpuesto
                    AND id_factura = $id_factura_rep
                    ORDER BY 3 ASC;";
            
			return ejecutarConsulta($sql);
        }

        public function listarFacturas($idRpuesto){
			$sql="SELECT id_factura_rep, factura_nro
                    FROM factura_rep                    
                    WHERE id_repuesto = $idRpuesto; ";
			return ejecutarConsulta($sql);
        }

        function consultaRepuestoxFactura($idRpuesto, $id_factura_rep){
            $sql="SELECT r.id_repuesto, r.cod_producto, r.nro_serie, fr.id_proveedor, r.id_marca, r.nombre, r.modelo, fr.cantidad, fr.precio,
                            r.id_categoria, r.ubicacion, fr.factura_nro, fr.factura_file, r.descripcion, fr.id_factura_rep
                    FROM repuesto r
                    INNER JOIN factura_rep fr on r.id_repuesto = fr.id_repuesto
                    WHERE r.id_repuesto = $idRpuesto
                    AND fr.id_factura_rep = $id_factura_rep
                    ORDER BY fr.id_factura_rep DESC
                    LIMIT 1;";
			return ejecutarConsulta($sql);
        }

        function validaNroSerie($nro_serie, $idRpuesto){
            $sql = "SELECT nro_serie 
                    FROM repuestos_numeros
                    WHERE id_repuesto = $idRpuesto
                    AND nro_serie = $nro_serie;";
            return Filas($sql);
        }

	}
?>