var tabla;


function init(){
    
	mostarform(false);
        
	listar();

	$('[data-toggle="tooltip"]').tooltip(); 

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})

	$.post("../ajax/tdispositivo.php?op=selecttdispositivo", function(r){
		$("#tdispositivo").html(r);
		$("#tdispositivo").selectpicker('refresh');
	});
        
  $(document).on('change','input[type="file"]',function(){
	// this.files[0].size recupera el tamaño del archivo
	// alert(this.files[0].size);
	
	var fileName = this.files[0].name;
	var fileSize = this.files[0].size;
     
	if(fileSize > 5242880){
                new PNotify({
                        title: 'Error!',
                        text: 'El archivo no debe superar 5 MB.',
                        type: 'error',
                        styling: 'bootstrap3'
                    }); 
		this.value = '';
		this.files[0].name = '';
	}else{
		// recuperamos la extensión del archivo
		var ext = fileName.split('.').pop();

		// console.log(ext);
		switch (ext) {
                    
			case 'pdf': 
                            
                        break;
                        
			default:
                                new PNotify({
                                    title: 'Error!',
                                    text: 'El archivo de tipo '+ ext +' no es valido.',
                                    type: 'error',
                                    styling: 'bootstrap3'
                                }); 
				this.value = ''; // reset del valor
				this.files[0].name = '';
		}   
            }
        });

    $("#ip").focusout(function () {
        
        if($.trim($("#ip").val()).length>0){
             
            var expreg = /^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/;
             
            if( expreg.test($("#ip").val()) ){
                
                    var res = $("#ip").val().split(".");
                    
                    if(res[0]<=255 && res[1]<=255 && res[2]<=255 && res[3]<=255)
                    {

                        $.post( "../ajax/dispositivo.php?op=validarIpRegistrada", 
                               { "iddispositivo": $("#iddispositivo").val(), "ip": $("#ip").val() }, 
                               function( data ) {

                               if(parseInt(data.cantidad)==0){

                                    $("#btnGuardar").prop("disabled", false);

                                   new PNotify({
                                       title: 'Correcto!',
                                       text: 'El ip es válido.',
                                       type: 'success',
                                       styling: 'bootstrap3'
                                   });

                               }else{

                                   $("#btnGuardar").prop("disabled", true);

                                   new PNotify({
                                       title: 'Error!',
                                       text: 'El ip ya se encuentra asignada a otro computador o dispositivo.',
                                       type: 'error',
                                       styling: 'bootstrap3'
                                   });
                               }
                       }, "json");
                       
                    }else{
                        
                        $("#btnGuardar").prop("disabled", true);

                        new PNotify({
                            title: 'Error!',
                            text: 'El formato de ip es inválido',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }      
            }else{
               
               $("#btnGuardar").prop("disabled", true);

                    new PNotify({
                        title: 'Error!',
                        text: 'El formato de ip es inválido',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
           }
               
        }else{
           $("#btnGuardar").prop("disabled", false); 
        }
    });

}


// Otras funciones
function limpiar(){
	$("#iddispositivo").val("");
	$("#tdispositivo").val("");
	$("#tdispositivo").selectpicker('refresh');
        $("#ip").val("");
        $("#marca").val("");      
        $("#modelo").val("");
        $("#estado").val("");
	$("#estado").selectpicker('refresh');
        $("#serial").val("");
        $("#maclan").val("");
        $("#macwifi").val("");
        $("#observaciones").val("");
        $("#factura").val("");
        $("#factura_actual").val("");
        $("#fvencimiento_garantia").val("");
        $("#previa_factura").html("");
}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadodispositivos").hide();
		$("#formulariodispositivos").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadodispositivos").show();
		$("#formulariodispositivos").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tbldispositivos').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax":{
			url:'../ajax/dispositivo.php?op=listar',
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
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url:'../ajax/dispositivo.php?op=guardaryeditar',
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

function mostrar(iddispositivo){
	$.post("../ajax/dispositivo.php?op=mostrar",{iddispositivo:iddispositivo}, function(data,status){
            
		data = JSON.parse(data);
		mostarform(true);
                
                $("#iddispositivo").val(data.iddispositivo);
                $("#tdispositivo").val(data.tdispositivo);
                $("#tdispositivo").selectpicker('refresh');
                $("#ip").val(data.ip);
                $("#marca").val(data.marca);
                $("#modelo").val(data.modelo);
                $("#estado").val(data.estado);
                $("#estado").selectpicker('refresh');
                $("#serial").val(data.serial);
                $("#maclan").val(data.maclan);
                $("#macwifi").val(data.macwifi);
                $("#factura_actual").val(data.factura);
                $("#fvencimiento_garantia").val(data.fvencimiento_garantia);
                $("#estado").val(data.estado);
                $("#estado").selectpicker('refresh');
                $("#observaciones").val(data.observaciones);   
                 if(data.factura !="" && data.factura != null){
                    $("#previa_factura").html('<button class="btn btn-secondary" onclick="window.open(\'../files/facturasdispositivo/'+data.factura+'\');return false;"  ><i class="fa fa-file-pdf-o"></i></button>');
                }
	})
}

function desactivar(iddispositivo){

	bootbox.confirm("SEGURO QUIERE INHABILITAR EL DISPOSITIVO?", function(result){
		if(result){
			$.post("../ajax/dispositivo.php?op=desactivar",{iddispositivo:iddispositivo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function activar(iddispositivo){

	bootbox.confirm("SEGURO QUIERE HABILITAR EL DISPOSITIVO?", function(result){
		if(result){
			$.post("../ajax/dispositivo.php?op=activar",{iddispositivo:iddispositivo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}


init();