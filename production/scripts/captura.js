/*
    Tomar una fotografia y guardarla en un archivo
    @date 2017-11-22
    @author parzibyte
    @web parzibyte.me/blog
    modificado por Ing. cristhian paul ramirez.
*/
function tieneSoporteUserMedia() {
    return !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia);
}

function _getUserMedia() {
    return (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);
}

function activar_camara(){
    
        if (tieneSoporteUserMedia()) {
         _getUserMedia({
                 video: true
             },
             function(stream) {
                 console.log("Permiso concedido");
                 document.getElementById("video").srcObject = stream;
                 document.getElementById("video").play();
                 $("#div_video").show('slow');
             },
             function(error) {
                 console.log("Permiso denegado o error: ", error);
                 document.getElementById("estado").innerHTML = "<font color='red'>No se puede acceder a la camara, o no diste permiso.</font>";
             });
     } else {
         alert("Lo siento. Tu navegador no soporta esta caracteristica");
         document.getElementById("estado").innerHTML = "<font color='red'>Parece que tu navegador no soporta esta caracteristica. Intenta actualizarlo.</font>";
     }
}

function tomar_foto(){

                //Pausar reproduccion
                document.getElementById("video").pause();

                //Obtener contexto del canvas
                var contexto = document.getElementById("canvas").getContext("2d");
                //limpiar el canvas.
                contexto.clearRect(0, 0, 200, 200);
                //dibujar sobre el canvas
                contexto.drawImage(document.getElementById("video"), 0, 0,200,200);
 
                document.getElementById('imagen_cam').value = document.getElementById("canvas").toDataURL();

                //Reanudar reproduccion
                document.getElementById("video").play();
}



