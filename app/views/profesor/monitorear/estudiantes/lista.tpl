{capture assign='content'}
    
    
 
    
    <table class="table table-hover table-bordered table-condensed">
      <thead>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/estudiante/{$estudiante['id']}'>Nombre</a></th>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/estudiantes?sortby=ejercicios_resueltos'>Ejercicios resueltos</a></th>
       
        <th><a href='{url('curso/monitorear')}/{$curso->id}/estudiantes?sortby=tiempo_logueado'>Tiempo en la plataforma</a></th>   
        <th><a href='{url('curso/monitorear')}/{$curso->id}/estudiantes?sortby=ultimo_acceso'>Fecha Ultimo inicio de sesión</a></th>   
        <th><a href='{url('curso/monitorear')}/{$curso->id}/estudiantes?sortby=puntos'>Puntos</a></th>
        <th><a href="#">Monitor</a></th>
    </thead>
    <tbody>
        {foreach $estudiantes as $estudiante}
            <tr>
                <td>{$estudiante['nombres']|capitalize} {$estudiante['apellidos']|capitalize}</a></td>
                <td>{$estudiante['ejercicios_resueltos']} / {$curso->get_numero_ejercicios()}</a></td>       
                <td>{LMSController::formatear_tiempo($estudiante['tiempo_logueado'],'s')}</a></td>                
                <td>{$estudiante['ultimo_acceso']}</a></td>
                <td>{$estudiante['puntos']}</td>
                <td><input type="checkbox"  {if usuario::find($estudiante['id'])->es_monitor($curso->id)}checked{/if} value="{$estudiante['id']}" onclick="curso.set_monitor({$estudiante['id']},this.checked, {$curso->id})"></td>
            </tr>
        {/foreach}

        
    </tbody>    
</table>    
{HTML::script('js/curso.js')}

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='estudiantes'}
