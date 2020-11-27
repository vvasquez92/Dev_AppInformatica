var tabla;
var tablaDetalle;
var tablaNroSer;
var tablaFact;
var dataSet = [];
var cantActual;
var cantTotal;

//funcion que se ejecuta iniciando
function init() {
    mostarform(false);
    listar();

    $.post("../ajax/regiones.php?op=selectRegiones", function(r) {
        $("#idregiones").html(r);
        $("#idregiones").selectpicker('refresh');
    });


    $("#idregiones").on("change", function(e) {
        $.get("../ajax/comunas.php?op=selectComunas", { id: $("#idregiones").val() }, function(r) {
            $("#idcomunas").html(r);
            $("#idcomunas").selectpicker('refresh');
        });
    });

    $("#btnVerNroSerie").hide();
}

function mostarform(flag) {

    if (flag) {
        $("#listadorepuestos").hide();
        $("#formulariorepuestos").show();
        $("#op_agregar").hide();
        $("#op_listar").show();
        $("#btnGuardar").prop("disabled", false);
        $("#nro_serie").show();
        $("#btnVerNroSerie").hide();
        cargaMarcasRep();
        cargaCategoriasRep();
        cargaProveedoresRep();
        $("#btnVerFactura").hide();
    } else {
        $("#listadorepuestos").show();
        $("#formulariorepuestos").hide();
        $("#op_agregar").show();
        $("#op_listar").hide();
    }

}

function cancelarform() {
    mostarform(false);
    limpiar();
}

function listar() {

    let botones = [];
    if ($("#administrador").val() == 1) {
        botones = ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'];
    }

    tabla = $('#tblrepuestos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: botones,
        "ajax": {
            url: '../ajax/bodega_repuestos.php?op=listar',
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

function cargaMarcasRep() {
    $.post("../ajax/bodega_repuestos.php?op=cargaMarcasRep", {}, function(r) {
        $("#marca_rep").html(r);
        $("#marca_rep").selectpicker('refresh');
    });
}

function cargaCategoriasRep() {
    $.post("../ajax/bodega_repuestos.php?op=cargaCategoriasRep", {}, function(r) {
        $("#categoria_rep").html(r);
        $("#categoria_rep").selectpicker('refresh');
    });
}

function cargaProveedoresRep() {
    $.post("../ajax/bodega_repuestos.php?op=cargaProveedoresRep", {}, function(r) {
        $("#proveedor_rep").html(r);
        $("#proveedor_rep").selectpicker('refresh');
    });
}

function agregarMarca() {
    $("#NewMarca").val("");
    $('#btnSumMarca').trigger("click");
}

function agregarProveedor() {
    $("#newNombreProv").val("");
    $("#newRutProv").val("");
    $("#newDireccionProv").val("");
    $("#newTelefonoProv").val("");
    $("#newCorreoProv").val("");
    $('#btnSumProveedor').trigger("click");
}

$("#Marcaform").on("submit", function(e) {
    e.preventDefault();

    var newMarca = $("#NewMarca").val();

    var formData = new FormData();
    formData.append("marca", newMarca);

    $.ajax({

        url: '../ajax/bodega_repuestos.php?op=agregarMarca',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Marca guardada.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                cargaMarcasRep();
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Marca no guardada',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                cargaMarcasRep();
                $(".close").trigger("click");
            }
        }

    });

});

$("#Proveedorform").on("submit", function(e) {
    e.preventDefault();

    var newNombreProv = $("#newNombreProv").val();
    var newRutProv = $("#newRutProv").val();
    var newDireccionProv = $("#newDireccionProv").val();
    var newRegionProv = $("#idregiones").val();
    var newComunaProv = $("#idcomunas").val();
    var newContactoProv = $("#newContactoProv").val();
    var newTelefonoProv = $("#newTelefonoProv").val();
    var newCorreoProv = $("#newCorreoProv").val();

    var formData = new FormData();
    formData.append("nombreProveedor", newNombreProv);
    formData.append("rutProveedor", newRutProv);
    formData.append("direccionProveedor", newDireccionProv);
    formData.append("regionProveedor", newRegionProv);
    formData.append("comunaProveedor", newComunaProv);
    formData.append("contactoProveedor", newContactoProv);
    formData.append("telefonoProveedor", newTelefonoProv);
    formData.append("correoProveedor", newCorreoProv);

    $.ajax({

        url: '../ajax/bodega_repuestos.php?op=agregarProveedor',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data != 0) {
                new PNotify({
                    title: 'Correcto!',
                    text: 'Proveedor guardado.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                cargaProveedoresRep();
                $(".close").trigger("click");
            } else {
                new PNotify({
                    title: 'Error!',
                    text: 'Proveedor no guardado',
                    type: 'error',
                    styling: 'bootstrap3'
                });
                cargaProveedoresRep();
                $(".close").trigger("click");
            }
        }

    });

});

function levantaPopUpNroSerie(id_rep, cantT, nserie) {
    //console.log(2);
    if (id_rep == "") {
        //  NUEVO PRODUCTO
        cantActual = 1;
        cantTotal = cantT;
        $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);

        dataSet = [
            ['<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarNroSerie(' + nserie + ')"><i class="fa fa-times-circle"></i></button>',
                nserie
            ]
        ];

        $('#tblNroSerie').DataTable({
            destroy: true,
            data: dataSet,
            columns: [
                { title: "" },
                { title: "Números de serie agregados" }
            ],
            "bFilter": false,
            "lengthChange": false,
            "iDisplayLength": 10
        });


        $('#btnNroSerie').trigger("click");
    } else {
        // PRODUCTO YA EXISTE
        if ($("#sumaStock").val() == "1") {
            // AGREGA
            cantActual = 1;
            cantTotal = cantT;
            $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);

            dataSet = [
                ['<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarNroSerie(' + nserie + ')"><i class="fa fa-times-circle"></i></button>',
                    nserie
                ]
            ];

            $('#tblNroSerie').DataTable({
                destroy: true,
                data: dataSet,
                columns: [
                    { title: "" },
                    { title: "Números de serie agregados" }
                ],
                "bFilter": false,
                "lengthChange": false,
                "iDisplayLength": 10
            });


            $('#btnNroSerie').trigger("click");
        } else {
            //  EDITA
            $.post("../ajax/bodega_repuestos.php?op=consultaNroSerie", { idRpuesto: $('#id_repuesto').val(), id_factura_rep: $('#id_factura_rep').val() }, function(data) {
                data = JSON.parse(data);

                var boton_, ns_;

                for (i = 0; i < data.length; i++) {
                    boton_ = '<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarNroSerie(' + data[i]['nro_serie'] + ')"><i class="fa fa-times-circle"></i></button>';
                    ns_ = data[i]['nro_serie'];

                    dataSet.push([boton_, ns_]);
                }

                cantActual = data.length;
                cantTotal = cantT;
                $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);


                $('#tblNroSerie').DataTable({
                    destroy: true,
                    data: dataSet,
                    columns: [
                        { title: "" },
                        { title: "Números de serie agregados" }
                    ],
                    "bFilter": false,
                    "lengthChange": false,
                    "iDisplayLength": 10
                });

            });


            $('#btnNroSerie').trigger("click");
        }
    }
}

$("#formformularioRepuestos").on("submit", function(e) {
    e.preventDefault();

    if (document.getElementById("file_factura").files[0] != undefined) {
        var sizeByte = document.getElementById("file_factura").files[0].size / 1024;
    } else {
        sizeByte = 0;
    }
    if (sizeByte > 3000) {
        new PNotify({
            title: 'Error',
            text: 'El archivo no puede ser mayor a 3 MB',
            type: 'error',
            styling: 'bootstrap3'
        });
    } else {
        var id_repuesto = undefined;
        if ($("#id_repuesto").val() != "") {
            id_repuesto = $("#id_repuesto").val();
        } else {
            id_repuesto = "";
        }
        var id_factura_rep = $("#id_factura_rep").val();
        var cod_producto = $("#cod_producto").val();
        var nro_serie = $("#nro_serie").val();
        var marca_rep = $("#marca_rep").val();
        var nombre_rep = $("#nombre_rep").val();
        var modelo_rep = $("#modelo_rep").val();
        var observaciones_rep = $("#observaciones_rep").val();
        var categoria_rep = $("#categoria_rep").val();
        var proveedor_rep = $("#proveedor_rep").val();
        var nro_factura = $("#nro_factura").val();
        var file_factura = document.getElementById("file_factura").files[0];
        var precio_rep = $("#precio_rep").val();
        var cantidad_rep = $("#cantidad_rep").val();
        var ubicacion_rep = $("#ubicacion_rep").val();

        if (cantidad_rep > 1) {
            //console.log("1");
            dataSet = [];
            levantaPopUpNroSerie($("#id_repuesto").val(), cantidad_rep, nro_serie);
        } else {
            guardar(id_repuesto, id_factura_rep, cod_producto, nro_serie, marca_rep, nombre_rep, modelo_rep, observaciones_rep,
                categoria_rep, proveedor_rep, nro_factura, file_factura, precio_rep, cantidad_rep, ubicacion_rep);
        }

    }
});

function validaCodigoProducto() {
    var cod_producto = $("#cod_producto").val();
    $.post("../ajax/bodega_repuestos.php?op=validaCodigo", { cod_producto: cod_producto }, function(data) {
        data = JSON.parse(data);
        if (data == 0) {
            // no existe codigo
        } else {
            // si existe codigo
            new PNotify({
                title: 'Error!',
                text: 'El código ' + cod_producto + ' pertenece a otro producto.',
                type: 'error',
                styling: 'bootstrap3'
            });

            $("#cod_producto").trigger("focus");
        }
    });
}

function guardar(id_repuesto, id_factura_rep, cod_producto, nro_serie, marca_rep, nombre_rep, modelo_rep, observaciones_rep,
    categoria_rep, proveedor_rep, nro_factura, file_factura, precio_rep, cantidad_rep, ubicacion_rep) {

    var formData = new FormData();
    formData.append("idRpuesto", id_repuesto);
    formData.append("id_factura_rep", id_factura_rep);
    formData.append("cod_producto", cod_producto);
    formData.append("nro_serie", nro_serie);
    formData.append("marca_rep", marca_rep);
    formData.append("nombre_rep", nombre_rep);
    formData.append("modelo_rep", modelo_rep);
    formData.append("observaciones_rep", observaciones_rep);
    formData.append("categoria_rep", categoria_rep);
    formData.append("proveedor_rep", proveedor_rep);
    formData.append("nro_factura", nro_factura);
    formData.append("file_factura", file_factura);
    formData.append("precio_rep", precio_rep);
    formData.append("cantidad_rep", cantidad_rep);
    formData.append("ubicacion_rep", ubicacion_rep);

    //AGREGA NUEVO
    if ($("#id_repuesto").val() == "") {

        if ($("#file_factura").val() == "") {
            new PNotify({
                title: 'Error!',
                text: 'Debe adjuntar una factura',
                type: 'error',
                styling: 'bootstrap3'
            });
        } else {
            $.ajax({

                url: '../ajax/bodega_repuestos.php?op=agregarRepuesto',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data != 0) {
                        new PNotify({
                            title: 'Correcto!',
                            text: 'Repuesto guardado.',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        limpiar();
                        init();
                        $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                    } else {
                        new PNotify({
                            title: 'Error!',
                            text: 'Repuesto no guardado',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                        limpiar();
                        init();
                        $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                    }
                }

            });
        }
    } else {

        //SUMA STOCK
        if ($("#sumaStock").val() == "1") {
            if ($("#file_factura").val() == "") {
                new PNotify({
                    title: 'Error!',
                    text: 'Debe adjuntar una factura',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            } else {
                $.ajax({
                    url: '../ajax/bodega_repuestos.php?op=sumaStockRepuesto',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data != 0) {
                            new PNotify({
                                title: 'Correcto!',
                                text: 'Stock actualizado.',
                                type: 'success',
                                styling: 'bootstrap3'
                            });
                            limpiar();
                            init();
                            $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                        } else {
                            new PNotify({
                                title: 'Error!',
                                text: 'Stock no pudo ser actualizado.',
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                            limpiar();
                            init();
                            $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                        }
                    }
                });
            }
        }
        // EDITAR
        else {
            $.ajax({
                url: '../ajax/bodega_repuestos.php?op=editarRepuesto',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data != 0) {
                        new PNotify({
                            title: 'Correcto!',
                            text: 'Repuesto editado.',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        limpiar();
                        init();
                        $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                    } else {
                        new PNotify({
                            title: 'Error!',
                            text: 'Repuesto no pudo ser editado.',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                        limpiar();
                        init();
                        $("#ModalNroSerie").modal('hide'); //ocultamos el modal
                    }
                }
            });
        }
    }
}

function quitarNroSerie(nroserie) {

    for (let i = 0; i < dataSet.length; i++) {
        //var g = dataSet[i].indexOf(nroserie);
        if (dataSet[i][1].indexOf(nroserie) >= 0) {
            dataSet.splice(i, 1);

            cantActual = cantActual - 1;

            $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);

            $('#tblNroSerie').DataTable({
                destroy: true,
                data: dataSet,
                columns: [
                    { title: "" },
                    { title: "Números de serie agregados" }
                ],
                "bFilter": false,
                "lengthChange": false,
                "iDisplayLength": 10
            });

        }
    }

}

function agregarNroSerie() {

    if (cantActual >= cantTotal) {
        bootbox.alert("Ya fueron agregados todos los números de serie");
    } else {

        var boton = '<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarNroSerie(' + $("#NewNroSerie").val() + ')"><i class="fa fa-times-circle"></i></button>';
        var ns = $("#NewNroSerie").val();
        var existe = 0;
        var idRep = $('#id_repuesto').val();

        $.post("../ajax/bodega_repuestos.php?op=validaNroSerie", { nro_serie: ns, idRpuesto: idRep }, function(data) {
            data = JSON.parse(data);
            if (data == 0) {
                $('#tblNroSerie').DataTable().rows().data().each(function(value) {

                    var a = value[1];
                    if (a === ns && existe === 0) {
                        bootbox.alert("Este número de serie ya fue agregado");
                        existe = 1;
                    } else {
                        cantActual = cantActual + 1;

                        $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);

                        dataSet.push([boton, ns]);

                        $('#tblNroSerie').DataTable({
                            destroy: true,
                            data: dataSet,
                            columns: [
                                { title: "" },
                                { title: "Números de serie agregados" }
                            ],
                            "bFilter": false,
                            "lengthChange": false,
                            "iDisplayLength": 10
                        });

                        $("#NewNroSerie").val("");
                    }
                });
            } else {
                // si existe codigo
                existe = 1;
                $("#NewNroSerie").trigger("focus");

                bootbox.alert("Este número de serie ya fue agregado con otra factura");

            }
        });




    }

}

function editar(id_repuesto) {
    $("#btnVerFactura").show();
    $('#id_repuesto').val(id_repuesto);
    tablaFact = $('#tblFacturas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/bodega_repuestos.php?op=listarFacturas',
            type: "post",
            data: { idRpuesto: id_repuesto },
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
    $('#btnFacturas').trigger("click");
}

function verFactura() {
    window.open('../files/taller/' + $('#nombreFact').val());
    return false;
}

function editarXFactura(id_repuesto, id_factura) {
    $("#listadorepuestos").hide();
    $("#formulariorepuestos").show();
    $("#op_agregar").hide();
    $("#op_listar").show();
    $("#btnGuardar").prop("disabled", false);
    cargaMarcasRep();
    cargaCategoriasRep();
    cargaProveedoresRep();

    $.post("../ajax/bodega_repuestos.php?op=consultaRepuestoxFactura", { idRpuesto: id_repuesto, id_factura_rep: id_factura }, function(data) {
        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {


            $("#categoria_rep").val(data[i]['id_categoria']);
            $("#categoria_rep").selectpicker('refresh');
            $("#proveedor_rep").val(data[i]['id_proveedor']);
            $("#proveedor_rep").selectpicker('refresh');
            $("#marca_rep").val(data[i]['id_marca']);
            $("#marca_rep").selectpicker('refresh');

            $('#id_repuesto').val(data[i]['id_repuesto']);
            $('#id_factura_rep').val(data[i]['id_factura_rep']);
            $('#editCod_producto').val(data[i]['cod_producto']);
            $('#cod_producto').val(data[i]['cod_producto']);
            $('#nro_serie').val(data[i]['nro_serie']);
            $('#nombre_rep').val(data[i]['nombre']);
            $('#modelo_rep').val(data[i]['modelo']);
            $('#observaciones_rep').val(data[i]['descripcion']);
            $('#nro_factura').val(data[i]['factura_nro']);
            $('#precio_rep').val(data[i]['precio']);
            $('#cantidad_rep').val(data[i]['cantidad']);
            $('#ubicacion_rep').val(data[i]['ubicacion']);
            $('#nombreFact').val(data[i]['factura_file']);
        }
    });

    $("#cod_producto").prop("disabled", false);
    $("#nro_serie").prop("disabled", false);
    $("#nro_serie").hide();
    $("#btnVerNroSerie").show();
    $("#marca_rep").prop("disabled", false);
    $("#btnAgregarMarca").prop("disabled", false);
    $("#nombre_rep").prop("disabled", false);
    $("#modelo_rep").prop("disabled", false);
    $("#observaciones_rep").prop("disabled", false);
    $("#categoria_rep").prop("disabled", false);
    $("#ubicacion_rep").prop("disabled", false);
}

function agregar(id_repuesto) {
    $("#btnVerFactura").hide();
    $("#listadorepuestos").hide();
    $("#formulariorepuestos").show();
    $("#op_agregar").hide();
    $("#op_listar").show();
    $("#btnGuardar").prop("disabled", false);
    $('#sumaStock').val("1");
    cargaMarcasRep();
    cargaCategoriasRep();
    cargaProveedoresRep();

    $.post("../ajax/bodega_repuestos.php?op=consultaRepuesto", { idRpuesto: id_repuesto }, function(data) {
        data = JSON.parse(data);

        for (i = 0; i < data.length; i++) {
            $('#id_repuesto').val(data[i]['id_repuesto']);
            $('#cod_producto').val(data[i]['cod_producto']);
            //$('#nro_serie').val(data[i]['nro_serie']);
            $("#marca_rep").val(data[i]['id_marca']);
            $("#marca_rep").selectpicker('refresh');
            $('#nombre_rep').val(data[i]['nombre']);
            $('#modelo_rep').val(data[i]['modelo']);
            $('#observaciones_rep').val(data[i]['descripcion']);
            $("#categoria_rep").val(data[i]['id_categoria']);
            $("#categoria_rep").selectpicker('refresh');
            $('#ubicacion_rep').val(data[i]['ubicacion']);
        }
    });

    $("#cod_producto").prop("disabled", true);
    $("#nro_serie").prop("disabled", false);
    $("#nro_serie").show();
    $("#btnVerNroSerie").hide();
    $("#marca_rep").prop("disabled", true);
    $("#btnAgregarMarca").prop("disabled", true);
    $("#nombre_rep").prop("disabled", true);
    $("#modelo_rep").prop("disabled", true);
    $("#observaciones_rep").prop("disabled", false);
    $("#categoria_rep").prop("disabled", true);
    $("#ubicacion_rep").prop("disabled", false);
}

function detalle(id_repuesto) {

    tablaDetalle = $('#tblmovimientos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/bodega_repuestos.php?op=detalle',
            type: "post",
            data: { idRpuesto: id_repuesto },
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
                [2, "desc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();

    $('#btnDetalleMov').trigger("click");
}

function limpiar() {
    $("#newNombreProv").val("");
    $("#newRutProv").val("");
    $("#newDireccionProv").val("");
    $("#newTelefonoProv").val("");
    $("#newCorreoProv").val("");

    $("#NewMarca").val("");

    $('#sumaStock').val("");
    $('#id_repuesto').val("");
    $('#id_factura_rep').val("");
    $("#cod_producto").val("");
    $("#nro_serie").val("");
    $("#marca_rep").val("");
    $("#marca_rep").selectpicker('refresh');
    $("#nombre_rep").val("");
    $("#modelo_rep").val("");
    $("#observaciones_rep").val("");
    $("#categoria_rep").val("");
    $("#categoria_rep").selectpicker('refresh');
    $("#proveedor_rep").val("");
    $("#proveedor_rep").selectpicker('refresh');
    $("#nro_factura").val("");
    $("#file_factura").val("");
    $("#precio_rep").val("");
    $("#cantidad_rep").val("");
    $("#ubicacion_rep").val("");

    $("#cod_producto").prop("disabled", false);
    $("#nro_serie").prop("disabled", false);
    $("#marca_rep").prop("disabled", false);
    $("#btnAgregarMarca").prop("disabled", false);
    $("#nombre_rep").prop("disabled", false);
    $("#modelo_rep").prop("disabled", false);
    $("#observaciones_rep").prop("disabled", false);
    $("#categoria_rep").prop("disabled", false);
    $("#ubicacion_rep").prop("disabled", false);
}

function guardaNroSerie() {

    if (cantActual < cantTotal) {
        bootbox.alert("Debe agregar todos los números de serie para poder guardar");
    } else {
        var newDataSet = [];
        for (let i = 0; i < dataSet.length; i++) {
            newDataSet.push(dataSet[i][1]);
        }

        var id_repuesto = undefined;
        if ($("#id_repuesto").val() != "") {
            id_repuesto = $("#id_repuesto").val();
        } else {
            id_repuesto = "";
        }
        var id_factura_rep = $("#id_factura_rep").val();
        var cod_producto = $("#cod_producto").val();
        var marca_rep = $("#marca_rep").val();
        var nombre_rep = $("#nombre_rep").val();
        var modelo_rep = $("#modelo_rep").val();
        var observaciones_rep = $("#observaciones_rep").val();
        var categoria_rep = $("#categoria_rep").val();
        var proveedor_rep = $("#proveedor_rep").val();
        var nro_factura = $("#nro_factura").val();
        var file_factura = document.getElementById("file_factura").files[0];
        var precio_rep = $("#precio_rep").val();
        var cantidad_rep = $("#cantidad_rep").val();
        var ubicacion_rep = $("#ubicacion_rep").val();

        guardar(id_repuesto, id_factura_rep, cod_producto, newDataSet, marca_rep, nombre_rep, modelo_rep, observaciones_rep,
            categoria_rep, proveedor_rep, nro_factura, file_factura, precio_rep, cantidad_rep, ubicacion_rep);
    }
}

function verNroSerie() {

    dataSet = [];
    $.post("../ajax/bodega_repuestos.php?op=consultaNroSerie", { idRpuesto: $('#id_repuesto').val(), id_factura_rep: $('#id_factura_rep').val() }, function(data) {
        data = JSON.parse(data);
        //console.log(data.length);
        //console.log(data);

        var boton, ns;

        for (i = 0; i < data.length; i++) {
            boton = '<button class="btn btn-danger btn-xs" data-toggle="tooltip" title="Quitar" onclick="quitarNroSerie(' + data[i]['nro_serie'] + ')"><i class="fa fa-times-circle"></i></button>';
            ns = data[i]['nro_serie'];
            //console.log(boton, ns);
            dataSet.push([boton, ns]);
        }

        cantActual = data.length;
        cantTotal = data.length;
        $("#lblCan").html('Números agregados: ' + cantActual + ' de ' + cantTotal);


        $('#tblNroSerie').DataTable({
            destroy: true,
            data: dataSet,
            columns: [
                { title: "" },
                { title: "Números de serie agregados" }
            ],
            "bFilter": false,
            "lengthChange": false,
            "iDisplayLength": 10
        });

        $('#btnNroSerie').trigger("click");

    });
}

function seleccionarFact(id_factura_rep) {

    var idrepu = $('#id_repuesto').val();
    editarXFactura(idrepu, id_factura_rep);
    $('#btnFacturas').trigger("click");
}

init();