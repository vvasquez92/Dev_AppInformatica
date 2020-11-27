var signaturePad;

function init(){
    $.post("../ajax/usuario.php?op=buscaFirma", {  }, function (data) {
        data = JSON.parse(data);
        
        if (data.length == 0){
            //alert("no tiene firma registrada");
            $("#titulo").hide();
            $("#firma").show();

            var canvas = document.getElementById('firmafi');
            canvas.height = canvas.offsetHeight;
            canvas.width = canvas.offsetWidth;
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
        }
        else{
            $("#titulo").show();
            $("#firma").hide();
        }
    });
}

function fijarfirma(){    
            
    if(signaturePad.isEmpty()){
        new PNotify({
                    title: 'Error en la firma',
                    text: 'La firma no puede estar vacia',
                    type: 'error',
                    styling: 'bootstrap3'
                });
    }else{
        const padfirma = signaturePad.toDataURL("image/jpeg", 100);
        if(padfirma){
        $("#firmavali").val("Firma validada");
        $("#firmavali").addClass(' border border-success');
        $("#firma").val(padfirma);
        $("#firmapad").hide();
        }else{
            $("#firmavali").val("Error al validar");
            $("#firmavali").addClass(' border border-danger');
        }     
    }
          
}

function borrarfirma(){
    $("#firmavali").val("");
    signaturePad.clear();
    $("#firmapad").show();
}

$("#firmaForm").on("submit", function (e) {
    $("#btnGuardar").prop('disabled', true);
    
    e.preventDefault();    

    if($("#firmavali").val() == ""){
        new PNotify({
            title: 'Error en la firma',
            text: 'Debe validar la firma',
            type: 'error',
            styling: 'bootstrap3'
        });
        $("#btnGuardar").prop('disabled', false)
    }else{

        var firmaRecibe = $("#firma").val();

        var formData = new FormData();
        formData.append("firma", firmaRecibe);

        $.ajax({
            url:'../ajax/usuario.php?op=guardaFirmaUsuario',
            type:"POST",
            data:formData,
            contentType: false,
            processData:false,

            success: function(datos){               
                new PNotify({
                    title: 'Correcto!',
                    text: 'Firma guardada.',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                init();
            }
        });
    }
});

init();