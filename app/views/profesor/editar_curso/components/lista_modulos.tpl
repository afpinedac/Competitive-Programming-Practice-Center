       
<div class="row-fluid">
                    <div class="span12">

                        
              
                        <div class="row-fluid">
                           
                            <h3>Módulos <button onclick="curso.modulo.crear()" class='pull-right btn btn-small btn-success' style='font-size: 15px;'><i class='icon icon-plus'></i> Nuevo</button></h3>
                           
                        </div>
                            
     <div class="row-fluid hide" id="div-crear-modulo" data-state='0'>
                    <div class="span12">
                        
                        {Form::open(['action'=>'ModuloController@postCrear'])}
                       
                        <center> <input type="text" name='nombre' required class='input' autofocus="" placeholder='Nombre módulo' style='height: 30px;' name=""> </center>
                        <center><button class='btn btn-success btn-info btn-small' type='submit' style='margin-top: -7px' class='btn'>Crear</button> </center>
                        
                        {Form::close()}
                    </div>
                </div>
              
                            
                        
                        <div class="row-fluid">
                            <div class="span12">
              <div class="span12 well">
   <ul class="nav nav-list">
                            <li class="nav-header">Módulos</li>
                            
                            {foreach $modulos as $module}                                
                                
                                <li  class='{if Session::get('modulo_profesor') == $module->id}active{/if}'><a title="{$module->nombre}" href='{URL::to('modulo/editar/')}/{$module->id}' class='suspensive-points'>+ {$module->nombre}</a></li>  
                            
                            {/foreach}
                            
                           
                            <li class="divider"></li>
                            <li class="nav-header">Ejercicios</li>
                            <li class='{if $tab2=='ejercicios'}active{/if}'><a href='{url('curso/ver')}/{$curso->id}/editar/lista-ejercicios'><i class='icon icon-star'></i> Mis ejercicios</a></li>
                           
                            <li class="divider"></li>
                           <li class='{if $tab2=='edicion-curso'}active{/if}'><a href='{url('curso/ver')}/{$curso->id}/editar/informacion'><i class='icon icon-edit'></i> Edición curso</a></li>

                            
                        </ul>
                        </div>
                            </div>
                        </div>
                         
                           
          
                                      
                            
                            
                        <div class="span9" >
                            
                            <div class="row-fluid">
                                <div class="span12">
                                        {if isset($content)}
                                            {$content}
                                        {/if}
                                </div>
                            </div>
                          
                             
                            </div>
  
                                            
                                            
                                        </div>
                                    </div>