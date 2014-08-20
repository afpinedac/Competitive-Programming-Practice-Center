{capture assign='content'}
    

    
    <h4>Envíos</h4>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
        <th>ID</th>
        <th>Ejercicio</th>
        <th>Fecha</th>
        <th>Resultado</th>
        <th>Tiempo (seg)</th>
    </thead>
    <tbody>
        
        {foreach $usuario->get_envios_en_evaluacion($evaluacion->id) as $envio}

            {assign var=tipo_entrada value=ejercicioxevaluacion::where('ejercicio', $envio->ejercicio)->where('evaluacion',$evaluacion->id)->first()->tipo_entrada}
        <tr class='{if $envio->resultado=='accepted'}success{/if}'>
            <td>{$envio->id}</td>
            <td>{$envio->nombre}</td>
            <td>{$envio->created_at}</td>
            
            <td>
                {if $tipo_entrada==1}
                <a href='{url("curso/monitorear/")}/{$curso->id}/evaluaciones/{$evaluacion->id}/envios/{$envio->id}'>{$envio->resultado}</a>
                {else}
                    {$envio->resultado}
                {/if}
            </td>
            <td>{$envio->tiempo_de_ejecucion}</td>
        </tr>
            
            
        {/foreach}

    </tbody>    
</table>    
    

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='evaluacion'}
