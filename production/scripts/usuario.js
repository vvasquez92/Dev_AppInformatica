var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

	$.post("../ajax/usuario.php?op=selectRole", function (r) {
		console.log(r);
		$("#idrole").html(r);
		$("#idrole").selectpicker('refresh');
	});

	$("#tipo_documento").on("change", function (e) {
		$("#num_documento").attr('readonly', false);
		$("#num_documento").inputmask('remove');
		if (this.value == 'RUT') {
			$("#num_documento").inputmask({ "mask": "99.999.999-*" }); //specifying options                                
		} else if (this.value == 'P') {
			$("#num_documento").inputmask({ "mask": "P-*{1,40}" }); //specifying options           
		}
	});

	$("#imagenmuestra").hide();
}


// Otras funciones
function limpiar() {

	$("#iduser").val("");
	$("#idrole").val("");
	$("#idrole").selectpicker('refresh');
	$("#username").val("");
	$("#password").val("");
	$("#nombre").val("");
	$("#apellido").val("");
	$("#tipo_documento").val("");
	$("#tipo_documento").selectpicker('refresh');
	$("#num_documento").val("");
	$("#fecha_nac").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#imagenmuestra").attr("src", "");
	$("#imagenactual").val("");
	$("#imagen").val("");

}

function mostarform(flag) {

	limpiar();
	if (flag) {
		$("#listadousuarios").hide();
		$("#formulariousuarios").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadousuarios").show();
		$("#formulariousuarios").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tblusuarios').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			'print',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/usuario.php?op=listado',
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		  },
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion 10 items
		"order": [[1, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function guardaryeditar(e) {
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: '../ajax/usuario.php?op=guardaryeditar',
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			bootbox.alert(datos);
			mostarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

function mostar(iduser) {
	$.post("../ajax/usuario.php?op=mostar", { iduser: iduser }, function (data, status) {
		data = JSON.parse(data);
		mostarform(true);

		$("#iduser").val(data.iduser);
		$("#idrole").val(data.idrole);
		$("#idrole").selectpicker('refresh');
		$("#username").val(data.username);
		$("#password").val("");
		$("#nombre").val(data.nombre);
		$("#apellido").val(data.apellido);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#fecha_nac").val(data.fecha_nac);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen);
		$("#imagenactual").val(data.imagen);

	})
}

function desactivar(iduser) {

	bootbox.confirm("Esta seguro que quiere inhabilitar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=desactivar", { iduser: iduser }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			})
		}
	})
}

function activar(iduser) {

	bootbox.confirm("Esta seguro que quiere habilitar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=activar", { iduser: iduser }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			})
		}
	})
}


init();