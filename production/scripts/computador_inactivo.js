/* global bootbox */
var tabla;

//funcion que se ejecuta iniciando
function init() {
    listarhistoricos();

	$('[data-toggle="tooltip"]').tooltip();


}

function listarhistoricos() {
    tabla = $('#tblcomputadoreshistorico').dataTable({
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
            url: '../ajax/computador.php?op=listarhistorico',
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


function desactivar(idcomputador,disponible) {
	//disponible = 0 -> asignado | disponible = 1 -> sin asignar //
	
	var mensaje;
	if (disponible == 0){
		mensaje = 'Este dispositivo está asignado, ¿Está seguro que quiere inhabilitarlo?';
	}
	if (disponible == 1){
		mensaje = '¿Está seguro que quiere inhabilitar el dispositivo?';
	}

	bootbox.confirm(mensaje, function (result) {
		if (result) {
			$.post("../ajax/computador.php?op=desactivar", { idcomputador: idcomputador }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	});
}

function activar(idcomputador) {

	bootbox.confirm("SEGURO QUIERE HABILITAR EL COMPUTADOR?", function (result) {
		if (result) {
			$.post("../ajax/computador.php?op=activar", { idcomputador: idcomputador }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	});
}


init();