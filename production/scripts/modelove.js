var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
    })
    
    $.post("../ajax/marcave.php?op=selectmarcave", function (r) {
		$("#idmarca").html(r);
		$("#idmarca").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar(){

    $("#idmodelove").val("");
    $("#idmarca").val("");
    $("#idmarca").selectpicker('refresh');
	$("#nombre").val("");
}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadomodelos").hide();
		$("#formulariomodelos").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadomodelos").show();
		$("#formulariomodelos").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblmodelos').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[
			'copyHtml5',
			'print',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax":{
			url:'../ajax/modelove.php?op=listar',
			type:"get",
			dataType:"json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		  },
		"bDestroy": true,
		"iDisplayLength": 10, //Paginacion 10 items
		"order" : [[1 , "asc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function guardaryeditar(e){
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url:'../ajax/modelove.php?op=guardaryeditar',
		type:"POST",
		data:formData,
		contentType: false,
		processData:false,

		success: function(datos){
			bootbox.alert(datos);
			mostarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

function mostar(idmodelove){
	$.post("../ajax/modelove.php?op=mostar",{idmodelove:idmodelove}, function(data,status){
        
		data = JSON.parse(data);
		mostarform(true);
	
        $("#idmodelove").val(data.idmodelove);
        $("#idmarca").val(data.idmarca);
        $("#idmarca").selectpicker('refresh');
		$("#nombre").val(data.nombre);

	})
}

function eliminar(idmodelove) {
	$.post("../ajax/modelove.php?op=eliminar", { idmodelove: idmodelove }, function (data, status) {
		bootbox.alert("Modelo quitado");
		mostarform(false);
		tabla.ajax.reload();
	})
}



init();