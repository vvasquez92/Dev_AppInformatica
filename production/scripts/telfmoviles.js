var tabla;


//funcion que se ejecuta iniciando
function init() {
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip();


}

function mostarform(flag) {

	$("#listadoasignacion").show();

}


function listar() {
	tabla = $('#tblasignacion').dataTable({
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
			url: '../ajax/asignacion.php?op=listartelf',
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
		"order": [[0, "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

init();