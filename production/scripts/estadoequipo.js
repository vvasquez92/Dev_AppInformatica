function init(){
	CargarDatos();
}

function CargarDatos(){
    
	var  equipos=0, 
             equipos_asignados=0, 
             equipos_libres =0, 
             equipos_averiados=0,
             equipos_robados=0,
             escritorio=0,
             tableta=0,
             laptop=0,
             todo_en_uno=0;

 $.post("../ajax/estado.php?op=contarTiposEquipo", function(data,status){
        
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            
          if(data.aaData[i]['tcomputador']==1){ //escritorio
             escritorio =  data.aaData[i]['cantidad'];
          }          
          
           if(data.aaData[i]['tcomputador']==2){ //tablet
             tableta =  data.aaData[i]['cantidad'];
          }
          
          if(data.aaData[i]['tcomputador']==3){ // portatil
             laptop =  data.aaData[i]['cantidad'];
          }
          
          if(data.aaData[i]['tcomputador']==4){ //todo en uno
             todo_en_uno =  data.aaData[i]['cantidad'];
          }
          
        }
        //Actualizamos valores
        $("#equipos").html(equipos);
        $("#equipos_asignados").html(equipos_asignados);
        $("#equipos_libres").html(equipos_libres);
        $("#equipos_averiados").html(equipos_averiados);
        $("#equipos_robados").html(equipos_robados);
        $("#escritorio").html(escritorio);
        $("#tableta").html(tableta);
        $("#laptop").html(laptop);
        $("#todo_en_uno").html(todo_en_uno);
    });
      
    $.post("../ajax/estado.php?op=contarEquipos", function(data,status){
        
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            
          if(data.aaData[i]['disponible']==1){ //escritorio
             equipos_libres =  data.aaData[i]['cantidad'];
          }          
          
          equipos = equipos + parseInt(data.aaData[i]['cantidad']);
          
        }
        //Actualizamos valores
        $("#equipos").html(equipos);
        $("#equipos_libres").html(equipos_libres);

    });
    
    $.post("../ajax/estado.php?op=contarEquiposGestion", function(data,status){
        
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            
          if(data.aaData[i]['condicion']==1){ //asignados
             equipos_asignados =  data.aaData[i]['cantidad'];
          }          
          if(data.aaData[i]['condicion']==2){  //computador dañado
             equipos_averiados =  data.aaData[i]['cantidad'];
          } 
          if(data.aaData[i]['condicion']==3){  //computador robado
             equipos_robados =  data.aaData[i]['cantidad'];
          } 

        }
        //Actualizamos valores
        $("#equipos_asignados").html(equipos_asignados);
        $("#equipos_averiados").html(equipos_averiados);
        $("#equipos_robados").html(equipos_robados);

    });
    
                
}


init();