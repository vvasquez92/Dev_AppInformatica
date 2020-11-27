var tabla;
var tablaHist;
var tablaMensajes;

function init() {

    mostrarform(false);

    listarVehiculosGestion();

    $('[data-toggle="tooltip"]').tooltip();

    $("#formGestion").on("submit", function(e) {
        guardarFormGestion(e);
    });

    $('#modalGestion').on('hidden.bs.modal', function(e) {
        limpiarForm();
    });

    $('#modalGestion').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $("#idgestion_ve").val(button.data('idgestion_ve'));
        $("#idvehiculo").val(button.data('idvehiculo'));
    });

    $('#check_finGestion').on("change", function(e) {

        if (this.checked != true) {
            $("#div_estado_final").hide();
            $('#estado_final').prop('required', false);
        } else {
            $('#estado_final').prop('required', true);
            $('#estado_final').val("");
            $("#div_estado_final").show();
        }
    });

}

function muestraHistorico() {
    $("#listadovehiculosgestion").hide();
    $("#op_actual").show();
    $("#op_historico").hide();
    listarVehiculosGestionHist();
    $("#listadovehiculosgestionHist").show();
}

function mostrarform(bandera) {

    if (bandera) {
        $("#listadovehiculosgestion").hide();
        $("#verGestion").show();
        $("#boton_regresar").hide();
        $("#op_actual").show();
        $("#op_historico").hide();
        $("#listadovehiculosgestionHist").hide();
    } else {
        $("#listadovehiculosgestion").show();
        $("#listadovehiculosgestionHist").hide();
        $("#verGestion").hide();
        $("#boton_regresar").hide();
        $("#op_historico").show();
        $("#op_actual").hide();
    }
}

function limpiarForm() {
    $("#idgestion_ve").val("");
    $("#idvehiculo").val("");
    $("#titulo").val("");
    $("#descripcion").val("");
    $('#check_finGestion').prop('checked', false); // Unchecks it
    $('#check_finGestion').trigger("change");
    $('#estado_final').val("");
}


function mostrar_gestion(idgestion_ve) {

    //datos de la gestion del vehiculo
    $.post("../ajax/gestionvehiculo.php?op=mostrar", { idgestion_ve: idgestion_ve }, function(data, status) {
        data = JSON.parse(data);
        $("#tipo_gestion").html(data.tipo_gestion);
        $("#fecha_creacion_gestion").html(data.created_time);
        $("#fecha_actualizacion_gestion").html(data.updated_time);
        var disp = "";
        //datos del vehiculo.
        $.post("../ajax/vehiculo.php?op=mostar", { idvehiculo: data.idvehiculo }, function(dato, status) {
            dato = JSON.parse(dato);
            if (parseInt(dato.disponible) == 0) {
                disp = '<span class="label bg-red">ASIGNADO</span>';
            } else if (parseInt(dato.disponible) == 1) {
                disp = '<span class="label bg-green">SIN ASIGNAR</span>';
            } else if (parseInt(dato.disponible) == 2) {
                disp = '<span class="label bg-orange">NO DISPONIBLE</span>';
            }

            $("#vehiculo").html(dato.nombre_marca + ' ' + dato.nombre_modelo);
            $("#tipo").html(dato.tipo_vehiculo);
            $("#anio").html(dato.ano);
            $("#patente").html(dato.patente);
            $("#kilometraje").html(dato.kilometraje);
            $("#disponible").html(disp);
            $("#estado").html((parseInt(dato.estado)) ? '<span class="label bg-green">NUEVO</span>' : '<span class="label bg-red">USADO</span>');
            $("#condicion").html((parseInt(dato.condicion)) ? '<span class="label bg-green">HABILITADO</span>' : '<span class="label bg-red">INHABILITADO</span>');
        });

    });


    /*
        $.post("../ajax/mensajesvehiculo.php?op=listar", { idgestion_ve: idgestion_ve }, function (data, status) {

            data = JSON.parse(data);
            var str = "";
            if (data.TotalRecords == 0) {
                $("#listaGestion").html("No se ha realizado Gestión a este Vehículo.");
            } else {
                for (var i = 0; i < data.TotalRecords; i++) {

                    str += ' <li>' +
                        '<img src="../files/usuarios/' + data.registros[i].imagen + '" class="avatar" alt="Avatar">' +
                        '<div class="message_date"><h3 class="date text-info">' + data.registros[i].dia + '</h3>' +
                        '<p class="month">' + data.registros[i].mes + '</p>' +
                        '</div>' +
                        '<div class="message_wrapper">' +
                        '<h4 class="heading">' + data.registros[i].nombre + ', ' + data.registros[i].apellido + ' - ' + data.registros[i].titulo + '</h4>' +
                        '<blockquote class="message">' + data.registros[i].descripcion + '</blockquote><br />' +
                        '<p class="url"><span class="fs1 text-info" aria-hidden="true" data-icon=""></span>' +
                        '</p></div>' +
                        '</li>';
                }
                $("#listaGestion").html(str);
            }
        });*/

    tablaMensajes = $('#tblListadoGestiones').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/mensajesvehiculo.php?op=listar2',
            type: "post",
            data: { idgestion_ve: idgestion_ve },
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "bDestroy": true,
        "bFilter": false,
        "iDisplayLength": 5, //Paginacion 10 items
        "order": [
                [3, "desc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    mostrarform(true);

}

function listarVehiculosGestion() {

    tabla = $('#tblvehiculosgestion').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/gestionvehiculo.php?op=listar',
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

function listarVehiculosGestionHist() {

    tablaHist = $('#tblvehiculosgestionHist').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/gestionvehiculo.php?op=listarHist',
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
                [1, "desc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}


function guardarFormGestion(e) {

    e.preventDefault();

    $.ajax({
            // En data puedes utilizar un objeto JSON, un array o un query string
            data: $("#formGestion").serialize(),
            type: "POST",
            url: "../ajax/mensajesvehiculo.php?op=guardaryeditar",
            beforeSend: function() {
                $("#btnGuardar").prop("disabled", true);
                $('.modal-body').css('opacity', '.5');
            }
        })
        .done(function(data, textStatus, jqXHR) {
            if (console && console.log) {
                console.log("La solicitud se ha completado correctamente.");
            }
            bootbox.alert(data);
            tabla.ajax.reload();
            $("#btnGuardar").prop("disabled", false);
            $('.modal-body').css('opacity', '');
            $('#modalGestion').modal('toggle');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

    limpiarForm();
}

function inhabilitarDefinitivamenteVehiculo(idgestion_ve, idvehiculo) {

    bootbox.prompt({
        title: "Indique el Motivo por el cual se esta Inhabilitando el Vehículo ",
        inputType: 'textarea',
        className: 'bootbox-custom-class',
        callback: function(result) {
            if (result !== null) {
                if (result.length > 0) {
                    $.post("../ajax/gestionvehiculo.php?op=inhabilitarDefinitivamenteVehiculo", { "idgestion_ve": idgestion_ve, "idvehiculo": idvehiculo, "observaciones": result }, function(e) {
                        bootbox.alert(e);
                        tabla.ajax.reload();
                    });
                } else {
                    new PNotify({
                        title: 'Error!',
                        text: 'Debe indicar el Motivo para inhabilitar el Vehículo.',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }
            }
        }
    }).on("shown.bs.modal", function(event) {
        $('.bootbox-custom-class').find('.bootbox-input').css("text-transform", "uppercase");
    });

}

init();