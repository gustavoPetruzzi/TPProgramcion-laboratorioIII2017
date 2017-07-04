function logueado(data,status, xhr){
    if(xhr.status = 200){
        localStorage.setItem('token', data.token);
        var htmlLogueado = '<h4 class="navbar-text"> Bienvenido  ' +  data.usuario + ' </h4>';
        htmlLogueado += '  <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>';
        
        estacionamiento();
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        if(data.usuario == 'admin'){
            $("#empleados").removeClass("hidden");
            $("#cocheras").removeClass("hidden");
        }
        $("#estacionamiento").removeClass("hidden");
        $("#operaciones").removeClass("hidden");
        desloguear();
    }
    else{
        alert(data.mensaje);
    }

}



function deslogueado(data){
    if(data.exito){
        var htmlDeslogueado =' <form class="navbar-form navbar-right" >';
        htmlDeslogueado +='<div class="form-group">';
        htmlDeslogueado +=  '<input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario"> </div>';
        htmlDeslogueado +='<div class="form-group">';
        htmlDeslogueado +=  '<input type="password" class="form-control" placeholder="password" name="pass" id="pass"> </div>';
        htmlDeslogueado +='<button class="btn btn-default" type="button" id="login"><span class="glyphicon glyphicon-log-in"></span> Login </button> </form>'
        $("#logout").html(htmlDeslogueado);
        $("#logout").attr('id', 'log');
        $("#empleados").addClass('hidden');
        $("#estacionamiento").addClass("hidden");
        $("#operaciones").addClass("hidden");
        $("#cocheras").addClass("hidden");
        $("#info").html("");
        $("#precios").html("");
        $("#respuesta").html("");
        $("#opciones").html("");
        loguear();
    }
    else{
        console.info(data)
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

        }).then(logueado, error)
    })
}
function desloguear(){
    $("#desLogin").click(function(){ 
        urlLogout = 'log/out';
        $.ajax({
            url:urlLogout,
            type:"GET",
            headers: { token : localStorage.getItem('token')},
        }).then(deslogueado, error)
    })
}