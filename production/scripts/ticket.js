var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

	$("#idtipoticket").on("change", function (e) {
		$.post("../ajax/ticket.php?op=tipo_solicitud", { idtipoticket: $("#idtipoticket").val() }, function (r) {
			$("#idtipo").html(r);
			$("#idtipo").selectpicker('refresh');
		});
	});

	$.post("../ajax/ticket.php?op=tipo_ticket", function (r) {
		$("#idtipoticket").html(r);
		$("#idtipoticket").selectpicker('refresh');
	});

	$.post("../ajax/empleado.php?op=selectempleado", function (r) {
		$("#idempleado").html(r);
		$("#idempleado").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar() {

	$("#idtarjeta").val("");
	$("#idnivel").val("");
	$("#idnivel").selectpicker('refresh');
	$("#codigo").val("");
	$("#codigosys").val("");
	$("#descripcion").val("");

}

function mostarform(flag) {

	limpiar();
	if (flag) {
		$("#listadotickets").hide();
		$("#formulariotickets").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadotickets").show();
		$("#formulariotickets").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tbltickets').dataTable({
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
			url: '../ajax/ticket.php?op=listar',
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
		url: '../ajax/ticket.php?op=guardaryeditar',
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

function mostar(idticket) {
	$.post("../ajax/ticket.php?op=mostar", { idtarjeta: idtarjeta }, function (data, status) {
		data = JSON.parse(data);
		mostarform(true);

		$("#idtarjeta").val(data.idtarjeta);
		$("#idnivel").val(data.idnivel);
		$("#idnivel").selectpicker('refresh');
		$("#codigo").val(data.codigo);
		$("#codigosys").val(data.codigosys);
		$("#descripcion").val(data.descripcion);
	});
}


init();