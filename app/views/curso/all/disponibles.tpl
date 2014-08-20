

<h3><i class='icon icon-star'></i> Cursos disponibles</h3>



<script>
    curso_disponible = {
            ver_contenido : function(curso){
                
                $("#contenido-"+curso).toggle();
              //  window.console.log('sds');
                return false;    
            }
       }
    
</script>



  {foreach $cursos_disponibles as $curso}
        
        <div class="row-fluid">
            <div class="span12 well">
                 <div class="span4">
                      <img src="{url('img/cursos/')}/{$curso->imagen}" class='img-presentacion-curso'>
                </div>
                <div class="span4">
                    <h3>{$curso->nombre|capitalize}</h3>
                    <ul>
                          
                    
                     <li><strong>Profesor: </strong>{$curso->nombres|capitalize} {$curso->apellidos|capitalize}</li>
            <li><strong>Fecha de creación: </strong>{$curso->created_at|date_format}</li>
           <!-- <li><strong>Numero de estudiantes inscritos: </strong>{*curso::find($curso->id)->get_numero_estudiantes_inscritos()*}</li>-->
            
            </ul>
            
                </div>
                <div class="span4">
                    <ul>
                        <li><strong>Descripción:</strong> {curso::find($curso->id)->descripcion|truncate:150}</li> 
                        <br>
                        <li><strong>Tipo:</strong> {if $curso->publico == 0} Privado <i class='icon icon-key'></i> {else}Público{/if}</li>
                   
                   {* <a onclick="return confirm('Si desmatricula este curso, se perderá todo el progreso en este. ¿Está seguro?') && confirm('¿Está completamente seguro?') && confirm('¿Enserio?')" href='{URL::to('curso/desmatricular')}/{$curso->id}' class='btn btn-danger btn-mini'>Desmatricular</a>*}
                    
                   <br>
                 <li>  <a onclick="return curso_disponible.ver_contenido({$curso->id});" href="#" >[Ver contenido]</a></li>
                 
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
                   
                   
                   
                    {if $curso->publico == 1} {*el curso es público*}
                    <a onclick="return lms.confirmar()" href='{URL::to('curso/inscribir')}/{$curso->id}' class='btn btn-small btn-success '>Inscribir</a>
                    {else}
                        <a  href='#password-curso' onclick="document.getElementById('curso_privado').value = {$curso->id};" data-toggle='modal' class='btn btn-success'><i class='icon icon-key'></i> Inscribir</a>
                        {/if}

                    

                    
                </div>
            </div>
        </div>
        {foreachelse}
                  
            <div class="alert alert-danger">
                <center><p><em>No tiene inscrito ningún curso</em></p></center>

            </div>    

        {/foreach}
        
        
  
    
                {*modal para inscribir un curso*}
                
                

    <div id="password-curso" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-plus-sign'></i> Inscribir curso</h4>

        </div>
        <div class="modal-body">
            {Form::open(['action'=>'CursoController@postInscribir'])}
            
            <input type="hidden" name="curso" id='curso_privado'>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right"><i class='icon icon-key'></i> Contraseña: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="text" name="password" >
                </div>
            </div>



            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class='icon icon-plus-sign'></i> Inscribir</button>
            </div>
            {Form::close()}
        </div>

    </div>
