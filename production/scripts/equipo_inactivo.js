var tabla;

//funcion que se ejecuta iniciando
function init(){

    listarhistoricos();
	
	$('[data-toggle="tooltip"]').tooltip(); 
	
	$.post("../ajax/detalle.php?op=selectDetalle", function(r){
		$("#iddetalle").html(r);
		$("#iddetalle").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar(){

	$("#idequipo").val("");
	$("#iddetalle").val("");
	$("#iddetalle").selectpicker('refresh');	
	$("#imei").val("");
	$("#serial").val("");
	$("#caja").val("");
	$("#estado").val("");
	$("#estado").selectpicker('refresh');

}

function listarhistoricos() {
    tabla = $('#tblmovileshistorico').dataTable({
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
            url: '../ajax/equipo.php?op=listarhistorico',
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

function mostar(idequipo){
	$.post("../ajax/equipo.php?op=mostar",{idequipo:idequipo}, function(data,status){
		data = JSON.parse(data);
		mostarform(true);
	
		$("#idequipo").val(data.idequipo);
		$("#iddetalle").val(data.iddetalle);
		$("#iddetalle").selectpicker('refresh');
		$("#imei").val(data.imei);
		$("#serial").val(data.serial);
		$("#caja").val(data.caja);
		$("#estado").val(data.estado);
		$("#estado").selectpicker('refresh');

	})
}

function activar(idequipo){

	bootbox.confirm("Esta seguro que quiere habilitar el telefono movil?", function(result){
		if(result){
			$.post("../ajax/equipo.php?op=activar",{idequipo:idequipo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			})	
		}
	})
}


init();