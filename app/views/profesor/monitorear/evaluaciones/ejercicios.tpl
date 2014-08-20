{capture assign='content'}
    


    <table class="table table-striped table-bordered table-condensed">
        <thead>
        <th>Nombre</th>
        <th>Tipo de entrada</th>
        <th># estudiantes que lo han resuelto</th>
        <th>Estadísticas</th>
    </thead>
    <tbody>
  
   
    
    
    {foreach $ejercicios as $ejercicio}
        
        <tr>
            <td>{$ejercicio->nombre}</td>
            <td>{if $ejercicio->tipo_entrada == 1}Código{else}solamente out{/if}</td>
            <td> {ejercicio::find($ejercicio->id)->get_numero_estudiantes_resolvieron_en_modulo(1,$evaluacion->id)} </td>
            <td> <a href='{url('curso/monitorear')}/{$curso->id}/evaluaciones/{$evaluacion->id}/ejercicios/{$ejercicio->id}'><img src='{url('img/general/estadisticas.gif')}'></a></td>
        </tr>
        

    {/foreach}

       </tbody>    
</table>  
    
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='evaluaciones'}
