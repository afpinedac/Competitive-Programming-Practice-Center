                    <div class="row-fluid">
                            <div class="span12">
              <div class="span12 well">
   <ul class="nav nav-list">
                            <li class="nav-header">Módulos</li>
                            
                            
                            
                            
                            
                            
                            {foreach $modulos as $module}                                
                              
                                
                                <li {if $module->id == $modulo->id}class='active'{/if}>
                                    <a href='{url('curso/modulo/')}/{$module->id}'>
                                        <span> <i class='icon icon-caret-right'></i> {$module->nombre}  </span>
                                        
                                        {assign var=desbloqueado value=modulo::find($module->id)->esta_desbloqueado()}
                                        
                                        {if $desbloqueado or $curso->profesor_id == Auth::user()->id or usuario::find(Auth::user()->id)->es_monitor($curso->id)}                                       
                                        
                                        {assign var=total value=modulo::find($module->id)->get_numero_ejercicios()}
                                        {assign var=solved value=modulo::find($module->id)->get_numero_ejercicios_realizados($curso->id,Auth::user()->id)}
                                        <span class='pull-right'>{if $total==0}-/-{else} {$solved}/{$total}{/if} &nbsp; <i class='icon icon-unlock'></i></span>
                                       
                                    {*    n: {modulo::get_numero_ejercicios($module->id)} -- r: {modulo::get_numero_ejercicios_realizados($curso->id,$module->id,Auth::user()->id)} *}
                                        
                                        
                                      
                                      
                                        
                                         <div style='margin-top: 10px; height: 10px; margin-top: 0px; margin-bottom: 6px;' class="progress progress-striped progress-success active">
                                        <div class="bar" style='width:{if $total==0}0{else}{($solved/$total)*100}{/if}%'></div>
                                         </div> 
                                            {else}
                                         
                                        <span class='pull-right'><i class='icon icon-lock icon-2x'></i></span>
                                       {/if}
                                    </a>
                                </li> 
                                <li class='divider'></li>
                                
                            
                            {/foreach}
                            {*
                            <li class='divider'></li>
                            
                            <li class="nav-header">Opciones</li>
                            <li><a href='{url('curso/ver')}/{$curso->id}/mis-envios'><i class='icon icon-search'></i> Ver mis envíos</a></li>
                            *}
                         
                        </ul>
                        </div>
                            </div>
                        </div>
                         
                            