var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

}


function mostarform(flag){

	if(flag){
		$("#listadopermisos").hide();
		$("#formulariopermisos").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadopermisos").show();
		$("#formulariopermisos").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}


function listar(){
	tabla=$('#tblpermisos').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[
			'copyHtml5',
			'print',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax":{
			url:'../ajax/permiso.php?op=listar',
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
		"order" : [[0 , "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

init();