{capture assign='content'}


    
    <div class="row-fluid">
        <div class="span12">
                
        
            
            <div class="row-fluid">
                <div class="span12">
                    
                    
                    
                    

                    
                    
                    <div class="row-fluid">
                        <div class="span12">
                                {* Informacion General*}
                                <span class='badge-info badge'><em>Nombre:</em></span> {$estudiante->nombres|capitalize} {$estudiante->apellidos|capitalize}<br>
                                <span class='badge-info badge'><em>Email:</em></span> {$estudiante->email}<br>
                                <span class='badge-info badge'><em>Registrado desde:</em></span> {$estudiante->fecha_registro}<br>
                                
                                
                                
                        </div>
                    </div>
                    
                                <hr>
                                
                                
                                                         <div class="row-fluid">
                                    <div class="span12">
                                        <h3>Envíos en el curso</h3>
                                        
                                        
                                        
                                        
{*char de envios*}    
    {assign var=envios value=envio::get_numero_envios('estudiante',$estudiante->id)}
{assign var=title value='Envios realizados por el estudiante'}

{include file='../../../graficas/timeline.tpl'}
 <center><div  id="timeline" style="width: 800px; height: 300px;"></div></center>
{*end char de envios*}
                                    </div>
                                </div>
                                
                                <hr>
                    
                    
                                <div class="row-fluid">
                                    <div class="span12">
                                          {* Tabla de talleres *}
                                          <h3>Talleres {* <p class='pull-right'><a href='{url('curso/monitorear')}/{$curso->id}/estudiante/{$estudiante->id}/taller'><img src='{url('img/general/estadisticas.gif')}'></a></p>*}
                                          </h3>
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                        <th width="5%">Estado</th>
                        <th>Módulo</th>
                        <th>Ejercicios Resueltos</th>                        
                        <th>Estadísticas</th>                        
                        </thead>
                        <tbody>
                            
                            {foreach $curso->get_modulos() as $modulo}

                            <tr>
                                <td><i class='icon icon-{if modulo::find($modulo->id)->esta_desbloqueado()}unlock{else}lock{/if}'></i></td>
                                <td>{$modulo->nombre|capitalize}</td>
                                <td>{usuario::find($estudiante->id)->get_numero_ejercicios_resultos_en_taller($modulo->id)}/{taller::find($modulo->id)->get_numero_ejercicios()} ({usuario::find(Auth::user()->id)->get_porcentaje_en_taller($modulo->id)|string_format:"%.2f"}%)</td>
                                <td><a href='{url('curso/monitorear')}/{$curso->id}/estudiante/{$estudiante->id}/taller/{$modulo->id}'><img class='estadisticas' src='{url('img/general/estadisticas.gif')}'></a></td>
                            </tr>
                                
                            {/foreach}

                            
                           
                            
                        </tbody>    
                    </table>   
                                        
                                        
                                    </div>
                                </div>
                                
                                
                                <hr>
                                
                                                  <div class="row-fluid">
                                    <div class="span12">
                                          {* Tabla de evaluaciones *}
                                             <h3>Evaluaciones {*<p class='pull-right'><a href='{url('curso/monitorear')}/{$curso->id}/estudiante/{$estudiante->id}/evaluacion'><img src='{url('img/general/estadisticas.gif')}'></a></p>*}</h3>
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                        <th>Módulo</th>
                        <th>Evaluación</th>
                        <th>Ejercicios resueltos</th>
                        <th>Estadísticas</th>
                        </thead>
                        <tbody>
                            
                            {foreach $curso->get_modulos() as $modulo}

                            

                            {foreach modulo::find($modulo->id)->get_evaluaciones() as $evaluacion}
                            <tr>
                                <td>{$modulo->nombre|capitalize}</td>
                                <td>{$evaluacion->nombre}</td>
                                <td>{usuario::find(Auth::user()->id)->get_numero_ejercicios_resultos_en_evaluacion($evaluacion->id)}/{evaluacion::find($evaluacion->id)->get_numero_ejercicios()}</td>
                                <td><a href='{url('curso/monitorear')}/{$curso->id}/estudiante/{$estudiante->id}/evaluacion/{$evaluacion->id}'><img class='estadisticas' src='{url('img/general/estadisticas.gif')}'></a></td>
                            </tr>
                           {/foreach}
                            
                            {/foreach}

                           
                            
                        </tbody>    
                    </table>   
                                        
                                        
                                    </div>
                                </div>
                                
                                
                                <hr>
                                
       
                    <div class="row-fluid">
                        <div class="span12">
                            
                    {*Bitacora de logueo*}
                    
                    
                    <h3>Registro de Ingresos</h3>
                    <table class="table table-hover table-bordered table-condensed">
                        <thead>
                        <th>Fecha Ingreso</th>
                        <th>Fecha Salida</th>
                        <th>Tiempo total (H:m:s)</th>
                        </thead>
                        <tbody>
                            {foreach $logueos as $logueo}
                            <tr>
                                <td>{$logueo->fecha_ingreso}</td>
                                <td>{$logueo->fecha_salida}</td>
                                <td>{$logueo->tiempo_total}</td>
                            </tr>
                            {/foreach}
                        </tbody>    
                    </table>    
                        </div>
                    </div>
                    
                    
                </div>
            </div>
            
            
        </div>
    </div>
    
    

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='estudiantes'}
