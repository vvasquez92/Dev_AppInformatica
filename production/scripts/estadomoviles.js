function init(){
	CargarDatos();
}

function CargarDatos(){
    
	var moviles=0,
            moviles_asignados=0,
            moviles_libres=0,
            moviles_descompuestos=0,
            moviles_robados=0,
            sim=0,
            sim_asignadas=0,
            sim_libres=0,
            sim_robadas=0;
    
         $.post("../ajax/estado.php?op=ContarMoviles", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['disponible']==0){
                        moviles_asignados=data.aaData[i]['cantidad'];
                    }
                    
                    moviles = parseInt(moviles) + parseInt(data.aaData[i]['cantidad']) 
                }
 
                $("#moviles").html(moviles);
                $("#moviles_asignados").html(moviles_asignados);

        });
        
         $.post("../ajax/estado.php?op=contarMovilesGestion", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['condicion']==1){     //telefono devuelto
                        moviles_libres=data.aaData[i]['cantidad'];
                    }
                    if(data.aaData[i]['condicion']==2){     // telefono descompuesto
                        moviles_descompuestos=data.aaData[i]['cantidad'];
                    }
                    if(data.aaData[i]['condicion']==3){     //telefono robado.-
                        moviles_robados=data.aaData[i]['cantidad'];
                    }
                 
                }
 
                $("#moviles_libres").html(moviles_libres);
                $("#moviles_descompuestos").html(moviles_descompuestos);
                $("#moviles_robados").html(moviles_robados);

        });
        
        $.post("../ajax/estado.php?op=ContarSim", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['disponible']==1){     //telefono devuelto o telefono descompuesto 
                        sim_libres=data.aaData[i]['cantidad'];
                    }

                sim = parseInt(sim) + parseInt(data.aaData[i]['cantidad']);
                 
                }
 
                $("#moviles_libres").html(moviles_libres);
                $("#moviles_descompuestos").html(moviles_descompuestos);
                $("#moviles_robados").html(moviles_robados);
                $("#sim_libres").html(sim_libres);
                $("#sim").html(sim);

        });
    
        $.post("../ajax/estado.php?op=ContarSimGestion", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['condicion']==1){ // sim asignados     
                        sim_asignadas=data.aaData[i]['cantidad'];
                    }

                    if(data.aaData[i]['condicion']==3){ // telefono robado 
                        sim_robadas=data.aaData[i]['cantidad'];
                    }
                }
 
                $("#moviles_libres").html(moviles_libres);
                $("#moviles_descompuestos").html(moviles_descompuestos);
                $("#moviles_robados").html(moviles_robados);
                $("#sim_libres").html(sim_libres);
                $("#sim").html(sim);
                $("#sim_asignadas").html(sim_asignadas);
                $("#sim_robadas").html(sim_robadas);

        });
        
 }
init();