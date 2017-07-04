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
                                        <input type='text' class="form-control" id='operacionesDesde' />
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