var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

}


// Otras funciones
function limpiar() {

	$("#idmarcacom").val("");
	$("#nombre").val("");
}

function mostarform(flag) {

	limpiar();
	if (flag) {
		$("#listadomarcas").hide();
		$("#formulariomarcas").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadomarcas").show();
		$("#formulariomarcas").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tblmarcas').dataTable({
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
			url: '../ajax/marcacom.php?op=listar',
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
		url: '../ajax/marcacom.php?op=guardaryeditar',
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

function mostar(idmarcacom) {
	$.post("../ajax/marcacom.php?op=mostar", { idmarcacom: idmarcacom }, function (data, status) {

		data = JSON.parse(data);
		mostarform(true);

		$("#idmarcacom").val(data.idmarcacom);
		$("#nombre").val(data.nombre);

	})
}

function eliminar(idmarcacom) {
	$.post("../ajax/marcacom.php?op=eliminar", { idmarcacom: idmarcacom }, function (data, status) {
		bootbox.alert("Marca quitada");
		mostarform(false);
		tabla.ajax.reload();
	})
}



init();