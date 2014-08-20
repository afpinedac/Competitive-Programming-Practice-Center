

<div class="row-fluid">
                            <div class="span12">
                                
                                
                                <div class="row-fluid">
                                    <div class="span12">
                             
  <h3 style='float: left' id="ejercicios"><i class='icon icon-cogs icon-2x'></i> Ejercicios </h3>&nbsp;
  
 
                             
    <a style="margin-bottom: -30px" href='{url('curso/ver')}/{$curso->id}/mis-envios' title="Información de todos los envios" class="pull-right"> <i class='icon icon-cogs'></i> Ver todos mis envíos</a>
                            </div>
                        </div>
                                    </div>
                                </div>

<div class="row-fluid">
    <div class="span12">

        {assign var=ejercicios value=$modulo->get_ejercicios()}
        {foreach $ejercicios as $ejercicio}
            {*
            <div class="wrapper">
       <div class="ribbon-wrapper-green"><div class="ribbon-green">Aceptado</div></div>
</div>*}
{assign var=resuelto value=ejercicio::find($ejercicio->id)->esta_resuelto(Auth::user()->id, 0 , $modulo->id)}    

            <div class="row-fluid" style="margin-top: -10px;">
                <div class="span12 well well-small {if $resuelto}wrapper{/if}">
                   {if $resuelto}  <div class="ribbon-wrapper-green"><div class="ribbon-green">Aceptado</div></div>{/if}
                    <h4 class="pull-left"><i class='icon icon-star'></i> <a class='ejercicio' data-tipped='{url('ejercicio/envios/')}/{$curso->id}/{$ejercicio->id}/{$modulo->id}' href='{url('curso/ver/')}/{$curso->id}/ejercicio/{$ejercicio->id}'>{$ejercicio->nombre}</a></h4>
                    
                     {if $resuelto}
                            <br>
                     
                        
                     {if $ejercicio->tipo_entrada==1 and $curso->tiene_soluciones_visibles()}
                         <span style="margin-top: -25px; margin-right: 70px;" class="pull-right"><a href='{url('curso/ver/')}/{$curso->id}/ejercicio/{$ejercicio->id}#soluciones'>[<i class='icon icon-eye-open'></i> soluciones]</a></span>
                     {/if}  
                      
                         {/if}
                    <br>
                    <br>
                    <ul>
                        
                        <li><small><em><strong>Tipo solución:</strong></em> {if $ejercicio->tipo_entrada == 1}Código{else}Solo respuesta{/if}</small></li> 
                        {if $ejercicio->tipo_entrada == 1}
                            <li><small><em><strong>Tiempo límite:</strong></em> {$ejercicio->time_limit|string_format:"%.1f"} seg</small></li> 
                        {/if}
                    {if $ejercicio->tipo_formulacion==0}
                        <li><em>{$ejercicio->formulacion|truncate:150}</em></li>

                        {/if}
                        
                    </ul>
                       
                        {if $resuelto}
                           <span class="pull-right" style="margin-top:  -30px;margin-right: 5px;"> <small>Solucionado al </small> <strong>{usuario::find(Auth::user()->id)->numero_de_intentos_ejercicio_accepted($curso->id,$ejercicio->id,0,$ejercicio->taller)}</strong> <small>intento</small>
                         </span>  
                          
                         {if $ejercicio->tipo_entrada == 1}                             
                         
                               <span class="pull-right" style="margin-top:  -15px;margin-right: 5px;"> <small>Tiempo de ejecución: </small> <strong>{usuario::find(Auth::user()->id)->get_mejor_tiempo_ejecucion_ejercicio($ejercicio->id,$ejercicio->taller)} <small>seg.</small></strong>
                         </span>  
                     
                             
                             
                             {/if}
                         {/if}
                         <br>
                         
                         {assign var=veces_solucionado value=ejercicio::find($ejercicio->id)->get_numero_veces_resuelto(0,$modulo->id,$ejercicio->id)}
                        <span class="pull-right" style="margin-top:  -20px;margin-right: 5px;"> <small>Solucionado  </small> <strong>{$veces_solucionado}</strong> <small>{if $veces_solucionado!=1}veces{else}vez{/if}</small></span>  

                </div>
            </div>

        {foreachelse}
            <div class="alert alert-block">
                                              <p><center>Este módulo no tiene ejercicios</center></p>
                                          </div> 
        {/foreach}

    </div>
</div>



                    
                            
        
        
        
  {*RIBBON CSS*}
  
{HTML::style('css/ribbon.css')}
  
  {*END RIBBON CSS*}