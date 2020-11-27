var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})

	$.post("../ajax/operador.php?op=selectOperador", function(r){
		$("#idoperador").html(r);
		$("#idoperador").selectpicker('refresh');
	});

}


// Otras funciones
function limpiar(){

	$("#idchip").val("");
	$("#idoperador").val("");
	$("#idoperador").selectpicker('refresh');	
	$("#serial").val("");
	$("#numero").val("");
	$("#pin").val("");
	$("#puk").val("");

}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadochips").hide();
		$("#formulariochips").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadochips").show();
		$("#formulariochips").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblchips').dataTable({
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
			url:'../ajax/chip.php?op=listar',
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
		"order" : [[1 , "desc"]] //Ordenar en base a la columna 0 descendente
	}).DataTable();
}

function guardaryeditar(e){
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url:'../ajax/chip.php?op=guardaryeditar',
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

function mostar(idchip){
	$.post("../ajax/chip.php?op=mostar",{idchip:idchip}, function(data,status){
		data = JSON.parse(data);
		mostarform(true);
	
		$("#idchip").val(data.idchip);
		$("#idoperador").val(data.idoperador);
		$("#idoperador").selectpicker('refresh');
		$("#serial").val(data.serial);
		$("#numero").val(data.numero);
		$("#pin").val(data.pin);
		$("#puk").val(data.puk);

	})
}

function desactivar(idchip){

	bootbox.confirm("Esta seguro que quiere inhabilitar la tarjeta SIM?", function(resultado){
            
		if(resultado){                    
                    bootbox.prompt({
                        title: "Indique el Motivo por el cual se esta Inhabilitando el SIM.",
                        inputType: 'textarea',
                        className: 'bootbox-custom-class',
                        callback: function (result) {
                           if(result !== null){
                             if($.trim(result).length > 0){
                                $.post("../ajax/chip.php?op=inhabilitarDefinitivamente",{idchip:idchip,"detalle":result}, function(e){
                                    bootbox.alert(e);
                                    tabla.ajax.reload();
                                })
                             }else{
                                 new PNotify({
                                    title: 'Error!',
                                    text: 'Debe indicar el Motivo por el cual se esta Inhabilitando el SIM.',
                                    type: 'error',
                                    styling: 'bootstrap3'
                                }); 
                             }   
                           } 
                        }
                }).on("shown.bs.modal", function (event) {        
                    $('.bootbox-custom-class').find('.bootbox-input').css("text-transform", "uppercase");
                });   
            }
	});
}

function activar(idchip){

	bootbox.confirm("Esta seguro que quiere habilitar la tarjeta SIM?", function(result){
		if(result){
			$.post("../ajax/chip.php?op=activar",{idchip:idchip}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			})	
		}
	})
}


init();