function logueado(data,status, xhr){
    if(xhr.status = 200){
        localStorage.setItem('token', data.token);
        
        var htmlLogueado = `<button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>`;
        var usuario = `<h3 class="text-center"> Bienvenido ${data.usuario} </h3>
                        <img src="images/banana.gif" class="img-responsive" >`;
        $("#infoUsuario").html(usuario);
        estacionamiento();
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        if(data.admin == 1){
            $("#empleados").removeClass("hidden");
            $("#cocheras").removeClass("hidden");
            $("#autos").removeClass("hidden");
        }
        $("#estacionamiento").removeClass("hidden");
        desloguear();
    }
    else{
        alert(data.mensaje);
    }

}



function deslogueado(data){
    if(data.exito){

        var htmlDeslogueado =`<form id="log" class="navbar-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario">
                                </div>
            
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="password" name="pass" id="pass">
                                </div> 
                                <button class="btn btn-default" type="button" id="login"><span class="glyphicon glyphicon-log-in"></span> Login </button>
                
                                </form>
            </div>`;
        $("#logout").html(htmlDeslogueado);
        $("#logout").attr('id', 'log');
        $("#empleados").addClass('hidden');
        $("#estacionamiento").addClass("hidden");
        $("#operaciones").addClass("hidden");
        $("#cocheras").addClass("hidden");
        $("#autos").addClass("hidden");
        $("#infoUsuario").html("");
        $("#info").html("");
        $("#precios").html("");
        $("#respuesta").html("");
        $("#usados").remove();
        $("#opciones").html("");
        loguear();
    }
    else{
        alert(data);
    }
}

function error(data) {
    alert("error");
    console.info(data);
}




function loguear(){
    $("#login").click(function(){
        var usuario = $("#usuario").val();
        var pass = $("#pass").val();
        $.ajax({
            url: "log/in",
            type:"POST",
            data: { usuario : usuario, pass: pass},

        }).then(logueado, errores)
    })
}
function desloguear(){
    $("#desLogin").click(function(){ 
        urlLogout = 'log/out';
        $.ajax({
            url:urlLogout,
            type:"GET",
            headers: { token : localStorage.getItem('token')},
        }).then(deslogueado, errores)
    })
}