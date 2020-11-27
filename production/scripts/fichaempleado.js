
//funcion que se ejecuta iniciando
function init() {

        $.post("../ajax/empleado.php?op=selectempleado", function (r) {
                $("#idempleado").html(r);
                $("#idempleado").selectpicker('refresh');
        });

        $('[data-toggle="tooltip"]').tooltip();

        $("#idempleado").change(function () {

                $.post("../ajax/empleado.php?op=mostar", { "idempleado": $(this).val() }, function (data) {

                        $("#FichaEmpleado").show();
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
                                data: { "idempleado": $(this).val() },
                                error: function (e) {
                                        console.log(e.responseText);
                                }
                        },
                        "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
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
                                data: { "idempleado": $(this).val() },
                                error: function (e) {
                                        console.log(e.responseText);
                                }
                        },
                        "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
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
                                data: { "idempleado": $(this).val() },
                                error: function (e) {
                                        console.log(e.responseText);
                                }
                        },
                        "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
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
                                data: { "idempleado": $(this).val() },
                                error: function (e) {
                                        console.log(e.responseText);
                                }
                        },
                        "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                          },
                        "bDestroy": true,
                        "iDisplayLength": 5, //Paginacion 10 items
                        "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
                }).DataTable();

        });

}



init();