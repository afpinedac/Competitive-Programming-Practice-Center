{capture assign='content'}

     
    
    
    
    <div class="row-fluid">
        <div class="span12">
                            
            <div class="span3">
                {include file='./components/lista_modulos.tpl'}
            </div>
            
            
            <div class="span9">
                           
                            {if count($modulos)>0}
                            
                            <div class="row-fluid">
                               
                                <div class="span12">
                                        
                                    <div class="span9 well well-small">
                                        <div class="row-fluid">
                                            <form action="{url('modulo/editar')}/{$modulo->id}" method="post">
                                            <div class="span12">
                                                         <div class="span4">
                                    <center>Nombre:</center>
                                    <input type="text" name="nombre" required class='span12' style='font-size: 18px; height: 30px; padding: 3px; color: #2fa4e7' value='{$modulo->nombre}'><br>
                                    
                                </div>
                                <div class="span6">
                                    <center># Mínimo de ejercicios para desbloquear</center>
                                    
                                    <center>
                                        
                                        <select class="input-mini" name="minimo_para_desbloquear">
                                            {for $var=0 to $modulo->get_numero_ejercicios()}
                                            <option {if $modulo->minimo_para_desbloquear == $var}selected{/if}>{$var}</option>
                                            {/for}
                                        </select>
                                        
                                    </center>
                                    {if Session::has('error')}
                                    <center class='fadeOut' style='margin-top: -10px;'><small class='text-error'>El número es inválido, debe ser menor o igual al numero de ejercicios ({count($ejercicios)})</small></center>
                                    {/if}
                                    <input type="hidden" class='input-mini' value='{Crypt::encrypt($curso->id)}' name="curso">
                                </div>
                                <div class="span2" style='margin-top: 10px; '>
                                 
                                    <button style='margin-top:10px;' class='btn  btn-block btn-success' onclick="return ec.validar()">  <i class='icon icon-save'></i> Guardar</button>
                                   <!-- <a style='font-size: 10px;' href='#' class='pull-right' onclick="return ec.ver()">ver más</a>  -->                               
                                </div>
                                            </div>
                             
                                    </form>
                                    
                                        </div>
                                    </div>
                                    <div class="span3">
                                           <div style='margin-top: 10px;' class="well well-small">
                                    <center><a href='{URL::to('modulo/eliminar/')}/{$modulo->id}' onclick="return confirm('¿Esta seguro de eliminar el módulo?') && confirm('¿Si elimina el módulo se perderan toda la informacion de pre-requisitos, materiales,ejercicios,evaluaciones subidos a este, está seguro?');"><i class='icon icon-trash'></i> Eliminar módulo</a></center>
                                </div>
                                    </div>
                                    
                                </div> 

                                

                           
                                     
                                
                                
                             
                            </div>
                            {*
                                <div class="row-fluid">
                                   <span class='pull-right'><em >Ir a: </em><a href='#materiales' class='badge badge-important'>Materiales</a> <a href='#ejercicios' class='badge badge-info'>Ejercicios</a> <a href='#evaluaciones' class='badge badge-success'>Evaluaciones</a></span>
                                </div>
                                *}
                             
                            
                                
          
                               
                                
                             

                                

                            <div class="row-fluid">
                                <div class="span12">
                                   
                                    <h3 id="materiales"><i class='icon icon-book icon-2x'></i> Materiales</h3>
                                    <a href='#nuevo-material'  class='' data-toggle='modal'><i class='icon icon-plus'></i><em> Agregar nuevo material</em></a>
                                    <br>
                                    
                                    {if count($materiales)>0}
                                    <br>
                                  
                                    <div class="row-fluid">
                                        <div class="span12">

                                             <table class="table table-bordered table-striped table-condensed">
                                                <thead>
                                                <th width='30%'>Nombre</th>
                                                <th>Descripción</th>
                                                <th width='15%'>Opciones</th>
                                                </thead>
                                                <tbody>
                                                    {foreach $materiales as $material}
                                                    <tr>
                                                        <td>{$material->nombre}</td>
                                                        <td>{$material->descripcion}</td>
                                                        <td> 
                                                            {if $material->enlace}  <a  target='_blank' href='{$material->enlace}'><i class='icon icon-option icon-link' title="Ir a la dirección"></i></a>{/if}
                                                    {if $material->archivo}
                                                    <a target='_blank' href='{url('contenido/descargar/')}/{LMSController::encoder($material->id)}'><i class='icon icon-option icon-download' title="Descargar"></i></a>
                                                        {/if} 
                                                    
                                                        <a href='#editar-material' data-toggle='modal' onclick="editar_material.editar({$material->id})"><i class='icon icon-option icon-edit' title="Editar"></i></a>
                                                        <a href='{url('contenido/eliminar')}/{$material->id}' onclick="return lms.confirmar()"><i class='icon icon-option icon-trash' title='Eliminar'></i></a>
                                                    
                                                    </td>
                                                    </tr>
                                                     {/foreach} 
                                                </tbody>    
          </table>   
                                          
                                         </div>
                                    </div>
                                                
                                     {/if}
                                </div>
                            </div>
                            <hr>
                            
                            
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="span6">
        
                                    <h3 id='ejercicios'><i class='icon icon-cogs icon-2x'></i> Taller</h3> 
                                    <span class=''>  <a href='#agregar-ejercicio' data-toggle='modal'><i class='icon icon-plus'></i> Añadir nuevo ejercicio</a></span>
                                    
                                            </div>
                                            <div class="span6">
                                                <a class='btn btn-success pull-right' href='#nuevo-ejercicio' data-toggle='modal' onclick="nuevo_ejercicio.reestablecer_formulario()"><i class='icon icon-plus'></i> Crear nuevo ejercicio</a>
                                            </div>
                              
                                      </div>
                                    </div>
                                    
                                    
                                    <div class="row-fluid">
                                        <div class="span12">
                                                 <span class='pull-right'><a href='#' onclick="return oa.ver();"><i class='icon icon-cog'></i> Opciones avanzadas</a></span> 
                                                                  {*Opciones avanzadas*}

                                <div class="row-fluid hide" id='div-opciones-avanzadas'>
                                    <div class="span12 well">
                                        {include file='./components/opciones_avanzadas.tpl'}
                                    </div>
                                </div>
                                {*script para hacer mostrar/ocultar el div de opciones avanzadas*}
                                    <script>
                                    oa = {
                                        open : false,
                                        ver : function(){
                                                if(oa.open){
                                                    oa.open = false;
                                                    $("#div-opciones-avanzadas").hide();
                                                }else{
                                                    oa.open = true;
                                                    $("#div-opciones-avanzadas").show();
                                                }
                                                return false;
                                        }
                                        
                                    }
                                    </script>
                                    
                                    <hr>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="row-fluid">
                                        <div class="span12">

                               
                                    {if count($ejercicios)>0}
                                        <table class="table table-bordered table-striped table-condensed">
                                            <thead>
                                            <th>Orden</th>
                                            <th>Nombre</th>
                                            <th>Tipo Respuesta</th>
                                            <th>Tiempo Limite (seg)</th>
                                            <th>Opciones</th>
                                            </thead>
                                            <tbody>
                                                {foreach $ejercicios as $ejercicio}                                                    
                                                <tr>
                                                    <td><a href='{url('ejercicio/cambiar-prioridad/')}/{$curso->id}/{$taller->id}/{$ejercicio->id}'><i class='icon icon-arrow-up'></i></a> 
                                                        <a href='{url('ejercicio/cambiar-prioridad/')}/{$curso->id}/{$taller->id}/{$ejercicio->id}/d'><i class='icon icon-arrow-down'></i></a>
                                                    </td>
                                                    <td>{$ejercicio->nombre}</td>
                                                    <td>{if $ejercicio->tipo_entrada == 0} solamente out {else}  código completo {/if}</td>
                                                    <td>{if $ejercicio->tipo_entrada == 0}No tiene{else}{$ejercicio->time_limit|string_format:"%.1f"}{/if}</td>
                                                    <td>
                                                        <a onclick="editar_agregar_ejercicio.editar({$ejercicio->id});" href='#editar-agregar-ejercicio'  data-toggle='modal' ><i class='icon icon-edit'></i></a>
                                                        <a onclick="return lms.confirmar()" href='{url('taller/eliminar-ejercicio')}/{$ejercicio->id}'> <i class='icon icon-trash'></i></a></td>
                                                </tr>
                                                    
                                                {/foreach}                                               
                                            </tbody>    
                                        </table>  
                                                {else}
                                                  
                                    {/if}
                                    
                             </div>
                                    </div>
                                    
                                </div>
                            </div>
                                                     
                            <div class="row-fluid">
                                <div class="span12">
                                    <h3 id='evaluaciones'><i class='icon icon-file-text-alt icon-2x'></i> Evaluaciones</h3>
  <a href='#nueva-evaluacion' class='' data-toggle='modal'><i class='icon icon-plus'></i><em> Crear nueva evaluación</em></a>
                                    <br>
                                    
                                    {if count($evaluaciones)>0}
                                    <br>
                                  
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <table class="table table-striped table-condensed table-bordered">
                                                <thead>
                                                <th width='15%'>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Fecha activación</th>
                                                <th>Duración</th>
                                                <th>Porcentaje Aprobación</th>
                                                <th>Ejercicios</th>
                                                <th>Opciones</th>
                                               
                                              {*  <th width='15%'>Opciones</th>*}
                                                </thead>
                                                <tbody>
                                                    {foreach $evaluaciones as $evaluacion}
                                                    <tr class='{if Session::get('nueva_evaluacion') == $evaluacion->id}success{/if}'>
                                                        <td>{$evaluacion->nombre}</td>
                                                        <td><p class='text-left'>{$evaluacion->descripcion}<p></td>
                                                        <td>{$evaluacion->fecha_activacion}</td>
                                                        <td>{$evaluacion->duracion} mins. ({LMSController::formatear_tiempo($evaluacion->duracion)})</td>
                                                        <td>{$evaluacion->porcentaje_aprobacion}%</td>
                                                        <td>(<span id='numero-ejercicios-{$evaluacion->id}'>{evaluacion::find($evaluacion->id)->get_numero_ejercicios()}</span>) <a href='#agregar-nuevo-ejercicio-evaluacion' onclick='ejercicio_evaluacion.set_evaluacion({$evaluacion->id})' data-toggle='modal' class='btn btn-mini btn-success'>Agregar / Editar</a></td>
                                                        <td><p class='text-center'>
                                                                <a href='#editar-evaluacion' onclick="editar_evaluacion.editar({$evaluacion->id})" data-toggle='modal'><i class='icon icon-edit'></i></a>
                                                                <a  onclick='return lms.confirmar()' href='{url('evaluacion/eliminar')}/{$evaluacion->id}'><i class='icon icon-trash'></i></a><p></td>
                                                      
                                                    </tr>
                                                     {/foreach} 
                                                </tbody>    
                                            </table>    
                                    
                                       
   
                                       
                                          
                                         </div>
                                    </div>
                                                
                                                {/if}
                                </div>
                            </div>
                             
                         
                            
                            <hr>
                           {if count($modulos)>1}
                            <div class="row-fluid">
                                <div class="span12">
                                    <h4><i class='icon icon-bookmark'></i> Pre-requisitos</h4>
                                    
                                    <div class="row-fluid">
                                        <div class="span12">
                                            {foreach $modulos as $pre}
                                                {if $pre->id != $modulo->id}
                                                    <input  style="margin-top: -1px;" {if modulo::es_pre_requisito($pre->id,$modulo->id)}checked=""{/if}  type="checkbox" name="" onclick="curso.verificar_pre_requisito({$pre->id},{$modulo->id},this)"><span> {$pre->nombre}</span><br>
                                               {/if}
                                                {/foreach}

                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                                            <hr>
                               {/if}             
                            
                                                    {else}
                                    <br>
                                    <br>
                                    <br>
                                    <div class="row-fluid">
                                        <div class="span12">
  <div class="alert alert-info span8 offset2">
                                        <center><p><em>Este curso no tiene módulos, intente creando uno en la parte superior</em></p>
                                        </center>
                                    </div>  
                                        </div>
                                    </div>
                            
                               
                              
                         
                            
                                {/if}
                                </div>  
            </div>
            
        </div>
    
   
     
     
  
   {*--------------------- MODALES DE CREACION ------------------*}
    
    
    {include file='./modales/nuevo_material.tpl'} 
    
    {include file='./modales/nuevo_ejercicio.tpl'}
    
   {include file='./modales/agregar_ejercicio.tpl'}
    
    
    {include file='./modales/nueva_evaluacion.tpl'}
    
    {include file='./modales/agregar_ejercicio_evaluacion.tpl'}
  
    
    {*---------------------- MODALES DE EDICIÓN -------------------- *}
     
    
    {include file='./modales/_editar_material.tpl'}   
    
    {include file='./modales/_editar_agregar_ejercicio.tpl'}   
    
     {include file='./modales/_editar_evaluacion.tpl'}   
    




{HTML::script('js/curso.js')}
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='edicion'}
