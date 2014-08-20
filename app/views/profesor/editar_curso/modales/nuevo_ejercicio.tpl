
    <div id="nuevo-ejercicio" style='width: 50%;' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="alingCenter" ><i class='icon icon-plus-sign'></i> Crear nuevo ejercicio</h4>

            </div>
            <div class="modal-body">
                
               
                {Form::open(['action'=>'EjercicioController@postCrear','files'=>true])}
                
                
                <span id='info-ejercicio' data-ejercicio='0' data-formulacion='0' ></span>
                
                       
                
                   <span class='badge badge-important'>1</span> <span class='lead'>Información</span>
               <br>
               <br>
                
                       
                
                <div class="row-fluid"> 
                    <div class="span3">
                        <label  class="pull-right">Nombre: *</label>
                    </div>                
                    <div class="span7">
                        <input required  class='span12' id='ne_nombre' type="text" name="nombre" >
                    </div>
                </div>
             
                
                
                  <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Tipo Formulación: *</label>
                    </div>                
                    <div class="span7">
                        <span class=''>Plano: </span><input  id='ne_plano' type="radio" name="tipo_formulacion" value='0' checked="" onclick="nuevo_ejercicio.set_formulacion(0)">
                        <span class=''>Archivo: </span><input id='ne_archivo' type="radio" name="tipo_formulacion" value='-1' onclick="nuevo_ejercicio.set_formulacion(1)">
                    </div>

                </div>

                <div class="row-fluid" id='formulacion_tipo0'> 
                    <div class="span3">
                        <label class="pull-right">Formulación:</label>
                    </div>                
                    <div class="span7">
                        <textarea class='span12' id='formulacion' name='formulacion' rows="5" placeholder='Escriba la formulación del problema'></textarea>
                    </div>

                </div>
                <div class="row-fluid hide" id='formulacion_tipo1'> 
                    <div class="span3">
                        <label class="pull-right">Formulación (PDF): </label>
                    </div>                
                    <div class="span7">
                        <input type="file" id="archivo_formulacion" name="archivo_formulacion" accept="application/pdf">
                    </div>

                </div>
                <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right"><small>Archivo Entrada (.txt):</small> *</label>
                    </div>                
                    <div class="span7">
                        <input type="file" id='ne_archivo_entrada' required name="archivo_entrada" accept="text/plain">
                    </div>

                </div>
                <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right"><small>Archivo Salida (.txt):</small> *</label>
                    </div>                
                    <div class="span7">
                        <input type="file" id='ne_archivo_salida' required name="archivo_salida" accept="text/plain">
                    </div>

                </div>
                <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Agregar al taller de: *</label>
                    </div>                
                    <div class="span7">
                        <select name='taller' id='select-modulos' onclick="nuevo_ejercicio.set_taller()" >
                            <option value='-1'>Ninguno</option> 
                            {foreach curso::find($curso->id)->get_modulos() as $mod}                            
                                <option value='{$mod->id}'>{$mod->nombre}</option>
                            {/foreach}

                        </select>   
                    </div>

                </div>
                   
                            
                  <div class="row-fluid" id='div-tipo-respuesta'> 
                    
                      <div class="row-fluid">


                      <div class="span3">
                        <label class="pull-right">Tipo de respuesta</label>
                    </div>                
                    <div class="span7">
                        'out'<input type="radio" checked="" id='ne_out'  value='0' name="tipo_entrada"> código <input type="radio" id='ne_codigo' value='1'  name="tipo_entrada">
                        
                    </div>
                                            </div>
                      
                            <div class="row-fluid">


                    <div class="span3">
                        <label class="pull-right">Tiempo Límite: *</label>
                    </div>                
                    <div class="span7">
                        
                        
                        <select  name="time_limit"   required class="input-mini"  >
                            <option>0.1</option>
                            <option>0.2</option>
                            <option>0.3</option>
                            <option>0.5</option>
                            <option selected="">1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>5</option>
                            </select> 
                        
                        
                        <span> Segundos</span>
                          
                    </div>
                    </div>
                      
                      
                </div>
                                
                                
                                        
              
                                
                       
            
                <hr>
                
                
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" onclick="return nuevo_ejercicio.formulario.validar()" class="btn btn-inverse"><i class='icon icon-plus-sign'></i> Crear</button>
                </div>
                {Form::close()}
            </div>

        </div>
            
            
                <script>        
     
  $(document).ready(function() {


    nuevo_ejercicio = {
        formulacion: 0,
        formulario: {
            validar: function() {
                
                if (nuevo_ejercicio.formulacion == 0) {  // si es texto
                    if ($("#formulacion").val() == "") {
                        $("#formulacion").focus();
                        return false;
                    }
                } else { // si es un archivo
                    if ($("#archivo_formulacion").val() == "") {
                        $("#archivo_formulacion").focus();
                        return false;
                    }
                }
                return true;
            }

        },
        reestablecer_formulario : function(){
            $("#ne_nombre").val("");           
            $("#ne_archivo").val("");
            $("#ne_archivo_entrada").val("");
            $("#ne_archivo_salida").val("");
            $("#ne_plano").prop('checked',true);
            $("#formulacion").text("");
            $("#select-modulos").val({$modulo->id});
             $("#ne_out").prop("checked",true);
          //   window.console.log('pasa');
             $("#ne_revision").prop('checked',false);
             $("#formulacion_tipo0").show();
             $("#formulacion_tipo1").hide();
             $("#div-codigos-revision").hide();
            
        }
        ,
        set_formulacion: function(s) {
            nuevo_ejercicio.formulacion = s;
            $("#formulacion_tipo" + (1 - s)).hide();
            $("#formulacion_tipo" + (s)).show();

        },
        set_taller: function() {
            opt = $("#select-modulos").val();
            if (opt > 0) {
                $("#div-tipo-respuesta").show();
            } else {
                $("#div-tipo-respuesta").hide();
            }

        },
   
    }

    $("#select-modulos").val({$modulo->id});
});
</script>