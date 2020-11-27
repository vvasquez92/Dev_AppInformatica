<?php 

require "../config/conexion.php";

	Class Revision{
		//Constructor para instancias
		public function __construct(){

		}

		public function insertar($idasigvehi, $tipo, $fecha, $kilometraje, $padron, $permiso, $revision, $seguro, $combustible, $selloverde, $tag, $radio, $parasoles, $gata, $rerueda, $luces, $frenos, $odometro, $velocimetro, $indicombus, $cambios, $tapaestan, $direccion, $fremano, $intermitente, $limparabrisas, $asientos, $pisos, $niveles, $parabrisas, $luneta, $puertas, $alzavidrios, $cenicero, $jaula, $motor, $gps, $observaciones){
                        //$sql="INSERT INTO revision (idasigvehi, tipo, fecha, kilometraje, padron, permiso, revision, seguro, combustible, selloverde, tag, radio, parasoles, gata, rerueda, luces, frenos, odometro, velocimetro, indicombus, cambios, tapaestan, direccion, fremano, intermitente, limparabrisas, asientos, pisos, niveles, parabrisas, luneta, puertas, alzavidrios, cenicero, jaula, motor, observaciones) VALUES ('$idasigvehi', '$tipo', '$fecha', '$kilometraje', '$padron', '$permiso', '$revision', '$seguro', '$combustible', '$selloverde', '$tag', '$radio', '$parasoles', '$gata', '$rerueda', '$luces', '$frenos', '$odometro', '$velocimetro', '$indicombus', '$cambios', '$tapaestan', '$direccion', '$fremano', '$intermitente', '$limparabrisas', '$asientos', '$pisos', '$niveles', '$parabrisas', '$luneta', '$puertas', '$alzavidrios', '$cenicero', '$jaula', '$motor', '$observaciones')";
                        $sql="INSERT INTO revision (idasigvehi, tipo, fecha, kilometraje, padron, permiso, revision, seguro, combustible, selloverde, tag, radio, parasoles, gata, rerueda, luces, frenos, odometro, velocimetro, indicombus, cambios, tapaestan, direccion, fremano, intermitente, limparabrisas, asientos, pisos, niveles, parabrisas, luneta, puertas, alzavidrios, cenicero, jaula, motor, gps, observaciones) VALUES ('$idasigvehi', '$tipo', '$fecha', '$kilometraje', '$padron', '$permiso', '$revision', '$seguro', '$combustible', '$selloverde', '$tag', '$radio', '$parasoles', '$gata', '$rerueda', '$luces', '$frenos', '$odometro', '$velocimetro', '$indicombus', '$cambios', '$tapaestan', '$direccion', '$fremano', '$intermitente', '$limparabrisas', '$asientos', '$pisos', '$niveles', '$parabrisas', '$luneta', '$puertas', '$alzavidrios', '$cenicero', '$jaula', '$motor', '$gps', upper('$observaciones'))";
			return ejecutarConsulta($sql);
		}

		public function actualizar($idasigvehi, $tipo, $fecha, $kilometraje, $padron, $permiso, $revision, $seguro, $combustible, $selloverde, $tag, $radio, 
									$parasoles, $gata, $rerueda, $luces, $frenos, $odometro, $velocimetro, $indicombus, $cambios, $tapaestan, 
									$direccion, $fremano, $intermitente, $limparabrisas, $asientos, $pisos, $niveles, $parabrisas, $luneta, $puertas, 
									$alzavidrios, $cenicero, $jaula, $motor, $gps, $observaciones){
			$sql="UPDATE revision 
					SET fecha = '$fecha', 
						kilometraje = $kilometraje, 
						padron = $padron, 
						permiso = $permiso, 
						revision = $revision, 
						seguro = $seguro, 
						combustible = $combustible, 
						selloverde = $selloverde, 
						tag = $tag, 
						radio = $radio, 
						parasoles = $parasoles, 
						gata = $gata, 
						rerueda = $rerueda, 
						luces = $luces, 
						frenos = $frenos, 
						odometro = $odometro, 
						velocimetro = $velocimetro, 
						indicombus = $indicombus, 
						cambios = $cambios, 
						tapaestan = $tapaestan, 
						direccion = $direccion, 
						fremano = $fremano, 
						intermitente = $intermitente, 
						limparabrisas = $limparabrisas, 
						asientos = $asientos, 
						pisos = $pisos, 
						niveles = $niveles, 
						parabrisas = $parabrisas, 
						luneta = $luneta, 
						puertas = $puertas, 
						alzavidrios = $alzavidrios, 
						cenicero = $cenicero, 
						jaula = $jaula, 
						motor = $motor, 
						gps = $gps, 
						observaciones = upper('$observaciones')
					WHERE idasigvehi = $idasigvehi
					AND tipo = 0;";
			return ejecutarConsulta($sql);
		}


	}
?>