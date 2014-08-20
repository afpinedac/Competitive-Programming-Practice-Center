

<div class="row-fluid">
                            <div class="span12">
                                
                                
                                <div class="row-fluid">
                                    <div class="span12">
                             
  <h3 style='float: left' id="evaluaciones"><i class='icon icon-file-text-alt icon-2x'></i> Competencia</h3>&nbsp;
                             
  
                            </div>
                        </div>
                                    </div>
                                </div>



                        <div class="row-fluid">
                            <div class="span12">
                                {assign var=evaluaciones value=$modulo->get_evaluaciones()}
                                {if count($evaluaciones)>0}
                              
                                    <table class="table table-bordered table-condensed table-striped">
                                        <thead>
                                        <th>Nombre</th>
                                        <th>Fecha Activación</th>
                                        <th>Duración</th>
                                        <th>Porcentaje Aprobación</th>
                                        <th>Estado</th>
                                        </thead>
                                        <tbody>                                       
                                
                                    {foreach $evaluaciones as $evaluacion}
                                        
                                        
                                        
                                        
                                    
                                    <tr>
                                        <td>{$evaluacion->nombre}</td>
                                        <td>{$evaluacion->fecha_activacion}</td>
                                        <td>{LMSController::formatear_tiempo($evaluacion->duracion)}</td>
                                        <td>{$evaluacion->porcentaje_aprobacion}%</td>
                                        <td>
                                            {if evaluacion::find($evaluacion->id)->get_time_fin() >= time() and time() >= ($evaluacion->fecha_activacion|strtotime)}
                                            <a href='{url('curso/ver')}/{$curso->id}/evaluacion/{$evaluacion->id}' class='btn btn-mini btn-success'>Presentar</a>                                        
                                            {else if ($evaluacion->fecha_activacion|strtotime)  + ($evaluacion->duracion)*60 < time()}
                                                <button class='btn btn-info btn-mini'><i class='icon icon-lock'></i> Finalizada</button>
                                            {if evaluacion::find($evaluacion->id)->get_time_fin() + 30 < time()}    
                                                <br>                                                
                                                <a href='{url('curso')}/ver/{$curso->id}/evaluacion/{$evaluacion->id}/resultado' class='btn btn-success btn-mini'><i class='icon icon-pencil'></i> <small>Resultados</small></a>
                                             {/if}  
                                            {else}
                                                    <button class='btn btn-mini'><i class='icon icon-lock'></i> bloqueado</button>
                                                 
                                                   
                                                {/if}
                                                
                                        </td>
                                     </tr>    
                                      
                                    {/foreach}
                               
                                             </tbody>    
                                    </table>   
                                    
                                {else}
                                   <div class="alert alert-block">
                                              <p><center>Este módulo no tiene competencias</center></p>
                                          </div>  

                                    {/if}


                            </div>
                        </div>
                      
                                