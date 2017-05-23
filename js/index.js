$(document).ready(function(){
    loguear();
});




// TODO poner desloguear en una funcion como loguear()
function logueado(data){
    
    if(!isJSON(data)){
        $("#log").html(data);
        $("#navbar-nav").find("a").removeClass("hidden");
        $("#log").attr('id', 'logout');
        $("#desLogin").click(function(){ // TODO poner desloguear en una funcion como loguear()
            $.ajax({
                url:"php/administracion.php",
                type:"POST",
                data: {accion: "desloguear"},
            }).then(deslogueado, error)
        })
    }
    else{
        alert(data);
    }
}



function deslogueado(data){
    if(!isJSON(data)){
        $("#logout").html(data);
        $("#logout").attr('id', 'log');
        loguear();
    }
    else{
        alert(data);
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
            url: "php/administracion.php",
            type:"POST",
            data: { accion: "loguear", usuario : usuario, pass: pass},

        }).then(logueado, error)
    })
}

function isJSON(str){
    try {
        JSON.parse(str);
    } catch (error) {
        return false;
    }
    return true;
}