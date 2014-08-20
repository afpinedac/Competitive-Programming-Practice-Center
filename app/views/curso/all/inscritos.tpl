
    
    <h3><i class='icon icon-star'></i> Cursos Inscritos</h3>
    <br>
    
        
        
        {foreach $cursos_inscritos as $curso}
        
        <div class="row-fluid">
            <div class="span12 well">                
                 <div class="span4">
                    <a  href='{URL::to("curso/entrar/")}/{$curso->id}'> <img src="{url('img/cursos/')}/{$curso->imagen}" class='img-presentacion-curso'></a>
                </div>
                <div class="span4">
                    <h2>{$curso->nombre|capitalize}</h2>
                    <ul>
                          
                    
                     <li><strong>Profesor: </strong>{$curso->nombre_profesor|capitalize} {$curso->apellido_profesor|capitalize}</li>
            
                     <li><strong>Fecha de Inscripción: </strong>{$curso->fecha_inscripcion|date_format}</li>
            <li><strong>Numero de estudiantes inscritos: </strong>{curso::find($curso->id)->get_numero_estudiantes_inscritos()}</li>
            
            </ul>
            <a class='btn btn-{if Auth::user()->id == $curso->profesor}info{else}success{/if}'  {if (not curso::find($curso->id)->terminado()) or (Auth::user()->id == $curso->profesor)}href='{URL::to("curso/entrar/")}/{$curso->id}'{/if}><span style='font-size: 20px'>
                {if Auth::user()->id == $curso->profesor}
                    Ver / Editar
                {else if curso::find($curso->id)->terminado()}
                    Terminado                    
                    {else}
                        Entrar
                    {/if}
                </span></a>
                </div>
                <div class="span4 pull-right">
                    <ul>
                        <li><strong>Descripción:</strong> {curso::find($curso->id)->descripcion|truncate:150}</li> 
                        <br>
                        <li><strong>Tipo:</strong> {if $curso->publico == 0} Privado <i class='icon icon-key'></i> {else}Público{/if}</li>
                   
                    
                        <br>
                    
                   <li><a onclick="return curso_disponible.ver_contenido({$curso->id});" href="#" >[Ver contenido]</a></li>
                    </ul>
                   
                   <div class='hide' id="contenido-{$curso->id}">
                       {assign value=curso::find($curso->id)->get_modulos() var=mod}
                       <ol>
                           {foreach $mod as $mo}
                           <li><small><em>{$mo->nombre}</em></small></li>    
                           {/foreach} 
                       </ol>
                       <br>
                   </div>
                    
                    
                           
                      {if Auth::user()->id != $curso->profesor && (not curso::find($curso->id)->terminado())}
                    <a onclick="return confirm('Si desmatricula este curso, se perderá todo el progreso en este. ¿Está seguro?') && confirm('¿Está completamente seguro?') && confirm('¿Enserio?')" href='{URL::to('curso/desmatricular')}/{$curso->id}' class='btn btn-danger btn-mini'>Desmatricular</a>
                    {/if}
                    

                    

                    
                </div>
            </div>
        </div>
        {foreachelse}
                  
            <div class="alert alert-danger">
                <center><p><em>No tiene inscrito ningún curso</em></p></center>

            </div>    

        {/foreach}
        
        
        {*RIBBON*}
        {HTML::style('css/ribbon.css')}
{*RIBBON*}
       
       
