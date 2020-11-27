var tabla,tabtelefono,tabvehiculo,tabcomputador,tabtarjeta;

//funcion que se ejecuta iniciando
function init(){

	listarDispositivosDocumentacion();

	$('[data-toggle="tooltip"]').tooltip(); 
         
        
}

function listarDispositivosDocumentacion(){
    
	tabla=$('#tbldispositivos').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[		
			'excelHtml5',		
		],
		"ajax":{
			url:'../ajax/dispositivo.php?op=listarDispositivosDocumentacion',
			type:"get",
			dataType:"json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		  },
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion 10 items
		"order" : [[1 , "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

init();