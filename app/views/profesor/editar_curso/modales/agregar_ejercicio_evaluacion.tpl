


    <div id="agregar-nuevo-ejercicio-evaluacion" style='width: 55%; left: 40%' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-plus-sign'></i> Nueva evaluación</h4>

        </div>
        <div class="modal-body">
            {Form::open(['action'=>'EvaluacionController@postCrear'])}

          
           <span class='badge badge-important'>1</span> <span class='lead'>Lista de ejercicios</span>  
            <br><br>
            
            
                     <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Seleccionar: *</label>
                    </div>                
                    <div class="span7">
                        
                        <select id='select-ejercicios' required name='ejercicio' class='span12'>
                            <option value='-1'>Seleccione el ejercicio</option>   
                           
                        </select>   
                    </div>
                     
                </div>
                        
                        
                           <div class="row-fluid"> 
                <div class="span3">
                
                </div>                
                <div class="span4">
                    <span class=''>Respuesta:  'out':</span><input type="radio" checked="" id='aee_out' name="tipo_entrada" value='0' onclick='ejercicio_evaluacion.set_entrada(0)'>
                    <span class=''>Código:</span><input type="radio" name="tipo_entrada" value='1' onclick="ejercicio_evaluacion.set_entrada(1)">
                       
                </div>
                                <div class="span2">
                            <button onclick="return ejercicio_evaluacion.add_ejercicio();" class='btn btn-success'><i class='icon icon-plus-sign'></i>Agregar</button>
                        </div>
            </div>
                        
                        
                        <hr>
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Lista de ejercicios: </label>
                </div>                
                <div class="span7">
                      <select id="listaEjercicios" class='span12' multiple="multiple" size="8">
                </select><br>
                <button class='btn btn-danger btn-small' onclick="return ejercicio_evaluacion.eliminar_ejercicio();">Eliminar de la lista</button>
                </div>
            </div>
            
        <br>
          

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button data-dismiss="modal" class="btn btn-inverse"><i class='icon icon-plus-sign'></i> Guardar</button>
            </div>
            {Form::close()}
        </div>

    </div>
        
        
        <script>
            
           
            
          
    ejercicio_evaluacion = {
    evaluacion: null,
    limpiar_lista_ejercicios: function() {
        ///         window.console.log('inicia la limpieza -----');
        $.each($("#listaEjercicios option"), function(idx) {
            this.remove();
        });
        $.each($("#select-ejercicios option"), function(idx) {
            this.remove();
        });
       // window.console.log('termina la limpieza-----');
    },
    set_evaluacion: function(s) {
        ejercicio_evaluacion.evaluacion = s;

        //eliminamos todos los datos del modal
        ejercicio_evaluacion.limpiar_lista_ejercicios();
        $("#aee_out").prop('checked',true);
        $("#div-codigos-revision4").hide();        

        //cargar los ejercicios que puede tener la evaluacion                  
        $.ajax({
            dataType: "json",
            type: 'post',
            url: "{url('evaluacion/informacion')}",
            data: {
                evaluacion: s
            },
            success: function(data) {
                //  window.console.log(data);

                //cargamos los actuales
                $.each(data.actuales, function(idx, ejercicio) {
                        respuesta = ejercicio.tipo_entrada == 0 ? 'out' : 'código';
                                   $("#listaEjercicios").append("<option value='" + ejercicio.id + "'>" + ejercicio.nombre + ".              ["+ respuesta +"]" + "</option>");
                });
                //cargamos los posibles
                $("#select-ejercicios").append("<option value='-1'>Seleccione el ejercicio</option>");
                $.each(data.posibles, function(idx, ejercicio) {
                    $("#select-ejercicios").append("<option value='" + ejercicio.id + "'>" + ejercicio.nombre + "</option>");
                });


            }
        });


    },
    add_ejercicio: function() {
        ejer = $("#select-ejercicios").val();


        if (ejer > 0) { // es válido                            
            //llamada de ajax para agregar el ejercicio

            entrada = 1;
            revision = 0;
            if ($("#aee_out").prop('checked')) {
                entrada = 0;
            }else if($("#aee_revision").prop('checked')){
                    revision = 1;
            }

            $.ajax({
                dataType: "json",
                type: 'post',
                url: '{url('evaluacion/agregar-ejercicio')}',
                data: {
                    ejercicio: ejer,
                    evaluacion: ejercicio_evaluacion.evaluacion,
                    tipo_entrada: entrada,
                    revision: revision
                },
                success: function(data) {
      // window.console.log(data);
                
                    //agregar al select de ejercicios
                    $("#listaEjercicios").append("<option value='" + data.id + "'>" + data.nombre + "   ["+ data.respuesta +"]" + "</option>");
                    //eliminar del select
                    $("#select-ejercicios option:selected").remove();
                    //poner el nuevo numero de ejercicios
                    $("#numero-ejercicios-" + ejercicio_evaluacion.evaluacion).text(data.n);
                }
            });




        }

        return false;

    }
    ,
    eliminar_ejercicio: function() {

        ejer = $("#listaEjercicios option:selected").val();
        if (ejer != undefined) {
            eval = ejercicio_evaluacion.evaluacion;

            //ajax para eliminar de la bd

            $.ajax({
                type: 'post',
                url: "{url('evaluacion/eliminar-ejercicio')}",
                data: {
                    evaluacion: eval,
                    ejercicio: ejer

                },
                success: function(data) {
                  //  window.console.log(data);
                    //eliminar del select
                    $("#listaEjercicios option:selected").remove();
                    //agregar a la lista de posibles esjercicios
                    $("#select-ejercicios").append("<option value='" + data.id + "'>" + data.nombre + "</option>");
                    //poner el nuevo n
                    $("#numero-ejercicios-" + ejercicio_evaluacion.evaluacion).text(data.n);
                }

            });

        } else {
            alertify.alert('Debe seleccionar un ejercicio');
            $("#listaEjercicios").focus();
        }
        return false;
    },
    set_entrada: function(entrada) {
        if (entrada == 0) {
            $("#div-codigos-revision4").hide();
        } else {
            $("#div-codigos-revision4").show();
        }
    }

}
        
        
        
        
        </script>
