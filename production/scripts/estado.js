

//funcion que se ejecuta iniciando
function init(){
	CargarDatos();
	ListarAlerta(false);
}

function ListarAlerta(flag){

	if(flag){
		$("#Charts").hide();
		$("#Listado").show();
	}else{
		$("#Charts").show();
		$("#Listado").hide();
	}

}


function CargarGraficos(){

				
}

function CargarDatos(){
	var moviles=0, molibres=0, moasig=0, sim=0, simasig=0, simlibres=0, equipos=0, elibres=0, easignados=0, epor=0, eescri=0, etable=0, vehiculos=0, velibres=0, veasig=0; 
	$.post("../ajax/estado.php?op=ContarMoviles", function(data,status){
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            if(data.aaData[i][1]==1){
                molibres=data.aaData[i][0];
            }
            if(data.aaData[i][1]==0){
                moasig=data.aaData[i][0];
            }

        }
        moviles=parseInt(molibres)+parseInt(moasig);
        //Actualizamos valores
        $("#moviles").html(moviles);
        $("#moasig").html(moasig);
        $("#molibres").html(molibres);
   
        });
        
        $.post("../ajax/estado.php?op=ContarEquipos", function(data,status){
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            if(data.aaData[i][1]==1){
                elibres++;
            }
            if(data.aaData[i][1]==0){
                easignados++;
                if(data.aaData[i][0]==1){
                    eescri++;
                }
                if(data.aaData[i][0]==2){
                    etable++;
                }
                if(data.aaData[i][0]==3){
                    epor++;
                }
            }
        }
        
        equipos=parseInt(elibres)+parseInt(easignados);
        //Actualizamos valores
        $("#equipos").html(equipos);
        $("#equiasig").html(easignados);
        $("#equilibres").html(elibres);
        $("#note").html(epor);
        $("#desk").html(eescri);
        $("#table").html(etable);
   
        });
        
        $.post("../ajax/estado.php?op=ContarSim", function(data,status){
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            if(data.aaData[i][1]==1){
                simlibres=data.aaData[i][0];
            }
            if(data.aaData[i][1]==0){
                simasig=data.aaData[i][0];
            }
            
            sim=parseInt(simasig)+parseInt(simlibres);
        }
        
        //Actualizamos valores
        $("#sim").html(sim);
        $("#simasig").html(simasig);
        $("#simlibres").html(simlibres);
   
        });
        
        $.post("../ajax/estado.php?op=ContarVehiculos", function(data,status){
	data = JSON.parse(data);
        
        for (var i=0; i<data.aaData.length; i++){
            if(data.aaData[i][1]==1){
                velibres=data.aaData[i][0];
            }
            if(data.aaData[i][1]==0){
                veasig=data.aaData[i][0];
            }
            
            vehiculos=parseInt(velibres)+parseInt(veasig);
        }
        
        //Actualizamos valores
        $("#vehiculos").html(vehiculos);
        $("#vehiasig").html(veasig);
        $("#vehilibres").html(velibres);
   
        });
        
   
        
}

function Actualizar(){
	CargarDatos();
}

init();