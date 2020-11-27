var tabla;

//funcion que se ejecuta iniciando
function init(){
    
	mostarform(false);
        
	listar();

	$("#formulario").on("submit", function(e){
		guardaryeditar(e);
	})


	$.post("../ajax/departamentos.php?op=selectDepartamentosIntranet", function(r){
		$("#iddepartamento").html(r);
		$("#iddepartamento").selectpicker('refresh');
	});

        
}

// Otras funciones
function limpiar(){

	$("#idnoticia").val("");
        $("#titulo").val("");
        $("#contenido").val("");
	$("#iddepartamento").val("");
	$("#iddepartamento").selectpicker('refresh');	        
        $('#galleryImgs').val("");
        $('#div_imagenes').html('');
}

function mostarform(flag){

	limpiar();
	if(flag){
		$("#listadonoticias").hide();
		$("#formularionoticias").show();
		$("#op_agregar").hide();
		$("#op_listar").show();
		$("#btnGuardar").prop("disabled", false);

	}else{
		$("#listadonoticias").show();
		$("#formularionoticias").hide();
		$("#op_agregar").show();
		$("#op_listar").hide();
	}

}

function cancelarform(){
	limpiar();
	mostarform(false);
}

function listar(){
	tabla=$('#tblnoticias').dataTable({
		"aProcessing":true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:[
			'excelHtml5',
			'pdf'
		],
		"ajax":{
			url:'../ajax/noticia.php?op=listar',
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
        
        var $fileUpload = $("#galleryImgs");
        
        if (parseInt($fileUpload.get(0).files.length)>2){
            
            new PNotify({
                title: 'Error!',
                text: 'Tu puedes Subir Solamente un Máximo de 2 imágenes.',
                type: 'error',
                styling: 'bootstrap3'
            });
            
            $('#galleryImgs').val("");
            
        }else{
          	$("#btnGuardar").prop("disabled", true);
                var formData = new FormData($("#formulario")[0]);
                $.ajax({
                        url:'../ajax/noticia.php?op=guardaryeditar',
                        type:"POST",
                        data:formData,
                        contentType: false,
                        processData:false,

                        success: function(datos){
                                bootbox.alert(datos);
                                mostarform(false);
                                tabla.ajax.reload();
                                limpiar(); 
                        }
                });
                
        }
        

}

function mostrar(idnoticia){
    
	$.post("../ajax/noticia.php?op=mostar",{idnoticia:idnoticia}, function(resp,status){
		resp = JSON.parse(resp);
		mostarform(true);	
		$("#idnoticia").val(resp.respuesta.idnoticia);
                $("#titulo").val(resp.respuesta.titulo);
                $("#contenido").val(resp.respuesta.contenido);
		$("#iddepartamento").val(resp.respuesta.iddepartamento);
		$("#iddepartamento").selectpicker('refresh');
                
                var str="";
                for(var i=0; i<resp.data.length; i++){
                    str +='<span style="margin: 50px 10px; padding: 5px;" ><img  src="../files/noticias/'+resp.data[i].url+'" width="400" height="400"></span>';
                }
                $("#div_imagenes").html(str);                
	});
}

function desactivar(idnoticia){

	bootbox.confirm("Esta seguro que quiere desactivar la noticia?", function(result){
		if(result){
			$.post("../ajax/noticia.php?op=desactivar",{idnoticia:idnoticia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function activar(idnoticia){

	bootbox.confirm("Esta seguro que quiere activar la noticia?", function(result){
		if(result){
			$.post("../ajax/noticia.php?op=activar",{idnoticia:idnoticia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function agregarHome(idnoticia){

	bootbox.confirm("Esta seguro que quiere activar la noticia en el HOME?", function(result){
		if(result){
			$.post("../ajax/noticia.php?op=agregarHome",{idnoticia:idnoticia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function quitarHome(idnoticia){

	bootbox.confirm("Esta seguro que quiere desactivar la noticia del HOME?", function(result){
		if(result){
			$.post("../ajax/noticia.php?op=quitarHome",{idnoticia:idnoticia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

function eliminar(idnoticia){

	bootbox.confirm("Esta seguro que quiere eliminar la noticia?", function(result){
		if(result){
			$.post("../ajax/noticia.php?op=eliminar",{idnoticia:idnoticia}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});	
		}
	});
}

init();