var tabla;

//funcion que se ejecuta iniciando
function init() {
    mostarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $.post("../ajax/regiones.php?op=selectRegiones", function (r) {
        $("#idregiones").html(r);
        $("#idregiones").selectpicker('refresh');
    });

    $("#idregiones").on("change", function (e) {
        $.get("../ajax/comunas.php?op=selectComunas", { id: $("#idregiones").val() }, function (r) {
            $("#idcomunas").html(r);
            $("#idcomunas").selectpicker('refresh');
        });

        $.get("../ajax/provincia.php?op=selectProvincia", { id: $("#idregiones").val() }, function (r) {
            $("#idprovincias").html(r);
            $("#idprovincias").selectpicker('refresh');
        });
    });

}


// Otras funciones
function limpiar() {

    $("#idoficinas").val("");
    $("#nombre").val("");
    $("#direccion").val("");
    $("#idregiones").val("");
    $("#idregiones").selectpicker('refresh');
    $("#idprovincias").val("");
    $("#idprovincias").selectpicker('refresh');
    $("#idcomunas").val("");
    $("#idcomunas").selectpicker('refresh');
}

function mostarform(flag) {

    limpiar();
    if (flag) {
        $("#listadooficinas").hide();
        $("#formulariooficinas").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);

    } else {
        $("#listadooficinas").show();
        $("#formulariooficinas").hide();
        $("#op_agregar").show();
        $("#op_listar").hide();
    }

}

function cancelarform() {
    limpiar();
    mostarform(false);
}

function listar() {
    tabla = $('#tbloficinas').dataTable({
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
            url: '../ajax/oficinas.php?op=listar',
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
        url: '../ajax/oficinas.php?op=guardaryeditar',
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

function mostar(idoficinas) {
    $.post("../ajax/oficinas.php?op=mostar", { idoficinas: idoficinas }, function (data, status) {

        data = JSON.parse(data);
        mostarform(true);

        $("#idoficinas").val(data.idoficinas);
        $("#nombre").val(data.nombre);
        $("#direccion").val(data.direccion);
        $("#idregiones").val(data.idregiones);
        $("#idregiones").selectpicker('refresh');
        $.get("../ajax/comunas.php?op=selectComunas", { id: data.idregiones }, function (r) {
            $("#idcomunas").html(r);
            $("#idcomunas").val(data.idcomunas);
            $("#idcomunas").selectpicker('refresh');
        });
        $.get("../ajax/provincia.php?op=selectProvincia", { id: data.idregiones }, function (r) {
            $("#idprovincias").html(r);
            $("#idprovincias").val(data.idprovincias);
            $("#idprovincias").selectpicker('refresh');
        });

    })
}

function eliminar(idoficinas) {
	$.post("../ajax/oficinas.php?op=eliminar", { idoficinas: idoficinas }, function (data, status) {
		bootbox.alert("Oficina quitada");
		mostarform(false);
		tabla.ajax.reload();
	})
}



init();