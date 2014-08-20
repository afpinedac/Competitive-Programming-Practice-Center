
    <div id="editar-agregar-ejercicio" style='width: 50%' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="alingCenter" ><i class='icon icon-plus-sign'></i> Editar ejercicio</h4>

            </div>
            <div class="modal-body">
                
               
                {Form::open(['action'=>'TallerController@postEditarEjercicio'])}
                
                
                <span id='info-ejercicio' data-ejercicio='0' data-formulacion='0' ></span>
                
                
               
                
                   <span class='badge badge-important'>1</span> <span class='lead'>Ejercicio</span>
               <br>
               <br> 
                      
                    <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Seleccionar: *</label>
                    </div>                
                    <div class="span7">
                        
                        <select required id='eae-select-agregar-ejercicio' name='ejercicio' class='span12'>
                            <option value=''>Seleccione el ejercicio</option>  
                            
                            {if $modulo}
                            {foreach $modulo->get_otros_ejercicios(Auth::user()->id) as $ejer}
                                <option value='{$ejer->id}'>{$ejer->nombre}</option>
                            {/foreach}
                            {/if}

                        </select>   
                    </div>

                </div>
          
                            <input type="hidden" id='old_ejercicio'  name="old_ejercicio">
                            <hr>
                            
                             <span class='badge badge-important'>2</span> 
                             <span class='lead'>Tipo de entrada</span>
               <br>
               <br>
                               <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Entrada: *</label>
                    </div>                
                    <div class="span7">
                        <span class=''>Solamente 'out': </span><input type="radio" name="tipo_entrada" value='0' checked=""  id='eae_out' >
                        <span class=''>Código: </span><input type="radio" name="tipo_entrada" value='1' id='eae_codigo' >
                            
                         
                    </div>
                </div>
               
                    <div class="row-fluid">


                    <div class="span3">
                        <label class="pull-right">Tiempo Límite: *</label>
                    </div>                
                    <div class="span7">
                        
                        <select id="eae-timit-limit" name="time_limit"   required class="input-mini"  >
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

                             
               
                       
            
                <hr>
                
                
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" onclick="return editar_agregar_ejercicio.formulario.validar();" class="btn btn-inverse"><i class='icon icon-upload-alt'></i> Actualizar</button>
                </div>
                {Form::close()}
            </div>

        </div>
            
            
                <script>        
     
      editar_agregar_ejercicio = {   
                ejercicio : null,
      
                formulario : {
                            validar : function(){                                                       
                                    $("#old_ejercicio").val(editar_agregar_ejercicio.ejercicio);
                                    return true;
                            }
                },
                 reestablecer_formulario : function(){
                     
                   $("#eae-select-agregar-ejercicio").empty();
                  
                 }, 
                         
                         
     editar : function(ejercicio){
                      editar_agregar_ejercicio.ejercicio = ejercicio;
                      editar_agregar_ejercicio.reestablecer_formulario();
          $.ajax({
            dataType: "json",
            type: 'post',
            url: "{url('taller/infojson')}",
            data: {
                ejercicio: ejercicio
            },
            success: function(data) {
             //window.console.log(data);
                
                $("#eae-select-agregar-ejercicio").append("<option value='"+data.id+"'>"+data.nombre+"</option>");
               $("#eae-select-agregar-ejercicio").val(data.ejercicio);
               $("#eae-timit-limit").val(data.time_limit);
               
               if(data.tipo_entrada == 1){ //se ingresa solo la respuesta                   
                   $("#eae_out").prop('checked',false);
                   $("#eae_codigo").prop('checked',true);                   
               }else{
                   $("#eae_out").prop('checked',true);
                   $("#eae_codigo").prop('checked',false);     
                }
                   
                
            }
        });
                      
                      
                  }   
                         
               
                
      }
        
    </script>