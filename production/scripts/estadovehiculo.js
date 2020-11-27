var tabla;
var tabla_a;
var tabla_l;
function init(){
	CargarDatos();
}

function CargarDatos(){
    
	var  vehiculos=0, vehi_libres=0, vehi_asig=0, vehi_nodisp=0, vehi_mant=0, vehi_rep=0, vehi_sin=0, vehi_rob=0, vehi_dev=0; 

        $.post("../ajax/estado.php?op=ContarVehiculos", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i][1]==0){
                        vehi_asig=data.aaData[i][0];
                    }
                                        
                    if(data.aaData[i][1]==1){
                        vehi_libres=data.aaData[i][0];
                    }

                    if(data.aaData[i][1]==2){
                        vehi_nodisp=data.aaData[i][0];
                    }

                    vehiculos=parseInt(vehi_libres)+parseInt(vehi_asig)+parseInt(vehi_nodisp);
                }

                //Actualizamos valores
                $("#vehiculos").html(vehiculos);
                $("#vehi_asig").html(vehi_asig);
                $("#vehi_libres").html(vehi_libres);
        }); 
        
        $.post("../ajax/estado.php?op=contarVehiculoGestion", function(data,status){
	
                data = JSON.parse(data);

                for (var i=0; i<data.aaData.length; i++){
                    
                    if(data.aaData[i]['gestion']==1){   //vehiculo en mantencion
                        vehi_dev=data.aaData[i]['cant_vehiculos'];
                    }
                                        
                    if(data.aaData[i]['gestion']==2){   //vehiculo en reparacion
                        vehi_rep=data.aaData[i]['cant_vehiculos'];
                    }

                    if(data.aaData[i]['gestion']==3){   //vehiculo con siniestro
                        vehi_sin=data.aaData[i]['cant_vehiculos'];
                    }
                    
                    if(data.aaData[i]['gestion']==4){   //vehiculo robado
                        vehi_rob=data.aaData[i]['cant_vehiculos'];
                    }

                    if(data.aaData[i]['gestion']==5){   //vehiculo en mantencion
                        vehi_mant=data.aaData[i]['cant_vehiculos'];
                    }
                }

                //Actualizamos valores
                $("#vehi_dev").html(vehi_dev);
                $("#vehi_mant").html(vehi_mant);
                $("#vehi_rep").html(vehi_rep);
                $("#vehi_sin").html(vehi_sin);
                $("#vehi_rob").html(vehi_rob);
        }); 
}

function listarVehDet(vtipo,descripcion){

    $('#myModalLabel').html(descripcion);

    $('#btnVehDet').trigger("click");
    tabla = $('#tblVehDet').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/estado.php?op=listarVehDet',
			type:"post",
            data: {tipo: vtipo},
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion 10 items
		"order": [[0, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function listarVehAsig(){

    $('#btnVehAsig').trigger("click");
    tabla_a = $('#tblVehAsig').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/estado.php?op=listarVehAsig',
			type:"get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion 10 items
		"order": [[0, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function listarVehLib(){
    $('#btnVehLib').trigger("click");
    tabla_l = $('#tblVehLib').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/estado.php?op=listarVehLib',
			type:"get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		},
		"bDestroy": true,
		"iDisplayLength": 5, //Paginacion 10 items
		"order": [[0, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}




init();