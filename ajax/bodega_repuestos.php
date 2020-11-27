<?php 
session_start();

require_once "../modelos/BodegaRepuestos.php";

$bodRepuesto = new bodRepuesto();

$marca=isset($_POST["marca"])?limpiarCadena($_POST["marca"]):"";
$nombreProveedor=isset($_POST["nombreProveedor"])?limpiarCadena($_POST["nombreProveedor"]):"";
$rutProveedor=isset($_POST["rutProveedor"])?limpiarCadena($_POST["rutProveedor"]):"";
$direccionProveedor=isset($_POST["direccionProveedor"])?limpiarCadena($_POST["direccionProveedor"]):"";
$regionProveedor=isset($_POST["regionProveedor"])?limpiarCadena($_POST["regionProveedor"]):"";
$regionProveedor=isset($_POST["comunaProveedor"])?limpiarCadena($_POST["comunaProveedor"]):"";
$contactoProveedor=isset($_POST["contactoProveedor"])?limpiarCadena($_POST["contactoProveedor"]):"";
$telefonoProveedor=isset($_POST["telefonoProveedor"])?limpiarCadena($_POST["telefonoProveedor"]):"";
$correoProveedor=isset($_POST["correoProveedor"])?limpiarCadena($_POST["correoProveedor"]):"";

$cod_producto=isset($_POST["cod_producto"])?limpiarCadena($_POST["cod_producto"]):"";
$nro_serie=isset($_POST["nro_serie"])?limpiarCadena($_POST["nro_serie"]):"";
$marca_rep=isset($_POST["marca_rep"])?limpiarCadena($_POST["marca_rep"]):"";
$nombre_rep=isset($_POST["nombre_rep"])?limpiarCadena($_POST["nombre_rep"]):"";
$modelo_rep=isset($_POST["modelo_rep"])?limpiarCadena($_POST["modelo_rep"]):"";
$observaciones_rep=isset($_POST["observaciones_rep"])?limpiarCadena($_POST["observaciones_rep"]):"";
$categoria_rep=isset($_POST["categoria_rep"])?limpiarCadena($_POST["categoria_rep"]):"";
$proveedor_rep=isset($_POST["proveedor_rep"])?limpiarCadena($_POST["proveedor_rep"]):"";
$nro_factura=isset($_POST["nro_factura"])?limpiarCadena($_POST["nro_factura"]):"";
$file_factura=isset($_POST["file_factura"])?limpiarCadena($_POST["file_factura"]):"";
$precio_rep=isset($_POST["precio_rep"])?limpiarCadena($_POST["precio_rep"]):"";
$cantidad_rep=isset($_POST["cantidad_rep"])?limpiarCadena($_POST["cantidad_rep"]):"";
$ubicacion_rep=isset($_POST["ubicacion_rep"])?limpiarCadena($_POST["ubicacion_rep"]):"";

$idRpuesto=isset($_POST["idRpuesto"])?limpiarCadena($_POST["idRpuesto"]):"";
$id_factura_rep=isset($_POST["id_factura_rep"])?limpiarCadena($_POST["id_factura_rep"]):"";

switch ($_GET["op"]) {
	
	case 'listar':
		$rspta=$bodRepuesto->listar();
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $boton='';            

            $data[] = array(
                "0"=>
                '<button class="btn btn-warning btn-xs" data-toggle="tooltip" title="Editar" onclick="editar('.$reg->id_repuesto.')"><i class="fa fa-pencil"></i></button>'.
                '<button class="btn btn-success btn-xs"  data-toggle="tooltip" title="Agregar" onclick="agregar('.$reg->id_repuesto.')"><i class="fa fa-plus-circle"></i></button>'.
                '<button class="btn btn-primary btn-xs"  data-toggle="tooltip" title="Detalle" onclick="detalle('.$reg->id_repuesto.')"><i class="fa fa-list"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->marca,
                "4"=>$reg->modelo,
                "5"=>$reg->cantidad
            );
		}
		$results = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data
        );

		echo json_encode($results);
        connClose();
    break;

    case 'cargaMarcasRep':

        echo '<option value="" selected disabled>SELECCIONE MARCA</option>';
        $rspta = $bodRepuesto->cargaMarcasRep();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_marca.'>'.$reg->descripcion.'</option>';
        }
    break;

    case 'cargaCategoriasRep':

        echo '<option value="" selected disabled>SELECCIONE CATEGORÍA</option>';
        $rspta = $bodRepuesto->cargaCategoriasRep();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_categoria.'>'.$reg->descripcion.'</option>';
        }
    break;

    case 'cargaProveedoresRep':

        echo '<option value="" selected disabled>SELECCIONE PROVEEDOR</option>';
        $rspta = $bodRepuesto->cargaProveedoresRep();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_proveedor.'>'.$reg->nombre.'</option>';
        }
    break;

    case 'cargaProveedoresRep':

        echo '<option value="" selected disabled>SELECCIONE PROVEEDOR</option>';
        $rspta = $bodRepuesto->cargaProveedoresRep();        

        while($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->id_proveedor.'>'.$reg->nombre. ' / '. $reg->rut.'</option>';
        }
    break;

    case 'agregarMarca':

        $cant = $bodRepuesto->consultaMarca($marca);
        if ($cant < 1){
            $rspta = $bodRepuesto->agregarMarca($marca);
            echo $rspta;
        }
    break;

    case 'agregarProveedor':
        $cant = $bodRepuesto->consultaProveedor($rutProveedor, $nombreProveedor);
        if ($cant < 1){
            $rspta = $bodRepuesto->agregarProveedor($nombreProveedor,
                                                    $rutProveedor,
                                                    $direccionProveedor,
                                                    $regionProveedor,
                                                    $comunaProveedor,
                                                    $contactoProveedor,
                                                    $telefonoProveedor,
                                                    $correoProveedor);
            echo $rspta;
        }

    break;

    case 'agregarRepuesto':
        $archivoname = "";
        if (isset($_FILES['file_factura']['name'])) {
            if ($_FILES['file_factura']['name']) {
                $ext = explode(".", $_FILES['file_factura']['name']);
                if ($_FILES["file_factura"]["size"] <= 3000000) {
                    $link = "../files/taller";
                    $archivoname =  $proveedor_rep . "_" . $nro_factura . "." . end($ext);
                    if (!file_exists($link)) {
                        mkdir($link, 0777, true);
                    }
                    move_uploaded_file($_FILES['file_factura']['tmp_name'], $link . '/' . $archivoname);
                } else {
                    $rspta = "el tamaño del archivo no puede ser mayor a 3mb.";
                    echo $rspta;
                    break;
                }
            }
        } else {
            $archivoname = $_POST['file'];
        }
        
        $rspta = $bodRepuesto->agregarRepuesto($cod_producto,$nro_serie,$marca_rep,$nombre_rep,$modelo_rep,$observaciones_rep,$categoria_rep,$proveedor_rep,$nro_factura,$archivoname,$precio_rep,$cantidad_rep,$ubicacion_rep);
        echo $rspta;

    break;

    case 'consultaRepuesto':
        $rspta = $bodRepuesto->consultaRepuesto($idRpuesto);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "id_repuesto" => $reg->id_repuesto,
                "id_factura_rep" => $reg->id_factura_rep,
                "cod_producto" => $reg->cod_producto,
                "nro_serie" => $reg->nro_serie,
                "id_proveedor" => $reg->id_proveedor,
                "id_marca" => $reg->id_marca,
                "nombre" => $reg->nombre,
                "modelo" => $reg->modelo,
                "cantidad" => $reg->cantidad,
                "precio" => $reg->precio,
                "id_categoria" => $reg->id_categoria,
                "ubicacion" => $reg->ubicacion,
                "factura_nro" => $reg->factura_nro,
                "factura_file" => $reg->factura_file,
                "descripcion" => $reg->descripcion
            );
        };
        echo json_encode($data);
    break;

    case 'editarRepuesto':
        $archivoname = "no";
        if (isset($_FILES['file_factura']['name'])) {
            if ($_FILES['file_factura']['name']) {
                $ext = explode(".", $_FILES['file_factura']['name']);
                if ($_FILES["file_factura"]["size"] <= 3000000) {
                    $link = "../files/taller";
                    $archivoname =  $proveedor_rep . "_" . $nro_factura . "." . end($ext);
                    if (!file_exists($link)) {
                        mkdir($link, 0777, true);
                    }
                    move_uploaded_file($_FILES['file_factura']['tmp_name'], $link . '/' . $archivoname);
                } else {
                    $rspta = "el tamaño del archivo no puede ser mayor a 3mb.";
                    echo $rspta;
                    break;
                }
            }
        }
        
        $rspta = $bodRepuesto->editarRepuesto($idRpuesto,$id_factura_rep,$cod_producto,$nro_serie,$marca_rep,$nombre_rep,$modelo_rep,
                                            $observaciones_rep,$categoria_rep,$proveedor_rep,$nro_factura,$archivoname,$precio_rep,$cantidad_rep,$ubicacion_rep);
        echo $rspta;

    break;

    case 'sumaStockRepuesto':
        $archivoname = "no";
        if (isset($_FILES['file_factura']['name'])) {
            if ($_FILES['file_factura']['name']) {
                $ext = explode(".", $_FILES['file_factura']['name']);
                if ($_FILES["file_factura"]["size"] <= 3000000) {
                    $link = "../files/taller";
                    $archivoname =  $proveedor_rep . "_" . $nro_factura . "." . end($ext);
                    if (!file_exists($link)) {
                        mkdir($link, 0777, true);
                    }
                    move_uploaded_file($_FILES['file_factura']['tmp_name'], $link . '/' . $archivoname);
                } else {
                    $rspta = "el tamaño del archivo no puede ser mayor a 3mb.";
                    echo $rspta;
                    break;
                }
            }
        }
        
        $rspta = $bodRepuesto->sumaStockRepuesto($idRpuesto,$nro_serie, $cantidad_rep,$precio_rep,$proveedor_rep,$nro_factura,$archivoname, $ubicacion_rep);
        echo $rspta;

    break;
        
    case 'detalle':
		$rspta=$bodRepuesto->detalle($idRpuesto);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            
            if ($reg->tipo == 'ENTRADA' ){
                $boton_archivo='<button class="btn btn-secondary" onclick="window.open(\'../files/taller/'.$reg->archivo.'\'); return false;" data-tooltip="tooltip" title="Abrir Documento"><i class="fa fa-file"></i></button>';
            }
            else if ($reg->tipo == 'SALIDA' ){
                $boton_archivo='<button class="btn btn-secondary" onclick="window.open(\'../files/taller/ServicioTallerMecanico_'.$reg->archivo.'\')" data-tooltip="tooltip" title="Abrir Documento"><i class="fa fa-file"></i></button>';
            }
            else{
                $boton_archivo='';
            }

            $data[] = array(
                "0"=>$boton_archivo,
                "1"=>$reg->tipo,
                "2"=>$reg->fh_ingreso,
                "3"=>$reg->cantidad,
                "4"=>$reg->factura_nro,
                "5"=>$reg->nro_servicio
            );
		}
		$results = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data
        );

		echo json_encode($results);
        connClose();
    break;  
    
    case 'validaCodigo':
        $cant = $bodRepuesto->validaCodigo($cod_producto);
        echo $cant;
    break;

    case 'consultaNroSerie':
        $rspta = $bodRepuesto->consultaNroSerie($idRpuesto, $id_factura_rep);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(                
                "nro_serie" => $reg->nro_serie
            );
        };
        echo json_encode($data);
    break;

    case 'listarFacturas':
		$rspta=$bodRepuesto->listarFacturas($idRpuesto);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
            $boton='';            

            $data[] = array(
                "0"=>
                '<button class="btn btn-primary btn-xs" data-toggle="tooltip" title="Seleccionar" onclick="seleccionarFact('.$reg->id_factura_rep.'); return false;"><i class="fa fa-check-circle"></i></button>',                
                "1"=>$reg->factura_nro
            );
		}
		$results = array(
            "sEcho"=>1,
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data
        );

		echo json_encode($results);
        connClose();
    break;

    case 'consultaRepuestoxFactura':
        $rspta = $bodRepuesto->consultaRepuestoxFactura($idRpuesto, $id_factura_rep);
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "id_repuesto" => $reg->id_repuesto,
                "id_factura_rep" => $reg->id_factura_rep,
                "cod_producto" => $reg->cod_producto,
                "nro_serie" => $reg->nro_serie,
                "id_proveedor" => $reg->id_proveedor,
                "id_marca" => $reg->id_marca,
                "nombre" => $reg->nombre,
                "modelo" => $reg->modelo,
                "cantidad" => $reg->cantidad,
                "precio" => $reg->precio,
                "id_categoria" => $reg->id_categoria,
                "ubicacion" => $reg->ubicacion,
                "factura_nro" => $reg->factura_nro,
                "factura_file" => $reg->factura_file,
                "descripcion" => $reg->descripcion
            );
        };
        echo json_encode($data);
    break;

    case 'validaNroSerie':
        $cant = $bodRepuesto->validaNroSerie($nro_serie, $idRpuesto);
        echo $cant;
    break;
}

 ?>