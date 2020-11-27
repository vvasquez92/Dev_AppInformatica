
var datos = [];


//funcion que se ejecuta iniciando
function init(){
	mostarform();

	$.post("http://172.16.32.209/general/information.html?kind=item", function(r){
                var html = r;
                var res = r.split('<div class="contentsGroup">');
                console.log(res);
	});

}

function mostarform(){
		$("#prevencion").show();
}

init();