{capture assign='content'}
    
        
    
    <div class="row-fluid">
        <div class="span12">
            
            <p class='pull-right'><a href='{url('curso/ver')}/{$curso->id}/foro'><i class='icon icon-reply'></i> volver al foro</a></p>

          <div class="row-fluid">
        <div class="span12 well">
            <h3 style='margin-top: -15px;' class='pull-left'><i class='icon icon-comment'></i> {$tema->nombre}</h3>
            
            <p style="margin-top: -10px;" class='pull-right'><small>Creado por <a href='#'>{$tema->nombres|capitalize}</a> el dia {$tema->created_at}</small></p>
<br>
<br>

            
            <p>{e($tema->descripcion)}</p>
                    

            
        </div>
    </div>
            <div class="row-fluid">
                <div class="span12">
                    {Form::open(['url'=>['foro/responder' , $tema->id]])}
                    
                    <textarea class='span12' name='respuesta' rows="3"></textarea>
                    <input type="submit" class='btn btn-success' value='Responder'>                    
                    
                    {Form::close()}
                </div>
            </div>
            
                
            
            <h3 class="pull-right"><i class='icon icon-comments'></i> Respuestas ({temaforo::find($tema->id)->get_numero_de_respuestas()})</h3><br><br>
            
            
            
            
            <div class="row-fluid">
                <div class="span12">
                        
                    {foreach $respuestas as $respuesta}
                    
                        <div class="row-fluid">
                            <div class="span12">
                                
                                
                                <div class="span1">
                                    <center>
                                        <img style='cursor: pointer' class='img-foto-foro' onclick="usuario.ver_perfil({$respuesta->usuario})" src='{General::avatar($respuesta->usuario)}'>
                                        <p class='suspensive-points'>{$respuesta->nombres|capitalize} {$respuesta->apellidos|capitalize}</p>


</center>
                                </div>
                                <div class="span11">
                                    <pre>{if Auth::user()->id == $respuesta->usuario}<a href='{url('foro/eliminar-respuesta')}/{$respuesta->id}/{$tema->id}' onclick="return lms.confirmar();"><p class='pull-right'><i class='icon icon-remove'></i></p></a>{/if}{e($respuesta->respuesta)}<br><br><p class="pull-right" style='margin-top: -10px;'><em><small> <a href='#' onclick="usuario.ver_perfil({$respuesta->usuario}); return false;">{$respuesta->nombres}</a> a las {$respuesta->created_at}</small></em></p><br></pre>                                                 
                                </div>
                                
                                    
                               
                                                 
                                                  
                                                

                            </div>
                                        
                        </div>    
                           
                        
                     {/foreach}   
                    
                </div>
            </div>
            
            
            
            
            
            
        </div>
    </div>
    
    
        
                    {include file='../modales/perfil_usuario.tpl'}        
                    
                    
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='foro'}
