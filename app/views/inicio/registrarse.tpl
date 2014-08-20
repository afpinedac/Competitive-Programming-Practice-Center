{capture assign='content'}

    
    <div class="row-fluid">
        <div class="span12">

    
    <div class="row-fluid">
        <div class="span12">
 {include file='./_components/header_login.tpl'}
        </div>
    </div>
    
   
    
 
    
    <div class="row-fluid">
        <div class="span12">
            <div class="span6 offset3">
                
                <center><a href='{url('/')}'><i class='icon icon-reply'></i> Regresar</a></center>
                <div class="row-fluid">
		<div class="span12 well">
			<legend>Registrate (es gratis)</legend>
                        
                        
                      
                        
                        
                        
                       {if $errors->has()}
                          <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                           {foreach from=$errors->all() key=key item=item}
                              <h5>{$item}</h5>
                           {/foreach}  </div>                    
                      

    
                       {/if}    
                        

                        
                        
          	
			{Form::open(['url'=>'registrar'])}
                            <div>
                            <label style='margin-top: 4px;' for="nombre" class='span3'><strong>Nombre</strong> </label><input type="text" id="nombre" class="span9" name="nombres" value='{Input::old('nombres')}' placeholder="Nombres">
			 </div>
                            <div>
                            <label style='margin-top: 4px;' for="apellido" class='span3'><strong>Apellido</strong> </label><input type="text" id="apellido" class="span9" name="apellidos" value='{Input::old('apellidos')}' placeholder="Apellidos">
			 </div>
                            <div>
                                <label style='margin-top: 4px;' for="email" class='span3'><strong>E-mail</strong> </label><input type="text" id="email" class="span9" name="email" value="{Input::old('email')}" placeholder="E-mail">
			 </div>
                            <div>
                                
                                 <label style='margin-top: 4px;' for="university" class='span3'><strong>Universidad</strong> </label>
                                 <select name='universidad' required class='span9' >
                                    <option value=''>Seleccione universidad</option>
                                {foreach from=$universidades item=universidad}
                              <option {if $universidad->id == Input::old('universidad')} selected{/if} value='{$universidad->id}'>{$universidad->nombre}</option>
                           {/foreach}
                                
                           </select>
                                
                                
                             <!--  <input type="text" id="universidad" class="span9" name="universidad" value="Input::old('universidad')" placeholder="Universidad"> -->
			 </div>
                            <div>
                                <label style='margin-top: 4px;' for="password" class='span4'><strong>Contraseña</strong> </label><input type="password" id="password" class="span8" name="password" value='{Input::old('password')}' placeholder="Contraseña">
			 </div>
                            <div>
                                <label style='margin-top: 4px; font-size: 12px;' for="confirmed_password" class='span4'><strong>Repetir contraseña:</strong> </label><input type="password" id="confirmed_password" class="span8" value='{Input::old('password_confirmation')}' name="password_confirmation" placeholder="Repetir contraseña">
			 </div>
                         
                         
                               <div>
                                
                                 <label style='margin-top: 4px;' for="genero" class='span3'><strong>Género</strong> </label>
                                 <select name='genero' required class='span9' >
                                    
                              <option {if Input::old('genero') == "1"} selected{/if} value='1'>Masculino</option>
                              <option {if Input::old('genero') == "2"} selected{/if} value='2'>Femenino</option>
                           
                                
                           </select>
                                
                                
                             <!--  <input type="text" id="universidad" class="span9" name="universidad" value="Input::old('universidad')" placeholder="Universidad"> -->
			 </div>
                         
                         
                            <div>
                                <input style='margin-top: -2px;' {if Input::old('profesor') == 1}checked{/if} type="checkbox" value='1' name="profesor"><span class=''><strong> Soy profesor</strong></span>
			 </div>
           <br>
			<button type="submit"  class="btn btn-success btn-block">REGISTRAR</button>
			{Form::close()}  
		</div>
</div>


            </div>
        </div>
    </div>



    </div>
    </div>



{/capture}   


{include file='_templates/template.tpl' layout=''}
