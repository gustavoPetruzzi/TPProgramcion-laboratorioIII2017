$(document).ready( function (){
    loguear();
    var colores = ['rojo', 'blanco', 'negro', 'azul', 'verde'];
    var marcas = ['renault', 'ford', 'chevrolet', 'peugeot'];
    cargarSelect("#color", colores);
    cargarSelect("#marca", marcas);
    modalEmpleado();
    eventEstacionar();
    salidaAuto();
    modalEstacionarOnClose();
    $('a').click(function(){
        $("#usados").remove();
        $("#respuesta").html("");
    })
});


function cargarSelect(id, elementos) {
    $.each(elementos, function (value) {   
        $(id)
            .append($("<option></option>")
                .attr("value", elementos[value])
                .text(elementos[value])); 
    });
}

function traerDate(id){
    var retorno =`<div class="container">
                        <div class="row">
                            <div class='col-sm-6'>
                                <div class="form-group">
                                    <div class='input-group date' id='`+ id +`'>
                                        <input type='text' class="form-control" id='` + id +`Input' />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#`+id + `').datetimepicker({
                                        format: 'YYYY-MM-DD'
                                    });
                                });
                            </script>
                        </div>
                    </div>`;
    return retorno;
}

function error206(data){
    var datos = `<div class="row" id="usados">
                        <h2> ${data} </h2>
                    </div>`;
        $("#principal").append(datos);
}

function errores(xhr, status, errorThrown){
    switch (xhr.status) {
        case 500:
            alert("Un error ha ocurrido en el servidor");
            break;
        case 511:
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
            $("#autos").addClass("hidden");
            $("#info").html("");
            $("#precios").html("");
            $("#respuesta").html("");
            $("#usados").remove();
            $("#opciones").html("");
            loguear();
        default:
            break;
    }
}