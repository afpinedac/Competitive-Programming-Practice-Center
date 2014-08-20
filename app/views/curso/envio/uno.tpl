{capture assign='content'}

  
     
    
    <a class='pull-right' href='{url("curso/ver")}/{$curso->id}/mis-envios'><i class='icon icon-reply'></i> Volver a mis envíos</a>
    
    
    <h4>Envio #{$envio->id}</h4>
    
    <ul>
        <li><strong>Fecha de envio:</strong> {$envio->created_at}</li>    
        <li><strong>Resultado:</strong> {$envio->resultado}</li>    
        <li><strong>Tiempo de ejecución:</strong> {$envio->tiempo_de_ejecucion|default:"-"}</li>    
        <li><strong>Mensaje del Juez:</strong> <pre><small>{e($envio->mensaje|default:"-")}</small></pre></li>    
    </ul>
    
  
    
    
    <pre>{e($envio->algoritmo)}</pre>
    
    
    
    
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='envios'}
