function init(){
	CargarDatos();
}

function CargarDatos(){
    
	var tarjetas=0,
            tarjetas_asignadas=0,
            tarjetas_libres=0,
            nivel_0=0,
            nivel_1=0,
            nivel_2=0,
            nivel_3=0,
            nivel_4=0,
            nivel_5=0,
            nivel_6=0,
            nivel_7=0;
    
         $.post("../ajax/estado.php?op=contarTarjetas", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){

                    if(data.aaData[i]['disponible']==0){
                        tarjetas_asignadas=data.aaData[i]['cantidad'];
                    }

                    if(data.aaData[i]['disponible']==1){
                        tarjetas_libres=data.aaData[i]['cantidad'];
                    } 
                }
                
                tarjetas=parseInt(tarjetas_asignadas)+parseInt(tarjetas_libres);
 
                $("#tarjetas").html(tarjetas);
                $("#tarjetas_asignadas").html(tarjetas_asignadas);
                $("#tarjetas_libres").html(tarjetas_libres);

        });
        
        $.post("../ajax/estado.php?op=contarNivelesTarjeta", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['idnivel']==0){
                       nivel_0 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==1){
                       nivel_1 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==2){
                       nivel_2 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==3){
                       nivel_3 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==4){
                       nivel_4 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==5){
                       nivel_5 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==6){
                       nivel_6 = data.aaData[i]['cantidad'];
                    }
                    
                    if(data.aaData[i]['idnivel']==8){
                       nivel_7 = data.aaData[i]['cantidad'];
                    }
                    
                }

                $("#tarjetas").html(tarjetas);
                $("#tarjetas_asignadas").html(tarjetas_asignadas);
                $("#tarjetas_libres").html(tarjetas_libres);
                $("#nivel_0").html(nivel_0);
                $("#nivel_1").html(nivel_1);
                $("#nivel_2").html(nivel_2);
                $("#nivel_3").html(nivel_3);
                $("#nivel_4").html(nivel_4);
                $("#nivel_5").html(nivel_5);
                $("#nivel_6").html(nivel_6);
                $("#nivel_7").html(nivel_7);
        }); 
 }
init();