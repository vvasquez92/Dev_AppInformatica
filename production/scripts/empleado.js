var tabla, tabtelefono, tabvehiculo, tabcomputador, tabtarjeta;

//funcion que se ejecuta iniciando
function init() {
    mostarform(false);
    listar();
    historico();

    $('[data-toggle="tooltip"]').tooltip();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    $("#formPlazoDocumentosEmpleado").on("submit", function (e) {
        guardarFormPlazoDocumentosEmpleado(e);
    });

    $("#formCorreoCorporativo").on("submit", function (e) {
        guardarformCorreoCorporativo(e);
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

    // $("#imagenmuestra").hide();

    $("#tipo_documento").on("change", function (e) {
        $("#num_documento").attr('readonly', false);
        $("#num_documento").inputmask('remove');
        if (this.value == 'RUT') {
            $("#num_documento").inputmask({ "mask": "99.999.999-*" }); //specifying options                                
        } else if (this.value == 'P') {
            $("#num_documento").inputmask({ "mask": "P-*{1,40}" }); //specifying options           
        }
    });


    $("#formCargo").on("submit", function (e) {
        guardarCargo(e);
    });

    $('#modalFormCargo').on('hidden.bs.modal', function (e) {
        limpiarFormCargo();
    });


    $("#num_documento").focusout(function () {

        var regexp = /^\d{2}.\d{3}.\d{3}-[k|K|\d]{1}$/;

        if (($("#tipo_documento").val() == 'RUT' && regexp.test($("#num_documento").val())) ||
            ($("#tipo_documento").val() != 'RUT' && $("#num_documento").val().length > 0)) {

            $.post("../ajax/empleado.php?op=validarExisteNumDocumento",
                { "num_documento": $("#num_documento").val(), "idempleado": $("#idempleado").val() }, function (data) {
                    if (data == null) {
                        $("#btnGuardar").prop("disabled", false);
                        new PNotify({
                            title: 'Correcto!',
                            text: 'Numero de documento es correcto.',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    }
                    else {
                        if (data.condicion == 0) {
                            $("#btnGuardar").prop("disabled", true);
                            new PNotify({
                                title: 'Alerta!',
                                text: 'Numero de documento ya existe, pero está deshabilitado.',
                                type: 'warning',
                                styling: 'bootstrap3'
                            });
                        } else if (data.condicion == 1) {
                            $("#btnGuardar").prop("disabled", true);
                            new PNotify({
                                title: 'Error!',
                                text: 'Numero de documento ya existe.',
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }
                    }
                    /*if (parseInt(data.cantidad) == 0) {
                        $("#btnGuardar").prop("disabled", false);
                        new PNotify({
                            title: 'Correcto!',
                            text: 'Numero de documento es correcto.',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    } else {
                        $("#btnGuardar").prop("disabled", true);
                        new PNotify({
                            title: 'Error!',
                            text: 'Numero de documento ya existe.',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }*/
                }, "json");

        } else {
            $("#btnGuardar").prop("disabled", true);
            new PNotify({
                title: 'Error!',
                text: 'Debe completar el numero.',
                type: 'error',
                styling: 'bootstrap3'
            });

        }

    });

    $('#modalFormCargo').on('show.bs.modal', function (event) {
        $.post("../ajax/departamentos.php?op=selectDepartamentos", function (r) {
            $("#id_departamento").html(r);
            $("#id_departamento").selectpicker('refresh');
        });
    });


    $('#modalDocumentos').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        $("#id_empleado").val(button.data('idempleado'));

        $.post("../ajax/empleado.php?op=mostar", { idempleado: button.data('idempleado') }, function (data) {

            if (data.vencimiento_carnet == null) {
                $('#doc_vencimiento_carnet').prop('readonly', false);
            } else {
                $('#doc_vencimiento_carnet').prop('readonly', true);
            }

            if (data.licencia == null) {
                $('#doc_licencia').prop('readonly', false);
            } else {
                $('#doc_licencia').prop('readonly', true);
            }

            $("#doc_vencimiento_carnet").val(data.vencimiento_carnet);
            $("#doc_licencia").val(data.licencia);

        }, "json");
    });

    $('#modalCorreoCorporativo').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        $("#id_empl").val(button.data('idempleado'));

        $.post("../ajax/empleado.php?op=mostar", { idempleado: button.data('idempleado') }, function (data) {

            $("#doc_correo_corporativo").val(data.doc_correo_corporativo);

        }, "json");
    });

    var ctx = document.getElementById("canvas").getContext("2d");
    var img = new Image();
    img.src = "../files/empleados/noimg.jpg";
    img.onload = function () {
        ctx.drawImage(img, 0, 0, 200, 200);
    };

    $('#check_ti').on('click', function () {

        if ($(this).is(':checked')) {
            $("#div_ti").show('slow');
        } else {
            $("#div_ti").hide('slow');
        }
    });

}


// Otras funciones
function limpiar() {

    $("#idempleado").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#tipo_documento").val("");
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val("");
    $("#num_documento").inputmask('remove');
    $("#num_documento").inputmask({ "mask": "99.999.999-*" }); //specifying options
    $("#num_documento").attr('readonly', true);
    $("#vencimiento_carnet").val("");
    $("#licencia").val("");
    $("#fecha_nac").val("");
    $("#direccion").val("");
    $("#idregiones").val("");
    $("#idregiones").selectpicker('refresh');
    $("#idcomunas").val("");
    $("#idcomunas").selectpicker('refresh');
    $("#movil").val("");
    $("#residencial").val("");
    $("#email").val("");
    $("#email_corporativo").val("");
    $("#idoficina").val("");
    $("#idoficina").selectpicker('refresh');
    $("#iddepartamento").val("");
    $("#iddepartamento").selectpicker('refresh');
    $("#idcargo").val("");
    $("#idcargo").selectpicker('refresh');


    var ctx = document.getElementById("canvas").getContext("2d");   //obtener el elemento canvas.
    ctx.clearRect(0, 0, 200, 200);  //limpiar el elemento canvas
    var img = new Image();  //crea un objeto imagen.
    img.src = "../files/empleados/noimg.jpg";   //le asigno el atributo src con la imagen generica.
    img.onload = function () {    //al cargar la imagen en el objeto imagen la dibujo en el canvas.
        ctx.drawImage(img, 0, 0, 200, 200);
    };

    //detener el stream
    let stream = document.getElementById("video").srcObject;
    if (stream != null) {
        let tracks = stream.getTracks();
        tracks.forEach(function (track) {
            track.stop();
        });
    }

    //Deteniendo la descarga de contenido multimedia
    document.getElementById("video").pause();
    document.getElementById("video").src = "";

    //ocultar el div de la camara.
    $("#div_video").hide('slow');


    $("#imagenactual").val("");



}

function mostarform(flag) {

    limpiar();
    if (flag) {
        $("#listadoempleados").hide();
        $("#formularioempleados").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#listadoempleadoshistorico").hide();

    } else {
        $("#listadoempleados").show();
        $("#formularioempleados").hide();
        $("#op_agregar").show();
        $("#op_listar").hide();
        $("#FichaEmpleado").hide();
        $("#listadoempleadoshistorico").hide();
        $("#op_historico").show();
    }

}

function historico(flag) {

    limpiar();
    if (flag) {
        $("#listadoempleados").hide();
        $("#formularioempleados").hide();
        listarhistoricos();
        $("#listadoempleadoshistorico").show();
        $("#op_historico").hide();
        $("#op_agregar").hide();
        $("#op_listar").show();

    } else {
        $("#listadoempleados").show();
        $("#formularioempleados").hide();
        $("#listadoempleadoshistorico").hide();
        $("#op_historico").show();
        $("#op_agregar").show();
        $("#op_listar").hide();
        $("#FichaEmpleado").hide();
    }
}

function mostrarFichaEmpleado() {

    $("#FichaEmpleado").show();
    $("#listadoempleados").hide();
    $("#op_agregar").hide();
    $("#op_listar").show();
    $("#listadoempleadoshistorico").hide();
}

function cancelarform() {
    limpiar();
    mostarform(false);
}

function listar() {
    tabla = $('#tblempleados').dataTable({
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
            url: '../ajax/empleado.php?op=listar',
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

function listarhistoricos() {
    tabla = $('#tblempleadoshistorico').dataTable({
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
            url: '../ajax/empleado.php?op=listarhistorico',
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
        url: '../ajax/empleado.php?op=guardaryeditar',
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

function mostar(idempleado) {
    $.post("../ajax/empleado.php?op=mostar", { idempleado: idempleado }, function (data, status) {
        data = JSON.parse(data);
        mostarform(true);
        $("#idempleado").val(data.idempleado);
        $("#nombre").val(data.nombre);
        $("#apellido").val(data.apellido);
        $("#tipo_documento").val(data.tipo_documento).trigger('change');
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").removeAttr("data-inputmask");
        $("#num_documento").inputmask('remove');
        if ($("#tipo_documento").val() == 'RUT') {
            $("#num_documento").inputmask({ "mask": "99.999.999-*" }); //specifying options                                
        } else if ($("#tipo_documento").val() == 'P') {
            $("#num_documento").inputmask({ "mask": "P-*{1,40}" }); //specifying options           
        }
        $("#num_documento").val(data.num_documento);
        $("#vencimiento_carnet").val(data.vencimiento_carnet);
        $("#licencia").val(data.licencia);
        $("#fecha_nac").val(data.fecha_nac);
        $("#direccion").val(data.direccion);
        $("#idregiones").val(data.idregiones);
        $("#idregiones").selectpicker('refresh');
        $.get("../ajax/comunas.php?op=selectComunas", { id: data.idregiones }, function (r) {
            $("#idcomunas").html(r);
            $("#idcomunas").val(data.idcomunas);
            $("#idcomunas").selectpicker('refresh');
        });
        $("#movil").val(data.movil);
        $("#residencial").val(data.residencial);
        $("#email").val(data.email);
        $("#email_corporativo").val(data.email_corporativo);
        $("#idoficina").val(data.idoficinas);
        $("#idoficina").selectpicker('refresh');
        $("#iddepartamento").val(data.iddepartamento);
        $("#iddepartamento").selectpicker('refresh');
        $.post("../ajax/cargos.php?op=selectCargos", { iddepartamento: data.iddepartamento }, function (r) {
            $("#idcargo").html(r);
            $("#idcargo").val(data.idcargo);
            $("#idcargo").selectpicker('refresh');
        });

        $("#imagenactual").val(data.imagen);

        if (data.imagen != null && data.imagen != "") {

            var ctx = document.getElementById("canvas").getContext("2d");
            ctx.clearRect(0, 0, 200, 200);
            var img = new Image();
            img.src = "../files/empleados/" + data.imagen;
            img.onload = function () {
                ctx.drawImage(img, 0, 0, 200, 200);
            };
        }

    });
}

function desactivar(idempleado) {

    bootbox.confirm("Esta seguro que quiere inhabilitar el usuario?", function (result) {

        if (result) {
            $.post("../ajax/empleado.php?op=verAsignacionesEmpleado", { "idempleado": idempleado }, function (data) {

                if (parseInt(data.asigMovil) > 0 || parseInt(data.asigVehic) > 0 || parseInt(data.asigComp) > 0 || parseInt(data.asigTarj) > 0) {

                    var mensaje = "No puede inabilitarse el empleado porque tiene asignado algun implemento de trabajo:";
                    mensaje += (parseInt(data.asigMovil) > 0) ? "<br /><br /> -Tiene Asignado un Teléfono Móvil." : "";
                    mensaje += (parseInt(data.asigVehic) > 0) ? "<br /><br /> -Tiene Asignado un Vehículo." : "";
                    mensaje += (parseInt(data.asigComp) > 0) ? "<br /><br /> -Tiene Asignado un Computador." : "";
                    mensaje += (parseInt(data.asigTarj) > 0) ? "<br /><br /> -Tiene Asignado una Tarjeta de Acceso." : "";
                    bootbox.alert(mensaje);

                } else {
                    $.post("../ajax/empleado.php?op=desactivar", { idempleado: idempleado }, function (e) {
                        bootbox.alert(e);
                        tabla.ajax.reload();
                    });
                }
            }, "json");


        }
    });
}

function activar(idempleado) {

    bootbox.confirm("Esta seguro que quiere habilitar el usuario?", function (result) {
        if (result) {
            $.post("../ajax/empleado.php?op=activar", { idempleado: idempleado }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            })
        }
    })
}

function agregarDepartamento() {

    bootbox.prompt({
        title: "Ingrese el nombre del Departamento",
        className: 'bootbox-custom-class',
        callback: function (result) {
            if (result) {
                $.ajax({
                    // En data puedes utilizar un objeto JSON, un array o un query string
                    data: { "nombre": result },
                    type: "POST",
                    url: "../ajax/departamentos.php?op=guardaryeditar"
                })
                    .done(function (data, textStatus, jqXHR) {
                        if (console && console.log) {
                            console.log("La solicitud se ha completado correctamente.");
                        }
                        bootbox.alert(data);
                        $.post("../ajax/departamentos.php?op=selectDepartamentos", function (r) {
                            $("#iddepartamento").html(r);
                            $("#iddepartamento").selectpicker('refresh');
                        });
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        if (console && console.log) {
                            console.log("La solicitud a fallado: " + textStatus);
                        }
                    });
            }
        }
    }).on("shown.bs.modal", function (event) {
        $('.bootbox-custom-class').find('.bootbox-input').attr('maxlength', 50);
        $('.bootbox-custom-class').find('.bootbox-input').css("text-transform", "uppercase");
    });
}

function guardarCargo(e) {

    e.preventDefault();

    $.ajax({
        // En data puedes utilizar un objeto JSON, un array o un query string
        data: { idcargos: "", "iddepartamento": $("#id_departamento").val(), "nombre": $("#nombre_cargo").val() },
        type: "POST",
        url: "../ajax/cargos.php?op=guardaryeditar",
        beforeSend: function () {
            $("#btnGuardarCargo").prop("disabled", true);
            $('.modal-body').css('opacity', '.5');
        }
    })
        .done(function (data, textStatus, jqXHR) {
            if (console && console.log) {
                console.log("La solicitud se ha completado correctamente.");
            }
            bootbox.alert(data);

            $.post("../ajax/cargos.php?op=selectCargos", { iddepartamento: $("#iddepartamento").val() }, function (r) {
                $("#idcargo").html(r);
                $("#idcargo").selectpicker('refresh');
            });

            $("#btnGuardarCargo").prop("disabled", false);
            $('.modal-body').css('opacity', '');
            $('#modalFormCargo').modal('toggle');
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

    limpiarFormCargo();


}

function limpiarFormCargo() {
    $("#idcargos").val("");
    $("#id_departamento").val("");
    $("#id_departamento").selectpicker('refresh');
    $("#nombre_cargo").val("");
}

function verFichaEmpleado(idempleado) {

    $.post("../ajax/empleado.php?op=mostar", { "idempleado": idempleado }, function (data) {
        $("#nombre_empleado").html(data.nombre);
        $("#apellido_empleado").html(data.apellido);
        if (data.imagen == null || data.imagen == "") {
            $("#imagen_empleado").attr("src", "../files/empleados/noimg.jpg");
        } else {
            $("#imagen_empleado").attr("src", "../files/empleados/" + data.imagen);
        }
        var tipo_documento_empleado = "";
        if (data.tipo_documento == "P") {
            tipo_documento_empleado = "Pasaporte";
        } else if (data.tipo_documento == "O") {
            tipo_documento_empleado = "Otro";
        } else {
            tipo_documento_empleado = "RUT";
        }
        $("#tipo_documento_empleado").html(tipo_documento_empleado);
        $("#documento_empleado").html(data.num_documento);
        $("#movil_empleado").html(data.movil);
        $("#residencial_empleado").html(data.residencial);
        $("#email_empleado").html(data.email);
        $("#direccion_empleado").html('<address>' + data.direccion + '</address>');

        $("#departamento_empleado").html(data.nombre_departamento);
        $("#cargo_empleado").html(data.nombre_cargo);
        var email_corporativo = (data.email_corporativo == null) ? "Sin Correo" : data.email_corporativo;
        $("#correo_corporativo").html(email_corporativo);

    }, "json");

    tabtelefono = $('#tbltabtelefono').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        searching: false,
        "language": {
            "emptyTable": "No tiene Teléfono Asignado"
        },
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asignacion.php?op=mostrarAsignaciones',
            type: "POST",
            dataType: "json",
            data: { "idempleado": idempleado },
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginacion 10 items
        "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    tabvehiculo = $('#tbltabvehiculo').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        searching: false,
        "language": {
            "emptyTable": "No tiene Vehículo Asignado"
        },
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asignacionvehiculo.php?op=mostrarAsignaciones',
            type: "POST",
            dataType: "json",
            data: { "idempleado": idempleado },
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginacion 10 items
        "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    tabcomputador = $('#tbltabcomputador').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        searching: false,
        "language": {
            "emptyTable": "No tiene Computador Asignado"
        },
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asigcomputador.php?op=mostrarAsignaciones',
            type: "POST",
            dataType: "json",
            data: { "idempleado": idempleado },
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginacion 10 items
        "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    tabtarjeta = $('#tbltabtarjeta').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        searching: false,
        "language": {
            "emptyTable": "No tiene Tarjeta Asignada"
        },
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/asigtarjeta.php?op=mostrarAsignaciones',
            type: "POST",
            dataType: "json",
            data: { "idempleado": idempleado },
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginacion 10 items
        "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    mostrarFichaEmpleado();

}

function guardarFormPlazoDocumentosEmpleado(e) {

    e.preventDefault();

    $.ajax({
        // En data puedes utilizar un objeto JSON, un array o un query string
        data: $("#formPlazoDocumentosEmpleado").serialize(),
        type: "POST",
        url: "../ajax/empleado.php?op=guardarPlazoDocumentosVehiculo",
        beforeSend: function () {
            $("#btnGuardarModalDoc").prop("disabled", true);
            $('.modal-body').css('opacity', '.5');
        }
    })
        .done(function (data, textStatus, jqXHR) {
            if (console && console.log) {
                console.log("La solicitud se ha completado correctamente.");
            }
            tabla.ajax.reload();
            bootbox.alert(data);
            $("#btnGuardarModalDoc").prop("disabled", false);
            $('.modal-body').css('opacity', '');
            $('#modalDocumentos').modal('toggle');
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

}

function guardarformCorreoCorporativo(e) {

    e.preventDefault();

    $.ajax({
        // En data puedes utilizar un objeto JSON, un array o un query string
        data: $("#formCorreoCorporativo").serialize(),
        type: "POST",
        url: "../ajax/empleado.php?op=ActualizarCorreoCorporativo",
        beforeSend: function () {
            $("#btnGuardarModalCorreo").prop("disabled", true);
            $('.modal-body').css('opacity', '.5');
        }
    })
        .done(function (data, textStatus, jqXHR) {
            if (console && console.log) {
                console.log("La solicitud se ha completado correctamente.");
            }
            tabla.ajax.reload();
            bootbox.alert(data);
            $("#btnGuardarModalCorreo").prop("disabled", false);
            $('.modal-body').css('opacity', '');
            $('#modalCorreoCorporativo').modal('toggle');
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

}

init();