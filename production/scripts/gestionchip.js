var tabla;

function init() {

	mostrarform(false);

	listarSimRobadas();

	$('[data-toggle="tooltip"]').tooltip();

	$("#formGestion").on("submit", function (e) {
		guardarFormGestion(e);
	});

	$('#modalGestion').on('hidden.bs.modal', function (e) {
		limpiarForm();
	});

	$('#modalGestion').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		$("#idchip").val(button.data('idchip'));

	});

	$('#hablilitar').change(function (e) {
		e.preventDefault();

		check_habilitar(this);

	});
}

function check_habilitar(obj) {

	if ($(obj).is(':checked')) {
		$('div .serie').removeClass('hidden');
	} else {
		$('div .serie').addClass('hidden');
	}

}

function mostrarform(bandera) {

	if (bandera) {
		$("#listadochips").hide();
		$("#verGestion").show();
		$("#boton_regresar").show();
	} else {
		$("#listadochips").show();
		$("#verGestion").hide();
		$("#boton_regresar").hide();
	}
}


function limpiarForm() {
	$("#idchip").val("");
	$("#detalle").val("");
	$("#descripcion").val("");
	$('#hablilitar').prop('checked', false); // Unchecks it
}


function mostrarGestion(idchip) {

	$.post("../ajax/chip.php?op=mostar", { idchip: idchip }, function (data, status) {
		data = JSON.parse(data);
		$("#operador_sim").html(data.nombre_operador);
		$("#numero_sim").html(data.numero);
		$("#serial_sim").html(data.serial);
		$("#disponibilidad_sim").html((parseInt(data.disponible)) ? '<span class="label bg-green">SIN ASIGNAR</span>' : '<span class="label bg-red">ASIGNADO</span>');
	});


	$.post("../ajax/gestionchip.php?op=listar", { idchip: idchip }, function (data, status) {

		data = JSON.parse(data);
		var str = "";
		if (data.TotalRecords == 0) {
			$("#listaGestion").html("No se ha realizado Gestión a esta SIM.");
		} else {
			for (var i = 0; i < data.TotalRecords; i++) {

				str += ' <li>' +
					'<img src="../files/usuarios/' + data.registros[i].imagen + '" class="avatar" alt="Avatar">' +
					'<div class="message_date"><h3 class="date text-info">' + data.registros[i].dia + '</h3>' +
					'<p class="month">' + data.registros[i].mes + '</p>' +
					'</div>' +
					'<div class="message_wrapper">' +
					'<h4 class="heading">' + data.registros[i].nombre + ', ' + data.registros[i].apellido + ' - ' + data.registros[i].detalle + '</h4>' +
					'<blockquote class="message">' + data.registros[i].descripcion + '</blockquote><br />' +
					'<p class="url"><span class="fs1 text-info" aria-hidden="true" data-icon=""></span>' +
					'</p></div>' +
					'</li>';
			}
			$("#listaGestion").html(str);
		}
	});

	mostrarform(true);

}

function listarSimRobadas() {
	tabla = $('#tblchips').dataTable({
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
			url: '../ajax/chip.php?op=listarSimRobadas',
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


function guardarFormGestion(e) {

	e.preventDefault();

	$.ajax({
		// En data puedes utilizar un objeto JSON, un array o un query string
		data: $("#formGestion").serialize(),
		type: "POST",
		url: "../ajax/gestionchip.php?op=guardaryeditar",
		beforeSend: function () {
			$("#btnGuardar").prop("disabled", true);
			$('.modal-body').css('opacity', '.5');
		}
	})
		.done(function (data, textStatus, jqXHR) {
			if (console && console.log) {
				console.log("La solicitud se ha completado correctamente.");
			}
			bootbox.alert(data);
			tabla.ajax.reload();
			$("#btnGuardar").prop("disabled", false);
			$('.modal-body').css('opacity', '');
			$('#modalGestion').modal('toggle');
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			if (console && console.log) {
				console.log("La solicitud a fallado: " + textStatus);
			}

		});

	limpiarForm();
}

function inhabilitarSim(idchip) {

	bootbox.prompt({
		title: "Indique el Motivo por el cual se esta Inhabilitando el SIM",
		inputType: 'textarea',
		className: 'bootbox-custom-class',
		callback: function (result) {
			if (result !== null) {
				if (result.length > 0) {
					$.post("../ajax/chip.php?op=inhabilitarDefinitivamente", { "idchip": idchip, "detalle": result }, function (e) {
						bootbox.alert(e);
						tabla.ajax.reload();
					});
				} else {
					new PNotify({
						title: 'Error!',
						text: 'Debe indicar el Motivo para inhabilitar el SIM.',
						type: 'error',
						styling: 'bootstrap3'
					});
				}
			}
		}
	}).on("shown.bs.modal", function (event) {
		$('.bootbox-custom-class').find('.bootbox-input').css("text-transform", "uppercase");
	});

}

init();