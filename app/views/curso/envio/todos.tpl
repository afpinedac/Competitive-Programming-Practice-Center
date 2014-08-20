{capture assign='content'}

  
     
    <a class='pull-right' href='{url("curso/ver")}/{$curso->id}/contenido'><i class='icon icon-reply'></i> Volver al contenido</a>
    
    
    {if count($envios)}
        
        
        <center><h2>Mis Envíos</h2></center>
        <br>

        
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <th>#</th>
            <th><small>Tipo</small></th>
            <th><small>Ejercicio</small></th>
            <th><small>Taller</small></th>
            <th><small>Fecha de envío</small></th>            
            <th><small>Lenguaje</small></th>
            <th><small>Resultado</small></th>
            <th><small>Tiempo de <br>ejecución</small></th>
            <th width="25%"><small>Mensaje</small></th>
           <!-- <th>Observación</th> -->
            
        </thead>
        <tbody>
            
                {foreach $envios as $envio}
                    <tr>
                    <td><strong>{$envio->id}</strong></td>
                    <td><small>{if $envio->tipo == 0}Taller{else}Evaluación{/if}</small></td>
                    <td>{ejercicio::find($envio->ejercicio)->nombre}</td>
                    <td><small>{if $envio->tipo == 0}{modulo::find($envio->codigo)->nombre}{else}{evaluacion::find($envio->codigo)->nombre}{/if}</small></td>
                    <td><small>{$envio->created_at}</small></td>
                    <td>{$envio->lenguaje}</td>
                    <td><a href='{url('curso/ver')}/{$curso->id}/mis-envios/{$envio->id}'><small>{$envio->resultado|default:"uknown"}</small></a></td>
                    <td>{$envio->tiempo_de_ejecucion|default:"-"}</td>
                    <td><small style="font-size: 9px;">{$envio->mensaje|default:"-"|truncate:200}</small></td>
                   <!-- <td>-</td> -->
                     </tr>
                {/foreach}

                
           
        </tbody>    
    </table>    
        
        {else}
            
            <div class="alert alert-block">
                <center><p>No has realizado ningún envío</p>
                </center>
            </div>    

            
    {/if}
  
    
    
    
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='envios'}
