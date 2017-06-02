$(document).ready(function(){
    loguear();
});



function traerEmpleados(){
    $.ajax({
        url:'empleados',
        type: "GET"
    }).then(empleados, error)
}

function empleados(data){
    if(data.exito){
        $("#info").html("<h2>" + data.empleados[0] + "</h2>");
    }
    else{
        $("#info").html("<h2> Usted no tiene los permisos para lo requerido </h2>");
    }
}










// TODO poner desloguear en una funcion como loguear()
function logueado(data){
    
    if(data.exito){
        var htmlLogueado = '<h4 class="navbar-text"> Bienvenido  ' +  data.usuario + ' </h4>';
        htmlLogueado += '  <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>'
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        desloguear();
    }

}



function deslogueado(data){
    
    if(data.exito){
        var htmlDeslogueado =' <form class="form-signin navbar-form" >';
        htmlDeslogueado +=  '<input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario">';
        htmlDeslogueado +=     '<input type="password" class="form-control" placeholder="password" name="pass" id="pass">';
        htmlDeslogueado +='<button class="btn btn-default" type="button" id="login"> Login </button> </form>'
        $("#logout").html(htmlDeslogueado);
        $("#logout").attr('id', 'log');
        loguear();
    }
    else{
        console.info(data)
    }
}

function error(data) {
    alert("error");
    console.info(data);
    console.info(data.data);
}




function loguear(){
    $("#login").click(function(){
        var usuario = $("#usuario").val();
        var pass = $("#pass").val();
        $.ajax({
            url: "login",
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
