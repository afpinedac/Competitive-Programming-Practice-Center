{capture assign='content'}
    

    
    <h4>Envio #{$envio->id}</h4>
    
    <ul>
        <li><strong>Fecha de envio:</strong> {$envio->created_at}</li>    
        <li><strong>Resultado:</strong> {$envio->resultado}</li>    
        <li><strong>Tiempo de ejecución:</strong> {$envio->tiempo_de_ejecucion} s</li>    
    </ul>
    
  
    
    
    <pre>{e($envio->algoritmo)}</pre>
    

    

    {assign var=similars  value=envio::find($envio->id)->get_similares()}

    
    {if !empty($similars)}
    <hr>    
        <h3>Similares</h3>
        
        {foreach $similars as $similar}
            {assign var=sim value=envio::find($similar)}
  <ul>
        <li><strong>Estudiante</strong> {usuario::find($sim->usuario)->nombres} {usuario::find($sim->usuario)->apellidos} </li>    
        <li><strong>Fecha de envio:</strong> {$sim->created_at}</li>    
        <li><strong>Resultado:</strong> {$sim->resultado}</li>    
        <li><strong>Tiempo de ejecución:</strong> {$sim->tiempo_de_ejecucion} s</li>    
    </ul>
    
               <pre>{e($sim->algoritmo)}</pre>
               <hr>
        {/foreach}

        
        
        {/if}
    
 

    

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
