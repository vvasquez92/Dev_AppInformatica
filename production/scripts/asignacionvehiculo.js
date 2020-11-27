var tabla;


//funcion que se ejecuta iniciando
function init() {
    mostarform(0);
    listar();

    $('[data-toggle="tooltip"]').tooltip();

    $("#formformularioAsig").on("submit", function(e) {
        entrada(e);
    });

    $("#formulariodev").on("submit", function(e) {
        salida(e);
    });

    $("#formRegistroPrestamo").on("submit", function(e) {
        guardarFormRegistroPrestamo(e);
    });

    $('#modalPrestamo').on('hidden.bs.modal', function(e) {
        limpiarFormPrestamo();
    });

    $('#modalPrestamo').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $("#id_asigvehi").val(button.data('id_asigvehi'));
    });

    $.post("../ajax/empleado.php?op=selectEmpleadosDocCompleta", function(r) {

        $("#id_empleado").html(r); //llena el input  del modal.
        $("#id_empleado").selectpicker('refresh');

    });

    $.post("../ajax/empleado.php?op=selectEmpleadosDocCompleta", function(r) {
        $("#idempleado").html(r);
        $("#idempleado").selectpicker('refresh');
    });

    $.post("../ajax/vehiculo.php?op=selectvehiculo", function(r) {
        $("#idvehiculo").html(r);
        $("#idvehiculo").selectpicker('refresh');

    });

    //! Codigo nuevo
    $("#motivoInhab").change(function() {
        motivoInhab = $("#motivoInhab").val();
        if (motivoInhab == 5 || motivoInhab == 2) {
            $('#divMotivo').show();
            $('#divMotivo2').show();
            $('#divMotivo3').show();
            $("#tipoMant").val("");
            $("#tipoMant").selectpicker('refresh');
            $("#fhSolicitud").val("");
            $("#comentariosServ").val("");
            //$("#kmsActual").val("");
            if (motivoInhab == 5) {
                $.post("../ajax/asignacionvehiculo.php?op=cargaTiposMantencion", {}, function(r) {
                    $("#tipoMant").html(r);
                    $("#tipoMant").selectpicker('refresh');
                });
            } else {
                $.post("../ajax/asignacionvehiculo.php?op=cargaTiposReparacion", {}, function(r) {
                    $("#tipoMant").html(r);
                    $("#tipoMant").selectpicker('refresh');
                });
            }
        } else {
            $('#divMotivo').hide();
            $('#divMotivo2').hide();
            $('#divMotivo3').hide();
        }
    });
    //! fin codigo nuevo
}


// Otras funciones
function limpiar() {
    $("#idasigvehi").val("");
    $("#idvehiculo").val("");
    $("#idvehiculo").selectpicker('refresh');
    $("#idempleado").val("");
    $("#idempleado").selectpicker('refresh');
    $("#kilometraje").val("");
    $("#fecha").val("");
    $("#fhCompromiso").val("");
    $("#fecha").prop("disabled", false);
}

function limpiardev() {
    $("#iddevasigvehi").val("");
    $("#iddevvehiculo").val("");
    $("#vehiculo").val("");
    $("#iddevempleado").val("");
    $("#empleado").val("");
    $("#kilometraje").val("");
    $("#fecha").val("");
    $("#fhCompromiso").val("");
    $("#fecha").prop("disabled", false);
}

function limpiarFormPrestamo() {
    $("#idprestamo").val("");
    $("#id_asigvehi").val("");
    $("#id_empleado").val("");
    $("#id_empleado").selectpicker('refresh');
    $("#fhCompromiso").val("");
    $("#fecha").val("");
    $("#fecha").prop("disabled", false);
}

function mostarform(flag) {
    $("#idempleado").prop("disabled", false);
    limpiar();
    if (flag == 1) {
        $("#formulariodevvehiculo").hide();
        $("#listadoasigvehiculo").hide();
        $("#formularioasigvehiculo").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#selectVehiculo").show();
        $("#lblDatosVehiculo").hide();
        $("#fhDev").hide();
    } else if (flag == 0) {
        $("#formularioasigvehiculo").hide();
        $("#formulariodevvehiculo").hide();
        $("#listadoasigvehiculo").show();
        $("#op_agregar").show();
        $("#op_listar").hide();
        $("#fhDev").hide();
    } else if (flag == 2) {
        $("#formularioasigvehiculo").hide();
        $("#listadoasigvehiculo").hide();
        $("#formulariodevvehiculo").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#selectVehiculo").show();
        $("#lblDatosVehiculo").hide();
        $("#fhDev").hide();
    } else if (flag == 3) {
        console.log("Muestra div mostrar");
    }

}

function cancelarform() {
    limpiar();
    limpiardev();
    mostarform(0);
}

function listar() {

    let botones = [];
    if ($("#administrador").val() == 1) {
        botones = ['copyHtml5', 'print', 'excelHtml5', 'csvHtml5', 'pdf'];
    }

    tabla = $('#tblasigvehiculo').dataTable({
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
            url: '../ajax/asignacionvehiculo.php?op=listar',
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
                [1, "asc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function entrada(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var idasigna = $("#idasigvehic").val();
    $("#idasigvehi").val(idasigna);
    var formData = new FormData($("#formformularioAsig")[0]);
    var esVisibleSelectVehiculo = $("#lblDatosVehiculo").is(":visible");
    if (esVisibleSelectVehiculo === true) {

        if ($("#idprestamo").val() == null || $("#idprestamo").val() == undefined || $("#idprestamo").val() == '') {
            //creacion de prestamo
            $.ajax({
                url: "../ajax/prestamovehiculo.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos) {
                    pdf(idasigna, 0, 1)
                    bootbox.alert(datos);
                    mostarform(0);
                    tabla.ajax.reload();
                }
            });
        } else {
            //devolucion de prestamo
            $.ajax({
                url: "../ajax/prestamovehiculo.php?op=desactivar_prestamo",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos) {
                    pdf(idasigna, 1, 1);
                    bootbox.alert(datos);
                    mostarform(0);
                    tabla.ajax.reload();
                }
            });
        }
    } else {
        // asignacion normal
        $.ajax({
            url: '../ajax/asignacionvehiculo.php?op=entrada',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos) {
                bootbox.alert(datos);
                mostarform(0);
                tabla.ajax.reload();
            }
        });
    }
    limpiar();
}

function salida(e) {
    e.preventDefault();
    $("#btnGuardardev").prop("disabled", true);
    var formData = new FormData($("#formulariodev")[0]);
    $.ajax({
        url: '../ajax/asignacionvehiculo.php?op=salida',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            mostarform(0);
            tabla.ajax.reload();
        }
    });
    limpiar();
}



function devolucion(idasigvehi) {

    $.post("../ajax/asignacionvehiculo.php?op=devolucion", { idasigvehi: idasigvehi }, function(data, status) {
        data = JSON.parse(data);
        mostarform(2);
        $("#iddevasigvehi").val(data.idasigvehi);
        $("#iddevvehiculo").val(data.idvehiculo);
        $("#vehiculo").val(data.marca + ' ' + data.modelo + ' - ' + data.patente);
        $("#iddevempleado").val(data.idempleado);
        $("#empleado").val(data.nombre + ' ' + data.apellido + ' - ' + data.num_documento);
    });
}


function mostar(idasigvehi) {
    console.log("Interfaz para mostrar la asignacion" + idasigvehi);
}

//! codigo nuevo
function desactivar(idasigvehi, idempleado, kilometraje) {

    bootbox.confirm("Esta seguro que quiere inhabilitar la asignacion del vehiculo?", function(result) {
        if (result) {
            $('#divMotivo').hide();
            $('#divMotivo2').hide();
            $('#divMotivo3').hide();
            cargaTipoDevolucion();
            $('#idasigvehiculo').val(idasigvehi);
            $('#idempleadoveh').val(idempleado);
            $('#btnInhab').trigger("click");
        }
    })

    $("#kmsActual").val(kilometraje);

}

function cargaTipoDevolucion() {
    $.post("../ajax/asignacionvehiculo.php?op=cargaTipoDevolucion", {}, function(r) {
        $("#motivoInhab").html(r);
        $("#motivoInhab").selectpicker('refresh');
    });
}

function prestamo(idasig, idvehiculo) {

    $("#idasigvehi").val(idasig);
    $("#idasigvehic").val(idasig);

    mostarform(1);
    $.post("../ajax/vehiculo.php?op=listaDatosVehiculo", { idvehiculo: idvehiculo }, function(data) {
        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {

            var dataVehiculo = data[i]['marca'] + ' ' + data[i]['modelo'] + ' / ' + data[i]['patente'];
            $("#lblDatosVehiculo").val(dataVehiculo);

        }
    });
    $("#selectVehiculo").hide();
    $("#lblDatosVehiculo").show();
    $("#lblDatosVehiculo").prop("disabled", true);
    $("#fhDev").show();
}

$("#Inhabilitarform").on("submit", function(e) {
    e.preventDefault();

    var idasigvehi = $("#idasigvehiculo").val();
    var gestion = $("#motivoInhab").val();
    var tipoServ = $("#tipoMant").val();
    var fhSolic = $("#fhSolicitud").val();
    var comentServ = $("#comentariosServ").val();
    var idempleado = $("#idempleadoveh").val();
    var kmsActual = $("#kmsActual").val();

    var puedeCont = 1;

    if (gestion == 2 || gestion == 5) {

        if (tipoServ == undefined || tipoServ == null) {
            puedeCont = 0;
        } else {
            puedeCont = 1;
        }
        if (fhSolic == undefined || fhSolic == null || fhSolic == "") {
            puedeCont = 0;
        } else {
            puedeCont = 1;
        }
        if (kmsActual == undefined || kmsActual == null || kmsActual == "") {
            puedeCont = 0;
        } else {
            puedeCont = 1;
        }
    } else {
        fhSolic = "1999-01-01";
        tipoServ = 0;
        comentServ = "";
        kmsActual = 1;
    }

    if (puedeCont == 1) {

        var formData = new FormData();
        formData.append("idasigvehi", idasigvehi);
        formData.append("gestion", gestion);
        formData.append("tipoServ", tipoServ);
        formData.append("fhSolic", fhSolic);
        formData.append("comentServ", comentServ);
        formData.append("idempleado", idempleado);
        formData.append("kilometraje", kmsActual);

        $.ajax({

            url: '../ajax/asignacionvehiculo.php?op=desactivar',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    new PNotify({
                        title: 'Correcto!',
                        text: 'Asignación inhabilitada.',
                        type: 'success',
                        styling: 'bootstrap3'
                    });

                    mostarform(0);
                    listar();
                    $(".close").trigger("click");
                } else {
                    new PNotify({
                        title: 'Error!',
                        text: 'No se pudo realizar la inhabilitación',
                        type: 'error',
                        styling: 'bootstrap3'
                    });

                    mostarform(0);
                    listar();
                    $(".close").trigger("click");
                }
            }

        });
    } else {
        new PNotify({
            title: 'Error!',
            text: 'Por favor complete todos los campos solicitados',
            type: 'error',
            styling: 'bootstrap3'
        });
    }

});

//! fin codigo nuevo
/*
function desactivar(idasigvehi) {

    bootbox.confirm("Esta seguro que quiere inhabilitar la asignacion del vehiculo?", function (result) {
        if (result) {
            
               bootbox.prompt({
                            title: "Seleccione el motivo por el cual se esta inhabilitando la asignación del vehiculo",
                            inputType: 'select',
                            inputOptions: [
                                {
                                    text: '<--SELECCIONE-->',
                                    value: '',
                                },
                                {
                                    text: 'Devolución de Vehículo',
                                    value: '0',
                                },
                                {
                                    text: 'Devolución por Reparación',
                                    value: '2',
                                },
                                {
                                    text: 'Devolución por Siniestro',
                                    value: '3',
                                },
                                {
                                    text: 'Devolución por Robo',
                                    value: '4',
                                },
                                {
                                    text: 'Devolución por Mantención',
                                    value: '1',
                                }
                            ],
                            callback: function (result) {
                                if (result !== null) {
                                    if(result != ""){
                                        $.post("../ajax/asignacionvehiculo.php?op=desactivar", {idasigvehi: idasigvehi, gestion: result}, function (e) {
                                            bootbox.alert(e);
                                            tabla.ajax.reload();
                                            });
                                    }else{
                                         new PNotify({
                                            title: 'Error!',
                                            text: 'Debe Seleccionar el Motivo de la Devolución.',
                                            type: 'error',
                                            styling: 'bootstrap3'
                                        });
                                    }    
                                }
                            }
                        }); 
        }
    })
}
*/



function pdf(idasigvehi, tipo, prestamo) {
    console.log("Generando PDF");

    var opcion;
    if (prestamo == 0) {
        opcion = "../ajax/asignacionvehiculo.php?op=pdf";
    } else {
        opcion = "../ajax/asignacionvehiculo.php?op=pdfPrestamo";
    }

    $.post(opcion, { idasigvehi: idasigvehi, tipo: tipo }, function(data, status) {
        data = JSON.parse(data);
        console.log(data);

        if (tipo == 0) {
            var titulo = 'ACTA DE ENTREGA DE VEHÍCULO';
            var inicodigo = 'FF-SS-AC-V-E';
            var subtitulo = "ENTREGA A:";
            var archivo = "ENTREGA";
        } else {
            var titulo = 'ACTA DE DEVOLUCIÓN DE VEHÍCULO';
            var inicodigo = 'FF-SS-AC-V-D';
            var subtitulo = "RECIBE DE:";
            var archivo = "DEVOLUCION";
        }


        var Mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        var company_logo = {
            srcf: 'data:image/jpeg;base64,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',
            src: 'data:image/jpeg;base64,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',
            w: 200,
            h: 40,
            wf: 127
        };

        var fontSizes = {
            HeadTitleFontSize: 18,
            Head2TitleFontSize: 16,
            TitleFontSize: 14,
            SubTitleFontSize: 12,
            NormalFontSize: 10,
            SmallFontSize: 8
        };

        var lineSpacing = {
            NormalSpacing: 12,
            DobleSpacing: 24,
            LineSpacing: 10,
            LetterSpace: 6
        };

        var doc = new jsPDF('p', 'pt', 'letter');

        var rightStartCol1 = 400;
        var rightStartCol2 = 480;


        var InitialstartX = 40;
        var startX = 40;
        var InitialstartY = 50;
        var startY = 0;
        var lines = 0;
        var space = 0;

        doc.addImage(company_logo.src, 'PNG', startX, startY += 50, company_logo.w, company_logo.h);

        doc.setFontSize(fontSizes.NormalFontSize);

        doc.setFontType('bold');
        doc.text(startX, startY += 15 + company_logo.h, "Cod Documento: ");
        doc.setFontType('normal');
        doc.text(startX + 85, startY, inicodigo + data.idasigvehi);
        doc.setFontType('bold');
        doc.myText(titulo, { align: "center" }, startX, startY += lineSpacing.DobleSpacing);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "Fecha: ");
        doc.setFontType('normal');
        doc.text(startX + 35, startY, data.dia + ' de ' + Mes[data.mes - 1] + ' del ' + data.año);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Nombre del funcionario: ");
        doc.setFontType('normal');
        doc.text(startX + 120, startY, data.nomuser + ' ' + data.apeuser);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "RUT ");
        doc.setFontType('normal');
        doc.text(startX + 25, startY, data.numuser);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, subtitulo);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Nombre del empleado: ");
        doc.setFontType('normal');
        doc.text(startX + 110, startY, data.nombre + ' ' + data.apellido);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "RUT: ");
        doc.setFontType('normal');
        doc.text(startX + 25, startY, data.num_documento);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Departamento: ");
        doc.setFontType('normal');
        doc.text(startX + 80, startY, data.departamento);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Cargo: ");
        doc.setFontType('normal');
        doc.text(startX + 40, startY, data.cargo);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "EL VEHÍCULO:");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Marca y Modelo: ");
        doc.setFontType('normal');
        doc.text(startX + 85, startY, data.marca + ' ' + data.modelo);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Año: ");
        doc.setFontType('normal');
        doc.text(startX + 25, startY, data.ano);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Patente: ");
        doc.setFontType('normal');
        doc.text(startX + 45, startY, data.patente);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Kilometraje: ");
        doc.setFontType('normal');
        doc.text(startX + 65, startY, data.kilometraje);

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "REVISIÓN DOCUMENTOS: ");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Padrón: ");
        doc.setFontType('normal');
        doc.text(startX + 40, startY, (data.padron == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Revisión técnica: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 85, startY, (data.revision == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Tarjeta combustible: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 100, startY, (data.combustible == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Permiso de circulación: ");
        doc.setFontType('normal');
        doc.text(startX + 115, startY, (data.permiso == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Seguro obligatorio: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 95, startY, (data.seguro == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Sello verde: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 60, startY, (data.selloverde == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "TAG: ");
        doc.setFontType('normal');
        doc.text(startX + 30, startY, (data.tag == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "REVISIÓN ACCESORIOS: ");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Radio: ");
        doc.setFontType('normal');
        doc.text(startX + 35, startY, (data.radio == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Parasoles: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 55, startY, (data.parasoles == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Gata: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 30, startY, (data.gata == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Llave de ruedas: ");
        doc.setFontType('normal');
        doc.text(startX + 85, startY, (data.gata == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Rueda de repuesto: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 100, startY, (data.rerueda == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Asientos: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 50, startY, (data.asientos == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Pisos de goma: ");
        doc.setFontType('normal');
        doc.text(startX + 80, startY, (data.pisos == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Cenicero: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 50, startY, (data.cenicero == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Jaula: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 30, startY, (data.jaula == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "REVISIÓN MECÁNICA: ");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Luces: ");
        doc.setFontType('normal');
        doc.text(startX + 35, startY, (data.luces == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Frenos: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 40, startY, (data.frenos == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Odometro: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 55, startY, (data.odometro == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Velocímetro: ");
        doc.setFontType('normal');
        doc.text(startX + 65, startY, (data.velocimetro == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Indicador combustible: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 115, startY, (data.indicombus == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Palanca de cambios: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 105, startY, (data.cambios == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Tapa de estanque: ");
        doc.setFontType('normal');
        doc.text(startX + 90, startY, (data.tapaestan == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Dirección: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 55, startY, (data.direccion == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Freno de mano: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 80, startY, (data.fremano == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Intermitente: ");
        doc.setFontType('normal');
        doc.text(startX + 65, startY, (data.intermitente == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Limpia parabrisas: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 95, startY, (data.limparabrisas == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Nivel fluidos: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 70, startY, (data.niveles == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Parabrisas: ");
        doc.setFontType('normal');
        doc.text(startX + 60, startY, (data.parabrisas == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Luneta: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 40, startY, (data.luneta == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "Puertas: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 45, startY, (data.puertas == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.NormalSpacing, "Alzavidrios: ");
        doc.setFontType('normal');
        doc.text(startX + 60, startY, (data.alzavidrios == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 150, startY, "Motor: ");
        doc.setFontType('normal');
        doc.text(startX + 150 + 35, startY, (data.motor == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX + 300, startY, "GPS: ");
        doc.setFontType('normal');
        doc.text(startX + 300 + 45, startY, (data.gps == 1) ? "OK" : "X");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "OBSERVACIONES ADICIONALES: ");

        doc.setFontType('normal');
        lines = doc.splitTextToSize(data.observaciones, 540);
        doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
        space = lines.length * lineSpacing.LineSpacing;

        doc.setFontType('bold');
        doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Entregado por: ");
        doc.setFontType('bold');
        doc.text(startX + 250, startY, "Recibido por: ");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "RUT: ");
        doc.setFontType('bold');
        doc.text(startX + 250, startY, "RUT: ");

        doc.setFontType('bold');
        doc.text(startX, startY += lineSpacing.DobleSpacing, "FIRMA: ");
        doc.setFontType('bold');
        doc.text(startX + 250, startY, "FIRMA: ");



        doc.addImage(company_logo.srcf, 'PNG', startX + 400, 676 + 50, company_logo.wf, company_logo.h);

        doc.save(archivo + data.idasigvehi + '.pdf');

    });
}

function guardarFormRegistroPrestamo(e) {

    e.preventDefault();

    $.ajax({
            // En data puedes utilizar un objeto JSON, un array o un query string
            data: $("#formRegistroPrestamo").serialize(),
            type: "POST",
            url: "../ajax/prestamovehiculo.php?op=guardaryeditar",
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
            $('#modalPrestamo').modal('toggle');
            idas = $("#id_asigvehi").val();
            pdf(idas, 0, 1);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }

        });

}

function desactivar_prestamo(idasig, idvehiculo, idprestamo) {

    $("#idasigvehi").val(idasig);
    $("#idasigvehic").val(idasig);
    $("#idprestamo").val(idprestamo);

    mostarform(1);
    $.post("../ajax/vehiculo.php?op=listaDatosVehiculoPrestamo", { idasig: idasig, idprestamo: idprestamo }, function(data) {
        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {

            var dataVehiculo = data[i]['marca'] + ' ' + data[i]['modelo'] + ' / ' + data[i]['patente'];
            $("#lblDatosVehiculo").val(dataVehiculo);
            $("#idempleado").val(data[i]['idempleado']);
            $("#idempleado").selectpicker('refresh');
            $("#fecha").val(data[i]['fhPrestamo']);
            $("#fecha").prop("disabled", true);
            $("#fhCompromiso").val(data[i]['fhCompromiso']);
        }
    });
    $("#selectVehiculo").hide();
    $("#lblDatosVehiculo").show();
    $("#lblDatosVehiculo").prop("disabled", true);
    $("#idempleado").prop("disabled", true);
    $("#fhDev").show();


    /*

    bootbox.confirm("Esta seguro que quiere inhabilitar el prestamo del vehículo?", function(result) {
        if (result) {
            $.post("../ajax/prestamovehiculo.php?op=desactivar_prestamo", { idprestamo: idprestamo }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            })
        }
    })

    */
}

init();