{capture assign='content'}


    
    
    
    
    <center><i class='icon icon-reply'></i> <a href='{URL::to('curso')}'> Volver</a></center>
    <br>
    
    <div class="row-fluid">
        <div class="span12">

            <div class="span6 offset3">
                        
                    <div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="{if !Session::has('pass')}active{/if}"><a href="#tab1" data-toggle="tab">Editar Información</a></li>
    <li class="{if Session::has('pass')}active{/if}"><a href="#tab2" data-toggle="tab">Cambiar Contraseña</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane {if !Session::has('pass')}active{/if}" id="tab1">
     
        
        {* Cambio de Información *}
                 <div class="row-fluid">
		<div class="span12 well">
			<legend>Actualizar</legend>
                        
                                              
                      
                        
                        
                        
                       {if $errors->has()}
                          <div class="alert alert-danger fadeOut">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                           {foreach from=$errors->all() key=key item=item}
                              <h5>{$item}</h5>
                           {/foreach}  </div>                    
                      

    
                       {/if} 
                        
                        
          	
			{Form::open(['action'=>'UsuarioController@postEditar'])}
                            <div>
                            <label style='margin-top: 4px;' for="nombre" class='span3'><strong>Nombre</strong> </label><input type="text" id="nombre" class="span9" name="nombres" value='{$usuario->nombres}' placeholder="Nombres">
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span3'><strong>Apellido</strong> </label><input type="text" id="apellido" class="span9" name="apellidos" value='{$usuario->apellidos}' placeholder="Apellidos">
			 </div>
                            <div>
                                <label style='margin-top: 4px;' for="email" class='span3'><strong>E-mail</strong> </label><input type="text" id="email" class="span9" name="email" value="{$usuario->email}" placeholder="E-mail">
                                {if Session::has('email_invalido')}
                                <center class='fadeOut'><p class='text-error'><strong>Este correo electrónico ya esta en uso</strong></p></center>
                                {/if}
                            </div>
                            <div>
                                
                                 <label style='margin-top: 4px;' for="university" class='span3'><strong>Universidad</strong> </label>
                                <select id='select-universidades' name='universidad' required class='span9' data-universidad='{$usuario->universidad_id}' >
                                    <option value=''>Seleccione universidad</option>
                                {foreach from=$universidades item=universidad}
                              <option value='{$universidad->id}'>{$universidad->nombre}</option>
                           {/foreach}
                           
                           </select>
                           
                           
                           
                                
                                
                             <!--  <input type="text" id="universidad" class="span9" name="universidad" value="Input::old('universidad')" placeholder="Universidad"> -->
			 </div>
                            <div>
                                
                                 <label style='margin-top: 4px;' for="genero" class='span3'><strong>Género</strong> </label>
                                <select id='' name='genero' required class='span9'  >                                  
                                
                              <option {if Auth::user()->genero == '1'}selected{/if} value='1'>Masculino</option>
                              <option {if Auth::user()->genero == '2'}selected{/if} value='2'>Femenino</option>
                           
                           
                           </select>
                           
                           
                           
                                
                                
                             <!--  <input type="text" id="universidad" class="span9" name="universidad" value="Input::old('universidad')" placeholder="Universidad"> -->
			 </div>
                        {*    <div>
                                <label style='margin-top: 4px;' for="password" class='span4'><strong>Contraseña</strong> </label><input type="password" id="password" class="span8" name="password" value='{Input::old('password')}' placeholder="Contraseña">
			 </div>
                            <div>
                                <label style='margin-top: 4px; font-size: 12px;' for="confirmed_password" class='span4'><strong>Repetir contraseña:</strong> </label><input type="password" id="confirmed_password" class="span8" value='{Input::old('password_confirmation')}' name="password_confirmation" placeholder="Repetir contraseña">
			 </div>*}
                            <div>
                              {*  <input style='margin-top: -2px;' type="checkbox"  name="profesor"><span class=''><strong> Soy profesor</strong></span> *}
			 </div>
           <br>
			<button type="submit"  class="btn btn-success btn-block">ACTUALIZAR</button>
			{Form::close()}  
		</div>
</div>
        
        
    </div>
    <div class="tab-pane {if Session::has('pass')}active{/if}" id="tab2">
    
        
         {* Cambio de Información *}
                 <div class="row-fluid">
		<div class="span12 well">
			<legend>Cambiar Contraseña</legend>
                        
                        
                           {if Session::has('valid')}
                          <div class="alert alert-success fadeOut">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <h5>Contraseña cambiada correctamente</h5>                  
                      
</div>


    
                       {/if} 
                        
                        
                      
                        
                        
                        
                       {if $errors->has()}
                          <div class="alert alert-danger fadeOut">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                           {foreach from=$errors->all() key=key item=item}
                              <h5>{$item}</h5>
                           {/foreach}  </div>                    
                      

    
                       {/if} 
                        
                        
          	
			{Form::open(['action'=>'UsuarioController@postEditarPassword'])}
                            <div>
                                <label style='margin-top: 4px;' for="nombre" class='span4'><strong>Contraseña actual:</strong> </label><input type="password" min="3" required id="nombre" class="span8" name="old_password" value='' placeholder="">
                              {if Session::has('invalid_old')}
                                <center class='fadeOut'><p class='text-error'><strong>La contraseña actual es incorrecta</strong></p></center>
                            {/if}
                              </div>
                            
                            <div>
                                <label style='margin-top: 4px;' for="apellido" class='span4'><strong>Nueva contraseña:</strong> </label><input type="password" min="3" required id="apellido" class="span8" name="new_password1" value='' placeholder="">
			 {if Session::has('invalid_coincidence')}
                                <center class='fadeOut'><p class='text-error'><strong>Las contraseñas no coinciden</strong></p></center>
                         {/if}   
                         </div>
                            <div>
                                <label style='margin-top: 4px;' for="email" class='span4'><strong>Repetir contraseña:</strong> </label><input type="password" min="3" required id="email" class="span8" name="new_password2" value="" placeholder="">
			 </div>
                      
                            <div>
                              
			 </div>
           <br>
			<button type="submit"  class="btn btn-success btn-block">CAMBIAR</button>
			{Form::close()}  
		</div>
</div>
        
        
        
    </div>
  </div>
</div>
                
            </div>
                
                <div class="span3">
                    {include file='./_components/sidebar_estudiante.tpl'}
                </div>
                
        </div>
    </div>
    

    

                
                
       
<script>
    $(document).ready(function(){
        $("#select-universidades").val($("#select-universidades").data('universidad'));
});
    </script>

   

                

    

{/capture}   


{include file='_templates/template.tpl' layout='default_login'}
