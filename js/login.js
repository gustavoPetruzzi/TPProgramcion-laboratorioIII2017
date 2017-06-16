function logueado(data){
    if(data.exito){
        var htmlLogueado = '<h4 class="navbar-text"> Bienvenido  ' +  data.empleado + ' </h4>';
        htmlLogueado += '  <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>';
        
        estacionamiento();
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        if(data.empleado == 'admin'){
            $("#empleados").removeClass("hidden");
        }
        $("#autos").removeClass("hidden");
        $("#operaciones").removeClass("hidden");
        desloguear();
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
        $("#autos").addClass("hidden");
        $("#operaciones").addClass("hidden");
        $("#info").html("");
        $("#precios").html("");
        $("#respuesta").html("");
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
            url: "empleados/login",
            type:"POST",
            data: { usuario : usuario, pass: pass},

        }).then(logueado, error)
    })
}
function desloguear(){
    $("#desLogin").click(function(){ 
        $.ajax({
            url:"desloguear",
            type:"POST",
        }).then(deslogueado, error)
    })
}