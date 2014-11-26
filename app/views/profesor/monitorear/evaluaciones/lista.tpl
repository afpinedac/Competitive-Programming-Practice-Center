{capture assign='content'}

    
    <ul>
        
        
        {foreach $modulos as $modulo}
            
             {assign var=evaluaciones value=modulo::find($modulo->id)->get_evaluaciones()}
            
        <li>
            <span> <strong>{$modulo->nombre|capitalize}:</strong> {if count($evaluaciones) eq 0} (<em class="small">Este módulo no tiene evaluaciones</em>) {/if}  </span>

            
             
             {if count($evaluaciones)>0}
             
            <ul>               
               
                
                {foreach $evaluaciones  as $evaluacion}
                  <li><a target="_blank" href="{url('evaluacion/ranking')}/{$evaluacion->id}">{$evaluacion->nombre}</a>: {$evaluacion->descripcion} , (<a href="{url('curso/monitorear')}/{$curso->id}/evaluaciones/{$evaluacion->id}/ejercicios">{evaluacion::find($evaluacion->id)->get_numero_ejercicios()}</a>) ejercicios , con una duración de <strong>{LMSController::formatear_tiempo($evaluacion->duracion)}</strong> , con porcentaje de aprobación de <strong>{$evaluacion->porcentaje_aprobacion}%</strong> </li>    
                {/foreach}

                
                
            </ul>
                <br>
              {/if}
            
            
        </li>    
        {/foreach}
        
    </ul>
   
       
        
    </tbody>    
</table>    
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='evaluaciones'}
