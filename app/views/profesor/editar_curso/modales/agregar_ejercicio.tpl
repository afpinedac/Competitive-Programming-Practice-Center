
    <div id="agregar-ejercicio" style='width: 50%' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="alingCenter" ><i class='icon icon-plus-sign'></i> Agregar ejercicio</h4>

            </div>
            <div class="modal-body">
                
               
                {Form::open(['action'=>'TallerController@postAgregarEjercicio'])}
                
                
                <span id='info-ejercicio' data-ejercicio='0' data-formulacion='0' ></span>
                
        
               
                
                   <span class='badge badge-important'>1</span> <span class='lead'>Ejercicio</span>
               <br>
               <br> 
                      
                    <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Seleccionar: *</label>
                    </div>                
                    <div class="span7">
                        
                        <select required id='select-agregar-ejercicio' name='ejercicio' class='span12'>
                            <option value=''>Seleccione el ejercicio</option>  
                            {if $modulo}
                            {foreach $modulo->get_otros_ejercicios(Auth::user()->id) as $ejer}
                                <option value='{$ejer->id}'>{$ejer->nombre}</option>
                            {/foreach}
                            {/if}

                        </select>   
                    </div>

                </div>
          
               
                            <hr>
                            
                             <span class='badge badge-important'>2</span> 
                             <span class='lead'>Tipo de entrada</span>
               <br>
               <br>
                               <div class="row-fluid"> 
                    <div class="span3">
                        <label class="pull-right">Entrada: * </label>
                    </div>                
                    <div class="span7">
                        <span class=''>Solamente 'out': </span><input type="radio" name="tipo_entrada" value='0' checked=""  id='ae_out' >
                        <span class=''>Código: </span><input type="radio" name="tipo_entrada" value='1'  >
                          
                    </div>
                                   <div class="row-fluid">


                    <div class="span3">
                        <label class="pull-right">Tiempo Límite: *</label>
                    </div>                
                    <div class="span7">
                        
                        
                         
                        
                         <select name="time_limit" min="1"  required class="input-mini"  >
                            <option>0.1</option>
                            <option>0.2</option>
                            <option>0.3</option>
                            <option>0.5</option>
                            <option selected="">1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>5</option>
                            </select><span> Segundos</span>
                    </div>
                                                                          </div>
                </div>

                             
               
                       
            
                <hr>
                
                
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancelar</button>
                    <button type="submit" onclick="return agregar_ejercicio.formulario.validar();" class="btn btn-inverse"><i class='icon icon-plus-sign'></i> Crear</button>
                </div>
                {Form::close()}
            </div>

        </div>
            
            
                <script>        
     
      agregar_ejercicio = {      
                formulario : {
                            validar : function(){
                            return true;
                            }
                },
                 reestablecer_formulario : function(){
                    $("#select-agregar-ejercicio").val(-1);
                    $("#ae_out").prop('checked', true);
                    $("#ae_respuesta").prop('checked', true);
                   $("#div-codigos-revision2").hide();
                 }
                
      }
        
    </script>