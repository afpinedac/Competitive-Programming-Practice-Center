{capture assign='content'}


                 <script>
                                        //edicion ejercicio
                                     ee = {
                                      
                                        set_formulacion: function(option){
                                            if(option == 0){
                                                $("#ee-formulacion-0").show();
                                                $("#ee-formulacion-1").hide();
                                            }else{
                                                $("#ee-formulacion-1").show();
                                                $("#ee-formulacion-0").hide();
                                                
                                            }
                                                
                                        }, validar:function(){
                                              return true;
                                                }                            
                                    };
                                
                            
                                    </script> 
    
    <div class="row-fluid">
        <div class="span12">

            <h3 ><i class='icon icon-star'></i> Editar Ejercicio</h3>        
            <a href='{url('curso/ver')}/{$curso->id}/editar/lista-ejercicios'> <i class='icon icon-reply'></i> Volver</a>
            <br>
                         <div class="row-fluid">
		<div class="span12" style='padding-left: 20px; padding-right: 20px; '>
                    
                    {Form::open(['action'=>'EjercicioController@postEditar','files'=>true])}
                    <legend>Actualizar  <button  onclick="return ee.validar();" class="pull-right btn btn-success">Actualizar</button></legend>
                        
                 {*script para hacer mostrar/ocultar el div de edicion de datos del curso*}
                       
                        
                        
          	
			
                            <div>
                            <label style='margin-top: 4px;' for="nombre" class='span2'><strong>Nombre:</strong> </label>
                            <input type="text" required class="span10" name="nombre" value='{$ejercicio->nombre}' placeholder="Nombre">
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'><strong>Tipo de formulación:</strong> </label>
                            <select onchange="ee.set_formulacion(this.value)" name="tipo_formulacion">
                                <option value='1' {if $ejercicio->tipo_formulacion==1}selected{/if}>Archivo PDF</option>
                                <option value='0' {if $ejercicio->tipo_formulacion==0}selected{/if}>Texto plano</option>
                            </select>
                            {if $ejercicio->tipo_formulacion == 1}
                                <a target="_blank" id="ee-link-formulacion" href="{url('ejercicio/descargar-formulacion')}/{LMSController::encoder($ejercicio->id)}"><img style='margin-top: -10px;' src='{url('img/general/pdf.jpg')}'></a>
                                {/if}
                            </div>
                         <br>
                          <div id="ee-formulacion-0" class="{if $ejercicio->tipo_formulacion==1}hide{/if}">
                                
                            <label style='margin-top: 4px;' for="apellido" class='span2'>
                            <strong>Formulación:</strong> 
                            </label>
                            <span >
                                <textarea class="span10" rows="5" name="formulacion0">{$ejercicio->formulacion}</textarea>
                            </span>
			 </div>
                            
                            
                            <div id="ee-formulacion-1"  class="{if $ejercicio->tipo_formulacion==0}hide{/if}">
                                
                          <label style='margin-top: 4px;' for="" class='span2'>
                            <strong><small>Formulación (PDF):</small></strong> 
                            </label>
                            <span>
                                <input type="file"  name="formulacion1" >
                            </span>
			 </div>
                                                       
                         <input type="hidden" name="ejercicio" value="{Crypt::encrypt($ejercicio->id)}">
                         <div>
                             <hr>
                             
                             
                             <div class="row-fluid">
                                 <div class="span12">
                                     <div class="span6">
                                         <strong>Entrada: </strong><br>
                                         Cambiar: <input type="file" name="in" accept="text/plain" ><br>
                                         <pre>{$ejercicio->in}</pre>
                                     </div>
                                     <div class="span6">
                                          <strong>Salida: </strong><br>
                                         Cambiar: <input type="file" name="out" accept="text/plain" ><br>
                                         <pre>{$ejercicio->out}</pre>
                                     </div>
                                 </div>
                             </div>
                             
                             
                            
                            
                         
			 </div>
                        
                         
                       
           <br>
           <button type="submit" onclick="return ec.validar();"  class="btn btn-success btn-block">ACTUALIZAR</button>
			{Form::close()}  
		</div>
</div>
          
                        
            
        </div>
    </div>
    
    

{/capture}   


{include file='_templates/template.tpl' layout='edicion' tab='ejercicios'}
