/* global bootbox */
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

	$.post("../ajax/tcomputador.php?op=selecttcomputador", function (r) {
		$("#tcomputador").html(r);
		$("#tcomputador").selectpicker('refresh');
	});

	$.post("../ajax/marcacom.php?op=selectmarcacom", function (r) {
		$("#idmarca").html(r);
		$("#idmarca").selectpicker('refresh');
	});


	$(document).on('change', 'input[type="file"]', function () {
		// this.files[0].size recupera el tamaño del archivo
		// alert(this.files[0].size);

		var fileName = this.files[0].name;
		var fileSize = this.files[0].size;

		if (fileSize > 5242880) {
			new PNotify({
				title: 'Error!',
				text: 'El archivo no debe superar 5 MB.',
				type: 'error',
				styling: 'bootstrap3'
			});
			this.value = '';
			this.files[0].name = '';
		} else {
			// recuperamos la extensión del archivo
			var ext = fileName.split('.').pop();

			// console.log(ext);
			switch (ext) {

				case 'pdf':

					break;

				default:
					new PNotify({
						title: 'Error!',
						text: 'El archivo de tipo ' + ext + ' no es valido.',
						type: 'error',
						styling: 'bootstrap3'
					});
					this.value = ''; // reset del valor
					this.files[0].name = '';
			}
		}
	});

}


// Otras funciones
function limpiar() {

	$("#idcomputador").val("");
	$("#tcomputador").val("");
	$("#tcomputador").selectpicker('refresh');
	$("#idmarca").val("");
	$("#idmarca").selectpicker('refresh');
	$("#modelo").val("");
	$("#estado").val("");
	$("#estado").selectpicker('refresh');
	$("#serial").val("");
	$("#maclan").val("");
	$("#macwifi").val("");
	$("#observaciones").val("");
	$("#factura").val("");
	$("#factura_actual").val("");
	$("#fvencimiento_garantia").val("");
	$("#previa_factura").html("");
	$("#precio").val("");
}

function mostarform(flag) {
	limpiar();
	if (flag) {
		$("#listadocomputadores").hide();
		$("#formulariocomputadores").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#listadocomputadores").show();
		$("#formulariocomputadores").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}
}

function cancelarform() {
	limpiar();
	mostarform(false);
}

function listar() {
	tabla = $('#tblcomputadores').dataTable({
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
			url: '../ajax/computador.php?op=listar',
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
	$.post("../ajax/computador.php?op=cargaTotales", function(data,status){
		data = JSON.parse(data);

		$("#lblAsignados").html("TOTAL ASIGNADOS: " + data.libres);
		$("#lblLibres").html("TOTAL SIN ASIGNAR: " + data.asignados);
		$("#lblUsados").html("TOTAL USADOS: " + data.usados);
		$("#lblNuevos").html("TOTAL NUEVOS: " + data.nuevos);

	})
}

function guardaryeditar(e) {

	e.preventDefault();


	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: '../ajax/computador.php?op=guardaryeditar',
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

function mostar(idcomputador) {
	$.post("../ajax/computador.php?op=mostar", { idcomputador: idcomputador }, function (data, status) {

		data = JSON.parse(data);
		mostarform(true);

		$("#idcomputador").val(data.idcomputador);
		$("#tcomputador").val(data.tcomputador);
		$("#tcomputador").selectpicker('refresh');
		$("#idmarca").val(data.idmarca);
		$("#idmarca").selectpicker('refresh');
		$("#modelo").val(data.modelo);
		$("#estado").val(data.estado);
		$("#estado").selectpicker('refresh');
		$("#serial").val(data.serial);
		$("#maclan").val(data.maclan);
		$("#macwifi").val(data.macwifi);
		$("#factura_actual").val(data.factura);
		$("#fvencimiento_garantia").val(data.fvencimiento_garantia);
		$("#estado").val(data.estado);
		$("#estado").selectpicker('refresh');
		$("#observaciones").val(data.observaciones);
		$("#precio").val(data.precio);
		if (data.factura != "" && data.factura != null) {
			$("#previa_factura").html('<button class="btn btn-secondary" onclick="window.open(\'../files/facturascomputador/' + data.factura + '\');return false;"  ><i class="fa fa-file-pdf-o"></i></button>');
		}
	});
}

function desactivar(idcomputador,disponible) {
	//disponible = 0 -> asignado | disponible = 1 -> sin asignar //
	
	var mensaje;
	if (disponible == 0){
		mensaje = 'Este dispositivo está asignado, ¿Está seguro que quiere inhabilitarlo?';
	}
	if (disponible == 1){
		mensaje = '¿Está seguro que quiere inhabilitar el dispositivo?';
	}

	bootbox.confirm(mensaje, function (result) {
		if (result) {
			$.post("../ajax/computador.php?op=desactivar", { idcomputador: idcomputador }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	});
}

function activar(idcomputador) {

	bootbox.confirm("SEGURO QUIERE HABILITAR EL COMPUTADOR?", function (result) {
		if (result) {
			$.post("../ajax/computador.php?op=activar", { idcomputador: idcomputador }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	});
}


init();