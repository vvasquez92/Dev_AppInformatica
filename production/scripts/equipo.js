var tabla;

//funcion que se ejecuta iniciando
function init(){
	mostarform(false);
	listar();
	cargaTotales();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})

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

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadomoviles").hide();
		$("#formularimoviles").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadomoviles").show();
		$("#formularimoviles").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblmoviles').dataTable({
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
			url:'../ajax/equipo.php?op=listar',
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
		url:'../ajax/equipo.php?op=guardaryeditar',
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

function cargaTotales(){
	$.post("../ajax/equipo.php?op=cargaTotales", function(data,status){
		data = JSON.parse(data);

		$("#lblAsignados").html("TOTAL ASIGNADOS: " + data.libres);
		$("#lblLibres").html("TOTAL SIN ASIGNAR: " + data.asignados);
		$("#lblUsados").html("TOTAL USADOS: " + data.usados);
		$("#lblNuevos").html("TOTAL NUEVOS: " + data.nuevos);

	})
}

function desactivar(idequipo,disponible){
	//disponible = 0 -> asignado | disponible = 1 -> sin asignar //
	
	var mensaje;
	if (disponible == 0){
		mensaje = 'Este equipo está asignado, ¿Está seguro que quiere inhabilitarlo?';
	}
	if (disponible == 1){
		mensaje = '¿Está seguro que quiere inhabilitar el equipo?';
	}

	bootbox.confirm(mensaje, function(resultado){		
            if(resultado){                    
                bootbox.prompt({
                        title: "Indique el Motivo por el cual se esta Inhabilitando el Teléfono.",
                        inputType: 'textarea',
                        className: 'bootbox-custom-class',
                        callback: function (result) {
                           if(result !== null){
                             if($.trim(result).length > 0){
                                $.post("../ajax/equipo.php?op=inhabilitarEquipo",{idequipo:idequipo, detalle: result}, function(e){
                                    bootbox.alert(e);
                                    tabla.ajax.reload();
                                });
                             }else{
                                 new PNotify({
                                    title: 'Error!',
                                    text: 'Debe indicar el Motivo por el cual se esta Inhabilitando el Teléfono.',
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