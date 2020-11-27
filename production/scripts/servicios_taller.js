/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* CALENDAR */

var tabla;
var tabla_e;
var repAgregados = [];
var signaturePad;

function init() {

    calendario();
    listarServicios('1,2,3,4,5');
    $("#recepcion").hide();

    $('#myDatepicker_hrDesde').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly: true
    });

    $('#myDatepicker_hrHasta').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly: true
    });

    $('#myDatepicker_hrDesdeReprog').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly: true
    });

    $('#myDatepicker_hrHastaReprog').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly: true
    });

    $("#fhIniServ").on("change", function(e) { //evento cuando se cierra el calendario.
        if ($('#fhFinServ').val() != "" && $('#hrDesde').val() != "") {
            validar_fechas();
        }
    });

    $("#fhFinServ").on("change", function(e) { //evento cuando se cierra el calendario.
        if ($('#fhIniServ').val() != "" && $('#hrHasta').val() != "") {
            validar_fechas();
        }
    });

    $("#myDatepicker_hrDesde").on("dp.hide", function(e) { //evento cuando se cierra el calendario.
        if ($('#hrHasta').val() != "") {
            validar_fechas();
        }
    });

    $("#myDatepicker_hrHasta").on("dp.hide", function(e) { //evento cuando se cierra el calendario.
        if ($('#hrDesde').val() != "") {
            validar_fechas();
        }
    });

    $("#fhIniServReprog").on("change", function(e) { //evento cuando se cierra el calendario.
        if ($('#fhFinServReprog').val() != "" && $('#hrDesdeReprog').val() != "") {
            validar_fechas();
        }
    });

    $("#fhFinServReprog").on("change", function(e) { //evento cuando se cierra el calendario.
        if ($('#fhIniServReprog').val() != "" && $('#hrHastaReprog').val() != "") {
            validar_fechas();
        }
    });

    $("#myDatepicker_hrDesdeReprog").on("dp.hide", function(e) { //evento cuando se cierra el calendario.
        if ($('#hrHastaReprog').val() != "") {
            validar_fechas();
        }
    });

    $("#myDatepicker_hrHastaReprog").on("dp.hide", function(e) { //evento cuando se cierra el calendario.
        if ($('#hrDesdeReprog').val() != "") {
            validar_fechas();
        }
    });

    $("#sRep").change(function() {
        sRep = $("#sRep").val();

        $.post("../ajax/servicios_taller.php?op=cantidadMaxRep", { idRep: sRep }, function(data) {
            data = JSON.parse(data);

            for (i = 0; i < data.length; i++) {
                $('#cantMaxima').val(data[i]['cantidad']);
            }
        });

    });

}

function cargaRepuestos() {
    $.post("../ajax/servicios_taller.php?op=cargaRepXCat", {}, function(r) {
        $("#sRep").html(r);
        $("#sRep").selectpicker('refresh');
    });
}

function calendario() {

    var vistaDefecto, tituloDias, todoDia;
    if (window.matchMedia("(orientation: portrait)").matches) {
        vistaDefecto = 'listMonth'; // valor por defecto para moviles
        tituloDias = 'ddd';
        todoDia = "hrs asig";
    }

    if (window.matchMedia("(orientation: landscape)").matches) {
        vistaDefecto = 'month'; // valor por defecto para escritorio
        tituloDias = 'ddd D-MMM';
        todoDia = "horas asig.";
    }

    var dt = new Date();
    var time = (dt.getHours() - 1) + ":00:00";


    if (typeof($.fn.fullCalendar) === 'undefined') {
        return;
    }

    var date = new Date(),
        d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear(),
        started,
        categoryClass;

    var calendar = $('#calendario').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        defaultView: vistaDefecto,
        locale: 'es',
        buttonText: {
            prev: "Ant",
            next: "Sig",
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Agenda"
        },
        closeText: "Cerrar",
        prevText: "&#x3C;Ant",
        nextText: "Sig&#x3E;",
        currentText: "HOY",
        monthNames: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"],
        monthNamesShort: ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"],
        dayNames: ["DOMINGO", "LUNES", "MARTES", "MIÉRCOLES", "JUEVES", "VIERNES", "SÁBADO"],
        dayNamesShort: ["DOM", "LUN", "MAR", "MIÉ", "JUE", "VIE", "SÁB"],
        dayNamesMin: ["D", "L", "M", "X", "J", "V", "S"],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: !1,
        showMonthAfterYear: !1,
        yearSuffix: "",
        allDayHtml: todoDia,
        eventLimitText: "más",
        views: {
            month: { columnFormat: 'ddd' },
            week: {
                columnFormat: tituloDias,
                titleFormat: 'D MMMM, YYYY',
            },
            day: {
                columnFormat: 'dddd D',
                titleFormat: 'D MMMM, YYYY',
            }
        },
        businessHours: {
            start: '08:00', // hora final
            end: '17:30', // hora inicial
            dow: [1, 2, 3, 4, 5] // dias de semana, 0=Domingo
        },
        scrollTime: time, //empieza el scroll desde 1 hora antes a la actual. Ejemplo sin son las 15:30 el scroll empieza desde las 14:00
        slotDuration: '00:15:00', // cuadros cada 15 minutos
        slotLabelFormat: "HH:mm", //muestra la hora en formato HH:mm        
        editable: false,
        selectable: true,
        eventLimit: false,
        selectHelper: false,
        // displayEventTime: false, //Quitar hora de inicio [opcional]
        select: function(start, end, allDay) {

            $(".antosubmit").on("click", function() {
                //categoryClass = $("#event_type").val();
                calendar.fullCalendar('unselect');
                //$('.antoclose').click();
                return false;

            });
        },
        eventClick: function(calEvent, jsEvent, view) {
            //console.log(calEvent.id_tarea);
            levantaPopUpServicio(calEvent.id_servicio, calEvent.tipo_servicio, calEvent.motivo, calEvent.estado, calEvent.kms_actual, calEvent.fh_solicitud, calEvent.fini, calEvent.ffin, calEvent.mecanico, calEvent.datos_veh, calEvent.tipo_servicio_desc);
        }
    });

}

function listarServicios(estado) {

    var events = [];
    var events2 = [];

    $('#calendario').fullCalendar('removeEvents', function() {
        return true;
    });

    $('#calendario').fullCalendar('refetchEvents');

    $.post("../ajax/servicios_taller.php?op=listaServicios", { estado: estado }, function(data) {

        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {

            events.push({
                id_servicio: data[i]['id_servicio'],
                title: data[i]['patente'] + ' | ' + data[i]['tipo_servicio'],
                start: data[i]['fh_inicio'],
                end: data[i]['fh_fin'],
                allDay: false,
                color: data[i]['color'], // an option!
                textColor: 'white',
                motivo: data[i]['motivo'],
                estado: data[i]['estado'],
                kms_actual: data[i]['kms_actual'],
                fh_solicitud: data[i]['fh_solicitud'],
                fini: data[i]['fini'],
                ffin: data[i]['ffin'],
                mecanico: data[i]['mecanico'],
                patente: data[i]['patente'],
                tipo_servicio: data[i]['tipo_servicio'],
                datos_veh: data[i]['datos_veh'],
                tipo_servicio_desc: data[i]['tipo_servicio_desc']
            });
        }

        $('#calendario').fullCalendar('addEventSource', events);
    });

}

function levantaPopUpServicio(id_servicio, title, motivo, estado, kms_actual, fh_solicitud, fh_inicio, fh_fin, mecanico, datos_veh, tipo_servicio_desc) {

    if (estado == 1) {
        limpiar();
        $('#tipServicio').val(tipo_servicio_desc + ' - ' + title);
        $('#comentarioServ').val(motivo);
        $('#id_servicio').val(id_servicio);
        $('#btnSolicServ').trigger("click");
    }
    if (estado == 2) {
        cargaRepuestosServicio(id_servicio);
        cargaRepuestos();
        $('#lblDatosVeh').val(datos_veh);
        $('#idServicio').val(id_servicio);
        $('#stipServicio').val(tipo_servicio_desc + ' - ' + title);
        $('#sKmsActual').val(kms_actual);
        $("#sKmsActual").prop("disabled", true);
        $('#sFhIniServ').val(fh_inicio);
        $('#sFhFinserv').val(fh_fin);
        $("#sFhIniServ").prop("disabled", true);
        $("#sFhFinserv").prop("disabled", true);
        $('#btnServicio').trigger("click");
        $("#iniciarServ").show();

        $("#divCat").hide();
        $("#divRep").hide();
        $("#divCant").hide();
        $("#agregarRep").hide();
        $("#divTab").hide();

        $("#finalizarServ").hide();
    }
    if (estado == 3) {
        cargaRepuestosServicio(id_servicio);
        cargaRepuestos();
        $('#lblDatosVeh').val(datos_veh);
        $('#idServicio').val(id_servicio);
        $('#stipServicio').val(tipo_servicio_desc + ' - ' + title);
        $('#sKmsActual').val(kms_actual);
        $("#sKmsActual").prop("disabled", true);
        $('#sFhIniServ').val(fh_inicio);
        $('#sFhFinserv').val(fh_fin);
        $("#sFhIniServ").prop("disabled", true);
        $("#sFhFinserv").prop("disabled", true);
        $('#btnServicio').trigger("click");
        $("#iniciarServ").hide();

        $("#divCat").show();
        $("#divRep").show();
        $("#divCant").show();
        $("#agregarRep").show();
        $("#divTab").show();

        $("#finalizarServ").show();
    }
    if (estado == 4) {
        $("#filtros").hide();
        $("#calendario").hide();
        $("#recepcion").show();
        $('#patenteRecibe').val(datos_veh);
        $('#idServicio').val(id_servicio);

        var canvas = document.getElementById('firmafi');
        canvas.height = canvas.offsetHeight;
        canvas.width = canvas.offsetWidth;
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });
    }
    if (estado == 5) {
        cargaRepuestosServicioEntregado(id_servicio);
        $('#e_lblDatosVeh').val(datos_veh);
        $('#e_idServicio').val(id_servicio);
        $('#e_stipServicio').val(tipo_servicio_desc + ' - ' + title);
        $('#e_sKmsActual').val(kms_actual);
        $('#e_sFhIniServ').val(fh_inicio);
        $('#e_sFhFinserv').val(fh_fin);
        $('#btnEntregado').trigger("click");
        //generarPdf(id_servicio, 1);
    }
}

function descargarPDF() {
    var ids = $('#e_idServicio').val();
    generarPdf(ids, 1);
}

$("#recepcionForm").on("submit", function(e) {
    $("#btnGuardar").prop('disabled', true);

    e.preventDefault();

    if ($("#firmavali").val() == "") {
        new PNotify({
            title: 'Error en la firma',
            text: 'Debe validar la firma',
            type: 'error',
            styling: 'bootstrap3'
        });
        $("#btnGuardar").prop('disabled', false)
    } else {

        var firmaRecibe = $("#firma").val();
        var rutRecibe = $("#rutRecibe").val();
        var nombreRecibe = $("#nombreRecibe").val();
        var correoRecibe = $("#correoRecibe").val();
        var id_servicio = $('#idServicio').val();

        var formData = new FormData();
        formData.append("id_servicio", id_servicio);
        formData.append("firma", firmaRecibe);
        formData.append("rutRecibe", rutRecibe);
        formData.append("nombreRecibe", nombreRecibe);
        formData.append("correoRecibe", correoRecibe);

        $.ajax({
            url: '../ajax/servicios_taller.php?op=recibeVehiculo',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(datos) {
                generarPdf(id_servicio, 0);
            }
        });
    }
});

function cancelaEntrega() {
    $("#filtros").show();
    $("#calendario").show();
    $("#recepcion").hide();
    $('#rutRecibe').val("");
    $('#nombreRecibe').val("");
    $('#correoRecibe').val("");
    borrarfirma();
    $("#btnGuardar").prop("disabled", false);
}

function validar_fechas() {
    if ($('#hrHasta').val() < $('#hrDesde').val() && $('#fhFinServ').val() == $('#fhIniServ').val()) {

        $("#enviar").prop("disabled", true);

        new PNotify({
            title: 'Error!',
            text: 'Los datos de INICIO no pueden ser mayor a los de FIN',
            type: 'error',
            styling: 'bootstrap3'
        });

        $("#div_desde").addClass("has-error");
        $("#div_hasta").addClass("has-error");

    } else if ($('#fhFinServ').val() < $('#fhIniServ').val()) {
        $("#enviar").prop("disabled", true);

        new PNotify({
            title: 'Error!',
            text: 'Los datos de INICIO no pueden ser mayor a los de FIN',
            type: 'error',
            styling: 'bootstrap3'
        });

        $("#div_desde").addClass("has-error");
        $("#div_hasta").addClass("has-error");
    } else {
        $("#enviar").prop("disabled", false);
        $("#div_desde").removeClass("has-error");
        $("#div_hasta").removeClass("has-error");
    }

}

$("#SolicServform").on("submit", function(e) {
    e.preventDefault();

    var id_servicio = $("#id_servicio").val();
    var inicio = $("#fhIniServ").val() + " " + $("#hrDesde").val();
    var fin = $("#fhFinServ").val() + " " + $("#hrHasta").val();

    var formData = new FormData();
    formData.append("id_servicio", id_servicio);
    formData.append("inicio", inicio);
    formData.append("fin", fin);

    $.ajax({

        url: '../ajax/servicios_taller.php?op=tomaServicio',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Servicio actualizado.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Servicio no pudo ser actualizado',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            }
        }

    });
});

function agregarRepuestos() {

    var puedeAgregar = 1;

    var repId = $("#sRep").val();
    var qty = $("#sCantRep").val();
    var cantMaxima = $("#cantMaxima").val();
    var idServ = $('#idServicio').val();

    if (qty == undefined || qty == null || qty == 0) {
        puedeAgregar = 0;
    }
    if (repId == undefined || repId == null) {
        puedeAgregar = 0;
    }

    if (parseInt(qty) > parseInt(cantMaxima)) {
        puedeAgregar = 2;

        var Botones2 = {
            cancel: { label: 'Entendido', className: 'btn-info' }
        }

        bootbox.dialog({
            title: '<b>ALERTA</b>',
            message: '<p>La cantidad ingresada supera el stock actual</p>',
            buttons: Botones2
        });
    }

    if (puedeAgregar == 1) {
        var newMarca = $("#NewMarca").val();

        var formData = new FormData();
        formData.append("id_servicio", idServ);
        formData.append("idRep", repId);
        formData.append("cantidad", qty);

        $.ajax({

            url: '../ajax/servicios_taller.php?op=agregaRepAServicio',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    new PNotify({
                        title: 'Correcto!',
                        text: 'Repuesto agregado.',
                        type: 'success',
                        styling: 'bootstrap3'
                    });
                    cargaRepuestosServicio(idServ);
                } else {
                    new PNotify({
                        title: 'Error!',
                        text: 'Repuesto no pudo ser agregado',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                    cargaRepuestosServicio(idServ);
                }
            }

        });
    } else if (puedeAgregar == 2) {

    } else {
        var Botones2 = {
            cancel: { label: 'Entendido', className: 'btn-info' }
        }

        bootbox.dialog({
            title: '<b>ALERTA</b>',
            message: '<p>Debe ingresar un repuesto con cantidad mayor a cero para continuar</p>',
            buttons: Botones2
        });
    }
}

$("#tblrepuestos").on('click', '.btn-danger', function() {
    $(this).parent().parent().remove();
});

$("#Servicioform").on("submit", function(e) {
    e.preventDefault();
});


function cargaRepuestosServicio(idServicio) {

    $("#sRep").val("");
    $("#sRep").selectpicker('refresh');
    $("#sCantRep").val("");

    tabla = $('#tblrepuestos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "bFilter": false,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/servicios_taller.php?op=cargaRepuestosServicio',
            type: "post",
            data: { id_servicio: idServicio },
            dataType: "json",
            error: function(e) {
                //console.log(e.responseText);
            }
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [
            [1, "asc"]
        ]
    }).DataTable();
}

function cargaRepuestosServicioEntregado(idServicio) {

    $("#sRep").val("");
    $("#sRep").selectpicker('refresh');
    $("#sCantRep").val("");

    tabla_e = $('#tblrepuestosEntregados').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "bFilter": false,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/servicios_taller.php?op=cargaRepuestosServicioEntregado',
            type: "post",
            data: { id_servicio: idServicio },
            dataType: "json",
            error: function(e) {
                //console.log(e.responseText);
            }
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [
            [1, "asc"]
        ]
    }).DataTable();
}

function finalizarServicio() {
    var event = '<b>INGRESE OBSERVACIONES DEL SERVICIO: </b>' +
        '<textarea style="height:70px; text-transform: uppercase; resize:none; width:100%;" maxlenght="500" id="obsServicio" name="obsServicio" placeholder="Ingrese observaciones del servicio"></textarea></td></tr>';

    var Botones2 = {
        cancel: { label: 'Cancelar', className: 'btn-info' },
        save: {
            label: 'Finalizar',
            className: 'btn-dark',
            callback: function() {
                finalizar($("#obsServicio").val())
            }
        },
    };

    bootbox.dialog({
        title: '<b>FINALIZAR SERVICIO</b>',
        message: event,
        buttons: Botones2
    });
}

function finalizar(observaciones) {
    var idServ = $('#idServicio').val();

    var formData = new FormData();
    formData.append("id_servicio", idServ);
    formData.append("observaciones", observaciones);

    $.ajax({

        url: '../ajax/servicios_taller.php?op=finalizarServicio',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Servicio finalizado',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $(".close").trigger("click");
                listarServicios('1,2,3,4,5');
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'No se pudo finalizar el servicio',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                $(".close").trigger("click");
                listarServicios('1,2,3,4,5');
            }
        }

    });
}

function iniciarServicio() {
    var idServicio = $('#idServicio').val();

    var formData = new FormData();
    formData.append("id_servicio", idServicio);

    $.ajax({

        url: '../ajax/servicios_taller.php?op=iniciaServicio',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Servicio iniciado.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Servicio no pudo ser iniciado',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            }
        }

    });

}

function quitarRep(id_repuesto) {
    var idServicio = $('#idServicio').val();

    var formData = new FormData();
    formData.append("id_servicio", idServicio);
    formData.append("idRep", id_repuesto);

    $.ajax({

        url: '../ajax/servicios_taller.php?op=quitarRep',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Repuesto quitado.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Repuesto no se pudo quitar',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
        }

    });

}

function reprogServicio() {
    limpiar();
    $('#btnReprogServ').trigger("click");
}

$("#Reprogform").on("submit", function(e) {
    e.preventDefault();

    var id_servicio = $("#idServicio").val();
    var motivoReprog = $("#motivoReprog").val();
    var inicio = $("#fhIniServReprog").val() + " " + $("#hrDesdeReprog").val();
    var fin = $("#fhFinServReprog").val() + " " + $("#hrHastaReprog").val();

    var formData = new FormData();
    formData.append("id_servicio", id_servicio);
    formData.append("motivoReprog", motivoReprog);
    formData.append("inicio", inicio);
    formData.append("fin", fin);

    $.ajax({

        url: '../ajax/servicios_taller.php?op=reprogramaServicio',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Servicio reprogramado.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Servicio no pudo ser reprogramado',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                listarServicios('1,2,3,4,5');
                $(".close").trigger("click");
            }
        }

    });
});

function fijarfirma() {


    if (signaturePad.isEmpty()) {
        new PNotify({
            title: 'Error en la firma',
            text: 'La firma no puede estar vacia',
            type: 'error',
            styling: 'bootstrap3'
        });
    } else {
        const padfirma = signaturePad.toDataURL("image/jpeg", 100);
        if (padfirma) {
            $("#firmavali").val("Firma validada");
            $("#firmavali").addClass(' border border-success');
            $("#firma").val(padfirma);
            $("#firmapad").hide();
        } else {
            $("#firmavali").val("Error al validar");
            $("#firmavali").addClass(' border border-danger');
        }
    }

}

function borrarfirma() {
    $("#firmavali").val("");
    signaturePad.clear();
    $("#firmapad").show();
}

function generarPdf(id_servicio, descarga) {
    //console.log("Generando PDF");

    var dataRep = [];

    $.post("../ajax/servicios_taller.php?op=cargaRepuestosServicioPDF", { id_servicio: id_servicio }, function(data) {

            data = JSON.parse(data);

            for (i = 0; i < data.length; i++) {
                dataRep.push({
                    nombre: data[i]['nombre'],
                    marca: data[i]['marca'],
                    modelo: data[i]['modelo'],
                    cantidad: data[i]['cantidad']
                });
            }

        })
        .done(function() {
            $.post("../ajax/servicios_taller.php?op=pdf", { id_servicio: id_servicio }, function(data, status) {
                data = JSON.parse(data);
                //console.log(data);

                var archivo = "ServicioTallerMecanico";

                var Mes = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];

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

                var startX = 40;
                var startY = 0;
                var space = 0;

                doc.addImage(company_logo.src, 'PNG', startX + 330, startY += 50, company_logo.w, company_logo.h);

                doc.setFontSize(fontSizes.NormalFontSize);

                doc.setFontType('bold');
                doc.text(startX, startY += 35, "FECHA: ");
                doc.setFontType('normal');
                doc.text(startX + 40, startY, data.dia + ' DE ' + Mes[data.mes - 1] + ' DEL ' + data.anio);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 30, "DATOS DEL CHOFER");

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 10, "NOMBRE:");
                doc.setFontType('normal');
                doc.text(startX + 50, startY, data.nombre_completo);

                doc.setFontType('bold');
                doc.text(startX + 275, startY, "TELÉFONO:");
                doc.setFontType('normal');
                doc.text(startX + 335, startY, data.telefono);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing, "RUT:");
                doc.setFontType('normal');
                doc.text(startX + 25, startY, data.rut);

                doc.setFontType('bold');
                doc.text(startX + 275, startY, "CORREO:");
                doc.setFontType('normal');
                doc.text(startX + 330, startY, data.correo);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 30, "DATOS DEL VEHÍCULO");

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 10, "MARCA:");
                doc.setFontType('normal');
                doc.text(startX + 45, startY, data.marca_vehiculo);

                doc.setFontType('bold');
                doc.text(startX + 275, startY, "MODELO:");
                doc.setFontType('normal');
                doc.text(startX + 325, startY, data.modelo_vehiculo);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing, "PATENTE:");
                doc.setFontType('normal');
                doc.text(startX + 55, startY, data.patente);

                doc.setFontType('bold');
                doc.text(startX + 275, startY, "AÑO:");
                doc.setFontType('normal');
                doc.text(startX + 305, startY, data.ano);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing, "NRO DE SERIE MOTOR:");
                doc.setFontType('normal');
                doc.text(startX + 120, startY, data.serialmotor);

                doc.setFontType('bold');
                doc.text(startX + 275, startY, "KILOMETRAJE ACTUAL:");
                doc.setFontType('normal');
                doc.text(startX + 400, startY, data.kms_actual);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 30, "OBSERVACIONES");

                doc.setFontType('normal');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 10, data.observaciones);

                doc.setFontType('bold');
                doc.text(startX, startY += lineSpacing.NormalSpacing + 30, "REPUESTOS UTILIZADOS");

                var columns = [
                    { title: "NOMBRE", dataKey: "nombre" },
                    { title: "CANTIDAD", dataKey: "cantidad" }
                ];

                //console.log(dataRep);
                var options = { startY: startY += lineSpacing.NormalSpacing };
                doc.autoTable(columns, dataRep, options);


                doc.setFontType('bold');
                doc.text(startX, 630, "ENTREGADO POR: ");
                doc.setFontType('normal');
                doc.text(startX + 95, 630, data.mecanico);
                doc.setFontType('bold');
                doc.text(startX + 300, 630, "RECIBIDO POR: ");
                doc.setFontType('normal');
                doc.text(startX + 380, 630, data.nombre_recibe);

                doc.setFontType('bold');
                doc.text(startX, 645, "RUT: ");
                doc.setFontType('normal');
                doc.text(startX + 30, 645, data.rut_mecanico);
                doc.setFontType('bold');
                doc.text(startX + 300, 645, "RUT: ");
                doc.setFontType('normal');
                doc.text(startX + 330, 645, data.rut_recibe);

                doc.setFontType('bold');
                doc.text(startX, 660, "FIRMA: ");
                doc.setFontType('bold');
                doc.text(startX + 300, 660, "FIRMA: ");

                var firmaMecanico = {
                    srcf: data.firma_mecanico,
                    src: data.firma_mecanico,
                    w: 200,
                    h: 50,
                    wf: 127
                };

                if (data.firma_mecanico != null) {
                    doc.addImage(firmaMecanico.src, 'PNG', startX, 660, firmaMecanico.w, firmaMecanico.h);
                }

                var firmaRecibe = {
                    srcf: data.firma_empleado,
                    src: data.firma_empleado,
                    w: 250,
                    h: 60,
                    wf: 127
                };

                doc.addImage(firmaRecibe.src, 'PNG', startX + 300, 660, firmaRecibe.w, firmaRecibe.h);

                if (descarga == 1) {
                    doc.save(archivo + '_' + id_servicio + '.pdf');
                } else {
                    var blob = doc.output('blob');

                    var formData = new FormData();
                    formData.append('id_servicio', id_servicio);
                    formData.append('pdf', blob);

                    $.ajax({
                        url: '../ajax/servicios_taller.php?op=guardaPDF',
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(datos) {

                            var formData2 = new FormData();
                            formData2.append('id_servicio', id_servicio);
                            formData2.append('correoRecibe', data.correo_recibe.toLowerCase());

                            $.ajax({
                                url: '../ajax/servicios_taller.php?op=enviaCorreo',
                                type: "POST",
                                data: formData2,
                                contentType: false,
                                processData: false,

                                success: function(datos) {
                                    new PNotify({
                                        title: 'Correcto!',
                                        text: 'Correo enviado.',
                                        type: 'success',
                                        styling: 'bootstrap3'
                                    });
                                    cancelaEntrega();
                                    listarServicios('1,2,3,4,5');
                                }
                            });
                        }
                    });
                }
            });
        });
}

function limpiar() {
    $('#fhIniServ').val("");
    $('#hrDesde').val("");
    $('#fhFinServ').val("");
    $('#hrHasta').val("");

    $('#motivoReprog').val("");
    $('#fhIniServReprog').val("");
    $('#hrDesdeReprog').val("");
    $('#fhFinServReprog').val("");
    $('#hrHastaReprog').val("");
}

init();