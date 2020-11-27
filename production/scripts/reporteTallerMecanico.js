var tabla;
var tabla_e;

//funcion que se ejecuta iniciando
function init() {
    cargaPatentes();
    cargaAnios();
    //listar(0,0,0);
    $("#listadovehiculosgestion").hide();

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var month2 = ("0" + (now.getMonth())).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var today2 = now.getFullYear() + "-" + (month2) + "-" + (day);

    $('#fhDesde').val(today2);
    $('#fhHasta').val(today);
}

function cargaPatentes() {
    $.post("../ajax/reporteTallerMecanico.php?op=cargaPatentes", {}, function(r) {
        $("#patente").html(r);
        $("#patente").selectpicker('refresh');
    });
}

function cargaAnios() {
    $.post("../ajax/reporteTallerMecanico.php?op=selectano", function(r) {
        $("#ano").html(r);
        $("#ano").selectpicker('refresh');
    });
}

function listar(vpatente, vdesde, vhasta) {

    let botones = [];
    if ($("#administrador").val() == 1) {
        botones = ['copyHtml5', 'print', 'excelHtml5', 'csvHtml5', 'pdf'];
    }

    tabla = $('#tblReporte').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: botones,
        "ajax": {
            url: '../ajax/reporteTallerMecanico.php?op=listar',
            type: "post",
            data: { patente: vpatente, desde: vdesde, hasta: vhasta },
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
                [0, "asc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function buscar() {
    var vpatente = $("#patente").val();
    var vdesde = $("#fhDesde").val();
    var vhasta = $("#fhHasta").val();
    $("#listadovehiculosgestion").show();
    listar(vpatente, vdesde, vhasta);
}

function detalle(vid_servicio) {
    $('#btnDetalle').trigger("click");
    $("#mecanico").prop("disabled", true);
    $("#chofer").prop("disabled", true);
    $("#obsServicio").prop("disabled", true);

    tabla_e = $('#tblDetalle').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "bFilter": false,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/servicios_taller.php?op=cargaRepuestosServicioEntregado',
            type: "post",
            data: { id_servicio: vid_servicio },
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
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

    $.post("../ajax/reporteTallerMecanico.php?op=listaDatosServicio", { idServicio: vid_servicio }, function(data) {
        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {
            $('#mecanico').val(data[i]['mecanico']);
            $('#chofer').val(data[i]['chofer']);
            $('#obsServicio').html(data[i]['observaciones']);
        }
    });

}



init();