$("#frmAcceso").on('submit', function(e){
	e.preventDefault();
	username = $("#username").val();
	password = $("#password").val();
	
	$.post("../ajax/usuario.php?op=verificar",{"username_form": username, "password_form": password}, function(data){
		
		if(data!="null"){
			data = JSON.parse(data);
			if(data.username == username){
				$(location).attr("href", "inicio.php");
			}
			else{
				bootbox.alert("Usuario o Password Incorrectos")
			}
		}else{
			bootbox.alert("Usuario o Password Incorrectos");
		}

	})

})