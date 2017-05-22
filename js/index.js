$(document).ready(function(){
    loguear();
});





function logueado(data){
    $("#log").html(data);
    $("#log").attr('id', 'logout');
    $("#desLogin").click(function(){
        $.ajax({
            url:"php/administracion.php",
            type:"POST",
            data: {accion: "desloguear"},
        }).then(deslogueado, error)
    })  
}



function deslogueado(data){
    $("#logout").html(data);
    $("#logout").attr('id', 'log');
    loguear();
}
function error(data) {
    alert("error");
}

function loguear(){
    $("#login").click(function(){
        var usuario = $("#usuario").val();
        var pass = $("#pass").val();
        $.ajax({
            url: "php/administracion.php",
            type:"POST",
            data: { accion: "loguear", usuario : usuario, pass: pass}

        }).then(logueado, error)
    })
}