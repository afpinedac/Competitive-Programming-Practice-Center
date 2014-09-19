{capture assign='content'}        
{assign var=envios value=envio::get_numero_envios('usuarioentaller', $usuario->id , $taller->id)}
{assign var=title value='Envios realizados por el estudiante'}
{include file='../../../graficas/timeline.tpl'}
<div  id="timeline" style=" height: 300px;"></div>

    
    <h4>Envíos</h4>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
        <th>ID</th>
        <th>Ejercicio</th>
        <th>Tipo solución</th>
        <th>Fecha</th>
        <th>Resultado</th>
        <th>Tiempo (seg)</th>
    </thead>
    <tbody>
        
        {foreach $usuario->get_envios_en_taller($taller->id) as $envio}
         
            
         
         
            {assign var=tipo_entrada value=ejercicioxtaller::where('ejercicio', $envio->ejercicio)->where('taller',$taller->id)->first()->tipo_entrada}
         
        <tr class='{if $envio->resultado == 'accepted'}success{/if}'>
            <td>{$envio->id}</td>
            <td>{$envio->nombre}</td>
            <td>{if ejercicio::find($envio->ejercicio)->get_tipo_entrada_en_taller($taller->id) == 0} out {else} código {/if}</td>
            <td>{$envio->created_at}</td>
            <td>
                
                {if $tipo_entrada==1}
                <a href='{url("curso/monitorear/")}/{$curso->id}/talleres/{$taller->id}/envios/{$envio->id}'>{$envio->resultado}</a>
                
               {*  {envio::find($envio->id)->get_similares()|var_dump}*}
                  {if $envio->resultado == 'accepted'}  
                      {assign var=sss value=envio::find($envio->id)->get_similares()}
                    {if !empty($sss)}
                        <i class="icon icon-star"></i> ({$sss|count})
                    {/if}
                    
                    {/if}
                    
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


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
