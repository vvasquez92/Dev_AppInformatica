<?php 
session_start();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

require_once "../modelos/Noticia.php";
require_once "../modelos/NoticiaImagen.php";

$noticia = new Noticia();

$noticiaImagen = new NoticiaImagen();

$idnoticia=isset($_POST["idnoticia"])?limpiarCadena($_POST["idnoticia"]):"";
$titulo=isset($_POST["titulo"])?limpiarCadena($_POST["titulo"]):"";
$contenido=isset($_POST["contenido"])?limpiarCadena($_POST["contenido"]):"";
$estado=isset($_POST["estado"])?limpiarCadena($_POST["estado"]):"";
$iddepartamento=isset($_POST["iddepartamento"])?limpiarCadena($_POST["iddepartamento"]):"";
$visible_portada = isset($_POST['visible_portada']) ? 1 : 0;
/*parametros para filtar la consulta*/
$param_estado=isset($_POST["param_estado"])?limpiarCadena($_POST["param_estado"]):"";
$param_iddepartamento=isset($_POST["param_iddepartamento"])?limpiarCadena($_POST["param_iddepartamento"]):"";
$param_visible_portada=isset($_POST["param_visible_portada"])?limpiarCadena($_POST["param_visible_portada"]):"";

switch ($_GET["op"]) {
    
	case 'guardaryeditar':
             
		if(empty($idnoticia)){
                        
                        $update_time='';
                        $updated_user='';
                        $created_user=$_SESSION['iduser'];                        
                        $condicion=1;  
                        $estado=0;
                        $visible_portada=0;
			$rspta=$noticia->insertar($titulo,$contenido,$estado,$visible_portada,$iddepartamento,$update_time,$created_user,$updated_user,$condicion);        
                        $idnoticia=$rspta;
                        $mensaje= $rspta ? "Noticia Registrada" : "Noticia no pudo ser Registrada.";
		}
		else{
                        $update_time = date("Y-m-d H:i:s");
                        $updated_user=$_SESSION['iduser'];
			$rspta=$noticia->editar($idnoticia,$titulo,$contenido,$iddepartamento,$update_time,$updated_user);
			$mensaje= $rspta ? "Noticia modificada." : "Noticia no pudo ser modificada.";
		}
                
            if($rspta){
                     
                $total = 0; //variable total de imagenes
                $fileCount=count($_FILES['imagenes']['name']);
                $fileSize=$_FILES['imagenes']['size'];

                    for($i=0;$i<$fileCount;$i++) { //se verifica si se enviaron imagenes en el input file.
                        if ($fileSize[$i] > 0) {
                           $total++;
                        }
                    }
                    
                if($total >0){
                
                    $noticiaImagen->eliminar($idnoticia);   
                
                    for ($i = 0; $i < $total; $i++){
                
                        if ($_FILES['imagenes']['type'][$i] == "image/jpg" || $_FILES['imagenes']['type'][$i] == "image/jpeg" || $_FILES['imagenes']['type'][$i] == "image/png") {
                    
                            $fileName = $_FILES['imagenes']['tmp_name'][$i]; 
                            $sourceProperties = getimagesize($fileName);
                            $resizeFileName  = round(microtime(true))."".$i;
                            $uploadPath = "../files/noticias/";
                            $fileExt = pathinfo($_FILES['imagenes']['name'][$i], PATHINFO_EXTENSION);
                            $uploadImageType = $sourceProperties[2];
                            $sourceImageWidth = $sourceProperties[0];
                            $sourceImageHeight = $sourceProperties[1];
                    
                            switch ($uploadImageType) {
                                case IMAGETYPE_JPEG:
                                    $resourceType = imagecreatefromjpeg($fileName); 
                                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                                    imagejpeg($imageLayer,$uploadPath.''.$resizeFileName.'.'. $fileExt);
                                    break;

                                case IMAGETYPE_GIF:
                                    $resourceType = imagecreatefromgif($fileName); 
                                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                                    imagegif($imageLayer,$uploadPath.''.$resizeFileName.'.'. $fileExt);
                                    break;

                                case IMAGETYPE_PNG:
                                    $resourceType = imagecreatefrompng($fileName); 
                                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                                    imagepng($imageLayer,$uploadPath.''.$resizeFileName.'.'. $fileExt);
                                    break;
                            }

                            $noticiaImagen->insertar($resizeFileName.'.'. $fileExt, $idnoticia);                    
                        }
                    } 
                }
            
            }
            
            echo  $mensaje;
            connClose();
        break;

	case 'desactivar':
		$rspta=$noticia->desactivar($idnoticia);
        echo $rspta ? "Noticia inhabilitada" : "Noticia no se pudo inhabilitar";
        connClose();
		break;

	case 'activar':
                
                $max_noticias=3;
            
                $resp = $noticia->mostrar($idnoticia);
                $iddepartamento = $resp['iddepartamento'];
                $result = $noticia->contarNoticiasActivas($iddepartamento,"");
		$cantidad_noticias = $result['cantidad'];
                        
                if($cantidad_noticias<$max_noticias){
                    
                    $rspta=$noticia->activar($idnoticia);
                    echo $rspta ? "Noticia habilitada" : "Noticia no se pudo habilitar";
                }else{
                    echo "Noticia no se pudo habilitar, solo se permite $max_noticias Noticias maximo.";    
                }
                connClose();
		break;

        case 'eliminar':
                $rspta=$noticia->eliminar($idnoticia);
                echo $rspta ? "Noticia eliminada" : "Noticia no se pudo eliminar";
                connClose();
        break;
            
	case 'mostar':
            
		$respuesta=$noticia->mostrar($idnoticia);
                
                $rspta= $noticiaImagen->mostrar($idnoticia);
                $data = Array();
                while ($reg = $rspta->fetch_object()){
                    $data[] = array(
                                "url"=>$reg->url,
                                "idnoticia"=>$reg->idnoticia,
                               );
                }
                
                echo json_encode(Array('respuesta'=>$respuesta,'data'=>$data));
                connClose();
		break;
                                
	case 'listar':
                                               
		$rspta=$noticia->listar($param_iddepartamento,$param_estado,$param_visible_portada);
		$data = Array();
		while ($reg = $rspta->fetch_object()){
                    
                    if($reg->visible_portada){
                                $campo_visible='SI ESTA VISIBLE&nbsp;<button class="btn btn-success btn-xs" onclick="quitarHome('.$reg->idnoticia.')" data-tooltip="tooltip" title="Quitar del HOME"><i class="fa fa-check-square-o"></i></button>';
                            }else{
                                $campo_visible='NO ESTA VISIBLE&nbsp;<button class="btn btn-success btn-xs" onclick="agregarHome('.$reg->idnoticia.')" data-tooltip="tooltip" title="Agregar al HOME"><i class="fa fa-square-o"></i></button>';
                            }
                            
			$data[] = array(
					"0"=>($reg->estado)?
					'<button title="mostrar"    class="btn btn-primary btn-xs" onclick="mostrar('.$reg->idnoticia.')"><i class="fa fa-pencil"></i></button>'.
					'<button title="desactivar" class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idnoticia.')"><i class="fa fa-close"></i></button>':
					'<button title="mostrar"    class="btn btn-primary btn-xs" onclick="mostrar('.$reg->idnoticia.')"><i class="fa fa-pencil"></i></button>'.
					'<button title="activar"    class="btn btn-success btn-xs" onclick="activar('.$reg->idnoticia.')"><i class="fa fa-check"></i></button>',                                        					
                                        "1"=>$reg->titulo,
                                        "2"=>$reg->nombre_estado,
                                        "3"=>$campo_visible,
					"4"=>$reg->nombre.", ".$reg->apellido,
                                        "5"=>$reg->nombre_departamento,
					"6"=>$reg->created_time,
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
                
        case 'listarNoticiasIntranet':

        $rspta= $noticia->listar($param_iddepartamento,$param_estado,$param_visible_portada);                    
        $data = Array();   
        $arreglo = Array();
        while ($reg = $rspta->fetch_object()){
        $data[] = array(
                        "idnoticia"=>$reg->idnoticia,
                        "titulo"=>$reg->titulo,
                        "contenido"=>$reg->contenido,
                        "iddepartamento"=>$reg->iddepartamento,
                        );
        
                $obj = $noticiaImagen->mostrar($reg->idnoticia); //Buscar imagenes 
                $arreglo[$reg->idnoticia]=array();
                while ($row = $obj->fetch_object()){
                    $arreglo[$row->idnoticia][] = array(
                        "idnoticia_imagen"=>$row->idnoticia_imagen,
                        "url"=>$row->url,
                    );
                } 
        }
                                            
        echo json_encode(array('data'=> $data,'arreglo'=>$arreglo));
        connClose();
        break;    

        case 'salir':
            session_unset();
            session_destroy();
            header("Location: ../index.php");
        break;
                
        case 'agregarHome':
            
        $max_home=1;
        $max_activas=3;
        
        $resp = $noticia->mostrar($idnoticia);//consultar el departamento de la noticia
        $iddepartamento = $resp['iddepartamento'];
        
        $result = $noticia->contarNoticiasHome($iddepartamento);
$cantidad = $result['cantidad'];
                
        if($cantidad < $max_home){
            
            //contar la cantidad de noticias activas.
            $result = $noticia->contarNoticiasActivas($iddepartamento,$idnoticia);
            $cant_activas = $result['cantidad'];
        
            if($cant_activas<$max_activas){
                
                    $rspta=$noticia->activar($idnoticia);
                    
                    $rspta=$noticia->agregarHome($idnoticia);  
                    
                    echo $rspta ? "Noticia Agregada al Home" : "Noticia no se pudo agregar al Home";
            }else{
                echo "Noticia no se pudo Agregar al Home, la noticia que intenta agregar al Home debe estar incluida entre las noticias activas.";    
            }

        }else{
            echo "Noticia no se pudo Agregar al Home, solo se permite $max_home Noticia.";    
        }
        connClose();
        break;   
            
                case 'quitarHome':
                    
                    $rspta=$noticia->quitarHome($idnoticia);
                    echo $rspta ? "Noticia se quito del Home" : "Noticia no se pudo quitar del Home";
                    connClose();
                break;   
            
}

function resizeImage($resourceType,$image_width,$image_height) {
    $resizeWidth = 400;
    $resizeHeight = 400;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
}

 ?>