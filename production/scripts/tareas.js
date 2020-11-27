var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})

	$.post("../ajax/tareas.php?op=selectEmpleado", function(r){
		$("#idempleado").html(r);
		$("#idempleado").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar(){
	$("#idtarea").val("");
	$("#idempleado").val("");
	$("#idempleado").selectpicker('refresh');	
	$("#tit_tarea").val("");
	$("#tit_fhini").val("");
	$("#tit_horaini").val("");
    $("#tit_fhfin").val("");
    $("#tit_horafin").val("");
	$("#tit_comentario").val("");
}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadotareas").hide();
		$("#formulariotareas").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadotareas").show();
		$("#formulariotareas").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tbltareas').dataTable({
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
			url:'../ajax/tareas.php?op=listar',
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
		url:'../ajax/equipo.php?op=guardaryeditar',
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

function mostar(idequipo){
	$.post("../ajax/equipo.php?op=mostar",{idequipo:idequipo}, function(data,status){
		data = JSON.parse(data);
		mostarform(true);
	
		$("#idequipo").val(data.idequipo);
		$("#iddetalle").val(data.iddetalle);
		$("#iddetalle").selectpicker('refresh');
		$("#imei").val(data.imei);
		$("#serial").val(data.serial);
		$("#caja").val(data.caja);
		$("#estado").val(data.estado);
		$("#estado").selectpicker('refresh');

	})
}


init();