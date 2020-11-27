var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();

	$.post("../ajax/role.php?op=permisos&id=", function (r) {
		$("#permisos").html(r);
	});

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})
}


// Otras funciones
function limpiar() {

	$("#idrole").val("");
	$("#nombre").val("");
	$("#descripcion").val("");

}

function mostarform(flag) {

	limpiar();
	if (flag) {
		$("#listadoroles").hide();
		$("#formularioroles").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadoroles").show();
		$("#formularioroles").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tblroles').dataTable({
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
			url: '../ajax/role.php?op=listar',
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
		"order": [[0, "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function guardaryeditar(e) {
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: '../ajax/role.php?op=guardaryeditar',
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

function mostar(idrole) {
	$.post("../ajax/role.php?op=mostar", { idrole: idrole }, function (data, status) {
		data = JSON.parse(data);
		mostarform(true);
		$("#idrole").val(data.idrole);
		$("#nombre").val(data.nombre);
		$("#descripcion").val(data.descripcion);
	});

	$.post("../ajax/role.php?op=permisos&id=" + idrole, function (r) {
		$("#permisos").html(r);
	});
}

function eliminar(idrole) {

	bootbox.confirm("Esta seguro que quiere eliminar el role?", function (result) {
		if (result) {
			$.post("../ajax/role.php?op=eliminar", { idrole: idrole }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			})
		}
	})
}


init();