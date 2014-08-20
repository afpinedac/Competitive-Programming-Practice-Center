{capture assign='content'}


    
    <div class="row-fluid">
        <div class="span12">

            
            
            <div class="span3">
                {assign var=tab2 value='edicion-curso'}
 {include file='./components/lista_modulos.tpl'}
            </div>
            
            <div class="span9">

    
    <div class="row-fluid">
        <div class="span12">

            <h3 ><i class='icon icon-star'></i> Editar Información curso</h3>        
             
            <br>

                        <div class="row-fluid">
		<div class="span12" style='padding-left: 20px; padding-right: 20px; '>
			<legend>Actualizar</legend>
                        
                 {*script para hacer mostrar/ocultar el div de edicion de datos del curso*}
                                    <script>
                                        //edicion curso
                                    ec = {
                                      
                                        set_publico: function(){
                                            if($("#ec-privado").prop('checked')){
                                                    $("#ec-password-curso").show();
                                            }else{
                                                $("#ec-password-curso").hide();
                                            }
                                        }, validar:function(){
                                               if($("#ec-privado").prop('checked')){
                                                   if($("#ec-password-curso input").val()=="") {
                                                        window.alert("El curso es privado, la contraseña del curso es obligatoria");
                                                        $("#ec-password-curso").focus();
                                                        return false;
                                                }                            
                                    }
                                }
                            }
                                    </script> 
                        
                        
          	
			{Form::open(['action'=>'CursoController@postEditar','files'=>true])}
                            <div>
                            <label style='margin-top: 4px;' for="nombre" class='span2'><strong>Nombre:</strong> </label>
                            <input type="text" required class="span10" name="nombre" value='{$curso->nombre}' placeholder="Nombre">
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'><strong>Descripción:</strong> </label>
                            <textarea class='span10' rows="3" name='descripcion'>{$curso->descripcion}</textarea>
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'>
                            <strong>Privado:</strong> <input id='ec-privado' type="checkbox" {if $curso->publico==0}checked='checked'{/if}name="privado" onclick="ec.set_publico()"> 
                            </label>
                            <span class="{if $curso->publico==1}hide{/if}" id='ec-password-curso'>
                            <input type="text"  name="password" value='{$curso->password}' placeholder="Contraseña" >
                            </span>
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'>
                            <strong>Chat:</strong> <input id='ec-privado' type="checkbox" {if $curso->tiene_chat()}checked='checked'{/if} name="chat" > 
                            </label>
                            
                            
                            
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="" class='span4'>
                                <strong><span style="">Soluciones visibles:</span></strong> <input id='ec-privado' type="checkbox" {if $curso->tiene_soluciones_visibles()}checked='checked'{/if} name="soluciones_visibles" > 
                            </label> 
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="" class='span4'>
                                <strong><span style="">Finalizar curso:</span></strong> <input id='ec-privado' type="checkbox" {if $curso->terminado()}checked='checked'{/if} name="terminado" > 
                            </label> 
			 </div>
                            <br>
                            <br>
                         <input type="hidden" name="curso" value="{Crypt::encrypt($curso->id)}">
                         <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'><strong>Imagen:</strong> </label>
                            <input type="file" name="imagen">                          
                         
			 </div>
                         <br>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span2'><strong>Actual:</strong> </label>
                             <img src='{url('img/cursos/')}/{$curso->imagen}' class='img-presentacion-curso'>
			 </div>
                         
                       
           <br>
           <button type="submit" onclick="return ec.validar();"  class="btn btn-success btn-block">ACTUALIZAR</button>
			{Form::close()}  
		</div>
</div>
            
            
        </div>
    </div>
    
                
        </div>
    </div>
    </div>
    
    

{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='edicion' tab2='edicion-curso'}
