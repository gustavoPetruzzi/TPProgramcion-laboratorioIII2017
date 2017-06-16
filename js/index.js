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
    //$('#myTable').DataTable();
});



/*
window.onbeforeunload = function(e){
        $.ajax({
        url:"desloguear",
        type:"POST",
    }).then(refresh, error)
}
*/
function cargarSelect(id, elementos) {
    $.each(elementos, function (value) {   
        $(id)
            .append($("<option></option>")
                .attr("value", value)
                .text(value)); 
    });
}
