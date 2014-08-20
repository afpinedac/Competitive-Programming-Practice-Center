{capture assign='content'}

    
   
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="span3 well well-small">
                                <ul class='nav nav-list'>
                                    <li class='{if $accion =='-1'}active{/if}'><a href='{url('curso/ver/')}/{$curso->id}/mensajes'><i class='icon icon-envelope'></i> Bandeja de Entrada ({usuario::find(Auth::user()->id)->numero_de_mensajes_sin_leer($curso->id)})  </a></li>    
                                    <li class='{if $accion =='nuevo'}active{/if}'><a href='{url('curso/ver/')}/{$curso->id}/mensajes/nuevo'><i class='icon icon-plus'></i> Enviar Nuevo Mensaje</a></li>    
                                 {*   <li class='{if $accion =='enviados'}active{/if}'><a href='{url('curso/ver/')}/{$curso->id}/mensajes/enviados'><i class='icon icon-reply'></i> Mensajes enviados</a></li>    *}
                                   
                                </ul>
                            </div>
                                    
                                    
                            <div class="span9">
                               
                                
                              
                                
                                 <div class="row-fluid">
                                    <div class="span12">
                                {if $accion == 'nuevo'}  {* Va a escribir un mensaje nuevo   *}
                                        
                                        
                                    {Form::open(['action' => 'MensajeController@postEnviar'])}
                                    <span class=''>Para:</span>
                                    <input type="hidden" name="remitente" value='{Crypt::encrypt(Auth::user()->id)}'>
                                    
                                    {if $mensaje}  {*se guarda el mensaje respuesta_a*}
                                        <input type="hidden" name="respuesta_a" value='{Crypt::encrypt($mensaje->id)}'>   
                                     {/if}
                                    
                                    <select  name='destinatario' required class='span12'>
                                     <option value=''>Seleccione el destinatario</option>
                                        {if Auth::user()->rol == 1}
                                            <option value='0'> + Todos los estudiantes del curso</option>
                                        {/if} 
                                        {foreach $amigos as $amigo}
                                                {if $mensaje} {*se esta respondiendo un mensaje*}
                                                    {if $amigo->id == $mensaje->remitente}
                                                        <option selected="" value='{$amigo->id}'>+ {$amigo->nombres|capitalize} {$amigo->apellidos|capitalize}</option>
                                                     {else}
                                                         <option value='{$amigo->id}'>+ {$amigo->nombres|capitalize} {$amigo->apellidos|capitalize}</option>
                                                     {/if}  
                                                   
                                                   {else}
                                                       <option value='{$amigo->id}'>+ {$amigo->nombres|capitalize} {$amigo->apellidos|capitalize}</option>
                                                    
                                                 {/if}   
                                              
                                        {/foreach}
                                                                            
                                    </select>
                                    
                                    <span class=''>Asunto:</span><input required type="text" name="asunto" value='{if $mensaje}RE:{$mensaje->asunto}{/if}' class='span12'>
                                    <textarea autofocus="" required name='mensaje' placeholder='Escriba el mensaje aquí' class='span12' style='overflow-y: scroll' rows="8"></textarea>                                    
                                
                                    {if $mensaje}{* si hay una cadena de mensajes *}
                                    
                                            
                                                   {*Mostramos todos los mensajes dependientes a este*}
                                              
                                              {assign var=n value=$mensaje->id}
                                              
                                              {while $n!=null}
                                                  
                                                    {assign var=old_message value=mensaje::find($n)}
                                                    {assign var=old_usuario value=usuario::find($old_message->remitente)}
                                                    
                                                    
                                                    <div class="row-fluid">
                                                        <div class="span12 well well-small">
                                                            <img class='foto-mensaje' src='{General::avatar($old_usuario->id)}'> <small> {$old_usuario->nombres|capitalize} {$old_usuario->apellidos|capitalize}</small> 
                                                            <small><p><em>El {$old_message->created_at} Escribió:</em></p></small>
                                                            <p>{e($old_message->mensaje)}</p>


                                                        </div>
                                                    </div>
                                                    
                                                  
                                                  
                                                  {assign var=n value=mensaje::find($n)->respuesta_a} 
                                                  
                                               {/while}  
                                            
                                            
                                    {/if}
                                    
                                    <button class='btn btn-success' type='submit'><i class='icon icon-envelope'></i> Enviar</button>
                                
                                {Form::close()}
                                
                                
                                
                                {else if $accion == 'leer'} {* ------- leer un mensaje --------*}
                                    
                                    
                                    {assign var=remitente value=usuario::find($mensaje->remitente)}
                                    
                                    
                                    <div class="row-fluid">
                                        <div class="span12">
                                            
                                            <img class='foto-mensaje' src='{General::avatar($remitente->id)}'> <span class=''>{$remitente->nombres|capitalize} {$remitente->apellidos|capitalize}</span> <span class='pull-right'> Enviado el: {$mensaje->created_at}</span>
                                        </div>
                                    </div>
                                        <hr>
                                    <div  style='margin-top: -30px;' class="row-fluid">
                                        <div class="span12">
                                            
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <h3 class='pull-left'>{$mensaje->asunto}</h3> <a class='pull-right' href='{url('curso/ver')}/{$curso->id}/mensajes/nuevo/{$mensaje->id}'><i class='icon icon-arrow-left'></i> Responder</a>
                                                </div>
                                            </div>
                                          
                                                                                             
                                                    
                                              <pre>
{e($mensaje->mensaje)}
                                              </pre>
                                              
                                              {*Mostramos todos los mensajes dependientes a este*}
                                              
                                              {assign var=n value=$mensaje->respuesta_a}
                                              
                                              {while $n!=null}
                                                  
                                                    {assign var=old_message value=mensaje::find($n)}
                                                    {assign var=old_usuario value=usuario::find($old_message->remitente)}
                                                    
                                                    
                                                    <div class="row-fluid">
                                                        <div class="span12 well well-small">
                                                            
                                                            <img class='foto-mensaje' src='{url('img/avatars/')}/{$old_usuario->foto}'> <small> {$old_usuario->nombres|capitalize} {$old_usuario->apellidos|capitalize}</small> 
                                                            <small><p><em>El {$old_message->created_at} Escribió:</em></p></small>
                                                            <p>{e($old_message->mensaje)}</p>
                                                            
                                                            


                                                        </div>
                                                    </div>
                                                    
                                                  
                                                  
                                                  {assign var=n value=mensaje::find($n)->respuesta_a} 
                                                  
                                               {/while}   
                                                  
                                              
                                              
                                              
                                              
                                              
                                              
                                        </div>
                                    </div>
                                    
                                {else if $accion=='enviados'}
                                        mensajes enviados
                                {else}   {* ------No va a realizar ninguna acción------ *}
                                    
                              {if count($mensajes)>0}
                               {*
                                  <div class="row-fluid">
                                      <div class="span12">
                                          <a href='#'><i class='icon icon-trash'></i> Eliminar seleccionados</a>
                                      </div>
                                  </div>
                               *}
                               {foreach $mensajes as $mensaje}
                                   {*{$mensaje|var_dump}*}

                                   <div   class="row-fluid {if $mensaje->leido==0}mensaje-no-leido{else}mensaje-leido{/if}" style='padding-top: 5px;'>
                                          <div class="span12"> 
                                              <div class="span1">
                                             {*     <input type="checkbox" name="seleccionado[]">    *}
                                              </div>
                                              <div style='margin-left: -50px;' class="span3 suspensive-points"  onclick="location.href='{url('curso/ver')}/{$curso->id}/mensajes/leer/{$mensaje->id}'">
                                               <span> {if $mensaje->leido==0}<i class='icon icon-envelope-alt'></i>{else}<i class='icon icon-folder-open-alt'></i>{/if} <strong>De:</strong> <em>{$mensaje->nombres} {$mensaje->apellidos}</em></span>
                                              </div>
                                              <div onclick="location.href='{url('curso/ver')}/{$curso->id}/mensajes/leer/{$mensaje->id}'" class="span5 suspensive-points">
                                                  <strong>{$mensaje->asunto}:</strong> {e($mensaje->mensaje)}
                                              </div>
                                              <div  onclick="location.href='{url('curso/ver')}/{$curso->id}/mensajes/leer/{$mensaje->id}'" class="span3">
                                                  {$mensaje->created_at|date_format}
                                             <span style='margin-top: 2px; margin-right: 15px;' class='badge badge-info pull-right'> {if $mensaje->leido == 1}Leído{else}Nuevo{/if}</span>
                                              </div>
                                          </div>
                                      </div>
                                             
                               {/foreach}

                                  
                                      
                                      
                               

                                  
                                  {else}  {*No tiene mensajes*}
                                    <div class="alert alert-block">
                                    <p class='text-center'>Usted no tiene mensajes</p>
                                    </div>  
                                  
                               {/if}
                               {/if}
                                
                               </div>
                               </div>
                               
                            </div>
                               
                                 
                               
                        </div>
                    </div>

                
                

{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='mensajes'}
