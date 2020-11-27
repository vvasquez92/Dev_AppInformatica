<?php 
session_start();

require_once "../modelos/Prestamo.php";
require_once "../modelos/Revision.php";

$prestamo = new Prestamo();
$revision = new Revision();

$idprestamo=isset($_POST["idprestamo"])?limpiarCadena($_POST["idprestamo"]):"";
$idasigvehi=isset($_POST["idasigvehi"])?limpiarCadena($_POST["idasigvehi"]):"";
$idempleado=isset($_POST["idempleado"])?limpiarCadena($_POST["idempleado"]):"";
$fhCompromiso=isset($_POST["fhCompromiso"])?limpiarCadena($_POST["fhCompromiso"]):"";
$fecha=isset($_POST["fecha"])?limpiarCadena($_POST["fecha"]):"";
$kilometraje=isset($_POST["kilometraje"])?limpiarCadena($_POST["kilometraje"]):"";
$observaciones=isset($_POST["observaciones"])?limpiarCadena($_POST["observaciones"]):"";
$fhCompromiso=isset($_POST["fhCompromiso"])?limpiarCadena($_POST["fhCompromiso"]):"";

switch ($_GET["op"]) {
    
    case 'guardaryeditar':
        
		if(empty($idprestamo)){                    
                        $closed_time="";
                        $created_user=$_SESSION['iduser'];
                        $closed_user="";
                        $condicion=1;
            $numrevision= count($_POST["revision"]);
            $oprevision = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            for ($o=0; $o < (int)$numrevision; $o++) {
                $oprevision[$_POST["revision"][$o]] = 1;
            }  
            $rsp=$revision->actualizar($idasigvehi, 0, $fecha, $kilometraje, $oprevision[0], $oprevision[1], $oprevision[2], $oprevision[3], $oprevision[4], $oprevision[5], $oprevision[6], $oprevision[7], $oprevision[8], $oprevision[9], $oprevision[10], $oprevision[11], $oprevision[12], $oprevision[13], $oprevision[14], $oprevision[15], $oprevision[16], $oprevision[17], $oprevision[18], $oprevision[19], $oprevision[20], $oprevision[21], $oprevision[22], $oprevision[23], $oprevision[24], $oprevision[25], $oprevision[26], $oprevision[27], $oprevision[28], $oprevision[29], $oprevision[30], $oprevision[31], $oprevision[32], $observaciones);                                

            $rspta=$prestamo->insertar($idasigvehi,$idempleado,$closed_time,$created_user,$closed_user,$condicion, $fhCompromiso);
            
			echo $rspta ? "Préstamo de vehículo registrado" : "Préstamo de vehículo no se registro";
		}
        connClose();
		break;
                
                case 'desactivar_prestamo':

                    $numrevision= count($_POST["revision"]);
                    $oprevision = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                    for ($o=0; $o < (int)$numrevision; $o++) {
                        $oprevision[$_POST["revision"][$o]] = 1;
                    }  
                    $rsp=$revision->actualizar($idasigvehi, 0, $fhCompromiso, $kilometraje, $oprevision[0], $oprevision[1], $oprevision[2], $oprevision[3], $oprevision[4], $oprevision[5], $oprevision[6], $oprevision[7], $oprevision[8], $oprevision[9], $oprevision[10], $oprevision[11], $oprevision[12], $oprevision[13], $oprevision[14], $oprevision[15], $oprevision[16], $oprevision[17], $oprevision[18], $oprevision[19], $oprevision[20], $oprevision[21], $oprevision[22], $oprevision[23], $oprevision[24], $oprevision[25], $oprevision[26], $oprevision[27], $oprevision[28], $oprevision[29], $oprevision[30], $oprevision[31], $oprevision[32], $observaciones);                                
                
                    $closed_user=$_SESSION['iduser'];    
                    $rspta=$prestamo->desactivar($idprestamo,$closed_user);
                    echo $rspta ? "préstamo inhabilitado" : "préstamo no se pudo inhabilitar";    
                    connClose();
                break;    
}

 ?>