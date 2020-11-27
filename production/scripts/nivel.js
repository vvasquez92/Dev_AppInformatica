/* global bootbox */

var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	});
}


// Otras funciones
function limpiar(){
	$("#idnivel").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

function mostarform(flag){
	limpiar();
	if(flag){
		$("#listadoniveles").hide();
		$("#formularioniveles").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadoniveles").show();
		$("#formularioniveles").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}
}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblniveles').dataTable({
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
			url:'../ajax/nivel.php?op=listar',
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
		url:'../ajax/nivel.php?op=guardaryeditar',
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

function mostar(idnivel){
	$.post("../ajax/nivel.php?op=mostar",{idnivel:idnivel}, function(data,status){
		data = JSON.parse(data);
		$("#idnivel").val(data.idnivel);
		$("#nombre").val(data.nombre);
		$("#descripcion").val(data.descripcion);
                mostarform(true);
	});
}

function desactivar(idnivel){
	bootbox.confirm("SEGURO QUE DESEA INHABILITAR EL NIVEL DE ACCESO?", function(result){
		if(result){
			$.post("../ajax/nivel.php?op=desactivar",{idnivel:idnivel}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function activar(idnivel){
	bootbox.confirm("SEGURO QUE DESEA HABILITAR EL NIVEL DE ACCESO?", function(result){
		if(result){
			$.post("../ajax/nivel.php?op=activar",{idnivel:idnivel}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

init();