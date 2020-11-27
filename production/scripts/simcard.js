var tabla;

//funcion que se ejecuta iniciando
function init() {

	mostarform(false);

	listar();

	$('[data-toggle="tooltip"]').tooltip();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

	$.post("../ajax/operador.php?op=selectOperador", function (r) {
		$("#idoperador").html(r);
		$("#idoperador").selectpicker('refresh');
	});

	$("#numero").focusout(function () {

		if ($.trim($("#numero").val()).length > 0) {

			$.post("../ajax/simcard.php?op=validarNumeroRegistrado",
				{ "idsimcard": $("#idsimcard").val(), "numero": $("#numero").val() },
				function (data) {

					if (parseInt(data.cantidad) == 0) {

						$("#btnGuardar").prop("disabled", false);

						new PNotify({
							title: 'Correcto!',
							text: 'El numero de telefono es válido.',
							type: 'success',
							styling: 'bootstrap3'
						});

					} else {

						$("#btnGuardar").prop("disabled", true);

						new PNotify({
							title: 'Error!',
							text: 'El numero de telefono ya se encuentra asignado a otro simcard.',
							type: 'error',
							styling: 'bootstrap3'
						});
					}
				}, "json");

		} else {
			$("#btnGuardar").prop("disabled", false);
		}
	});

}


// Otras funciones
function limpiar() {

	$("#idsimcard").val("");
	$("#idoperador").val("");
	$("#idoperador").selectpicker('refresh');
	$("#serial").val("");
	$("#numero").val("");
	$("#pin").val("");
	$("#puk").val("");

}

function mostarform(flag) {

	limpiar();

	if (flag) {
		$("#listadosimcards").hide();
		$("#formulariosimcard").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadosimcards").show();
		$("#formulariosimcard").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {

	tabla = $('#tblsimcards').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons: [
			'excelHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/simcard.php?op=listar',
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
		"order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function guardaryeditar(e) {

	e.preventDefault();

	$("#btnGuardar").prop("disabled", true);

	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: '../ajax/simcard.php?op=guardaryeditar',
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

function mostrar(idsimcard) {

	$.post("../ajax/simcard.php?op=mostrar", { idsimcard: idsimcard }, function (data, status) {

		data = JSON.parse(data);

		mostarform(true);

		$("#idsimcard").val(data.idsimcard);
		$("#idoperador").val(data.idoperador);
		$("#idoperador").selectpicker('refresh');
		$("#serial").val(data.serial);
		$("#numero").val(data.numero);
		$("#pin").val(data.pin);
		$("#puk").val(data.puk);

	});
}

function desactivar(idsimcard) {

	bootbox.confirm("Esta seguro que quiere inhabilitar la tarjeta SIM?", function (resultado) {

		if (resultado) {
			$.post("../ajax/simcard.php?op=desactivar", { idsimcard: idsimcard }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});

		}
	});
}

function activar(idsimcard) {

	bootbox.confirm("Esta seguro que quiere habilitar la tarjeta SIM?", function (result) {
		if (result) {
			$.post("../ajax/simcard.php?op=activar", { idsimcard: idsimcard }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	});
}


init();