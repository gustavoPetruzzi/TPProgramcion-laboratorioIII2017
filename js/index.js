$(document).ready(function(){
    loguear();
});





function logueado(data){
    $("#navbar-nav").removeClass("hidden");
    if(data.exito){
        $("#log").html(data);
        $("#navbar-nav").find("a").removeClass("hidden");
        $("#log").attr('id', 'logout');
        $("#desLogin").click(function(){
            $.ajax({
                url:"php/administracion.php",
                type:"POST",
                data: {accion: "desloguear"},
            }).then(deslogueado, error)
        })
    }  
}



function deslogueado(data){
    $("#logout").html(data);
    $("#logout").attr('id', 'log');
    loguear();
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
            url: "php/administracion.php",
            type:"POST",
            data: { accion: "loguear", usuario : usuario, pass: pass},
            dataType: 'json'

        }).then(logueado, error)
    })
}