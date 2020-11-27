var tabla;
var tablahist;

//funcion que se ejecuta iniciando
function init() {

    mostarform(false);
    listar();
    cargaProveedorGPS();
    $("#divGPS").hide();

    $("#idmarca").on("change", function(e) {
        $.get("../ajax/modelove.php?op=selectmodelove", { id: $("#idmarca").val() }, function(r) {
            $("#idmodelo").html(r);
            $("#idmodelo").selectpicker('refresh');
        });
    });

    $('[data-toggle="tooltip"]').tooltip();

    $("#formformularioVehiculos").on("submit", function(e) {
        guardaryeditar(e);
    });

    $("#formPlazoDocumentosVehiculo").on("submit", function(e) {
        guardarFormPlazoDocumentosVehiculo(e);
    });

    $.post("../ajax/tvehiculo.php?op=selecttvehiculo", function(r) {
        $("#tvehiculo").html(r);
        $("#tvehiculo").selectpicker('refresh');
    });

    $.post("../ajax/marcave.php?op=selectmarcave", function(r) {
        $("#idmarca").html(r);
        $("#idmarca").selectpicker('refresh');
    });

    $("#tieneGPS").on("change", function(e) {
        var tieneDispGps = $("#tieneGPS").val();;

        if (tieneDispGps == 1) {
            $("#divGPS").show();
        } else {
            $("#divGPS").hide();
        }
    });

    $('#modalDocumentos').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);
        $("#id_vehiculo").val(button.data('idvehiculo'));

        $.post("../ajax/vehiculo.php?op=mostar", { idvehiculo: button.data('idvehiculo') }, function(data) {

            if (data.gases == null) {
                $('#doc_gases').prop('readonly', false);
            } else {
                $('#doc_gases').prop('readonly', true);
            }
            if (data.tecnica == null) {
                $('#doc_tecnica').prop('readonly', false);
            } else {
                $('#doc_tecnica').prop('readonly', true);
            }
            if (data.circulacion == null) {
                $('#doc_circulacion').prop('readonly', false);
            } else {
                $('#doc_circulacion').prop('readonly', true);
            }

            $("#doc_gases").val(data.gases);
            $("#doc_tecnica").val(data.tecnica);
            $("#doc_circulacion").val(data.circulacion);
        }, "json");
    });

    $("#tvehiculo").on("change", function(e) {
        if (this.value == 1 || this.value == 2 || this.value == 3 || this.value == 4 || this.value == 5 || this.value == 8 || this.value == 9) {
            $("#patente").inputmask({ "mask": "AAAA-99-9" }); //specifying options  
        } else {
            $("#patente").inputmask('remove');
        }
    });
}


// Otras funciones
function limpiar() {

    $("#idvehiculo").val("");
    $("#tvehiculo").val("");
    $("#tvehiculo").selectpicker('refresh');
    $("#idmarca").val("");
    $("#idmarca").selectpicker('refresh');
    $("#idmodelo").val("");
    $("#idmodelo").selectpicker('refresh');
    $("#ano").val("");
    $("#patente").val("");
    $("#serialmotor").val("");
    $("#serialcarroceria").val("");
    $("#estado").val("");
    $("#estado").selectpicker('refresh');
    $("#gases").val("");
    $("#tecnica").val("");
    $("#circulacion").val("");
    $("#observaciones").val("");
    $("#kilometraje").val("");
    $("#tieneGPS").val("");
    $("#tieneGPS").selectpicker('refresh');
    $("#instalaGPS").val("");
    $("#sProvGpsNew").val("");
    $("#sProvGpsNew").selectpicker('refresh');
    $("#divGPS").hide();
}

function mostarform(flag) {

    limpiar();
    if (flag == true) {
        $("#listadovehiculos").hide();
        $("#formulariovehiculos").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#op_hist").hide();
        $("#listadovehiculoshist").hide();
    } else if (flag == false) {
        $("#listadovehiculos").show();
        $("#formulariovehiculos").hide();
        $("#op_agregar").show();
        $("#op_listar").hide();
        $("#op_hist").show();
        $("#listadovehiculoshist").hide();
    } else if (flag == 99) {
        $("#listadovehiculos").hide();
        $("#formulariovehiculos").hide();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#op_hist").hide();
        $("#listadovehiculoshist").show();
        listarhist();
    }


}

function cancelarform() {
    limpiar();
    mostarform(false);
}

function listar() {

    let botones = [];
    if ($("#administrador").val() == 1) {
        botones = ['copyHtml5', 'print', 'excelHtml5', 'csvHtml5', 'pdf'];
    }

    tabla = $('#tblvehiculos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: botones,
        "ajax": {
            url: '../ajax/vehiculo.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion 10 items
        "order": [
                [4, "asc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function listarhist() {

    let botones = [];
    if ($("#administrador").val() == 1) {
        botones = ['copyHtml5', 'print', 'excelHtml5', 'csvHtml5', 'pdf'];
    }

    tablahist = $('#tblvehiculoshist').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: botones,
        "ajax": {
            url: '../ajax/vehiculo.php?op=listarhist',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion 10 items
        "order": [
                [3, "asc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function guardaryeditar(e) {

    var tieneGPS = $("#tieneGPS").val();
    var fechaGPS = $("#instalaGPS").val();
    var provGPS = $("#sProvGpsNew").val();

    if (tieneGPS == 1) {
        if (fechaGPS == '' || provGPS == null) {
            e.preventDefault();
            bootbox.alert("No se pudo guardar. Debe ingresar la fecha de instalacion y proveedor GPS");
        } else {
            e.preventDefault();
            var formData = new FormData($("#formformularioVehiculos")[0]);

            $.ajax({
                url: '../ajax/vehiculo.php?op=guardaryeditar',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos) {
                    //$.get('https://api.telegram.org/bot451016925:AAEHLdlYx-Na35mt8ODT5RCsRkLRo_khc3Q/sendMessage?chat_id=431473212&text=' + datos + '');
                    bootbox.alert(datos);
                    mostarform(false);
                    tabla.ajax.reload();
                }
            });
            limpiar();
        }
    } else {
        e.preventDefault();
        var formData = new FormData($("#formformularioVehiculos")[0]);

        $.ajax({
            url: '../ajax/vehiculo.php?op=guardaryeditar',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos) {
                //$.get('https://api.telegram.org/bot451016925:AAEHLdlYx-Na35mt8ODT5RCsRkLRo_khc3Q/sendMessage?chat_id=431473212&text=' + datos + '');
                bootbox.alert(datos);
                mostarform(false);
                tabla.ajax.reload();
            }
        });
        limpiar();
    }
}

function mostar(idvehiculo) {
    $.post("../ajax/vehiculo.php?op=mostar", { idvehiculo: idvehiculo }, function(data, status) {

        data = JSON.parse(data);

        mostarform(true);

        $("#idvehiculo").val(data.idvehiculo);
        $("#tvehiculo").val(data.tvehiculo);
        $("#tvehiculo").selectpicker('refresh');
        if (data.tvehiculo == 1 || data.tvehiculo == 2 || data.tvehiculo == 3 || data.tvehiculo == 4 || data.tvehiculo == 5 || data.tvehiculo == 8 || data.tvehiculo == 9) {
            $("#patente").inputmask({ "mask": "AAAA-99-9" }); //specifying options  
        } else {
            $("#patente").inputmask('remove');
        }
        $("#idmarca").val(data.idmarca);
        $("#idmarca").selectpicker('refresh');
        $.get("../ajax/modelove.php?op=selectmodelove", { id: data.idmarca }, function(r) {
            $("#idmodelo").html(r);
            $("#idmodelo").val(data.idmodelo);
            $("#idmodelo").selectpicker('refresh');
        });
        $("#ano").val(data.ano);
        $("#kilometraje").val(data.kilometraje);
        $("#patente").val(data.patente);
        $("#serialmotor").val(data.serialmotor);
        $("#serialcarroceria").val(data.serialcarroceria);
        $("#gases").val(data.gases);
        $("#tecnica").val(data.tecnica);
        $("#circulacion").val(data.circulacion);
        $("#estado").val(data.estado);
        $("#estado").selectpicker('refresh');
        $("#observaciones").val(data.observaciones);

        $("#tieneGPS").val(data.tiene_gps);
        $("#tieneGPS").selectpicker('refresh');

        if (data.tiene_gps == 1) {
            $("#divGPS").show();
        } else {
            $("#divGPS").hide();
        }
        $("#instalaGPS").val(data.fh_instalacion);
        $("#sProvGpsNew").val(data.id_proveedor);
        $("#sProvGpsNew").selectpicker('refresh');

    });
}

function desactivar(idvehiculo, disponible) {
    //disponible = 0 -> asignado | disponible = 1 -> sin asignar //

    var mensaje;
    if (disponible == 0) {
        mensaje = 'Este vehículo está asignado, por favor inhabilite la asignación para realizar este proceso';
        bootbox.alert(mensaje);
    }
    if (disponible == 1 || disponible == 2) {
        mensaje = '¿Está seguro que quiere inhabilitar el vehículo?';

        bootbox.confirm(mensaje, function(result) {
            if (result) {
                $.post("../ajax/vehiculo.php?op=desactivar", { idvehiculo: idvehiculo }, function(e) {
                    bootbox.alert(e);
                    listar();
                    tabla.ajax.reload();
                });
            }
        });
    }


}

function activar(idvehiculo) {

    bootbox.confirm("Está seguro que quiere habilitar el vehiculo?", function(result) {
        if (result) {
            $.post("../ajax/vehiculo.php?op=activar", { idvehiculo: idvehiculo }, function(e) {
                bootbox.alert(e);
                listarhist();
                tabla.ajax.reload();
            });
        }
    });
}

function guardarFormPlazoDocumentosVehiculo(e) {

    e.preventDefault();

    $.ajax({
            // En data puedes utilizar un objeto JSON, un array o un query string
            data: $("#formPlazoDocumentosVehiculo").serialize(),
            type: "POST",
            url: "../ajax/vehiculo.php?op=guardarPlazoDocumentosVehiculo",
            beforeSend: function() {
                $("#btnGuardarModal").prop("disabled", true);
                $('.modal-body').css('opacity', '.5');
            }
        })
        .done(function(data, textStatus, jqXHR) {
            if (console && console.log) {
                console.log("La solicitud se ha completado correctamente.");
            }
            tabla.ajax.reload();
            bootbox.alert(data);
            $("#btnGuardarModal").prop("disabled", false);
            $('.modal-body').css('opacity', '');
            $('#modalDocumentos').modal('toggle');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

}

function infoGPS(idvehiculo) {
    $('#id_vehiculo').val(idvehiculo);

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear() + "-" + (month) + "-" + (day);

    $('#fhInstalacionGPS').val(today);

    $.post("../ajax/vehiculo.php?op=cargaProveedorGPS", {}, function(r) {
        $("#sProvGps").html(r);
        $("#sProvGps").selectpicker('refresh');
    });

    $('#btnDispGPS').trigger("click");
}

$("#GPSform").on("submit", function(e) {
    e.preventDefault();

    var id_vehiculo = $("#id_vehiculo").val();
    var fhInstalacionGPS = $("#fhInstalacionGPS").val();
    var sProvGps = $("#sProvGps").val();

    var formData = new FormData();
    formData.append("id_vehiculo", id_vehiculo);
    formData.append("fhInstalacionGPS", fhInstalacionGPS);
    formData.append("sProvGps", sProvGps);

    $.ajax({
        url: '../ajax/vehiculo.php?op=guardaDispositivoGPS',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Dispositivo agregado con éxito',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                mostarform(false);
                listar();
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'No se pudo agregar el dispositivo',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                mostarform(false);
                listar();
                $(".close").trigger("click");
            }
        }
    });
});

function cargaProveedorGPS() {
    $.post("../ajax/vehiculo.php?op=cargaProveedorGPS", {}, function(r) {
        $("#sProvGpsNew").html(r);
        $("#sProvGpsNew").selectpicker('refresh');
    });
}

function taller(id_vehiculo) {
    $("#idveh").val(id_vehiculo);
    $("#txtKmsActual").val("");
    $("#txaMotivo").val("");
    $('#btnInspeccion').trigger("click");
}

$("#Inspeccionform").on("submit", function(e) {
    e.preventDefault();

    var id_vehiculo = $("#idveh").val();
    var kmsAct = $("#txtKmsActual").val();
    var mot = $("#txaMotivo").val();

    var formData = new FormData();
    formData.append("id_vehiculo", id_vehiculo);
    formData.append("kilometraje", kmsAct);
    formData.append("motivo", mot);

    $.ajax({
        url: '../ajax/vehiculo.php?op=inspeccion',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Inspección agregada con éxito',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                mostarform(false);
                listar();
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'No se pudo agregar la inspección',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                mostarform(false);
                listar();
                $(".close").trigger("click");
            }
        }
    });
});


init();