{capture assign='content'}
    


 <ul>
        {foreach $modulos as $modulo}
            {assign var=taller value=taller::find($modulo->id)}
<li>
    
    <a href='{url('curso/monitorear/')}/{$curso->id}/talleres/{$modulo->id}'><strong style='font-size: 18px;'>{$modulo->nombre}</strong></a>
    
    <a href="{url('curso/monitorear/')}/{$curso->id}/talleres/{$modulo->id}/ejercicios" title="Ver ejercicios">({$modulo->minimo_para_desbloquear}/{$taller->get_numero_ejercicios()})</a>, 
    
    
    
    {*fecha inicio y fecha fin*}
   
    {if $taller->tiene_inicio == 0 and $taller->tiene_fin == 0}
       siempre activo      
    {else}
        {if $taller->tiene_inicio == 0}
        sin fecha de inicio
        {else }
        {$taller->fecha_inicio}       
     {/if}         -
    
        {if $taller->tiene_fin == 0}
        sin fecha de fin
        {else}
            {$taller->fin}
        {/if}
     {/if},

        {*envios tardios*}

        {if $taller->envios_tardios == 1}
            
        
           con envios tardios {if $taller->porcentaje_disminucion!=0}({$taller->porcentaje_disminucion}% menos cada {$taller->tiempo_disminucion} {$taller->unidad_disminucion}){/if}
         {else}
            sin envios tardios
        {/if}

        
</li>
    


{*
            <td><a href='{url('curso/monitorear')}/{$curso->id}/taller/{$modulo->id}'>{$modulo->nombre}</a></td>
            <td>{modulo::get_numero_ejercicios($modulo->id)}</td>
            <td>{modulo::get_numero_minimo_para_desbloquear($modulo->id)}</td>
        </tr>*}
        {/foreach}
 
</ul>
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
