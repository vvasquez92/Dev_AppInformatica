var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})

	$.post("../ajax/departamentos.php?op=selectDepartamentos", function(r){
		$("#iddepartamento").html(r);
		$("#iddepartamento").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar(){

	$("#idcargos").val("");
	$("#nombre").val("");
	$("#iddepartamento").val("");
	$("#iddepartamento").selectpicker('refresh');	

}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadocargos").hide();
		$("#formulariocargos").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadocargos").show();
		$("#formulariocargos").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblcargos').dataTable({
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
			url:'../ajax/cargos.php?op=listar',
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

function guardaryeditar(e){
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url:'../ajax/cargos.php?op=guardaryeditar',
		type:"POST",
		data:formData,
		contentType: false,
		processData:false,

		success: function(datos){
			bootbox.alert(datos);
			mostarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

function mostar(idcargos){
	$.post("../ajax/cargos.php?op=mostar",{idcargos:idcargos}, function(data,status){
		data = JSON.parse(data);
		mostarform(true);
	
		$("#idcargos").val(data.cargos);
		$("#nombre").val(data.nombre);
		$("#iddepartamento").val(data.iddepartamento);
		$("#iddetalle").selectpicker('refresh');

	})
}


init();