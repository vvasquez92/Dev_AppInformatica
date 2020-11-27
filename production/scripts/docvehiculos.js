var tabla;

//funcion que se ejecuta iniciando
function init() {

    listar();

    $('[data-toggle="tooltip"]').tooltip();


    $("#idmarca").on("change", function(e) {
        $.get("../ajax/modelove.php?op=selectmodelove", { id: $("#idmarca").val() }, function(r) {
            $("#idmodelo").html(r);
            $("#idmodelo").selectpicker('refresh');
        });
    });


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
            url: '../ajax/vehiculo.php?op=listarVehiculosDocumentacionCompleta',
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

function documentos(idv) {
    $("#id_vehiculo").val(idv);
    $("#doc_gases").val("");
    $("#doc_tecnica").val("");
    $("#doc_circulacion").val("");
    $("#btnDocumentos").trigger('click');
}

$("#formPlazoDocumentosVehiculo").on("submit", function(e) {
    guardarFormPlazoDocumentosVehiculo(e);
});

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



init();