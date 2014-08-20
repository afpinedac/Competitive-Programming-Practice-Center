
                
    
    <h4 style="margin-top: -20px;" class="pull-right"><i class='icon icon-plus'></i> <a href='#nuevo-curso' data-toggle='modal'>Crear nuevo curso</a></h4>

                
                
            {HTML::script('js/curso.js')}
            
            
            
            <hr>
            
            <h3><i class='icon icon-star-empty'></i> Cursos creados</h3>
            
           {if count($mis_cursos)>0}
            
            <table class="table table-striped table-condensed table-bordered">
                <thead>
                <th>Nombre</th>
                <th>Fecha de creación</th>
                <th># Estudiantes</th>
                <th># Módulos</th>
                <th># Ejercicios</th>
                <th>¿Público?</th>                
                <th>Opciones</th>                
            </thead>
            <tbody>
                
                {foreach from=$mis_cursos item=curso}
                <tr>
                    <td>{$curso->nombre|truncate:45}</td>
                    <td>{$curso->created_at|date_format}</td>
                    <td>{curso::find($curso->id)->get_numero_estudiantes_inscritos()}</td>
                    <td>{curso::find($curso->id)->get_numero_modulos()}</td>
                    <td>{curso::find($curso->id)->get_numero_ejercicios()}</td>
                    <td>{if $curso->publico == 1}{"si"}{else}{"no"} <br>(pass: {$curso->password}){/if}</td>
                  
                    <td><a  class='btn btn-mini btn-info' href='{URL::to('curso/monitorear/')}/{$curso->id}'>Monitorear</a>
                   
                    <a onclick="return lms.confirmar()" class='btn btn-mini btn-danger' href='{URL::to('curso/eliminar/')}/{$curso->id}'>Eliminar</a></td>
                </tr>
                {/foreach}
            </tbody>    
</table>    
            
            {else}
                    <div class="alert alert-danger">
                <center><p><em>No tiene ningún curso creado</em></p></center>

            </div>    

                {/if}
            
            
            {* MODAL CREAR CURSO *}
            
            
             <div id="nuevo-curso" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-plus-sign'></i> Crear curso</h4>

    </div>
    <div class="modal-body">
        {Form::open(['action'=>'CursoController@postCrear'])}
           
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="text" name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' class='span12'  rows="4" required placeholder="Escriba la descripción"></textarea>
                </div>
                                
            </div>
        
        <div class="row-fluid">
            <div class="span3">
 
                <span class='pull-right' style='margin-top: -7px'><strong> Privado</strong></span><input style='margin-top: -2px;' type="checkbox" value='1' data-public='1' id='is_public' class="pull-right" onclick="curso.set_publico()" name="publico" title="Los estudiantes que se quieran inscribir necesitaran conocer la contraseña del curso">
   
            </div>
            <div class="span7">
 <input type="text" id='password_curso'  name="password"   placeholder='Contraseña' class='span12  hide' >
            </div>
        </div>
            
      

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-inverse" onclick="return validar();"><i class='icon icon-plus-sign' ></i> Crear</button>
            </div>
        {Form::close()}
    </div>

</div>
    
    
    <script>
        function validar(){
            
            if($("#is_public").prop('checked')){
                if($("#password_curso").val().length==0){
                    window.alert("La contraseña es obligatoria");
                    $("#password_curso").focus();
                    return false;
                }
            }
            
            return true;
        }
        
    </script>
            
            
            
            
            

