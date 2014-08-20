{capture assign='content'}
    

    
    <h4>Envio #{$envio->id}</h4>
    
    <ul>
        <li><strong>Fecha de envio:</strong> {$envio->created_at}</li>    
        <li><strong>Resultado:</strong> {$envio->resultado}</li>    
        <li><strong>Tiempo de ejecución:</strong> {$envio->tiempo_de_ejecucion} s</li>    
    </ul>
    
  
    
    
    <pre>{e($envio->algoritmo)}</pre>
    

    

    
 

    

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
