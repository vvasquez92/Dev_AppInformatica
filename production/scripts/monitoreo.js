//Var Globales
var markers = [];
var refdata = [];
var map;
var refnuevos = [];

//http://maps.google.com/mapfiles/kml/pal3/icon51.png - Falla
//http://maps.google.com/mapfiles/kml/pal3/icon38.png - Mantencion
//http://maps.google.com/mapfiles/kml/pal3/icon39.png - Sismo
//http://maps.google.com/mapfiles/kml/pal3/icon32.png - Revision

//http://maps.google.com/mapfiles/ms/micons/mechanic.png

var detalle = {
          2: {
            icon: '../public/api/erroricon.png',
            info: 'Falla'
          },
          3: {
            icon: '../public/api/bomberos.png',
            info: 'Bomberos'
          },
          4: {
            icon: '../public/api/Sismo.png',
            info: 'Sismo'
          },
          5: {
            icon: '../public/api/icon_ins.png',
            info: 'Inspeccion'
          },
          6: {
              icon: '../public/api/electricidad.png',
              info: 'Suministro Electrico'
            }
};

//funcion que se ejecuta iniciando
function init(){

	//Cargamos Alertas al iniciar aplicacion
	alertas();
	
	//Mantiene actulizado el mapa con nuevos Markers
	setInterval("VerificarCambios()", 5000);

}

function alertas(){
		$.post("../ajax/monitoreo.php?op=listarAlerta", function(data,status){
		data = JSON.parse(data);
        for (var i=0; i<data.aaData.length; i++){
        //Configuracion del marcador
            if(data.aaData[i][2] != 1){
            	  var content = '<div id="iw-container">' +
                  '<div class="iw-title">'+detalle[data.aaData[i][2]].info+'</div>' +
                  '<div class="iw-content">' +
                    '<div class="iw-subTitle">Personal</div>' +
                    '<p><b>Supervisor: </b>Rodrigo Toro<br>' +
                    '<b>Tecnico: </b>Omar Maldonado<br></p>' +
                    '<div class="iw-subTitle">Ubicacion</div>' +
                    '<p><b>Edificio: </b>FABRIMETAL SA<br>'+
                    '<b>Dirección: </b>Volcan Lascar 818<br>'+
                    '<b>Santiago</b>, Pudahuel<br></p>'+
                    '<div class="iw-subTitle">Contacto</div>' +
                    '<p><b>Nombre: </b>Marco Sandoval<br>'+
                    '<b>Telefono: </b>229493900<br>'+
                    '<b>Correo Electronico: </b>msandoval@fabrimetal.cl</p>'+
                    '<div class="iw-subTitle">Equipo</div>' +
                    '<p><b>Marca: </b>Kone<br>'+
                    '<b>Modelo: </b>Monospace</p>'+
                  '</div>' +
                  '<div class="iw-bottom-gradient"></div>' +
                '</div>';
            	  
	            if(data.aaData[i][5] != 1){
	                var MarLatLng = {lat: parseFloat(data.aaData[i][3]), lng: parseFloat(data.aaData[i][4])};    
	                var markerOptions = {
	                    position: MarLatLng,
	                    icon: detalle[data.aaData[i][2]].icon,
	                    animation: google.maps.Animation.BOUNCE,
	                    map: map,
	                    title: "Codigo: "+data.aaData[i][1]
	                }
	                
	                var marker = new google.maps.Marker(markerOptions);
	                VentanaInfo(marker, content);
	                var newArray = new Array (data.aaData[i][0],marker);
	 	            markers.push(newArray);
            	}else{
            		var MarLatLng = {lat: parseFloat(data.aaData[i][3]), lng: parseFloat(data.aaData[i][4])};    
 	                var markerOptions = {
 	                    position: MarLatLng,
 	                    icon: detalle[data.aaData[i][2]].icon,
 	                    map: map,
 	                    title: "Codigo: "+data.aaData[i][1]
 	                }
 	                var marker = new google.maps.Marker(markerOptions);
 	                VentanaInfo(marker, content);
 	                //Almaceno marca en el array
 	               var newArray = new Array (data.aaData[i][0],marker);
 	               markers.push(newArray);
            	}
            }
        }
        console.log("Marcas actuales");
        console.log(markers);
        refdata = data;   
	})
}


function LimpiarMarker(pos){
		markers[pos][1].setMap(null); 
}

function LimpiarAnimacion(pos){
	markers[pos][1].setAnimation(null);
}

function BorrarMarkers(eliminar){

	console.log("Funcion Eliminar");
	console.log(eliminar);
	console.log("Original");
	console.log(markers);
	
	for (var i=0; i<eliminar.length; i++){			
		for (var o=0; o<markers.length; o++){			
			if(eliminar[i][0] == markers[o][0]){
				console.log("Elimina marca con la id: "+markers[o][0]);
				LimpiarMarker(o);
				markers.splice(o,1);
			}
		}		
	}
}


function AnimacionMarkers(animados){
	console.log("Funcion Animaciones");
	console.log(animados);
	console.log("Original");
	console.log(markers);
	
	for (var i=0; i<animados.length; i++){			
		for (var o=0; o<markers.length; o++){			
			if(animados[i][0] == markers[o][0]){
				console.log("Elimina animacion con la id: "+markers[o][0]);
				LimpiarAnimacion(o);
			}
		}		
	}
}

function AgregarMarkers(nuevos) {
    for (var i=0; i<nuevos.length; i++){
        //Configuracion del marcador
            if(nuevos[i][2] != 1){
            	  var content = '<div id="iw-container">' +
                  '<div class="iw-title">'+detalle[nuevos[i][2]].info+'</div>' +
                  '<div class="iw-content">' +
                    '<div class="iw-subTitle">Personal</div>' +
                    '<p><b>Supervisor: </b>Rodrigo Toro<br>' +
                    '<b>Tecnico: </b>Omar Maldonado<br></p>' +
                    '<div class="iw-subTitle">Ubicacion</div>' +
                    '<p><b>Edificio: </b>FABRIMETAL SA<br>'+
                    '<b>Dirección: </b>Volcan Lascar 818<br>'+
                    '<b>Santiago</b>, Pudahuel<br></p>'+
                    '<div class="iw-subTitle">Contacto</div>' +
                    '<p><b>Nombre: </b>Marco Sandoval<br>'+
                    '<b>Telefono: </b>229493900<br>'+
                    '<b>Correo Electronico: </b>msandoval@fabrimetal.cl</p>'+
                    '<div class="iw-subTitle">Equipo</div>' +
                    '<p><b>Marca: </b>Kone<br>'+
                    '<b>Modelo: </b>Monospace</p>'+
                  '</div>' +
                  '<div class="iw-bottom-gradient"></div>' +
                '</div>';
            	  
	            if(nuevos[i][5] != 1){
	                var MarLatLng = {lat: parseFloat(nuevos[i][3]), lng: parseFloat(nuevos[i][4])};    
	                var markerOptions = {
	                    position: MarLatLng,
	                    icon: detalle[nuevos[i][2]].icon,
	                    animation: google.maps.Animation.BOUNCE,
	                    map: map,
	                    title: "Codigo: "+nuevos[i][1]
	                }
	                
	                var marker = new google.maps.Marker(markerOptions);
	                VentanaInfo(marker, content);
	                var newArray = new Array (nuevos[i][0],marker);
	 	            markers.push(newArray);
            	}else{
            		var MarLatLng = {lat: parseFloat(nuevos[i][3]), lng: parseFloat(nuevos[i][4])};    
 	                var markerOptions = {
 	                    position: MarLatLng,
 	                    icon: detalle[nuevos[i][2]].icon,
 	                    map: map,
 	                    title: "Codigo: "+nuevos[i][1]
 	                }
 	                var marker = new google.maps.Marker(markerOptions);
 	                VentanaInfo(marker, content);
 	                //Almaceno marca en el array
 	               var newArray = new Array (nuevos,marker);
 	               markers.push(newArray);
            	}
            }
        }
    console.log("Marcas despues de agregar");
    console.log(markers);
}

//Verifica animaciones
function VerificarAnimaciones(){
	if(refnuevos.length > 0){
		AnimacionMarkers(refnuevos);
	}
	refnuevos = [];
}
//Funcion que verifica nuevos cambios de estado de Ascensores
function VerificarCambios(){
	console.log("Vienen nueos");
	console.log(refnuevos);
	//Actualiza animacion si entro una alerta nueva en la verificacion anterior
	VerificarAnimaciones();
	
	console.log("Verificando Cambios");	
	var nuevos = [];
	var eliminados = [];
	var band;
	//Consumimos el servicio que retorna las nuevas alertas
	$.post("../ajax/monitoreo.php?op=listarAlerta", function(data,status){
		data = JSON.parse(data);
		//Recorrido de arreglos para buscar nuevas alertas para ingresar
		var pos = 0;
		$.each(data.aaData, function( index, value ) {
			$.each(refdata.aaData, function( i, val ) {
				if(value[0] == val[0]){
					band = false;
					return false;;
				}
				band = true;
			});
			if(band){
				nuevos.push(data.aaData[pos]);
			}
			pos++;
		});
		
		//Recorrido de arreglos que permite verificar alertas que deben ser eliminadas
		var pos = 0;
		$.each(refdata.aaData, function( index, value ) {
			$.each(data.aaData, function( i, val ) {
				if(value[0] == val[0]){
					band = false;
					return false;;
				}
				band = true;
			});
			if(band){
				eliminados.push(refdata.aaData[pos]);
			}
			pos++;
		});
		
		

		if(eliminados.length > 0){
			console.log("Entro a eliminar");
			BorrarMarkers(eliminados);
			
		}
		
		if(nuevos.length > 0){
			console.log("Entro a agregar");
			AgregarMarkers(nuevos);		
			refnuevos = nuevos ;			
		}
		
		

		console.log("Cambio data de referencia");
		refdata = data;
	})
}


function VentanaInfo(marker, contenido) {
    var infowindow = new google.maps.InfoWindow({
      content: contenido
    });

    marker.addListener('click', function() {
      infowindow.open(marker.get('map'), marker);
    });
  }


init();