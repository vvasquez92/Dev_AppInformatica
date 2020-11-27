var tabla;

//funcion que se ejecuta iniciando
function init() {
	mostarform(true);

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$.post("../ajax/oficinas.php?op=selectOficinas", function (r) {
		$("#idoficina").html(r);
		$("#idoficina").selectpicker('refresh');
	});

	$.post("../ajax/departamentos.php?op=selectDepartamentos", function (r) {
		$("#iddepartamento").html(r);
		$("#iddepartamento").selectpicker('refresh');
	});

	$("#iddepartamento").on("change", function (e) {
		$.post("../ajax/cargos.php?op=selectCargos", { iddepartamento: $("#iddepartamento").val() }, function (r) {
			$("#idcargo").html(r);
			$("#idcargo").selectpicker('refresh');
		});
	});

	$.post("../ajax/regiones.php?op=selectRegiones", function (r) {
		$("#idregiones").html(r);
		$("#idregiones").selectpicker('refresh');
	});


	$("#idregiones").on("change", function (e) {
		$.get("../ajax/comunas.php?op=selectComunas", { id: $("#idregiones").val() }, function (r) {
			$("#idcomunas").html(r);
			$("#idcomunas").selectpicker('refresh');
		});
	});

}


// Otras funciones
function limpiar() {

	$("#nombre").val("");
	$("#apellido").val("");
	$("#tipo_documento").val("");
	$("#tipo_documento").selectpicker('refresh');
	$("#num_documento").val("");
	$("#fecha_nac").val("");
	$("#direccion").val("");
	$("#idregiones").val("");
	$("#idregiones").selectpicker('refresh');
	$("#idcomunas").val("");
	$("#idcomunas").selectpicker('refresh');
	$("#movil").val("");
	$("#residencial").val("");
	$("#email").val("");
	$("#idoficina").val("");
	$("#idoficina").selectpicker('refresh');
	$("#iddepartamento").val("");
	$("#iddepartamento").selectpicker('refresh');
	$("#idcargo").val("");
	$("#idcargo").selectpicker('refresh');

}

function mostarform(flag) {

	limpiar();
	if (flag) {
		$("#agradecer").hide();
		$("#formularioempleados").show();
		$("#btnGuardar").prop("disabled", false);

	} else {
		$("#agradecer").show();
		$("#formularioempleados").hide();
	}
}

function cancelarform() {
	mostarform(false);
}


function guardaryeditar(e) {
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: '../ajax/empleado.php?op=guardar',
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			bootbox.alert(datos);
			mostarform(false);
		}
	});
	limpiar();
}

init();