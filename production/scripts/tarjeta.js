var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();
	cargaTotales();

	$('[data-toggle="tooltip"]').tooltip();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

	$.post("../ajax/nivel.php?op=SLNivel", function (r) {
		$("#idnivel").html(r);
		$("#idnivel").selectpicker('refresh');
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
		$("#listadotarjetas").hide();
		$("#formulariotarjetas").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadotarjetas").show();
		$("#formulariotarjetas").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tbltarjetas').dataTable({
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
			url: '../ajax/tarjeta.php?op=listar',
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

function cargaTotales(){
	$.post("../ajax/tarjeta.php?op=cargaTotales", function(data,status){
		data = JSON.parse(data);

		$("#lblAsignados").html("TOTAL ASIGNADOS: " + data.libres);
		$("#lblLibres").html("TOTAL SIN ASIGNAR: " + data.asignados);

	})
}

function guardaryeditar(e) {
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: '../ajax/tarjeta.php?op=guardaryeditar',
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

function mostar(idtarjeta) {
	$.post("../ajax/tarjeta.php?op=mostar", { idtarjeta: idtarjeta }, function (data, status) {
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

function desactivar(idtarjeta, disponible) {
	//disponible = 0 -> asignado | disponible = 1 -> sin asignar //

	var mensaje;
	if (disponible == 0) {
		mensaje = 'Esta tarjeta está asignada, ¿Está seguro que quiere inhabilitarla?';
	}
	if (disponible == 1) {
		mensaje = '¿Está seguro que quiere inhabilitar la Tarjeta ID?';
	}

	bootbox.confirm(mensaje, function (result) {
		if (result) {
			$.post("../ajax/tarjeta.php?op=desactivar", { idtarjeta: idtarjeta }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			})
		}
	})
}

function activar(idtarjeta) {

	bootbox.confirm("SEGURO QUE DESEA HABILITAR LA TARJETA ID?", function (result) {
		if (result) {
			$.post("../ajax/tarjeta.php?op=activar", { idtarjeta: idtarjeta }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			})
		}
	})
}


init();